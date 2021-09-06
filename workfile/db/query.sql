select distinct piano,
CASE
  WHEN piano = -1 THEN 'Deposito'
  WHEN piano = 0 THEN 'Piano terra'
  WHEN piano = 1 THEN 'Primo piano'
  WHEN piano = 3 THEN 'Terzo piano'
END as nome
from liste.sale
order by piano asc;
