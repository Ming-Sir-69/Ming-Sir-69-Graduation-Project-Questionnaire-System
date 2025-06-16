<?php
/**
 * 行李箱调研系统 - 会话关闭
 * 专门负责清除和关闭会话
 */

/**
 * 关闭会话并清除所有数据
 * @param bool $destroy 是否销毁会话ID
 * @return bool 操作是否成功
 */
function close_session($destroy = true) {
    // 记录即将关闭的会话ID
    $session_id = session_id();
    error_log("正在关闭会话: " . $session_id);
    
    // 清除所有会话数据
    $_SESSION = array();
    
    // 如果要销毁会话，清除会话cookie
    if ($destroy && isset($_COOKIE[session_name()])) {
        // 设置cookie过期时间为过去
        setcookie(session_name(), '', time() - 42000, '/');
    }
    
    // 销毁会话
    $result = session_destroy();
    
    error_log("会话已关闭: " . $session_id . " 结果: " . ($result ? '成功' : '失败'));
    
    return $result;
}

/**
 * 保持会话ID但清除所有数据
 * @return bool 操作是否成功
 */
function clear_session_data() {
    // 保存当前状态
    $session_id = session_id();
    
    // 清除除了会话ID外的所有数据
    foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]);
    }
    
    // 重新初始化会话状态
    $_SESSION['session_status'] = 'new';
    $_SESSION['last_activity'] = time();
    
    error_log("会话数据已清除但保留ID: " . $session_id);
    
    return true;
}
?> 