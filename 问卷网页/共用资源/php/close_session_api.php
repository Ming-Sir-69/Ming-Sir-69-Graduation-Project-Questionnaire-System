<?php
/**
 * 行李箱调研系统 - 会话关闭API
 * 用于通过AJAX请求关闭用户会话
 */

// 设置响应头
header('Content-Type: application/json');

// 启动会话
session_start();

// 获取当前会话ID用于日志记录
$session_id = session_id();

// 收集必要的数据用于报告或通知
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'unknown';
$completion_time = date('Y-m-d H:i:s');

// 记录会话结束日志
error_log("问卷调研完成 - 会话ID: $session_id, 用户ID: $user_id, 完成时间: $completion_time");

// 记录已完成的问卷（如果需要）
if (isset($_SESSION['questionnaire_1']) && isset($_SESSION['questionnaire_1']['completed']) && $_SESSION['questionnaire_1']['completed']) {
    error_log("用户 $user_id 完成了问卷1");
}
if (isset($_SESSION['questionnaire_2']) && isset($_SESSION['questionnaire_2']['completed']) && $_SESSION['questionnaire_2']['completed']) {
    error_log("用户 $user_id 完成了问卷2");
}
if (isset($_SESSION['questionnaire_3']) && isset($_SESSION['questionnaire_3']['completed']) && $_SESSION['questionnaire_3']['completed']) {
    error_log("用户 $user_id 完成了问卷3");
}

// 引入会话关闭工具
require_once('session_close.php');

// 调用会话关闭函数
$result = close_session(true);

// 返回操作结果
echo json_encode([
    'success' => $result,
    'message' => $result ? '会话已成功结束' : '会话结束失败',
    'completion_time' => $completion_time
]); 