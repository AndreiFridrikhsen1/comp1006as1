<?php 
include("db.php");
$stmt = $pdo->prepare("DELETE FROM translations;");
$stmt->execute();


$stmt = $pdo->prepare("DELETE FROM words;");
$stmt->execute();
// reset autoincrement value 
$stmt = $pdo->prepare("ALTER TABLE words AUTO_INCREMENT = 1;");
$stmt->execute();
$db=null;
// redirect back 
header("location: show-all.php");