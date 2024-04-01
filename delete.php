<?php 
include("db.php");
$deleted = false;
// get word id from the url

 $wordId = $_GET['wordId'];
if(is_numeric($wordId)){
     $stmnt = $pdo->prepare("delete words, translations from words JOIN translations ON words.word_id = translations.word_id where words.word_id = :wordId");
    $stmnt-> bindParam(":wordId", $wordId);
    $stmnt->execute();
    
    $pdo = null;
    $deleted = true;
}






 //redirect back 
if($deleted){
    header("location: show-all.php");
}