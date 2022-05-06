begin;
drop view if exists lista_schede;
create view lista_schede as 
with main as (
  select
    s.id scheda, 
    s.titolo,
    s.tsk,
    tsk.value as tipo,
    s.cmpn,
    concat(u.nome,' ',u.cognome) operatore, 
    nctn.nctn, 
    concat(i.prefisso,' ',i.inventario,' ',i.suffisso) as inventario
  from scheda s
  INNER JOIN liste.tsk as tsk on s.tsk = tsk.id
  INNER JOIN utenti u on s.cmpn = u.id
  INNER JOIN nctn_scheda nctn on nctn.scheda = s.id
  left join inventario i on i.scheda = s.id
),
stato as (select * from stato_scheda),
ogtd as (
  select
    og_nu.scheda,
    ogtd.id as ogtdid,
    ogtd.value as ogtd
  from og_nu
  INNER JOIN liste.ogtd as ogtd on og_nu.ogtd = ogtd.id
  union 
  select 
    og_ra.scheda,
    ogtd.id as ogtdid,
    ogtd.value as ogtd
  from og_ra
  inner join liste.ra_cls_l4 ogtd on og_ra.l4 = ogtd.id
),
mtc as ( select * from mtc ),
cronologia as (
  select 
    dt.scheda,
    dt.dtzgi,
    dt.dtzgf,
    inizio.value as inizio, 
    fine.value as fine
  from dt
  inner join liste.cronologia as inizio on dt.dtzgi = inizio.id
  inner join liste.cronologia as fine on dt.dtzgf = fine.id
),
ubicazione as (
  select lc.scheda,lc.piano, lc.sala, lc.contenitore, lc.cassetta, s.descrizione as nome_sala from lc, liste.sale s where lc.sala = s.id
),
localizzazione as (
  select s.scheda, s.comune comid, s.via viaid, s.geonote, c.comune, v.via
  from geolocalizzazione s
  inner join comuni c on s.comune = c.id
  left join vie v on s.via = v.osm_id
)
select distinct 
  main.scheda, 
  main.nctn,
  main.tsk,
  main.tipo,
  main.inventario,
  main.titolo, 
  main.cmpn,
  main.operatore,
  stato.chiusa,
  stato.verificata,
  stato.inviata,
  stato.accettata,
  ogtd.ogtd, 
  cronologia.inizio, 
  cronologia.fine, 
  ubicazione.piano,
  ubicazione.sala,
  ubicazione.contenitore,
  ubicazione.cassetta,
  localizzazione.comune, 
  localizzazione.via
from main
inner join stato on stato.scheda = main.scheda
inner join ogtd on ogtd.scheda = main.scheda
inner join mtc on mtc.scheda = main.scheda
inner join cronologia on cronologia.scheda = main.scheda
inner join ubicazione on ubicazione.scheda = main.scheda
left join localizzazione on localizzazione.scheda = main.scheda;
alter view lista_schede owner to marta;
commit;