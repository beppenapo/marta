BEGIN;
-- insert into bibliografia(id, anno, tipo, luogo, autore, titolo, editore) values (164, '1997', 1, 'Taranto', 'AA.VV.', 'Catalogo del Museo Nazionale Archeologico di Taranto. I, 3. Atleti e guerrieri. Tradizioni aristocratiche a Taranto tra VI e C sec. a.C.', 'La colomba');
insert into biblio_scheda (scheda,biblio, pagine, livello) values
(1029, 164, '218, n. 40.5',  1),
(1030, 164, '218, n. 40.6',  1),
(1031, 164, '218, n. 40.1',  1),
(1032, 164, '218, n. 40.3',  1),
(1033, 164, '218, n. 40.2',  1),
(1115, 164, '210, n. 35.26', 1),
(1117, 164, '210, n. 35.25', 1),
(1118, 164, '266, n. 69.5',  1),
(1119, 164, '266, n. 69.4',  1),
(1120, 164, '264, n. 69.1',  1),
(1121, 164, '262, n. 67.2',  1);
COMMIT;
