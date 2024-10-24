--sale totali
-- select piano, count(*) sale from liste.sale where piano = 2 group by piano order by piano asc;

--contenitori totali

select s.piano, count(*)
from liste.sale s 
inner join liste.vetrine v on v.sala = s.id 
where v.vetrina = 'fuori vetrina'
   -- and piano = 1
group by s.piano
union 
select s.piano, count(*)
from liste.sale s
inner join liste.scaffali scaffali on scaffali.sala = s.id
group by s.piano

-- contenitori per piano
-- contenitori per sala
