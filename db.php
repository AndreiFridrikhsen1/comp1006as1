<?php
//lampstack
//$username = "Andrei200558923";
//$password = "dFb_m5ckO7";
//$dsn = "mysql:host=172.31.22.43;dbname=Andrei200558923";
// set up connection
$username = "root";
$password = "543389Mysql";
$dsn = "mysql:host=localhost; dbname=dictionary";

$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));



