select
   s.id
   ,f.file
   ,g.classe
   ,g.ogtd
   ,lc.piano
   ,lc.sala
   ,sale.descrizione nome_sala
   ,lc.contenitore 
from scheda s 
inner join lc on lc.scheda = s.id 
inner join liste.sale sale on lc.sala = sale.id 
inner join file f on f.scheda = s.id 
inner join gallery g on g.id = s.id 
inner join geolocalizzazione geo on geo.scheda = s.id 
where f.tipo = 3 
   and f.foto_principale = true
   and geo.comune = 111 
   -- LIMIT 24 OFFSET 0;
