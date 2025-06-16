<?php
/**
 * 行李箱调研系统 - 数据库操作
 * 简单直接的MySQL操作函数，符合学校教学风格
 */

/**
 * 执行查询语句
 * @param mysqli $dbc 数据库连接
 * @param string $query SQL查询语句
 * @return mysqli_result|bool 成功返回结果集，失败返回false
 */
function db_query($dbc, $query) {
    if (!$dbc) return false;
    
    $result = mysqli_query($dbc, $query);
    
    if (!$result) {
        $error = "查询执行失败: " . mysqli_error($dbc) . "\nSQL: " . $query;
        error_log($error);
    }
    
    return $result;
}

/**
 * 获取查询结果的一行数据
 * @param mysqli_result $result 查询结果集
 * @return array|null 成功返回结果数组，失败或无数据返回null
 */
function db_fetch($result) {
    if (!$result) return null;
    return mysqli_fetch_array($result);
}

/**
 * 获取查询结果的行数
 * @param mysqli_result $result 查询结果集
 * @return int 结果行数
 */
function db_num_rows($result) {
    if (!$result) return 0;
    return mysqli_num_rows($result);
}

/**
 * 转义特殊字符以防止SQL注入
 * @param mysqli $dbc 数据库连接
 * @param string $data 需要转义的数据
 * @return string 转义后的数据
 */
function db_escape($dbc, $data) {
    if (!$dbc) return $data;
    return mysqli_real_escape_string($dbc, $data);
}

/**
 * 插入数据
 * @param mysqli $dbc 数据库连接
 * @param string $table 表名
 * @param array $data 数据数组，格式为['字段名'=>'值']
 * @return bool 是否插入成功
 */
function db_insert($dbc, $table, $data) {
    if (!$dbc || empty($table) || empty($data)) return false;
    
    $fields = [];
    $values = [];
    
    foreach ($data as $field => $value) {
        $fields[] = $field;
        $values[] = "'" . db_escape($dbc, $value) . "'";
    }
    
    $query = "INSERT INTO $table (" . implode(',', $fields) . ") 
              VALUES (" . implode(',', $values) . ")";
    
    return mysqli_query($dbc, $query);
}

/**
 * 更新数据
 * @param mysqli $dbc 数据库连接
 * @param string $table 表名
 * @param array $data 数据数组，格式为['字段名'=>'值']
 * @param string $where 条件语句，如"id=1"
 * @return bool 是否更新成功
 */
function db_update_record($dbc, $table, $data, $where) {
    if (!$dbc || empty($table) || empty($data) || empty($where)) return false;
    
    $set_clause = [];
    
    foreach ($data as $field => $value) {
        $set_clause[] = "$field = '" . db_escape($dbc, $value) . "'";
    }
    
    $query = "UPDATE $table SET " . implode(',', $set_clause) . " WHERE $where";
    
    return mysqli_query($dbc, $query);
}

/**
 * 删除数据
 * @param mysqli $dbc 数据库连接
 * @param string $table 表名
 * @param string $where 条件语句，如"id=1"
 * @return bool 是否删除成功
 */
function db_delete($dbc, $table, $where) {
    if (!$dbc || empty($table) || empty($where)) return false;
    
    $query = "DELETE FROM $table WHERE $where";
    
    return mysqli_query($dbc, $query);
}

/**
 * 获取最后插入的ID
 * @param mysqli $dbc 数据库连接
 * @return int 最后插入的ID
 */
function db_last_insert_id($dbc) {
    if (!$dbc) return 0;
    return mysqli_insert_id($dbc);
}

/**
 * 获取影响的行数
 * @param mysqli $dbc 数据库连接
 * @return int 影响的行数
 */
function db_affected_rows($dbc) {
    if (!$dbc) return 0;
    return mysqli_affected_rows($dbc);
}

/**
 * 执行开始事务（简单实现）
 * @param mysqli $dbc 数据库连接
 * @return bool 是否成功
 */
function db_begin_transaction($dbc) {
    if (!$dbc) return false;
    return mysqli_query($dbc, "START TRANSACTION");
}

/**
 * 执行提交事务（简单实现）
 * @param mysqli $dbc 数据库连接
 * @return bool 是否成功
 */
function db_commit($dbc) {
    if (!$dbc) return false;
    return mysqli_query($dbc, "COMMIT");
}

/**
 * 执行回滚事务（简单实现）
 * @param mysqli $dbc 数据库连接
 * @return bool 是否成功
 */
function db_rollback($dbc) {
    if (!$dbc) return false;
    return mysqli_query($dbc, "ROLLBACK");
}
?> 