<?php
/**
 * 行李箱调研系统 - 会话处理器
 * 处理前端会话操作请求
 */

// 启动会话
session_start();

// 设置响应头
header('Content-Type: application/json');

// 获取请求数据
$requestData = json_decode(file_get_contents('php://input'), true);
if (!$requestData) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

// 根据请求操作类型执行相应功能
$action = $requestData['action'] ?? '';
$response = ['success' => false];

switch ($action) {
    case 'save_basic_info':
        // 保存基本信息到会话
        if (isset($requestData['data'])) {
            $_SESSION['basic_info'] = $requestData['data'];
            $_SESSION['session_status'] = 'active';
            $_SESSION['last_activity'] = time();
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'error' => 'No data provided'];
        }
        break;
        
    case 'get_basic_info':
        // 从会话获取基本信息
        $response = [
            'success' => true,
            'data' => $_SESSION['basic_info'] ?? null
        ];
        break;
        
    case 'save_questionnaire':
        // 保存问卷数据到会话
        if (isset($requestData['id']) && isset($requestData['data'])) {
            $id = $requestData['id'];
            $_SESSION['questionnaire_' . $id] = $requestData['data'];
            $_SESSION['last_activity'] = time();
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'error' => 'Invalid questionnaire data'];
        }
        break;
        
    case 'get_questionnaire':
        // 从会话获取问卷数据
        if (isset($requestData['id'])) {
            $id = $requestData['id'];
            $response = [
                'success' => true,
                'data' => $_SESSION['questionnaire_' . $id] ?? null
            ];
        } else {
            $response = ['success' => false, 'error' => 'No questionnaire ID provided'];
        }
        break;
        
    case 'save_state':
        // 保存最后状态
        if (isset($requestData['state'])) {
            $_SESSION['last_state'] = $requestData['state'];
            $_SESSION['last_activity'] = time();
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'error' => 'No state provided'];
        }
        break;
        
    case 'get_state':
        // 获取最后状态
        $response = [
            'success' => true,
            'state' => $_SESSION['last_state'] ?? null
        ];
        break;
        
    case 'check_session':
        // 检查会话是否过期
        $isExpired = !isset($_SESSION) || empty($_SESSION);
        
        // 如果会话存在但已超时（30分钟）
        if (!$isExpired && isset($_SESSION['last_activity'])) {
            $timeout = 1800; // 30分钟
            if (time() - $_SESSION['last_activity'] > $timeout) {
                $isExpired = true;
                $_SESSION['session_status'] = 'expired';
            }
        }
        
        $response = [
            'success' => true,
            'expired' => $isExpired,
            'status' => $_SESSION['session_status'] ?? 'unknown'
        ];
        break;
        
    case 'clear_session':
        // 完全清除会话数据
        session_unset();
        session_destroy();
        $response = ['success' => true];
        break;
        
    case 'clear_session_data':
        // 清除会话数据但保留会话ID
        $preserveSession = isset($requestData['preserve_session']) ? $requestData['preserve_session'] : false;
        
        // 保存当前会话ID
        $sessionId = session_id();
        
        // 清除所有会话数据
        foreach ($_SESSION as $key => $value) {
            if ($key != 'session_id') {
                unset($_SESSION[$key]);
            }
        }
        
        // 重置状态为新会话
        $_SESSION['session_status'] = 'new';
        $_SESSION['last_activity'] = time();
        
        $response = ['success' => true];
        break;
        
    default:
        $response = ['success' => false, 'error' => 'Unknown action'];
}

// 更新最后活动时间（除非是检查会话状态）
if ($action != 'check_session') {
    $_SESSION['last_activity'] = time();
}

// 返回响应
echo json_encode($response); 