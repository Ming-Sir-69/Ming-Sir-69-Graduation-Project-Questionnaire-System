<?php
/**
 * 行李箱调研系统 - 数据库处理器
 * 处理数据库连接和问卷数据保存
 */

// 数据库连接参数 - 请根据实际配置修改
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'luggage_survey');
define('DB_PORT', '3306');

/**
 * 获取数据库连接
 * @return mysqli|false 成功返回数据库连接对象，失败返回false
 */
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            
            // Check connection
            if (mysqli_connect_errno()) {
                throw new Exception("数据库连接失败: " . mysqli_connect_error());
            }
            
            // Set charset
            mysqli_set_charset($conn, 'utf8mb4');
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log("数据库连接失败: " . mysqli_connect_error() . " (错误码: " . mysqli_connect_errno() . ")");
            return false;
        }
    }
    
    return $conn;
}

/**
 * 开始数据库事务
 * @param mysqli $conn 数据库连接
 * @return bool 是否成功开始事务
 */
function beginTransaction($conn) {
    return mysqli_begin_transaction($conn);
}

/**
 * 提交数据库事务
 * @param mysqli $conn 数据库连接
 * @return bool 是否成功提交事务
 */
function commitTransaction($conn) {
    return mysqli_commit($conn);
}

/**
 * 回滚数据库事务
 * @param mysqli $conn 数据库连接
 * @return bool 是否成功回滚事务
 */
function rollbackTransaction($conn) {
    return mysqli_rollback($conn);
}

/**
 * 锁定会话数据
 * @param mysqli $conn 数据库连接
 * @param string $sessionId 会话ID
 * @return bool 是否成功锁定
 */
function lockSessionData($conn, $sessionId) {
    // 使用锁表方式防止并发操作
    $query = "SELECT GET_LOCK('session_lock_$sessionId', 10) AS lock_result";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['lock_result'] == 1;
    }
    
    return false;
}

/**
 * 解锁会话数据
 * @param mysqli $conn 数据库连接
 * @param string $sessionId 会话ID
 * @return bool 是否成功解锁
 */
function unlockSessionData($conn, $sessionId) {
    $query = "SELECT RELEASE_LOCK('session_lock_$sessionId') AS release_result";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['release_result'] == 1;
    }
    
    return false;
}

/**
 * 保存基本信息到数据库
 * @param array $data 基本信息数据
 * @return array 操作结果
 */
function saveBasicInfoToDb($data) {
    $conn = getDbConnection();
    if (!$conn) {
        return ['success' => false, 'error' => '数据库连接失败'];
    }
    
    // 获取会话ID
    $sessionId = $data['session_id'] ?? session_id();
    
    // 尝试锁定会话数据
    if (!lockSessionData($conn, $sessionId)) {
        return ['success' => false, 'error' => '无法获取数据锁定，可能有其他操作正在进行'];
    }
    
    try {
        // 开始事务
        beginTransaction($conn);
        
        // 检查是否存在记录
        $checkQuery = "SELECT id FROM basic_info WHERE session_id = '$sessionId'";
        $checkResult = mysqli_query($conn, $checkQuery);
        $recordExists = mysqli_num_rows($checkResult) > 0;
        
        // 准备数据
        $gender = mysqli_real_escape_string($conn, $data['gender'] ?? '');
        $ageGroup = mysqli_real_escape_string($conn, $data['age_group'] ?? '');
        $frequency = mysqli_real_escape_string($conn, $data['usage_frequency'] ?? '');
        $count = mysqli_real_escape_string($conn, $data['luggage_count'] ?? '');
        
        if ($recordExists) {
            // 更新现有记录
            $query = "UPDATE basic_info SET 
                        gender = '$gender', 
                        age_group = '$ageGroup', 
                        usage_frequency = '$frequency', 
                        luggage_count = '$count', 
                        updated_at = NOW() 
                      WHERE session_id = '$sessionId'";
        } else {
            // 插入新记录
            $query = "INSERT INTO basic_info (
                        session_id, gender, age_group, usage_frequency, luggage_count, created_at
                      ) VALUES (
                        '$sessionId', '$gender', '$ageGroup', '$frequency', '$count', NOW()
                      )";
        }
        
        // 执行查询
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            // 查询失败，回滚并返回错误
            rollbackTransaction($conn);
            throw new Exception("保存基本信息失败: " . mysqli_error($conn));
        }
        
        // 提交事务
        commitTransaction($conn);
        
        return ['success' => true, 'message' => '基本信息保存成功'];
        
    } catch (Exception $e) {
        // 确保事务回滚
        rollbackTransaction($conn);
        
        error_log($e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    } finally {
        // 释放锁
        unlockSessionData($conn, $sessionId);
    }
}

