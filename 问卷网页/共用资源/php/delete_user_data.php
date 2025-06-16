<?php
/**
 * 行李箱调研系统 - 删除用户数据处理器
 * 负责删除用户基本信息和问卷数据
 */

// 引入会话启动文件
require_once('session_start.php');

// 引入数据库连接文件
require_once('db_connect.php');

// 设置响应类型为JSON
header('Content-Type: application/json');

// 检查会话中是否有用户ID
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => '未找到用户信息，请重新填写基本信息'
    ]);
    exit;
}

// 获取用户ID
$user_id = $_SESSION['user_id'];

// 连接数据库
$dbc = db_connect();

if (!$dbc) {
    echo json_encode([
        'success' => false,
        'message' => '数据库连接失败，请稍后再试'
    ]);
    exit;
}

// 开始事务，确保数据完整性
mysqli_autocommit($dbc, false);
$success = true;
$errors = [];

try {
    // 1. 删除用户基本信息
    $query = "DELETE FROM basic_info WHERE user_id = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        $success = false;
        $errors[] = "删除基本信息失败: " . mysqli_error($dbc);
    }
    
    // 2. 删除用户问卷数据 (如果有相关表)
    // 以下是示例，实际需要根据数据库表结构调整
    $tables = ['questionnaire1_responses', 'questionnaire2_responses', 'questionnaire3_responses'];
    
    foreach ($tables as $table) {
        // 检查表是否存在
        $table_check = mysqli_query($dbc, "SHOW TABLES LIKE '$table'");
        
        if (mysqli_num_rows($table_check) > 0) {
            $query = "DELETE FROM $table WHERE user_id = ?";
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, 's', $user_id);
            
            if (!mysqli_stmt_execute($stmt)) {
                $success = false;
                $errors[] = "删除 $table 数据失败: " . mysqli_error($dbc);
            }
        }
    }
    
    // 如果所有操作成功，提交事务
    if ($success) {
        mysqli_commit($dbc);
        
        // 清除会话中的用户ID
        unset($_SESSION['user_id']);
        
        echo json_encode([
            'success' => true,
            'message' => '用户数据已成功删除，请重新填写基本信息'
        ]);
    } else {
        // 如果有任何错误，回滚事务
        mysqli_rollback($dbc);
        
        echo json_encode([
            'success' => false,
            'message' => '删除用户数据时发生错误',
            'errors' => $errors
        ]);
    }
} catch (Exception $e) {
    // 捕获任何异常，回滚事务
    mysqli_rollback($dbc);
    
    echo json_encode([
        'success' => false,
        'message' => '系统错误，请稍后再试',
        'error' => $e->getMessage()
    ]);
}

// 关闭数据库连接
mysqli_close($dbc);
?> 