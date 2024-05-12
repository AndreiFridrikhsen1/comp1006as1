<?php 
include("db.php");
$deleted = false;
// if delete all button is pressed delete all
$stmnt=$pdo->prepare("Delete from words");
$stmnt ->execute();
$stmnt=$pdo->prepare("Delete from translations");
$stmnt ->execute();
// reset autoincrement value 
$stmnt = $pdo->prepare("ALTER TABLE words AUTO_INCREMENT = 1;");
$stmnt->execute();
$pdo = null;
$deleted = true;







 //redirect back 

header("location: show-all.php");