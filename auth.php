<?php
if(!empty($_SESSION['username'])){
    if($title == "Home"){
        // display info about the website
        header("location: home.php");

    }else{
        header("location: login.php");
    }
        
}