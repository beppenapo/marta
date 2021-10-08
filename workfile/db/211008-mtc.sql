begin;
alter table  mtc drop constraint mtc_pkey;
alter table  mtc drop constraint mtc_scheda_materia_key;
alter table mtc add CONSTRAINT mtc_pkey PRIMARY KEY (scheda,materia);
alter table mtc drop COLUMN id;
commit;
