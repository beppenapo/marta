begin;
create table contributo(
  id serial primary key,
  raccolta integer not null REFERENCES bibliografia(id) on delete cascade,
  titolo character VARYING not null,
  autore character VARYING not null,
  altri_autori character VARYING,
  pag character VARYING,
  unique(raccolta,titolo)
);
insert into contributo(raccolta,titolo, autore) select id, titolo, autore from bibliografia where tipo = 2;

alter TABLE biblio_scheda add COLUMN contributo integer references contributo(id);
update biblio_scheda set contributo = contributo.id from contributo where biblio_scheda.biblio = contributo.raccolta;

update bibliografia set titolo = titolo_raccolta where tipo = 2;
alter table bibliografia DROP COLUMN titolo_raccolta;
update bibliografia set autore = 'AA.VV.' where tipo = 2;

commit;
