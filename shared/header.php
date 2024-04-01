
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title?></title>
    <link href = "style.css" rel="stylesheet">
</head>
<body>
<nav>
        
        
        <?php
        session_start();
        if(empty($_SESSION['username'])){
                echo '<li><a href="home.php">Home</a></li>';
                echo '<li><a href="login.php">Log in</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
        }else {
                echo '<li><a href="index.php">Home</a></li>';
                echo '<li><a href="add-word.php">Add word</a></li>';
                echo '<li><a href="show-all.php">Show all words</a></li>';
                echo '<li>'.htmlspecialchars($_SESSION['username']).'</li>';
                echo '<li><a href="logout.php">Log out</a></li>';
        }
        ?>
        

</nav>