/**
 * 保存问卷数据到数据库
 * @param int $questionnaireId 问卷ID
 * @param array $data 问卷数据
 * @return array 操作结果
 */
function saveQuestionnaireToDb($questionnaireId, $data) {
    $conn = getDbConnection();
    if (!$conn) {
        return ['success' => false, 'error' => '数据库连接失败'];
    }
    
    // 获取会话ID
    $sessionId = $data['session_id'] ?? session_id();
    
    // 使用事务，但不对每个操作都锁定会话，只在finalizeSubmission中锁定
    try {
        // 开始事务
        beginTransaction($conn);
        
        // 检查是否存在记录
        $checkQuery = "SELECT id FROM questionnaire_responses 
                      WHERE session_id = '$sessionId' AND questionnaire_id = $questionnaireId";
        $checkResult = mysqli_query($conn, $checkQuery);
        $recordExists = mysqli_num_rows($checkResult) > 0;
        
        // 准备数据
        $responseData = mysqli_real_escape_string($conn, json_encode($data, JSON_UNESCAPED_UNICODE));
        $completed = isset($data['completed']) && $data['completed'] ? 1 : 0;
        
        if ($recordExists) {
            // 更新现有记录
            $query = "UPDATE questionnaire_responses SET 
                        response_data = '$responseData', 
                        completed = $completed, 
                        updated_at = NOW() 
                      WHERE session_id = '$sessionId' AND questionnaire_id = $questionnaireId";
        } else {
            // 插入新记录
            $query = "INSERT INTO questionnaire_responses (
                        session_id, questionnaire_id, response_data, completed, created_at
                      ) VALUES (
                        '$sessionId', $questionnaireId, '$responseData', $completed, NOW()
                      )";
        }
        
        // 执行查询
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            // 查询失败，回滚并返回错误
            rollbackTransaction($conn);
            throw new Exception("保存问卷数据失败: " . mysqli_error($conn));
        }
        
        // 提交事务
        commitTransaction($conn);
        
        return ['success' => true, 'message' => '问卷数据保存成功'];
        
    } catch (Exception $e) {
        // 确保事务回滚
        rollbackTransaction($conn);
        
        error_log($e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * 完成问卷提交过程
 * @param string $sessionId 会话ID
 * @return array 操作结果
 */
function finalizeSubmission($sessionId) {
    $conn = getDbConnection();
    if (!$conn) {
        return ['success' => false, 'error' => '数据库连接失败'];
    }
    
    // 在最终提交时锁定会话数据
    if (!lockSessionData($conn, $sessionId)) {
        return ['success' => false, 'error' => '无法获取数据锁定，可能有其他操作正在进行'];
    }
    
    try {
        // 开始事务
        beginTransaction($conn);
        
        // 首先，标记所有会话的问卷为已提交
        $updateQuery = "UPDATE questionnaire_responses 
                       SET is_submitted = 1, submitted_at = NOW() 
                       WHERE session_id = '$sessionId'";
        $updateResult = mysqli_query($conn, $updateQuery);
        
        if (!$updateResult) {
            rollbackTransaction($conn);
            throw new Exception("标记问卷为已提交状态失败: " . mysqli_error($conn));
        }
        
        // 然后记录会话完成状态
        $insertQuery = "INSERT INTO submission_records (
                        session_id, submitted_at, status
                       ) VALUES (
                        '$sessionId', NOW(), 'completed'
                       ) ON DUPLICATE KEY UPDATE 
                        submitted_at = NOW(), 
                        status = 'completed'";
        $insertResult = mysqli_query($conn, $insertQuery);
        
        if (!$insertResult) {
            rollbackTransaction($conn);
            throw new Exception("记录提交状态失败: " . mysqli_error($conn));
        }
        
        // 提交事务
        commitTransaction($conn);
        
        return ['success' => true, 'message' => '问卷提交完成'];
        
    } catch (Exception $e) {
        // 确保事务回滚
        rollbackTransaction($conn);
        
        error_log($e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    } finally {
        // 释放锁
        unlockSessionData($conn, $sessionId);
    }
} 