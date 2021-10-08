begin;
alter table  dtm drop constraint dtm_pkey;
alter table  dtm drop constraint dtm_scheda_dtm_key;
alter table dtm add CONSTRAINT dtm_pkey PRIMARY KEY (scheda,dtm);
alter table dtm drop COLUMN id;
commit;
