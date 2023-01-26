BEGIN;
DROP TABLE IF EXISTS work.cecconi_schede;
DROP TABLE IF EXISTS work.cecconi_mis;
DROP TABLE IF EXISTS work.cecconi_dtm;
update scheda set id_temp = null;

CREATE TABLE work.cecconi_schede(
  id INTEGER PRIMARY KEY,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  cmpd date,
  nctn integer,
  inventario integer,
  suffisso CHARACTER VARYING,
  titolo CHARACTER VARYING,
  l3 INTEGER references liste.ra_cls_l3(id) ON DELETE CASCADE,
  l4 INTEGER references liste.ra_cls_l4(id) ON DELETE CASCADE,
  piano integer,
  sala integer,
  contenitore CHARACTER VARYING,
  comune INTEGER REFERENCES comuni(id) ON DELETE CASCADE,
  via CHARACTER VARYING,
  dtzgi INTEGER REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzgf integer REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  materia integer,
  tecnica CHARACTER VARYING,
  stcc integer REFERENCES liste.stcc(id) ON DELETE CASCADE,
  deso CHARACTER VARYING,
  oss CHARACTER VARYING
);
CREATE TABLE work.cecconi_mis(
  id integer primary key references work.cecconi_schede(id) on delete cascade,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  misa numeric(5,2),
  misl numeric(5,2),
  misp numeric(5,2)
);
CREATE TABLE work.cecconi_dtm(
  id integer references work.cecconi_schede(id) on delete cascade,
  scheda integer REFERENCES scheda(id) on DELETE CASCADE,
  dtm integer REFERENCES liste.dtm(id) ON DELETE CASCADE,
  primary key(id,dtm)
);
alter table work.cecconi_schede owner to marta;
alter table work.cecconi_mis owner to marta;
alter table work.cecconi_dtm owner to marta;
copy work.cecconi_schede from '/var/www/html/marta/workfile/normalizzazione/cecconi/cecconi_schede.csv' delimiter ',' csv header;
copy work.cecconi_mis from '/var/www/html/marta/workfile/normalizzazione/cecconi/cecconi_mis.csv' delimiter ',' csv header;
copy work.cecconi_dtm from '/var/www/html/marta/workfile/normalizzazione/cecconi/cecconi_dtm.csv' delimiter ',' csv header;

update nctn set libero = false where nctn in (select nctn from work.cecconi_schede);
insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id, titolo, 1, 2, 37, cmpd, 45 from work.cecconi_schede;
update work.cecconi_schede t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.cecconi_mis t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
update work.cecconi_dtm t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;

insert into stato_scheda(scheda) select scheda from work.cecconi_schede;
insert into nctn_scheda(nctn, scheda) select nctn, scheda from work.cecconi_schede;
insert into inventario(inventario,suffisso,scheda) select inventario,suffisso,scheda from work.cecconi_schede where inventario is not null;
INSERT INTO og_ra(scheda, l3, l4) select scheda, l3, l4 from work.cecconi_schede;
INSERT INTO lc(scheda, piano, sala, contenitore) select scheda, piano, sala, contenitore from work.cecconi_schede;
INSERT INTO geolocalizzazione(scheda,comune,via) select scheda, comune, via from work.cecconi_schede;
INSERT INTO dt(scheda, dtsi, dtsf, dtzgi, dtzgf) select scheda, dtsi, dtsf, dtzgi, dtzgf from work.cecconi_schede;
INSERT INTO dtm select scheda, dtm from work.cecconi_dtm;
INSERT INTO mtc select scheda, materia, tecnica from work.cecconi_schede;
INSERT INTO mis(scheda,misa, misl, misp) select scheda,misa, misl, misp from work.cecconi_mis;
INSERT INTO da(scheda, deso) select scheda, deso from work.cecconi_schede;
INSERT INTO co select scheda, stcc, 3 from work.cecconi_schede;
INSERT INTO an SELECT scheda, oss from work.cecconi_schede where oss is not null;
INSERT INTO ad(scheda, adsp, adsm) select scheda, 1, 1 from work.cecconi_schede;
INSERT INTO tu(scheda,cdgg) select scheda, 1 from work.cecconi_schede;

/* aggiorno le foto */
update import i set scheda = w.scheda from work.cecconi_schede w where i.nctn = w.nctn and i.imported = false;
insert into file(file, scheda, tipo ) select file, scheda, 3 from import where scheda is not null and imported = false;
update import set imported = true where scheda is not null and imported = false;
/* aggiorno i 3d */
update work.nxz nxz set scheda = w.scheda from work.cecconi_schede w where nxz.inv = w.inventario and nxz.imported = false;
insert into file(file, scheda, tipo ) select file, scheda, 1 from work.nxz where scheda is not null and imported = false;
update work.nxz set imported = true where scheda is not null and imported = false;
COMMIT;
