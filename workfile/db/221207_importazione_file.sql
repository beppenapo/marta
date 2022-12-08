begin;
drop table if exists import;
create table import(file character varying primary key, nctn integer);
alter table import owner to marta;
copy import from '/var/www/marta/workfile/csv/file.csv' delimiter ',' csv header;
commit;
