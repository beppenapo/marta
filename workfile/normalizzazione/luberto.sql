BEGIN;
--pulisco i campi con gli id temporanei dalle varie tabelle
update bibliografia set id_temp = null;
update contributo set id_temp = null;
update scheda set id_temp = null;

-- creo le tabelle
create table work.luberto_bibliografia as select id, id_def as biblio, tipo, titolo, autore, editore, anno, luogo, curatore from work.biblio_tiberi limit 0;
create table work.luberto_contributi as select id, raccolta as biblio_temp, id_def as contributo, raccolta_def as biblio, titolo, autore, altri_autori, pag from work.contributi_tiberi limit 0;
create table work.luberto_biblio_scheda(
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
create table work.luberto_schede(
  id_temp integer primary key,
  id_def integer,
  nctn integer,
  inv integer,
  suf CHARACTER VARYING,
  l3 INTEGER,
  l4 INTEGER,
  piano INTEGER,
  sala INTEGER,
  vetrina CHARACTER VARYING,
  comune INTEGER,
  via CHARACTER VARYING,
  geonote CHARACTER VARYING,
  scan CHARACTER VARYING,
  dsca CHARACTER VARYING,
  dscd CHARACTER VARYING,
  dtzgi INTEGER,
  dtzgf INTEGER,
  dtzs INTEGER,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  dtm INTEGER,
  materia INTEGER,
  tecnica CHARACTER VARYING,
  misa numeric(5,2),
  misl numeric(5,2),
  misd numeric(5,2),
  deso CHARACTER VARYING,
  stcc INTEGER,
  stcl integer,
  adsp INTEGER,
  adsm INTEGER,
  cmpd date,
  cmpn INTEGER,
  fur INTEGER,
  cdgg INTEGER
);

--modifico i proprietari delle tabelle
alter table work.luberto_bibliografia owner to marta;
alter table work.luberto_contributi owner to marta;
alter table work.luberto_biblio_scheda owner to marta;
alter table work.luberto_schede owner to marta;

--aggiungo le varie chiavi
alter table work.luberto_bibliografia add primary key (id);
alter table work.luberto_bibliografia add unique (biblio);
alter table work.luberto_bibliografia ADD CONSTRAINT biblio_fki foreign key (biblio) REFERENCES bibliografia(id) on delete cascade;
alter table work.luberto_contributi add primary key (id);
alter table work.luberto_contributi add unique (contributo);

--copio i dati dai file csv
copy work.luberto_bibliografia from '/var/www/marta/workfile/normalizzazione/luberto_bibliografia.csv' delimiter ',' csv header;
copy work.luberto_contributi from '/var/www/marta/workfile/normalizzazione/luberto_contributi.csv' delimiter ',' csv header;
copy work.luberto_biblio_scheda(scheda_temp, biblio_temp,contributo_temp, pagine, figura) from '/var/www/marta/workfile/normalizzazione/luberto_scheda_biblio.csv' delim
iter ',' csv header;
copy work.luberto_schede from '/var/www/marta/workfile/normalizzazione/luberto_schede.csv' delimiter ',' csv header;

--inserisco i dati nelle tabelle principali e copio l'id definitivo
INSERT INTO bibliografia(id_temp,tipo, titolo, autore, editore, anno, luogo, curatore) select id, tipo, titolo, autore, editore, anno, luogo, curatore from work.luberto_bibliografia where biblio is null order by id asc;
update work.luberto_bibliografia l set biblio = b.id from bibliografia b where l.id = b.id_temp;

update work.luberto_contributi c set biblio = b.biblio from work.luberto_bibliografia b where c.biblio_temp = b.id;
insert into contributo(id_temp, raccolta, titolo, autore, altri_autori, pag)
  select id, biblio, titolo, autore, altri_autori, pag from work.luberto_contributi where contributo is null order by titolo asc;
update work.luberto_contributi l set contributo = c.id from contributo c where l.id = c.id_temp and c.id_temp is not null;

--scheda

--biblio_scheda
update work.luberto_biblio_scheda l set biblio = b.id from bibliografia b where l.biblio_temp = b.id_temp and l.contributo_temp is null;



COMMIT;
