BEGIN;
drop table IF EXISTS work.stuani_schede;
drop table IF EXISTS work.stuani_mtc;
drop table IF EXISTS work.stuani_raccolte;
drop table IF EXISTS work.stuani_contributi;
drop table IF EXISTS work.stuani_biblio_scheda;

update bibliografia set id_temp = null;
update contributo set id_temp = null;
update scheda set id_temp = null;

CREATE TABLE work.stuani_schede(
  id_temp INTEGER PRIMARY KEY,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  cmpd date,
  nctn integer,
  inv INTEGER,
  suff CHARACTER VARYING,
  titolo CHARACTER VARYING,
  l3 INTEGER,
  l4 INTEGER,
  piano INTEGER,
  sala INTEGER,
  vetrina CHARACTER VARYING,
  comune INTEGER,
  via CHARACTER VARYING,
  dtzgi INTEGER,
  dtzgf INTEGER,
  dtzs INTEGER,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  dtm INTEGER,
  misa numeric(5,2),
  misl numeric(5,2),
  misd numeric(5,2),
  misn numeric(5,2),
  miss numeric(5,2),
  misv numeric(5,2),
  deso CHARACTER VARYING,
  stcc INTEGER,
  adsp INTEGER,
  adsm INTEGER,
  oss CHARACTER VARYING
);

CREATE TABLE work.stuani_mtc(
  id_temp integer,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  materia integer,
  tecnica CHARACTER VARYING,
  primary key (id_temp,materia)
);

CREATE TABLE work.stuani_raccolte(
  id_temp integer primary key,
  id integer references bibliografia(id) on delete CASCADE,
  tipo integer,
  titolo character varying,
  autore character varying,
  editore character varying,
  anno character varying,
  luogo character varying,
  curatore character varying
);

CREATE TABLE work.stuani_contributi(
  id_temp integer primary key,
  id integer references contributo(id) on delete CASCADE,
  raccolta_temp integer,
  raccolta integer references bibliografia(id) on delete cascade,
  titolo CHARACTER VARYING,
  autore CHARACTER VARYING,
  altri_autori CHARACTER VARYING
);

create table work.stuani_biblio_scheda(
  scheda_temp integer,
  scheda integer references scheda(id) on delete CASCADE,
  biblio_temp integer,
  biblio integer references bibliografia(id) on delete CASCADE,
  contributo_temp integer,
  contributo integer references contributo(id) on delete CASCADE,
  pagine character varying,
  figura character varying,
  livello integer default 4
);

alter table work.stuani_schede owner to marta;
alter table work.stuani_mtc owner to marta;
alter table work.stuani_raccolte owner to marta;
alter table work.stuani_contributi owner to marta;
alter table work.stuani_biblio_scheda owner to marta;

-- copy work.stuani_schede from '/var/www/html/marta/workfile/normalizzazione/stuani/stuani_scheda.csv' delimiter ',' csv header;
-- copy work.stuani_mtc from '/var/www/html/marta/workfile/normalizzazione/stuani/stuani_mtc.csv' delimiter ',' csv header;
-- copy work.stuani_raccolte from '/var/www/html/marta/workfile/normalizzazione/stuani/stuani_raccolte.csv' delimiter ',' csv header;
-- copy work.stuani_contributi from '/var/www/html/marta/workfile/normalizzazione/stuani/stuani_contributi.csv' delimiter ',' csv header;
-- copy work.stuani_biblio_scheda from '/var/www/html/marta/workfile/normalizzazione/stuani/stuani_biblio_scheda.csv' delimiter ',' csv header;

-- path server
copy work.stuani_schede from '/var/www/marta/workfile/normalizzazione/stuani/stuani_scheda.csv' delimiter ',' csv header;
copy work.stuani_mtc from '/var/www/marta/workfile/normalizzazione/stuani/stuani_mtc.csv' delimiter ',' csv header;
copy work.stuani_raccolte from '/var/www/marta/workfile/normalizzazione/stuani/stuani_raccolte.csv' delimiter ',' csv header;
copy work.stuani_contributi from '/var/www/marta/workfile/normalizzazione/stuani/stuani_contributi.csv' delimiter ',' csv header;
copy work.stuani_biblio_scheda from '/var/www/marta/workfile/normalizzazione/stuani/stuani_biblio_scheda.csv' delimiter ',' csv header;

update nctn set libero = false where nctn in (select nctn from work.stuani_schede);
insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id_temp, titolo, 1, 2, 31, cmpd, 45 from work.stuani_schede;
update work.stuani_schede t set scheda = s.id from scheda s where t.id_temp = s.id_temp and s.id_temp is not null;
insert into stato_scheda(scheda) select scheda from work.stuani_schede;
insert into nctn_scheda(nctn, scheda) select nctn, scheda from work.stuani_schede;
insert into inventario(inventario,suffisso,scheda) select inv,suff,scheda from work.stuani_schede where inv is not null;
INSERT INTO ad(scheda, adsp, adsm) select scheda, adsp, adsm from work.stuani_schede;
INSERT INTO co select scheda, stcc, 3 from work.stuani_schede;
INSERT INTO da(scheda, deso) select scheda, deso from work.stuani_schede;
INSERT INTO dt select scheda, dtzs, dtsi, dtsf, dtzgi, dtzgf from work.stuani_schede;
INSERT INTO lc(scheda, piano, sala, contenitore) select scheda, piano, sala, vetrina from work.stuani_schede;
INSERT INTO mis(scheda,misa, misl, misd, misn, miss, misv) select scheda, misa, misl, misd, misn, miss, misv from work.stuani_schede;
INSERT INTO tu(scheda,cdgg) select scheda, 1 from work.stuani_schede;
INSERT INTO dtm select scheda, dtm from work.stuani_schede;
INSERT INTO geolocalizzazione(scheda,comune,via) select scheda, comune, via from work.stuani_schede;
INSERT INTO og_ra(scheda, l3, l4) select scheda, l3, l4 from work.stuani_schede;
INSERT INTO an SELECT scheda, oss from work.stuani_schede where oss is not null;

update work.stuani_mtc m set scheda = s.scheda from work.stuani_schede s where m.id_temp = s.id_temp;
INSERT INTO mtc select scheda, materia, tecnica from work.stuani_mtc;

insert into bibliografia(id_temp, tipo, titolo, autore, editore, anno, luogo, curatore)
select id_temp, tipo, titolo, autore, editore, anno, luogo, curatore from work.stuani_raccolte;
update work.stuani_raccolte s set id = b.id from bibliografia b where s.id_temp = b.id_temp;

update work.stuani_contributi s set raccolta = b.id from bibliografia b where s.raccolta_temp = b.id_temp and s.raccolta_temp is not null and s.raccolta is null;
insert into contributo(id_temp, raccolta, titolo, autore, altri_autori)
select id_temp, raccolta, titolo, autore, altri_autori from work.stuani_contributi;
update work.stuani_contributi s set id = c.id from contributo c where s.id_temp = c.id_temp;

update work.stuani_biblio_scheda w set scheda = s.id from scheda s where w.scheda_temp = s.id_temp;
update work.stuani_biblio_scheda w set biblio = b.id from bibliografia b where w.biblio_temp = b.id_temp and w.biblio_temp is not null and w.biblio is null;
update work.stuani_biblio_scheda w set contributo = c.id from contributo c where w.contributo_temp = c.id_temp and w.contributo_temp is not null and w.contributo is null;
insert into biblio_scheda(scheda, biblio, contributo, pagine, figure, livello)
select scheda, biblio, contributo, pagine, figura, livello from work.stuani_biblio_scheda;

COMMIT;
