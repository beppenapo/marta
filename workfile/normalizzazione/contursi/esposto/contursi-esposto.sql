begin;
DROP TABLE IF EXISTS work.contursi_esposto_schede;
DROP TABLE IF EXISTS work.contursi_esposto_dtm;
DROP TABLE IF EXISTS work.contursi_esposto_materia;
DROP TABLE IF EXISTS work.contursi_esposto_misure;
update scheda set id_temp = null;

CREATE TABLE work.contursi_esposto_schede(
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

create TABLE work.contursi_esposto_dtm(
  id integer,
  scheda integer references scheda(id) on delete cascade,
  dtm integer references liste.dtm(id) on delete cascade,
  primary key(id,dtm)
);
create TABLE work.contursi_esposto_mtc(
  id integer,
  scheda integer references scheda(id) on delete cascade,
  materia integer references liste.materia(id) on delete cascade,
  tecnica CHARACTER VARYING,
  primary key(id,materia,tecnica)
);
create TABLE work.contursi_esposto_mis(
  id integer primary key,
  scheda integer references scheda(id) on delete cascade,
  misa numeric(5,2),
  misl numeric(5,2),
  misp numeric(5,2)
);

alter table work.contursi_esposto_schede owner to marta;
alter table work.contursi_esposto_dtm owner to marta;
alter table work.contursi_esposto_mtc owner to marta;
alter table work.contursi_esposto_mis owner to marta;

copy work.contursi_esposto_schede from '/var/www/marta/workfile/normalizzazione/contursi/esposto/contursi_esposto-schede.csv' delimiter ',' csv header;
copy work.contursi_esposto_dtm from '/var/www/marta/workfile/normalizzazione/contursi/esposto/contursi_esposto-dtm.csv' delimiter ',' csv header;
copy work.contursi_esposto_mtc from '/var/www/marta/workfile/normalizzazione/contursi/esposto/contursi_esposto-mtc.csv' delimiter ',' csv header;
copy work.contursi_esposto_mis from '/var/www/marta/workfile/normalizzazione/contursi/esposto/contursi_esposto-mis.csv' delimiter ',' csv header;

update nctn set libero = false where nctn in (select nctn from work.contursi_esposto_schede);

insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id, titolo, 1, 2, 30, cmpd, 45 from work.contursi_esposto_schede;
update work.contursi_esposto_schede t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.contursi_esposto_dtm t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.contursi_esposto_mtc t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.contursi_esposto_mis t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;

insert into stato_scheda(scheda) select scheda from work.contursi_esposto_schede;
insert into nctn_scheda(nctn, scheda) select nctn, scheda from work.contursi_esposto_schede;
insert into inventario(inventario,suffisso,scheda) select inventario,suffisso,scheda from work.contursi_esposto_schede where inventario is not null;
INSERT INTO og_ra(scheda, l3, l4) select scheda, l3, l4 from work.contursi_esposto_schede;
INSERT INTO lc(scheda, piano, sala, contenitore) select scheda, piano, sala, contenitore from work.contursi_esposto_schede;
INSERT INTO geolocalizzazione(scheda,comune,via, geonote) select scheda, comune, via, geonote from work.contursi_esposto_schede;
INSERT INTO dt select scheda, dtzs, dtsi, dtsf, dtzgi, dtzgf from work.contursi_esposto_schede;
INSERT INTO dtm select scheda, dtm from work.contursi_esposto_dtm;
INSERT INTO mtc select scheda, materia, tecnica from work.contursi_esposto_mtc;
INSERT INTO mis(scheda,misa,misl,misp) select scheda,misa,misl,misp from work.contursi_esposto_mis;
INSERT INTO da(scheda, deso) select scheda, deso from work.contursi_esposto_schede;
INSERT INTO co select scheda, stcc, 3 from work.contursi_esposto_schede;
INSERT INTO an SELECT scheda, oss from work.contursi_esposto_schede where oss is not null;
INSERT INTO ad(scheda, adsp, adsm) select scheda, 1, 1 from work.contursi_esposto_schede;
INSERT INTO tu(scheda,cdgg) select scheda, 1 from work.contursi_esposto_schede;

/* aggiorno le foto */
update import i set scheda = w.scheda from work.contursi_esposto_schede w where i.nctn = w.nctn and i.imported = false;
insert into file(file, scheda, tipo ) select file, scheda, 3 from import where scheda is not null and imported = false;
update import set imported = true where scheda is not null and imported = false;
/* aggiorno i 3d */
update work.nxz nxz set scheda = w.scheda from work.contursi_esposto_schede w where nxz.inv = w.inventario and nxz.imported = false;
insert into file(file, scheda, tipo ) select file, scheda, 1 from work.nxz where scheda is not null and imported = false;
update work.nxz set imported = true where scheda is not null and imported = false;

commit;
