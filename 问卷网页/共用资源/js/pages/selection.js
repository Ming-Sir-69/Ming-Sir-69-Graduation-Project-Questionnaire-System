/**
 * 行李箱调研系统 - 问卷选择页面脚本
 * 处理问卷选择和状态管理
 */

// 全局变量
let completedQuestionnaires = [];

$(document).ready(function() {
    // 初始化页面
    initPage();
    
    // 问卷选择事件
    $(".questionnaire-card button").on("click", function() {
        const questionnaireId = $(this).data("questionnaire-id");
        navigateToQuestionnaire(questionnaireId);
    });
    
    // 提交所有问卷按钮
    $("#submitAllButton").on("click", function() {
        submitAllQuestionnaires();
    });
    
    // 隐私政策链接点击
    $("#privacyLink").on("click", function(e) {
        e.preventDefault();
        showPrivacyPolicy();
    });
    
    // 返回基本信息按钮点击 - 确保直接绑定
    $("#backToBasicInfoBtn").on("click", function() {
        console.log("返回基本信息按钮被点击");
        navigateToBasicInfo();
    });
});

/**
 * 初始化页面
 */
function initPage() {
    // 获取基本信息
    const basicInfo = SurveyStorage.getBasicInfo();
    if (!basicInfo) {
        // 如果没有基本信息，重定向到入口页面
        window.location.href = "../../入口界面/index.php";
        return;
    }
    
    // 不再需要显示用户信息，因为已经由PHP处理
    // displayUserInfo(basicInfo);
    
    // 检查已完成的问卷并更新UI
    updateCompletedQuestionnaires();
    
    // 更新提交按钮状态
    updateSubmitButtonState();
    
    // 页面入场动画
    animateEntrance();
    
    // 绑定高可读性模式事件
    bindAccessibilityEvents();
}

/**
 * 显示用户基本信息 - 不再使用，由PHP处理
 * @param {Object} basicInfo - 用户基本信息对象
 */
/* 
function displayUserInfo(basicInfo) {
    const userInfoDiv = document.getElementById('userInfoDisplay');
    if (!userInfoDiv) return;
    
    // 性别映射
    const genderMap = {
        'male': '男',
        'female': '女',
        'other': '不愿透露'
    };
    
    // 使用频率映射
    const frequencyMap = {
        'weekly': '一周多次',
        'monthly': '一个月1-2次',
        'quarterly': '一季度1-2次',
        'semiannually': '半年1-2次',
        'yearly': '一年不到1次'
    };
    
    // 构建显示内容
    const infoHTML = `
        <span class="badge bg-light text-dark me-2">
            <i class="fas fa-user me-1"></i> ${genderMap[basicInfo.gender] || '未知'}
        </span>
        <span class="badge bg-light text-dark me-2">
            <i class="fas fa-birthday-cake me-1"></i> ${basicInfo.ageGroup || '未知'}
        </span>
        <span class="badge bg-light text-dark me-2">
            <i class="fas fa-clock me-1"></i> ${frequencyMap[basicInfo.frequency] || '未知'}
        </span>
        <span class="badge bg-light text-dark">
            <i class="fas fa-suitcase me-1"></i> ${basicInfo.luggageCount || '未知'} 个行李箱
        </span>
    `;
    
    userInfoDiv.innerHTML = infoHTML;
}
*/

/**
 * 检查已完成的问卷并更新UI
 */
function updateCompletedQuestionnaires() {
    // 重置完成状态
    completedQuestionnaires = [];
    
    // 检查各问卷完成状态
    for (let i = 1; i <= 3; i++) {
        const questionnaireData = SurveyStorage.getQuestionnaireData(i);
        const card = document.querySelector(`.questionnaire-card[data-questionnaire-id="${i}"]`);
        
        if (questionnaireData && questionnaireData.completed) {
            // 问卷已完成
            completedQuestionnaires.push(i);
            
            // 更新UI显示完成状态
            if (card) {
                // 如果没有完成标记，添加一个
                if (!card.querySelector('.questionnaire-completed')) {
                    const completedMark = document.createElement('div');
                    completedMark.className = 'questionnaire-completed';
                    completedMark.innerHTML = '<i class="fas fa-check-circle me-1"></i> 已完成';
                    card.appendChild(completedMark);
                }
                
                // 更新按钮文本
                const button = card.querySelector('.start-questionnaire');
                if (button) {
                    button.innerHTML = '<i class="fas fa-edit me-1"></i> 查看/编辑';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-outline-primary');
                }
            }
        } else {
            // 问卷未完成，确保UI显示未完成状态
            if (card) {
                // 移除完成标记(如果有)
                const completedMark = card.querySelector('.questionnaire-completed');
                if (completedMark) {
                    completedMark.remove();
                }
                
                // 更新按钮文本
                const button = card.querySelector('.start-questionnaire');
                if (button) {
                    button.innerHTML = '开始填写';
                    button.classList.add('btn-primary');
                    button.classList.remove('btn-outline-primary');
                }
            }
        }
    }
}

