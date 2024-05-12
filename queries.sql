
use Andrei200558923;
DROP TABLE IF EXISTS translations;
DROP TABLE IF EXISTS words;


CREATE TABLE words (
    word_id INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(100),
    part_of_speech VARCHAR(100)
);

CREATE TABLE translations (
    word_id INT,
    translation VARCHAR(100),
    FOREIGN KEY (word_id) REFERENCES words(word_id),
    part_of_speech VARCHAR(100)
);

select * from words;
select * from translations;
delete  from words;