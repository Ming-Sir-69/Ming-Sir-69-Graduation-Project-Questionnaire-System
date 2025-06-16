/**
 * 行李箱调研系统 - 可访问性功能管理
 * 主要管理高可读性模式的切换与应用
 */

// 可访问性管理器
const AccessibilityManager = {
    // 高可读性模式状态
    isHighReadabilityMode: false,
    
    // 存储键名
    STORAGE_KEY: 'luggage_survey_accessibility',
    
    /**
     * 初始化可访问性功能
     */
    init: function() {
        // 从本地存储加载设置
        this.loadSettings();
        
        // 绑定切换按钮事件
        this.bindEvents();
        
        // 应用初始状态
        this.applySettings();
        
        // 初始化完成后触发事件
        this.triggerEvent('accessibilityInitialized', { 
            isHighReadabilityMode: this.isHighReadabilityMode 
        });

        // 检查URL参数是否有高可读性模式标记（用于从入口页面过来时自动应用）
        this.checkUrlParams();
    },
    
    /**
     * 检查URL参数中是否有高可读性模式标记
     */
    checkUrlParams: function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('highReadability') && urlParams.get('highReadability') === 'true') {
            // 如果URL中指定了高可读性模式，并且当前不是高可读性模式，则切换
            if (!this.isHighReadabilityMode) {
                this.isHighReadabilityMode = true;
                this.applySettings();
                this.saveSettings();
            }
        }
    },
    
    /**
     * 从本地存储加载设置
     */
    loadSettings: function() {
        try {
            const settings = localStorage.getItem(this.STORAGE_KEY);
            if (settings) {
                const parsedSettings = JSON.parse(settings);
                this.isHighReadabilityMode = parsedSettings.isHighReadabilityMode || false;
            }
        } catch (error) {
            console.error('Failed to load accessibility settings:', error);
            // 出错时使用默认设置
            this.isHighReadabilityMode = false;
        }
    },
    
    /**
     * 保存设置到本地存储
     */
    saveSettings: function() {
        try {
            const settings = {
                isHighReadabilityMode: this.isHighReadabilityMode
            };
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(settings));
        } catch (error) {
            console.error('Failed to save accessibility settings:', error);
        }
    },
    
    /**
     * 绑定事件处理程序
     */
    bindEvents: function() {
        // 获取切换按钮
        const toggleButton = document.getElementById('accessibilityToggleButton');
        if (toggleButton) {
            // 绑定点击事件
            toggleButton.addEventListener('click', () => {
                this.toggleHighReadabilityMode();
            });
        }
    },
    
    /**
     * 切换高可读性模式
     */
    toggleHighReadabilityMode: function() {
        // 切换状态
        this.isHighReadabilityMode = !this.isHighReadabilityMode;
        
        // 应用设置
        this.applySettings();
        
        // 保存设置
        this.saveSettings();
        
        // 触发事件
        this.triggerEvent('accessibilityChanged', { 
            isHighReadabilityMode: this.isHighReadabilityMode 
        });
    },
    
    /**
     * 应用当前设置
     */
    applySettings: function() {
        // 应用高可读性模式
        if (this.isHighReadabilityMode) {
            document.body.classList.add('high-readability');
            this.updateToggleButtonUI(true);
        } else {
            document.body.classList.remove('high-readability');
            this.updateToggleButtonUI(false);
        }
    },
    
    /**
     * 更新切换按钮UI
     * @param {boolean} isActive - 高可读性模式是否启用
     */
    updateToggleButtonUI: function(isActive) {
        const toggleButton = document.getElementById('accessibilityToggleButton');
        if (!toggleButton) return;
        
        if (isActive) {
            // 高可读性模式启用状态
            toggleButton.classList.add('active');
            toggleButton.setAttribute('aria-pressed', 'true');
        } else {
            // 高可读性模式禁用状态
            toggleButton.classList.remove('active');
            toggleButton.setAttribute('aria-pressed', 'false');
        }
    },
    
    /**
     * 触发自定义事件
     * @param {string} eventName - 事件名称
     * @param {Object} detail - 事件详情
     */
    triggerEvent: function(eventName, detail) {
        const event = new CustomEvent(eventName, { detail });
        document.dispatchEvent(event);
    }
}; 