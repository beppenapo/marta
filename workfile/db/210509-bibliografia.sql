begin;
ALTER TABLE bibliografia ADD COLUMN curatore CHARACTER VARYING;
SELECT audit.audit_table('bibliografia');
commit;
