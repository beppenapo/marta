select bs.scheda, nctn.nctn, scheda.tsk, scheda.titolo
from biblio_scheda bs
inner JOIN nctn_scheda nctn on nctn.scheda = bs.scheda
inner join scheda on bs.scheda = scheda.id
where bs.contributo = 3 order by nctn asc;
