begin;
drop table if exists stime;
create table stime(
  nctn integer primary key,
  scheda integer,
  stis character varying,
  stid character varying,
  invn character varying
);
alter table stime owner to marta;
copy stime(nctn,stis,stid) from '/var/www/marta/workfile/csv/dati/stime.csv' delimiter ',' csv header;
update stime set scheda = n.scheda from nctn_scheda n where stime.nctn = n.nctn;
update stime set invn = concat(i.inventario::text,'-',i.suffisso) from inventario i where stime.scheda = i.scheda;

drop table if exists munsell_update;
create table munsell_update(
  nctn integer primary key,
  scheda integer,
  munsell character varying
);
alter table munsell_update owner to marta;
copy munsell_update(nctn,munsell) from '/var/www/marta/workfile/csv/dati/munsell.csv' delimiter ',' csv header;
update munsell_update set scheda = n.scheda from nctn_scheda n where munsell_update.nctn = n.nctn;
commit;
