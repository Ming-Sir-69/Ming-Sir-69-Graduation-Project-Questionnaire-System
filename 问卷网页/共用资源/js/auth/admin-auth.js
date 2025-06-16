/**
 * 管理员身份验证模块
 * 处理登录验证和会话管理
 */
class AdminAuth {
    constructor() {
        // 默认凭据
        this.defaultCredentials = {
            username: 'Admin',
            password: 'luggage2025'
        };
        
        // 会话状态
        this.sessionKey = 'luggage_survey_admin_session';
        this.sessionTimeout = 3600000; // 1小时
        
        // 管理页面路径 - 更新为PHP文件路径
        this.adminPagePath = '/问卷模块/管理页面/index.php';
    }

    /**
     * 验证登录凭据
     * @param {string} username - 输入的用户名
     * @param {string} password - 输入的密码
     * @returns {boolean} - 验证结果
     */
    validateCredentials(username, password) {
        return username === this.defaultCredentials.username && 
               password === this.defaultCredentials.password;
    }

    /**
     * 创建管理员会话
     * @returns {string} - 生成的会话令牌
     */
    createSession() {
        const sessionToken = this._generateSessionToken();
        const sessionData = {
            token: sessionToken,
            timestamp: Date.now(),
            expiresAt: Date.now() + this.sessionTimeout
        };
        
        // 保存会话到本地存储
        localStorage.setItem(this.sessionKey, JSON.stringify(sessionData));
        return sessionToken;
    }

    /**
     * 验证当前会话是否有效
     * @returns {boolean} - 会话是否有效
     */
    validateSession() {
        const sessionData = this._getSessionData();
        if (!sessionData) return false;
        
        // 检查会话是否过期
        if (Date.now() > sessionData.expiresAt) {
            this.endSession();
            return false;
        }
        
        // 刷新会话时间
        this._refreshSession();
        return true;
    }

    /**
     * 结束当前会话
     */
    endSession() {
        localStorage.removeItem(this.sessionKey);
    }

    /**
     * 处理管理员登录
     * @param {string} username - 用户名
     * @param {string} password - 密码
     * @returns {Object} - 登录结果
     */
    login(username, password) {
        if (this.validateCredentials(username, password)) {
            const token = this.createSession();
            return {
                success: true,
                token: token,
                message: '登录成功'
            };
        } else {
            return {
                success: false,
                message: '用户名或密码错误'
            };
        }
    }
    
    /**
     * 重定向到管理页面
     */
    redirectToAdminPage() {
        if (this.validateSession()) {
            window.location.href = this.adminPagePath;
        } else {
            console.error('未授权访问管理页面');
            // 可以选择重定向到登录页面或显示错误
        }
    }

    /**
     * 生成唯一的会话令牌
     * @private
     * @returns {string} - 生成的会话令牌
     */
    _generateSessionToken() {
        return 'admin-' + Math.random().toString(36).substring(2, 15) + 
               Math.random().toString(36).substring(2, 15);
    }

    /**
     * 获取当前会话数据
     * @private
     * @returns {Object|null} - 会话数据对象或null
     */
    _getSessionData() {
        const sessionJson = localStorage.getItem(this.sessionKey);
        if (!sessionJson) return null;
        
        try {
            return JSON.parse(sessionJson);
        } catch (e) {
            console.error('Session data parse error:', e);
            return null;
        }
    }

    /**
     * 刷新会话时间
     * @private
     */
    _refreshSession() {
        const sessionData = this._getSessionData();
        if (!sessionData) return;
        
        sessionData.timestamp = Date.now();
        sessionData.expiresAt = Date.now() + this.sessionTimeout;
        localStorage.setItem(this.sessionKey, JSON.stringify(sessionData));
    }
}

// 创建全局实例
const adminAuth = new AdminAuth();

// 导出为全局变量
window.AdminAuth = adminAuth; 