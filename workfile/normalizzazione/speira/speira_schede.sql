BEGIN;
DROP TABLE IF EXISTS work.speira_schede;
update scheda set id_temp = null;

CREATE TABLE work.speira_schede(
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
  geonote CHARACTER VARYING,
  dtzgi INTEGER REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzgf integer REFERENCES liste.cronologia(id) ON DELETE CASCADE,
  dtzs integer REFERENCES liste.dtzs(id) ON DELETE CASCADE,
  dtsi NUMERIC(4,0),
  dtsf NUMERIC(4,0),
  dtm integer,
  materia integer,
  tecnica CHARACTER VARYING,
  misa numeric(5,2),
  misl numeric(5,2),
  mislu numeric(5,2),
  misp numeric(5,2),
  misd numeric(5,2),
  deso CHARACTER VARYING,
  stcc integer REFERENCES liste.stcc(id) ON DELETE CASCADE,
  oss CHARACTER VARYING
);
alter table work.speira_schede owner to marta;
-- copy work.speira_schede from '/var/www/marta/workfile/normalizzazione/speira/speira_schede.csv' delimiter ',' csv header;
-- update nctn set libero = false where nctn in (select nctn from work.speira_schede);
--
-- insert into scheda(id_temp, titolo, tsk,lir,cmpn,cmpd,fur) select id, titolo, 1, 2, 81, cmpd, 45 from work.speira_schede;
-- update work.speira_schede t set scheda = s.id from scheda s where t.id = s.id_temp and s.id_temp is not null;
-- insert into stato_scheda(scheda) select scheda from work.speira_schede;
-- insert into nctn_scheda(nctn, scheda) select nctn, scheda from work.speira_schede;
-- insert into inventario(inventario,suffisso,scheda) select inventario,suffisso,scheda from work.speira_schede where inventario is not null;
-- INSERT INTO og_ra(scheda, l3, l4) select scheda, l3, l4 from work.speira_schede;
-- INSERT INTO lc(scheda, piano, sala, contenitore) select scheda, piano, sala, contenitore from work.speira_schede;
-- INSERT INTO geolocalizzazione(scheda,comune,via, geonote) select scheda, comune, via, geonote from work.speira_schede;
-- INSERT INTO dt select scheda, dtzs, dtsi, dtsf, dtzgi, dtzgf from work.speira_schede;
-- INSERT INTO dtm select scheda, dtm from work.speira_schede;
-- INSERT INTO mtc select scheda, materia, tecnica from work.speira_schede;
-- INSERT INTO mis(scheda,misa, misl, mislu, misp, misd) select scheda,misa, misl, mislu, misp, misd from work.speira_schede;
-- INSERT INTO da(scheda, deso) select scheda, deso from work.speira_schede;
-- INSERT INTO co select scheda, stcc, 3 from work.speira_schede;
-- INSERT INTO an SELECT scheda, oss from work.speira_schede where oss is not null;
-- INSERT INTO ad(scheda, adsp, adsm) select scheda, 1, 1 from work.speira_schede;
-- INSERT INTO tu(scheda,cdgg) select scheda, 1 from work.speira_schede;

COMMIT;
