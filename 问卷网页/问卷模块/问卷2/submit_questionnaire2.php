<?php
// 开始会话，确保能够访问用户会话数据
session_start();

// 引入数据库连接文件
require_once '../../共用资源/php/db_connect.php';

// 调试信息记录
error_log("问卷2提交开始处理 - SESSION ID: " . session_id());
error_log("用户ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '未登录'));

// 检查用户是否已登录
if (!isset($_SESSION['user_id'])) {
    // 未登录，重定向到登录页面
    header('Location: ../../入口界面/index.php');
    exit();
}

// 获取用户ID
$user_id = $_SESSION['user_id'];

// 检查是否是POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 记录提交的POST数据用于调试
        error_log("提交的POST数据: " . print_r($_POST, true));
        
        // 验证必填字段
        $required_fields = ['q3', 'q5']; // 单选题字段
        $required_multi_fields = ['q1', 'q2', 'q6', 'q7', 'q8']; // 多选题字段
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("缺少必填字段: {$field}");
            }
        }
        
        foreach ($required_multi_fields as $field) {
            if (!isset($_POST[$field]) || !is_array($_POST[$field]) || empty($_POST[$field])) {
                error_log("警告: 多选题 {$field} 没有选择");
                // 对于多选题，我们允许空值，但会记录警告
            }
        }
        
        // 获取数据库连接
        $dbc = db_connect();
        if (!$dbc) {
            throw new Exception("无法连接到数据库");
        }
        
        // 获取提交的问卷数据
        
        // 问题1：结构问题（多选题）
        $question1 = '';
        if (isset($_POST['q1']) && is_array($_POST['q1'])) {
            $question1 = implode(',', $_POST['q1']);
        }
        
        // 问题2：身体不适（多选题）
        $question2 = '';
        if (isset($_POST['q2']) && is_array($_POST['q2'])) {
            $question2 = implode(',', $_POST['q2']);
        }
        
        // 问题3：拉杆高度体验（单选题）
        $question3 = isset($_POST['q3']) ? $_POST['q3'] : '';
        
        // 问题4：内部结构改进（排序题）
        $question4 = '[]'; // 默认空JSON数组
        if (isset($_POST['q4_order'])) {
            $question4 = $_POST['q4_order'];
            // 确保是有效的JSON
            if (!json_decode($question4)) {
                error_log("警告: q4_order 不是有效的JSON格式: " . $question4);
                $question4 = '[]';
            }
        }
        
        // 问题5：重量分布（单选题）
        $question5 = isset($_POST['q5']) ? $_POST['q5'] : '';
        
        // 问题6：行李箱功能分工（多选题）
        $question6 = '';
        if (isset($_POST['q6']) && is_array($_POST['q6'])) {
            $question6 = implode(',', $_POST['q6']);
        }
        
        // 问题7：上下楼梯困难（多选题）
        $question7 = '';
        if (isset($_POST['q7']) && is_array($_POST['q7'])) {
            $question7 = implode(',', $_POST['q7']);
        }
        
        // 问题8：结构改进需求（多选题）
        $question8 = '';
        if (isset($_POST['q8']) && is_array($_POST['q8'])) {
            $question8 = implode(',', $_POST['q8']);
        }
        
        // 防SQL注入处理 - 记录原始值用于调试
        error_log("提交到数据库的数据: q1={$question1}, q2={$question2}, q3={$question3}, q4={$question4}, q5={$question5}, q6={$question6}, q7={$question7}, q8={$question8}");
        
        // 开始数据库事务
        mysqli_begin_transaction($dbc);
        
        // 检查是否已存在提交记录
        $check_query = "SELECT submission_id FROM questionnaire2 WHERE user_id = ?";
        $check_stmt = mysqli_prepare($dbc, $check_query);
        if (!$check_stmt) {
            throw new Exception("检查SQL准备阶段错误: " . mysqli_error($dbc));
        }
        
        mysqli_stmt_bind_param($check_stmt, "s", $user_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // 已存在记录，执行更新
            $query = "UPDATE questionnaire2 SET 
                      question1 = ?, question2 = ?, question3 = ?, 
                      question4 = ?, question5 = ?, question6 = ?, 
                      question7 = ?, question8 = ?, submission_date = CURRENT_TIMESTAMP 
                      WHERE user_id = ?";
            $stmt = mysqli_prepare($dbc, $query);
            if (!$stmt) {
                throw new Exception("更新SQL准备阶段错误: " . mysqli_error($dbc));
            }
            
            mysqli_stmt_bind_param($stmt, "sssssssss", $question1, $question2, $question3, $question4, $question5, $question6, $question7, $question8, $user_id);
        } else {
            // 不存在记录，执行插入
            $query = "INSERT INTO questionnaire2 (user_id, question1, question2, question3, question4, question5, question6, question7, question8) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($dbc, $query);
            if (!$stmt) {
                throw new Exception("插入SQL准备阶段错误: " . mysqli_error($dbc));
            }
            
            mysqli_stmt_bind_param($stmt, "sssssssss", $user_id, $question1, $question2, $question3, $question4, $question5, $question6, $question7, $question8);
        }
        
        mysqli_stmt_close($check_stmt);
        
        // 执行SQL并检查是否成功
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("SQL执行错误: " . mysqli_stmt_error($stmt));
        }
        
        // 记录问卷完成状态
        $query = "INSERT INTO questionnaire_completions (user_id, questionnaire_id) VALUES (?, 2)
                  ON DUPLICATE KEY UPDATE completed_at = CURRENT_TIMESTAMP";
        $stmt = mysqli_prepare($dbc, $query);
        if (!$stmt) {
            throw new Exception("完成状态SQL准备错误: " . mysqli_error($dbc));
        }
        
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("完成状态SQL执行错误: " . mysqli_stmt_error($stmt));
        }
        
        // 提交事务
        mysqli_commit($dbc);
        
        // 记录成功信息
        error_log("问卷2数据成功提交 - 用户ID: " . $user_id);
        
        // 重定向到成功页面
        header('Location: ../主页面/主界面.php?success=1');
        exit();
        
    } catch (Exception $e) {
        // 如果出现错误，回滚事务
        if (isset($dbc) && mysqli_ping($dbc)) {
            mysqli_rollback($dbc);
        }
        
        // 记录错误信息
        error_log("问卷2提交错误: " . $e->getMessage());
        
        // 重定向到错误页面
        header('Location: questionnaire2.php?error=1&message=' . urlencode($e->getMessage()));
        exit();
    } finally {
        // 关闭数据库连接
        if (isset($dbc) && mysqli_ping($dbc)) {
            mysqli_close($dbc);
        }
    }
} else {
    // 如果不是POST请求，重定向到问卷页面
    header('Location: questionnaire2.php');
    exit();
}
?>