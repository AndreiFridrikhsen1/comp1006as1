<?php

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
    <?php include("shared/header.php"); ?>
    <section>
        <form class="add-category" method="post" action="part-of-speech.php">
            <input type="text" required placeholder="hint: noun, verb, adjective" id="partOfSpeech" name="partOfSpeech">
            <input type="submit" name="submit" id="submit">
        </form>
    </section>
    
</body>
</html>