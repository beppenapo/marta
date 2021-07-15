select
gpl.value as gpl
, gp.gpdpx
, gp.gpdpy
, gpm.value as gpm
, gpt.value as gpt
, gpp.value as gpp
, gp.gpbb
, gp.gpbt
from gp
inner join liste.gpl on gp.gpl = gpl.id
inner join liste.gpm on gp.gpm = gpm.id
inner join liste.gpt on gp.gpt = gpt.id
inner join liste.gpp on gp.gpp = gpp.id
-- where og.scheda = 286
;
