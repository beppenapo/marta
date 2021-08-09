BEGIN;
-- create table liste.cronologia(id serial primary key, value character VARYING unique not null);
-- alter table dt
--   add column dtzgi integer references liste.cronologia(id) on delete cascade,
--   add column dtzgf integer references liste.cronologia(id) on delete cascade;
-- alter TABLE dt alter column dtzg drop not null;
-- alter TABLE dt alter column dtzs drop not null;
-- alter TABLE dt alter column dtsi drop not null;
-- alter TABLE dt alter column dtsf drop not null;
-- alter TABLE dt alter column dtzgi set not null;
-- alter TABLE dt alter column dtzgf set not null;
-- insert into liste.cronologia(value) values ('XL  millennio a.C.'), ('XXXV millennio a.C.'), ('XXX millennio a.C.'), ('XXV millennio a.C.'), ('XX millennio a.C.'), ('XV millennio a.C.'), ('X millennio a.C.'), ('IX millennio a.C.'), ('VIII millennio a.C.'), ('VII millennio a.C.'), ('VI millennio a.C.'), ('V millennio a.C.'), ('IV millennio a.C.'), ('III millennio a.C.'), ('II millennio a.C.'), ('I millennio a.C.'), ('XIII secolo a.C.'), ('XII secolo a.C.'), ('XI secolo a.C.'), ('X secolo a.C.'), ('IX secolo a.C.'), ('VIII secolo a.C.'), ('VII secolo a.C.'), ('VI secolo a.C.'), ('V secolo a.C.'), ('IV secolo a.C.'), ('III secolo a.C.'), ('II secolo a.C.'), ('I secolo a.C.'), ('I  secolo d.C.'), ('II secolo d.C.'), ('III  secolo d.C.'), ('IV  secolo d.C.'), ('V  secolo d.C.'), ('VI  secolo d.C.'), ('VII  secolo d.C.'), ('VIII  secolo d.C.'), ('IX  secolo d.C.'), ('X  secolo d.C.'), ('XI  secolo d.C.'), ('XII  secolo d.C.'), ('XIII  secolo d.C.'), ('XIV secolo d.C.'), ('XV  secolo d.C.'), ('XVI  secolo d.C.'), ('XVII  secolo d.C.'), ('XVIII  secolo d.C.'), ('XIX  secolo d.C.'), ('XX  secolo d.C.'), ('XXI secolo d.C.');
-- update dt set dtzgi = 23, dtzgf  = 24 where dtzg = 14;
-- update dt set dtzgi = 24, dtzgf = 24 where dtzg = 15;
-- update dt set dtzgi = 25, dtzgf = 25 where dtzg = 17;
-- update dt set dtzgi = 26, dtzgf = 26 where dtzg = 19;
-- update dt set dtzgi = 26, dtzgf = 27 where dtzg = 20;
-- update dt set dtzgi = 27, dtzgf = 27 where dtzg = 21;
-- alter table dt drop column dtzg;
COMMIT;
