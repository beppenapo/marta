select b.id, b.titolo, b.anno, b.autore,c.id as contrib_id, c.titolo as contrib_tit, c.autore as contrib_aut, bs.pagine, bs.figure
from bibliografia b
INNER JOIN biblio_scheda bs on bs.biblio = b.id
left join contributo c on bs.contributo = c.id
WHERE bs.scheda = 391
ORDER BY anno, autore, titolo asc;
