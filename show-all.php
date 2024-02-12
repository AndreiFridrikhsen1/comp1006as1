<?php 
include("db.php");
$stmt = $pdo->prepare("SELECT * FROM words");
$stmt->execute();
$words = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM translations");
$stmt->execute();
$translations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    function displayTable($words) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Word</th><th>Part of Speech</th></tr>';
        foreach ($words as $word) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($word['word_id']) . '</td>'; 
            echo '<td>' . htmlspecialchars($word['word']) . '</td>'; 
            echo '<td>' . htmlspecialchars($word['part_of_speech']) . '</td>'; 
            echo '</tr>';
        }
        echo '</table>';
    }
    function displayTable2($translations) {
        echo '<table>';
        echo '<tr><th>Translation</th><th>Part of Speech</th></tr>';
        foreach ($translations as $translation) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($translation['translation']) . '</td>'; 
            echo '<td>' . htmlspecialchars($translation['part_of_speech']) . '</td>'; 
            echo '</tr>';
        }
        echo '</table>';
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link href = "style.css" rel="stylesheet">
</head>
<body>
   <?php include("shared/header.php")?>
   <section class="show">
        <div>
            <?php displayTable($words); displayTable2($translations) ?>
        </div>
        <form method="POST" action = "delete-all.php">
            <input type="submit" name="delete" id="delete" value="Delete All">
        </form>
        
    </section>
   
</body>
</html>