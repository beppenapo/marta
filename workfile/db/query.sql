-- select ra.l4, l4.value as ogtd, count(ra.*)
-- from og_ra ra, liste.ra_cls_l4 l4
-- where ra.l4 = l4.id
-- group by ra.l4, l4.value
-- order by 3 desc

with tot as (select count(*) from og_ra),
ra as (select l4, count(*) from og_ra group by l4)
select ra.l4, l4.value as ogtd, ra.count, ((ra.count * 100 ) / tot.count)::int as perc
from ra, tot, liste.ra_cls_l4 l4
where ra.l4 = l4.id
group by ra.l4, ra.count, tot.count, l4.value
HAVING ((ra.count * 100 ) / tot.count)::int > 1
order by 3 desc;
