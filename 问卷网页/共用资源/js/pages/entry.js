/**
 * 行李箱调研系统 - 入口页面脚本
 * 处理基本信息表单的交互和提交
 */

$(document).ready(function() {
    // 初始化
    initPage();
    
    // 表单提交处理
    $("#basicInfoForm").on("submit", function(e) {
        // 表单验证
        if (!validateForm()) {
            e.preventDefault(); // 只在表单无效时阻止提交
            return false;
        }
        
        // 显示提交动画并进行实际提交
        if (!window.isAnimatingSubmit) {
            e.preventDefault(); // 暂时阻止默认提交
            window.isAnimatingSubmit = true;
            
            // 保存表单对象的引用（重要！）
            const form = $(this);
            
            // 显示提交动画，动画期间保持表单存在
            showSubmitAnimation().then(() => {
                // 动画结束后，安全地提交表单
                window.isAnimatingSubmit = false;
                form[0].submit(); // 使用保存的引用
            });
            
            return false;
        }
    });
    
    // 卡片选项点击事件
    setupCardSelectionEvents();
    
    // 显示调研信息模态框
    if (!localStorage.getItem('survey_info_seen')) {
        // 不使用延时，直接显示模态框
        const infoModal = new bootstrap.Modal(document.getElementById('surveyInfoModal'));
        infoModal.show();
        localStorage.setItem('survey_info_seen', 'true');
    }
});

/**
 * 初始化页面
 */
function initPage() {
    // 不再主动清除数据，让PHP管理状态
    
    // 确保表单是空白状态
    resetForm();
    
    // 动画进入效果
    animateEntrance();
}

/**
 * 重置表单所有字段为初始状态
 */
function resetForm() {
    // 清除所有单选按钮的选中状态
    $('input[name="gender"]').prop('checked', false);
    $('input[name="ageGroup"]').prop('checked', false);
    $('input[name="frequency"]').prop('checked', false);
    $('input[name="luggageCount"]').prop('checked', false);
    
    // 移除表单验证样式
    $('#basicInfoForm').removeClass('was-validated');
}

/**
 * 填充表单数据
 * @param {Object} data - 保存的表单数据
 */
function populateFormWithSavedData(data) {
    // 性别
    if (data.gender) {
        $(`input[name="gender"][value="${data.gender}"]`).prop('checked', true);
    }
    
    // 年龄段
    if (data.ageGroup) {
        $(`input[name="ageGroup"][value="${data.ageGroup}"]`).prop('checked', true);
    }
    
    // 使用频率
    if (data.frequency) {
        $(`input[name="frequency"][value="${data.frequency}"]`).prop('checked', true);
    }
    
    // 行李箱数量
    if (data.luggageCount) {
        $(`input[name="luggageCount"][value="${data.luggageCount}"]`).prop('checked', true);
    }
}

/**
 * 设置卡片选择事件
 */
function setupCardSelectionEvents() {
    // 点击标签时选中对应的单选按钮
    $('.option-card, .frequency-card, .count-card, .age-card').on('click', function() {
        const radioId = $(this).attr('for');
        $('#' + radioId).prop('checked', true);
        
        // 动画效果
        anime({
            targets: this,
            scale: [1, 1.05, 1],
            duration: 300,
            easing: 'easeInOutQuad'
        });
    });
}

/**
 * 验证表单
 * @returns {Boolean} 表单是否有效
 */
function validateForm() {
    // 获取所有必填字段
    const form = document.getElementById('basicInfoForm');
    
    // Bootstrap 5 表单验证
    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        form.classList.add('was-validated');
        
        // 滚动到第一个错误字段
        const firstInvalidField = form.querySelector(':invalid');
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return false;
    }
    
    form.classList.add('was-validated');
    return true;
}

/**
 * 收集表单数据
 * @returns {Object} 表单数据对象
 */
function collectFormData() {
    return {
        gender: $('input[name="gender"]:checked').val(),
        ageGroup: $('input[name="ageGroup"]:checked').val(),
        frequency: $('input[name="frequency"]:checked').val(),
        luggageCount: $('input[name="luggageCount"]:checked').val(),
        skipQuestion: $('input[name="luggageCount"]:checked').data('skip') === true
    };
}

/**
 * 显示提交动画
 * @returns {Promise} 动画完成的Promise
 */
function showSubmitAnimation() {
    return new Promise((resolve) => {
        // 更新进度条
        const progressBar = $('.progress-bar');
        progressBar.css('width', '0%');
        
        // 不要替换.card-body的内容，而是隐藏表单并添加动画元素
        const formContainer = $('.card-body');
        const formContent = $('#basicInfoForm').hide(); // 隐藏表单但不移除
        
        // 创建加载动画元素
        const loadingElement = $(`
            <div class="loading-animation text-center py-5">
                <div class="luggage-icon mb-4">
                    <i class="fas fa-suitcase-rolling fa-4x text-primary animate-pulse"></i>
                </div>
                <h3 class="mb-3">正在处理您的信息</h3>
                <p class="lead">请稍候...</p>
            </div>
        `);
        
        // 添加动画元素但不删除表单
        formContainer.append(loadingElement);
        
        // 进度条动画
        progressBar.animate({
            width: '100%'
        }, 1500, function() {
            setTimeout(() => {
                // 动画完成后处理
                resolve();
            }, 500);
        });
    });
}

/**
 * 重定向到问卷选择页面 - 不再使用此函数，改由PHP处理重定向
 * 保留此函数仅为兼容性考虑
 */
function redirectToQuestionnaireSelection() {
    console.log("重定向函数已废弃，改由PHP处理");
    // 这个函数不再主动调用，保留只是为了避免其他地方可能的引用报错
}

/**
 * 入场动画
 */
function animateEntrance() {
    anime({
        targets: '.survey-card',
        translateY: [20, 0],
        opacity: [0, 1],
        duration: 800,
        easing: 'easeOutQuad'
    });
} 