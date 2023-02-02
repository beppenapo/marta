begin;
DROP TABLE IF EXISTS work.melillo_schede;
DROP TABLE IF EXISTS work.melillo_dtm;
DROP TABLE IF EXISTS work.melillo_materia;
DROP TABLE IF EXISTS work.melillo_misure;
update scheda set id_temp = null;

CREATE TABLE work.melillo_schede(
  id INTEGER PRIMARY KEY,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  nctn integer,
  inventario integer,
  suffisso CHARACTER VARYING,
  cmpd date,
  titolo CHARACTER VARYING,
  deso CHARACTER VARYING,
  l3 INTEGER references liste.ra_cls_l3(id) ON DELETE CASCADE,
  l4 INTEGER references liste.ra_cls_l4(id) ON DELETE CASCADE,
  piano integer,
  sala integer,
  contenitore CHARACTER VARYING,
  comune INTEGER REFERENCES comuni(id) ON DELETE CASCADE,
  via CHARACTER VARYING,
  geonote CHARACTER VARYING,
  dtzgi INTEGER REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzgf integer REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzs integer REFERENCES liste.dtzs(id) ON DELETE CASCADE,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  stcc integer REFERENCES liste.stcc(id) ON DELETE CASCADE,
  oss CHARACTER VARYING
);

create TABLE work.melillo_dtm(
  id integer,
  scheda integer references scheda(id) on delete cascade,
  dtm integer references liste.dtm(id) on delete cascade,
  primary key(id,dtm)
);
create TABLE work.melillo_mtc(
  id integer,
  scheda integer references scheda(id) on delete cascade,
  materia integer references liste.materia(id) on delete cascade,
  tecnica CHARACTER VARYING,
  primary key(id,materia,tecnica)
);
create TABLE work.melillo_mis(
  id integer primary key,
  scheda integer references scheda(id) on delete cascade,
  misa numeric(5,2),
  misl numeric(5,2),
  misp numeric(5,2),
  misn numeric(5,2),
  misd numeric(5,2)
);

alter table work.melillo_schede owner to marta;
alter table work.melillo_dtm owner to marta;
alter table work.melillo_mtc owner to marta;
alter table work.melillo_mis owner to marta;

copy work.melillo_schede from '/var/www/marta/workfile/normalizzazione/melillo/melillo_schede.csv' delimiter ',' csv header;
copy work.melillo_dtm from '/var/www/marta/workfile/normalizzazione/melillo/melillo_dtm.csv' delimiter ',' csv header;
copy work.melillo_mtc from '/var/www/marta/workfile/normalizzazione/melillo/melillo_mtc.csv' delimiter ',' csv header;
copy work.melillo_mis from '/var/www/marta/workfile/normalizzazione/melillo/melillo_mis.csv' delimiter ',' csv header;

update nctn set libero = false where nctn in (select nctn from work.melillo_schede);

insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id, titolo, 1, 2, 82, cmpd, 45 from work.melillo_schede;
update work.melillo_schede t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.melillo_dtm t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.melillo_mtc t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.melillo_mis t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;

insert into stato_scheda(scheda) select scheda from work.melillo_schede;
insert into nctn_scheda(nctn, scheda) select nctn, scheda from work.melillo_schede;
insert into inventario(inventario,suffisso,scheda) select inventario,suffisso,scheda from work.melillo_schede where inventario is not null;
INSERT INTO og_ra(scheda, l3, l4) select scheda, l3, l4 from work.melillo_schede;
INSERT INTO lc(scheda, piano, sala, contenitore) select scheda, piano, sala, contenitore from work.melillo_schede;
INSERT INTO geolocalizzazione(scheda,comune,via, geonote) select scheda, comune, via, geonote from work.melillo_schede;
INSERT INTO dt select scheda, dtzs, dtsi, dtsf, dtzgi, dtzgf from work.melillo_schede;
INSERT INTO dtm select scheda, dtm from work.melillo_dtm;
INSERT INTO mtc select scheda, materia, tecnica from work.melillo_mtc;
INSERT INTO mis(scheda,misa,misl,misp,misn,misd) select scheda,misa,misl,misp,misn,misd from work.melillo_mis;
INSERT INTO da(scheda, deso) select scheda, deso from work.melillo_schede;
INSERT INTO co select scheda, stcc, 3 from work.melillo_schede;
INSERT INTO an SELECT scheda, oss from work.melillo_schede where oss is not null;
INSERT INTO ad(scheda, adsp, adsm) select scheda, 1, 1 from work.melillo_schede;
INSERT INTO tu(scheda,cdgg) select scheda, 1 from work.melillo_schede;

/* aggiorno le foto */
update import i set scheda = w.scheda from work.melillo_schede w where i.nctn = w.nctn and i.imported = false;
insert into file(file, scheda, tipo ) select file, scheda, 3 from import where scheda is not null and imported = false;
update import set imported = true where scheda is not null and imported = false;
/* aggiorno i 3d */
update work.nxz nxz set scheda = w.scheda from work.melillo_schede w where nxz.inv = w.inventario and nxz.imported = false;
insert into file(file, scheda, tipo ) select file, scheda, 1 from work.nxz where scheda is not null and imported = false;
update work.nxz set imported = true where scheda is not null and imported = false;

commit;
