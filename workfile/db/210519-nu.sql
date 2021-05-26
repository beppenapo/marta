BEGIN;
-- ALTER TABLE scheda ADD CONSTRAINT scheda_tsk_fki FOREIGN KEY (tsk) REFERENCES liste.tsk(id);
DROP TABLE cm;
DROP TABLE cd;

CREATE TABLE rve(
  rver integer REFERENCES nctn(id),
  rvel numeric(4,0),
  PRIMARY KEY(rver,rvel)
);
INSERT INTO liste.rser (relazione, tipo) VALUES ('riferimento alla matrice', 'non definito');
CREATE TABLE rse(
  rsec integer REFERENCES nctn(id),
  rser integer not null references liste.rser(id),
  rset integer not null references liste.tsk(id),
  PRIMARY KEY(rsec,rser,rset)
);
CREATE TABLE roz(
  nctn integer REFERENCES nctn(id),
  roz CHARACTER VARYING,
  PRIMARY KEY (nctn,roz)
);
ALTER TABLE nctn
  ADD COLUMN ncts CHARACTER VARYING,
  ADD COLUMN nctr NUMERIC(2,0) NOT NULL DEFAULT 16;
comment on column nctn.ncts is 'suffisso numero catalogo';

ALTER TABLE ente DROP COLUMN nctr;

CREATE TABLE nctn_ente (
  nctn INTEGER REFERENCES nctn(id) on delete cascade,
  ente INTEGER REFERENCES ente(id) on delete cascade,
  PRIMARY KEY (nctn,ente)
);

CREATE TABLE nctn_scheda (
  nctn INTEGER REFERENCES nctn(id) on delete cascade,
  scheda INTEGER REFERENCES scheda(id) on delete cascade,
  PRIMARY KEY (nctn,scheda)
);

CREATE TABLE inventario(
  id serial primary key,
  inventario INTEGER not NULL,
  prefisso CHARACTER VARYING,
  suffisso CHARACTER VARYING,
  unique (inventario, prefisso, suffisso)
);

CREATE TABLE inventario_scheda (
  inventario INTEGER REFERENCES inventario(id) on delete cascade,
  scheda INTEGER REFERENCES scheda(id) on delete cascade,
  PRIMARY KEY (inventario,scheda)
);

CREATE TABLE stato_scheda(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  chiusa BOOLEAN NOT NULL DEFAULT false,
  verificata BOOLEAN NOT NULL DEFAULT false,
  inviata BOOLEAN NOT NULL DEFAULT false,
  accettata BOOLEAN NOT NULL DEFAULT false
);
comment on column stato_scheda.chiusa is 'lo schedatore chiude la scheda quando è completa';
comment on column stato_scheda.verificata is 'la scheda chiusa viene sottoposta a verifica da parte di un responsabile';
comment on column stato_scheda.inviata is 'la scheda verificata viene inviata a ICCD';
comment on column stato_scheda.accettata is 'la scheda è stata accettata da ICCD';

ALTER TABLE scheda
DROP CONSTRAINT scheda_unique,
ADD COLUMN lir INTEGER NOT NULL REFERENCES liste.lir(id) DEFAULT 2,
ADD COLUMN cmpn INTEGER NOT NULL REFERENCES utenti(id),
ADD COLUMN cmpd DATE NOT NULL DEFAULT now(),
ADD COLUMN fur CHARACTER VARYING,
ADD CONSTRAINT scheda_unique UNIQUE (titolo,cmpn);

CREATE TABLE an(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id),
  oss TEXT NOT NULL
);
comment on table an IS 'tabella osservazioni finali';

CREATE TABLE liste.ogr(id serial PRIMARY KEY, value CHARACTER VARYING unique NOT null);
CREATE TABLE liste.ogto(id serial PRIMARY KEY, value CHARACTER VARYING unique NOT null);
CREATE TABLE liste.ogts(id serial PRIMARY KEY, value CHARACTER VARYING unique NOT null);
CREATE TABLE liste.ogtr(id serial PRIMARY KEY, value CHARACTER VARYING unique NOT null);
INSERT INTO liste.ogtr(value) VALUES ('Greca'), ('Celtica'), ('Romana repubblicana'), ('Romana imperiale'), ('Romana provinciale'), ('Bizantina'), ('Barbarica'), ('Barbarica/Longobardi'), ('Barbarica/Ostrogoti'), ('Barbarica/Svevi'), ('Barbarica/Vandali'), ('Barbarica/Visigoti'), ('Carolingia'), ('Islamica'), ('Islamica italiana'), ('Italiana/Repubblica di Genova'), ('Italiana/Stato Pontificio'), ('Italiana/Repubblica di Venezia'), ('Italiana/Ancona'), ('Europea/Austria'), ('Europea/Stati germanici/Baviera'), ('Europea/Stati italiani/Toscana'), ('Europea/Italia'), ('Asiatica/India'), ('Americana/Colombia'), ('Americana/Isole Olandesi');
INSERT INTO liste.ogts(value) VALUES ('argentatura'), ('bucatura'), ('deformazione'), ('doratura'), ('frazionamento'), ('serratura'), ('suberatura'), ('tosatura');
INSERT INTO liste.ogto(value) VALUES ('Antoniniano'), ('Asse'), ('asse librale'), ('Aureo'), ('Corona'), ('Denario'), ('Didracma'), ('Dinar'), ('dirhem'), ('Dracma'), ('Dupondio'), ('Fulus'), ('Guldengroschen'), ('iperpero'), ('miliarense'), ('oncia'), ('quadrante'), ('scudo'), ('semisse'), ('semuncia'), ('sesterzio'), ('siliqua'), ('solido'), ('tallero'), ('tarì'), ('vittoriato');

