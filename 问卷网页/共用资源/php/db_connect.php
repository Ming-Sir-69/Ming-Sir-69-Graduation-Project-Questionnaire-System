<?php
/**
 * 行李箱调研系统 - 数据库连接
 * 按照学校教学风格实现的数据库连接模块
 */

// 数据库配置
$db_host = 'localhost'; // 数据库服务器地址
$db_user = 'root';      // 数据库用户名
$db_pass = '';          // 数据库密码
$db_name = 'luggage_survey'; // 数据库名

/**
 * 连接数据库
 * @return mysqli|bool 成功返回数据库连接，失败返回false
 */
function db_connect() {
    global $db_host, $db_user, $db_pass, $db_name;
    
    // 使用标准mysqli_connect方式连接数据库
    $dbc = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    
    // 检查连接是否成功
    if (!$dbc) {
        // 记录连接错误信息到日志
        $error_msg = "数据库连接失败: " . mysqli_connect_error();
        error_log($error_msg);
        return false;
    }
    
    // 设置字符集为utf8
    mysqli_set_charset($dbc, 'utf8');
    
    return $dbc;
}
?> 