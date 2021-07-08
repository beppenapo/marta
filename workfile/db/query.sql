SELECT
  s.id
  , nctn.nctn
  , s.titolo
  , s.tsk
  , tsk.value as tipo
  , ogtd.value as ogtd
  , array_agg(m.value order by materia asc) as materia
  , concat (dtzg.value,' ', dtzs.value) as cronologia
  , lc.piano
  , concat(loc.sala,' ', loc.descrizione) as sala
from scheda s
INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
INNER JOIN liste.tsk as tsk on s.tsk = tsk.id
INNER JOIN og_nu on og_nu.scheda = s.id
INNER JOIN liste.ogtd as ogtd on og_nu.ogtd = ogtd.id
INNER JOIN mtc on mtc.scheda = s.id
INNER JOIN liste.materia as m on mtc.materia = m.id
INNER JOIN dt on dt.scheda = s.id
INNER JOIN liste.dtzg on dt.dtzg = dtzg.id
INNER JOIN liste.dtzs on dt.dtzs = dtzs.id
INNER JOIN lc on lc.scheda = s.id
INNER JOIN liste.sale as loc on lc.sala = loc.id
INNER JOIN utenti u on s.cmpn = u.id
where s.cmpn = 3
GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, ogtd.value, dtzg.value, dtzs.value, lc.piano, loc.sala, loc.descrizione

UNION

SELECT
  s.id
  , nctn.nctn
  , s.titolo
  , s.tsk
  , tsk.value as tipo
  , l4.value as ogtd
  , array_agg(m.value order by materia asc) as materia
  , concat (dtzg.value,' ', dtzs.value) as cronologia
  , lc.piano
  , concat(loc.sala,' ', loc.descrizione) as sala
from scheda s
INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
INNER JOIN liste.tsk as tsk on s.tsk = tsk.id
INNER JOIN og_ra on og_ra.scheda = s.id
INNER JOIN liste.ra_cls_l4 as l4 on og_ra.l4 = l4.id
INNER JOIN mtc on mtc.scheda = s.id
INNER JOIN liste.materia as m on mtc.materia = m.id
INNER JOIN dt on dt.scheda = s.id
INNER JOIN liste.dtzg on dt.dtzg = dtzg.id
INNER JOIN liste.dtzs on dt.dtzs = dtzs.id
INNER JOIN lc on lc.scheda = s.id
INNER JOIN liste.sale as loc on lc.sala = loc.id
INNER JOIN utenti u on s.cmpn = u.id
where s.cmpn = 3
GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, l4.value, dtzg.value, dtzs.value, lc.piano, loc.sala, loc.descrizione
order by nctn asc;
