select distinct 
  -- gallery.*, 
  file.* 
from gallery, liste.vetrine contenitore, file 
where 
  file.tipo = 3
  and (substring(file.file from 16 for 3) = '_A_' or substring(file.file from 16 for 4) = '_02_')
  and contenitore.sala = gallery.sala_id 
  and contenitore.vetrina = gallery.contenitore
  and gallery.piano = 1
  and file.scheda = gallery.id 
order by 1 asc;
