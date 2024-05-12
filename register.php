<?php 
$title = "Register";
include("db.php");
$username;
$password;
$pattern = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/";
$confirm;
$errors = [];
$validated = true;
$registred = false;
$hash;
$userExist = false;
if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    if(empty($username)){
        $validated = false;
        array_push($errors,"username can't be empty");
    }
    if(preg_match($pattern, $password)==0){
        $validated = false;
        array_push($errors,"invalid password format");
    }
    if($password!=$confirm){
        $validated = false;
        array_push($errors,"passwords doesn't match");
    }else {
        // hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);
    }
    if($validated == true){//check if the user doesnt exist and insert data into db
        $stmt =$pdo->prepare("select * from users where username=:placeholder");
        $stmt->bindParam(":placeholder", $username);
        $stmt->execute();
        $users= $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(empty($users)){
            $stmnt =$pdo->prepare("INSERT INTO users (username, password) VALUES (:placeholder1,:placeholder2)");
            $stmnt->bindParam(":placeholder1", $username);
            $stmnt->bindParam(":placeholder2", $hash);
            $stmnt->execute();
            $pdo = null;
            $registred = true;
        }else{
            $userExist = true;
        }
        
        
           
            
        

           
        
    }
    
}


include("shared/header.php");

?>

<section class="<?php echo $title?>">
        <h1><?php echo $title?></h1>
        <h5>Passwords must be a minimum of 8 characters, including 1 digit, 1 upper-case letter, and 1 lower-case letter.</h5>
        <?php if(sizeof($errors) > 0){foreach($errors as $error){echo $error."<br>";}} if($registred){echo "Account was successfuly created";} if($userExist){echo "This email already exists";}?>
        <form method="post" action="register.php">

        <fieldset>

        <label for="username">Username: *</label>

        <input name="username" id="username" required type="email" >

        </fieldset>

        <fieldset>

        <label for="password">Password: *</label>

        <input type="password" name="password" id="password" required

            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />

        </fieldset>

        <fieldset>

        <label for="confirm">Confirm Password: *</label>

        <input type="password" name="confirm" id="confirm" required

            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" />

        </fieldset>

        <button name="register" class="offset-button">Register</button>

        </form>
    </section>
</body>
</html>