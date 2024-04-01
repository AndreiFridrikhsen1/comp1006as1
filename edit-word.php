<?php
include("db.php");
$title = "Edit word";
$wordId = $_GET['wordId'];
$stmt = $pdo->prepare("SELECT DISTINCT part_of_speech FROM words");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$validated = true; 
$added = false;
$errors = ["Word already exists", "Word shoudn't contain any digits"];
$wordExists = false;
$containsDigits = false;
if (isset($_POST['submit'])) {
    $word = trim($_POST['word']);
    $translation = trim($_POST['translation']);
    $dropdown = $_POST['dropdown']; 
    $word = strtolower($word);
    $translation=strtolower($translation);
    #get wordId to be deleted from post array
    $wordId = $_POST['wordId'];

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
            echo "Word and translation should only contain letters.<br>";
            $containsDigits=true;
            $validated = false;
        }

        // if part of speech isnt selected at all 
        if ($dropdown === "Empty" && empty($_POST['partOfSpeech'])) {
            echo "Please select a valid part of speech.<br>";
            $validated = false;
        }

        //insert into data table
        if ($validated && isset($_POST["submit"])) {
            $stmnt =$pdo->prepare("UPDATE words SET word = :placeholder1, part_of_speech = :placeholder2 where word_id = :placeholder3");
            $stmnt->bindParam(":placeholder1", $word);
            $stmnt->bindParam(":placeholder2", $partOfSpeech);
            $stmnt->bindParam(":placeholder3", $wordId);
            $stmnt->execute();
            
            $stmt = $pdo->prepare("UPDATE translations SET translation =:translation, part_of_speech = :part_of_speech where word_id = :id");
            $stmt->bindParam(":translation", $translation);
            $stmt->bindParam(":part_of_speech", $partOfSpeech);
            $stmt->bindParam(":id", $wordId);
            $stmt->execute();
            
            $added = true;
            $pdo=null;
            // refresh page
            header('Location: '."show-all.php");
        }
}
}
?>

    <?php include("shared/header.php"); if ($added) {echo "<h1>Word was added!</h1>";} if ($wordExists){echo '<h1>'.$errors[0].'</h1>'; if ($containsDigits) {echo '<h1>'.$errors[0].'</h1>';};}?>
    <h1><?php echo $title?></h1> 
    <section>
        <form method="post" action ="edit-word.php">
            <input type="text" id="word" name="word" placeholder="Word" required><br>
            <input type="text" id="translation" name="translation" placeholder="Translation" required><br>
            <p>Part of speech</p>
            <select name="dropdown" id="dropdown">
                <?php if (empty($results)){echo "<option>Empty</option>";} else { foreach($results as $result){ echo "<option>".htmlspecialchars($result['part_of_speech'])."</option>";}}?>
            </select>
            <input type="hidden" name="wordId" id="wordId" value="<?php echo $wordId?>">
            <input type="text"  placeholder="type here: noun, verb, adjective" id="partOfSpeech" name="partOfSpeech"></br>
            <input type="submit" name="submit" id="submit">
        </form>
    </section>
    
</body>
</html>