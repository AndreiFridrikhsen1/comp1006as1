<?php
include("db.php");

$stmt = $pdo->prepare("SELECT DISTINCT part_of_speech FROM words");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$validated = true; 
$added = false;
$errors = ["Word already exists", "Word shouln't contain any digits"];
$wordExists = false;
$containsDigits = false;
if (isset($_POST['submit'])) {
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
            $stmnt =$pdo->prepare("INSERT INTO words (word, part_of_speech) VALUES (:placeholder1,:placeholder2)");
            $stmnt->bindParam(":placeholder1", $word);
            $stmnt->bindParam(":placeholder2", $partOfSpeech);
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add card</title>
    <link href = "style.css" rel="stylesheet">
</head>
<body>
    <?php include("shared/header.php"); if ($added) {echo "<h1>Word was added!</h1>";} if ($wordExists){echo '<h1>'.$errors[0].'</h1>'; if ($containsDigits) {echo '<h1>'.$errors[0].'</h1>';};}?>
    <section>
        <form method="post" action ="add-word.php">
            <input type="text" id="word" name="word" placeholder="Word" required><br>
            <input type="text" id="translation" name="translation" placeholder="Translation" required><br>
            <p>Part of speech</p>
            <select name="dropdown" id="dropdown">
                <?php if (empty($results)){echo "<option>Empty</option>";} else { foreach($results as $result){ echo "<option>".htmlspecialchars($result['part_of_speech'])."</option>";}}?>
            </select>
            <input type="text"  placeholder="type here: noun, verb, adjective" id="partOfSpeech" name="partOfSpeech"></br>
            <input type="submit" name="submit" id="submit">
        </form>
    </section>
    
</body>
</html>