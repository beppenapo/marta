select
l1.value as cls1
, l2.value as cls2
, l3.value as cls3
, l4.value as cls4
, coalesce(l5.value,'dato non inserito') as cls5
, coalesce(ogtt, 'dato non inserito') as ogtt
from og_ra og
inner join liste.ra_cls_l4 l4 on og.l4 = l4.id
inner join liste.ra_cls_l3 l3 on og.l3 = l3.id
inner join liste.ra_cls_l2 l2 on l3.l2 = l2.id
inner join liste.ra_cls_l1 l1 on l2.l1 = l1.id
left join liste.ra_cls_l5 l5 on og.l5 = l5.id
where og.scheda = 289;
