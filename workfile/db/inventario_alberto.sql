BEGIN;
CREATE TABLE inventario.inventario(
  id integer PRIMARY KEY,
  nctn integer,
  invn CHARACTER VARYING,
  suffix CHARACTER VARYING,
  tsk CHARACTER VARYING,
  lir CHARACTER VARYING,
  ogtd CHARACTER VARYING,
  cls CHARACTER VARYING,
  controllo_inventario INTEGER,
  rifatta BOOLEAN DEFAULT 'f'
);
ALTER TABLE inventario.inventario OWNER TO marta;
COPY inventario.inventario(id, nctn, invn, tsk, lir, ogtd, cls, controllo_inventario) FROM 'csv/invn_normalizzato.csv' WITH (FORMAT csv, HEADER true);
COMMIT;
