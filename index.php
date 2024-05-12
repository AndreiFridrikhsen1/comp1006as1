<?php
include("auth.php");
$title = "Home";
try{
    include("db.php");
    // fetch data from added words table and part_of_speech

    $stmt = $pdo->prepare("SELECT DISTINCT part_of_speech FROM words");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT added_word FROM added_words");
    $stmt->execute();
    $addedWords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $picturePaths = [];
    $sentence = "";
    $translationString="";
}
catch(Exception $error){
    header('location: errorPage.php');
}



if (isset($_POST['generate']) || isset($_POST['add']) || isset($_POST['remove']) ) {
    $dropdown = $_POST['dropdown'];
// if results empty 
    if (empty($results)) {
        echo "add new word";
        
    }else {
        // if add button was clicked 
        if (isset($_POST["add"])){
            
            // insert data safely with prepared stmnts 
            $stmnt =$pdo->prepare("INSERT INTO added_words (added_word) VALUES (:added_word)");
            $stmnt->bindParam(":added_word", $dropdown);
            $stmnt->execute();
            $pdo=null;
             // refresh page
            header("refresh: 1;");
        // remove last added element   
        }
        if (isset($_POST["remove"])){
            $stmt = $pdo->prepare("truncate table added_words;");
            $stmt->execute();
            $pdo=null;
             // refresh page
            header("refresh: 1;");
        }
        if (isset($_POST["generate"]) && !empty($addedWords)){
            foreach ($addedWords as $word){
                $stmt = $pdo->prepare("SELECT word_id,word FROM words where part_of_speech = :part_of_speech order by RAND() limit 1");
                $stmt ->bindParam(":part_of_speech", $word['added_word']);
                $stmt->execute();
                $fetchedWords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($fetchedWords as $fetchedWord){
                    // added the words added by the user to the string
                    $sentence .= htmlspecialchars($fetchedWord['word']) . " ";
                    //fetch translations of the words and add them to the string 
                    $stmt = $pdo->prepare("SELECT translation FROM translations where word_id = :id");
                    $stmt ->bindParam(":id", $fetchedWord["word_id"]);
                    $stmt->execute();
                    $translations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($translations as $translation){
                       
                        $translationString .= htmlspecialchars($translation["translation"]) ." ";
                        
                    }
                    //fetch pictures
                    $stmt = $pdo->prepare("SELECT picture FROM words where word_id = :id");
                    $stmt ->bindParam(":id", $fetchedWord["word_id"]);
                    $stmt->execute();
                    $pictures = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach($pictures as $picture){
                    if(!empty($picture['picture'])){
                        array_push($picturePaths, $picture['picture']);

                    }
                    }
                    
                    

                }
                

            }
        }
    }
    }

?>
<?php include("shared/header.php")?>
   <div class='sentence'>
   <?php
    if(!empty($sentence)){
        echo "<p> Random sentence: ".$sentence."</p>";
        echo "<p> Translation: ".$translationString."</p>";
        echo "<div id='imgContainer'>";
        foreach($picturePaths as $image){
            echo "<img src='" . htmlspecialchars($image) . "' alt='Word Image' />";
        }
        echo "</div>";
                
    }

   ?>
   </div>
   <section class="home">
   <div class="added">
        <?php 
            //  check if words have been added
            if (!empty($addedWords)){

            foreach ($addedWords as $addedWord){
            
            echo "<p>".htmlspecialchars($addedWord['added_word'])."<span> + </span></p><br>";
            }   
            
        }

        
        ?>
        </div>
        <form method="POST" action="index.php">
            <select name="dropdown" id="dropdown">
                <?php if (empty($results)){echo "<option>Empty</option>";} else { foreach($results as $result){ echo "<option>".htmlspecialchars($result['part_of_speech'])."</option>";}}?>
                <input class="button" type=submit id="add" value="add" name = "add"><br>
                <input class="button" type=submit id="generate" value = "generate a random sentence" name="generate"><br>
                <input class="button" type=submit id="remove" value = "remove" name="remove">

            </select> 
        </form>
       
        
    </section>
    
   
</body>
</html>