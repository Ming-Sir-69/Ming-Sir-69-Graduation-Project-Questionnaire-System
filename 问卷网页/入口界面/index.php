<?php
// 检查是否是从提交页面返回并需要结束会话
if (isset($_GET['session_end']) && $_GET['session_end'] == 1) {
    // 销毁会话
    session_start();
    session_unset();
    session_destroy();
    
    // 记录日志
    error_log("用户会话已结束: " . session_id());
    
    // 重定向到干净的入口页面以避免刷新导致反复执行此逻辑
    header("Location: index.php?completed=1");
    exit;
}

// 检查是否显示完成消息
$showCompletionMessage = isset($_GET['completed']) && $_GET['completed'] == 1;
$showSuccessMessage = isset($_GET['message']) ? $_GET['message'] : '';

// 引入会话启动文件
require_once('../共用资源/php/session_start.php');

// 引入数据库连接文件
require_once('../共用资源/php/db_connect.php');

// 检查是否是POST请求（表单提交）
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 连接数据库
    $dbc = db_connect();
    
    if ($dbc) {
        // 获取表单数据
        $gender = $_POST['gender'];
        $ageGroup = $_POST['ageGroup'];
        $frequency = $_POST['frequency'];
        $luggageCount = $_POST['luggageCount'];
        
        // 防SQL注入处理
        $gender = mysqli_real_escape_string($dbc, $gender);
        $ageGroup = mysqli_real_escape_string($dbc, $ageGroup);
        $frequency = mysqli_real_escape_string($dbc, $frequency);
        $luggageCount = mysqli_real_escape_string($dbc, $luggageCount);
        
        // 生成唯一用户ID (可用于问卷关联)
        $userId = uniqid('user_');
        
        // 插入数据库
        $query = "INSERT INTO basic_info (user_id, gender, age_group, usage_frequency, luggage_count, created_at) 
                 VALUES ('$userId', '$gender', '$ageGroup', '$frequency', '$luggageCount', NOW())";
        
        if (mysqli_query($dbc, $query)) {
            // 数据保存成功，将用户ID存入会话
            $_SESSION['user_id'] = $userId;
            
            // 检查是否需要传递高可读性模式参数
            $redirectUrl = '../问卷模块/主页面/主界面.php';
            
            // 检查cookie中是否存在高可读性模式设置
            if (isset($_COOKIE['luggage_survey_accessibility'])) {
                $settings = json_decode($_COOKIE['luggage_survey_accessibility'], true);
                if (isset($settings['isHighReadabilityMode']) && $settings['isHighReadabilityMode'] === true) {
                    $redirectUrl .= '?highReadability=true';
                }
            } else if (isset($_POST['highReadabilityMode']) && $_POST['highReadabilityMode'] == 'true') {
                // 如果表单中包含高可读性模式参数
                $redirectUrl .= '?highReadability=true';
            }
            
            // 重定向到问卷选择页面
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            // 保存失败，设置错误信息
            $error_message = "数据保存失败：" . mysqli_error($dbc);
        }
        
        // 关闭数据库连接
        mysqli_close($dbc);
    } else {
        $error_message = "数据库连接失败，请稍后再试";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行李箱用户体验与优化需求调研</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 基础样式 -->
    <link rel="stylesheet" href="../共用资源/css/base/style.css">
    <!-- 页面专用样式 -->
    <link rel="stylesheet" href="../共用资源/css/pages/entry.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="light-theme">
    <div class="container-fluid bg-gradient min-vh-100 d-flex align-items-center justify-content-center p-3">
        <!-- 背景浮动元素 -->
        <div class="bg-floating-elements">
            <!-- 随机形状元素将通过JS动态生成 -->
        </div>
        
        <!-- 高可读性模式按钮 -->
        <div class="accessibility-toggle-container">
            <button id="accessibilityToggleButton" class="btn accessibility-toggle-btn" title="高可读性模式">
                <i class="fas fa-glasses"></i>
            </button>
        </div>
        
        <!-- 管理员登录按钮 -->
        <div class="admin-login-container">
            <button id="adminLoginButton" class="btn admin-login-btn theme-aware-btn" data-bs-toggle="modal" data-bs-target="#adminLoginModal">
                <i class="fas fa-lock"></i> 管理员入口
            </button>
        </div>
        
        <div class="card survey-card shadow-lg">
            <!-- 页面头部 -->
            <div class="card-header bg-primary text-white">
                <h1 class="text-center mb-0">行李箱用户体验与优化需求调研</h1>
            </div>
            
            <!-- 进度指示器 -->
            <div class="progress rounded-0" style="height: 8px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="card-body">
                <!-- 显示完成问卷的感谢信息 -->
                <?php if (isset($showCompletionMessage) && $showCompletionMessage): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <strong><i class="fas fa-check-circle me-2"></i>感谢您！</strong> 您的问卷已成功提交。我们非常感谢您参与行李箱用户体验调研。
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- 显示错误信息 -->
                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong>提交失败：</strong> <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- 感谢参与信息 -->
                <?php if ($showCompletionMessage): ?>
                <div class="completion-message">
                    <div class="alert alert-success text-center p-4 shadow-sm fade-in">
                        <h4 class="mb-3"><i class="fas fa-check-circle me-2"></i>感谢您的参与</h4>
                        <p class="mb-0">您的问卷已提交成功，我们非常感谢您的宝贵意见。</p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- 成功消息提示 -->
                <?php if (!empty($showSuccessMessage)): ?>
                <div class="success-message" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
                    <div class="alert alert-success shadow-sm fade-in" style="min-width: 250px; max-width: 400px;">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($showSuccessMessage); ?>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.success-message').style.display = 'none';
                    }, 5000);
                </script>
                <?php endif; ?>
                
                <!-- 欢迎信息 -->
                <div class="welcome-section text-center mb-4">
                    <div class="luggage-icon mb-3">
                        <i class="fas fa-suitcase-rolling fa-4x text-primary"></i>
                    </div>
                    <h2 class="fw-bold">欢迎参与调研</h2>
                    <p class="lead fw-normal">我们正在收集用户对行李箱的使用体验和改进需求，您的反馈将帮助我们设计更好的产品。</p>
                    <p class="fw-medium"><strong>预计完成时间：</strong>8-10分钟（可选择部分问卷填写）</p>
                </div>

                <!-- 基本信息表单 -->
                <form id="basicInfoForm" class="needs-validation" action="index.php" method="POST" novalidate>
                    <!-- 隐藏字段，存储高可读性模式状态 -->
                    <input type="hidden" name="highReadabilityMode" id="highReadabilityMode" value="false">
                    
                    <div class="row g-4">
                        <!-- 性别选择 -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-medium">1. 您的性别：<span class="text-danger">*</span></label>
                                <div class="gender-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                        <label class="form-check-label gender-card" for="male">
                                            <i class="fas fa-mars"></i>
                                            <span>男</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                                        <label class="form-check-label gender-card" for="female">
                                            <i class="fas fa-venus"></i>
                                            <span>女</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="other" required>
                                        <label class="form-check-label gender-card" for="other">
                                            <i class="fas fa-genderless"></i>
                                            <span>不愿透露</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">请选择您的性别</div>
                            </div>
                        </div>

                        <!-- 年龄段选择 -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-medium">2. 您的年龄段：<span class="text-danger">*</span></label>
                                <div class="age-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ageGroup" id="age1" value="18-25" required>
                                        <label class="form-check-label age-card" for="age1">
                                            <i class="fas fa-user-graduate"></i>
                                            <span>18-25岁</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ageGroup" id="age2" value="26-35" required>
                                        <label class="form-check-label age-card" for="age2">
                                            <i class="fas fa-briefcase"></i>
                                            <span>26-35岁</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ageGroup" id="age3" value="36-45" required>
                                        <label class="form-check-label age-card" for="age3">
                                            <i class="fas fa-user-tie"></i>
                                            <span>36-45岁</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ageGroup" id="age4" value="46-60" required>
                                        <label class="form-check-label age-card" for="age4">
                                            <i class="fas fa-user-friends"></i>
                                            <span>46-60岁</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ageGroup" id="age5" value="60+" required>
                                        <label class="form-check-label age-card" for="age5">
                                            <i class="fas fa-user-plus"></i>
                                            <span>60岁以上</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">请选择您的年龄段</div>
                            </div>
                        </div>

                        <!-- 使用频率 -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-medium">3. 您多久使用一次行李箱？<span class="text-danger">*</span></label>
                                <div class="frequency-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frequency" id="freq1" value="weekly" required>
                                        <label class="form-check-label frequency-card" for="freq1">
                                            <i class="fas fa-calendar-day"></i>
                                            <span>一周多次</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frequency" id="freq2" value="monthly" required>
                                        <label class="form-check-label frequency-card" for="freq2">
                                            <i class="fas fa-calendar-week"></i>
                                            <span>一个月1-2次</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frequency" id="freq3" value="quarterly" required>
                                        <label class="form-check-label frequency-card" for="freq3">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>一季度1-2次</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frequency" id="freq4" value="biannually" required>
                                        <label class="form-check-label frequency-card" for="freq4">
                                            <i class="fas fa-hourglass-half"></i>
                                            <span>半年1-2次</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frequency" id="freq5" value="yearly" required>
                                        <label class="form-check-label frequency-card" for="freq5">
                                            <i class="fas fa-hourglass-end"></i>
                                            <span>一年不到1次</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">请选择使用频率</div>
                            </div>
                        </div>

                        <!-- 拥有数量 -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-medium">4. 您拥有几个行李箱？<span class="text-danger">*</span></label>
                                <div class="count-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="luggageCount" id="count1" value="1" required>
                                        <label class="form-check-label count-card" for="count1">
                                            <i class="fas fa-suitcase"></i>
                                            <span>1个</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="luggageCount" id="count2" value="2-3" required>
                                        <label class="form-check-label count-card" for="count2">
                                            <i class="fas fa-suitcase-rolling"></i>
                                            <span>2-3个</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="luggageCount" id="count3" value="4-5" required>
                                        <label class="form-check-label count-card" for="count3">
                                            <i class="fas fa-luggage-cart"></i>
                                            <span>4-5个</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="luggageCount" id="count4" value="5+" required>
                                        <label class="form-check-label count-card" for="count4">
                                            <i class="fas fa-cubes"></i>
                                            <span>5个以上</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="invalid-feedback">请选择拥有数量</div>
                            </div>
                        </div>
                    </div>

                    <!-- 提交按钮区域 -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-arrow-right me-2"></i>开始调研
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- 页脚信息 -->
            <div class="card-footer bg-light text-center py-3">
                <p class="mb-0 small footer-copyright">© 2025 行李箱用户体验调研项目 | 隐私政策</p>
            </div>
        </div>
    </div>

    <!-- 调研说明模态框 -->
    <div class="modal fade" id="surveyInfoModal" tabindex="-1" aria-labelledby="surveyInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="surveyInfoModalLabel">关于本次调研</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>本次调研旨在了解用户对行李箱的使用体验与期望改进。您的反馈将帮助我们设计更符合用户需求的产品。</p>
                    <p>调研共分为三个问卷模块，您可以根据自己的时间和兴趣选择填写：</p>
                    <ol>
                        <li><strong>行李箱购买决策与基本需求调研</strong> - 关注市场因素</li>
                        <li><strong>行李箱结构与使用体验调研</strong> - 关注使用体验</li>
                        <li><strong>行李箱功能创新与痛点改进调研</strong> - 关注创新需求</li>
                    </ol>
                    <p>所有数据仅用于学术研究，我们将严格保护您的隐私。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">我知道了</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 管理员登录模态框 -->
    <div class="modal fade" id="adminLoginModal" tabindex="-1" aria-labelledby="adminLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminLoginModalLabel">管理员登录</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="adminLoginForm" action="../共用资源/php/admin_login.php" method="post">
                        <div class="mb-3">
                            <label for="adminUsername" class="form-label">用户名</label>
                            <input type="text" class="form-control" id="adminUsername" name="username" placeholder="请输入用户名" autocomplete="username">
                        </div>
                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">密码</label>
                            <input type="password" class="form-control" id="adminPassword" name="password" placeholder="请输入密码" autocomplete="current-password">
                        </div>
                        <?php if (isset($_SESSION['admin_login_error'])): ?>
                        <div class="alert alert-danger" id="loginErrorMsg">
                            <?php echo htmlspecialchars($_SESSION['admin_login_error']); ?>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-danger" id="loginErrorMsg" style="display: none;"></div>
                        <?php endif; ?>
                        <div class="text-end mt-2">
                            <a href="../共用资源/php/admin_reset_verify.php" class="text-decoration-none">忘记密码?</a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" form="adminLoginForm" class="btn btn-primary" id="adminLoginBtn" style="background-color: #38b2ac !important; border-color: #38b2ac !important; color: white !important; font-weight: 500; padding: 8px 20px; border-radius: 8px;">登录</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript 库 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    
    <!-- 工具脚本 -->
    <script src="../共用资源/js/utils/theme.js"></script>
    <script src="../共用资源/js/utils/accessibility.js"></script>
    <script src="../共用资源/js/utils/storage.js"></script>
    <script src="../共用资源/js/utils/session.js"></script>
    
    <!-- 登录表单验证 -->
    <script src="../共用资源/js/pages/login.js"></script>
    
    <!-- 页面脚本 -->
    <!-- 注释掉admin-auth.js，改用PHP后端验证 -->
    <!-- <script src="../共用资源/js/auth/admin-auth.js"></script> -->
    <script src="../共用资源/js/pages/entry.js"></script>
    
    <!-- 初始化脚本 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 检查高可读性模式状态并更新隐藏字段
            try {
                const settings = localStorage.getItem('luggage_survey_accessibility');
                if (settings) {
                    const parsedSettings = JSON.parse(settings);
                    if (parsedSettings.isHighReadabilityMode) {
                        document.getElementById('highReadabilityMode').value = 'true';
                    }
                } else if (document.body.classList.contains('high-readability')) {
                    document.getElementById('highReadabilityMode').value = 'true';
                }
            } catch (error) {
                console.error('Failed to check accessibility settings:', error);
            }
            
            // 清除之前的会话和用户数据
            if (typeof SurveyStorage !== 'undefined') {
                // 清除基本信息数据
                SurveyStorage.clearBasicInfo();
                
                // 如果是新的会话或者需要清除所有数据，可以使用以下代码
                if (window.location.search.includes('newUser=true')) {
                    SurveyStorage.clearAllData();
                }
            }
            
            // 初始化可访问性功能
            if (typeof AccessibilityManager !== 'undefined') {
                AccessibilityManager.init();
                
                // 监听可访问性模式变化，更新隐藏字段
                document.addEventListener('accessibilityChanged', function(e) {
                    if (e.detail && typeof e.detail.isHighReadabilityMode !== 'undefined') {
                        document.getElementById('highReadabilityMode').value = 
                            e.detail.isHighReadabilityMode ? 'true' : 'false';
                    }
                });
            }
            
            // 生成背景浮动元素
            createBackgroundElements();
            
            // 检查是否需要切换到高可读性模式
            checkHighReadabilityParam();
            
            // 初始化表单提交处理
            initializeFormSubmission();
        });
        
        // 检查URL参数是否包含高可读性模式标记
        function checkHighReadabilityParam() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('highReadability') && urlParams.get('highReadability') === 'true') {
                // 如果URL中指定了高可读性模式
                document.body.classList.add('high-readability');
                const accessibilityButton = document.getElementById('accessibilityToggleButton');
                if (accessibilityButton) {
                    accessibilityButton.classList.add('active');
                    accessibilityButton.setAttribute('aria-pressed', 'true');
                }
                
                // 如果存在AccessibilityManager，更新它的状态
                if (typeof AccessibilityManager !== 'undefined') {
                    AccessibilityManager.isHighReadabilityMode = true;
                    AccessibilityManager.saveSettings();
                }
            }
        }
        
        // 创建背景浮动元素
        function createBackgroundElements() {
            const container = document.querySelector('.bg-floating-elements');
            const colors = [
                'var(--accent1)', 'var(--accent2)', 'var(--accent3)', 
                'var(--accent4)', 'var(--accent5)', 'var(--primary-color)',
                'var(--secondary-color)', 'var(--info-color)'
            ];
            const shapes = ['circle', 'square', 'triangle', 'icon'];
            const icons = [
                'suitcase', 'suitcase-rolling', 'luggage-cart', 'plane', 
                'train', 'subway', 'ship', 'car', 'bus', 'taxi',
                'map-marker-alt', 'passport', 'ticket-alt', 'map',
                'compass', 'route', 'hotel', 'umbrella-beach',
                'briefcase', 'backpack', 'shopping-bag', 'hiking'
            ];
            
            // 清空容器
            container.innerHTML = '';
            
            // 获取容器尺寸
            const containerWidth = container.offsetWidth;
            const containerHeight = container.offsetHeight;
            
            // 创建更多浮动元素
            for (let i = 0; i < 35; i++) {
                const element = document.createElement('div');
                element.className = 'floating-element';
                
                // 增加图标概率
                const shapeIndex = Math.random() < 0.6 ? 3 : Math.floor(Math.random() * 3);
                const shape = shapes[shapeIndex];
                
                // 随机位置，但限制在容器内
                const posX = Math.random() * 80 + 10; // 10% 到 90% 之间
                const posY = Math.random() * 80 + 10;
                element.style.left = `${posX}%`;
                element.style.top = `${posY}%`;
                
                // 更长的动画时间
                element.style.setProperty('--delay', `${Math.random() * 5}s`);
                element.style.setProperty('--duration', `${30 + Math.random() * 30}s`);
                
                // 限制移动范围在容器内
                const maxMove = Math.min(containerWidth, containerHeight) * 0.2; // 最大移动范围为容器尺寸的20%
                element.style.setProperty('--translate-x', `${-maxMove + Math.random() * maxMove * 2}px`);
                element.style.setProperty('--translate-y', `${-maxMove + Math.random() * maxMove * 2}px`);
                element.style.setProperty('--translate-x2', `${-maxMove + Math.random() * maxMove * 2}px`);
                element.style.setProperty('--translate-y2', `${-maxMove + Math.random() * maxMove * 2}px`);
                element.style.setProperty('--translate-x3', `${-maxMove + Math.random() * maxMove * 2}px`);
                element.style.setProperty('--translate-y3', `${-maxMove + Math.random() * maxMove * 2}px`);
                
                // 更小的旋转角度
                element.style.setProperty('--rotate', `${Math.random() * 180}deg`);
                element.style.setProperty('--rotate2', `${Math.random() * 180}deg`);
                element.style.setProperty('--rotate3', `${Math.random() * 180}deg`);
                
                // 随机颜色
                const color = colors[Math.floor(Math.random() * colors.length)];
                element.style.setProperty('--color', color);
                
                // 根据深度调整不透明度 - 创建3D效果
                const depth = 0.3 + Math.random() * 0.7;
                element.style.opacity = depth.toString();
                element.style.setProperty('--scale', depth.toString());
                
                // 生成形状元素
                const innerElement = document.createElement('div');
                
                if (shape === 'circle') {
                    innerElement.className = 'element-circle';
                    const size = 20 + Math.random() * 40; // 减小最大尺寸
                    innerElement.style.setProperty('--size', `${size}px`);
                } else if (shape === 'square') {
                    innerElement.className = 'element-square';
                    const size = 20 + Math.random() * 30;
                    innerElement.style.setProperty('--size', `${size}px`);
                    innerElement.style.setProperty('--rotate', `${Math.random() * 45}deg`);
                } else if (shape === 'triangle') {
                    innerElement.className = 'element-triangle';
                    const size = 20 + Math.random() * 30;
                    innerElement.style.setProperty('--size', `${size}px`);
                } else if (shape === 'icon') {
                    innerElement.className = 'element-icon';
                    // 确保行李箱相关图标比例更高
                    let icon;
                    if (Math.random() < 0.4) {
                        // 行李箱相关图标
                        const luggageIcons = ['suitcase', 'suitcase-rolling', 'luggage-cart', 'briefcase', 'backpack', 'shopping-bag'];
                        icon = luggageIcons[Math.floor(Math.random() * luggageIcons.length)];
                    } else {
                        // 其他交通和旅行图标
                        icon = icons[Math.floor(Math.random() * icons.length)];
                    }
                    
                    // 修复一些FontAwesome图标名称
                    const iconMap = {
                        'backpack': 'hiking',
                        'subway': 'train',
                        'hiking': 'hiking'
                    };
                    
                    const faIcon = iconMap[icon] || icon;
                    innerElement.innerHTML = `<i class="fas fa-${faIcon}"></i>`;
                    
                    // 根据深度调整大小
                    const size = 20 + (Math.random() * 20 * depth); // 减小最大尺寸
                    innerElement.style.setProperty('--size', `${size}px`);
                }
                
                element.appendChild(innerElement);
                container.appendChild(element);
            }
        }
        
        /**
         * 初始化表单提交处理
         */
        function initializeFormSubmission() {
            // 获取表单元素
            const form = document.getElementById('basicInfoForm');
            if (!form) return;
            
            // 表单验证
            form.addEventListener('submit', function(event) {
                // 使用HTML5 Validation API进行表单验证
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // 添加was-validated类，触发自定义验证样式
                    form.classList.add('was-validated');
                    
                    // 显示错误信息
                    showValidationErrors(form);
                    return;
                }
                
                // 表单验证通过，允许表单提交（PHP处理）
                form.classList.add('was-validated');
                
                // 显示加载提示
                showLoadingOverlay('正在保存您的信息...');
                
                // 让表单正常提交，不阻止默认行为
            });
        }

        /**
         * 显示表单验证错误信息
         * @param {HTMLFormElement} form - 表单元素
         */
        function showValidationErrors(form) {
            // 高亮显示未填写的必填项
            const invalidFields = form.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
                // 找到父级form-group，添加has-error类
                const formGroup = field.closest('.form-group');
                if (formGroup) {
                    formGroup.classList.add('has-error');
                }
            });
        }

        /**
         * 显示加载提示
         * @param {string} message - 提示消息
         */
        function showLoadingOverlay(message = '加载中...') {
            // 如果已存在加载提示，更新消息
            let loadingOverlay = document.querySelector('.loading-overlay');
            if (loadingOverlay) {
                const messageElem = loadingOverlay.querySelector('.loading-message');
                if (messageElem) {
                    messageElem.textContent = message;
                }
                return;
            }
            
            // 创建加载提示
            loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            
            // 创建提示内容
            const content = document.createElement('div');
            content.className = 'loading-content';
            
            // 创建加载图标
            const spinner = document.createElement('div');
            spinner.className = 'spinner-border text-light';
            spinner.setAttribute('role', 'status');
            
            // 创建消息
            const messageElem = document.createElement('div');
            messageElem.className = 'loading-message';
            messageElem.textContent = message;
            
            // 组合元素
            content.appendChild(spinner);
            content.appendChild(messageElem);
            loadingOverlay.appendChild(content);
            
            // 添加到页面
            document.body.appendChild(loadingOverlay);
            
            // 防止滚动
            document.body.style.overflow = 'hidden';
        }
    </script>
    
    <!-- 如果有管理员登录错误，自动打开模态框 -->
    <?php if (isset($_SESSION['show_admin_modal']) && $_SESSION['show_admin_modal']): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 使用Bootstrap的modal方法打开模态框
            var adminModal = new bootstrap.Modal(document.getElementById('adminLoginModal'));
            adminModal.show();
            
            // 清除会话变量，防止刷新页面后再次打开
            <?php 
            // 保留错误信息，但删除显示标志，这样刷新页面时不会自动打开模态框，但错误消息仍然存在
            unset($_SESSION['show_admin_modal']); 
            ?>
        });
    </script>
    <?php endif; ?>
</body>
</html> 