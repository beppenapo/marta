BEGIN;
-- alter table biblio_scheda  DROP CONSTRAINT biblio_scheda_contributo_fkey;
-- alter table biblio_scheda add CONSTRAINT biblio_scheda_contributo_fkey foreign key (contributo) REFERENCES contributo(id) on delete cascade;
-- CREATE TABLE progetto.report(
--   id serial primary key,
--   data date not null,
--   utente integer not null REFERENCES utenti(id) on DELETE cascade,
--   report text not null,
--   unique(data,utente)
-- );
COMMIT;
