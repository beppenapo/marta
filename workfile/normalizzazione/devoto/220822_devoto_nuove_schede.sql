BEGIN;
update scheda set id_temp = null;
drop table IF EXISTS work.devoto_schede;

create table work.devoto_schede(
  id_temp integer primary key,
  cmpd date,
  nctn integer,
  inv integer,
  suf CHARACTER VARYING,
  titolo CHARACTER VARYING,
  ogtd INTEGER,
  ogr INTEGER,
  ogto INTEGER,
  piano INTEGER,
  sala INTEGER,
  scaffale INTEGER,
  colonna INTEGER,
  plateau INTEGER,
  comune INTEGER,
  dtzgi INTEGER,
  dtzgf INTEGER,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  dtm INTEGER,
  materia INTEGER,
  tecnica CHARACTER VARYING,
  misd NUMERIC(5,2),
  misg NUMERIC(5,2),
  misv CHARACTER VARYING,
  desa CHARACTER VARYING,
  desm CHARACTER VARYING,
  desv CHARACTER VARYING,
  desl CHARACTER VARYING,
  desg CHARACTER VARYING,
  desf CHARACTER VARYING,
  dest CHARACTER VARYING,
  desn CHARACTER VARYING,
  desr CHARACTER VARYING,
  zec CHARACTER VARYING,
  stcc INTEGER,
  stcl integer,
  cdgg INTEGER,
  adsp INTEGER,
  adsm INTEGER,
  id_def integer REFERENCES scheda(id) on DELETE CASCADE,
  oss text
);
alter table work.devoto_schede owner to marta;

--path locale
-- copy work.devoto_schede from '/var/www/html/marta/workfile/normalizzazione/devoto/devoto_nuove_schede.csv' delimiter ',' csv header;
--path remoto
copy work.devoto_schede from '/var/www/marta/workfile/normalizzazione/devoto/devoto_nuove_schede.csv' delimiter ',' csv header;

update work.devoto_schede set misd = replace(misd,',','.');
update work.devoto_schede set misd = replace(misd,' ','');
update work.devoto_schede set misg = replace(misg,',','.');
update work.devoto_schede set misg = replace(misg,' ','');
alter table work.devoto_schede alter column misd type numeric(5,2) using misd::numeric;
alter table work.devoto_schede alter column misg type numeric(5,2) using misg::numeric;

update nctn set libero = false where nctn in (select nctn from work.devoto_schede);
insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id_temp, titolo, 2, 2, 28, cmpd, 45 from work.devoto_schede;
update work.devoto_schede t set id_def = s.id from scheda s where t.id_temp = s.id_temp and s.id_temp is not null;
insert into stato_scheda(scheda) select id_def from work.devoto_schede;
insert into nctn_scheda(nctn, scheda) select nctn, id_def from work.devoto_schede;
insert into inventario(inventario,suffisso,scheda) select inv,suf,id_def from work.devoto_schede where inv is not null;
INSERT INTO ad(scheda, adsp, adsm) select id_def, adsp, adsm from work.devoto_schede;
INSERT INTO co select id_def, stcc, stcl from work.devoto_schede;

INSERT INTO da(scheda, desa, desm, desv, desl, desg, desf, dest, desn, desr, zec) select id_def, desa, desm, desv, desl, desg, desf, dest, desn, desr, zec from work.devoto_schede;

INSERT INTO dt(scheda, dtsi, dtsf, dtzgi, dtzgf) select id_def, dtsi, dtsf, dtzgi, dtzgf from work.devoto_schede;

INSERT INTO lc(scheda, piano, sala, contenitore, colonna, ripiano) select id_def, piano, sala, scaffale, colonna, plateau from work.devoto_schede;
INSERT INTO tu(scheda,cdgg) select id_def, cdgg from work.devoto_schede;
INSERT INTO dtm select id_def, dtm from work.devoto_schede;
INSERT INTO mtc select id_def, materia, tecnica from work.devoto_schede;
INSERT INTO geolocalizzazione(scheda,comune) select id_def, comune from work.devoto_schede;
INSERT INTO og_nu(scheda, ogtd, ogr, ogto) select id_def, ogtd, ogr, ogto from work.devoto_schede;
INSERT INTO mis(scheda, misd, misg, misv) select id_def, misd, misg, misv from work.devoto_schede;
INSERT INTO an(scheda, oss) select id_def, oss from work.devoto_schede where oss is not null;
COMMIT;
