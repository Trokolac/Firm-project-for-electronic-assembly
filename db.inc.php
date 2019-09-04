<?php

$conf = require './config.inc.php';

try {
    $db = new PDO(
        "mysql:host={$conf['db_host']};dbname={$conf['db_name']};charset=utf8;",
        $conf['db_user'],
        $conf['db_pass']
    );
  
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $db;
} catch(PDOException $e) {
    var_dump($e);
}