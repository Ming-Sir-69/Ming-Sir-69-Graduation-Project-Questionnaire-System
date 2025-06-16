<?php
/**
 * 行李箱调研系统 - 问卷提交API
 * 处理问卷数据的提交
 */

// 启动会话
session_start();

// 包含必要的文件
require_once '../database_handler.php';
require_once '../check_session.php';

// 设置响应头
header('Content-Type: application/json');

// 验证会话是否有效
if (!isSessionValid('questionnaire')) {
    echo json_encode([
        'success' => false, 
        'error' => '会话已过期或无效，请重新开始',
        'redirect' => '../../入口界面/index.php'
    ]);
    exit;
}

// 获取请求数据
$requestData = json_decode(file_get_contents('php://input'), true);
if (!$requestData) {
    echo json_encode(['success' => false, 'error' => '无效的请求数据']);
    exit;
}

// 获取会话ID
$sessionId = session_id();

// 获取操作类型（提交并结束会话 or 提交并保持会话）
$action = $requestData['action'] ?? 'save'; // 默认为保存

// 记录操作日志
$logMessage = "问卷提交操作 - 会话ID: $sessionId, 操作: $action";
error_log($logMessage);

// 获取问卷ID
$questionnaireId = $requestData['questionnaire_id'] ?? null;

// 获取问卷数据
$questionnaireData = $requestData['data'] ?? null;

// 操作结果
$result = ['success' => false];

// 处理问卷数据
if ($questionnaireId && $questionnaireData) {
    // 准备数据包含会话ID
    $data = $questionnaireData;
    $data['session_id'] = $sessionId;
    
    // 保存到数据库
    $saveResult = saveQuestionnaireToDb($questionnaireId, $data);
    
    if ($saveResult['success']) {
        // 问卷保存成功
        $result = ['success' => true, 'message' => '问卷数据已保存'];
        
        // 如果是提交并结束会话，处理会话结束
        if ($action === 'submit') {
            // 完成提交并记录
            $finalizeResult = finalizeSubmission($sessionId);
            
            if ($finalizeResult['success']) {
                // 准备成功响应
                $result['message'] = '问卷已成功提交，会话已结束';
                $result['redirect'] = '../../入口界面/thank_you.php';
                
                // 更新会话状态为已提交 - 但暂不销毁会话
                $_SESSION['session_status'] = 'submitted';
                
                // 注册关闭会话的函数，确保在响应发送后执行
                register_shutdown_function(function() {
                    // 最后才清除会话数据
                    session_write_close(); // 先保存会话
                    if (isset($_SESSION)) {
                        session_unset();
                        session_destroy();
                    }
                });
            } else {
                // 提交完成失败，但数据已保存
                $result['message'] = '问卷数据已保存，但未能完成提交记录';
                $result['warning'] = $finalizeResult['error'];
            }
        }
    } else {
        // 问卷保存失败
        $result = [
            'success' => false,
            'error' => '保存问卷数据失败: ' . ($saveResult['error'] ?? '未知错误')
        ];
    }
} elseif (isset($requestData['all_questionnaires']) && $requestData['all_questionnaires'] === true) {
    // 处理提交所有问卷的情况
    $allSuccess = true;
    $errors = [];
    
    // 获取会话中的所有问卷数据
    $basicInfo = $_SESSION['basic_info'] ?? null;
    
    // 先检查是否有已完成的问卷，避免不必要的数据库操作
    $hasCompletedQuestionnaire = false;
    for ($i = 1; $i <= 3; $i++) {
        $qData = $_SESSION['questionnaire_' . $i] ?? null;
        if ($qData && isset($qData['completed']) && $qData['completed']) {
            $hasCompletedQuestionnaire = true;
            break;
        }
    }
    
    // 检查是否至少有一个已完成的问卷
    if (!$hasCompletedQuestionnaire) {
        echo json_encode([
            'success' => false,
            'error' => '至少需要完成一份问卷才能提交'
        ]);
        exit;
    }
    
    // 保存基本信息
    if ($basicInfo) {
        $basicInfo['session_id'] = $sessionId;
        $saveBasicResult = saveBasicInfoToDb($basicInfo);
        
        if (!$saveBasicResult['success']) {
            $allSuccess = false;
            $errors[] = '保存基本信息失败: ' . ($saveBasicResult['error'] ?? '未知错误');
        }
    } else {
        $allSuccess = false;
        $errors[] = '基本信息不存在';
    }
    
    // 保存所有问卷 
    for ($i = 1; $i <= 3; $i++) {
        $qData = $_SESSION['questionnaire_' . $i] ?? null;
        
        if ($qData) {
            // 只处理已完成的问卷，减少不必要的数据库操作
            if (isset($qData['completed']) && $qData['completed']) {
                $qData['session_id'] = $sessionId;
                $saveQResult = saveQuestionnaireToDb($i, $qData);
                
                if (!$saveQResult['success']) {
                    $allSuccess = false;
                    $errors[] = '保存问卷' . $i . '失败: ' . ($saveQResult['error'] ?? '未知错误');
                }
            }
        }
    }
    
    // 处理结果
    if ($allSuccess) {
        // 如果是提交并结束会话
        if ($action === 'submit') {
            // 保存所有问卷数据成功后，完成提交并记录
            $finalizeResult = finalizeSubmission($sessionId);
            
            if ($finalizeResult['success']) {
                // 准备成功响应
                $result = [
                    'success' => true,
                    'message' => '所有问卷已成功提交，会话已结束',
                    'redirect' => '../../入口界面/thank_you.php'
                ];
                
                // 更新会话状态为已提交 - 但暂不销毁会话
                $_SESSION['session_status'] = 'submitted';
                
                // 注册关闭会话的函数，确保在响应发送后执行
                register_shutdown_function(function() {
                    // 最后才清除会话数据
                    session_write_close(); // 先保存会话
                    if (isset($_SESSION)) {
                        session_unset();
                        session_destroy();
                    }
                });
            } else {
                // 提交完成失败，但数据已保存
                $result = [
                    'success' => true,
                    'message' => '所有问卷数据已保存，但未能完成提交记录',
                    'warning' => $finalizeResult['error']
                ];
            }
        } else {
            // 只保存不结束会话
            $result = [
                'success' => true,
                'message' => '所有问卷数据已保存'
            ];
        }
    } else {
        // 有保存失败的情况
        $result = [
            'success' => false,
            'error' => '保存问卷数据时发生错误',
            'details' => $errors
        ];
    }
} else {
    // 请求数据不完整
    $result = [
        'success' => false,
        'error' => '请求数据不完整'
    ];
}

// 记录操作结果
$resultLog = "问卷提交结果 - 会话ID: $sessionId, 成功: " . ($result['success'] ? '是' : '否');
if (!$result['success'] && isset($result['error'])) {
    $resultLog .= ", 错误: " . $result['error'];
}
error_log($resultLog);

// 返回响应
echo json_encode($result); 