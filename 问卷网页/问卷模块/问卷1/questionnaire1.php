<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>问卷一：行李箱购买决策与基本需求调研</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 基础样式 -->
    <link rel="stylesheet" href="../../共用资源/css/base/style.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 问卷一主题色 */
        :root {
            --primary-color: #4d6dff;
            --secondary-color: #3a57d9;
            --hover-color: #2c44b3;
            --light-bg: #f7f9ff;
            --border-color: #e0e6ff;
            --divider-color: #d1d8ff;
            
            /* 问题类型颜色 */
            --single-choice-color: #4d6dff; /* 单选题颜色 */
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
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Microsoft YaHei', sans-serif;
            color: #333;
        }

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
        
        .progress-bar {
            background-color: #1ebe5e;
        }

        .questionnaire-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(77, 109, 255, 0.1);
            max-width: 1000px;
            margin: 0 auto;
        }

        .question-box {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 35px;
            border-left: 5px solid var(--primary-color);
            transition: transform 0.2s;
            position: relative;
            box-shadow: 0 4px 12px rgba(77, 109, 255, 0.08);
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
            box-shadow: 0 10px 20px rgba(77, 109, 255, 0.1);
        }

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

        /* 修改问题类型标签样式 */
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

        .option-group {
            margin-top: 15px;
            background-color: #fcfdff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f0f4ff;
        }

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
            background-color: rgba(77, 109, 255, 0.1);
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
            background-color: rgba(77, 109, 255, 0.15);
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

        .option-label {
            font-size: 1rem;
            flex-grow: 1;
            cursor: pointer;
        }

        .sortable-list {
            list-style-type: none;
            padding: 0;
        }

        .sortable-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 8px;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: move;
            transition: all 0.3s;
            user-select: none;
        }

        .sortable-item .drag-handle {
            color: #9ca3af;
            margin-right: 10px;
            cursor: move;
        }

        .sortable-item .item-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--sort-choice-color);
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }

        .question-description {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 15px;
            padding-left: 40px; /* 与问题标题对齐 */
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
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
        }

        .instruction-text {
            color: #6b7280;
            font-style: italic;
            font-size: 0.9rem;
            margin-top: 5px;
            padding-left: 40px; /* 与问题标题对齐 */
        }

        .required-mark {
            color: #ef4444;
            margin-left: 5px;
        }

        .question-box:nth-child(odd) {
            background-color: white;
        }
        
        .question-box:nth-child(even) {
            background-color: #fafbff;
        }

        .question-box.active {
            border-left-width: 8px;
            box-shadow: 0 8px 16px rgba(77, 109, 255, 0.15);
        }

        /* 添加问题卡片标题 */
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

        /* 进度条动画效果 */
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
        
        /* 完成度动画效果 */
        @keyframes complete-pulse {
            0% { box-shadow: 0 0 0 0 rgba(30, 190, 94, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(30, 190, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(30, 190, 94, 0); }
        }
        
        .option-item.selected {
            animation: highlight-selection 0.5s ease-out;
        }
        
        @keyframes highlight-selection {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
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
        
        /* 确保拖动时的元素不会重叠 */
        .sortable-list {
            position: relative;
            padding: 10px 0;
        }
        
        .sortable-list > * {
            margin-bottom: 12px !important;
            /* 移除过渡效果，提高响应速度 */
            transition: none;
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
        
        /* 防止拖动时的文字变形 */
        .sortable-item * {
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .question-box {
                padding: 20px;
                margin-bottom: 30px;
            }

            .question-box::after {
                left: 5%;
                width: 90%;
            }
            
            .question-title {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .question-type-badge {
                margin-left: 40px;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body class="light-theme">
    <div class="container-fluid bg-gradient min-vh-100 d-flex align-items-center justify-content-center p-3 py-5">
        <div class="questionnaire-card shadow-lg" style="width: 100%;">
            <!-- 页面头部 -->
            <div class="header-bar">
                问卷一：行李箱购买决策与基本需求调研
            </div>
            
            <!-- 进度指示器 -->
            <div class="progress rounded-0" style="height: 8px;">
                <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="card-body p-4">
                <!-- 问卷介绍 -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold">行李箱购买决策与基本需求</h2>
                    <p class="text-muted">本问卷旨在了解您在购买和使用行李箱时的决策因素与基本需求</p>
                </div>
                
                <!-- 问题 1 -->
                <div class="question-box multiple-choice" id="question1">
                    <div class="question-tag">问题 1</div>
                    <div class="question-title">
                        <span class="question-number number-1">1</span> 您目前使用的行李箱尺寸主要是：<span class="required-mark">*</span>
                        <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                    </div>
                    <div class="question-description">可以选择多个您经常使用的尺寸</div>
                    <div class="option-group">
                        <label class="option-item">
                            <input type="checkbox" name="question1" value="小型/登机箱(18-20寸)">
                            <span class="option-label">小型/登机箱(18-20寸)</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question1" value="中型箱(21-25寸)">
                            <span class="option-label">中型箱(21-25寸)</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question1" value="大型箱(26-30寸)">
                            <span class="option-label">大型箱(26-30寸)</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question1" value="特大号(30寸以上)">
                            <span class="option-label">特大号(30寸以上)</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question1" value="手提旅行袋/背包">
                            <span class="option-label">手提旅行袋/背包</span>
                        </label>
                    </div>
                </div>
                
                <!-- 问题 2 -->
                <div class="question-box sort-choice" id="question2">
                    <div class="question-tag">问题 2</div>
                    <div class="question-title">
                        <span class="question-number number-2">2</span> 您选择行李箱时最看重的因素是：<span class="required-mark">*</span>
                        <span class="question-type-badge badge-sort"><i class="fas fa-sort"></i>排序题</span>
                    </div>
                    <div class="question-description">请按照重要性排序，拖拽选项调整顺序（最重要的放在最上面）</div>
                    <ul class="sortable-list" id="sortableFactors">
                        <li class="sortable-item" data-value="价格">
                            <i class="fas fa-grip-lines drag-handle"></i>
                            <div class="item-number">1</div>
                            <span class="option-label">价格</span>
                        </li>
                        <li class="sortable-item" data-value="耐用程度">
                            <i class="fas fa-grip-lines drag-handle"></i>
                            <div class="item-number">2</div>
                            <span class="option-label">耐用程度</span>
                        </li>
                        <li class="sortable-item" data-value="重量轻便性">
                            <i class="fas fa-grip-lines drag-handle"></i>
                            <div class="item-number">3</div>
                            <span class="option-label">重量轻便性</span>
                        </li>
                        <li class="sortable-item" data-value="内部结构设计">
                            <i class="fas fa-grip-lines drag-handle"></i>
                            <div class="item-number">4</div>
                            <span class="option-label">内部结构设计</span>
                        </li>
                        <li class="sortable-item" data-value="功能创新性">
                            <i class="fas fa-grip-lines drag-handle"></i>
                            <div class="item-number">5</div>
                            <span class="option-label">功能创新性</span>
                        </li>
                    </ul>
                    <p class="instruction-text mt-3">提示：点击并拖动选项可调整排序</p>
                </div>
                
                <!-- 问题 3 -->
                <div class="question-box single-choice" id="question3">
                    <div class="question-tag">问题 3</div>
                    <div class="question-title">
                        <span class="question-number number-3">3</span> 您能接受的行李箱价格范围是：<span class="required-mark">*</span>
                        <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                    </div>
                    <div class="option-group">
                        <label class="option-item">
                            <input type="radio" name="question3" value="300元以下">
                            <span class="option-label">300元以下</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question3" value="300-800元">
                            <span class="option-label">300-800元</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question3" value="800-1500元">
                            <span class="option-label">800-1500元</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question3" value="1500-3000元">
                            <span class="option-label">1500-3000元</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question3" value="3000元以上">
                            <span class="option-label">3000元以上</span>
                        </label>
                    </div>
                </div>
                
                <!-- 问题 4 -->
                <div class="question-box single-choice" id="question4">
                    <div class="question-tag">问题 4</div>
                    <div class="question-title">
                        <span class="question-number number-4">4</span> 您更喜欢哪类行李箱材质？<span class="required-mark">*</span>
                        <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                    </div>
                    <div class="option-group">
                        <label class="option-item">
                            <input type="radio" name="question4" value="硬壳PC/ABS材质">
                            <span class="option-label">硬壳PC/ABS材质</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question4" value="软壳尼龙/帆布材质">
                            <span class="option-label">软壳尼龙/帆布材质</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question4" value="铝镁合金材质">
                            <span class="option-label">铝镁合金材质</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question4" value="皮革材质">
                            <span class="option-label">皮革材质</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question4" value="混合材质">
                            <span class="option-label">混合材质</span>
                        </label>
                    </div>
                </div>
                
                <!-- 问题 5 -->
                <div class="question-box multiple-choice" id="question5">
                    <div class="question-tag">问题 5</div>
                    <div class="question-title">
                        <span class="question-number number-5">5</span> 您目前使用的行李箱品牌是：<span class="required-mark">*</span>
                        <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                    </div>
                    <div class="question-description">可以选择多个您正在使用的品牌</div>
                    <div class="option-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="新秀丽(Samsonite)">
                                    <span class="option-label">新秀丽(Samsonite)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="美旅(American Tourister)">
                                    <span class="option-label">美旅(American Tourister)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="日默瓦(RIMOWA)">
                                    <span class="option-label">日默瓦(RIMOWA)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="途明(TUMI)">
                                    <span class="option-label">途明(TUMI)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="外交官(Diplomat)">
                                    <span class="option-label">外交官(Diplomat)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="爱华仕(OIWAS)">
                                    <span class="option-label">爱华仕(OIWAS)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="90分">
                                    <span class="option-label">90分</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="LEVEL8">
                                    <span class="option-label">LEVEL8</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="米家(MIJIA)">
                                    <span class="option-label">米家(MIJIA)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="ito">
                                    <span class="option-label">ito</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="戴乐世(DELSEY)">
                                    <span class="option-label">戴乐世(DELSEY)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="爱思(ACE)">
                                    <span class="option-label">爱思(ACE)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="皇冠(CROWN)">
                                    <span class="option-label">皇冠(CROWN)</span>
                                </label>
                                <label class="option-item">
                                    <input type="checkbox" name="question5" value="不莱玫(bromen)">
                                    <span class="option-label">不莱玫(bromen)</span>
                                </label>
                            </div>
                        </div>
                        <label class="option-item mt-2">
                            <input type="checkbox" name="question5" value="其他国产品牌">
                            <span class="option-label">其他国产品牌</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question5" value="其他国际品牌">
                            <span class="option-label">其他国际品牌</span>
                        </label>
                    </div>
                </div>
                
                <!-- 问题 6 -->
                <div class="question-box single-choice" id="question6">
                    <div class="question-tag">问题 6</div>
                    <div class="question-title">
                        <span class="question-number number-6">6</span> 您在选择行李箱重量时的偏好是：<span class="required-mark">*</span>
                        <span class="question-type-badge badge-single"><i class="fas fa-dot-circle"></i>单选题</span>
                    </div>
                    <div class="option-group">
                        <label class="option-item">
                            <input type="radio" name="question6" value="越轻越好，即使牺牲部分耐用性">
                            <span class="option-label">越轻越好，即使牺牲部分耐用性</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question6" value="中等重量，兼顾轻便与耐用">
                            <span class="option-label">中等重量，兼顾轻便与耐用</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question6" value="较重但更耐用，不太在意重量">
                            <span class="option-label">较重但更耐用，不太在意重量</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question6" value="取决于旅行目的">
                            <span class="option-label">取决于旅行目的</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question6" value="无特别偏好">
                            <span class="option-label">无特别偏好</span>
                        </label>
                        <label class="option-item">
                            <input type="radio" name="question6" value="采用新型轻量化材料（如碳纤维）">
                            <span class="option-label">采用新型轻量化材料（如碳纤维）</span>
                        </label>
                    </div>
                </div>
                
                <!-- 问题 7 -->
                <div class="question-box multiple-choice" id="question7">
                    <div class="question-tag">问题 7</div>
                    <div class="question-title">
                        <span class="question-number number-7">7</span> 您通常会在哪些渠道购买行李箱？<span class="required-mark">*</span>
                        <span class="question-type-badge badge-multiple"><i class="fas fa-check-square"></i>多选题</span>
                    </div>
                    <div class="option-group">
                        <label class="option-item">
                            <input type="checkbox" name="question7" value="线下专卖店">
                            <span class="option-label">线下专卖店</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question7" value="线下综合百货/商场">
                            <span class="option-label">线下综合百货/商场</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question7" value="天猫/京东等电商平台">
                            <span class="option-label">天猫/京东等电商平台</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question7" value="品牌官网">
                            <span class="option-label">品牌官网</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question7" value="二手交易平台">
                            <span class="option-label">二手交易平台</span>
                        </label>
                        <label class="option-item">
                            <input type="checkbox" name="question7" value="海外购买">
                            <span class="option-label">海外购买</span>
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

    <!-- JavaScript 库 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- 工具脚本 -->
    <script src="../../共用资源/js/utils/theme.js"></script>
    <script src="../../共用资源/js/utils/accessibility.js"></script>
    <script src="../../共用资源/js/utils/storage.js"></script>
    <script src="../../共用资源/js/utils/session.js"></script>
    
    <script>
        // 页面加载完成后执行
        $(document).ready(function() {
            // 禁用长按选择文本，提高移动设备上的拖拽体验
            document.addEventListener('touchstart', function(e) {
                if($(e.target).closest('.sortable-item').length) {
                    e.preventDefault();
                }
            }, {passive: false});
            
            // 创建进度存储工具
            const ProgressStorage = {
                // 存储键名和版本标记
                PROGRESS_KEY: 'survey_progress_value',
                VERSION_KEY: 'survey_progress_version',
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
            
            // === 处理表单交互 ===
            
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
            
            // === 排序题处理 ===
            
            // 初始化排序功能
            var sortableList = document.getElementById('sortableFactors');
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
                    setData: function (dataTransfer, dragEl) {
                        // 优化拖拽数据传输
                        dataTransfer.setData('text', '');
                    },
                    onStart: function(evt) {
                        $(evt.item).closest('.sort-choice').data('user-interacted', true);
                        updateProgressBar();
                    },
                    onEnd: function(evt) {
                        // 重新编号
                        $('#sortableFactors .item-number').each(function(index) {
                            $(this).text(index + 1);
                        });
                    }
                });
                
                // 为排序项添加提示效果
                $('#sortableFactors').find('.sortable-item').each(function() {
                    $(this).attr('title', '点击并拖动此项调整顺序');
                });
            }
            
            // === 导航按钮 ===
            
            // 返回选择界面
            $('.back-btn').click(function() {
                window.location.href = '../主页面/主界面.php';
            });
            
            // 提交问卷
            $('.next-btn').click(function() {
                // 检查是否所有问题都已回答
                const allAnswered = checkAllQuestionsAnswered();
                
                if (allAnswered) {
                    // 收集所有问题的答案
                    const formData = new FormData();
                    
                    // 问题1：行李箱尺寸（多选题）
                    const q1Values = [];
                    $('input[name="question1"]:checked').each(function() {
                        q1Values.push($(this).val());
                    });
                    q1Values.forEach(value => formData.append('q1[]', value));
                    
                    // 问题2：行李箱选择因素（排序题）
                    const q2Order = [];
                    $('.sortable-list li').each(function() {
                        q2Order.push($(this).data('value'));
                    });
                    formData.append('q2_order', JSON.stringify(q2Order));
                    
                    // 问题3：价格范围（单选题）
                    formData.append('q3', $('input[name="question3"]:checked').val());
                    
                    // 问题4：行李箱材质（单选题）
                    formData.append('q4', $('input[name="question4"]:checked').val());
                    
                    // 问题5：行李箱品牌（多选题）
                    const q5Values = [];
                    $('input[name="question5"]:checked').each(function() {
                        q5Values.push($(this).val());
                    });
                    q5Values.forEach(value => formData.append('q5[]', value));
                    
                    // 问题6：重量偏好（单选题）
                    formData.append('q6', $('input[name="question6"]:checked').val());
                    
                    // 问题7：购买渠道（多选题）
                    const q7Values = [];
                    $('input[name="question7"]:checked').each(function() {
                        q7Values.push($(this).val());
                    });
                    q7Values.forEach(value => formData.append('q7[]', value));
                    
                    // 使用fetch API提交数据
                    fetch('submit_questionnaire1.php', {
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
            
            // === 问题高亮效果 ===
            
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
            
            // 添加"全部填写"辅助按钮（仅用于测试）
            function addTestButton() {
                const $testButton = $('<button class="btn btn-sm btn-primary position-fixed" style="bottom: 60px; right: 10px; z-index: 1000;">一键填写所有题目</button>');
                $testButton.on('click', function() {
                    // 选中所有单选题的第一个选项
                    $('.single-choice').each(function() {
                        $(this).find('input[type="radio"]').first().prop('checked', true).change();
                    });
                    
                    // 选中所有多选题的第一个选项
                    $('.multiple-choice').each(function() {
                        $(this).find('input[type="checkbox"]').first().prop('checked', true).change();
                    });
                    
                    // 标记所有排序题为已交互
                    $('.sort-choice').each(function() {
                        $(this).data('user-interacted', true);
                    });
                    
                    // 更新进度条
                    updateProgressBar();
                });
                $('body').append($testButton);
            }
            
            // 仅用于测试，实际使用时应注释掉
            // addTestButton();
        });

        // 添加成功消息提示函数（如果未定义）
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