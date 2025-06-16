<?php
/**
 * 行李箱调研系统 - 会话启动
 * 专门负责启动和初始化会话
 */

// 设置会话参数
ini_set('session.cookie_lifetime', 1800);  // 会话cookie生存期为30分钟
ini_set('session.gc_maxlifetime', 1800);   // 会话最长生存期为30分钟
ini_set('session.use_strict_mode', 1);     // 使用严格模式

/**
 * 启动并初始化会话
 * @return bool 是否成功启动会话
 */
function session_start_custom() {
    // 如果会话尚未启动
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        
        // 初始化会话状态信息
        if (!isset($_SESSION['session_status'])) {
            $_SESSION['session_status'] = 'new';
        }
        
        // 记录活动时间
        $_SESSION['last_activity'] = time();
        
        // 为调试记录会话启动日志
        error_log("会话启动: " . session_id());
        
        return true;
    }
    
    return false;
}

// 自动启动会话
session_start_custom();
?> 