<!DOCTYPE html>
<?php
// 启动会话
session_start();

// 获取会话ID
$session_id = session_id();

// 包含数据库连接文件
require_once('../../共用资源/php/db_connect.php');

// 连接数据库
$dbc = db_connect();

// 初始化用户数据变量
$userData = array(
    'gender' => null,
    'age_group' => null,
    'usage_frequency' => null,
    'luggage_count' => null
);

// 调试信息（仅在开发环境使用）
$debug = array(
    'session_id' => $session_id,
    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not_set',
    'db_connected' => ($dbc ? true : false),
    'query_status' => 'not_executed',
    'has_data' => false,
    'error' => ''
);

// 从数据库获取用户基本信息
if ($dbc) {
    // 先通过session_id查询respondents表获取user_id
    $user_id = null;
    
    // 方法1: 通过session_id查询respondents表获取user_id
    $query1 = "SELECT user_id FROM respondents WHERE session_id = '$session_id'";
    $result1 = mysqli_query($dbc, $query1);
    
    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_array($result1);
        $user_id = $row1['user_id'];
        $debug['found_method'] = 'session_id_lookup';
    } 
    // 方法2: 如果session里直接存了user_id
    else if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $debug['found_method'] = 'session_variable';
    }
    // 方法3: 通过最近创建的记录获取(如果没有关联关系)
    else {
        $query3 = "SELECT * FROM basic_info ORDER BY created_at DESC LIMIT 1";
        $result3 = mysqli_query($dbc, $query3);
        
        if ($result3 && mysqli_num_rows($result3) > 0) {
            $row3 = mysqli_fetch_array($result3);
            
            // 如果表中有id字段
            if (isset($row3['id'])) {
                $user_id = $row3['id'];
                $debug['found_method'] = 'latest_record';
                // 直接从结果中获取数据
                $userData['gender'] = $row3['gender'];
                $userData['age_group'] = $row3['age_group'];
                $userData['usage_frequency'] = $row3['usage_frequency'];
                $userData['luggage_count'] = $row3['luggage_count'];
                $debug['has_data'] = true;
            }
            // 如果表中有user_id字段
            else if (isset($row3['user_id'])) {
                $user_id = $row3['user_id'];
                $debug['found_method'] = 'latest_record_user_id';
                // 直接从结果中获取数据
                $userData['gender'] = $row3['gender'];
                $userData['age_group'] = $row3['age_group'];
                $userData['usage_frequency'] = $row3['usage_frequency'];
                $userData['luggage_count'] = $row3['luggage_count'];
                $debug['has_data'] = true;
            }
        } else {
            $debug['error'] = '无法获取用户ID，尝试了多种方法但都失败了';
        }
    }
    
    $debug['user_id'] = $user_id;
    
    // 如果获取到了user_id并且还没有获取到用户数据，则查询basic_info表
    if ($user_id && !$debug['has_data']) {
        // 构建查询语句 - 先尝试使用id字段
        $query2 = "SELECT gender, age_group, usage_frequency, luggage_count FROM basic_info WHERE id = '$user_id'";
        $result2 = mysqli_query($dbc, $query2);
        
        // 检查查询是否成功
        if ($result2 && mysqli_num_rows($result2) > 0) {
            $debug['query_status'] = 'success_by_id';
            $debug['has_data'] = true;
            
            // 获取用户数据
            $row2 = mysqli_fetch_array($result2);
            
            // 赋值给用户数据数组
            $userData['gender'] = $row2['gender'];
            $userData['age_group'] = $row2['age_group'];
            $userData['usage_frequency'] = $row2['usage_frequency'];
            $userData['luggage_count'] = $row2['luggage_count'];
        } else {
            // 尝试使用user_id字段
            $query2alt = "SELECT gender, age_group, usage_frequency, luggage_count FROM basic_info WHERE user_id = '$user_id'";
            $result2alt = mysqli_query($dbc, $query2alt);
            
            if ($result2alt && mysqli_num_rows($result2alt) > 0) {
                $debug['query_status'] = 'success_by_user_id';
                $debug['has_data'] = true;
                
                // 获取用户数据
                $row2alt = mysqli_fetch_array($result2alt);
                
                // 赋值给用户数据数组
                $userData['gender'] = $row2alt['gender'];
                $userData['age_group'] = $row2alt['age_group'];
                $userData['usage_frequency'] = $row2alt['usage_frequency'];
                $userData['luggage_count'] = $row2alt['luggage_count'];
            } else {
                $debug['error'] = '未找到与user_id匹配的记录';
                // 查询所有记录，看看表中有什么
                $debug_query = "SELECT * FROM basic_info LIMIT 1";
                $debug_result = mysqli_query($dbc, $debug_query);
                if ($debug_result && mysqli_num_rows($debug_result) > 0) {
                    $debug_row = mysqli_fetch_array($debug_result);
                    $debug['table_structure'] = array_keys($debug_row);
                }
            }
        }
    }
}