/**
 * 更新提交按钮状态
 */
function updateSubmitButtonState() {
    const submitButton = document.getElementById('submitAllButton');
    if (!submitButton) return;
    
    if (completedQuestionnaires.length > 0) {
        // 至少完成了一份问卷，启用提交按钮
        submitButton.disabled = false;
    } else {
        // 没有完成任何问卷，禁用提交按钮
        submitButton.disabled = true;
    }
}

/**
 * 页面入场动画
 */
function animateEntrance() {
    const cards = document.querySelectorAll('.questionnaire-card');
    
    anime.timeline({
        easing: 'easeOutExpo'
    })
    .add({
        targets: '.completed-info',
        opacity: [0, 1],
        translateY: [-20, 0],
        duration: 800
    })
    .add({
        targets: cards,
        opacity: [0, 1],
        translateY: [-30, 0],
        delay: anime.stagger(100),
        duration: 800
    }, '-=400')
    .add({
        targets: '.submit-section',
        opacity: [0, 1],
        translateY: [-20, 0],
        duration: 800
    }, '-=600');
}

/**
 * 绑定高可读性模式事件
 */
function bindAccessibilityEvents() {
    // 监听可访问性变化事件
    document.addEventListener('accessibilityChanged', function(e) {
        console.log('高可读性模式状态:', e.detail.isHighReadabilityMode);
    });
    
    // 确保按钮存在时绑定事件
    const accessibilityButton = document.getElementById('accessibilityToggleButton');
    if (accessibilityButton && typeof AccessibilityManager === 'undefined') {
        // 如果AccessibilityManager未定义，提供简单的替代功能
        accessibilityButton.addEventListener('click', function() {
            document.body.classList.toggle('high-readability');
            this.classList.toggle('active');
        });
    }
}

/**
 * 导航到指定问卷
 * @param {string} questionnaireId - 问卷ID
 */
function navigateToQuestionnaire(questionnaireId) {
    // 保存当前状态
    if (typeof SurveyStorage !== 'undefined') {
        SurveyStorage.setLastState('selection');
    }
    
    // 检查高可读性模式状态
    let highReadabilityMode = false;
    try {
        const settings = localStorage.getItem('luggage_survey_accessibility');
        if (settings) {
            const parsedSettings = JSON.parse(settings);
            highReadabilityMode = parsedSettings.isHighReadabilityMode || false;
        }
    } catch (error) {
        console.error('Failed to get accessibility settings:', error);
    }
    
    // 根据问卷ID跳转到对应页面
    switch(questionnaireId) {
        case '1':
            window.location.href = "../问卷1/questionnaire1.php" + (highReadabilityMode ? '?highReadability=true' : '');
            break;
        case '2':
            window.location.href = "../问卷2/questionnaire2.php" + (highReadabilityMode ? '?highReadability=true' : '');
            break;
        case '3':
            window.location.href = "../问卷3/questionnaire3.php" + (highReadabilityMode ? '?highReadability=true' : '');
            break;
        default:
            console.error('未知的问卷ID:', questionnaireId);
    }
}

/**
 * 导航回基本信息页面
 * 点击按钮后清除所有已填写的信息，重置状态
 */
function navigateToBasicInfo() {
    console.log("正在导航回基本信息页面...");
    
    // 确认用户是否确实要返回并清除所有信息
    if (confirm("返回修改基本信息将删除所有已保存的数据！确定要继续吗？")) {
        try {
            // 显示加载提示
            showLoadingOverlay("正在处理您的请求...");
            
            // 清除本地存储的数据
            if (typeof SurveyStorage !== 'undefined') {
                SurveyStorage.clearAllData();
            }
            
            // 调用新的删除用户数据API
            fetch('../../共用资源/php/delete_user_data.php', {
                method: 'POST',
                credentials: 'same-origin' // 包含会话cookie
            })
            .then(response => response.json())
            .then(data => {
                hideLoadingOverlay();
                console.log('删除用户数据结果:', data);
                
                if (data.success) {
                    // 删除成功，重定向到入口页面
                    window.location.href = '../../入口界面/index.php';
                } else {
                    // 删除失败，但仍然尝试重定向
                    alert('删除数据时发生错误: ' + data.message);
                    window.location.href = '../../入口界面/index.php';
                }
            })
            .catch(error => {
                hideLoadingOverlay();
                console.error('删除用户数据请求失败:', error);
                alert('系统错误，请稍后再试');
                
                // 即使出错也尝试重定向
                window.location.href = '../../入口界面/index.php';
            });
        } catch (error) {
            hideLoadingOverlay();
            console.error('导航回基本信息页面时出错:', error);
            alert('操作过程中出现错误，请重试');
        }
    }
}

