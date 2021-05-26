BEGIN;
--sezione CO
create table liste.stcl(id serial primary key, value CHARACTER VARYING unique not null);
insert into liste.stcl(value) values ('totale'),('buona'),('discreta'),('ridotta'),('assente');
alter TABLE co add column stcl integer REFERENCES liste.stcl(id);

--sezione RS non inserita

--sezione TU
alter table liste.condizione_giuridica rename to cdgg;
create table liste.acqt(id serial primary key, value CHARACTER VARYING unique not null);
create table liste.nvct(id serial primary key, value CHARACTER VARYING unique not null);
INSERT INTO liste.acqt(value) VALUES ('acquisto'), ('alienazione'), ('aggiudicazione (a seguito di atto giudiziario)'), ('assegnazione'), ('compravendita'), ('confisca'), ('deposito'), ('donazione'), ('esercizio di diritto di prelazione'), ('permuta'), ('prelazione'), ('restituzione postbellica'), ('ritrovamento fortuito'), ('sequestro'), ('scavo'), ('soppressione'), ('successione');
INSERT INTO liste.nvct(value) VALUES ('Notificazione (L. 364/1909)'), ('DM (L. 1089/1939, art.3)'), ('DM (L. 1089/1939, art.5)'), ('DLgs 490/1999, art. 6, comma 1'), ('DLgs 490/1999, art. 6, comma 2'), ('Revoca notificazione (L. 364/1909)'), ('Revoca DM (L. 1089/1939, art. 3)'), ('Revoca DM (L. 1089/1939, art. 5)'), ('Revoca DLgs 490/1999, art. 6, comma 1'), ('Revoca DLgs 490/1999, art. 6, comma 2'), ('Rinnovo Notificazione (L. 364/1909)'), ('Rinnovo DM (L. 1089/1939, art. 3)'), ('Rinnovo DM (L. 1089/1939, art. 5)'), ('DLgs 42/2004, art. 13, comma 1');
alter table tu
  ADD COLUMN acqt integer references liste.acqt(id),
  ADD COLUMN acqn CHARACTER VARYING,
  ADD COLUMN acqd CHARACTER VARYING,
  ADD COLUMN acql CHARACTER VARYING,
  ADD COLUMN nvct integer references liste.nvct(id),
  ADD COLUMN nvce CHARACTER VARYING;

