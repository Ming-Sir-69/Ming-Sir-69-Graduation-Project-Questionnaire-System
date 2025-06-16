<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>问卷三：内部空间设计专项调研</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 基础样式 -->
    <link rel="stylesheet" href="../../共用资源/css/base/style.css">
    <!-- 页面专用样式 -->
    <link rel="stylesheet" href="../../共用资源/css/pages/questionnaire.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Sortable.js 用于拖拽排序 -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- jQuery (Bootstrap依赖) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- 自定义样式 - 问卷三特有样式 -->
    <style>
        /* 问卷三主题色 */
        :root {
            --primary-color: #7b4dff;
            --secondary-color: #6039e5;
            --hover-color: #5132c7;
            --light-bg: #f7f4ff;
            --border-color: #e0d9ff;
            --divider-color: #d1c9ff;
            
            /* 问题类型颜色 */
            --single-choice-color: #7b4dff; /* 单选题颜色 */
            --multiple-choice-color: #4dc2ff; /* 多选题颜色 */
            --sort-choice-color: #ff5b8d; /* 排序题颜色 */
            
            /* 序号颜色 */
            --number-1-color: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --number-2-color: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
            --number-3-color: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            --number-4-color: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);
            --number-5-color: linear-gradient(to right, #fa709a 0%, #fee140 100%);
            --number-6-color: linear-gradient(to top, #30cfd0 0%, #330867 100%);
            --number-7-color: linear-gradient(to right, #43e97b 0%, #38f9d7 100%);
            --number-8-color: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
            --number-9-color: linear-gradient(45deg, #874da2 0%, #c43a30 100%);
        }
        
        /* 全局样式 */
        body {
            font-family: "Microsoft YaHei", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #f3f0ff 0%, #e9dffd 100%);
        }
        
        /* 问卷卡片样式 */
        .questionnaire-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 30px rgba(123, 77, 255, 0.1);
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
        .progress-bar {
            background: var(--multiple-choice-color);
            transition: width 0.3s ease;
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
            box-shadow: 0 4px 12px rgba(123, 77, 255, 0.08);
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
            box-shadow: 0 10px 20px rgba(123, 77, 255, 0.1);
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
        .number-9 { background: var(--number-9-color); }
        
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
        }
        
        /* 不同类型问题的选项悬停效果 */
        .single-choice .option-item:hover {
            background-color: rgba(123, 77, 255, 0.1);
            border-color: var(--single-choice-color);
        }
        
        .multiple-choice .option-item:hover {
            background-color: rgba(77, 194, 255, 0.1);
            border-color: var(--multiple-choice-color);
        }
        
        .sort-choice .sortable-item:hover {
            background-color: rgba(255, 91, 141, 0.1);
            border-color: var(--sort-choice-color);
        }

        /* 选项选中状态 */
        .single-choice .option-item.selected {
            background-color: rgba(123, 77, 255, 0.15);
            border-color: var(--single-choice-color);
        }
        
        .multiple-choice .option-item.selected {
            background-color: rgba(77, 194, 255, 0.15);
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
        }
        
        /* 问卷三特有 - 排序题样式 */
        .sortable-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sortable-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 8px;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: grab;
            transition: all 0.3s;
        }
        
        .sortable-item:hover {
            background-color: rgba(255, 91, 141, 0.1);
            border-color: var(--sort-choice-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
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
        
        /* 修改跟随鼠标的拖拽项样式 */
        /* .sortable-drag {
            opacity: 1 !important;
            background-color: var(--light-bg) !important;
            border: 1px solid var(--sort-choice-color) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
            transform: rotate(1deg) scale(1.02) !important;
            z-index: 1002 !important;
            cursor: grabbing !important;
        } */
        
        /* .active-dragging {
            background-color: rgba(255, 91, 141, 0.2);
            border-color: var(--sort-choice-color);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .sort-complete {
            animation: highlight-sort 0.5s ease-in-out;
        }
        
        @keyframes highlight-sort {
            0%, 100% { background-color: var(--light-bg); }
            50% { background-color: rgba(255, 91, 141, 0.2); }
        } */
        
        .drag-handle {
            color: #9ca3af;
            margin-right: 15px;
            cursor: grab;
        }
        
        /* 移除鼠标悬停时的缩放效果 */
        /* .sortable-item:hover .drag-handle {
            color: var(--sort-choice-color);
            transform: scale(1.2);
        } */
        
        /* 添加问卷二风格的拖拽手柄动画 */
        .sort-choice .sortable-item:hover .drag-handle {
            color: var(--sort-choice-color);
            animation: wiggle 1s ease-in-out infinite;
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-3deg); }
            75% { transform: rotate(3deg); }
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
        
        /* 移除项目号的动画效果 */
        /* .sortable-item:hover .item-number {
            transform: scale(1.1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        } */
        
        /* 移除排序进行中的全局样式 */
        /* body.sorting-in-progress {
            cursor: grabbing;
        }
        
        body.sorting-in-progress .sortable-item:not(.sortable-chosen):not(.sortable-drag) {
            opacity: 0.7;
        } */
        
        /* 防止拖动时的文字变形 */
        .sortable-item * {
            pointer-events: none;
        }
        
        /* 表单重置按钮样式 */
        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
            background-color: transparent;
            transition: all 0.3s;
        }
        
        .btn-outline-secondary:hover {
            color: #fff;
            background-color: #6c757d;
            transform: translateY(-2px);
        }
        
        /* 提示工具样式 */
        [data-tooltip] {
            position: relative;
            cursor: help;
        }
        
        [data-tooltip]:before,
        [data-tooltip]:after {
            position: absolute;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        [data-tooltip]:before {
            content: attr(data-tooltip);
            padding: 6px 10px;
            width: 160px;
            border-radius: 5px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            font-size: 12px;
            line-height: 1.2;
            bottom: 100%;
            left: 50%;
            margin-bottom: 5px;
            transform: translateX(-50%);
        }
        
        [data-tooltip]:after {
            content: '';
            border-width: 5px 5px 0 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
        }
        
        [data-tooltip]:hover:before,
        [data-tooltip]:hover:after {
            visibility: visible;
            opacity: 1;
        }
        
        /* 增强问题盒子的可见性效果 */
        .question-box {
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.5s ease, opacity 0.5s ease, box-shadow 0.3s, border-left-width 0.3s;
        }
        
        .question-box.visible {
            transform: translateY(0);
            opacity: 1;
        }
        
        /* 进度条动画效果 */
        .progress {
            overflow: hidden;
            height: 8px;
            border-radius: 0;
        }
        
        .progress-bar {
            position: relative;
            overflow: hidden;
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
        
        /* 验证消息和提示样式 */
        .validation-message,
        .success-message {
            border-radius: 8px;
            font-weight: 500;
            padding: 12px 20px;
            animation: slide-in-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }
        
        @keyframes slide-in-top {
            0% {
                transform: translateY(-20px) translateX(-50%);
                opacity: 0;
            }
            100% {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
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
        
        /* 添加问题高亮动画 */
        @keyframes question-highlight {
            0% { box-shadow: 0 0 0 rgba(123, 77, 255, 0); }
            50% { box-shadow: 0 0 15px rgba(123, 77, 255, 0.5); }
            100% { box-shadow: 0 0 0 rgba(123, 77, 255, 0); }
        }
        
        .question-box.active {
            border-left-width: 8px;
            box-shadow: 0 8px 16px rgba(123, 77, 255, 0.15);
            animation: question-highlight 2s infinite;
        }
        
        /* 单选和多选选项交互增强 */
        .option-item {
            position: relative;
            overflow: hidden;
        }
        
        .option-item:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 70%);
            opacity: 0;
            transform: scale(0);
            transition: transform 0.5s, opacity 0.5s;
        }
        
        .option-item:active:after {
            opacity: 1;
            transform: scale(2);
            transition: transform 0s, opacity 0.3s;
        }
        
        .option-item.selected {
            transform: scale(1.02);
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
            box-shadow: 0 4px 10px rgba(123, 77, 255, 0.3);
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
            box-shadow: 0 8px 16px rgba(123, 77, 255, 0.15);
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
        
        /* 添加防止文本选择的样式 */
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>
<body class="light-theme">
<!-- 问卷主体部分 -->
<div class="container-fluid bg-gradient min-vh-100 d-flex align-items-center justify-content-center p-3 py-5">
    <div class="questionnaire-card shadow-lg" style="width: 100%;">
        <!-- 页面头部 -->
        <div class="header-bar">
            问卷三：行李箱功能与创新需求调研
        </div>
        
        <!-- 进度指示器 -->
        <div class="progress rounded-0" style="height: 8px;">
            <div class="progress-bar" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        
        <div class="card-body p-4">
            <!-- 问卷介绍 -->
            <div class="text-center mb-4">
                <h2 class="fw-bold">行李箱功能与创新需求调研</h2>
                <p class="text-muted">本问卷旨在了解您对行李箱功能改进和创新方向的期望与需求</p>
            </div>
            
            <!-- 问题 1 -->
            <div class="question-box multiple-choice" id="question1">
                <div class="question-tag">问题 1</div>
                <div class="question-title">
                    <span class="question-number number-1">1</span> 您期望行李箱增加哪些智能功能？<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="question-description">可以选择多个您感兴趣的功能<i class="fas fa-info-circle ms-2" data-tooltip="最多可选择4项您最感兴趣的功能"></i></div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="GPS定位追踪">
                        <span class="option-label">GPS定位追踪</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="电子称重功能">
                        <span class="option-label">电子称重功能</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="指纹/人脸识别解锁">
                        <span class="option-label">指纹/人脸识别解锁</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="手机APP远程控制">
                        <span class="option-label">手机APP远程控制</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="自动跟随功能">
                        <span class="option-label">自动跟随功能</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="内置充电宝/充电功能">
                        <span class="option-label">内置充电宝/充电功能</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="语音控制功能">
                        <span class="option-label">语音控制功能</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question1" value="内置WiFi热点">
                        <span class="option-label">内置WiFi热点</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 2 -->
            <div class="question-box sort-choice" id="question2">
                <div class="question-tag">问题 2</div>
                <div class="question-title">
                    <span class="question-number number-2">2</span> 您对行李箱创新功能的优先考虑因素是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-sort"><i class="fas fa-sort"></i>排序题</span>
                </div>
                <div class="question-description">请按照重要性排序，拖拽选项调整顺序（最重要的放在最上面）<i class="fas fa-info-circle ms-2" data-tooltip="点击并拖动每个选项进行排序，排在最上方的为最重要"></i></div>
                <ul class="sortable-list" id="sortableInnovation">
                    <li class="sortable-item no-select" data-value="智能定位追踪">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">1</div>
                        <span class="option-label">智能定位追踪</span>
                    </li>
                    <li class="sortable-item no-select" data-value="指纹解锁">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">2</div>
                        <span class="option-label">指纹解锁</span>
                    </li>
                    <li class="sortable-item no-select" data-value="自动跟随功能">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">3</div>
                        <span class="option-label">自动跟随功能</span>
                    </li>
                    <li class="sortable-item no-select" data-value="内置电子秤">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">4</div>
                        <span class="option-label">内置电子秤</span>
                    </li>
                    <li class="sortable-item no-select" data-value="可折叠/伸缩设计">
                        <i class="fas fa-grip-lines drag-handle"></i>
                        <div class="item-number">5</div>
                        <span class="option-label">可折叠/伸缩设计</span>
                    </li>
                </ul>
                <p class="instruction-text mt-3">提示：<i class="fas fa-hand-point-up me-1"></i>点击并拖动选项可调整排序 <i class="fas fa-random ms-2 me-1"></i>您的排序将影响我们对行李箱创新方向的决策</p>
            </div>
            
            <!-- 问题 3 -->
            <div class="question-box single-choice" id="question3">
                <div class="question-tag">问题 3</div>
                <div class="question-title">
                    <span class="question-number number-3">3</span> 您对行李箱加入智能电子功能的态度是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                </div>
                <div class="question-description">选择最符合您观点的一项<i class="fas fa-info-circle ms-2" data-tooltip="此问题只能选择一个最符合您看法的选项"></i></div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="radio" name="question3" value="非常需要，愿意支付额外费用">
                        <span class="option-label">非常需要，愿意支付额外费用</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="比较需要，但价格要合理">
                        <span class="option-label">比较需要，但价格要合理</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="无所谓，取决于功能实用性">
                        <span class="option-label">无所谓，取决于功能实用性</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="不太需要，可能增加故障率">
                        <span class="option-label">不太需要，可能增加故障率</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question3" value="完全不需要，更偏好机械结构">
                        <span class="option-label">完全不需要，更偏好机械结构</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 4 -->
            <div class="question-box multiple-choice" id="question4">
                <div class="question-tag">问题 4</div>
                <div class="question-title">
                    <span class="question-number number-4">4</span> 您认为行李箱外观设计应改进的方面是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="更丰富的颜色选择">
                        <span class="option-label">更丰富的颜色选择</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="可个性化定制图案/纹理">
                        <span class="option-label">可个性化定制图案/纹理</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="更流线型的外观设计">
                        <span class="option-label">更流线型的外观设计</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="特殊材质表面处理（磨砂/光面等）">
                        <span class="option-label">特殊材质表面处理（磨砂/光面等）</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="更人性化的把手/拉杆设计">
                        <span class="option-label">更人性化的把手/拉杆设计</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="动态变色表面">
                        <span class="option-label">动态变色表面</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question4" value="外观无需改进">
                        <span class="option-label">外观无需改进</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 5 -->
            <div class="question-box single-choice" id="question5">
                <div class="question-tag">问题 5</div>
                <div class="question-title">
                    <span class="question-number number-5">5</span> 您最希望改进的行李箱轮子问题是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                </div>
                <div class="question-description">选择最符合您观点的一项<i class="fas fa-info-circle ms-2" data-tooltip="此问题只能选择一个最符合您看法的选项"></i></div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="radio" name="question5" value="噪音过大">
                        <span class="option-label">噪音过大</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="容易卡住/不灵活">
                        <span class="option-label">容易卡住/不灵活</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="耐用性差/易损坏">
                        <span class="option-label">耐用性差/易损坏</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="转向不灵敏">
                        <span class="option-label">转向不灵敏</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question5" value="轮子数量/位置不合理">
                        <span class="option-label">轮子数量/位置不合理</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 6 -->
            <div class="question-box multiple-choice" id="question6">
                <div class="question-tag">问题 6</div>
                <div class="question-title">
                    <span class="question-number number-6">6</span> 您认为行李箱应增加的便携设计有：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="可折叠/压缩设计">
                        <span class="option-label">可折叠/压缩设计</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="模块化组合式设计">
                        <span class="option-label">模块化组合式设计</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="可拆卸的小包/配件">
                        <span class="option-label">可拆卸的小包/配件</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="便携展开式座椅功能">
                        <span class="option-label">便携展开式座椅功能</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="多功能拉杆（可变形/多用途）">
                        <span class="option-label">多功能拉杆（可变形/多用途）</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question6" value="箱体外部快速存取口袋">
                        <span class="option-label">箱体外部快速存取口袋</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 7 -->
            <div class="question-box single-choice" id="question7">
                <div class="question-tag">问题 7</div>
                <div class="question-title">
                    <span class="question-number number-7">7</span> 关于行李箱的重量问题，您的看法是：<span class="required-mark">*</span>
                    <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                </div>
                <div class="question-description">选择最符合您观点的一项<i class="fas fa-info-circle ms-2" data-tooltip="此问题只能选择一个最符合您看法的选项"></i></div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="radio" name="question7" value="目前行李箱普遍太重，需要更轻量化设计">
                        <span class="option-label">目前行李箱普遍太重，需要更轻量化设计</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question7" value="重量适中，但应优化配重设计">
                        <span class="option-label">重量适中，但应优化配重设计</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question7" value="宁可重一些但更坚固耐用">
                        <span class="option-label">宁可重一些但更坚固耐用</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question7" value="需要采用更先进的复合材料减轻重量">
                        <span class="option-label">需要采用更先进的复合材料减轻重量</span>
                    </label>
                    <label class="option-item">
                        <input type="radio" name="question7" value="设计可调节重量的功能（如可拆卸部件）">
                        <span class="option-label">设计可调节重量的功能（如可拆卸部件）</span>
                    </label>
                </div>
            </div>
            
            <!-- 问题 8 -->
            <div class="question-box multiple-choice" id="question8">
                <div class="question-tag">问题 8</div>
                <div class="question-title">
                    <span class="question-number number-8">8</span> 您期望行李箱具备哪些环保特性？<span class="required-mark">*</span>
                    <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                </div>
                <div class="option-group">
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="使用可回收材料制造">
                        <span class="option-label">使用可回收材料制造</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="模块化设计便于更换部件（减少整体报废）">
                        <span class="option-label">模块化设计便于更换部件（减少整体报废）</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="使用生物降解材料">
                        <span class="option-label">使用生物降解材料</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="低碳/环保生产工艺">
                        <span class="option-label">低碳/环保生产工艺</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="旧行李箱回收再利用计划">
                        <span class="option-label">旧行李箱回收再利用计划</span>
                    </label>
                    <label class="option-item">
                        <input type="checkbox" name="question8" value="环保不是我购买行李箱的考虑因素">
                        <span class="option-label">环保不是我购买行李箱的考虑因素</span>
                    </label>
                </div>
            </div>
            
            <!-- 导航按钮 -->
            <div class="navigation-buttons">
                <button class="btn back-btn" data-tooltip="返回问卷选择页面">
                    <i class="fas fa-arrow-left me-2"></i>返回选择界面
                </button>
                <button class="btn next-btn" data-tooltip="提交您的答案">
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
    // 添加特定问题的条件跳转逻辑
    $(document).ready(function() {
        // 监听重量问题相关的选项
        $('input[name="question7"]').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue === '目前行李箱普遍太重，需要更轻量化设计') {
                // 标记此选项需要特殊跳转
                $(this).data('special-jump', true);
            } else {
                $(this).data('special-jump', false);
            }
        });
    });
    
    // 初始化排序功能
    var sortableList = document.getElementById('sortableInnovation');
    if(sortableList) {
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
                $('#sortableInnovation .item-number').each(function(index) {
                    $(this).text(index + 1);
                });
                // 恢复文本选择
                $('body').css('user-select', '');
            }
        });
        
        // 为排序项添加提示效果
        $('#sortableInnovation').find('.sortable-item').each(function() {
            $(this).attr('title', '点击并拖动此项调整顺序');
        });
    }
    
    // 禁用长按选择文本，提高移动设备上的拖拽体验
    document.addEventListener('touchstart', function(e) {
        if($(e.target).closest('.sortable-item').length) {
            e.preventDefault();
        }
    }, {passive: false});
