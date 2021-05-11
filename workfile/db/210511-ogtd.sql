BEGIN;
ALTER TABLE liste.ra_ogtd rename to ogtd;
ALTER TABLE liste.ogtd ADD COLUMN tipo integer;
update liste.ogtd set tipo = 1;
ALTER TABLE liste.ogtd DROP CONSTRAINT ra_ogtd_value_key;
ALTER TABLE liste.ogtd ADD CONSTRAINT ra_ogtd_value_key UNIQUE (value,tipo);
insert into liste.ogtd(value,tipo) select value, 2 from liste.nu_ogtd order by 1 asc;
DROP TABLE liste.nu_ogtd;
COMMIT;
