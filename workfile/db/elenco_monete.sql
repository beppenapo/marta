select 
  scheda.id,
  n.nctn, 
  trim(concat(coalesce(i.inventario,'0'),' ',i.suffisso)) inventario, 
  scheda.titolo,
  sala.piano,
  sala.sala, 
  case
    when lc.contenitore = '40' then 'monetiere'
    when lc.contenitore = '41' then 'cassaforte'
    else lc.contenitore
  end "scaffale/vetrina",
  case
    when lc.contenitore = '40' then 'monetiere'||lc.colonna::text
    when lc.contenitore = '41' then 'cassaforte'||lc.colonna::text
    else lc.colonna::text
  end colonna,
  case
    when lc.contenitore = '40' then 'plateau'||lc.ripiano::text
    else lc.ripiano::text
  end ripiano,
  concat(u.cognome,' ',u.nome) catalogatore
from scheda
inner join nctn_scheda n on n.scheda = scheda.id
inner join utenti u on scheda.cmpn = u.id
inner join lc on lc.scheda = scheda.id
inner join liste.sale sala on lc.sala = sala.id
left join inventario i on i.scheda = scheda.id
where scheda.tsk = 2
order by 1, 2 asc