<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行李箱调研管理系统</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 基础样式 -->
    <link rel="stylesheet" href="../../共用资源/css/base/style.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #ffffff;
            padding-top: 2rem;
        }
        
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.25rem;
            border-radius: 4px;
            margin-bottom: 0.25rem;
        }
        
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        
        .admin-sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        .stat-card {
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .stat-card .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .stat-label {
            font-size: 1rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- 内容将在会话验证通过后显示 -->
    <div id="adminContent" style="display: none;">
        <div class="container-fluid">
            <div class="row">
                <!-- 侧边栏 -->
                <div class="col-md-3 col-lg-2 px-0 admin-sidebar">
                    <div class="text-center mb-4 py-2">
                        <i class="fas fa-suitcase-rolling fa-3x mb-2"></i>
                        <h5 class="mb-0">行李箱调研管理</h5>
                    </div>
                    <ul class="nav flex-column px-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="fas fa-chart-line"></i>仪表盘</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-tasks"></i>调研问卷</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-users"></i>受访者</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-chart-pie"></i>数据分析</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-file-export"></i>导出报告</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-cog"></i>系统设置</a>
                        </li>
                        <li class="nav-item mt-5">
                            <a class="nav-link text-danger" href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i>退出系统</a>
                        </li>
                    </ul>
                </div>
                
                <!-- 主内容区 -->
                <div class="col-md-9 col-lg-10 admin-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4">仪表盘</h2>
                        <div>
                            <span class="me-3">欢迎回来，铭哥</span>
                            <span class="text-muted">最后登录: <span id="lastLoginTime">2024-03-19 08:30</span></span>
                        </div>
                    </div>
                    
                    <!-- 统计卡片 -->
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-white shadow-sm">
                                <div class="stat-icon bg-primary text-white">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <div class="stat-number">147</div>
                                <div class="stat-label">总受访者</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-white shadow-sm">
                                <div class="stat-icon bg-success text-white">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div class="stat-number">98</div>
                                <div class="stat-label">已完成问卷</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-white shadow-sm">
                                <div class="stat-icon bg-warning text-white">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div class="stat-number">49</div>
                                <div class="stat-label">进行中问卷</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-white shadow-sm">
                                <div class="stat-icon bg-info text-white">
                                    <i class="fas fa-chart-bar fa-lg"></i>
                                </div>
                                <div class="stat-number">3</div>
                                <div class="stat-label">问卷模块</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 最近活动 -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">最近活动</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>受访者ID</th>
                                            <th>性别</th>
                                            <th>年龄段</th>
                                            <th>问卷模块</th>
                                            <th>完成时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#00147</td>
                                            <td>女</td>
                                            <td>26-35岁</td>
                                            <td>购买决策调研</td>
                                            <td>2024-03-19 07:45</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">查看</a></td>
                                        </tr>
                                        <tr>
                                            <td>#00146</td>
                                            <td>男</td>
                                            <td>36-45岁</td>
                                            <td>结构体验调研</td>
                                            <td>2024-03-19 06:30</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">查看</a></td>
                                        </tr>
                                        <tr>
                                            <td>#00145</td>
                                            <td>男</td>
                                            <td>46-60岁</td>
                                            <td>功能创新调研</td>
                                            <td>2024-03-18 22:15</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">查看</a></td>
                                        </tr>
                                        <tr>
                                            <td>#00144</td>
                                            <td>女</td>
                                            <td>18-25岁</td>
                                            <td>购买决策调研</td>
                                            <td>2024-03-18 19:55</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">查看</a></td>
                                        </tr>
                                        <tr>
                                            <td>#00143</td>
                                            <td>不愿透露</td>
                                            <td>26-35岁</td>
                                            <td>功能创新调研</td>
                                            <td>2024-03-18 15:20</td>
                                            <td><a href="#" class="btn btn-sm btn-outline-primary">查看</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 未认证提示 -->
    <div id="unauthorizedContent" class="container mt-5 text-center" style="display: none;">
        <div class="alert alert-danger p-5">
            <h2><i class="fas fa-exclamation-triangle me-3"></i>访问受限</h2>
            <p class="lead mt-3">您需要登录管理员账号才能访问此页面</p>
            <div class="mt-4">
                <a href="../../入口界面/index.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>返回首页登录
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript 库 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- 自定义脚本 -->
    <script src="../../共用资源/js/utils/theme.js"></script>
    <script src="../../共用资源/js/utils/session.js"></script>
    <script src="../../共用资源/js/auth/admin-auth.js"></script>
    <script src="../../共用资源/js/pages/admin.js"></script>
</body>
</html> 