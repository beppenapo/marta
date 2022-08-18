BEGIN;
-- cambia il numero del piano 3
update liste.sale set piano = 2 where piano = 3;
-- aggiungi i "fuori vetrina" a tutte le sale dei piani 0, 1 e 2
insert into liste.vetrine (sala, vetrina) values (30,'fuori vetrina'), (31,'fuori vetrina'), (32,'fuori vetrina'), (5,'fuori vetrina'), (11,'fuori vetrina'), (12,'fuori vetrina'), (13,'fuori vetrina'), (14,'fuori vetrina'), (17,'fuori vetrina'), (22,'fuori vetrina');
-- cambio i numeri delle sale da arabi a romani
UPDATE liste.sale set descrizione = 'IX' where id = 5;
UPDATE liste.sale set descrizione = 'X' where id = 6;
UPDATE liste.sale set descrizione = 'XI' where id = 7;
UPDATE liste.sale set descrizione = 'XII' where id = 8;
UPDATE liste.sale set descrizione = 'XIII' where id = 9;
UPDATE liste.sale set descrizione = 'XIV' where id = 10;
UPDATE liste.sale set descrizione = 'XV' where id = 11;
UPDATE liste.sale set descrizione = 'XVI' where id = 12;
UPDATE liste.sale set descrizione = 'XVII' where id = 13;
UPDATE liste.sale set descrizione = 'XVIII' where id = 14;
UPDATE liste.sale set descrizione = 'XIX' where id = 15;
UPDATE liste.sale set descrizione = 'XX' where id = 16;
UPDATE liste.sale set descrizione = 'XXI' where id = 17;
UPDATE liste.sale set descrizione = 'XXII' where id = 18;
UPDATE liste.sale set descrizione = 'XXIII' where id = 19;
UPDATE liste.sale set descrizione = 'XXIV' where id = 20;
UPDATE liste.sale set descrizione = 'XXV' where id = 21;
UPDATE liste.sale set descrizione = 'I' where id = 22;
UPDATE liste.sale set descrizione = 'II' where id = 23;
UPDATE liste.sale set descrizione = 'III' where id = 24;
UPDATE liste.sale set descrizione = 'IV' where id = 25;
UPDATE liste.sale set descrizione = 'V' where id = 26;
UPDATE liste.sale set descrizione = 'VI' where id = 27;
UPDATE liste.sale set descrizione = 'VII' where id = 28;
UPDATE liste.sale set descrizione = 'VIII' where id = 29;

-- correggo gli errori nella tabella lc
COMMIT;

-- select s.id, s.piano, s.sala, v.vetrina, v.descrizione from liste.sale s LEFT join liste.vetrine v ON v.sala = s.id where s.piano = 2 order by 2, 3, 4 asc;
-- select sala, id from liste.sale where piano > -1 and descrizione is null order by id,sala asc;