/**
 * 行李箱调研系统 - UI辅助工具函数
 * 提供常用的UI相关辅助功能
 */

/**
 * 显示加载覆盖层
 * @param {string} message - 显示的加载消息
 */
function showLoadingOverlay(message = '加载中...') {
    // 检查是否已存在加载覆盖层
    let overlay = document.getElementById('loading-overlay');
    if (!overlay) {
        // 创建加载覆盖层
        overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        `;
        
        // 创建加载内容容器
        const content = document.createElement('div');
        content.style.cssText = `
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 80%;
        `;
        
        // 创建加载图标
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border text-primary';
        spinner.setAttribute('role', 'status');
        spinner.style.cssText = `
            width: 3rem;
            height: 3rem;
            margin: 0 auto 15px;
        `;
        spinner.innerHTML = '<span class="visually-hidden">加载中...</span>';
        
        // 创建消息元素
        const messageElement = document.createElement('p');
        messageElement.id = 'loading-message';
        messageElement.style.cssText = `
            margin: 0;
            font-size: 1rem;
            color: #333;
        `;
        messageElement.textContent = message;
        
        // 组装DOM
        content.appendChild(spinner);
        content.appendChild(messageElement);
        overlay.appendChild(content);
        
        // 添加到文档
        document.body.appendChild(overlay);
    } else {
        // 更新已存在的加载消息
        const messageElement = document.getElementById('loading-message');
        if (messageElement) {
            messageElement.textContent = message;
        }
        
        // 确保覆盖层可见
        overlay.style.display = 'flex';
    }
}

/**
 * 隐藏加载覆盖层
 */
function hideLoadingOverlay() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

/**
 * 显示提示消息
 * @param {string} message - 消息内容
 * @param {string} type - 消息类型 (success, info, warning, error)
 * @param {number} duration - 显示时长(毫秒)
 */
function showToast(message, type = 'info', duration = 3000) {
    // 检查是否支持Bootstrap Toast组件
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        // 检查toast容器是否存在
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // 根据类型设置样式
        let bgClass = 'bg-info';
        let icon = '<i class="fas fa-info-circle me-2"></i>';
        
        switch (type) {
            case 'success':
                bgClass = 'bg-success';
                icon = '<i class="fas fa-check-circle me-2"></i>';
                break;
            case 'warning':
                bgClass = 'bg-warning';
                icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
                break;
            case 'error':
                bgClass = 'bg-danger';
                icon = '<i class="fas fa-times-circle me-2"></i>';
                break;
        }
        
        // 创建Toast元素
        const toastId = 'toast-' + Date.now();
        const toastElement = document.createElement('div');
        toastElement.id = toastId;
        toastElement.className = 'toast';
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.innerHTML = `
            <div class="toast-header ${bgClass} text-white">
                ${icon}
                <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        // 添加到容器
        toastContainer.appendChild(toastElement);
        
        // 初始化并显示Toast
        const toastInstance = new bootstrap.Toast(toastElement, {
            delay: duration
        });
        
        toastInstance.show();
        
        // 监听隐藏事件，移除元素
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    } else {
        // 如果不支持Bootstrap Toast，使用alert
        alert(`${type.toUpperCase()}: ${message}`);
    }
}

/**
 * 创建并显示确认对话框
 * @param {string} title - 对话框标题
 * @param {string} message - 对话框消息
 * @param {Function} onConfirm - 确认回调函数
 * @param {Function} onCancel - 取消回调函数
 */
function showConfirmDialog(title, message, onConfirm, onCancel = null) {
    // 检查是否可以使用SweetAlert2
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                if (typeof onCancel === 'function') {
                    onCancel();
                }
            }
        });
    } else {
        // 使用原生确认对话框
        const confirmed = confirm(message);
        if (confirmed && typeof onConfirm === 'function') {
            onConfirm();
        } else if (!confirmed && typeof onCancel === 'function') {
            onCancel();
        }
    }
} 