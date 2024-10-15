-- select s.id,cls.id, cls.value cls, ogtd.id, ogtd.value ogtd, f.file
-- from scheda s 
-- inner join file f on f.scheda = s.id 
-- inner join gallery g on g.id = s.id 
-- inner join og_ra on og_ra.scheda = s.id 
-- inner join liste.ra_cls_l3 cls on og_ra.l3 = cls.id 
-- inner join liste.ra_cls_l4 ogtd on og_ra.l4 = ogtd.id 
-- inner join tag on tag.scheda = s.id 
-- where f.tipo = 3 
-- and s.id = 505
-- and f.foto_principale = true 
-- and s.tsk = 1 
-- -- and cls.id = 33
-- and '{edifici}' <@ tag.tags  
-- -- order by 4,3 asc
-- -- LIMIT 24 OFFSET 0
-- ;

 SELECT scheda.id,
    nctn.nctn,
    classe.id AS classe_id,
    classe.value AS classe,
    ogtd.value AS ogtd,
    lc.piano,
    s.id AS sala_id,
    COALESCE(s.descrizione, s.sala::text::character varying, ''::character varying) AS sala,
    lc.contenitore
   FROM scheda
     JOIN nctn_scheda nctn ON nctn.scheda = scheda.id
     JOIN og_ra ON og_ra.scheda = scheda.id
     JOIN liste.ra_cls_l4 ogtd ON og_ra.l4 = ogtd.id
     JOIN liste.ra_cls_l3 l3 ON ogtd.l3 = l3.id
     JOIN liste.ra_cls_l2 l2 ON l3.l2 = l2.id
     JOIN liste.ra_cls_l1 classe ON l2.l1 = classe.id
     JOIN lc ON lc.scheda = scheda.id
     JOIN liste.sale s ON lc.sala = s.id
  WHERE scheda.tsk = 1
UNION
 SELECT scheda.id,
    nctn.nctn,
    11 AS classe_id,
    'MONETE'::character varying AS classe,
    btrim(concat(ogtd.value, ' ', COALESCE(ogto.value, ''::character varying))) AS ogtd,
    lc.piano,
    s.id AS sala_id,
    COALESCE(s.descrizione, s.sala::text::character varying, ''::character varying) AS sala,
    lc.contenitore
   FROM scheda
     JOIN nctn_scheda nctn ON nctn.scheda = scheda.id
     JOIN og_nu ON og_nu.scheda = scheda.id
     JOIN liste.ogtd ON og_nu.ogtd = ogtd.id
     JOIN lc ON lc.scheda = scheda.id
     JOIN liste.sale s ON lc.sala = s.id
     LEFT JOIN liste.ogto ON og_nu.ogto = ogto.id
  WHERE scheda.tsk = 2;