/**
 * 显示加载中遮罩
 * @param {string} message - 显示的消息
 */
function showLoadingOverlay(message = '加载中...') {
    // 检查是否已存在加载遮罩
    let overlay = document.querySelector('.loading-overlay');
    
    if (overlay) {
        // 如果已存在，更新消息
        const messageElement = overlay.querySelector('.loading-message');
        if (messageElement) {
            messageElement.textContent = message;
        }
        overlay.style.display = 'flex';
        return;
    }
    
    // 创建加载遮罩
    overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    overlay.style.display = 'flex';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';
    overlay.style.zIndex = '9999';
    
    // 创建加载内容容器
    const content = document.createElement('div');
    content.style.backgroundColor = 'white';
    content.style.padding = '20px 30px';
    content.style.borderRadius = '8px';
    content.style.display = 'flex';
    content.style.flexDirection = 'column';
    content.style.alignItems = 'center';
    content.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    
    // 创建加载图标
    const spinner = document.createElement('div');
    spinner.className = 'spinner-border text-primary';
    spinner.setAttribute('role', 'status');
    
    // 创建消息文本
    const messageElement = document.createElement('p');
    messageElement.className = 'loading-message';
    messageElement.style.marginTop = '15px';
    messageElement.style.marginBottom = '0';
    messageElement.textContent = message;
    
    // 组装元素
    content.appendChild(spinner);
    content.appendChild(messageElement);
    overlay.appendChild(content);
    
    // 添加到文档
    document.body.appendChild(overlay);
}

/**
 * 隐藏加载中遮罩
 */
function hideLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

/**
 * 提交所有问卷
 */
function submitAllQuestionnaires() {
    console.log("正在提交所有问卷...");
    
    // 检查是否有已完成的问卷
    if (completedQuestionnaires.length === 0) {
        alert('请至少完成一份问卷后再提交');
        return;
    }
    
    // 显示确认对话框
    if (confirm('确定要提交所有已完成的问卷吗？提交后将无法再修改。')) {
        // 显示加载提示
        showLoadingOverlay('正在提交问卷数据，请稍候...');
        
        try {
            // 准备所有数据用于提交
            const data = {
                action: 'submit', // 提交并结束会话
                all_questionnaires: true
            };
            
            // 发送到API提交所有问卷
            fetch('../../共用资源/php/api/submit_questionnaire.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(result => {
                hideLoadingOverlay();
                
                if (result.success) {
                    // 提交成功，显示成功消息
                    Swal.fire({
                        title: '提交成功！',
                        text: '感谢您参与行李箱用户体验调研',
                        icon: 'success',
                        confirmButtonText: '完成'
                    }).then(() => {
                        // 清除本地存储数据
                        if (typeof SurveyStorage !== 'undefined') {
                            SurveyStorage.clearAllData();
                        }
                        
                        // 重定向到感谢页面
                        window.location.href = '../../入口界面/thank_you.php';
                    });
                } else {
                    // 提交失败，显示错误信息
                    Swal.fire({
                        title: '提交失败',
                        text: result.error || '提交问卷时出现错误',
                        icon: 'error',
                        confirmButtonText: '重试'
                    });
                }
            })
            .catch(error => {
                hideLoadingOverlay();
                console.error('提交问卷时出错:', error);
                
                Swal.fire({
                    title: '提交失败',
                    text: '网络或服务器错误，请稍后重试',
                    icon: 'error',
                    confirmButtonText: '确定'
                });
            });
        } catch (error) {
            hideLoadingOverlay();
            console.error('提交问卷过程中发生错误:', error);
            alert('提交过程中出现错误，请重试');
        }
    }
}

/**
 * 显示隐私政策
 */
function showPrivacyPolicy() {
    // 此处应显示隐私政策模态框
    alert("隐私政策内容将在后续开发中实现。");
} 