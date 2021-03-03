BEGIN;
ALTER TABLE liste.scaffali ADD COLUMN note CHARACTER VARYING;
INSERT INTO liste.scaffali(sala, scaffale, colonna, note) VALUES
  (4,40,1, 'forziere'),
  (4,40,2, 'forziere'),
  (4,40,3, 'forziere'),
  (4,41,1, 'cassaforte'),
  (4,41,2, 'cassaforte'),
  (4,41,3, 'cassaforte'),
  (4,41,4, 'cassaforte');
  
alter table lc
  ADD COLUMN piano INTEGER NOT NULL,
  ADD COLUMN stanza INTEGER NOT NULL,
  ADD COLUMN contenitore INTEGER,
  ADD COLUMN colonna INTEGER,
  ADD COLUMN ripiano CHARACTER VARYING;
COMMENT ON COLUMN lc.contenitore IS 'Nel caso dei piani 1-3 si tratta delle vetrine, mentre nel caso del piano -1 (deposito) si tratta degli scaffali';
COMMENT ON COLUMN lc.colonna IS 'Da compilare solo per il deposito. Le colonne dello scaffale 40 sono i forzieri, quelle dello scaffale 41 sono le casseforti';
COMMENT ON COLUMN lc.ripiano IS 'Nel caso degli scaffali sono ripiani (5 x colonna), nel caso dei forzieri sono plateau (104 x forziere), nel caso delle casseforti 1 e 4 sono cassetti (56 x la 1, 19 x la 4), nel caso delle casseforti 2 e 3 sono ripiani (4 x cassaforte). La cassaforte 4 ha i cassetti nominati non con i numeri arabi ma con le lettere da A a U';
COMMIT;
