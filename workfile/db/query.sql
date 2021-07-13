WITH
scheda as (
  SELECT
   s.id
   , nctn.nctn
   , s.titolo
   , s.tsk
   , tsk.value as tipo
   , array_agg(m.value order by materia asc) as materia
   , concat (dtzg.value,' ', dtzs.value) as cronologia
   , lc.piano
   , concat(loc.sala,' ', loc.descrizione) as sala
   from scheda s
   INNER JOIN stato_scheda on stato_scheda.scheda = s.id
   INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
   INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
   INNER JOIN liste.tsk as tsk on s.tsk = tsk.id
   INNER JOIN mtc on mtc.scheda = s.id
   INNER JOIN liste.materia as m on mtc.materia = m.id
   INNER JOIN dt on dt.scheda = s.id
   INNER JOIN liste.dtzg on dt.dtzg = dtzg.id
   INNER JOIN liste.dtzs on dt.dtzs = dtzs.id
   INNER JOIN lc on lc.scheda = s.id
   INNER JOIN liste.sale as loc on lc.sala = loc.id
   INNER JOIN utenti u on s.cmpn = u.id
   GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, dtzg.value, dtzs.value, lc.piano, loc.sala, loc.descrizione
),
-- nu as (
  -- SELECT og_nu.scheda, ogtd.value as ogtd
  -- from og_nu
  -- INNER JOIN liste.ogtd as ogtd on og_nu.ogtd = ogtd.id
-- ),
ra as(
  SELECT og_ra.scheda, l4.value as ogtd
  from og_ra
  INNER JOIN liste.ra_cls_l4 as l4 on og_ra.l4 = l4.id
)
select scheda.id, scheda.titolo, ra.ogtd from scheda, ra where ra.scheda = scheda.id
-- select scheda.*, nu.*, ra.* from scheda, nu, ra where nu.id = scheda.id and ra.id = scheda.id;
