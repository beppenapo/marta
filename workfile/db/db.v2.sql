BEGIN;
DROP TABLE roz;
DROP TABLE rse;
DROP TABLE rve;
DROP TABLE rv;
DROP TABLE cd;
DROP TABLE scheda CASCADE;
CREATE TABLE scheda(
  id serial PRIMARY KEY,
  inventario INTEGER NOT NULL UNIQUE,
  suffix CHARACTER VARYING,
  chiusa BOOLEAN NOT NULL DEFAULT 'f',
  verificata BOOLEAN NOT NULL DEFAULT 'f',
  inviata BOOLEAN NOT NULL DEFAULT 'f',
  validata BOOLEAN NOT NULL DEFAULT 'f'
);
CREATE INDEX scheda_idx ON scheda(inventario);
SELECT audit.audit_table('scheda');

CREATE TABLE cd(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  tsk INTEGER REFERENCES liste.tsk(id) ON DELETE SET NULL,
  lir INTEGER DEFAULT 2 REFERENCES liste.lir(id) ON DELETE SET DEFAULT,
  nctn CHARACTER VARYING,
  ncts CHARACTER VARYING,
  ente INTEGER DEFAULT 1 REFERENCES ente(id) ON DELETE SET DEFAULT
);
CREATE INDEX nctn_idx ON cd(nctn);
CREATE INDEX tsk_idx ON cd(tsk);
CREATE INDEX lir_idx ON cd(lir);
COMMENT ON COLUMN cd.ncts IS 'Suffisso numero catalogo generale';
SELECT audit.audit_table('cd');

CREATE TABLE og(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  ogtd INTEGER REFERENCES liste.ra_ogtd(id) ON DELETE SET NULL
);
CREATE INDEX ogtd_idx ON og(ogtd);
SELECT audit.audit_table('og');

CREATE TABLE pvc(
  id SERIAL PRIMARY KEY,
  pvcc INTEGER NOT NULL UNIQUE DEFAULT 73027 REFERENCES liste.comuni(codice) ON DELETE SET DEFAULT
);
INSERT INTO pvc(pvcc) VALUES(73027);

CREATE TABLE ldc(
  id SERIAL PRIMARY KEY,
  ldct CHARACTER VARYING NOT NULL,
  ldcq CHARACTER VARYING NOT NULL,
  ldcn CHARACTER VARYING NOT NULL,
  ldcc CHARACTER VARYING NOT NULL,
  ldcu CHARACTER VARYING NOT NULL
);
INSERT INTO ldc(ldct,ldcq,ldcn,ldcc,ldcu) VALUES('museo','archeologico','Museo Archeologico Nazionale di Taranto','Museo Archeologico Nazionale di Taranto','via Cavour, 10, 74123');

CREATE TABLE lc(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  pvc INTEGER NOT NULL DEFAULT 1 REFERENCES pvc(id) ON DELETE SET DEFAULT,
  ldc INTEGER NOT NULL DEFAULT 1 REFERENCES ldc(id) ON DELETE SET DEFAULT,
  ldcs character varying NOT NULL
);
COMMENT ON TABLE lc IS 'Localizzazione geografico-amministrativa. PVC e LDC sono sempre gli stessi';
COMMENT ON COLUMN lc.ldcs IS 'Collocazione all''interno del museo';
SELECT audit.audit_table('lc');

CREATE TABLE la(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  tcl INTEGER REFERENCES liste.la_tcl(id) ON DELETE SET NULL,
  prvc INTEGER REFERENCES liste.comuni(codice) ON DELETE SET NULL
);
COMMENT ON TABLE la IS 'Altre localizzazioni geografico-amministrative';
SELECT audit.audit_table('la');

CREATE TABLE re(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  scan CHARACTER VARYING NOT NULL,
  dsca CHARACTER VARYING NOT NULL,
  dscd CHARACTER VARYING NOT NULL
);
COMMENT ON TABLE re IS 'Modalità di reperimento';
COMMENT ON COLUMN re.scan IS 'Denominazione scavo';
COMMENT ON COLUMN re.dsca IS 'Responsabile scientifico';
SELECT audit.audit_table('re');

CREATE TABLE dtz(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  dtzg CHARACTER VARYING NOT NULL,
  dtzs INTEGER NOT NULL
);
SELECT audit.audit_table('dtz');

