<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>行李箱结构与使用体验调研 - 问卷二</title>
    
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- 自定义样式 -->
    <link rel="stylesheet" href="../../共用资源/css/base/style.css">
    <link rel="stylesheet" href="../../共用资源/css/pages/questionnaire.css">
    
    <!-- Sortable.js 用于拖拽排序 -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- jQuery (Bootstrap依赖) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- 自定义样式 - 问卷二特有样式 -->
    <style>
        /* 问卷二主题色 */
        :root {
            --primary-color: #3a5bb3;
            --secondary-color: #2a4494;
            --hover-color: #2c44b3;
            --light-bg: #f7f9ff;
            --border-color: #e0e6ff;
            --divider-color: #d1d8ff;
            
            /* 问题类型颜色 */
            --single-choice-color: #3a5bb3; /* 单选题颜色 */
            --multiple-choice-color: #1ebe5e; /* 多选题颜色 */
            --sort-choice-color: #ff9500; /* 排序题颜色 */
            
            /* 序号颜色 */
            --number-1-color: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --number-2-color: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            --number-3-color: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            --number-4-color: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);
            --number-5-color: linear-gradient(to right, #fa709a 0%, #fee140 100%);
            --number-6-color: linear-gradient(to top, #30cfd0 0%, #330867 100%);
            --number-7-color: linear-gradient(to right, #43e97b 0%, #38f9d7 100%);
            --number-8-color: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
        }
        
        /* 全局样式 */
        body {
            font-family: "Microsoft YaHei", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }
        
        /* 防止文本选择 */
        .no-select {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none;   /* Safari */
            -khtml-user-select: none;    /* Konqueror HTML */
            -moz-user-select: none;      /* Firefox */
            -ms-user-select: none;       /* Internet Explorer/Edge */
            user-select: none;           /* Non-prefixed version, currently supported by Chrome and Opera */
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #f0f4f8 0%, #d7e3f0 100%);
        }
        
        /* 问卷卡片样式 */
        .questionnaire-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* 头部样式 */
        .header-bar {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        /* 进度条样式 */
        .progress {
            overflow: hidden;
            height: 8px;
            border-radius: 0;
        }
        
        .progress-bar {
            position: relative;
            overflow: hidden;
            transition: width 0.5s ease-out;
        }
        
        .progress-bar:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.3) 50%, 
                rgba(255,255,255,0) 100%
            );
            animation: progress-shine 2s infinite;
        }
        
        @keyframes progress-shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* 问题盒子样式 */
        .question-box {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 35px;
            position: relative;
            border-left: 5px solid var(--primary-color);
            transition: transform 0.2s;
            box-shadow: 0 4px 12px rgba(58, 91, 179, 0.08);
        }
        
        /* 不同问题左边框颜色 */
        .question-box.single-choice {
            border-left-color: var(--single-choice-color);
        }
        
        .question-box.multiple-choice {
            border-left-color: var(--multiple-choice-color);
        }
        
        .question-box.sort-choice {
            border-left-color: var(--sort-choice-color);
        }

        .question-box::after {
            content: '';
            position: absolute;
            bottom: -18px;
            left: 10%;
            width: 80%;
            height: 2px;
            background-color: var(--divider-color);
            border-radius: 1px;
        }

        .question-box:last-of-type::after {
            display: none;
        }
        
        .question-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(58, 91, 179, 0.1);
        }
        
        /* 问题标签样式 */
        .question-tag {
            position: absolute;
            top: -10px;
            left: 20px;
            background: white;
            padding: 2px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            color: #666;
            border: 1px solid #eee;
        }
        
        .single-choice .question-tag {
            color: var(--single-choice-color);
            border-color: var(--single-choice-color);
        }
        
        .multiple-choice .question-tag {
            color: var(--multiple-choice-color);
            border-color: var(--multiple-choice-color);
        }
        
        .sort-choice .question-tag {
            color: var(--sort-choice-color);
            border-color: var(--sort-choice-color);
        }
        
        /* 问题标题样式 */
        .question-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1f2937;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--border-color);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        /* 问题描述样式 */
        .question-description {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 15px;
            padding-left: 40px; /* 与问题标题对齐 */
        }
        
        /* 问题编号样式 */
        .question-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            font-weight: bold;
            background: var(--primary-color);
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .question-number:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 8px rgba(0, 0, 0, 0.15);
        }

        /* 为每个问题序号设置不同的渐变色 */
        .number-1 { background: var(--number-1-color); }
        .number-2 { background: var(--number-2-color); }
        .number-3 { background: var(--number-3-color); }
        .number-4 { background: var(--number-4-color); }
        .number-5 { background: var(--number-5-color); }
        .number-6 { background: var(--number-6-color); }
        .number-7 { background: var(--number-7-color); }
        .number-8 { background: var(--number-8-color); }
        
        /* 必填标记样式 */
        .required-mark {
            color: #ef4444;
            margin-left: 5px;
        }
        
        /* 问题类型标签样式 */
        .question-type-badge {
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 0.8rem;
            margin-left: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
        }
        
        .question-type-badge i {
            margin-right: 5px;
        }
        
        .badge-single {
            background-color: var(--single-choice-color);
        }
        
        .badge-multiple {
            background-color: var(--multiple-choice-color);
        }
        
        .badge-sort {
            background-color: var(--sort-choice-color);
        }
        
        /* 选项组样式 */
        .option-group {
            margin-top: 15px;
            background-color: #fcfdff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f0f4ff;
        }
        
        /* 选项项目样式 */
        .option-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 10px;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            user-select: none; /* 防止文字被选中 */
        }
        
        /* 不同类型问题的选项悬停效果 */
        .single-choice .option-item:hover {
            background-color: rgba(58, 91, 179, 0.1);
            border-color: var(--single-choice-color);
        }
        
        .multiple-choice .option-item:hover {
            background-color: rgba(30, 190, 94, 0.1);
            border-color: var(--multiple-choice-color);
        }
        
        .sort-choice .sortable-item:hover {
            background-color: rgba(255, 149, 0, 0.1);
            border-color: var(--sort-choice-color);
        }

        /* 选项选中状态 */
        .single-choice .option-item.selected {
            background-color: rgba(58, 91, 179, 0.15);
            border-color: var(--single-choice-color);
        }
        
        .multiple-choice .option-item.selected {
            background-color: rgba(30, 190, 94, 0.15);
            border-color: var(--multiple-choice-color);
        }
        
        .option-item input[type="radio"],
        .option-item input[type="checkbox"] {
            margin-right: 10px;
            cursor: pointer;
        }
        
        /* 自定义单选和多选按钮样式 */
        .single-choice .option-item input[type="radio"] {
            accent-color: var(--single-choice-color);
        }
        
        .multiple-choice .option-item input[type="checkbox"] {
            accent-color: var(--multiple-choice-color);
        }
        
        /* 选项标签样式 */
        .option-label {
            font-size: 1rem;
            flex-grow: 1;
            cursor: pointer;
            user-select: none; /* 防止文字被选中 */
        }
        
        /* 问卷二特有 - 排序题样式 */
        .sortable-list {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            padding: 10px 0;
        }
        
        .sortable-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 12px !important;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: grab;
            transition: none;
            user-select: none; /* 防止文字被选中 */
        }
        
        .sortable-item:hover {
            background-color: rgba(255, 149, 0, 0.1);
            border-color: var(--sort-choice-color);
        }
        
        /* 添加拖拽时的视觉效果 */
        .sortable-chosen {
            background-color: var(--light-bg) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-2px);
            z-index: 1001;
            position: relative;
            /* 确保完全不透明 */
            opacity: 1 !important;
            /* 提高拖拽响应性 */
            will-change: transform;
            transition: none !important;
        }
        
        /* 拖拽时的占位符效果 */
        .sortable-ghost {
            background-color: rgba(255, 149, 0, 0.05) !important;
            border: 1px dashed var(--border-color) !important;
            opacity: 1 !important;
            box-shadow: none !important;
            transform: none !important;
            transition: none !important;
        }
        
        /* 隐藏占位符中的所有内容 */
        .sortable-ghost * {
            visibility: hidden !important;
        }
        
        .drag-handle {
            color: #9ca3af;
            margin-right: 15px;
            cursor: grab;
        }
        
        .item-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--sort-choice-color);
            color: white;
            font-weight: bold;
            margin-right: 15px;
        }
        
        /* 防止拖动时的文字变形 */
        .sortable-item * {
            pointer-events: none;
        }
        
        /* 增加拖拽手柄的视觉提示 */
        .sort-choice .sortable-item:hover .drag-handle {
            color: var(--sort-choice-color);
            animation: wiggle 1s ease-in-out infinite;
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-3deg); }
            75% { transform: rotate(3deg); }
        }
        
        /* 验证消息和提示样式 */
        .validation-message,
        .success-message {
            border-radius: 8px;
            font-weight: 500;
            padding: 12px 20px;
        }
        
        .validation-message {
            background: linear-gradient(45deg, #fff5f5 0%, #ffe3e3 100%);
            border-left: 4px solid #f27474;
        }
        
        .success-message {
            background: linear-gradient(45deg, #f0fff4 0%, #dcfce7 100%);
            border-left: 4px solid #68d391;
        }
        
        .instruction-text {
            color: #6b7280;
            font-style: italic;
            font-size: 0.9rem;
            margin-top: 5px;
            padding-left: 40px; /* 与问题标题对齐 */
        }
        
        /* 导航按钮样式 */
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
        }
        
        .back-btn {
            background-color: #f3f4f6;
            color: #4b5563;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background-color: #e5e7eb;
            transform: translateY(-2px);
        }
        
        .next-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .next-btn:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(58, 91, 179, 0.3);
        }

        /* 错误状态样式 */
        .validation-error {
            border-left-color: #dc3545 !important;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        /* 页脚样式 */
        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
        }
        
        /* 问题盒子奇偶不同样式 */
        .question-box:nth-child(odd) {
            background-color: white;
        }
        
        .question-box:nth-child(even) {
            background-color: #fafbff;
        }

        .question-box.active {
            border-left-width: 8px;
            box-shadow: 0 8px 16px rgba(58, 91, 179, 0.15);
        }
        
        /* 响应式调整 */
        @media (max-width: 768px) {
            .questionnaire-card {
                margin: 10px;
            }
            
            .question-title {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .question-type-badge {
                margin-left: 40px;
                margin-top: 5px;
            }
            
            .navigation-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .back-btn, .next-btn {
                width: 100%;
            }
            
            .question-box::after {
                left: 5%;
                width: 90%;
            }
        }
    </style>
</head>
<body class="light-theme">
<!-- 问卷主体部分 -->
<div class="container-fluid bg-gradient min-vh-100 d-flex align-items-center justify-content-center p-3 py-5">
    <div class="questionnaire-card shadow-lg" style="width: 100%;">
        <!-- 页面头部 -->
        <div class="header-bar">
            问卷二：行李箱结构与使用体验调研
        </div>
        
        <!-- 进度指示器 -->
        <div class="progress rounded-0" style="height: 8px;">
            <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        
        <div class="card-body p-4">
            <!-- 问卷介绍 -->
            <div class="text-center mb-4">
                <h2 class="fw-bold">行李箱结构与使用体验调研</h2>
                <p class="text-muted">本问卷旨在了解您对行李箱结构和使用过程中的体验与问题</p>
            </div>
            
            <!-- 问题 1 -->
            <div class="question-box multiple-choice" id="question1">
                <div class="question-tag">问题 1</div>
                <div class="question-title">
                    <span class="question-number number-1">1</span> 您在使用行李箱时，遇到的主要结构问题有：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="question-description">可以选择多个您经常遇到的问题</div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="拉杆槽凹凸影响内部空间">
                        <span class="option-label">拉杆槽凹凸影响内部空间</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="内部空间无明显分区">
                        <span class="option-label">内部空间无明显分区</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="打开后物品易散乱">
                        <span class="option-label">打开后物品易散乱</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="拉杆伸缩长度分级不明确">
                        <span class="option-label">拉杆伸缩长度分级不明确</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="拉链锁位置/推拉方式指示不明显">
                        <span class="option-label">拉链锁位置/推拉方式指示不明显</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="只有一个侧面有支撑，临时放置不便">
                        <span class="option-label">只有一个侧面有支撑，临时放置不便</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="其他">
                        <span class="option-label">其他</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 2 -->
            <div class="question-box multiple-choice" id="question2">
                <div class="question-tag">问题 2</div>
                <div class="question-title">
                    <span class="question-number number-2">2</span> 使用行李箱时，您经常感到的身体不适是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="手臂/肩膀酸痛">
                        <span class="option-label">手臂/肩膀酸痛</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="手腕疲劳">
                        <span class="option-label">手腕疲劳</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="握持不舒适">
                        <span class="option-label">握持不舒适</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="腰背部酸痛">
                        <span class="option-label">腰背部酸痛</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="推行时需弯腰/驼背">
                        <span class="option-label">推行时需弯腰/驼背</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="无明显不适">
                        <span class="option-label">无明显不适</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question2" value="其他">
                        <span class="option-label">其他</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 3 -->
            <div class="question-box single-choice" id="question3">
                <div class="question-tag">问题 3</div>
                <div class="question-title">
                    <span class="question-number number-3">3</span> 关于行李箱拉杆高度，您的体验是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="radio" name="question3" value="常常不够高，使用时需弯腰">
                        <span class="option-label">常常不够高，使用时需弯腰</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="通常正好合适">
                        <span class="option-label">通常正好合适</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="往往过高，使用不便">
                        <span class="option-label">往往过高，使用不便</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="高度合适但角度不理想">
                        <span class="option-label">高度合适但角度不理想</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="高度可调但不容易找到合适档位">
                        <span class="option-label">高度可调但不容易找到合适档位</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 4 -->
            <div class="question-box sort-choice" id="question4">
                <div class="question-tag">问题 4</div>
                <div class="question-title">
                    <span class="question-number number-4">4</span> 内部结构方面，您最希望改进的是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-sort"><i class="fas fa-sort"></i>排序题</span>
                </div>
                <div class="question-description">请按照重要性排序，拖拽选项调整顺序（最重要的放在最上面）</div>
                <ul class="sortable-list" id="sortableStructure">
                    <li class="sortable-item no-select" data-value="优化拉杆凹槽占用空间">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">1</div>
                        <span class="option-label">优化拉杆凹槽占用空间</span>
                    </li>
                    <li class="sortable-item no-select" data-value="增加明确的分区设计">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">2</div>
                        <span class="option-label">增加明确的分区设计</span>
                    </li>
                    <li class="sortable-item no-select" data-value="加强物品固定系统">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">3</div>
                        <span class="option-label">加强物品固定系统</span>
                    </li>
                    <li class="sortable-item no-select" data-value="设计电子设备专用区域">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">4</div>
                        <span class="option-label">设计电子设备专用区域</span>
                    </li>
                    <li class="sortable-item no-select" data-value="提高空间利用率的收纳方案">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">5</div>
                        <span class="option-label">提高空间利用率的收纳方案</span>
                    </li>
                    <li class="sortable-item no-select" data-value="防止物品移位的结构设计">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">6</div>
                        <span class="option-label">防止物品移位的结构设计</span>
                    </li>
                </ul>
                <p class="instruction-text mt-3">提示：点击并拖动选项可调整排序</p>
            </div>
            
            <!-- 问题 5 -->
            <div class="question-box single-choice" id="question5">
                <div class="question-tag">问题 5</div>
                <div class="question-title">
                    <span class="question-number number-5">5</span> 您使用行李箱的重量分布情况是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="radio" name="question5" value="重物集中在底部">
                        <span class="option-label">重物集中在底部</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="重物靠近滚轮侧">
                        <span class="option-label">重物靠近滚轮侧</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="重物分散放置">
                        <span class="option-label">重物分散放置</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="未特别考虑重量分布">
                        <span class="option-label">未特别考虑重量分布</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="根据不同使用场景调整分布">
                        <span class="option-label">根据不同使用场景调整分布</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 6 -->
            <div class="question-box multiple-choice" id="question6">
                <div class="question-tag">问题 6</div>
                <div class="question-title">
                    <span class="question-number number-6">6</span> 您使用多个行李箱时，如何管理各箱的功能分工？<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="按尺寸大小分配不同用途">
                        <span class="option-label">按尺寸大小分配不同用途</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="按旅行时长选择不同箱子">
                        <span class="option-label">按旅行时长选择不同箱子</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="按出行目的地/场合选择">
                        <span class="option-label">按出行目的地/场合选择</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="专门区分商务和休闲用箱">
                        <span class="option-label">专门区分商务和休闲用箱</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="一个主用箱，其他做备用">
                        <span class="option-label">一个主用箱，其他做备用</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="不同成员使用不同箱子">
                        <span class="option-label">不同成员使用不同箱子</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="没有特别规划">
                        <span class="option-label">没有特别规划</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 7 -->
            <div class="question-box multiple-choice" id="question7">
                <div class="question-tag">问题 7</div>
                <div class="question-title">
                    <span class="question-number number-7">7</span> 使用行李箱上下楼梯时，您遇到的主要困难是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="question-description">可选择多个您遇到的困难</div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question7" value="重量过大不易抬起" id="weight_problem">
                        <span class="option-label">重量过大不易抬起</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question7" value="手提把手位置不合理">
                        <span class="option-label">手提把手位置不合理</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question7" value="缺乏辅助上下楼梯的设计">
                        <span class="option-label">缺乏辅助上下楼梯的设计</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question7" value="箱体太大不易操控">
                        <span class="option-label">箱体太大不易操控</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question7" value="滚轮妨碍上下楼梯">
                        <span class="option-label">滚轮妨碍上下楼梯</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question7" value="其他">
                        <span class="option-label">其他</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 8 -->
            <div class="question-box multiple-choice" id="question8">
                <div class="question-tag">问题 8</div>
                <div class="question-title">
                    <span class="question-number number-8">8</span> 您是否需要以下结构改进？<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="多面底座设计（可任意面放置）">
                        <span class="option-label">多面底座设计（可任意面放置）</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="分格式自定义分栏系统">
                        <span class="option-label">分格式自定义分栏系统</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="拉杆伸缩档位可视化指示">
                        <span class="option-label">拉杆伸缩档位可视化指示</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="电子设备防误触保护层（防静电/防误操作）">
                        <span class="option-label">电子设备防误触保护层（防静电/防误操作）</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="3D打印替换部件接口">
                        <span class="option-label">3D打印替换部件接口</span>
                    </label>
                </div>
            </div>
            
            <!-- 导航按钮 -->
            <div class="navigation-buttons">
                <button class="btn back-btn">
                    <i class="fas fa-arrow-left me-2"></i>返回选择界面
                </button>
                <button class="btn next-btn">
                    提交问卷<i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
        
        <!-- 页脚信息 -->
        <div class="card-footer bg-light text-center py-3">
            <p class="mb-0 small">© 2024 行李箱用户体验调研项目</p>
            <div class="mt-1 small text-muted">问卷完成度：<span class="survey-completion">0%</span></div>
        </div>
    </div>
</div>

<!-- 条件跳转脚本（简化版） -->
<script>
    // 当选择"重量过大不易抬起"选项时，应跳转至问卷三第7题
    document.getElementById('weight_problem').addEventListener('change', function() {
        if(this.checked) {
            alert('您选择了"重量过大不易抬起"，在实际应用中将跳转至问卷三第7题');
        }
    });
</script>

<!-- 增强的问卷交互脚本 -->
<script>
    $(document).ready(function() {
        // 禁用长按选择文本，提高移动设备上的拖拽体验
        document.addEventListener('touchstart', function(e) {
            if($(e.target).closest('.sortable-item').length) {
                e.preventDefault();
            }
        }, {passive: false});
        
        // 禁用排序项的文本选择
        $('.sortable-item, .sortable-item *').on('selectstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // 创建进度存储工具
        const ProgressStorage = {
            // 存储键名和版本标记
            PROGRESS_KEY: 'survey2_progress_value',
            VERSION_KEY: 'survey2_progress_version',
            CURRENT_VERSION: '3.0', // 更新版本号
            
            // 清除所有进度数据
            clearAllProgress: function() {
                localStorage.removeItem(this.PROGRESS_KEY);
                localStorage.removeItem(this.VERSION_KEY);
            },
            
            // 保存进度
            saveProgress: function(value) {
                localStorage.setItem(this.PROGRESS_KEY, value);
                localStorage.setItem(this.VERSION_KEY, this.CURRENT_VERSION);
            },
            
            // 获取进度
            getProgress: function() {
                // 检查版本，如果版本不匹配，则使用默认值
                const version = localStorage.getItem(this.VERSION_KEY);
                if (version !== this.CURRENT_VERSION) {
                    return 20; // 入口页面基础进度值
                }
                
                const progress = localStorage.getItem(this.PROGRESS_KEY);
                // 如果没有已存储的进度，返回默认值20（入口页面基础信息填写后的进度）
                return progress ? parseFloat(progress) : 20;
            }
        };
        
        // 简化的进度条状态
        const ProgressState = {
            // 进度状态枚举
            INITIAL: 'initial',    // 初始状态（红色）- 基础信息填写后
            PARTIAL: 'partial',    // 部分填写（黄色）- 有回答但未全部完成
            COMPLETE: 'complete',  // 全部完成（绿色）- 所有问题都回答了
            
            // 进度条颜色
            COLORS: {
                initial: '#ff5252',  // 红色
                partial: '#ffb142',  // 黄色
                complete: '#2ed573'  // 绿色
            },
            
            // 进度条宽度
            WIDTHS: {
                initial: 20,  // 基础信息 - 20%
                partial: 60,  // 部分填写 - 60%
                complete: 100 // 全部完成 - 100%
            },
            
            // 当前状态
            current: 'initial'
        };
        
        // 更新进度条显示
        function updateProgressBar() {
            // 检查是否所有问题都已回答
            const allQuestionsAnswered = checkAllQuestionsAnswered();
            // 检查是否有任何问题已回答
            const anyQuestionAnswered = checkAnyQuestionAnswered();
            
            // 确定进度条状态
            let state = ProgressState.INITIAL;
            
            if (allQuestionsAnswered) {
                state = ProgressState.COMPLETE;
            } else if (anyQuestionAnswered) {
                state = ProgressState.PARTIAL;
            } else {
                state = ProgressState.INITIAL;
            }
            
            // 更新当前状态
            ProgressState.current = state;
            
            // 更新进度条
            $('.progress-bar').css({
                'width': ProgressState.WIDTHS[state] + '%',
                'background-color': ProgressState.COLORS[state],
                'transition': 'width 0.5s ease-out, background-color 0.5s ease-out'
            });
            
            // 更新完成度文本
            let completionText = "0%";
            if (state === ProgressState.COMPLETE) {
                completionText = "100%";
            } else if (state === ProgressState.PARTIAL) {
                // 计算大致完成百分比
                const totalQuestions = $('.question-box').length;
                const answeredQuestions = countAnsweredQuestions();
                const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
                completionText = percentage + "%";
            }
            $('.survey-completion').text(completionText);
            
            // 高亮完成度文本（如果状态是完成）
            $('.survey-completion').toggleClass('text-success fw-bold', state === ProgressState.COMPLETE);
            
            // 保存当前进度
            ProgressStorage.saveProgress(ProgressState.WIDTHS[state]);
            
            return state;
        }
        
        // 检查是否所有问题都已回答
        function checkAllQuestionsAnswered() {
            let allAnswered = true;
            
            // 检查所有问题
            $('.question-box').each(function() {
                if (!checkQuestionAnswered(this)) {
                    allAnswered = false;
                    return false; // 跳出循环
                }
            });
            
            return allAnswered;
        }
        
        // 检查是否有任何问题已回答
        function checkAnyQuestionAnswered() {
            let anyAnswered = false;
            
            // 检查所有问题
            $('.question-box').each(function() {
                if (checkQuestionAnswered(this)) {
                    anyAnswered = true;
                    return false; // 跳出循环
                }
            });
            
            return anyAnswered;
        }
        
        // 计算已回答问题数量
        function countAnsweredQuestions() {
            let count = 0;
            
            // 计数所有已回答问题
            $('.question-box').each(function() {
                if (checkQuestionAnswered(this)) {
                    count++;
                }
            });
            
            return count;
        }
        
        // 检查单个问题是否已回答
        function checkQuestionAnswered(questionBox) {
            const $questionBox = $(questionBox);
            
            if ($questionBox.hasClass('single-choice')) {
                // 单选题 - 有任何选中的选项即为已回答
                return $questionBox.find('input[type="radio"]:checked').length > 0;
            } 
            else if ($questionBox.hasClass('multiple-choice')) {
                // 多选题 - 有任何选中的选项即为已回答
                return $questionBox.find('input[type="checkbox"]:checked').length > 0;
            } 
            else if ($questionBox.hasClass('sort-choice')) {
                // 排序题 - 根据交互标记判断
                return $questionBox.data('user-interacted') === true;
            }
            
            return false;
        }
        
        // 初始化进度条
        function initProgressBar() {
            // 获取保存的进度
            const savedProgress = ProgressStorage.getProgress();
            
            // 设置进度条初始状态
            let initialState;
            if (savedProgress >= ProgressState.WIDTHS.complete) {
                initialState = ProgressState.COMPLETE;
            } else if (savedProgress >= ProgressState.WIDTHS.partial) {
                initialState = ProgressState.PARTIAL;
            } else {
                initialState = ProgressState.INITIAL;
            }
            
            // 更新当前状态
            ProgressState.current = initialState;
            
            // 设置初始进度条
            $('.progress-bar').css({
                'width': ProgressState.WIDTHS[initialState] + '%',
                'background-color': ProgressState.COLORS[initialState],
                'transition': 'none'
            });
            
            // 恢复已选项的样式
            restoreSelectionStyles();
            
            // 延迟计算实际进度
            setTimeout(function() {
                // 启用过渡效果
                $('.progress-bar').css('transition', 'width 0.5s ease-out, background-color 0.5s ease-out');
                // 检查实际状态并更新
                updateProgressBar();
            }, 300);
        }
        
        // 恢复选择样式
        function restoreSelectionStyles() {
            // 清除所有选中样式
            $('.option-item').removeClass('selected');
            
            // 为已选中的选项添加样式
            $('input[type="radio"]:checked, input[type="checkbox"]:checked').each(function() {
                $(this).closest('.option-item').addClass('selected');
            });
            
            // 检查排序题是否有用户自定义排序
            $('.sort-choice').each(function() {
                $(this).data('user-interacted', false);
            });
        }
        
        // 初始化
        initProgressBar();
        
        // 监听单选和多选题
        $(document).on('change', '.question-box input[type="radio"], .question-box input[type="checkbox"]', function() {
            const $input = $(this);
            
            // 更新选中样式
            if ($input.attr('type') === 'radio') {
                // 单选题 - 清除同组其他选项的样式
                $(`input[name="${$input.attr('name')}"]`).closest('.option-item').removeClass('selected');
                if ($input.prop('checked')) {
                    $input.closest('.option-item').addClass('selected');
                }
            } else {
                // 多选题 - 根据状态更新样式
                $input.closest('.option-item').toggleClass('selected', $input.prop('checked'));
            }
            
            // 更新进度条
            updateProgressBar();
        });
        
        // 初始化排序功能
        var sortableList = document.getElementById('sortableStructure');
        if (sortableList) {
            new Sortable(sortableList, {
                animation: 150, // 减少动画时间，提高响应速度
                easing: "cubic-bezier(0.2, 0, 0.2, 1)", // 更快的缓动函数
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                forceFallback: true, // 保持强制回退模式
                fallbackTolerance: 0, // 零容差
                delay: 0, // 无延迟
                delayOnTouchOnly: false,
                touchStartThreshold: 0, // 降低触摸阈值
                swapThreshold: 0.65, // 调整交换阈值
                swap: false, // 关闭swap模式，使用标准排序模式
                dragoverBubble: false,
                fallbackOffset: {x: 0, y: 0}, // 消除偏移
                preventOnFilter: true, // 防止过滤时的默认行为
                disableTouch: false, // 不禁用触摸
                supportPointer: true, // 支持指针事件
                setData: function (dataTransfer, dragEl) {
                    // 优化拖拽数据传输
                    dataTransfer.setData('text', '');
                },
                onStart: function(evt) {
                    $(evt.item).closest('.sort-choice').data('user-interacted', true);
                    // 防止文本选择
                    $('body').css('user-select', 'none');
                    updateProgressBar();
                },
                onEnd: function(evt) {
                    // 重新编号
                    $('#sortableStructure .item-number').each(function(index) {
                        $(this).text(index + 1);
                    });
                    // 恢复文本选择
                    $('body').css('user-select', '');
                }
            });
            
            // 为排序项添加提示效果
            $('#sortableStructure').find('.sortable-item').each(function() {
                $(this).attr('title', '点击并拖动此项调整顺序');
            });
        }
        
        // 返回按钮功能
        $('.back-btn').click(function() {
            window.location.href = '../主页面/主界面.php';
        });
        
        // 提交问卷
        $('.next-btn').click(function() {
            // 检查是否所有问题都已回答
            if (checkAllQuestionsAnswered()) {
                // 收集所有问题的答案
                const formData = new FormData();
                
                // 问题1：行李箱结构问题（多选题）
                const q1Values = [];
                $('input[name="question1"]:checked').each(function() {
                    q1Values.push($(this).val());
                });
                q1Values.forEach(value => formData.append('q1[]', value));
                
                // 问题2：身体不适（多选题）
                const q2Values = [];
                $('input[name="question2"]:checked').each(function() {
                    q2Values.push($(this).val());
                });
                q2Values.forEach(value => formData.append('q2[]', value));
                
                // 问题3：拉杆高度（单选题）
                formData.append('q3', $('input[name="question3"]:checked').val());
                
                // 问题4：内部结构改进（排序题）
                const q4Order = [];
                $('#sortableStructure li').each(function() {
                    q4Order.push($(this).data('value'));
                });
                formData.append('q4_order', JSON.stringify(q4Order));
                
                // 问题5：重量分布（单选题）
                formData.append('q5', $('input[name="question5"]:checked').val());
                
                // 问题6：功能分工（多选题）
                const q6Values = [];
                $('input[name="question6"]:checked').each(function() {
                    q6Values.push($(this).val());
                });
                q6Values.forEach(value => formData.append('q6[]', value));
                
                // 问题7：上下楼梯困难（多选题）
                const q7Values = [];
                $('input[name="question7"]:checked').each(function() {
                    q7Values.push($(this).val());
                });
                q7Values.forEach(value => formData.append('q7[]', value));
                
                // 问题8：结构改进（多选题）
                const q8Values = [];
                $('input[name="question8"]:checked').each(function() {
                    q8Values.push($(this).val());
                });
                q8Values.forEach(value => formData.append('q8[]', value));
                
                // 使用fetch API提交数据
                fetch('submit_questionnaire2.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.ok) {
                        // 设置为完成状态
                        ProgressStorage.saveProgress(ProgressState.WIDTHS.complete);
                        
                        // 显示提交成功消息
                        showSuccessMessage('问卷提交成功！谢谢您的参与');
                        
                        // 延迟后跳转到主界面
                        setTimeout(function() {
                            try {
                                // 正常情况，回到选择界面
                                console.log("跳转到成功页面");
                                window.location.replace("../主页面/主界面.php?success=1");
                            } catch(e) {
                                console.error("跳转错误:", e);
                                // 备用跳转方法
                                window.open("../主页面/主界面.php", "_self");
                            }
                        }, 2000);
                    } else {
                        throw new Error('提交失败');
                    }
                })
                .catch(error => {
                    console.error('提交错误:', error);
                    alert('提交过程中出现错误，请稍后重试。');
                });
            } else {
                // 提示未完成
                const answeredCount = countAnsweredQuestions();
                const totalCount = $('.question-box').length;
                alert(`您还有未回答的问题！已回答 ${answeredCount}/${totalCount} 题。请完成所有问题后再提交。`);
            }
        });
        
        // 问题点击高亮
        $('.question-box').click(function(event) {
            // 排除点击选项的情况
            if ($(event.target).closest('.option-item, .sortable-item').length) return;
            
            $('.question-box').removeClass('active');
            $(this).addClass('active');
        });
        
        // 滚动时高亮当前问题
        $(window).scroll(function() {
            const scrollPosition = $(window).scrollTop();
            
            // 找出当前可见问题
            $('.question-box').each(function() {
                const topPosition = $(this).offset().top;
                
                if (topPosition - 200 < scrollPosition && topPosition + $(this).height() > scrollPosition) {
                    $('.question-box').removeClass('active');
                    $(this).addClass('active');
                    return false; // 退出循环
                }
            });
        });
    });
</script>

<!-- JavaScript 库 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- 工具脚本 -->
<script src="../../共用资源/js/utils/theme.js"></script>
<script src="../../共用资源/js/utils/accessibility.js"></script>
<script src="../../共用资源/js/utils/storage.js"></script>
<script src="../../共用资源/js/utils/session.js"></script>

<!-- 添加成功消息提示函数 -->
<script>
    function showSuccessMessage(message) {
        // 移除已有消息
        $('.success-message').remove();
        
        // 创建消息元素
        var messageElement = $('<div class="success-message alert alert-success" role="alert">' + 
                               '<i class="fas fa-check-circle me-2"></i>' + message + '</div>');
        
        // 添加到页面并设置样式
        messageElement.css({
            'position': 'fixed',
            'top': '20px',
            'left': '50%',
            'transform': 'translateX(-50%)',
            'z-index': '1000',
            'min-width': '300px',
            'text-align': 'center',
            'box-shadow': '0 4px 8px rgba(0,0,0,0.1)'
        });
        
        $('body').append(messageElement);
        
        // 3秒后自动消失
        setTimeout(function() {
            messageElement.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
</script>
</body>
</html> 