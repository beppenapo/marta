BEGIN;
CREATE TABLE liste.la_tcl(
  id serial PRIMARY KEY,
  value CHARACTER VARYING UNIQUE NOT NULL
);
INSERT INTO liste.la_tcl(value) values
('luogo di provenienza'),
('luogo di esecuzione/fabbricazione'),
('luogo di reperimento'),
('luogo di deposito'),
('luogo di esposizione');
COMMIT;
