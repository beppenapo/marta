begin;
alter table inventario add column scheda integer references scheda(id) on delete cascade;
update inventario set scheda = s.scheda from inventario_scheda s where s.inventario = inventario.id;
delete from inventario where scheda is null;
alter table inventario alter column scheda set not null;
drop table inventario_scheda;
alter table inventario drop constraint inventario_inventario_prefisso_suffisso_key ;
alter table inventario add constraint inventario_unique UNIQUE (inventario, prefisso, suffisso,scheda);
commit;