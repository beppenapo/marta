BEGIN;
create table liste.tipo_file(id SMALLINT primary key,value character varying not null unique);
insert into liste.tipo_file(id,value) values (1,'3d'),(2,'documenti'),(3,'foto'),(4,'grafica'),(5,'video'),(6,'audio');
alter table file add constraint tipo_file_fki FOREIGN KEY (tipo) REFERENCES liste.tipo_file(id) on DELETE CASCADE;
COMMIT;
