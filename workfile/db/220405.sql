begin;
DROP TABLE if EXISTS strade;
create TABLE vie(
  osm_id bigint PRIMARY KEY,
  comune integer references comuni(id) on delete cascade not null,
  via CHARACTER VARYING not null,
  lat numeric(6,4),
  lon numeric(6,4)
);
create TABLE geolocalizzazione(
  scheda integer primary key references scheda(id) on delete cascade,
  comune integer REFERENCES comuni(id) on delete cascade not null,
  via bigint REFERENCES vie(osm_id),
  geonote text
);
commit;
