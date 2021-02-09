BEGIN;
DROP TABLE mtc;
CREATE TABLE mtc(
  id SERIAL PRIMARY KEY,
  scheda INTEGER REFERENCES scheda(id) ON DELETE CASCADE,
  materia INTEGER NOT NULL REFERENCES liste.dtm_motivazione_cronologia(id) ON DELETE CASCADE,
  tecnica CHARACTER VARYING,
  UNIQUE (scheda,materia)
);
CREATE INDEX mtc_idx ON mtc(materia);
SELECT audit.audit_table('mtc');
COMMIT;
