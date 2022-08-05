BEGIN;
--pulisco i campi con gli id temporanei dalle varie tabelle
update scheda set id_temp = null;

drop table IF EXISTS work.devoto_biblio_scheda;
drop table IF EXISTS work.devoto_schede;
-- creo le tabelle
create table work.devoto_biblio_scheda(
  scheda_temp integer,
  scheda integer references scheda(id) on delete CASCADE,
  biblio integer references bibliografia(id) on delete CASCADE,
  pagine character varying,
  livello integer default 4
);

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
  desa CHARACTER VARYING,
  desm CHARACTER VARYING,
  desv CHARACTER VARYING,
  desl CHARACTER VARYING,
  desg CHARACTER VARYING,
  desu CHARACTER VARYING,
  desf CHARACTER VARYING,
  dest CHARACTER VARYING,
  deso CHARACTER VARYING,
  desn CHARACTER VARYING,
  desr CHARACTER VARYING,
  desd CHARACTER VARYING,
  zec CHARACTER VARYING,
  stcc INTEGER,
  stcl integer,
  cdgg INTEGER,
  adsp INTEGER,
  adsm INTEGER
);

--modifico i proprietari delle tabelle
alter table work.devoto_biblio_scheda owner to marta;
alter table work.devoto_schede owner to marta;

--copio i dati dai file csv
copy work.devoto_biblio_scheda(scheda_temp, biblio, pagine) from '/var/www/marta/workfile/normalizzazione/devoto/p1-16_schede_biblio.csv' delimiter ',' csv header;
copy work.devoto_schede from '/var/www/marta/workfile/normalizzazione/devoto/p1-16_schede.csv' delimiter ',' csv header;

--aggiungo il campo id_def alla tabella delle schede
ALTER TABLE work.devoto_schede add column id_def integer;

--inserisco i dati nelle tabelle principali e copio l'id definitivo

--scheda
update nctn set libero = false where nctn in (select nctn from work.devoto_schede);
insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id_temp, titolo, 2, 2, 35, cmpd, 39 from work.devoto_schede;
update work.devoto_schede t set id_def = s.id from scheda s where t.id_temp = s.id_temp and s.id_temp is not null;
insert into stato_scheda(scheda) select id_def from work.devoto_schede;
insert into nctn_scheda(nctn, scheda) select nctn, id_def from work.devoto_schede;
insert into inventario(inventario,suffisso,scheda) select inv,suf,id_def from work.devoto_schede where inv is not null;
INSERT INTO ad(scheda, adsp, adsm) select id_def, adsp, adsm from work.devoto_schede;
INSERT INTO co select id_def, stcc, stcl from work.devoto_schede;
INSERT INTO da(scheda, desa, desm, desv, desl, desg, desu, desf, dest, deso, desn, desr, desd, zec) select id_def, desa, desm, desv, desl, desg, desu, desf, dest, deso, desn, desr, desd, zec from work.devoto_schede;
INSERT INTO dt(scheda, dtsi, dtsf, dtzgi, dtzgf) select id_def, dtsi, dtsf, dtzgi, dtzgf from work.devoto_schede;
INSERT INTO lc(scheda, piano, sala, contenitore, colonna, ripiano) select id_def, piano, sala, scaffale, colonna, plateau from work.devoto_schede;
INSERT INTO tu(scheda,cdgg) select id_def, cdgg from work.devoto_schede;
INSERT INTO dtm select id_def, dtm from work.devoto_schede;
INSERT INTO mtc select id_def, materia, tecnica from work.devoto_schede;
INSERT INTO geolocalizzazione(scheda,comune) select id_def, comune from work.devoto_schede;
INSERT INTO og_nu(scheda, ogtd, ogr, ogto) select id_def, ogtd, ogr, ogto from work.devoto_schede;


--biblio_scheda
update work.devoto_biblio_scheda l set scheda = b.id_def from work.devoto_schede b where l.scheda_temp = b.id_temp;
INSERT INTO biblio_scheda(scheda, biblio, pagine, livello) SELECT scheda, biblio, pagine, livello FROM work.devoto_biblio_scheda;

COMMIT;
