BEGIN;
alter TABLE scheda
  DROP COLUMN inventario,
  DROP COLUMN suffix,
  DROP COLUMN chiusa,
  DROP COLUMN verificata,
  DROP COLUMN inviata,
  DROP COLUMN validata,
  DROP COLUMN nctn;
drop TABLE nu_do;
COMMIT;
