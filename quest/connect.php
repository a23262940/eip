<?php
//資料庫設定
//資料庫位置
$db_server = "localhost";
//資料庫名稱
$db_name = "company";
//資料庫管理者帳號
$db_user = "root";
//資料庫管理者密碼
$db_passwd = "123qwe";

//對資料庫連線
$db = mysqli_connect($db_server, $db_user, $db_passwd, $db_name);
//        die("無法對資料庫連線");

//資料庫連線採UTF8
mysqli_set_charset($db,'utf8');
?>