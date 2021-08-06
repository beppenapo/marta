BEGIN;
-- alter table biblio_scheda  DROP CONSTRAINT biblio_scheda_contributo_fkey;
-- alter table biblio_scheda add CONSTRAINT biblio_scheda_contributo_fkey foreign key (contributo) REFERENCES contributo(id) on delete cascade;
COMMIT;
