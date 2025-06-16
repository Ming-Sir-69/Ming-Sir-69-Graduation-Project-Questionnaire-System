<?php
/**
 * 行李箱调研系统 - 会话结束处理脚本
 * 用于安全地关闭用户会话并记录相关日志
 */

// 设置响应头
header('Content-Type: application/json');

// 开始会话处理
session_start();

// 获取当前会话ID用于日志记录
$session_id = session_id();

// 收集必要的数据用于报告或通知
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'unknown';
$completion_time = date('Y-m-d H:i:s');

// 记录会话结束日志
error_log("问卷调研完成 - 会话ID: $session_id, 用户ID: $user_id, 完成时间: $completion_time");

// 标记已完成的问卷（如果需要）
if (isset($_SESSION['questionnaire_1']) && isset($_SESSION['questionnaire_1']['completed']) && $_SESSION['questionnaire_1']['completed']) {
    error_log("用户 $user_id 完成了问卷1");
}
if (isset($_SESSION['questionnaire_2']) && isset($_SESSION['questionnaire_2']['completed']) && $_SESSION['questionnaire_2']['completed']) {
    error_log("用户 $user_id 完成了问卷2");
}
if (isset($_SESSION['questionnaire_3']) && isset($_SESSION['questionnaire_3']['completed']) && $_SESSION['questionnaire_3']['completed']) {
    error_log("用户 $user_id 完成了问卷3");
}

// 安全地销毁会话
$_SESSION = array(); // 清空会话数组

// 如果使用了会话cookie，也销毁它
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 最后销毁会话
session_destroy();

// 返回成功响应
echo json_encode([
    'success' => true,
    'message' => '会话已成功结束',
    'completion_time' => $completion_time
]); 