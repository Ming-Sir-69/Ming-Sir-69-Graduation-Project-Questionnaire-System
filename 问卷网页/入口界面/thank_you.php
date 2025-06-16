<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>感谢参与调研 - 行李箱用户体验调研</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 基础样式 -->
    <link rel="stylesheet" href="../共用资源/css/base/style.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .thank-you-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .success-icon {
            font-size: 5rem;
            color: #1ebe5e;
            margin-bottom: 2rem;
        }
        
        .thank-you-text {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .back-home-btn {
            margin-top: 2rem;
        }
    </style>
</head>
<body class="light-theme">
    <div class="container-fluid bg-gradient min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="card shadow-lg thank-you-container">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">行李箱用户体验调研</h1>
            </div>
            
            <div class="card-body p-5 text-center">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                
                <h2 class="thank-you-text fw-bold">感谢您的参与!</h2>
                
                <p class="lead">
                    您的反馈对我们非常宝贵，将帮助我们改进行李箱设计并提升用户体验。
                </p>
                
                <p>
                    您的调研数据已成功提交，我们将认真分析每一份反馈。
                </p>
                
                <button class="btn btn-primary btn-lg back-home-btn" onclick="window.location.href='index.php'">
                    <i class="fas fa-home me-2"></i>返回首页
                </button>
            </div>
            
            <div class="card-footer bg-light text-center py-3">
                <p class="mb-0 small">© 2024 行李箱用户体验调研项目</p>
            </div>
        </div>
    </div>

    <!-- JavaScript 库 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 防止用户通过后退按钮返回到问卷页面
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
</body>
</html> 