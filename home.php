<?php
$title ="HomePage"; 
include("shared/header.php");
?>
<h1>Welcome</h1>
    <section class="<?php echo $title?>">
        <p>After you've created an account, you can add words and their translations to the dictionary; you can also add parts of speech to the dropdown list.If you type a part of speech in the input field it will submit that part of speech instead of the one in the dropdown.
        <br>After you have added enough words you can generate a random sentence + its translation structured according to the selected parts of speech.
        (Pronoun + verb + preposition + noun => He goes to college).<br>
        You may also add images to the words.
        </p>
    </section>
</body>
</html>

