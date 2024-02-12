Create database dictionary;
use dictionary;
create table words (
	word_id int,
    word varchar(50)
);

create table translations (
	word_id int,
    translation varchar(50)
);
select word_id, word 
from words;
