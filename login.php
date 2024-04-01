<?php 
$title = "Login";
include("db.php");
include("shared/header.php");
$username;
$password;

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    // check if username exists

    $stmt = $pdo->prepare("SELECT * FROM users where username = :placeholder1");
    $stmt->bindParam(":placeholder1", $username);
    $stmt->execute();
    $match = $stmt->fetch(PDO::FETCH_ASSOC);
    #if no user found add 
    if($match == null){
        
        header("location: login.php?notFound=true");
        
    }else {
        if(!password_verify($password, $match['password'])){
            header("location: login.php?wrongPassword=true");
        }else{
        session_start();
        $_SESSION['username']=$username;
        // redirect to the show all page
        header("location: show-all.php");
        }
    }
    

}
?>

    <section class="<?php echo $title?>">
        <h1><?php echo $title?></h1>
        <h5>Please enter your credentials.</h5>
        <?php if(isset($_GET['notFound'])){if($_GET['notFound']==true){echo "<h3>Email doesn't exist</h3>";}}?>
        <?php if(isset($_GET['wrongPassword'])){if($_GET['wrongPassword']==true){echo "<h3>Wrong password</h3>";}}?>
        <form method="post" action="login.php">

            <fieldset>
            <label for="username">Username:</label>

            <input name="username" id="username" required type="email" placeholder="email@email.com" />
            

            </fieldset>

            <fieldset>

            <label for="password">Password:</label>

            <input type="password" name="password" id="password" required />

            </fieldset>

            <button name="login" class="offset-button">Login</button>

        </form>
    
    </section>
</body>
</html>