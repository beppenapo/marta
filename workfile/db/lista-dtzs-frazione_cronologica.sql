BEGIN;
CREATE TABLE liste.dtzs_frazione_cronologia(id serial PRIMARY KEY, value CHARACTER VARYING UNIQUE NOT NULL);
INSERT INTO liste.dtzs_frazione_cronologia(value) VALUES
('inizio'),
('fine'),
('metà'),
('prima metà'),
('seconda metà'),
('primo quarto'),
('secondo quarto'),
('terzo quarto'),
('ultimo quarto'),
('inizio/ inizio'),
('inizio/ metà'),
('metà/ inizio'),
('metà/ metà'),
('metà/ fine'),
('fine/ inizio'),
('fine/ metà'),
('fine/ fine'),
('ante'),
('post'),
('ca'),
('(?)');
COMMIT;
