<?php
/**
 * 行李箱调研系统 - 数据库关闭
 * 简单的数据库连接关闭功能
 */

/**
 * 关闭数据库连接
 * @param mysqli $dbc 数据库连接对象
 * @return bool 是否成功关闭
 */
function db_close($dbc) {
    if (!$dbc) return false;
    return mysqli_close($dbc);
}
?> 