BEGIN;
drop TABLE IF EXISTS work.biblio_tiberi CASCADE;
drop table IF EXISTS work.contributi_tiberi;
create table work.biblio_tiberi(
  id integer primary key,
  tipo integer,
  titolo CHARACTER VARYING,
  autore CHARACTER VARYING,
  altri_autori CHARACTER VARYING,
  editore CHARACTER VARYING,
  anno SMALLINT,
  luogo CHARACTER VARYING,
  isbn CHARACTER VARYING,
  url CHARACTER VARYING,
  curatore CHARACTER VARYING
);
CREATE TABLE work.contributi_tiberi(
  id SMALLINT primary key,
  raccolta SMALLINT REFERENCES work.biblio_tiberi(id) on delete cascade,
  titolo CHARACTER VARYING,
  autore CHARACTER VARYING,
  altri_autori CHARACTER VARYING,
  pag CHARACTER VARYING
);
CREATE TABLE work.biblio_scheda_tiberi(
  scheda SMALLINT,
  biblio SMALLINT,
  contributo SMALLINT,
  pagine character VARYING,
  figure CHARACTER VARYING,
  livello SMALLINT
  -- ,primary key (scheda,biblio,contributo)
);
COPY work.biblio_tiberi FROM '/var/www/marta/workfile/csv/tiberi/bibliografia_tiberi.csv' DELIMITER ',' CSV HEADER;
COPY work.contributi_tiberi FROM '/var/www/marta/workfile/csv/tiberi/contributi_tiberi.csv' DELIMITER ',' CSV HEADER;
COPY work.biblio_scheda_tiberi FROM '/var/www/marta/workfile/csv/tiberi/biblio_scheda_tiberi.csv' DELIMITER ',' CSV HEADER;

--MODIFICHE TABELLA BILIOGRAFIA
alter table work.biblio_tiberi add column id_def SMALLINT;
alter table bibliografia add column id_temp SMALLINT;
insert into bibliografia(id_temp,tipo,titolo,autore,altri_autori,editore,anno,luogo,isbn,url,curatore) select id,tipo,titolo,autore,altri_autori,editore,anno,luogo,isbn,url,curatore from work.biblio_tiberi order by titolo asc;
update work.biblio_tiberi set id_def = bibliografia.id from bibliografia where bibliografia.id_temp = biblio_tiberi.id;


--MODIFICHE TABELLA CONTRIBUTI
alter table work.contributi_tiberi add column raccolta_def SMALLINT;
alter table work.contributi_tiberi add column id_def SMALLINT;
UPDATE work.contributi_tiberi set raccolta_def = b.id_def from work.biblio_tiberi b where contributi_tiberi.raccolta = b.id;
alter table contributo add column id_temp SMALLINT;
alter table contributo drop CONSTRAINT contributo_raccolta_titolo_key;
alter table contributo add constraint contributo_raccolta_titolo_key UNIQUE (raccolta, titolo, autore);
insert into contributo(id_temp,raccolta,titolo,autore,altri_autori,pag) select id,raccolta_def, titolo,autore,altri_autori,pag from work.contributi_tiberi order by titolo asc;
update work.contributi_tiberi set id_def = contributo.id from contributo where contributi_tiberi.id = contributo.id_temp;

--MODIFICHE TABELLA BIBLIO_SCHEDA
alter table biblio_scheda drop CONSTRAINT biblio_scheda_pkey;
alter table biblio_scheda add column id serial primary key;

--MODIFICHE TABELLA SCHEDA
alter table scheda add column id_temp SMALLINT;
COMMIT;