</script>

<!-- 增强的问卷交互脚本 -->
<script>
    $(document).ready(function() {
        // 创建进度存储工具
        const ProgressStorage = {
            // 存储键名和版本标记
            PROGRESS_KEY: 'survey3_progress_value',
            VERSION_KEY: 'survey3_progress_version',
            CURRENT_VERSION: '1.0',
            
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
        
        // 添加完成度跟踪功能
        function updateCompletionPercentage() {
            // 使用新的进度条更新方法
            updateProgressBar();
        }
        
        // 初始更新完成度
        updateCompletionPercentage();
        
        // 当选项改变时更新完成度
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
        
        // 提交表单验证
        $('.next-btn').on('click', function(e) {
            // 阻止默认行为和事件冒泡
            e.preventDefault();
            e.stopPropagation();
            
            // 验证所有必填问题
            var isValid = true;
            var firstInvalidQuestion = null;
            
            // 检查单选题
            $('.single-choice').each(function() {
                var questionId = $(this).attr('id');
                var hasChecked = $('input[name="' + questionId + '"]:checked').length > 0;
                
                if (!hasChecked) {
                    isValid = false;
                    if (!firstInvalidQuestion) {
                        firstInvalidQuestion = $(this);
                    }
                    $(this).addClass('validation-error');
                } else {
                    $(this).removeClass('validation-error');
                }
            });
            
            // 检查多选题
            $('.multiple-choice').each(function() {
                var questionId = $(this).attr('id');
                var hasChecked = $('input[name="' + questionId + '"]:checked').length > 0;
                
                if (!hasChecked) {
                    isValid = false;
                    if (!firstInvalidQuestion) {
                        firstInvalidQuestion = $(this);
                    }
                    $(this).addClass('validation-error');
                } else {
                    $(this).removeClass('validation-error');
                }
            });
            
            // 检查排序题
            $('.sort-choice').each(function() {
                // 排序题已经预设项目，视为已完成
                $(this).removeClass('validation-error');
            });
            
            // 检查完成度
            var completionPercentage = updateProgressBar();
            
            // 如果验证不通过，滚动到第一个错误问题
            if (!isValid) {
                showValidationMessage('请回答所有必填问题后再提交');
                
                if (firstInvalidQuestion) {
                    $('html, body').animate({
                        scrollTop: firstInvalidQuestion.offset().top - 100
                    }, 500);
                }
                return;
            }
            
            // 收集表单数据
            var formData = collectFormData();
            
            // 创建FormData对象用于提交到服务器
            var serverFormData = new FormData();
            
            // 问题1：智能功能（多选题）
            var q1Values = formData['question1'] || [];
            q1Values.forEach(value => serverFormData.append('q1[]', value));
            
            // 问题2：创新功能优先考虑因素（排序题）
            if (formData['question2']) {
                serverFormData.append('q2_order', JSON.stringify(formData['question2']));
            }
            
            // 问题3：智能电子功能态度（单选题）
            if (formData['question3']) {
                serverFormData.append('q3', formData['question3']);
            }
            
            // 问题4：外观设计改进（多选题）
            var q4Values = formData['question4'] || [];
            q4Values.forEach(value => serverFormData.append('q4[]', value));
            
            // 问题5：行李箱轮子问题（单选题）
            if (formData['question5']) {
                serverFormData.append('q5', formData['question5']);
            }
            
            // 问题6：便携设计（多选题）
            var q6Values = formData['question6'] || [];
            q6Values.forEach(value => serverFormData.append('q6[]', value));
            
            // 问题7：重量问题看法（单选题）
            if (formData['question7']) {
                serverFormData.append('q7', formData['question7']);
            }
            
            // 问题8：环保特性（多选题）
            var q8Values = formData['question8'] || [];
            q8Values.forEach(value => serverFormData.append('q8[]', value));
            
            // 使用fetch API提交数据到服务器
            fetch('submit_questionnaire3.php', {
                method: 'POST',
                body: serverFormData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.ok) {
                    // 保存数据到localStorage（可选）
                    localStorage.setItem('questionnaire3_data', JSON.stringify(formData));
                    
                    // 显示提交成功消息
                    showSuccessMessage('问卷提交成功！谢谢您的参与');
                    
                    // 检查是否有特殊跳转条件
                    var specialJump = false;
                    $('input[name="question7"]:checked').each(function() {
                        if ($(this).data('special-jump')) {
                            specialJump = true;
                        }
                    });
                    
                    // 跳转页面
                    setTimeout(function() {
                        try {
                            if (specialJump) {
                                // 有特殊跳转条件，跳转到特定页面
                                console.log("跳转到特殊页面");
                                window.location.replace("../主页面/主界面.php?special=true");
                            } else {
                                // 正常情况，回到选择界面
                                console.log("跳转到成功页面");
                                window.location.replace("../主页面/主界面.php?success=1");
                            }
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
                showValidationMessage('提交过程中出现错误，请稍后重试');
            });
        });
        
        // 返回按钮功能
        $('.back-btn').click(function(e) {
            // 阻止默认行为和事件冒泡
            e.preventDefault();
            e.stopPropagation();
            
            // 立即返回到选择界面
            window.location.href = '../主页面/主界面.php';
            return false; // 确保事件不继续传播
        });
        
        // 多选题限制选择数量（如果需要）
        $('input[type="checkbox"]').on('change', function() {
            var name = $(this).attr('name');
            var maxAllowed = 4; // 默认最多选择4项
            
            // 如果当前已选数量超过最大允许数量
            if ($('input[name="' + name + '"]:checked').length > maxAllowed) {
                $(this).prop('checked', false);
                $(this).closest('.option-item').removeClass('selected');
                alert('最多只能选择' + maxAllowed + '项');
            }
            
            // 更新选中状态
            if($(this).is(':checked')) {
                $(this).closest('.option-item').addClass('selected');
            } else {
                $(this).closest('.option-item').removeClass('selected');
            }
        });
        
        // 单选按钮点击处理
        $('input[type="radio"]').on('change', function() {
            // 清除同一组中所有选项的选中效果
            var name = $(this).attr('name');
            $('input[name="' + name + '"]').closest('.option-item').removeClass('selected');
            
            // 给当前选中项添加选中效果
            if($(this).is(':checked')) {
                $(this).closest('.option-item').addClass('selected');
            }
        });
        
        // 工具函数：显示验证错误消息
        function showValidationMessage(message) {
            // 移除已有消息
            $('.validation-message').remove();
            
            // 创建消息元素
            var messageElement = $('<div class="validation-message alert alert-danger" role="alert">' + 
                                   '<i class="fas fa-exclamation-circle me-2"></i>' + message + '</div>');
            
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
        
        // 工具函数：显示成功消息
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
        
        // 工具函数：收集表单数据
        function collectFormData() {
            var formData = {};
            
            // 收集单选题数据
            $('.single-choice').each(function() {
                var questionId = $(this).attr('id');
                var selectedValue = $('input[name="' + questionId + '"]:checked').val();
                formData[questionId] = selectedValue || '';
            });
            
            // 收集多选题数据
            $('.multiple-choice').each(function() {
                var questionId = $(this).attr('id');
                var selectedValues = [];
                
                $('input[name="' + questionId + '"]:checked').each(function() {
                    selectedValues.push($(this).val());
                });
                
                formData[questionId] = selectedValues;
            });
            
            // 收集排序题数据
            if ($('#sortableInnovation').length > 0) {
                var sortOrder = [];
                $('#sortableInnovation .sortable-item').each(function() {
                    sortOrder.push($(this).data('value'));
                });
                formData['question2'] = sortOrder;
            }
            
            // 添加时间戳
            formData['timestamp'] = new Date().toISOString();
            
            return formData;
        }
        
        // 添加交互高亮效果
        $('.question-box').click(function() {
            $('.question-box').removeClass('active');
            $(this).addClass('active');
        });

        // 滚动时检测当前问题
        $(window).scroll(function() {
            var scrollPosition = $(window).scrollTop();
            
            // 找出当前可见的问题
            $('.question-box').each(function() {
                var topPosition = $(this).offset().top;
                
                if (topPosition - 200 < scrollPosition && topPosition + $(this).height() > scrollPosition) {
                    $('.question-box').removeClass('active');
                    $(this).addClass('active');
                    
                    // 更新进度条 - 基于当前问题的索引
                    var currentIndex = $('.question-box').index(this) + 1;
                    var totalQuestions = $('.question-box').length;
                    var progressPercent = (currentIndex / totalQuestions) * 100;
                    $('.progress-bar').css('width', progressPercent + '%');
                    
                    return false; // 退出循环
                }
            });
        });
        
        // 页面加载时，预设已选中选项的样式
        $('.option-item input[type="radio"]:checked').each(function() {
            $(this).closest('.option-item').addClass('selected');
        });
        
        $('.option-item input[type="checkbox"]:checked').each(function() {
            $(this).closest('.option-item').addClass('selected');
        });
        
        // 添加页面进度指示动画（更流畅的版本）
        $(window).scroll(function() {
            var scrollTop = $(window).scrollTop();
            var docHeight = $(document).height();
            var winHeight = $(window).height();
            var scrollPercent = (scrollTop) / (docHeight - winHeight);
            var progressWidth = Math.min(Math.max(scrollPercent * 100, 5), 100); // 至少5%，最多100%
            
            // 更新进度条 - 使用动画过渡
            $('.progress-bar').css({
                'width': progressWidth + '%',
                'transition': 'width 0.3s ease-out'
            });
        });
        
        // 问卷自动保存功能
        var autoSaveInterval = 30000; // 30秒
        var autoSaveTimer;
        
        function startAutoSave() {
            autoSaveTimer = setInterval(function() {
                var formData = collectFormData();
                localStorage.setItem('questionnaire3_data', JSON.stringify(formData));
                console.log('表单数据已自动保存 - ' + new Date().toLocaleTimeString());
            }, autoSaveInterval);
        }
        
        function stopAutoSave() {
            clearInterval(autoSaveTimer);
        }
        
        // 加载保存的数据
        function loadSavedData() {
            // 禁用自动加载数据功能
            return;
        }
        
        // 清除之前保存的数据
        localStorage.removeItem('questionnaire3_data');
        
        // 添加问题卡片的渐入动画
        $('.question-box').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            });
            
            setTimeout(function(el) {
                $(el).css({
                    'transition': 'opacity 0.5s ease, transform 0.5s ease',
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, 100 * index, this);
        });
        
        // 添加页面滚动监听，使问题卡片逐步显示
        function checkVisibility() {
            $('.question-box').each(function() {
                var elementTop = $(this).offset().top;
                var elementBottom = elementTop + $(this).outerHeight();
                var viewportTop = $(window).scrollTop();
                var viewportBottom = viewportTop + $(window).height();
                
                // 如果元素进入可视区域
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    // 如果还没有激活
                    if (!$(this).hasClass('visible')) {
                        $(this).addClass('visible');
                        $(this).css({
                            'opacity': '1',
                            'transform': 'translateY(0)'
                        });
                    }
                }
            });
        }
        
        // 初始化可见性检查
        $(window).on('scroll resize', checkVisibility);
        checkVisibility(); // 页面加载时执行一次
        
        // 信息图标悬停提示初始化
        $('.fa-info-circle').hover(function() {
            // 鼠标进入时添加颜色效果
            $(this).css('color', '#7b4dff');
        }, function() {
            // 鼠标离开时恢复原样
            $(this).css('color', '');
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
</body>
</html>