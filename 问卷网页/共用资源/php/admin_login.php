<?php
/**
 * 行李箱调研系统 - 管理员登录处理
 */

// 启动会话
session_start();

// 调试信息记录
error_log("管理员登录处理开始，请求方法: " . $_SERVER['REQUEST_METHOD']);

// 引入数据库连接文件
require_once 'db_connect.php';

// 初始化响应
$error = '';

// 检查是否是POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取表单数据
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // 记录收到的用户名（不记录密码）
    error_log("收到登录请求: 用户名 = '$username'");
    
    // 验证输入不为空
    if (empty($username) || empty($password)) {
        $error = '请输入用户名和密码';
        error_log("错误: 用户名或密码为空");
    } else {
        // 连接数据库
        $conn = db_connect();
        
        if (!$conn) {
            $error = '数据库连接失败，请稍后再试';
            error_log("错误: 数据库连接失败");
        } else {
            error_log("数据库连接成功");
            
            // 查询用户信息
            $sql = "SELECT admin_id, username, password_hash, email FROM administrators WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            error_log("查询执行完成，结果行数: " . $result->num_rows);
            
            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();
                error_log("找到管理员: ID = " . $admin['admin_id']);
                
                // 验证密码
                $password_correct = password_verify($password, $admin['password_hash']);
                error_log("密码验证结果: " . ($password_correct ? "成功" : "失败"));
                
                if ($password_correct) {
                    // 密码正确，设置会话
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['is_admin'] = true;
                    $_SESSION['admin_last_activity'] = time();
                    
                    error_log("会话变量设置完成: admin_id = " . $_SESSION['admin_id']);
                    
                    // 更新最后登录时间
                    $update_sql = "UPDATE administrators SET last_login = NOW() WHERE admin_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("i", $admin['admin_id']);
                    $update_result = $update_stmt->execute();
                    error_log("更新最后登录时间: " . ($update_result ? "成功" : "失败"));
                    $update_stmt->close();
                    
                    // 记录登录操作
                    $log_sql = "INSERT INTO admin_logs (admin_id, action_type, target_table, action_details) 
                              VALUES (?, '登录', 'administrators', '管理员登录成功')";
                    $log_stmt = $conn->prepare($log_sql);
                    $log_stmt->bind_param("i", $admin['admin_id']);
                    $log_result = $log_stmt->execute();
                    error_log("记录登录日志: " . ($log_result ? "成功" : "失败"));
                    $log_stmt->close();
                    
                    // 关闭数据库连接
                    $conn->close();
                    
                    // 清除任何错误信息
                    unset($_SESSION['admin_login_error']);
                    unset($_SESSION['show_admin_modal']);
                    
                    // 重定向到管理页面
                    error_log("登录成功，重定向到管理页面");
                    header("Location: ../../问卷模块/管理页面/index.php");
                    exit();
                } else {
                    $error = '用户名或密码错误';
                }
            } else {
                $error = '用户名或密码错误';
                error_log("错误: 用户名不存在");
            }
            
            // 关闭语句和数据库连接
            $stmt->close();
            $conn->close();
        }
    }
}

// 如果有错误，使用会话变量存储错误信息，而不是URL参数
if (!empty($error)) {
    error_log("登录失败，错误信息: $error，设置会话变量");
    
    // 将错误信息存储在会话中
    $_SESSION['admin_login_error'] = $error;
    
    // 设置标志，表示应该显示管理员登录模态框
    $_SESSION['show_admin_modal'] = true;
    
    // 重定向回登录页面
    header("Location: ../../入口界面/index.php");
    exit();
}
?> 