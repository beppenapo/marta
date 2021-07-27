-- select og.scheda, scheda.tsk, ogtd.value as ogtd
-- from og
-- inner join liste.ogtd as ogtd on og.ogtd = ogtd.id
-- inner join scheda on og.scheda = scheda.id
-- inner join biblio_scheda bs on scheda.id = bs.scheda
-- where bs.biblio = 37 order by ogtd asc;
select bs.scheda, nctn.nctn, scheda.titolo
from biblio_scheda bs
inner JOIN nctn_scheda nctn on nctn.scheda = bs.scheda
inner join scheda on bs.scheda = scheda.id
