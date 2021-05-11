select b.id, l.value as tipo, b.autore, b.titolo, count(s.*) as schede
from bibliografia b
inner join liste.biblio_tipo as l on b.tipo = l.id
left join biblio_scheda bs on bs.biblio = b.id
left join scheda s on bs.scheda = s.id
group by b.id, l.value, b.autore, b.titolo
order by b.titolo asc;
