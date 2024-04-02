<?php
$title = "Add word";
include("db.php");
include("auth.php");
$title = "Add word";
$stmt = $pdo->prepare("SELECT DISTINCT part_of_speech FROM words");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$partOfSpeechSelected = true;
$validated = true; 
$added = false;
$errors = ["Word already exists", "Word shouln't contain any digits"];
$wordExists = false;
$containsDigits = false;
$picPath;
if (isset($_POST['submit'])) {
    // check for the image
    if(!empty($_FILES['picture'])){
        $tempLocation = $_FILES['picture']['tmp_name'];
        $type = mime_content_type($tempLocation);
        $picPath = "pictures/" . $_FILES['picture']['name'];
        if($type == "image/jpg" || $type == "image/jpeg"|| $type=="image/png"){
            move_uploaded_file($tempLocation,$picPath);
        }else {
            echo "invalid type";
            $validated = false;
            exit();
        }
        
    }
    $word = trim($_POST['word']);
    $translation = trim($_POST['translation']);
    $dropdown = $_POST['dropdown']; 
    $word = strtolower($word);
    $translation=strtolower($translation);

    $partOfSpeech = "";
    //check if word exists
    $stmt = $pdo->prepare("SELECT * FROM words");
    $stmt->execute();
    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($words as $existingWord) {
        echo $existingWord['part_of_speech'];
        echo $_POST['part_of_speech'];
    
        if(trim($_POST['partOfSpeech'])==trim($existingWord['part_of_speech'])|| $_POST['word']==$existingWord['word']){
            $validated = false;
            $wordExists = true;
            
        }
    }
    if ($wordExists == false){
        

        // use text input if not empty else use drop down
        if (empty($_POST['partOfSpeech'])) {
            $partOfSpeech = $dropdown;
        } else {
            $partOfSpeech = trim($_POST['partOfSpeech']);
            $partOfSpeech = strtolower($partOfSpeech);

        }


        // words and translations shouldnt containt digits
        if (!ctype_alpha($word) || !ctype_alpha($translation)) {
           // echo "Word and translation should only contain letters.<br>";
            $containsDigits=true;
            $validated = false;
        }

        // if part of speech isnt selected at all 
        if ($dropdown === "Empty" && empty($_POST['partOfSpeech'])) {
           // echo "Please select a valid part of speech.<br>";
            $partOfSpeechSelected = false;
            $validated = false;
        }

        //insert into data table
        if ($validated && isset($_POST["submit"])) {
            $stmnt =$pdo->prepare("INSERT INTO words (word, part_of_speech, picture) VALUES (:placeholder1,:placeholder2, :pic)");
            $stmnt->bindParam(":placeholder1", $word);
            $stmnt->bindParam(":placeholder2", $partOfSpeech);
            $stmnt->bindParam(":pic", $picPath);
            $stmnt->execute();
            // get the last inserted
            $id = $pdo ->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO translations (word_id,translation,part_of_speech) VALUES (:id,:translation, :part_of_speech)");
            $stmt->bindParam(":translation", $translation);
            $stmt->bindParam(":part_of_speech", $partOfSpeech);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            $added = true;
            $pdo=null;
            // refresh page
            header("refresh: 1;");
        }
}
}
?>
    <?php include("shared/header.php"); if ($added) {echo "<h1>Word was added!</h1>";} if ($wordExists){echo '<h1>'.$errors[0].'</h1>'; if ($containsDigits) {echo '<h1>'.$errors[0].'</h1>';};}?>
    <h1><?php echo $title?></h1> 
    <section>
    
        <form id="addWord" method="post" action ="add-word.php" enctype="multipart/form-data">
        <?php if($containsDigits){echo "<p class='error'>Word and translation should only contain letters, not digits or spaces.</p>";} if(!$partOfSpeechSelected){echo "<p class='error'>Choose part of speech</p>";}  ?>
            <input type="text" id="word" name="word" placeholder="Word" required><br>
            <input type="text" id="translation" name="translation" placeholder="Translation" required><br>
            <p>Part of speech</p>
            <input type="text"  placeholder="type here: noun, verb, adjective" id="partOfSpeech" name="partOfSpeech"></br>
            <select name="dropdown" id="dropdown">
                <?php if (empty($results)){echo "<option>Empty</option>";} else { foreach($results as $result){ echo "<option>".htmlspecialchars($result['part_of_speech'])."</option>";}}?>
            </select>
            <p>Add picture</p>
            <input id="picture" name="picture" type="file" accept="image/*">
            <input type="submit" name="submit" id="submit">
            
        </form>
    </section>
    
</body>
</html>