CREATE TABLE dts(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  dtsi CHARACTER VARYING NOT NULL,
  dtsf CHARACTER VARYING NOT NULL
);
SELECT audit.audit_table('dts');

CREATE TABLE dtm(
  id SERIAL PRIMARY KEY,
  scheda INTEGER REFERENCES scheda(id) ON DELETE CASCADE,
  dtm integer NOT NULL,
  UNIQUE (scheda,dtm)
);
SELECT audit.audit_table('dtm');

CREATE TABLE mtc(
  id SERIAL PRIMARY KEY,
  scheda INTEGER REFERENCES scheda(id) ON DELETE CASCADE,
  materia INTEGER NOT NULL REFERENCES liste.dtm_motivazione_cronologia(id) ON DELETE CASCADE,
  tecnica CHARACTER VARYING,
  UNIQUE (scheda,materia)
);
CREATE INDEX mtc_idx ON mtc(materia);
SELECT audit.audit_table('mtc');

CREATE TABLE mis(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  misa NUMERIC(5,2),
  misl NUMERIC(5,2),
  misp NUMERIC(5,2),
  misd NUMERIC(5,2),
  misn NUMERIC(5,2),
  miss NUMERIC(5,2),
  misg NUMERIC(5,2),
  misv CHARACTER VARYING,
  misr CHARACTER VARYING
);
SELECT audit.audit_table('mis');

COMMENT ON COLUMN mis.misa IS 'altezza';
COMMENT ON COLUMN mis.misl IS 'larghezza';
COMMENT ON COLUMN mis.misp IS 'profondità';
COMMENT ON COLUMN mis.misd IS 'diametro';
COMMENT ON COLUMN mis.misn IS 'lunghezza';
COMMENT ON COLUMN mis.miss IS 'spessore';
COMMENT ON COLUMN mis.misg IS 'peso';
COMMENT ON COLUMN mis.misv IS 'misure varie, indicare altre misure utili, specificando sia il tipo di misura, sia la parte presa in esame, sia l''unità di misura';
COMMENT ON COLUMN mis.misr IS 'Misure Non Rilevabili, indicate con l''acronimo MNR';

CREATE TABLE da(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  deso CHARACTER VARYING NOT NULL
);
SELECT audit.audit_table('da');

CREATE TABLE co(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  stcc INTEGER NOT NULL REFERENCES liste.conservazione(id) ON DELETE CASCADE
);
SELECT audit.audit_table('co');

CREATE TABLE tu(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  cdgg INTEGER NOT NULL REFERENCES liste.condizione_giuridica(id) ON DELETE CASCADE
);
SELECT audit.audit_table('tu');

CREATE TABLE liste.adsp(id SERIAL PRIMARY KEY, value CHARACTER VARYING NOT NULL UNIQUE);
CREATE TABLE liste.adsm(id SERIAL PRIMARY KEY, value CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO liste.adsp(value) VALUES
  ('livello basso di riservatezza'),
  ('livello medio di riservatezza'),
  ('livello alto di riservatezza');
INSERT INTO liste.adsm(value) VALUES
  ('scheda contenente dati liberamente accessibili'),
  ('scheda contenente dati personali o di bene di proprietà privata'),
  ('scheda di bene a rischio o non adeguatamente sorvegliabile');

CREATE TABLE ad(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  adsp INTEGER NOT NULL REFERENCES liste.adsp(id) ON DELETE CASCADE,
  adsm INTEGER NOT NULL REFERENCES liste.adsm(id) ON DELETE CASCADE
);
SELECT audit.audit_table('ad');

CREATE TABLE cm(
  scheda INTEGER PRIMARY KEY REFERENCES scheda(id) ON DELETE CASCADE,
  cmpn INTEGER REFERENCES utenti(id) ON DELETE SET NULL,
  cmpd date NOT NULL DEFAULT now(),
  fur CHARACTER VARYING not null
);
SELECT audit.audit_table('cm');
COMMENT ON TABLE cm IS 'Compilazione';
COMMENT ON COLUMN cm.cmpd IS 'data compilazione';
COMMENT ON COLUMN cm.cmpn IS 'compilatore';
COMMENT ON COLUMN cm.fur IS 'funzionario responsabile';

COMMIT;
