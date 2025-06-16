<?php
session_start();
require_once 'db_connect.php';

// 添加调试日志
error_log("管理员重置验证页面加载，会话ID: " . session_id());

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // 添加日志记录
    error_log("管理员重置验证 - 用户名: $username, 邮箱: $email", 0);

    // 查询管理员信息
    $conn = db_connect();
    if (!$conn) {
        $error = '数据库连接失败，请稍后再试';
        error_log("错误: 数据库连接失败");
    } else {
        error_log("数据库连接成功");
        
        $sql = "SELECT admin_id FROM administrators WHERE username = ? AND email = ?";
        error_log("执行SQL查询: " . $sql);
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        error_log("查询结果行数: " . $result->num_rows);
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $stmt->close(); // 关闭准备语句
            $conn->close();
            
            error_log("管理员身份验证成功，admin_id: " . $admin['admin_id']);

            // 设置会话变量
            $_SESSION['reset_admin_id'] = $admin['admin_id'];
            error_log("会话变量设置: reset_admin_id = " . $_SESSION['reset_admin_id']);

            header("Location: admin_reset_password.php"); // 跳转到重置密码界面
            exit();
        } else {
            $stmt->close(); // 关闭准备语句
            $conn->close();
            error_log("错误: 用户名或邮箱不匹配");
            $error = '用户名或邮箱不匹配';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员重置验证</title>
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
        }
        .btn-primary {
            background-color: #38b2ac;
            border-color: #38b2ac;
            width: 100%;
            padding: 10px;
        }
        .btn-secondary {
            background-color: #718096;
            border-color: #718096;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        .popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>管理员重置验证</h1>
    </div>
    <div class="container">
        <div class="form-container">
            <form action="admin_reset_verify.php" method="post">
                <div class="form-group">
                    <label for="username" class="form-label">用户名</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">邮箱</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">开始重置</button>
                    <a href="../../入口界面/index.php" class="btn btn-secondary text-white text-decoration-none">返回</a>
                </div>
                <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div id="popup" class="popup"></div>

    <script>
        function showPopup(message) {
            const popup = document.getElementById('popup');
            popup.textContent = message;
            popup.style.display = 'block';
            
            setTimeout(function() {
                popup.style.display = 'none';
            }, 3000);
        }
        
        <?php if (isset($error)): ?>
        showPopup("<?php echo htmlspecialchars($error); ?>");
        <?php endif; ?>
    </script>
</body>
</html> 