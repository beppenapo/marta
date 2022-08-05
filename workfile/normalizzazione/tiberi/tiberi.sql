begin;
create table work.tiberi(
  id_scheda integer primary key,
  nctn integer,
  inv integer,
  suf CHARACTER VARYING,
  l4 INTEGER,
  l3 INTEGER,
  piano INTEGER,
  sala INTEGER,
  vetrina CHARACTER VARYING,
  comune INTEGER,
  geonote CHARACTER VARYING,
  scan CHARACTER VARYING,
  dsca CHARACTER VARYING,
  dscd CHARACTER VARYING,
  dtzgi INTEGER,
  dtzgf INTEGER,
  dtzs INTEGER,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  dtm INTEGER,
  materia INTEGER,
  tecnica CHARACTER VARYING,
  misa numeric(5,2),
  misl numeric(5,2),
  misp numeric(5,2),
  misd numeric(5,2),
  deso CHARACTER VARYING,
  stcc INTEGER,
  stcl integer,
  adsp INTEGER,
  adsm INTEGER,
  cmpd date,
  cmpn INTEGER,
  fur INTEGER,
  cdgg INTEGER
);
alter table work.tiberi owner to marta;
alter table work.tiberi add column scheda integer references scheda(id) on delete cascade;
update nctn set libero = false where nctn in (select nctn from work.tiberi);
update work.tiberi set nctn = 333029 where id_scheda = 3;
insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id_scheda, concat('tiberi-',nctn), 1, 2, 43, cmpd, 39 from work.tiberi;
update work.tiberi t set scheda = s.id from scheda s where t.id_scheda = s.id_temp and s.id_temp is not null;
insert into stato_scheda(scheda) select scheda from work.tiberi;
insert into nctn_scheda(nctn, scheda) select nctn, scheda from work.tiberi;
INSERT INTO inventario(inventario,suffisso,scheda) select inv,suf,scheda from work.tiberi where inv is not null;
INSERT INTO ad(scheda, adsp, adsm) select scheda, adsp, adsm from work.tiberi;
INSERT INTO co select scheda, stcc, stcl from work.tiberi;
INSERT INTO da(scheda, deso) select scheda, deso from work.tiberi;
INSERT INTO dt select scheda, dtzs, dtsi, dtsf, dtzgi, dtzgf from work.tiberi;
INSERT INTO lc(scheda, piano, sala, contenitore) select scheda, piano, sala, vetrina from work.tiberi;
INSERT INTO mis(scheda,misa, misl, misp, misd) select scheda, misa, misl, misp, misd from work.tiberi;
INSERT INTO tu(scheda,cdgg) select scheda, cdgg from work.tiberi;
INSERT INTO dtm select scheda, dtm from work.tiberi;
INSERT INTO mtc select scheda, materia, tecnica from work.tiberi;
INSERT INTO geolocalizzazione(scheda,comune,geonote) select scheda, comune, geonote from work.tiberi;
INSERT INTO og_ra(scheda, l3, l4) select scheda, l3, l4 from work.tiberi;
update work.tiberi set scan = 'Dato assente' where scan is null;
update work.tiberi set dsca = 'Dato assente' where dsca is null;
update work.tiberi set dscd = 'Dato assente' where dscd is null;
INSERT INTO dsc(scheda,scan, dsca, dscd) select scheda, scan, dsca, dscd from work.tiberi;
alter table work.biblio_scheda_tiberi add column id_scheda integer references scheda(id) on delete cascade;
update work.biblio_scheda_tiberi t set id_scheda = s.id from scheda s where t.scheda = s.id_temp and s.id_temp is not null;
  insert into biblio_scheda(scheda, biblio, contributo, pagine, figure, livello)
  select t.id_scheda, b.id biblio, t.contributo, t.pagine, t.figure, t.livello
  from bibliografia b
  inner join work.biblio_scheda_tiberi t on t.biblio = b.id_temp
  where id_temp is not null
  order by 1 asc;

COMMIT;
