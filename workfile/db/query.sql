SELECT row_to_json(json.*) AS geometrie FROM (
  SELECT 'FeatureCollection'::text AS type, array_to_json(array_agg(features.*)) AS features
  FROM (
    SELECT 'Feature'::text AS type, st_asgeojson(comuni.geom)::json AS geometry, row_to_json(prop.*) AS properties
    FROM comuni
    JOIN ( SELECT id, comune FROM comuni ) prop
    ON comuni.id = prop.id
    where comuni.id = 108
  ) features
) json;
