BEGIN;
CREATE TABLE liste.conservazione(id serial PRIMARY KEY, value CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO liste.conservazione(value) VALUES
('integro'),
('intero'),
('mutilo'),
('ricomposto'),
('ricomponibile'),
('parzialmente ricomposto'),
('parzialmente ricomponibile'),
('frammentario'),
('reintegrato'),
('parzialmente reintegrato'),
('NR (recupero pregresso)');
COMMIT;
