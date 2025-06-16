<?php
// 开始会话，确保能够访问用户会话数据
session_start();

// 引入数据库连接文件
require_once '../../共用资源/php/db_connect.php';

// 增强调试信息记录
error_log("问卷1提交开始处理 - SESSION ID: " . session_id());
error_log("用户ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '未登录'));
error_log("请求方法: " . $_SERVER['REQUEST_METHOD']);
error_log("SESSION内容: " . print_r($_SESSION, true));

// 检查用户是否已登录
if (!isset($_SESSION['user_id'])) {
    // 未登录，记录错误并重定向到登录页面
    error_log("问卷1提交失败: 用户未登录");
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
        
        // 获取数据库连接
        $dbc = db_connect();
        if (!$dbc) {
            throw new Exception("无法连接到数据库");
        }
        
        // 获取提交的问卷数据
        
        // 问题1：行李箱尺寸（多选题）
        $question1 = '';
        if (isset($_POST['q1']) && is_array($_POST['q1'])) {
            $question1 = implode(',', $_POST['q1']);
        }
        
        // 问题2：行李箱选择因素（排序题）
        $question2 = '[]'; // 默认空JSON数组
        if (isset($_POST['q2_order'])) {
            $question2 = $_POST['q2_order'];
            // 确保是有效的JSON
            if (!json_decode($question2)) {
                $question2 = '[]';
            }
        }
        
        // 问题3：价格范围（单选题）
        $question3 = isset($_POST['q3']) ? $_POST['q3'] : '';
        
        // 问题4：行李箱材质（单选题）
        $question4 = isset($_POST['q4']) ? $_POST['q4'] : '';
        
        // 问题5：行李箱品牌（多选题）
        $question5 = '';
        if (isset($_POST['q5']) && is_array($_POST['q5'])) {
            $question5 = implode(',', $_POST['q5']);
        }
        
        // 问题6：重量偏好（单选题）
        $question6 = isset($_POST['q6']) ? $_POST['q6'] : '';
        
        // 问题7：购买渠道（多选题）
        $question7 = '';
        if (isset($_POST['q7']) && is_array($_POST['q7'])) {
            $question7 = implode(',', $_POST['q7']);
        }
        
        // 开始数据库事务
        mysqli_begin_transaction($dbc);
        
        // 插入问卷1数据
        $query = "INSERT INTO questionnaire1 (user_id, question1, question2, question3, question4, question5, question6, question7) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($dbc, $query);
        if (!$stmt) {
            throw new Exception("SQL准备阶段错误: " . mysqli_error($dbc));
        }
        
        mysqli_stmt_bind_param($stmt, "ssssssss", $user_id, $question1, $question2, $question3, $question4, $question5, $question6, $question7);
        
        // 执行SQL并检查是否成功
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("SQL执行错误: " . mysqli_stmt_error($stmt));
        }
        
        // 记录问卷完成状态
        $query = "INSERT INTO questionnaire_completions (user_id, questionnaire_id) VALUES (?, 1)
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
        error_log("问卷1数据成功提交 - 用户ID: " . $user_id);
        
        // 重定向到成功页面
        header('Location: ../主页面/主界面.php?success=1');
        exit();
        
    } catch (Exception $e) {
        // 如果出现错误，回滚事务
        if (isset($dbc)) {
            mysqli_rollback($dbc);
        }
        
        // 记录错误信息
        error_log("问卷1提交错误: " . $e->getMessage());
        
        // 重定向到错误页面
        header('Location: questionnaire1.php?error=1');
        exit();
    }
} else {
    // 如果不是POST请求，重定向到问卷页面
    header('Location: questionnaire1.php');
    exit();
}

// 关闭数据库连接
if (isset($dbc)) {
    mysqli_close($dbc);
}
?> 