INSERT INTO liste.ogr(value) VALUES ('reale'), ('documentata'), ('NR (recupero pregresso)');
CREATE TABLE og_nu(
  og INTEGER PRIMARY KEY REFERENCES og(scheda) ON DELETE CASCADE,
  ogr INTEGER NOT NULL REFERENCES liste.ogr(id),
  ogtt CHARACTER VARYING,
  ogth CHARACTER VARYING,
  ogtl CHARACTER VARYING,
  ogto INTEGER REFERENCES liste.ogto(id),
  ogts INTEGER REFERENCES liste.ogts(id),
  ogtr INTEGER REFERENCES liste.ogtr(id)
);

ALTER TABLE liste.pvcs RENAME COLUMN value to pvcs;
ALTER TABLE liste.regioni RENAME TO pvcr;
ALTER TABLE liste.pvcr RENAME COLUMN stato to pvcs;
ALTER TABLE liste.pvcr RENAME COLUMN value to pvcr;
ALTER TABLE liste.province RENAME TO pvcp;
ALTER TABLE liste.pvcp RENAME COLUMN regione to pvcr;
ALTER TABLE liste.pvcp RENAME COLUMN value to pvcp;
ALTER TABLE liste.comuni RENAME TO pvcc;
ALTER TABLE liste.pvcc RENAME COLUMN provincia to pvcp;
ALTER TABLE liste.pvcc RENAME COLUMN value to pvcc;

CREATE TABLE liste.ldct(id serial primary key, value CHARACTER VARYING not null unique);
INSERT INTO liste.ldct(value) values ('abbazia'), ('area archeologica'), ('battistero'), ('biblioteca'), ('campanile'), ('canonica'), ('cappella'), ('casa privata'), ('casale'), ('caserma'), ('castello'), ('chiesa'), ('cimitero'), ('convento'), ('deposito'), ('giardino'), ('istituto di credito'), ('istituto museale'), ('istituto religioso'), ('istituto universitario'), ('monastero'), ('oratorio'), ('ospedale'), ('ospizio'), ('palazzo'), ('parco'), ('parco archeologico'), ('percorso viario'), ('piazza'), ('ponte'), ('scuola'), ('stazione'), ('teatro'), ('torre'), ('villa');

ALTER TABLE lc DROP CONSTRAINT lc_pvc_fkey;
ALTER TABLE lc RENAME COLUMN pvc TO pvcc;
ALTER TABLE lc RENAME COLUMN stanza TO sala;
ALTER TABLE lc ADD CONSTRAINT lc_pvcc_fkey FOREIGN KEY (pvcc) REFERENCES liste.pvcc(codice);
ALTER TABLE lc DROP COLUMN ldcs;

ALTER TABLE la
  ADD COLUMN prl CHARACTER VARYING,
  ADD COLUMN prct INTEGER REFERENCES liste.ldct(id),
  ADD COLUMN prcd CHARACTER VARYING,
  ADD COLUMN prcm CHARACTER VARYING;

DROP TABLE ub;
CREATE TABLE liste.stim(id serial primary key, value CHARACTER VARYING unique NOT NULL);
INSERT INTO liste.stim(value) VALUES ('acquisto'), ('alienazione'), ('assicurazione'), ('compilazione dell''inventario generale'), ('donazione'), ('importazione'), ('premio di rinvenimento'), ('restauro');
CREATE TABLE ub(
  scheda integer PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  invn CHARACTER VARYING,
  stis CHARACTER VARYING,
  stid numeric(4,0),
  stim integer REFERENCES liste.stim(id)
);
CREATE TABLE liste.gpl(id serial primary key, value CHARACTER VARYING unique NOT NULL);
CREATE TABLE liste.gpm(id serial primary key, value CHARACTER VARYING unique NOT NULL);
CREATE TABLE liste.gpt(id serial primary key, value CHARACTER VARYING unique NOT NULL);
CREATE TABLE liste.gpp(id serial primary key, value CHARACTER VARYING unique NOT NULL, epsg INTEGER UNIQUE NOT NULL);
INSERT INTO liste.gpl(value) values ('localizzazione fisica'),('luogo di fabbricazione'), ('luogo di reperimento');
INSERT INTO liste.gpm(value) values ('punto esatto'),('punto approssimato');
INSERT INTO liste.gpt(value) values ('rilievo tradizionale'), ('rilievo da cartografia con sopralluogo'), ('rilievo da cartografia senza sopralluogo'), ('rilievo da foto aerea con sopralluogo'), ('rilievo da foto aerea senza sopralluogo'), ('rilievo tramite GPS'), ('rilievo tramite punti d''appoggio fiduciari o trigonometrici'), ('stereofotogrammetria');
INSERT INTO liste.gpp(value, epsg) values ('WGS84', 4326), ('WGS84 UTM32', 32632), ('WGS84 UTM33', 32633), ('ETRS89', 4258), ('ETRS89 UTM32', 25832 ), ('ETRS89 UTM33', 25833), ('GAUSS-BOAGA Est', 3004), ('GAUSS-BOAGA Ovest', 3003);

