select gp.scheda, array_agg(file.file) file, gp.gpdpx, gp.gpdpy,c.comune, geoloc.via, gallery.classe, gallery.ogtd
from gp
left join geolocalizzazione geoloc on geoloc.scheda = gp.scheda
left join comuni c on geoloc.comune = c.id
left join file on file.scheda = gp.scheda
inner join gallery on gallery.id = gp.scheda
group by gp.scheda, gp.gpdpx, gp.gpdpy,c.comune, geoloc.via, gallery.classe, gallery.ogtd