// 不要在这里关闭数据库连接，移到后面去
// mysqli_close($dbc);

// 转换为JavaScript可用的JSON格式
$userDataJSON = json_encode($userData);
$debugJSON = json_encode($debug);

// 根据性别获取对应的显示文本
function getGenderText($gender) {
    switch($gender) {
        case 'male': return '<i class="fas fa-mars"></i> 男';
        case 'female': return '<i class="fas fa-venus"></i> 女';
        case 'other': return '<i class="fas fa-genderless"></i> 不愿透露';
        default: return '<i class="fas fa-user"></i> 未知';
    }
}

// 根据使用频率获取对应的显示文本
function getFrequencyText($frequency) {
    switch($frequency) {
        case 'weekly': return '<i class="fas fa-calendar-day"></i> 一周多次';
        case 'monthly': return '<i class="fas fa-calendar-week"></i> 一个月1-2次';
        case 'quarterly': return '<i class="fas fa-calendar-alt"></i> 一季度1-2次';
        case 'biannually': return '<i class="fas fa-hourglass-half"></i> 半年1-2次';
        case 'yearly': return '<i class="fas fa-hourglass-end"></i> 一年不到1次';
        default: return '<i class="fas fa-calendar"></i> 未知';
    }
}

// 根据行李箱数量获取对应的显示文本
function getCountText($count) {
    switch($count) {
        case '1': return '<i class="fas fa-suitcase"></i> 1个';
        case '2-3': return '<i class="fas fa-suitcase-rolling"></i> 2-3个';
        case '4-5': return '<i class="fas fa-luggage-cart"></i> 4-5个';
        case '5+': return '<i class="fas fa-cubes"></i> 5个以上';
        default: return '<i class="fas fa-suitcase"></i> 未知';
    }
}

// 添加问卷完成状态检查
$completedQuestionnaires = [];
$isQuestionnaire1Completed = false;
$isQuestionnaire2Completed = false;
$isQuestionnaire3Completed = false;

// 连接数据库
if ($dbc && $user_id) {
    // 检查问卷1是否已完成
    $query1 = "SELECT submission_id FROM questionnaire1 WHERE user_id = '$user_id' LIMIT 1";
    $result1 = mysqli_query($dbc, $query1);
    if ($result1 && mysqli_num_rows($result1) > 0) {
        $isQuestionnaire1Completed = true;
        $completedQuestionnaires[] = 1;
    }
    
    // 检查问卷2是否已完成
    $query2 = "SELECT submission_id FROM questionnaire2 WHERE user_id = '$user_id' LIMIT 1";
    $result2 = mysqli_query($dbc, $query2);
    if ($result2 && mysqli_num_rows($result2) > 0) {
        $isQuestionnaire2Completed = true;
        $completedQuestionnaires[] = 2;
    }
    
    // 检查问卷3是否已完成
    $query3 = "SELECT submission_id FROM questionnaire3 WHERE user_id = '$user_id' LIMIT 1";
    $result3 = mysqli_query($dbc, $query3);
    if ($result3 && mysqli_num_rows($result3) > 0) {
        $isQuestionnaire3Completed = true;
        $completedQuestionnaires[] = 3;
    }
}

