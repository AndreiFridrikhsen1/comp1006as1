<?php 
$title = "Show all";
include("db.php");
include("auth.php");
$index = 1;
function fetchAllWords($pdo){// pass in pdo to make it acessible inside the function
    $data =[]; //stores fetched words and translations
    $stmt = $pdo->prepare("SELECT * FROM words");
    $stmt->execute();
    $data['words'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM translations");
    $stmt->execute();
    $data['translations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $data;
    

}
$data = fetchAllWords($pdo);


    
    function displayTable($words,$index) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Word</th><th>Part of Speech</tr>';
        foreach ($words as $word) {
            echo '<tr>';
            echo '<td>' .  $index++ . '</td>'; 
            echo '<td>' . htmlspecialchars($word['word']) . '</td>'; 
            echo '<td>' . htmlspecialchars($word['part_of_speech']) . '</td>'; 
            echo '</tr>';
        }
        echo '</table>';
    }
    function displayTable2($translations) {
        echo '<table>';
        echo '<tr><th>Translation</th><th>Part of Speech</th><th>Actions</th></tr>';
        foreach ($translations as $translation) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($translation['translation']) . '</td>'; 
            echo '<td>' . htmlspecialchars($translation['part_of_speech']) . '</td>'; 
            echo '<td><a class = "button" href="edit-word.php?wordId=' . htmlspecialchars($translation['word_id']) . ' ">Edit</a>
            <a class="button" href="delete.php?wordId=' . htmlspecialchars($translation['word_id']) . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }

?>
<?php include("shared/header.php")?>
   <section class="show">
        <div>
            <?php displayTable($data['words'], $index); displayTable2($data['translations']) ?>
        </div>
        <form method="POST" action = "delete-all.php">
            <input class="button" type="submit" name="deleteAll" id="delete" value="Delete All">
        </form>
        
    </section>
    
    <script src="js/script.js"></script>
   
</body>
</html>
