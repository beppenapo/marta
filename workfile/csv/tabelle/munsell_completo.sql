begin;
drop table liste.munsell cascade;
create table liste.munsell(
  id serial primary key,
  gruppo CHARACTER VARYING not null,
  code character varying not null,
  color character varying not null,
  unique(gruppo,code)
);
copy liste.munsell(gruppo, code, color) from '/var/www/marta/workfile/csv/munsell_completo.csv' delimiter ',' csv header;

--alter table munsell add column m_id integer;
--alter table munsell add constraint munsell_fki foreign key (m_id) references liste.munsell(id) on delete cascade;
update munsell set m_id = m.id from liste.munsell m where munsell.munsell = concat(m.gruppo,' ',m.code);
alter table munsell drop column munsell;
alter table munsell rename column m_id to munsell;
alter table liste.munsell OWNER to marta;
alter table munsell OWNER to marta;
commit;
