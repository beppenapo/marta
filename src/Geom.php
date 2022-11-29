<?php
namespace Marta;
session_start();
use \Marta\Conn;

class Geom extends Conn{
  function __construct(){}

  public function getComune(int $id){
    $checkReperti = $id == 0 ? ', gp where st_contains(comuni.geom, st_setsrid(st_point(gp.gpdpx, gp.gpdpy),4326)) group by id, comune ': '';
    $where = $id > 0 ? ' where comuni.id = '.$id : '';
    $sql = "
    SELECT row_to_json(json.*) AS geometrie FROM (
      SELECT 'FeatureCollection'::text AS type, array_to_json(array_agg(features.*)) AS features
      FROM (
        SELECT 'Feature'::text AS type, st_asgeojson(comuni.geom)::json AS geometry, row_to_json(prop.*) AS properties
        FROM comuni
        JOIN ( SELECT comuni.id, comuni.comune FROM comuni ".$checkReperti.") prop
        ON comuni.id = prop.id ".$where.") features
    ) json;";
    $json = $this->simple($sql);
    return $json[0]['geometrie'];
  }

  public function getMarker(int $id = null){
    $sql = "select gp.scheda, gp.gpdpx, gp.gpdpy,c.comune, geoloc.via, gallery.classe, gallery.ogtd
    from gp
    left join geolocalizzazione geoloc on geoloc.scheda = gp.scheda
    left join comuni c on geoloc.comune = c.id
    inner join gallery on gallery.id = gp.scheda";
    return $this->simple($sql);
  }
}
?>
