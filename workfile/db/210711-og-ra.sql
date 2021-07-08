CREATE TABLE og_ra(
  scheda integer PRIMARY KEY references scheda(id) on delete cascade,
  l3 integer not null REFERENCES liste.ra_cls_l3(id),
  l4 integer not null REFERENCES liste.ra_cls_l4(id),
  l5 integer REFERENCES liste.ra_cls_l5(id),
  ogtt CHARACTER VARYING
);
