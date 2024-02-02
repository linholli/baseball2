<?php
// db_connect.php

$host = "localhost";
$user = "admin";
$password = "1234";
$database = "baseball";

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("连接数据库失败：" . mysqli_connect_error());
}
?>
