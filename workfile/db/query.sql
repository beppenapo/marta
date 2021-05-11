select og.scheda, scheda.tipo, ogtd.value as ogtd
from og
inner join liste.ogtd as ogtd on og.ogtd = ogtd.id
inner join scheda on og.scheda = scheda.id
inner join biblio_scheda bs on scheda.id = bs.scheda
where bs.biblio = 33;
