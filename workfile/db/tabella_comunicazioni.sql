BEGIN;
CREATE TABLE progetto.comunicazioni(id SERIAL PRIMARY KEY,testo text NOT NULL, data DATE default NOW(), utente INTEGER NOT NULL REFERENCES utenti(id) ON DELETE CASCADE);
SELECT audit.audit_table('progetto.comunicazioni');
COMMIT;
