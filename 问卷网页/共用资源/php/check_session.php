<?php
/**
 * 行李箱调研系统 - 会话验证
 * 在各页面顶部引入以验证会话状态
 */

// 如果会话尚未开始，则启动会话
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * 检查会话是否有效
 * @param string $requiredPage 当前页面类型（'entry', 'selection', 'questionnaire'）
 * @return bool 会话是否有效
 */
function isSessionValid($requiredPage = '') {
    // 默认会话有效
    $isValid = true;
    
    // 检查会话是否存在
    if (!isset($_SESSION) || empty($_SESSION)) {
        // 会话不存在或为空，仅入口页面允许
        return $requiredPage === 'entry';
    }
    
    // 检查会话是否过期（30分钟无活动）
    if (isset($_SESSION['last_activity'])) {
        $timeout = 1800; // 30分钟
        if (time() - $_SESSION['last_activity'] > $timeout) {
            $_SESSION['session_status'] = 'expired';
            $isValid = false;
        }
    }
    
    // 检查会话状态是否正常
    if (isset($_SESSION['session_status']) && $_SESSION['session_status'] === 'expired') {
        $isValid = false;
    }
    
    // 针对不同页面做特定检查
    if ($isValid && $requiredPage !== 'entry') {
        // 对于非入口页面，需要基本信息已填写
        if (!isset($_SESSION['basic_info']) || empty($_SESSION['basic_info'])) {
            $isValid = false;
        }
    }
    
    // 更新最后活动时间
    $_SESSION['last_activity'] = time();
    
    return $isValid;
}

/**
 * 如果会话无效，重定向到指定页面
 * @param string $requiredPage 当前页面类型
 * @param string $redirectUrl 重定向URL
 */
function redirectIfInvalidSession($requiredPage, $redirectUrl = '../../入口界面/index.php') {
    if (!isSessionValid($requiredPage)) {
        // 如果会话已过期，添加提示参数
        if (isset($_SESSION['session_status']) && $_SESSION['session_status'] === 'expired') {
            $redirectUrl .= (strpos($redirectUrl, '?') === false) ? '?expired=1' : '&expired=1';
        }
        
        // 重定向到指定页面
        header('Location: ' . $redirectUrl);
        exit;
    }
} 