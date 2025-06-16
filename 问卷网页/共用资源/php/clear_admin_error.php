<?php
/**
 * 清除管理员登录错误信息
 * 当管理员登录模态框关闭时调用此脚本，清除存储在会话中的错误信息
 */

// 启动会话
session_start();

// 清除管理员登录错误相关的会话变量
if (isset($_SESSION['admin_login_error'])) {
    unset($_SESSION['admin_login_error']);
    error_log("已清除管理员登录错误信息");
}

if (isset($_SESSION['show_admin_modal'])) {
    unset($_SESSION['show_admin_modal']);
    error_log("已清除管理员模态框显示标志");
}

// 返回成功状态
header('Content-Type: application/json');
echo json_encode(['status' => 'success']);
?>
