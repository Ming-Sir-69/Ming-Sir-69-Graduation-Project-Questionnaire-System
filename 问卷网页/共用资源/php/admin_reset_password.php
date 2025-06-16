<?php
session_start();
require_once 'db_connect.php';

// 添加调试日志
error_log("密码重置处理开始，会话ID: " . session_id());
error_log("会话中的reset_admin_id: " . (isset($_SESSION['reset_admin_id']) ? $_SESSION['reset_admin_id'] : '未设置'));

// 检查用户是否已通过重置验证
if (!isset($_SESSION['reset_admin_id'])) {
    error_log("错误: 未通过重置验证，重定向到验证页面");
    header("Location: admin_reset_verify.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_id = $_SESSION['reset_admin_id'];

    error_log("收到密码重置请求: admin_id = $admin_id");

    if ($new_password === $confirm_password) {
        // 使用哈希加密新密码
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        error_log("密码哈希创建成功");

        // 连接数据库
        $conn = db_connect();
        if (!$conn) {
            $error = '数据库连接失败，请稍后再试';
            error_log("错误: 数据库连接失败");
        } else {
            error_log("数据库连接成功");

            // 打印SQL查询和参数，验证表结构
            error_log("SQL执行前检查 - 表: administrators, 字段: password_hash, 条件: admin_id = $admin_id");
            
            // 检查administrators表结构
            $check_table_sql = "DESCRIBE administrators";
            $check_result = $conn->query($check_table_sql);
            if ($check_result) {
                while ($row = $check_result->fetch_assoc()) {
                    error_log("表结构: 字段名=" . $row['Field'] . ", 类型=" . $row['Type']);
                }
            } else {
                error_log("无法检查表结构: " . $conn->error);
            }
            
            // 检查管理员ID是否存在
            $check_admin_sql = "SELECT admin_id FROM administrators WHERE admin_id = ?";
            $check_admin_stmt = $conn->prepare($check_admin_sql);
            $check_admin_stmt->bind_param("i", $admin_id);
            $check_admin_stmt->execute();
            $check_admin_result = $check_admin_stmt->get_result();
            
            error_log("检查管理员ID存在性: " . ($check_admin_result->num_rows > 0 ? "存在" : "不存在"));
            $check_admin_stmt->close();

            // 更新数据库中的密码
            $sql = "UPDATE administrators SET password_hash = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $admin_id);
            
            error_log("准备执行SQL更新: " . $sql);
            error_log("哈希密码(部分): " . substr($hashed_password, 0, 10) . "...");
            
            $update_result = $stmt->execute();
            $affected_rows = $stmt->affected_rows;
            
            error_log("SQL执行结果: " . ($update_result ? "成功" : "失败") . ", 受影响行数: $affected_rows");
            
            if ($update_result && $affected_rows > 0) {
                // 记录密码重置操作
                $log_sql = "INSERT INTO admin_logs (admin_id, action_type, target_table, action_details) 
                          VALUES (?, '更新', 'administrators', '管理员密码重置')";
                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("i", $admin_id);
                $log_result = $log_stmt->execute();
                error_log("记录密码重置日志: " . ($log_result ? "成功" : "失败"));
                $log_stmt->close();
                
                $stmt->close();
                $conn->close();
                
                // 销毁会话
                error_log("密码重置成功，销毁会话");
                session_unset();
                session_destroy();
                
                header("Location: ../../入口界面/index.php?message=" . urlencode("密码重置成功，请重新登录"));
                exit();
            } else {
                $stmt->close();
                $conn->close();
                $error = "密码重置失败，请重试 (错误码: " . ($update_result ? "0" : "1") . ")";
                error_log("错误: 密码重置失败, MySQL错误: " . $conn->error);
            }
        }
    } else {
        $error = "两次输入的密码不匹配";
        error_log("错误: 两次输入的密码不匹配");
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员重置密码</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .btn-primary {
            background-color: #38b2ac;
            border-color: #38b2ac;
            width: 100%;
            padding: 10px;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 40px;
            cursor: pointer;
        }
        .validity-indicator {
            position: absolute;
            right: 40px;
            top: 40px;
        }
        .password-hint {
            display: none;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>管理员重置密码</h1>
    </div>
    <div class="container">
        <div class="form-container">
            <form action="admin_reset_password.php" method="post" oninput="validatePassword(); confirmPasswordMatch();">
                <div class="form-group">
                    <label for="password" class="form-label">新密码</label>
                    <input type="password" id="password" name="password" class="form-control" maxlength="15" pattern="[a-zA-Z0-9@&#]{6,15}" required onfocus="showHint()" onblur="hideHint()">
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                    <span id="password-validity" class="validity-indicator"></span>
                    <div id="password-hint" class="password-hint">密码只能包含a~z、A~Z、0~9、@、&、#这些符号，长度为6-15位。</div>
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="form-label">再次确认密码</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" maxlength="15" required>
                    <span class="password-toggle" onclick="togglePassword('confirm_password')">👁️</span>
                    <span id="confirm-password-validity" class="validity-indicator"></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">重置密码</button>
                </div>
                <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            field.type = (field.type === 'password') ? 'text' : 'password';
        }
        
        function validatePassword() {
            const password = document.getElementById('password');
            const validityIndicator = document.getElementById('password-validity');
            const pattern = /^[a-zA-Z0-9@&#]{6,15}$/;
            
            if (password.value.length === 0) {
                validityIndicator.textContent = '';
            } else if (pattern.test(password.value)) {
                validityIndicator.textContent = '✓';
                validityIndicator.style.color = 'green';
            } else {
                validityIndicator.textContent = '✗';
                validityIndicator.style.color = 'red';
            }
        }
        
        function confirmPasswordMatch() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const validityIndicator = document.getElementById('confirm-password-validity');
            
            if (confirmPassword.value.length === 0) {
                validityIndicator.textContent = '';
            } else if (password.value === confirmPassword.value) {
                validityIndicator.textContent = '✓';
                validityIndicator.style.color = 'green';
            } else {
                validityIndicator.textContent = '✗';
                validityIndicator.style.color = 'red';
            }
        }
        
        function showHint() {
            document.getElementById('password-hint').style.display = 'block';
        }
        
        function hideHint() {
            document.getElementById('password-hint').style.display = 'none';
        }
    </script>
</body>
</html> 