BEGIN;
CREATE TABLE liste.condizione_giuridica(id serial PRIMARY KEY, value CHARACTER VARYING NOT NULL UNIQUE);
INSERT INTO liste.condizione_giuridica(value) VALUES
('proprietà Stato'),
('proprietà Ente pubblico territoriale'),
('proprietà Ente pubblico non territoriale'),
('proprietà privata'),
('proprietà Ente religioso cattolico'),
('proprietà Ente religioso non cattolico'),
('proprietà Ente straniero in Italia'),
('proprietà mista'),
('proprietà mista pubblica/privata'),
('proprietà mista pubblica/ecclesiastica'),
('proprietà mista privata/ecclesiastica'),
('proprietà persona giuridica senza scopo di lucro'),
('detenzione Stato'),
('detenzione Ente pubblico territoriale'),
('detenzione Ente pubblico non territoriale'),
('detenzione privata'),
('detenzione Ente religioso cattolico'),
('detenzione Ente religioso non cattolico'),
('detenzione Ente straniero in Italia'),
('detenzione mista pubblica/privata'),
('detenzione mista pubblica/ecclesiastica'),
('detenzione mista privata/ecclesiastica'),
('detenzione persona giuridica senza scopo di lucro'),
('dato non disponibile'),
('NR (recupero pregresso)');
COMMIT;