// 设置提交按钮状态和提示文本
$submitBtnDisabled = count($completedQuestionnaires) === 0;
$completionNoteText = "";
$submitBtnClass = "submit-all-btn";

if ($submitBtnDisabled) {
    $completionNoteText = "请至少完成一份问卷后提交";
    $submitBtnClass .= " disabled";
} else if (count($completedQuestionnaires) === 3) {
    $completionNoteText = "您已完成所有问卷，可以提交了!";
    $submitBtnClass .= " active"; 
} else {
    $completionNoteText = "您已完成" . count($completedQuestionnaires) . "份问卷，可以提交或继续完成其他问卷";
    $submitBtnClass .= " partial";
}

// 在所有PHP查询完成后关闭数据库连接
if ($dbc) {
    mysqli_close($dbc);
}
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行李箱用户体验调研</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 基础样式 - 使用相对路径 -->
    <link rel="stylesheet" href="../../共用资源/css/base/style.css">
    <!-- 页面专用样式 - 使用相对路径 -->
    <link rel="stylesheet" href="../../共用资源/css/pages/selection.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 内联样式，确保基本样式即使外部CSS加载失败也能看到 */
        .header-bar {
            background-color: #4d6dff;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        .progress-bar {
            background-color: #1ebe5e;
        }
        
        .completion-icon {
            width: 70px;
            height: 70px;
            background-color: #1ebe5e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .user-info-display {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        
        .user-info-item {
            background-color: #f5f5f5;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #333;
        }
        
        .back-button {
            background-color: transparent;
            border: 1px solid #4d6dff;
            color: #4d6dff;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 0.9rem;
            margin: 15px 0;
            transition: all 0.3s;
        }
        
        .back-button:hover {
            background-color: #4d6dff;
            color: white;
        }
        
        .questionnaire-section {
            margin-top: 30px;
        }
        
        .card-header.questionnaire-1-bg {
            background-color: #4d6dff;
        }
        
        .card-header.questionnaire-2-bg {
            background-color: #1ebe5e;
        }
        
        .card-header.questionnaire-3-bg {
            background-color: #9c27b0;
        }
        
        /* 提交按钮样式 */
        .submit-all-btn {
            background-color: #ddd;
            color: #888;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: not-allowed;
            transition: all 0.3s ease;
            margin: 15px 0;
        }
        
        .submit-all-btn.disabled {
            background-color: #ddd;
            color: #888;
            cursor: not-allowed;
        }
        
        .submit-all-btn.partial {
            background-color: #ffc107;
            color: #212529;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .submit-all-btn.active {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .submit-all-btn.partial:hover, .submit-all-btn.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        .completion-note {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 8px;
        }
        
        /* 问卷完成标记样式 */
        .questionnaire-completed {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #28a745;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .questionnaire-card {
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<body class="light-theme">
    <div class="container-fluid bg-gradient min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="card selection-card shadow-lg" style="max-width: 1000px; width: 100%;">
            <!-- 页面头部 -->
            <div class="header-bar">
                行李箱用户体验调研
            </div>
            
            <!-- 进度指示器 -->
            <div class="progress rounded-0" style="height: 8px;">
                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="card-body py-4">
                <!-- 完成指示图标和标题 -->
                <div class="text-center">
                    <div class="completion-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h2 class="fw-bold mb-3">您已完成基本信息填写</h2>
                    <p class="lead">请从以下问卷中选择您感兴趣的部分进行填写。您可以选择一个或多个问卷模块。</p>
                </div>
                
                <!-- 用户信息展示 -->
                <div class="user-info-display" id="userInfoDisplay">
                    <?php if($userData['gender'] || $userData['age_group'] || $userData['usage_frequency'] || $userData['luggage_count']): ?>
                        <?php if($userData['gender']): ?>
                            <div class="user-info-item"><?php echo getGenderText($userData['gender']); ?></div>
                        <?php endif; ?>
                        
                        <?php if($userData['age_group']): ?>
                            <div class="user-info-item"><i class="fas fa-user-graduate"></i> <?php echo $userData['age_group']; ?></div>
                        <?php endif; ?>
                        
                        <?php if($userData['usage_frequency']): ?>
                            <div class="user-info-item"><?php echo getFrequencyText($userData['usage_frequency']); ?></div>
                        <?php endif; ?>
                        
                        <?php if($userData['luggage_count']): ?>
                            <div class="user-info-item"><?php echo getCountText($userData['luggage_count']); ?></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="user-info-item"><i class="fas fa-exclamation-circle"></i> 未获取到用户信息</div>
                        
                        <!-- 仅在开发环境显示调试信息 -->
                        <?php if(isset($_GET['debug']) && $_GET['debug'] == 1): ?>
                        <div class="alert alert-warning mt-3">
                            <h5>调试信息：</h5>
                            <pre><?php echo json_encode($debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- 返回修改基本信息按钮 -->
                <div class="text-center">
                    <button id="backToBasicInfoBtn" class="back-button">
                        <i class="fas fa-arrow-left me-1"></i> 返回修改基本信息
                    </button>
                </div>
                
                <!-- 问卷选择 -->
                <div class="questionnaire-section">
                    <div class="row g-4">
                        <!-- 问卷一 -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card questionnaire-card shadow-sm h-100" data-questionnaire="1">
                                <div class="card-header text-white questionnaire-1-bg">
                                    <h3 class="fw-bold text-center mb-0">问卷一</h3>
                                    <?php if ($isQuestionnaire1Completed): ?>
                                    <div class="questionnaire-completed">
                                        <i class="fas fa-check-circle me-1"></i> 已完成
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h4 class="fw-semibold text-center mb-3">基础用户画像与痛点筛选</h4>
                                    <p class="flex-grow-1">只需3分钟，帮助我们了解您的基本使用情况和主要问题。</p>
                                    <div class="questionnaire-details">
                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <span>完成时间: 约3分钟</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-list-ol"></i>
                                            <span>问题数量: 7个</span>
                                        </div>
                                    </div>
                                    <button class="btn <?php echo $isQuestionnaire1Completed ? 'btn-outline-primary' : 'btn-primary'; ?> w-100 mt-3 start-questionnaire" data-questionnaire="1">
                                        <?php echo $isQuestionnaire1Completed ? '<i class="fas fa-edit me-1"></i> 查看/编辑' : '开始填写'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 问卷二 -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card questionnaire-card shadow-sm h-100" data-questionnaire="2">
                                <div class="card-header text-white questionnaire-2-bg">
                                    <h3 class="fw-bold text-center mb-0">问卷二</h3>
                                    <?php if ($isQuestionnaire2Completed): ?>
                                    <div class="questionnaire-completed">
                                        <i class="fas fa-check-circle me-1"></i> 已完成
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h4 class="fw-semibold text-center mb-3">拉杆与滚轮系统专项调研</h4>
                                    <p class="flex-grow-1">针对行李箱最常用部件的专项调研，提升移动体验。</p>
                                    <div class="questionnaire-details">
                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <span>完成时间: 约3-4分钟</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-list-ol"></i>
                                            <span>问题数量: 8个</span>
                                        </div>
                                    </div>
                                    <button class="btn <?php echo $isQuestionnaire2Completed ? 'btn-outline-primary' : 'btn-primary'; ?> w-100 mt-3 start-questionnaire" data-questionnaire="2">
                                        <?php echo $isQuestionnaire2Completed ? '<i class="fas fa-edit me-1"></i> 查看/编辑' : '开始填写'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 问卷三 -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card questionnaire-card shadow-sm h-100" data-questionnaire="3">
                                <div class="card-header text-white questionnaire-3-bg">
                                    <h3 class="fw-bold text-center mb-0">问卷三</h3>
                                    <?php if ($isQuestionnaire3Completed): ?>
                                    <div class="questionnaire-completed">
                                        <i class="fas fa-check-circle me-1"></i> 已完成
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h4 class="fw-semibold text-center mb-3">内部空间设计专项调研</h4>
                                    <p class="flex-grow-1">关于行李箱内部空间利用和收纳功能的深入调研。</p>
                                    <div class="questionnaire-details">
                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <span>完成时间: 约3-4分钟</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-list-ol"></i>
                                            <span>问题数量: 9个</span>
                                        </div>
                                    </div>
                                    <button class="btn <?php echo $isQuestionnaire3Completed ? 'btn-outline-primary' : 'btn-primary'; ?> w-100 mt-3 start-questionnaire" data-questionnaire="3">
                                        <?php echo $isQuestionnaire3Completed ? '<i class="fas fa-edit me-1"></i> 查看/编辑' : '开始填写'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 提交所有问卷 -->
                <div class="text-center mt-4">
                    <button id="submitAllBtn" class="<?php echo $submitBtnClass; ?>" <?php echo $submitBtnDisabled ? 'disabled' : ''; ?>>
                        <i class="fas fa-paper-plane me-2"></i>提交所有已完成问卷
                    </button>
                    <p class="completion-note"><?php echo $completionNoteText; ?></p>
                </div>
            </div>
            
            <!-- 页脚信息 -->
            <div class="card-footer bg-light text-center py-3">
                <p class="mb-0 small">© 2024 行李箱用户体验调研项目</p>
            </div>
        </div>
    </div>

    <!-- JavaScript 库 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- 动画库 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <!-- 自定义脚本 -->
    <script src="../../共用资源/js/utils/storage.js"></script>
    <script src="../../共用资源/js/utils/theme.js"></script>
    <script src="../../共用资源/js/utils/accessibility.js"></script>
    <script src="../../共用资源/js/utils/session.js"></script>
    
    <!-- 页面初始化脚本 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 初始化主题管理器
            if (typeof ThemeManager !== 'undefined') {
                ThemeManager.init();
            }
            
            // 显示用户信息
            displayUserInfo();
            
            // 添加返回按钮事件
            document.getElementById('backToBasicInfoBtn').addEventListener('click', function() {
                // 显示确认对话框
                if (confirm('您确定要返回修改基本信息吗？这将删除您之前填写的所有数据。')) {
                    // 显示加载提示
                    showLoading('正在处理...');
                    
                    // 调用删除用户数据的API
                    fetch('../../共用资源/php/delete_user_data.php', {
                        method: 'POST',
                        credentials: 'same-origin' // 包含会话cookie
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        
                        if (data.success) {
                            // 删除成功，重定向到入口界面
                            window.location.href = '../../入口界面/index.php';
                        } else {
                            // 删除失败，显示错误消息
                            alert('删除数据失败: ' + data.message);
                            console.error('删除用户数据失败:', data);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        alert('系统错误，请稍后再试');
                        console.error('请求错误:', error);
                    });
                }
            });
            
            // 显示加载中提示
            function showLoading(message) {
                let loadingOverlay = document.querySelector('.loading-overlay');
                if (loadingOverlay) {
                    // 如果已存在，更新消息
                    const messageElem = loadingOverlay.querySelector('.loading-message');
                    if (messageElem) messageElem.textContent = message;
                    loadingOverlay.style.display = 'flex';
                    return;
                }
                
                // 创建加载提示
                loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.style.position = 'fixed';
                loadingOverlay.style.top = '0';
                loadingOverlay.style.left = '0';
                loadingOverlay.style.width = '100%';
                loadingOverlay.style.height = '100%';
                loadingOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                loadingOverlay.style.display = 'flex';
                loadingOverlay.style.justifyContent = 'center';
                loadingOverlay.style.alignItems = 'center';
                loadingOverlay.style.zIndex = '9999';
                
                // 创建内容
                const content = document.createElement('div');
                content.style.backgroundColor = 'white';
                content.style.padding = '20px';
                content.style.borderRadius = '5px';
                content.style.display = 'flex';
                content.style.flexDirection = 'column';
                content.style.alignItems = 'center';
                
                // 创建加载图标
                const spinner = document.createElement('div');
                spinner.className = 'spinner-border text-primary';
                spinner.setAttribute('role', 'status');
                
                // 创建消息
                const messageElem = document.createElement('div');
                messageElem.className = 'loading-message';
                messageElem.style.marginTop = '10px';
                messageElem.textContent = message;
                
                // 组合元素
                content.appendChild(spinner);
                content.appendChild(messageElem);
                loadingOverlay.appendChild(content);
                
                // 添加到页面
                document.body.appendChild(loadingOverlay);
            }
            
            // 隐藏加载提示
            function hideLoading() {
                const loadingOverlay = document.querySelector('.loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
            }
            
            // 添加问卷按钮点击事件
            document.querySelectorAll('.start-questionnaire').forEach(button => {
                button.addEventListener('click', function() {
                    const questionnaireId = this.getAttribute('data-questionnaire');
                    // 根据问卷编号跳转到对应页面
                    switch(questionnaireId) {
                        case '1':
                            window.location.href = '../问卷1/questionnaire1.php';
                            break;
                        case '2':
                            window.location.href = '../问卷2/questionnaire2.php';
                            break;
                        case '3':
                            window.location.href = '../问卷3/questionnaire3.php';
                            break;
                    }
                });
            });
            
            // 添加提交所有问卷按钮点击事件
            document.getElementById('submitAllBtn').addEventListener('click', function() {
                if (!this.classList.contains('disabled')) {
                    // 显示确认对话框
                    if (confirm('您确定要提交所有已完成的问卷吗？提交后将无法再修改。')) {
                        // 更新按钮状态，防止重复点击
                        const submitBtn = this;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 正在提交...';
                        
                        // 显示加载提示
                        showLoading('正在提交问卷数据，请稍候...');
                        
                        // 模拟提交过程
                        setTimeout(() => {
                            // 首先更新加载信息
                            const loadingMessage = document.querySelector('.loading-message');
                            if (loadingMessage) {
                                loadingMessage.innerHTML = '<i class="fas fa-check-circle" style="color: green; font-size: 24px; margin-right: 10px;"></i> 提交成功！';
                            }
                            
                            // 使用已有的session_close.php关闭会话
                            fetch('../../共用资源/php/close_session_api.php', {
                                method: 'POST',
                                credentials: 'same-origin'
                            })
                            .then(response => response.json())
                            .then(data => {
                                // 再等待一小段时间让用户看到成功信息
                                setTimeout(() => {
                                    // 隐藏加载提示
                                    hideLoading();
                                    
                                    // 显示成功模态框
                                    showCompletionModal();
                                }, 1500);
                            })
                            .catch(error => {
                                console.error('结束会话失败:', error);
                                // 即使会话结束失败，也显示成功信息给用户
                                setTimeout(() => {
                                    hideLoading();
                                    showCompletionModal();
                                }, 1500);
                            });
                        }, 1000);
                    }
                }
            });
            
            // 显示完成提示模态框
            function showCompletionModal() {
                // 创建模态框
                const modalHTML = `
                    <div class="modal fade" id="completionModal" tabindex="-1" aria-labelledby="completionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="completionModalLabel">
                                        <i class="fas fa-check-circle me-2"></i>提交成功
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <div class="completion-icon mb-3">
                                        <i class="fas fa-trophy fa-4x text-success"></i>
                                    </div>
                                    <h4 class="mb-3">感谢您参与行李箱用户体验调研！</h4>
                                    <p class="lead">您的宝贵意见将帮助我们打造更好的产品。</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-primary px-4" id="returnToHomeBtn">
                                        <i class="fas fa-home me-2"></i>返回首页
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // 添加模态框到页面
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // 确保DOM更新后再初始化模态框
                setTimeout(() => {
                    // 获取模态框元素
                    const modalElement = document.getElementById('completionModal');
                    
                    // 添加返回首页按钮事件
                    document.getElementById('returnToHomeBtn').addEventListener('click', function() {
                        window.location.href = '../../入口界面/index.php?completed=1';
                    });
                    
                    // 当模态框关闭时也返回首页
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        window.location.href = '../../入口界面/index.php?completed=1';
                    });
                    
                    // 初始化并显示模态框
                    const completionModal = new bootstrap.Modal(modalElement, {
                        backdrop: true,
                        keyboard: true,
                        focus: true
                    });
                    completionModal.show();
                }, 100);
            }
            
            // 显示用户基本信息函数 - 保留作为备份，现在使用PHP直接生成HTML
            function displayUserInfo() {
                // 此函数内容已经通过PHP代码直接实现
                // 仅用于在PHP输出失败时作为备份
                const userInfoDisplay = document.getElementById('userInfoDisplay');
                if (!userInfoDisplay || userInfoDisplay.children.length > 0) return;
                
                // 使用PHP获取的用户数据
                const userData = <?php echo $userDataJSON; ?>;
                
                if (userData && (userData.gender || userData.age_group || userData.usage_frequency || userData.luggage_count)) {
                    // 性别映射
                    const genderMap = {
                        'male': '<i class="fas fa-mars"></i> 男',
                        'female': '<i class="fas fa-venus"></i> 女',
                        'other': '<i class="fas fa-genderless"></i> 不愿透露'
                    };
                    
                    // 使用频率映射
                    const frequencyMap = {
                        'weekly': '<i class="fas fa-calendar-day"></i> 一周多次',
                        'monthly': '<i class="fas fa-calendar-week"></i> 一个月1-2次',
                        'quarterly': '<i class="fas fa-calendar-alt"></i> 一季度1-2次',
                        'biannually': '<i class="fas fa-hourglass-half"></i> 半年1-2次',
                        'yearly': '<i class="fas fa-hourglass-end"></i> 一年不到1次'
                    };
                    
                    // 箱子数量映射
                    const countMap = {
                        '1': '<i class="fas fa-suitcase"></i> 1个',
                        '2-3': '<i class="fas fa-suitcase-rolling"></i> 2-3个',
                        '4-5': '<i class="fas fa-luggage-cart"></i> 4-5个',
                        '5+': '<i class="fas fa-cubes"></i> 5个以上'
                    };
                    
                    // 创建信息展示项
                    const infoItems = [];
                    
                    // 添加性别信息
                    if (userData.gender) {
                        infoItems.push(`<div class="user-info-item">${genderMap[userData.gender] || '<i class="fas fa-user"></i> 未知'}</div>`);
                    }
                    
                    // 添加年龄段信息
                    if (userData.age_group) {
                        infoItems.push(`<div class="user-info-item"><i class="fas fa-user-graduate"></i> ${userData.age_group}</div>`);
                    }
                    
                    // 添加使用频率信息
                    if (userData.usage_frequency) {
                        infoItems.push(`<div class="user-info-item">${frequencyMap[userData.usage_frequency] || '<i class="fas fa-calendar"></i> 未知'}</div>`);
                    }
                    
                    // 添加箱子数量信息
                    if (userData.luggage_count) {
                        infoItems.push(`<div class="user-info-item">${countMap[userData.luggage_count] || '<i class="fas fa-suitcase"></i> 未知'}</div>`);
                    }
                    
                    // 更新显示
                    userInfoDisplay.innerHTML = infoItems.join('');
                } else {
                    // 如果没有用户数据，显示提示
                    userInfoDisplay.innerHTML = '<div class="user-info-item"><i class="fas fa-exclamation-circle"></i> 未获取到用户信息</div>';
                }
            }
        });
    </script>
</body>
</html> 