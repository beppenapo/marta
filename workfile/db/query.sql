select
og.scheda
, ogr.value as ogr
, coalesce(og.ogtt,'dato non inserito') as ogtt
, coalesce(og.ogth, 'dato non inserito') as ogth
, coalesce(og.ogtl, 'dato non inserito') as ogtl
, coalesce(ogto.value, 'dato non inserito') as ogto
, coalesce(ogts.value, 'dato non inserito') as ogts
, coalesce(ogtr.value, 'dato non inserito') as ogtr
, ogtd.value as ogtd
from og_nu og
inner join liste.ogr on og.ogr = ogr.id
inner join liste.ogtd on og.ogtd = ogtd.id
left join liste.ogto on og.ogto = ogto.id
left join liste.ogts on og.ogts = ogts.id
left join liste.ogtr on og.ogtr = ogtr.id
-- where og.scheda = 282
;
