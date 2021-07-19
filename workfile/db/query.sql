select nctn.nctn, s.titolo, stato.*
from scheda s
INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
INNER JOIN stato_scheda stato on stato.scheda = s.id
where s.cmpn = 36
ORDER BY nctn asc
;
