/**
 * 管理员页面功能
 * 处理管理员页面特有的功能
 */
document.addEventListener('DOMContentLoaded', function() {
    // 初始化主题管理器
    if (window.ThemeManager) {
        ThemeManager.init();
    }
    
    // 检查是否已加载AdminAuth模块
    if (!window.AdminAuth) {
        console.error('AdminAuth模块未加载');
        showUnauthorizedContent();
        return;
    }
    
    // 检查管理员登录状态
    checkAdminAuth();
    
    // 添加登出事件监听
    setupLogoutButton();
    
    // 更新页面上显示的登录时间
    updateLastLoginTime();
});

/**
 * 检查管理员登录状态
 */
function checkAdminAuth() {
    if (window.AdminAuth.validateSession()) {
        showAdminContent();
    } else {
        showUnauthorizedContent();
    }
}

/**
 * 显示管理员内容
 */
function showAdminContent() {
    const adminContent = document.getElementById('adminContent');
    const unauthorizedContent = document.getElementById('unauthorizedContent');
    
    if (adminContent) {
        adminContent.style.display = 'block';
    }
    
    if (unauthorizedContent) {
        unauthorizedContent.style.display = 'none';
    }
}

/**
 * 显示未授权提示
 */
function showUnauthorizedContent() {
    const adminContent = document.getElementById('adminContent');
    const unauthorizedContent = document.getElementById('unauthorizedContent');
    
    if (adminContent) {
        adminContent.style.display = 'none';
    }
    
    if (unauthorizedContent) {
        unauthorizedContent.style.display = 'block';
    }
}

/**
 * 设置登出按钮事件
 */
function setupLogoutButton() {
    const logoutBtn = document.getElementById('logoutBtn');
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

/**
 * 更新最后登录时间显示
 */
function updateLastLoginTime() {
    const lastLoginTimeEl = document.getElementById('lastLoginTime');
    
    if (lastLoginTimeEl) {
        try {
            const sessionData = JSON.parse(localStorage.getItem('luggage_survey_admin_session'));
            if (sessionData && sessionData.timestamp) {
                const lastLogin = new Date(sessionData.timestamp);
                lastLoginTimeEl.textContent = lastLogin.toLocaleString('zh-CN', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        } catch (e) {
            console.error('解析会话数据出错:', e);
        }
    }
}

/**
 * 管理员登出
 */
function logout() {
    // 结束会话
    window.AdminAuth.endSession();
    
    // 跳转到登录页
    window.location.href = '/入口界面/index.php';
} 