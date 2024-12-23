<?php
namespace Marta;
session_start();
use \Marta\Conn;

class Geom extends Conn{
  function __construct(){}

  public function getComune(int $id){
    // $checkReperti = $id == 0 ? ', gp where st_contains(comuni.geom, st_setsrid(st_point(gp.gpdpx, gp.gpdpy),4326)) group by id, comune ': '';
    $checkReperti = '';
    $totReperti = '';
    $where = '';
    if($id == 0){
      $totReperti = ", count(*)";
      $checkReperti = ' inner join geolocalizzazione geo on geo.comune = comuni.id group by comuni.id, comuni.comune order by 2 asc';
    }
    if($id > 0){$where = 'where comuni.id = '.$id;}
    $sql = "
    SELECT row_to_json(json.*) AS geometrie FROM (
      SELECT 'FeatureCollection'::text AS type, array_to_json(array_agg(features.*)) AS features
      FROM (
        SELECT 'Feature'::text AS type, st_asgeojson(comuni.geom)::json AS geometry, row_to_json(prop.*) AS properties
        FROM comuni
        JOIN ( SELECT comuni.id, comuni.comune ".$totReperti." FROM comuni ".$checkReperti.") prop
        ON comuni.id = prop.id ".$where.") features
    ) json;";
    $json = $this->simple($sql);
    return $json[0]['geometrie'];
  }

  public function getMarker(int $id = null){
    $sql = "select gp.scheda, array_agg(file.file) file, gp.gpdpx, gp.gpdpy,c.comune, geoloc.via, gallery.classe, gallery.ogtd
    from gp
    left join geolocalizzazione geoloc on geoloc.scheda = gp.scheda
    left join comuni c on geoloc.comune = c.id
    left join file on file.scheda = gp.scheda
    inner join gallery on gallery.id = gp.scheda
    group by gp.scheda, gp.gpdpx, gp.gpdpy,c.comune, geoloc.via, gallery.classe, gallery.ogtd";
    return $this->simple($sql);
  }
}
?>
