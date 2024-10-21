select 
   s.id
   ,f.file
   ,g.classe
   ,g.ogtd 
from scheda s 
inner join file f on f.scheda = s.id 
inner join gallery g on g.id = s.id 
inner join tag on tag.scheda = s.id 
where f.tipo = 3 
   and f.foto_principale = true 
   and '{personaggi mitologici}' <@ tag.tags  
LIMIT 24 
OFFSET 0;