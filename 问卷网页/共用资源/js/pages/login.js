/**
 * 登录表单验证
 * 保留基本的表单验证提示，同时允许表单提交到后端PHP处理
 */
document.addEventListener('DOMContentLoaded', function() {
    // 获取表单和提示元素
    const loginModal = document.getElementById('adminLoginModal');
    const loginForm = document.getElementById('adminLoginForm');
    const usernameInput = document.getElementById('adminUsername');
    const passwordInput = document.getElementById('adminPassword');
    const errorMsg = document.getElementById('loginErrorMsg');
    
    // 确保表单提交目标正确设置
    if (loginForm) {
        // 检查并确保表单提交到正确的处理文件
        const currentAction = loginForm.getAttribute('action');
        if (!currentAction || !currentAction.includes('admin_login.php')) {
            console.log('修正登录表单提交路径');
            loginForm.setAttribute('action', '../共用资源/php/admin_login.php');
            loginForm.setAttribute('method', 'post');
        }
    }
    
    // 模态框打开时自动聚焦用户名输入框
    if (loginModal && typeof bootstrap !== 'undefined') {
        loginModal.addEventListener('shown.bs.modal', function() {
            if (usernameInput) {
                usernameInput.focus();
            }
        });
        
        // 模态框关闭时清除错误信息
        loginModal.addEventListener('hidden.bs.modal', function() {
            if (errorMsg) {
                errorMsg.style.display = 'none';
                errorMsg.textContent = '';
            }
            
            // 发送一个简单的请求来清除会话中的错误信息
            fetch('../共用资源/php/clear_admin_error.php')
                .then(response => console.log('错误信息已清除'))
                .catch(error => console.error('清除错误信息失败', error));
        });
    }
    
    // 为表单字段添加焦点事件，当用户开始输入时隐藏错误信息
    if (usernameInput) {
        usernameInput.addEventListener('focus', function() {
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('focus', function() {
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        });
    }
    
    // 表单提交前的基本验证
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = usernameInput ? usernameInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value.trim() : '';
            
            // 如果用户名或密码为空，显示提示信息
            if (!username || !password) {
                e.preventDefault(); // 阻止提交
                showError('请输入用户名和密码');
                return false;
            }
            
            // 添加调试信息
            console.log('表单提交到: ' + loginForm.getAttribute('action'));
            
            // 允许表单正常提交到后端PHP处理
            return true;
        });
    }
    
    /**
     * 显示错误提示
     * @param {string} message - 错误信息
     */
    function showError(message) {
        if (errorMsg) {
            errorMsg.textContent = message;
            errorMsg.style.display = 'block';
        }
    }
    
    // 检查URL参数是否包含错误信息
    function checkUrlForErrors() {
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        if (error) {
            showError(decodeURIComponent(error));
        }
    }
    
    // 页面加载时检查错误参数
    checkUrlForErrors();
}); 