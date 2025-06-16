/**
 * 行李箱调研系统 - 存储工具函数
 * 处理本地存储和会话数据
 */

const SurveyStorage = (function() {
    // 存储键名
    const STORAGE_KEYS = {
        SESSION_ID: 'survey_session_id',
        BASIC_INFO: 'survey_basic_info',
        QUESTIONNAIRE_1: 'survey_questionnaire_1',
        QUESTIONNAIRE_2: 'survey_questionnaire_2',
        QUESTIONNAIRE_3: 'survey_questionnaire_3',
        COMPLETED_FORMS: 'survey_completed_forms'
    };

    // 生成唯一会话ID
    function generateSessionId() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // 获取当前会话ID，如果不存在则创建
    function getSessionId() {
        let sessionId = localStorage.getItem(STORAGE_KEYS.SESSION_ID);
        if (!sessionId) {
            sessionId = generateSessionId();
            localStorage.setItem(STORAGE_KEYS.SESSION_ID, sessionId);
        }
        return sessionId;
    }

    // 保存基本信息
    function saveBasicInfo(data) {
        localStorage.setItem(STORAGE_KEYS.BASIC_INFO, JSON.stringify({
            ...data,
            timestamp: new Date().toISOString()
        }));
        
        // 添加到已完成表单列表
        const completedForms = getCompletedForms();
        if (!completedForms.includes('basic_info')) {
            completedForms.push('basic_info');
            saveCompletedForms(completedForms);
        }
    }

    // 获取基本信息
    function getBasicInfo() {
        const data = localStorage.getItem(STORAGE_KEYS.BASIC_INFO);
        return data ? JSON.parse(data) : null;
    }

    // 保存问卷数据
    function saveQuestionnaire(questionnaireNumber, data) {
        const key = STORAGE_KEYS[`QUESTIONNAIRE_${questionnaireNumber}`];
        localStorage.setItem(key, JSON.stringify({
            ...data,
            timestamp: new Date().toISOString()
        }));
        
        // 添加到已完成表单列表
        const completedForms = getCompletedForms();
        const formId = `questionnaire_${questionnaireNumber}`;
        if (!completedForms.includes(formId)) {
            completedForms.push(formId);
            saveCompletedForms(completedForms);
        }
    }

    // 获取问卷数据
    function getQuestionnaire(questionnaireNumber) {
        const key = STORAGE_KEYS[`QUESTIONNAIRE_${questionnaireNumber}`];
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    }

    // 保存已完成表单列表
    function saveCompletedForms(forms) {
        localStorage.setItem(STORAGE_KEYS.COMPLETED_FORMS, JSON.stringify(forms));
    }

    // 获取已完成表单列表
    function getCompletedForms() {
        const forms = localStorage.getItem(STORAGE_KEYS.COMPLETED_FORMS);
        return forms ? JSON.parse(forms) : [];
    }

    // 检查表单是否已完成
    function isFormCompleted(formId) {
        const completedForms = getCompletedForms();
        return completedForms.includes(formId);
    }

    // 清除单个表单数据
    function clearForm(formId) {
        if (formId === 'basic_info') {
            localStorage.removeItem(STORAGE_KEYS.BASIC_INFO);
        } else if (formId.startsWith('questionnaire_')) {
            const qNumber = formId.split('_')[1];
            localStorage.removeItem(STORAGE_KEYS[`QUESTIONNAIRE_${qNumber}`]);
        }
        
        // 从已完成列表中移除
        const completedForms = getCompletedForms();
        const updatedForms = completedForms.filter(form => form !== formId);
        saveCompletedForms(updatedForms);
    }

    // 清除所有调查数据
    function clearAllData() {
        Object.values(STORAGE_KEYS).forEach(key => {
            localStorage.removeItem(key);
        });
    }

    // 清除基本信息数据
    function clearBasicInfo() {
        localStorage.removeItem(STORAGE_KEYS.BASIC_INFO);
        
        // 从已完成列表中移除基本信息
        const completedForms = getCompletedForms();
        const updatedForms = completedForms.filter(form => form !== 'basic_info');
        saveCompletedForms(updatedForms);
        
        console.log('基本信息数据已清除');
    }

    // 准备提交的数据
    function prepareDataForSubmission() {
        const sessionId = getSessionId();
        const basicInfo = getBasicInfo() || {};
        const questionnaire1 = getQuestionnaire(1) || {};
        const questionnaire2 = getQuestionnaire(2) || {};
        const questionnaire3 = getQuestionnaire(3) || {};
        
        return {
            session_id: sessionId,
            basic_info: basicInfo,
            questionnaire_1: questionnaire1,
            questionnaire_2: questionnaire2,
            questionnaire_3: questionnaire3,
            completed_forms: getCompletedForms(),
            submission_time: new Date().toISOString()
        };
    }

    // 公开API
    return {
        getSessionId,
        saveBasicInfo,
        getBasicInfo,
        clearBasicInfo,
        saveQuestionnaire,
        getQuestionnaire,
        getCompletedForms,
        isFormCompleted,
        clearForm,
        clearAllData,
        prepareDataForSubmission
    };
})(); 