CREATE TABLE gp(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id),
  gpl INTEGER NOT NULL REFERENCES liste.gpl(id),
  gpdpx NUMERIC(14,4) NOT NULL,
  gpdpy NUMERIC(14,4) NOT NULL,
  gpm INTEGER REFERENCES liste.gpm(id) NOT NULL,
  gpt INTEGER REFERENCES liste.gpt(id) NOT NULL,
  gpp INTEGER REFERENCES liste.gpp(id) NOT NULL,
  gpbb CHARACTER VARYING NOT NULL,
  gpbt NUMERIC(4,0) NOT NULL,
  UNIQUE(scheda,gpdpx,gpdpy)
);

DROP TABLE re;
CREATE TABLE dsc(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id),
  nucn CHARACTER VARYING,
  scan CHARACTER VARYING NOT NULL,
  dscf CHARACTER VARYING,
  dsca CHARACTER VARYING,
  dscd CHARACTER VARYING NOT NULL,
  dscn TEXT,
  UNIQUE(scheda,scan,dscd)
);
CREATE TABLE rcg(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id),
  nucn CHARACTER VARYING,
  rcga CHARACTER VARYING,
  rcgd CHARACTER VARYING NOT NULL,
  rcgz TEXT
);

CREATE TABLE liste.aint(id serial primary key, value CHARACTER VARYING unique NOT NULL);
INSERT INTO liste.aint(value) VALUES ('attività di manutenzione'), ('carotaggio'), ('demolizione edifici'), ('prospezione geoelettrica'), ('restauro architettonico'), ('restauro di manufatti');
CREATE TABLE ain (
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id),
  aint INTEGER REFERENCES liste.aint(id) NOT NULL,
  aind CHARACTER VARYING NOT NULL,
  ains TEXT,
  UNIQUE(scheda,aint,aind)
);

ALTER TABLE liste.dtzs_frazione_cronologia RENAME TO dtzs;
ALTER TABLE liste.dtm_motivazione_cronologia RENAME TO dtm;
drop TABLE dts;
drop TABLE dtz;
CREATE TABLE dt(
  scheda INTEGER PRIMARY KEY,
  dtzg INTEGER REFERENCES liste.dtzg(id) not null,
  dtzs INTEGER REFERENCES liste.dtzs(id) NOT NULL,
  dtsi NUMERIC(4,0) NOT NULL,
  dtsf NUMERIC(4,0) NOT NULL
);
CREATE TABLE liste.dts(
  id serial primary key,
  dtzg INTEGER REFERENCES liste.dtzg(id) not null,
  dtzs INTEGER REFERENCES liste.dtzs(id),
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0) NOT NULL,
  unique(dtzg,dtzs,dtsi,dtsf)
);
INSERT INTO liste.dts(dtzg,dtzs,dtsi,dtsf) values
(12, 15, 710, 690),
(13, null, 700, 601),
(13, 1, 700, 690),
(13, 3, 661, 640),
(13, 4, 700, 651),
(13, 5, 650, 601),
(13, 6, 700, 676),
(13, 7, 675, 651),
(13, 8, 650, 626),
(13, 9, 625, 601),
(13, 2, 610, 601),
(14, 15, 610, 590),
(14, 12, 641, 590),
(15, null, 600, 501),
(15, 1, 600, 590),
(15, 6, 600, 576),
(15, 7, 575, 551),
(15, 8, 550, 526),
(15, 9, 525, 501),
(15, 4, 600, 551),
(15, 5, 550, 501),
(15, 2, 510, 501),
(16, 15, 510, 490),
(17, null, 500, 401),
(17, 1, 500, 490),
(17, 3, 461, 440),
(17, 4, 500, 451),
(17, 5, 450, 401),
(17, 2, 410, 401),
(18, 13, 441, 360),
(18, 17, 411, 300),
(18, 15, 500, 300),
(17, 6, 500, 476),
(17, 7, 475, 451),
(17, 8, 450, 426),
(17, 9, 425, 401),
(18, 15, 410, 390),
(19, null, 400, 301),
(19, 3, 361, 340),
(19, 4, 400, 351),
(19, 5, 350, 301),
(20, 15, 310, 290),
(19, 6, 400, 376),
(19, 7, 375, 351),
(19, 8, 350, 326),
(19, 9, 325, 301),
(20, 15, 400, 201),
(20, 16, 310, 251),
(21, null, 300, 201),
(21, 1, 300, 290),
(21, 6, 300, 276);

COMMIT;
