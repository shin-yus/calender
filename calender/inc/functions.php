<?php
//データベース接続
function db_open() :PDO{
    $user = "yshinmen_admin";
    $password = "yshinmen"; 
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ];
    $dbh = new PDO('mysql:host=157.112.147.201;dbname=yshinmen_database', $user, $password, $opt);
    return $dbh;
}