--sezione DO
create table liste.do(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
insert into liste.do(value) VALUES ('FTA - Documentazione fotografica'), ('DRA - Documentazione grafica'), ('VDC - Documentazione video-cinematografica'), ('FNT - Fonti e documenti'), ('ADM - Altra documentazione multimediale'), ('BIB - Bibliografia'),('MST - Mostre');
create TABLE liste.do_genere(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
INSERT INTO liste.do_genere(value) values ('documentazione allegata'), ('documentazione esistente'), ('documentazione non disponibile');
create table doc(
  id serial PRIMARY KEY,
  scheda integer REFERENCES scheda(id),
  tipo INTEGER NOT NULL REFERENCES liste.do(id),
  percorso character varying,
  didascalia CHARACTER VARYING
);

drop table liste.ftap cascade;
create TABLE liste.ftap(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
INSERT INTO liste.ftap(value) values ('documentazione non disponibile'), ('diapositiva b/n'), ('diapositiva colore'), ('fermo-immagine'), ('fotografia a raggi infrarossi'), ('fotografia aerea'), ('fotografia b/n'), ('fotografia colore'), ('fotografia digitale'), ('fotografia digitale ortorettificata'), ('fotografia digitale - riproduzione di fotografia da bibliografia'), ('fotografia digitale - riproduzione di fotografia da fonte archivistica'), ('fotografia digitale - riproduzione di disegno tecnico'), ('negativo a raggi infrarossi'), ('negativo b/n'), ('negativo colore'), ('positivo b/n'), ('positivo colore'), ('radiografia'), ('stereogramma'), ('NR (recupero pregresso)'), ('NR (recupero VIR - Vincoli In Rete)');
create table fta(
  scheda integer primary key references scheda(id),
  ftax integer not null REFERENCES liste.do_genere(id),
  ftap integer not null REFERENCES liste.ftap(id),
  ftan CHARACTER VARYING not NULL,
  ftat text
);

create TABLE liste.drat(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
INSERT INTO liste.drat(value) values ('CAD bidimensionale'), ('CAD tridimensionale'), ('carta topografica'), ('disegno'), ('disegno di progetto'), ('disegno esecutivo'), ('disegno tecnico'), ('eidotipo'), ('elaborato grafico di progetto'), ('grafico'), ('planimetria'), ('planimetria catastale'), ('rilievo'), ('rilievo con ipotesi ricostruttiva'), ('rilievo stratigrafico'), ('riproduzione di carata topografica'), ('sezione'), ('tavola composita');
create table dra(
  scheda integer primary key references scheda(id),
  drax integer not null REFERENCES liste.do_genere(id),
  drat integer not null REFERENCES liste.drat(id),
  dran CHARACTER VARYING not NULL,
  drao text
);

create TABLE liste.vdcp(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
INSERT INTO liste.vdcp(value) values ('CD Rom'), ('DVD'), ('file digitale'), ('file digitale AVI'), ('file digitale MPG'), ('film 35 mm'), ('film 16 mm'), ('film 8 mm'), ('film super 8 mm'), ('video 1 pollice'), ('video Betacam'), ('video BVU'), ('video DV'), ('video Hi8'), ('video Mini DV'), ('video super VHS'), ('video U-MATIC'), ('video VHS');
create table vdc(
  scheda integer primary key references scheda(id),
  vdcx integer not null REFERENCES liste.do_genere(id),
  vdcp integer not null REFERENCES liste.vdcp(id),
  vdcn CHARACTER VARYING not NULL,
  vdct text
);

create TABLE liste.fntp(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
INSERT INTO liste.fntp(value) values ('atto notarile'), ('libro mastro'), ('perizia tecnica'), ('relazione tecnico scientifica'), ('scheda obsoleta'), ('scheda storica');
create table fnt(
  scheda integer primary key references scheda(id),
  fntp INTEGER NOT NULL REFERENCES liste.fntp(id),
  fntd CHARACTER VARYING NOT NULL,
  fntn CHARACTER VARYING NOT NULL,
  fnts CHARACTER VARYING NOT NULL,
  fnti CHARACTER VARYING NOT NULL,
  note text
);

create TABLE liste.admp(id serial PRIMARY KEY, value CHARACTER VARYING not null unique);
INSERT INTO liste.admp(value) values ('file in formato .doc'),('file in formato .odt'), ('file in formato .ppt'), ('file in formato .odp'), ('file in formato .xls'), ('file in formato .ods'), ('file in formato .pdf'), ('modello 3d (.obj, .ply, ...)'), ('realtà virtuale (VRLM, ...)'), ('file musicale (.midi, .mp3, ...)'), ('altro formato');
create table adm(
  scheda integer primary key references scheda(id),
  admx integer not null REFERENCES liste.do_genere(id),
  admp INTEGER NOT NULL REFERENCES liste.admp(id),
  admn CHARACTER VARYING NOT NULL,
  admt text
);

update liste.biblio_livello set value = 'bibliografia specifica' where id = 1;
update liste.biblio_livello set value = 'bibliografia di confronto' where id = 3;
update liste.biblio_livello set value = 'bibliografia di corredo' where id = 2;
create table bib(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id),
  bibx INTEGER NOT NULL REFERENCES liste.biblio_livello(id),
  bibh serial not null unique,
  bib_rif INTEGER NOT NULL REFERENCES bibliografia(id),
  bibn CHARACTER VARYING,
  bibi CHARACTER VARYING
);

COMMIT;
