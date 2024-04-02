<?php 
include("db.php");
$deleted = false;
// get word id from the url

 $wordId = $_GET['wordId'];
if(is_numeric($wordId)){
    // turn off foreign key check 
     $stmnt = $pdo->prepare("SET FOREIGN_KEY_CHECKS=0");
     $stmnt->execute();
     $stmnt = $pdo->prepare("delete words, translations from words JOIN translations ON words.word_id = translations.word_id where words.word_id = :wordId");
    $stmnt-> bindParam(":wordId", $wordId);
    $stmnt->execute();
    // turn on foreign key check
    $stmnt = $pdo->prepare("SET FOREIGN_KEY_CHECKS=1");
    $stmnt->execute();
    
    $pdo = null;
    $deleted = true;
}






 //redirect back 
if($deleted){
    header("location: show-all.php");
}