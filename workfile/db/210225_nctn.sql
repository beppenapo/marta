BEGIN;
DROP TABLE IF EXISTS liste.nctn;
CREATE TABLE nctn(
  id serial PRIMARY KEY,
  nctn INTEGER NOT NULL UNIQUE,
  libero BOOLEAN DEFAULT false
);
insert into nctn(nctn) SELECT * FROM generate_series(314852,334518);

ALTER TABLE scheda ADD COLUMN nctn INTEGER NOT NULL REFERENCES nctn(nctn) ON DELETE NO ACTION;
ALTER TABLE scheda DROP CONSTRAINT scheda_inventario_key;
ALTER TABLE scheda ADD CONSTRAINT scheda_unique UNIQUE (nctn);
COMMIT;
