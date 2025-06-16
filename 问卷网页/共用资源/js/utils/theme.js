/**
 * 行李箱调研系统 - 主题切换工具
 * 处理日夜主题切换和高可读性模式
 */

const ThemeManager = (function() {
    // 存储键名
    const STORAGE_KEYS = {
        THEME: 'survey_theme_mode',
        HIGH_READABILITY: 'survey_high_readability'
    };

    // 主题模式
    const THEMES = {
        LIGHT: 'light-theme',
        DARK: 'dark-theme'
    };

    // 初始化主题
    function initTheme() {
        // 获取存储的主题设置
        const savedTheme = localStorage.getItem(STORAGE_KEYS.THEME) || THEMES.LIGHT;
        const highReadability = localStorage.getItem(STORAGE_KEYS.HIGH_READABILITY) === 'true';
        
        // 应用主题设置
        applyTheme(savedTheme, highReadability);
        
        // 更新UI状态
        updateThemeButtons(savedTheme, highReadability);
    }

    // 应用主题
    function applyTheme(theme, highReadability = false) {
        // 移除所有主题类
        document.body.classList.remove(THEMES.LIGHT, THEMES.DARK);
        
        // 设置当前主题
        document.body.classList.add(theme);
        
        // 设置高可读性模式
        if (highReadability) {
            document.body.classList.add('high-readability');
            // 增强特定元素的字体大小
            applyHighReadabilityEnhancements(true);
        } else {
            document.body.classList.remove('high-readability');
            // 恢复默认字体大小
            applyHighReadabilityEnhancements(false);
        }
        
        // 保存设置到本地存储
        localStorage.setItem(STORAGE_KEYS.THEME, theme);
        localStorage.setItem(STORAGE_KEYS.HIGH_READABILITY, highReadability);
        
        // 触发主题变化事件，让其他组件可以响应
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: {
                theme: theme,
                highReadability: highReadability,
                isDarkTheme: theme === THEMES.DARK
            }
        }));
    }

    // 增强高可读性模式元素
    function applyHighReadabilityEnhancements(enable) {
        // 选择需要增强的元素
        const elements = {
            headings: document.querySelectorAll('h1, h2, h3, h4, h5, h6'),
            labels: document.querySelectorAll('.form-label'),
            options: document.querySelectorAll('.option-card, .age-card, .frequency-card, .count-card, .gender-card'),
            buttons: document.querySelectorAll('.btn'),
            text: document.querySelectorAll('p, .lead')
        };

        if (enable) {
            // 增强字体大小
            elements.headings.forEach(el => {
                const currentSize = parseFloat(window.getComputedStyle(el).fontSize);
                el.style.fontSize = `${currentSize * 1.3}px`;
                el.style.fontWeight = '700';
            });
            
            elements.labels.forEach(el => {
                const currentSize = parseFloat(window.getComputedStyle(el).fontSize);
                el.style.fontSize = `${currentSize * 1.4}px`;
                el.style.fontWeight = '700';
                el.style.marginBottom = '1rem';
            });
            
            elements.options.forEach(el => {
                const currentSize = parseFloat(window.getComputedStyle(el).fontSize);
                el.style.fontSize = `${currentSize * 1.3}px`;
                el.style.fontWeight = '600';
                
                // 增大图标
                const icon = el.querySelector('i');
                if (icon) {
                    const iconSize = parseFloat(window.getComputedStyle(icon).fontSize);
                    icon.style.fontSize = `${iconSize * 1.3}px`;
                }
            });
            
            elements.buttons.forEach(el => {
                const currentSize = parseFloat(window.getComputedStyle(el).fontSize);
                el.style.fontSize = `${currentSize * 1.3}px`;
                el.style.fontWeight = '600';
                el.style.padding = '0.8rem 1.5rem';
            });
            
            elements.text.forEach(el => {
                const currentSize = parseFloat(window.getComputedStyle(el).fontSize);
                
                if (el.classList.contains('small') || el.classList.contains('footer-copyright')) {
                    // 小字体文本特别处理，确保在高可读性模式下仍然清晰可见
                    el.style.fontSize = '1.1rem';
                    el.style.fontWeight = '500';
                } else {
                    el.style.fontSize = `${currentSize * 1.25}px`;
                    el.style.fontWeight = '500';
                    el.style.lineHeight = '1.7';
                }
            });
            
            // 增加对比度
            document.body.classList.add('high-contrast');
        } else {
            // 恢复原始样式
            elements.headings.forEach(el => {
                el.style.fontSize = '';
                el.style.fontWeight = '';
            });
            
            elements.labels.forEach(el => {
                el.style.fontSize = '';
                el.style.fontWeight = '';
                el.style.marginBottom = '';
            });
            
            elements.options.forEach(el => {
                el.style.fontSize = '';
                el.style.fontWeight = '';
                
                // 恢复图标大小
                const icon = el.querySelector('i');
                if (icon) {
                    icon.style.fontSize = '';
                }
            });
            
            elements.buttons.forEach(el => {
                el.style.fontSize = '';
                el.style.fontWeight = '';
                el.style.padding = '';
            });
            
            elements.text.forEach(el => {
                el.style.fontSize = '';
                el.style.fontWeight = '';
                el.style.lineHeight = '';
            });
            
            // 移除高对比度
            document.body.classList.remove('high-contrast');
        }
    }

    // 更新主题按钮状态
    function updateThemeButtons(theme, highReadability) {
        // 检查按钮是否已存在
        if (document.querySelector('.theme-switcher')) {
            // 更新按钮活动状态
            const buttons = document.querySelectorAll('.theme-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active');
                
                // 设置正确的活动按钮
                if (btn.dataset.theme === theme) {
                    btn.classList.add('active');
                }
                
                // 设置高可读性按钮状态
                if (btn.dataset.theme === 'readable') {
                    btn.classList.toggle('active', highReadability);
                }
            });
        }
    }

    // 切换主题
    function toggleTheme(newTheme) {
        const currentTheme = localStorage.getItem(STORAGE_KEYS.THEME) || THEMES.LIGHT;
        const highReadability = localStorage.getItem(STORAGE_KEYS.HIGH_READABILITY) === 'true';
        
        // 如果切换到高可读性模式
        if (newTheme === 'readable') {
            const newReadabilityState = !highReadability;
            applyTheme(currentTheme, newReadabilityState);
            updateThemeButtons(currentTheme, newReadabilityState);
            return;
        }
        
        // 切换到明亮或暗黑主题
        const themeToApply = newTheme === 'light' ? THEMES.LIGHT : THEMES.DARK;
        applyTheme(themeToApply, highReadability);
        updateThemeButtons(themeToApply, highReadability);
    }

    // 创建主题切换器UI
    function createThemeSwitcher() {
        // 如果已存在，则不重复创建
        if (document.querySelector('.theme-switcher')) {
            return;
        }
        
        const switcher = document.createElement('div');
        switcher.className = 'theme-switcher';
        
        // 亮色主题按钮
        const lightBtn = document.createElement('button');
        lightBtn.className = 'theme-btn';
        lightBtn.dataset.theme = 'light';
        lightBtn.innerHTML = '<i class="fas fa-sun"></i>';
        lightBtn.setAttribute('title', '切换到日间模式');
        lightBtn.onclick = () => toggleTheme('light');
        
        // 暗色主题按钮
        const darkBtn = document.createElement('button');
        darkBtn.className = 'theme-btn';
        darkBtn.dataset.theme = 'dark';
        darkBtn.innerHTML = '<i class="fas fa-moon"></i>';
        darkBtn.setAttribute('title', '切换到夜间模式');
        darkBtn.onclick = () => toggleTheme('dark');
        
        // 高可读性模式按钮
        const readableBtn = document.createElement('button');
        readableBtn.className = 'theme-btn';
        readableBtn.dataset.theme = 'readable';
        readableBtn.innerHTML = '<i class="fas fa-glasses"></i>';
        readableBtn.setAttribute('title', '高可读性模式');
        readableBtn.onclick = () => toggleTheme('readable');
        
        // 添加按钮到切换器
        switcher.appendChild(lightBtn);
        switcher.appendChild(darkBtn);
        switcher.appendChild(readableBtn);
        
        // 添加切换器到页面
        document.body.appendChild(switcher);
        
        // 更新按钮状态
        const currentTheme = localStorage.getItem(STORAGE_KEYS.THEME) || THEMES.LIGHT;
        const highReadability = localStorage.getItem(STORAGE_KEYS.HIGH_READABILITY) === 'true';
        updateThemeButtons(currentTheme, highReadability);
    }

    // 公开API
    return {
        init: function() {
            createThemeSwitcher();
            initTheme();
        },
        toggle: toggleTheme
    };
})(); 