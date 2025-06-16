/**
 * 行李箱调研系统 - 会话工具
 * 负责与PHP会话处理器通信，管理本地与服务器会话数据同步
 */

const SessionManager = (function() {
    // 配置
    const config = {
        sessionEndpoint: '/共用资源/php/session_handler.php',
        fallbackToLocal: true, // 若服务器不可用，回退到本地存储
        autoSync: true         // 自动同步本地和会话数据
    };
    
    /**
     * 发送请求到PHP会话处理器
     * @param {Object} data - 请求数据
     * @returns {Promise<Object>} - 响应对象
     */
    async function sendRequest(data) {
        try {
            const response = await fetch(config.sessionEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
                credentials: 'same-origin' // 包含会话cookie
            });
            
            if (!response.ok) {
                throw new Error(`Session request failed: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('Session request error:', error);
            
            // 如果配置允许，回退到本地存储
            if (config.fallbackToLocal) {
                console.warn('Falling back to local storage...');
                return handleLocalFallback(data);
            }
            
            throw error;
        }
    }
    
    /**
     * 当服务器不可用时处理本地回退
     * @param {Object} data - 原始请求数据
     * @returns {Object} - 模拟响应对象
     */
    function handleLocalFallback(data) {
        const { action } = data;
        
        // 根据操作类型执行不同的本地操作
        switch (action) {
            case 'save_basic_info':
                localStorage.setItem('luggage_survey_basic_info', JSON.stringify(data.data));
                return { success: true };
                
            case 'get_basic_info':
                try {
                    const storedData = localStorage.getItem('luggage_survey_basic_info');
                    return { 
                        success: true, 
                        data: storedData ? JSON.parse(storedData) : null 
                    };
                } catch (e) {
                    return { success: false, error: 'Failed to parse local data' };
                }
                
            case 'save_questionnaire':
                try {
                    const questionnaireKey = `luggage_survey_questionnaire_${data.id}`;
                    localStorage.setItem(questionnaireKey, JSON.stringify(data.data));
                    return { success: true };
                } catch (e) {
                    return { success: false, error: 'Failed to save to local storage' };
                }
                
            case 'get_questionnaire':
                try {
                    const questionnaireKey = `luggage_survey_questionnaire_${data.id}`;
                    const storedData = localStorage.getItem(questionnaireKey);
                    return { 
                        success: true, 
                        data: storedData ? JSON.parse(storedData) : null 
                    };
                } catch (e) {
                    return { success: false, error: 'Failed to parse local data' };
                }
                
            case 'save_state':
                localStorage.setItem('luggage_survey_last_state', data.state);
                return { success: true };
                
            case 'get_state':
                return { 
                    success: true, 
                    state: localStorage.getItem('luggage_survey_last_state') 
                };
                
            case 'check_session':
                return { success: true, expired: false };
                
            case 'clear_session':
                // 仅清除调研相关数据，不清除可能的其他应用数据
                const keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key.startsWith('luggage_survey_')) {
                        keysToRemove.push(key);
                    }
                }
                keysToRemove.forEach(key => localStorage.removeItem(key));
                return { success: true };
                
            default:
                return { success: false, error: 'Unsupported action in local mode' };
        }
    }
    
    /**
     * 同步本地存储和会话数据
     * @returns {Promise<boolean>} - 是否同步成功
     */
    async function syncLocalAndSession() {
        if (!config.autoSync) return true;
        
        try {
            // 检查本地基本信息
            const basicInfo = SurveyStorage.getBasicInfo();
            if (basicInfo) {
                // 尝试保存到会话
                await saveBasicInfo(basicInfo);
            }
            
            // 检查已保存的问卷
            for (let i = 1; i <= 3; i++) { // 假设有3个问卷
                const questionnaireData = SurveyStorage.getQuestionnaireData(i);
                if (questionnaireData) {
                    // 尝试保存到会话
                    await saveQuestionnaireData(i, questionnaireData);
                }
            }
            
            // 同步最后状态
            const lastState = SurveyStorage.getLastState();
            if (lastState) {
                await saveLastState(lastState);
            }
            
            return true;
        } catch (error) {
            console.error('Failed to sync data with session:', error);
            return false;
        }
    }
    
    /**
     * 保存基本信息到会话
     * @param {Object} data - 基本信息数据
     * @returns {Promise<boolean>} - 是否保存成功
     */
    async function saveBasicInfo(data) {
        if (!data) return false;
        
        const response = await sendRequest({
            action: 'save_basic_info',
            data: data
        });
        
        return response.success === true;
    }
    
    /**
     * 从会话获取基本信息
     * @returns {Promise<Object|null>} - 基本信息或null
     */
    async function getBasicInfo() {
        const response = await sendRequest({
            action: 'get_basic_info'
        });
        
        return response.success === true ? response.data : null;
    }
    
    /**
     * 保存问卷数据到会话
     * @param {number} questionnaireId - 问卷ID
     * @param {Object} data - 问卷数据
     * @returns {Promise<boolean>} - 是否保存成功
     */
    async function saveQuestionnaireData(questionnaireId, data) {
        if (!questionnaireId || !data) return false;
        
        const response = await sendRequest({
            action: 'save_questionnaire',
            id: questionnaireId,
            data: data
        });
        
        return response.success === true;
    }
    
    /**
     * 从会话获取问卷数据
     * @param {number} questionnaireId - 问卷ID 
     * @returns {Promise<Object|null>} - 问卷数据或null
     */
    async function getQuestionnaireData(questionnaireId) {
        if (!questionnaireId) return null;
        
        const response = await sendRequest({
            action: 'get_questionnaire',
            id: questionnaireId
        });
        
        return response.success === true ? response.data : null;
    }
    
    /**
     * 保存最后状态到会话
     * @param {string} state - 状态标识
     * @returns {Promise<boolean>} - 是否保存成功
     */
    async function saveLastState(state) {
        if (!state) return false;
        
        const response = await sendRequest({
            action: 'save_state',
            state: state
        });
        
        return response.success === true;
    }
    
    /**
     * 从会话获取最后状态
     * @returns {Promise<string|null>} - 状态标识或null
     */
    async function getLastState() {
        const response = await sendRequest({
            action: 'get_state'
        });
        
        return response.success === true ? response.state : null;
    }
    
    /**
     * 检查会话是否已过期
     * @returns {Promise<boolean>} - 是否已过期
     */
    async function checkSessionExpired() {
        const response = await sendRequest({
            action: 'check_session'
        });
        
        return response.success === true ? response.expired : true;
    }
    
    /**
     * 清除会话数据
     * @returns {Promise<boolean>} - 是否清除成功
     */
    async function clearSessionData() {
        // 尝试清除服务器会话
        let success = true;
        try {
            const response = await sendRequest({
                action: 'clear_session'
            });
            success = response.success === true;
        } catch (error) {
            console.error('Failed to clear server session:', error);
            success = false;
        }
        
        // 同时清除本地存储数据
        try {
            SurveyStorage.clearAllData();
        } catch (error) {
            console.error('Failed to clear local storage:', error);
            success = false;
        }
        
        return success;
    }
    
    /**
     * 初始化会话管理器
     * @returns {Promise<boolean>} - 是否初始化成功
     */
    async function init() {
        try {
            // 检查会话状态
            const isExpired = await checkSessionExpired();
            
            if (isExpired) {
                console.log('Session expired, initializing new session...');
                await syncLocalAndSession();
            } else {
                console.log('Existing session found');
                
                // 如果配置允许，从会话加载数据到本地存储
                if (config.autoSync) {
                    await syncSessionToLocal();
                }
            }
            
            return true;
        } catch (error) {
            console.error('Failed to initialize session manager:', error);
            return false;
        }
    }
    
    /**
     * 从会话同步数据到本地存储
     * @returns {Promise<boolean>} - 是否同步成功
     */
    async function syncSessionToLocal() {
        try {
            // 获取基本信息
            const basicInfo = await getBasicInfo();
            if (basicInfo) {
                SurveyStorage.saveBasicInfo(basicInfo);
            }
            
            // 获取问卷数据
            for (let i = 1; i <= 3; i++) { // 假设有3个问卷
                const questionnaireData = await getQuestionnaireData(i);
                if (questionnaireData) {
                    SurveyStorage.saveQuestionnaireData(i, questionnaireData);
                }
            }
            
            // 获取最后状态
            const lastState = await getLastState();
            if (lastState) {
                SurveyStorage.setLastState(lastState);
            }
            
            return true;
        } catch (error) {
            console.error('Failed to sync session data to local storage:', error);
            return false;
        }
    }
    
    // 公共API
    return {
        init,
        saveBasicInfo,
        getBasicInfo,
        saveQuestionnaireData,
        getQuestionnaireData,
        saveLastState,
        getLastState,
        checkSessionExpired,
        clearSessionData,
        syncLocalAndSession,
        syncSessionToLocal
    };
})(); 