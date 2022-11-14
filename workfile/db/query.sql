-- SELECT row_to_json(json.*) AS geometrie FROM (
--   SELECT 'FeatureCollection'::text AS type, array_to_json(array_agg(features.*)) AS features
--   FROM (
--     SELECT 'Feature'::text AS type, st_asgeojson(comuni.geom)::json AS geometry, row_to_json(prop.*) AS properties
--     FROM comuni
--     JOIN ( SELECT id, comune FROM comuni ) prop
--     ON comuni.id = prop.id ) features
-- ) json;


SELECT comuni.id, comuni.comune FROM comuni, gp where st_contains(comuni.geom, st_setsrid(st_point(gp.gpdpx, gp.gpdpy),4326)) group by id, comune;
-- select st_point(gpdpx, gpdpy) from gp;
