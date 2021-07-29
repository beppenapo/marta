select b.id, b.titolo, b.anno, b.autore
from bibliografia b
INNER JOIN biblio_scheda bs on bs.biblio = b.id
WHERE bs.scheda = 304
ORDER BY anno, autore, titolo asc;
