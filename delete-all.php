<?php 
include("db.php");
$stmt = $pdo->prepare("truncate table words;");
$stmt->execute();

$stmt = $pdo->prepare("truncate table translations;");
$stmt->execute();
$db=null;
// redirect back 
header("location: show-all.php");