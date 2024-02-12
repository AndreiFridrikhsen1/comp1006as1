<?php
include("db.php");

$stmt = $pdo->prepare("SELECT part_of_speech FROM words");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$validated = true; 
$added = false;

if (isset($_POST['submit'])) {
    $word = trim($_POST['word']);
    $translation = trim($_POST['translation']);
    $dropdown = $_POST['dropdown']; 

    $partOfSpeech = "";
    // use text input if not empty else use drop down
    if (empty($_POST['partOfSpeech'])) {
        $partOfSpeech = $dropdown;
    } else {
        $partOfSpeech = $_POST['partOfSpeech'];
    }


    // words and translations shouldnt containt digits
    if (!ctype_alpha($word) || !ctype_alpha($translation)) {
        echo "Word and translation should only contain letters.<br>";
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

        $stmt = $pdo->prepare("INSERT INTO translations (translation,part_of_speech) VALUES (:translation, :part_of_speech)");
        $stmt->bindParam(":translation", $translation);
        $stmt->bindParam(":part_of_speech", $partOfSpeech);
        $stmt->execute();
        
        $added = true;
        $db=null;
        // refresh page
        header("refresh: 1;");
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
    <?php include("shared/header.php"); if ($added) {echo "<h1>Word was added!</h1>";} ?>
    <section>
        <form method="post" action ="add-word.php">
            
            <?php include("db.php");
                if(!$validated && isset($_POST["submit"])){
                    echo"Words shouldn't contain digits";

                }
            ?>
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