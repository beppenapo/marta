BEGIN;
CREATE TABLE liste.biblio_tipo(id serial PRIMARY KEY, value CHARACTER VARYING UNIQUE NOT NULL);
INSERT INTO liste.biblio_tipo(value) VALUES ('Monografia'),('Atti convegno'),('Articolo in rivista');
CREATE TABLE liste.biblio_livello(id serial PRIMARY KEY, value CHARACTER VARYING UNIQUE NOT NULL);
INSERT INTO liste.biblio_livello(value) VALUES ('riferimento specifico'),('solo contesto'),('confronto');

ALTER TABLE bibliografia DROP COLUMN pagine;

ALTER TABLE biblio_scheda
  ADD COLUMN pagine CHARACTER VARYING,
  ADD COLUMN figure CHARACTER VARYING,
  ADD COLUMN livello INTEGER REFERENCES liste.biblio_livello(id);


COMMIT;
