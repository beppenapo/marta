\COPY (select nctn.nctn, ub.stis, concat(m.gruppo,' ',m.code) munsell from nctn_scheda nctn inner join ub on ub.scheda = nctn.scheda inner join munsell on munsell.scheda = nctn.scheda inner join liste.munsell m on munsell.munsell = m.id order by 1 asc) TO 'stime_munsell.csv' (format CSV);
