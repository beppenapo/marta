BEGIN;
CREATE TABLE file(
  id serial PRIMARY KEY,
  scheda INTEGER REFERENCES scheda(id),
  codtxt CHARACTER VARYING NOT NULL,
  codint INTEGER NOT NULL,
  percorso CHARACTER VARYING,
  tipo INTEGER NOT NULL
);
comment on column file.tipo is '1=3d, 2=stereo, 3 = foto';
COMMIT;
