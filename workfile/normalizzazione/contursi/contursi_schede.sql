BEGIN;
DROP TABLE IF EXISTS work.contursi_schede;
drop table IF EXISTS work.contursi_dtm;
update scheda set id_temp = null;

CREATE TABLE work.contursi_schede(
  id INTEGER PRIMARY KEY,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  cmpd date,
  cmpn integer references utenti(id) on delete CASCADE default 30,
  nctn integer,
  inventario integer,
  suffisso CHARACTER VARYING,
  titolo CHARACTER VARYING,
  l3 INTEGER references liste.ra_cls_l3(id) ON DELETE CASCADE,
  l4 INTEGER references liste.ra_cls_l4(id) ON DELETE CASCADE,
  sala integer,
  scaffale CHARACTER VARYING,
  cassetta CHARACTER VARYING,
  comune INTEGER REFERENCES comuni(id) ON DELETE CASCADE,
  via CHARACTER VARYING,
  geonote CHARACTER VARYING,
  dtzgi INTEGER REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzgf integer REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzs integer REFERENCES liste.dtzs(id) ON DELETE CASCADE,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  materia integer,
  tecnica CHARACTER VARYING,
  misa numeric(5,2),
  misl numeric(5,2),
  misp numeric(5,2),
  deso CHARACTER VARYING,
  stcc integer REFERENCES liste.stcc(id) ON DELETE CASCADE,
  adsp integer REFERENCES liste.adsp(id) ON DELETE CASCADE default 1,
  adsm integer REFERENCES liste.adsm(id) ON DELETE CASCADE default 1,
  oss CHARACTER VARYING,
  stima CHARACTER VARYING
);
CREATE TABLE work.contursi_dtm(
  id integer,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  dtm integer REFERENCES liste.dtm(id) ON DELETE CASCADE,
  primary key (id,dtm)
);
alter table work.contursi_schede owner to marta;
alter table work.contursi_dtm owner to marta;

copy work.contursi_schede from '/var/www/marta/workfile/normalizzazione/contursi/contursi_schede.csv' delimiter ',' csv header;
copy work.contursi_dtm from '/var/www/marta/workfile/normalizzazione/contursi/contursi_dtm.csv' delimiter ',' csv header;
COMMIT;
