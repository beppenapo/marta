<?php
namespace Marta;
session_start();
// use \Marta\Conn;
class Scheda extends Conn{
  public $db;
  function __construct(){}

  public function setDefaultImg(array $dati){
    if(is_array($dati) && !empty($dati)){
      try {
        $old = ["default" => 'false',"id"=>(int) $dati['old'], "scheda"=>(int) $dati['scheda']];
        $new = ["default" => 'true',"id"=>(int) $dati['new'], "scheda"=>(int) $dati['scheda']];
        $query = "update file set foto_principale = :default where id = :id and scheda = :scheda ";
        $this->begin();
        $this->prepared($query,$old);
        $this->prepared($query,$new);
        $this->commit();
        return ["msg"=>"La foto principale è stata settata correttamente", "error"=>0];
      } catch (\Throwable $e) {
        $this->rollback();
        return ["msg"=>$e->getMessage(), "error"=>$e->getCode()];
      }

      return $dati;
    }
  }

  public function progress(int $id){
    // $biblio = $this->simple("select count(*) biblio from biblio_scheda where scheda = ".$id);
    $biblio = $this->simple("select count(*) biblio from biblio_fake where scheda = ".$id);
    $foto = $this->simple("select count(*) foto from file where tipo = 3 and scheda = ".$id);
    $geo = $this->simple("select count(*) geo from geolocalizzazione where scheda = ".$id);
    $gp = $this->simple("select count(*) gp from gp where scheda = ".$id);
    return ["biblio"=>$biblio[0]['biblio'], "foto"=>$foto[0]['foto'], "geo"=>$geo[0]['geo'], "gp"=>$gp[0]['gp']];
  }

  public function getModel(int $id = null){
    $filter = $id !== null ? ' and scheda = '.$id : '';
    $sql = "select * from file where tipo = 1".$filter.";";
    return $this->simple($sql);
  }

  public function comuniPuglia(){
    $sql = "select id, comune,round(st_xmin(geom)::numeric,4) xmin, round(st_ymin(geom)::numeric,4) ymin, round(st_xmax(geom)::numeric,4) xmax, round(st_ymax(geom)::numeric,4) ymax from comuni order by 2 asc;";
    return $this->simple($sql);
  }

  public function getComuneFromPoint($coo){
    $sql="select id from comuni where st_contains(geom,ST_SetSRID(ST_MakePoint(".$coo['x'].",".$coo['y']."), 4326));";
    $out = $this->simple($sql);
    return $out[0];
  }
  public function checkNctn(){
    $sql = "select min(nctn), max(nctn) from nctn;";
    $out = $this->simple($sql);
    return $out[0];
  }
  public function checkTitolo($titolo){
    $sql = "select count(*) from scheda where titolo = '".$titolo."'";
    $out = $this->simple($sql);
    return $out[0];
  }

  public function getStatoScheda(int $id){
    $res = $this->simple("select * from stato_scheda where scheda = ".$id.";");
    return $res[0];
  }
  public function cambiaStatoScheda(array $dati){
    $filter = ["scheda"=>$dati['scheda']];
    unset($dati['scheda']);
    if(isset($dati['chiusa']) && $dati['chiusa'] === 'false'){
      $dati = ["chiusa"=>'false', "verificata"=>'false', "inviata"=> 'false', "accettata"=>'false'];
    }
    $sql = $this->buildUpdate('stato_scheda',$filter,$dati);
    return $this->prepared($sql,$dati);
  }
  public function getBiblioScheda(int $id){
    $sql = "select b.id, b.titolo, b.anno, b.autore,c.id as contrib_id, c.titolo as contrib_tit, c.autore as contrib_aut,bs.pagine, bs.figure
    from bibliografia b
    INNER JOIN biblio_scheda bs on bs.biblio = b.id
    left join contributo c on bs.contributo = c.id
    WHERE bs.scheda = ".$id."
    ORDER BY anno, autore, titolo asc;";
    return $this->simple($sql);
  }
  public function getBiblioFake(int $scheda){
    $sql = "select * from biblio_fake where scheda not in(select scheda from biblio_scheda) and scheda = ".$scheda." order by riferimento asc";
    return $this->simple($sql);
  }
  public function addBiblioFake(array $dati){
    try {
      $this->begin();
      foreach ($dati as $item) {
        $sql = $this->buildInsert('biblio_fake',$item);
        $this->prepared($sql,$item);
      }
      $this->commit();
      return array("ok",1);
    } catch (\Exception $e) {
      $this->rollback();
      return $e->getMessage();
    }

  }
  public function getFoto(int $id = null){
    $filter = $id !== null ? "and scheda = ".$id : '';
    return $this->simple("select * from file where tipo = 3 ".$filter.";");
  }

  private function sezScheda(int $id){
    $sql = "select s.id,
              s.titolo,
              tsk.id as tskid,
              tsk.value as tsk,
              concat(lir.tipo,' - ', lir.definizione) as lir,
              concat(u.nome,' ',u.cognome) as cmpn,
              u.id as cmpid,
              s.cmpd,
              fur.id as furid,
              concat(fur.nome,' ',fur.cognome) as fur,
              nctn.nctn,
              i.id inventarioid,
              i.prefisso,
              i.inventario,
              i.suffisso
            from scheda s
            inner join liste.tsk on s.tsk = tsk.id
            inner join liste.lir on s.lir = lir.id
            inner join utenti u on s.cmpn = u.id
            inner join utenti fur on s.fur = fur.id
            inner join nctn_scheda ns on ns.scheda = s.id
            inner join nctn on ns.nctn = nctn.nctn
            left join inventario i on i.scheda = s.id
            where s.id = ".$id.";";
    $res = $this->simple($sql);
    return $res[0];
  }
  private function sezOg(int $id, int $tsk){
    if($tsk==1){
      //RA
      $sql = "select l1.value as cls1, l2.value as cls2,l3.id as cls3id, l3.value as cls3, l4.id as cls4id, l4.value as cls4,l5.id as cls5id, l5.value as cls5, ogtt from og_ra og inner join liste.ra_cls_l4 l4 on og.l4 = l4.id inner join liste.ra_cls_l3 l3 on og.l3 = l3.id inner join liste.ra_cls_l2 l2 on l3.l2 = l2.id inner join liste.ra_cls_l1 l1 on l2.l1 = l1.id left join liste.ra_cls_l5 l5 on og.l5 = l5.id where og.scheda = ".$id.";";
    }else {
      //NU
      $sql = "select ogtd.value as ogtd, ogtd.id as ogtdid, ogr.value as ogr, ogr.id as ogrid, og.ogtt, og.ogth, og.ogtl, ogto.id as ogtoid, ogto.value as ogto, ogts.id as ogtsid, ogts.value as ogts, ogtr.id as ogtrid, ogtr.value as ogtr from og_nu og inner join liste.ogr on og.ogr = ogr.id inner join liste.ogtd on og.ogtd = ogtd.id left join liste.ogto on og.ogto = ogto.id left join liste.ogts on og.ogts = ogts.id left join liste.ogtr on og.ogtr = ogtr.id where og.scheda = ".$id.";";
    }
    $res = $this->simple($sql);
    return $res[0];
  }
  private function sezGeoloc(int $id){
    $sql = "select c.id as id_comune, c.comune, g.via, g.geonote from geolocalizzazione g inner join comuni c on g.comune = c.id where g.scheda = ".$id.";";
    $res = $this->simple($sql);
    return $res[0];
  }
  private function sezGp(int $id){
    $out=[];
    $sql ="select gp.gpl as gplid, gpl.value as gpl, gp.gpdpx, gp.gpdpy, gp.gpm as gpmid, gpm.value as gpm, gp.gpt as gptid, gpt.value as gpt, gp.gpp as gppid, gpp.value as gpp, gpp.epsg, gp.gpbb, gp.gpbt from gp inner join liste.gpl on gp.gpl = gpl.id inner join liste.gpm on gp.gpm = gpm.id inner join liste.gpt on gp.gpt = gpt.id inner join liste.gpp on gp.gpp = gpp.id where gp.scheda = ".$id.";";
    $gp = $this->simple($sql);
    return $gp[0];
  }

  private function sezMt(int $id){
    $out = [];
    $mtc ="select mtc.materia materiaid, materia.value as materia, mtc.tecnica from mtc inner join liste.materia on mtc.materia = materia.id where mtc.scheda = ".$id.";";
    $out['mtc'] = $this->simple($mtc);
    $mis = $this->simple("select * from mis where scheda = ".$id.";");
    $out['mis'] = $mis[0];
    $munsell = $this->simple("select m.* from liste.munsell m, munsell where munsell.munsell = m.id and munsell.scheda = ".$id.";");
    $out['munsell'] = $munsell[0];
    return $out;
  }

  private function sezLc(int $id){
    $sql ="select comune.pvcc, ldc.ldcn, lc.piano,lista.id id_sala, lista.sala, lc.contenitore, lc.colonna, lc.ripiano, lc.cassetta from lc INNER JOIN liste.pvcc comune on lc.pvcc = comune.codice INNER JOIN liste.sale lista on lc.sala = lista.id INNER JOIN ldc on lc.ldc = ldc.id where lc.scheda = ".$id.";";
    $lc = $this->simple($sql);
    return $lc[0];
  }

  public function getScheda(int $id){
    $out=[];
    $out['scheda'] = $this->sezScheda($id);
    $out['og'] = $this->sezOg($id,$out['scheda']['tskid']);
    $out['geoloc'] = $this->sezGeoloc($id);
    $out['gp'] = $this->sezGp($id);
    $out['lc'] = $this->sezLc($id);

    $sql ="select ub.invn, ub.stis, ub.stid, stim.value stim, ub.stim as idstim from ub left join liste.stim on ub.stim = stim.id where ub.scheda = ".$id.";";
    $ub = $this->simple($sql);
    $out['ub'] = $ub[0];

    $sql ="select nucn, rcga, rcgd, rcgz from rcg where rcg.scheda = ".$id.";";
    $rcg = $this->simple($sql);
    $out['re']['rcg'] = $rcg[0];

    $sql ="select nucn, scan, dscf, dsca, dscd, dscn from dsc where dsc.scheda = ".$id.";";
    $dsc = $this->simple($sql);
    $out['re']['dsc'] = $dsc[0];

    $sql ="SELECT ain.aint aintid, aint.value aint, ain.aind, ain.ains from ain inner join liste.aint on ain.aint = aint.id where ain.scheda = ".$id.";";
    $ain = $this->simple($sql);
    $out['re']['ain'] = $ain[0];

    $sql = "SELECT ci.id as ciid, ci.value as ci, cf.id as cfid, cf.value as cf, dt.dtzs dtzsid, dtzs.value dtzs, dt.dtsi, dt.dtsf from dt inner join liste.cronologia ci on dt.dtzgi = ci.id inner join liste.cronologia cf on dt.dtzgf = cf.id left join liste.dtzs on dt.dtzs = dtzs.id where dt.scheda = ".$id.";";
    $dt = $this->simple($sql);
    $out['dt']['dt'] = $dt[0];
    $sql = "SELECT dtm.dtm dtmid, val.value dtm from dtm inner join liste.dtm val on dtm.dtm = val.id where dtm.scheda = ".$id." order by dtm asc;";
    $out['dt']['dtm'] = $this->simple($sql);


    $out['mt'] = $this->sezMt($id);

    $sql ="select * from da where da.scheda = ".$id.";";
    $da = $this->simple($sql);
    $out['da'] = $da[0];

    $sql ="SELECT stcc.id stccid, stcc.value stcc, stcl.id stclid, stcl.value stcl from co INNER JOIN liste.stcc on co.stcc = stcc.id INNER JOIN liste.stcl on co.stcl = stcl.id where co.scheda = ".$id.";";
    $co = $this->simple($sql);
    $out['co'] = $co[0];

    $sql ="select acqt.id acqtid, acqt.value acqt, acqn, acqd, acql from tu inner join liste.acqt on tu.acqt = acqt.id where tu.scheda = ".$id.";";
    $acq = $this->simple($sql);
    $out['tu']['acq'] = $acq[0];
    $sql ="select cdgg.id cdggid, cdgg.value cdgg from tu inner join liste.cdgg on tu.cdgg = cdgg.id where tu.scheda = ".$id.";";
    $cdg = $this->simple($sql);
    $out['tu']['cdg'] = $cdg[0];
    $sql ="select nvct.id nvctid, nvct.value nvct, nvce from tu inner join liste.nvct on tu.nvct = nvct.id where tu.scheda = ".$id.";";
    $nvc = $this->simple($sql);
    $out['tu']['nvc'] = $nvc[0];

    $sql ="select ad.adsp adspid, adsp.value adsp, ad.adsm adsmid, adsm.value adsm from ad inner join liste.adsp on ad.adsp = adsp.id inner join liste.adsm on ad.adsm = adsm.id where ad.scheda = ".$id.";";
    $ad = $this->simple($sql);
    $out['ad'] = $ad[0];

    $sql ="select oss from an where an.scheda = ".$id.";";
    $an = $this->simple($sql);
    $out['an'] = $an[0];
    return $out;
  }

  public function cloneScheda(array $dati){
    $this->begin();
    $clonata = $dati['scheda']['scheda'];
    unset($dati['scheda']['scheda']);
    // unset($dati['nctn_scheda']['old_nctn']);

    $schedaSql = $this->buildInsert('scheda',$dati['scheda']);
    $schedaSql = rtrim($schedaSql, ";") . " returning id;";
    $schedaId = $this->returning($schedaSql,$dati['scheda']);
    $this->prepared("insert into stato_scheda(scheda) values (:scheda);", array("scheda"=>$schedaId['id']));

    if (isset($dati['nctn_scheda']['nctn'])) {
      $nctn = $dati['nctn_scheda']['nctn'];
      $this->addSection('nctn_scheda', $schedaId['id'], $dati['nctn_scheda']);
      $this->setNctn(array("nctn"=>$dati['nctn_scheda']['nctn'],"libero" => 'f'));
    }else {
      $nctn = $this->getNctn();
      $this->addSection('nctn_scheda', $schedaId['id'], array("nctn"=>$nctn['nctn']));
      $this->setNctn(array("nctn"=>$nctn['nctn'],"libero" => 'f'));
    }

    if (isset($dati['inventario'])) {
      unset($dati['inventario']['old_inventario']);
      $dati['inventario']['scheda'] = $schedaId['id'];
      $invSql = $this->buildInsert('inventario',$dati['inventario']);
      $this->prepared($invSql,$dati['inventario']);
    }

    $this->addSection('ad', $schedaId['id'], $dati['ad']);
    $this->addSection('co', $schedaId['id'], $dati['co']);
    $this->addSection('da', $schedaId['id'], $dati['da']);
    $this->addSection('dt', $schedaId['id'], $dati['dt']);
    $this->addSection('lc', $schedaId['id'], $dati['lc']);
    $this->addSection('mis', $schedaId['id'], $dati['mis']);
    $this->addSection('tu', $schedaId['id'], $dati['tu']);

    foreach ($dati['dtm'] as $value) {$this->addSection('dtm',$schedaId['id'],array("dtm"=>(int)$value));}
    foreach ($dati['mtc'] as $val) {
      $datiMtc = array('materia'=>$val['materia'], 'tecnica'=>$val['tecnica']);
      $this->addSection('mtc', $schedaId['id'], $datiMtc);
    }
    if(isset($dati['vie'])) {
      $sql = $this->buildInsert('vie',$dati['vie']);
      $this->prepared($sql,$dati['vie']);
    }
    if(isset($dati['geolocalizzazione'])) {
      if(isset($dati['vie'])) {$dati['geolocalizzazione']['via'] = $dati['vie']['osm_id'];}
      $this->addSection('geolocalizzazione', $schedaId['id'], $dati['geolocalizzazione']);
    }
    if(isset($dati['og_ra'])) {$this->addSection('og_ra', $schedaId['id'], $dati['og_ra']);}
    if(isset($dati['og_nu'])) {$this->addSection('og_nu', $schedaId['id'], $dati['og_nu']);}
    if(isset($dati['ub'])) {$this->addSection('ub', $schedaId['id'], $dati['ub']);}
    if(isset($dati['gp'])) {$this->addSection('gp', $schedaId['id'], $dati['gp']);}
    if(isset($dati['rcg'])) {$this->addSection('rcg', $schedaId['id'], $dati['rcg']);}
    if(isset($dati['dsc'])) {$this->addSection('dsc', $schedaId['id'], $dati['dsc']);}
    if(isset($dati['ain'])) {$this->addSection('ain', $schedaId['id'], $dati['ain']);}
    if(isset($dati['munsell'])) {$this->addSection('munsell', $schedaId['id'], $dati['munsell']);}
    if(isset($dati['an'])) {$this->addSection('an', $schedaId['id'], $dati['an']);}

    //Bibliografia
    $biblioCheck = $this->simple("select count(*) from biblio_scheda where scheda = ".$clonata.";");
    if($biblioCheck[0]['count'] > 0){
      $x = $this->simple('insert into biblio_scheda(scheda, biblio, pagine, figure, livello, contributo) select '.$schedaId['id'].', biblio, pagine, figure, livello, contributo from biblio_scheda where scheda = '.$clonata.';');
      if(!$x){throw new \Exception($x['msg'], 1);}
    }

    $this->commit();
    return array("res"=>true,"msg"=>'La scheda è stata correttamente clonata.', "scheda"=>$schedaId['id'], "nctn"=>$nctn['nctn'], "pdo"=>$x['msg']);
  }

  public function addScheda(array $dati){
    // return $dati;
    try {
      $this->begin();
      $schedaSql = $this->buildInsert('scheda',$dati['scheda']);
      $schedaSql = rtrim($schedaSql, ";") . " returning id;";
      $schedaId = $this->returning($schedaSql,$dati['scheda']);
      $this->prepared("insert into stato_scheda(scheda) values (:scheda);", array("scheda"=>$schedaId['id']));
      if (isset($dati['nctn_scheda'])) {
        $nctn = $dati['nctn_scheda'];
        $this->addSection('nctn_scheda', $schedaId['id'], $dati['nctn_scheda']);
        $this->setNctn(array("nctn"=>$dati['nctn_scheda']['nctn'],"libero" => 'f'));
      }else {
        $nctn = $this->getNctn();
        $this->addSection('nctn_scheda', $schedaId['id'], array("nctn"=>$nctn['nctn']));
        $this->setNctn(array("nctn"=>$nctn['nctn'],"libero" => 'f'));
      }
      if (isset($dati['inventario'])) {
        $dati['inventario']['scheda'] = $schedaId['id'];
        $invSql = $this->buildInsert('inventario',$dati['inventario']);
        $this->prepared($invSql,$dati['inventario']);
      }
      $this->addSection('ad', $schedaId['id'], $dati['ad']);
      $this->addSection('co', $schedaId['id'], $dati['co']);
      $this->addSection('da', $schedaId['id'], $dati['da']);
      $this->addSection('dt', $schedaId['id'], $dati['dt']);
      $this->addSection('lc', $schedaId['id'], $dati['lc']);
      $this->addSection('mis', $schedaId['id'], $dati['mis']);
      $this->addSection('tu', $schedaId['id'], $dati['tu']);
      foreach ($dati['dtm'] as $value) {$this->addSection('dtm',$schedaId['id'],array("dtm"=>(int)$value));}
      foreach ($dati['mtc'] as $val) {
        $datiMtc = array('materia'=>$val['materia'], 'tecnica'=>$val['tecnica']);
        $this->addSection('mtc', $schedaId['id'], $datiMtc);
      }
      if(isset($dati['vie'])) {
        $sql = $this->buildInsert('vie',$dati['vie']);
        $this->prepared($sql,$dati['vie']);
      }
      if(isset($dati['geolocalizzazione'])) {
        if(isset($dati['vie'])) {$dati['geolocalizzazione']['via'] = $dati['vie']['osm_id'];}
        $this->addSection('geolocalizzazione', $schedaId['id'], $dati['geolocalizzazione']);
      }
      if(isset($dati['og_ra'])) {$this->addSection('og_ra', $schedaId['id'], $dati['og_ra']);}
      if (isset($dati['og_nu'])) {$this->addSection('og_nu', $schedaId['id'], $dati['og_nu']);}
      if (isset($dati['ub'])) {$this->addSection('ub', $schedaId['id'], $dati['ub']);}
      if (isset($dati['gp'])) {$this->addSection('gp', $schedaId['id'], $dati['gp']);}
      if (isset($dati['rcg'])) {$this->addSection('rcg', $schedaId['id'], $dati['rcg']);}
      if (isset($dati['dsc'])) {$this->addSection('dsc', $schedaId['id'], $dati['dsc']);}
      if (isset($dati['ain'])) {$this->addSection('ain', $schedaId['id'], $dati['ain']);}
      if (isset($dati['munsell'])) {$this->addSection('munsell', $schedaId['id'], $dati['munsell']);}
      if (isset($dati['an'])) {$this->addSection('an', $schedaId['id'], $dati['an']);}
      $this->commit();
      return array("res"=>true,"msg"=>'La scheda è stata correttamente salvata.<br/>Inserisci un nuovo record o accedi alla pagina di visualizzazione della scheda creata, dalla quale sarà possibile aggiungere bibliografia, file o immagini, e dalla quale sarà possibile duplicare i dati per creare nuove schede più velocemente', "scheda"=>$schedaId['id'], "nctn"=>$nctn['nctn']);
    } catch (\Exception $e) {
      return array("res"=>false,"msg"=>$e->getMessage());
    }
  }

  public function editScheda(array $dati){
      $this->begin();
      $scheda = $dati['scheda']['scheda'];
      unset($dati['scheda']['scheda']);

      $filtroId = ["id"=>$scheda];
      $filtroScheda = ["scheda"=>$scheda];

      $schedaSql = $this->buildUpdate('scheda',$filtroId,$dati['scheda']);
      $this->prepared($schedaSql,$dati['scheda']);

      if (isset($dati['nctn_scheda']['nctn']) && $dati['nctn_scheda']['nctn'] !== $dati['nctn_scheda']['old_nctn']) {
        $this->setNctn(array("nctn"=>$dati['nctn_scheda']['old_nctn'],"libero" => 't'));
        $this->setNctn(array("nctn"=>$dati['nctn_scheda']['nctn'],"libero" => 'f'));
        unset($dati['nctn_scheda']['old_nctn']);
        $nctnSql = $this->buildUpdate('nctn_scheda',$filtroScheda,$dati['nctn_scheda']);
        $this->prepared($nctnSql,$dati['nctn_scheda']);
      }
      if(!isset($dati['nctn_scheda']['nctn'])) {
        $nctn = $this->getNctn();
        $this->setNctn(array("nctn"=>$nctn['nctn'],"libero" => 't'));
        $nctnSql = $this->buildUpdate('nctn_scheda',$filtroScheda,array("nctn"=>$nctn['nctn']));
        $this->prepared($nctnSql,array("nctn"=>$nctn['nctn']));
      }

      if (!isset($dati['inventario']['old_inventario']) && strlen(trim($dati['inventario']['inventario'])) > 0) {
        $dati['inventario']['scheda'] = $scheda;
        $invSql = $this->buildInsert('inventario',$dati['inventario']);
        $x = $this->prepared($invSql,$dati['inventario']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }
      if (isset($dati['inventario']['old_inventario']) && strlen(trim($dati['inventario']['inventario'])) > 0){
        $filtro = array("id"=>$dati['inventario']['old_inventario']);
        unset($dati['inventario']['old_inventario']);
        $dati['inventario']['prefisso'] = isset($dati['inventario']['prefisso']) ? $dati['inventario']['prefisso'] : null;
        $dati['inventario']['suffisso'] = isset($dati['inventario']['suffisso']) ? $dati['inventario']['suffisso'] : null;
        $invSql = $this->buildUpdate("inventario", $filtro, $dati['inventario']);
        $x = $this->prepared($invSql,$dati['inventario']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }
      if (isset($dati['inventario']['old_inventario']) && strlen(trim($dati['inventario']['inventario'])) == 0){
        $invSql = "delete from inventario where id=:id;";
        $x = $this->prepared($invSql,array("id"=>$dati['inventario']['old_inventario']));
        if(!$x){throw new \Exception($x['msg'], 1);}
      }

      if(isset($dati['vie'])) {
        $sql = $this->buildInsert('vie',$dati['vie']);
        $this->prepared($sql,$dati['vie']);
      }
      if(isset($dati['geolocalizzazione'])) {
        if(isset($dati['vie'])) {$dati['geolocalizzazione']['via'] = $dati['vie']['osm_id'];}
        $this->updateSection('geolocalizzazione', $scheda, $dati['geolocalizzazione']);
      }


      if(isset($dati['og_ra'])){
        $dati['og_ra']['ogtt'] = strlen(trim($dati['og_ra']['ogtt'])) == 0 ? null : $dati['og_ra']['ogtt'];
        $dati['og_ra']['l5'] = isset($dati['og_ra']['l5']) ? $dati['og_ra']['l5'] : null;
        $sql = $this->buildUpdate("og_ra", $filtroScheda, $dati['og_ra']);
        $x = $this->prepared($sql,$dati['og_ra']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }
      if(isset($dati['og_nu'])){
        $dati['og_nu']['ogtt'] = strlen(trim($dati['og_nu']['ogtt'])) == 0 ? null : $dati['og_nu']['ogtt'];
        $dati['og_nu']['ogth'] = strlen(trim($dati['og_nu']['ogth'])) == 0 ? null : $dati['og_nu']['ogth'];
        $dati['og_nu']['ogtl'] = strlen(trim($dati['og_nu']['ogtl'])) == 0 ? null : $dati['og_nu']['ogtl'];

        $dati['og_nu']['ogto'] = isset($dati['og_nu']['ogto']) ? $dati['og_nu']['ogto'] : null;
        $dati['og_nu']['ogts'] = isset($dati['og_nu']['ogts']) ? $dati['og_nu']['ogts'] : null;
        $dati['og_nu']['ogtr'] = isset($dati['og_nu']['ogtr']) ? $dati['og_nu']['ogtr'] : null;

        $sql = $this->buildUpdate("og_nu", $filtroScheda, $dati['og_nu']);
        $x = $this->prepared($sql,$dati['og_nu']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }

      if (isset($dati['lc'])) {
        $dati['lc']['contenitore'] = isset($dati['lc']['contenitore']) ? $dati['lc']['contenitore'] : null;
        $dati['lc']['colonna'] = isset($dati['lc']['colonna']) ? $dati['lc']['colonna'] : null;
        $dati['lc']['ripiano'] = isset($dati['lc']['ripiano']) ? $dati['lc']['ripiano'] : null;
        $dati['lc']['cassetta'] = isset($dati['lc']['cassetta']) ? $dati['lc']['cassetta'] : null;
        $sql = $this->buildUpdate("lc", $filtroScheda, $dati['lc']);
        $x = $this->prepared($sql,$dati['lc']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }

      // UB
      $ubExists = $this->simple("select count(*) from ub where scheda = ".$scheda.";");
      if ($ubExists[0]['count'] == 0 && isset($dati['ub'])) { $this->addSection('ub', $scheda, $dati['ub']); }
      if ($ubExists[0]['count'] > 0 && isset($dati['ub'])) { $this->updateSection('ub', $scheda, $dati['ub']); }
      if ($ubExists[0]['count'] > 0 && !isset($dati['ub'])) { $this->delSection('ub', $scheda); }
      // GP
      $gpExists = $this->simple("select count(*) from gp where scheda = ".$scheda.";");
      if ($gpExists[0]['count'] == 0 && isset($dati['gp'])) { $this->addSection('gp', $scheda, $dati['gp']); }
      if ($gpExists[0]['count'] > 0 && isset($dati['gp'])) { $this->updateSection('gp', $scheda, $dati['gp']); }
      if ($gpExists[0]['count'] > 0 && !isset($dati['gp'])) { $this->delSection('gp', $scheda); }
      // RCG
      $rcgExists = $this->simple("select count(*) from rcg where scheda = ".$scheda.";");
      if ($rcgExists[0]['count'] == 0 && isset($dati['rcg'])) { $this->addSection('rcg', $scheda, $dati['rcg']); }
      if ($rcgExists[0]['count'] > 0 && isset($dati['rcg'])) { $this->updateSection('rcg', $scheda, $dati['rcg']); }
      if ($rcgExists[0]['count'] > 0 && !isset($dati['rcg'])) { $this->delSection('rcg', $scheda); }
      // DSC
      $dscExists = $this->simple("select count(*) from dsc where scheda = ".$scheda.";");
      if ($dscExists[0]['count'] == 0 && isset($dati['dsc'])) { $this->addSection('dsc', $scheda, $dati['dsc']); }
      if ($dscExists[0]['count'] > 0 && isset($dati['dsc'])) { $this->updateSection('dsc', $scheda, $dati['dsc']); }
      if ($dscExists[0]['count'] > 0 && !isset($dati['dsc'])) { $this->delSection('dsc', $scheda); }
      // AIN
      $ainExists = $this->simple("select count(*) from ain where scheda = ".$scheda.";");
      if ($ainExists[0]['count'] == 0 && isset($dati['ain'])) { $this->addSection('ain', $scheda, $dati['ain']); }
      if ($ainExists[0]['count'] > 0 && isset($dati['ain'])) { $this->updateSection('ain', $scheda, $dati['ain']); }
      if ($ainExists[0]['count'] > 0 && !isset($dati['ain'])) { $this->delSection('ain', $scheda); }
      // DTZ
      $dtzs = isset($dati['dt']['dtzs']) ? $dati['dt']['dtzs'] : null;
      $dtzData = array("dtzgi"=>$dati['dt']['dtzgi'],"dtzgf"=>$dati['dt']['dtzgf'], "dtzs"=>$dtzs);
      $this->updateSection('dt', $scheda, $dtzData);
      // DTS
      if (isset($dati['dt']['dtsi'])) { $this->updateSection('dt', $scheda, array("dtsi" => $dati['dt']['dtsi'],"dtsf" => $dati['dt']['dtsf'])); }
      if (!isset($dati['dt']['dtsi'])) { $this->updateSection('dt', $scheda, array("dtsi" => null,"dtsf" => null)); }
      // DTM
      $this->delSection('dtm', $scheda);
      foreach ($dati['dtm'] as $value) {$this->addSection('dtm',$scheda,array("dtm"=>(int)$value));}
      // MTC
      $this->delSection('mtc', $scheda);
      foreach ($dati['mtc'] as $val) {
        $datiMtc = array('materia'=>$val['materia'], 'tecnica'=>$val['tecnica']);
        $this->addSection('mtc', $scheda, $datiMtc);
      }
      // MIS
      if (isset($dati['mis']['misr'])) {
        $misr = $dati['mis']['misr'];
        $misa = $misl = $misp = $misd = $misn = $miss = $misg = $misv = null;
      }else {
        $misr = null;
        $misa = isset($dati['mis']['misa']) ? $dati['mis']['misa'] : null;
        $misl = isset($dati['mis']['misl']) ? $dati['mis']['misl'] : null;
        $misp = isset($dati['mis']['misp']) ? $dati['mis']['misp'] : null;
        $misd = isset($dati['mis']['misd']) ? $dati['mis']['misd'] : null;
        $misn = isset($dati['mis']['misn']) ? $dati['mis']['misn'] : null;
        $miss = isset($dati['mis']['miss']) ? $dati['mis']['miss'] : null;
        $misg = isset($dati['mis']['misg']) ? $dati['mis']['misg'] : null;
        $misv = isset($dati['mis']['misv']) ? $dati['mis']['misv'] : null;
      }
      $misData = array("misr" => $misr, "misa" => $misa, "misl" => $misl, "misp" => $misp, "misd" => $misd, "misn" => $misn, "miss" => $miss, "misg" => $misg, "misv" => $misv);
      $this->updateSection('mis', $scheda, $misData);
      // MUNSELL
      $munsellExists = $this->simple("select count(*) from munsell where scheda = ".$scheda.";");
      if ($munsellExists[0]['count'] == 0 && isset($dati['munsell'])) { $this->addSection('munsell', $scheda, $dati['munsell']); }
      if ($munsellExists[0]['count'] > 0 && isset($dati['munsell'])) { $this->updateSection('munsell', $scheda, $dati['munsell']); }
      if ($munsellExists[0]['count'] > 0 && !isset($dati['munsell'])) { $this->delSection('munsell', $scheda); }
      // DES
      $this->updateSection('da', $scheda, $dati['da']);
      // STC
      $this->updateSection('co', $scheda, $dati['co']);
      // ACQ
      if (isset($dati['tu']['acqt'])) {
        $acqn = isset($dati['tu']['acqn']) ? $dati['tu']['acqn'] : null;
        $acql = isset($dati['tu']['acql']) ? $dati['tu']['acql'] : null;
        $this->updateSection('tu', $scheda, array("acqt" => $dati['tu']['acqt'], "acqn" => $acqn, "acqd" => $dati['tu']['acqd'], "acql" => $acql));
      }
      if (!isset($dati['tu']['acqt'])) { $this->updateSection('tu', $scheda, array("acqt" => null, "acqn" => null, "acqd" => null, "acql" => null)); }
      // CDG
      $this->updateSection('tu', $scheda, array("cdgg" => $dati['tu']['cdgg']));
      // NVC
      if (isset($dati['tu']['nvct'])) {
        $nvce = isset($dati['tu']['nvce']) ? $dati['tu']['nvce'] : null;
        $this->updateSection('tu', $scheda, array("nvct" => $dati['tu']['nvct'], "nvce" => $nvce));
      }
      if (!isset($dati['tu']['nvct'])) { $this->updateSection('tu', $scheda, array("nvct" => null, "nvce" => null)); }
      // AD
      $this->updateSection('ad', $scheda, $dati['ad']);
      // AN
      $ossExists = $this->simple("select count(*) from an where scheda = ".$scheda.";");
      if ($ossExists[0]['count'] == 0 && isset($dati['an'])) { $this->addSection('an', $scheda, $dati['an']); }
      if ($ossExists[0]['count'] > 0 && isset($dati['an'])) { $this->updateSection('an', $scheda, $dati['an']); }
      if ($ossExists[0]['count'] > 0 && !isset($dati['an'])) { $this->delSection('an', $scheda); }

      $this->commit();
      return array("res"=>true,"msg"=>'La scheda è stata correttamente modificata.', "pdo"=>$x['msg']);
  }

  public function delScheda(array $dati){
    $updateDati = array('libero' => true, 'nctn' => $dati['nctn']);
    $deleteDati = array('id' => $dati['id']);
    $field = array('libero' => true);
    $filter = array('nctn' => $dati['nctn']);
    $updateSql = "update nctn set libero = :libero where nctn = :nctn;";
    $deleteSql = "delete from scheda where id = :id;";
    try {
      $this->begin();
      $this->prepared($updateSql, $updateDati);
      $this->prepared($deleteSql,$deleteDati);
      $this->commit();
      return array("res"=>true,"msg"=>'La scheda è stata correttamente eliminata.');
    } catch (\Exception $e) {
      return array("res"=>false,"msg"=>$e->getMessage());
    }
  }

  public function nctnList(){ return $this->simple("select nctn from nctn where libero = true order by nctn asc;"); }
  public function furList(){ return $this->simple("select id, concat(cognome,' ',nome) fur from utenti where classe = 4 order by 2 asc;"); }
  public function munsellGroup(){ return $this->simple("select distinct gruppo from liste.munsell order by 1 asc;"); }
  public function munsellCode($group = null){
    $filter = $group == null ? '' : "where gruppo = '".$group."'";
    return $this->simple("select id, gruppo, code, color from liste.munsell ".$filter." order by 2,3 asc;");
  }
  public function ogtdSel(array $dati){
    return $this->simple("select * from liste.".$dati['tab']." where ".$dati['field']." = ".$dati['val']." order by value asc;");
  }

  private function getNctn(){
    $res = $this->simple("select min(nctn) nctn from nctn where libero = true;");
    return $res[0];
  }
  private function setNctn(array $dati){
    $filter = ["nctn"=>$dati['nctn']];
    unset($dati['nctn']);
    $sql = $this->buildUpdate('nctn',$filter,$dati);
    return $this->prepared($sql,$dati);
  }

  public function mtc(array $dati){
    $out['mtc']=$this->simple("select * from liste.materia where tsk != ".$dati['filter']." order by 3 asc;");
    $out['tcn']=$this->simple("select * from liste.tecnica where tsk != ".$dati['filter']." order by 3 asc;");
    return $out;
  }
  public function setDtzgf(array $dati){
    return $this->simple("select * from liste.cronologia where id >= ".$dati['dtzgi']." order by id asc;");
  }

  //sezione liste
  public function listeComuni(){
    $opt=[];
    $tcl=array("tab"=>"liste.la_tcl", "order"=>2);
    $tclArr=$this->vocabolari($tcl);
    $opt['tcl']=$this->buildSel($tclArr);

    $stim=array("tab"=>'liste.stim');
    $stimArr=$this->vocabolari($stim);
    $opt['stim']=$this->buildSel($stimArr);

    $dtzg=array("tab"=>'liste.cronologia', "order"=>1);
    $dtzgArr=$this->vocabolari($dtzg);
    $opt['dtzg']=$this->buildSel($dtzgArr);

    $dtzs=array("tab"=>'liste.dtzs');
    $dtzsArr=$this->vocabolari($dtzs);
    $opt['dtzs']=$this->buildSel($dtzsArr);

    $dtm=array("tab"=>'liste.dtm');
    $dtmArr=$this->vocabolari($dtm);
    $opt['dtm']=$this->buildSel($dtmArr);

    $piani = "select distinct piano id,
    CASE
      WHEN piano = -1 THEN 'Deposito'
      WHEN piano = 0 THEN 'Piano terra'
      WHEN piano = 1 THEN 'Primo piano'
      WHEN piano = 2 THEN 'Secondo piano'
    END as value
    from liste.sale
    order by piano asc;";
    $opt['piani'] = $this->simple($piani);

    $gpl=array("tab"=>'liste.gpl');
    $gplArr=$this->vocabolari($gpl);
    $opt['gpl']=$this->buildSel($gplArr);

    $gpm=array("tab"=>'liste.gpm');
    $gpmArr=$this->vocabolari($gpm);
    $opt['gpm']=$this->buildSel($gpmArr);

    $gpt=array("tab"=>'liste.gpt');
    $gptArr=$this->vocabolari($gpt);
    $opt['gpt']=$this->buildSel($gptArr);

    $gpp=array("tab"=>'liste.gpp');
    $gppArr=$this->vocabolari($gpp);
    $opt['gpp']=$this->buildSel($gppArr);

    $aint=array("tab"=>'liste.aint');
    $aintArr=$this->vocabolari($aint);
    $opt['aint']=$this->buildSel($aintArr);

    $stcc=array("tab"=>'liste.stcc');
    $stccArr=$this->vocabolari($stcc);
    $opt['stcc']=$this->buildSel($stccArr);

    $stcl=array("tab"=>'liste.stcl');
    $stclArr=$this->vocabolari($stcl);
    $opt['stcl']=$this->buildSel($stclArr);

    $cdgg=array("tab"=>'liste.cdgg');
    $cdggArr=$this->vocabolari($cdgg);
    $opt['cdgg']=$this->buildSel($cdggArr);

    $acqt=array("tab"=>'liste.acqt');
    $acqtArr=$this->vocabolari($acqt);
    $opt['acqt']=$this->buildSel($acqtArr);

    $nvct=array("tab"=>'liste.nvct');
    $nvctArr=$this->vocabolari($nvct);
    $opt['nvct']=$this->buildSel($nvctArr);

    return $opt;
  }
  public function listeRA(){
    $opt=[];
    $opt['l3'] = $this->simple("select id, value from liste.ra_cls_l3 order by 2 asc;");
    $opt['ogtd'] = $this->simple("select id, value from liste.ra_cls_l4 order by 2 asc;");
    return $opt;
  }
  public function listeNu(){
    $opt=[];
    $ogtd=array("tab"=>"liste.ogtd","filter"=>array("field"=>'tipo',"value"=>2));
    $opt['ogtd']=$this->vocabolari($ogtd);;

    $ogr=array("tab"=>'liste.ogr');
    $opt['ogr']=$this->vocabolari($ogr);

    $ogto=array("tab"=>'liste.ogto');
    $opt['ogto']=$this->vocabolari($ogto);

    $ogts=array("tab"=>'liste.ogts');
    $opt['ogts']=$this->vocabolari($ogts);

    $ogtr=array("tab"=>'liste.ogtr');
    $opt['ogtr']=$this->vocabolari($ogtr);

    return $opt;
  }

  public function buildSel(array $dati, $sel = null){
    $res=[];
    $firstOpt = $sel == null ? 'selected' : '';
    array_push($res, '<option value="" '.$firstOpt.'>-- seleziona --</option>');
    foreach ($dati as $v) {
      $selOpt = $sel == $v['id'] ? 'selected' : '';
      array_push($res,"<option value='".$v['id']."' ".$selOpt.">".$v['value']."</option>");
    }
    return $res;
  }

  public function getCompilatore(int $id){
    $sql = "select cmpn from public.cm where scheda = ".$id.";";
    return $this->simple($sql);
  }

  public function getSale(int $piano){
    $sql = "select id, sala, descrizione from liste.sale where piano = ".$piano." order by sala asc;";
    return $this->simple($sql);
  }

  public function getContenitore(array $dati){
    $f = $dati['contenitore'] == 'vetrine' ? 'vetrina' : 'scaffale';
    $id = $dati['contenitore'] == 'vetrine' ? 'a.id,' : '';
    $sql = "select distinct ".$id." a.".$f." as c, a.note from liste.".$dati['contenitore']." a, liste.sale s where a.sala = s.id and s.id = ".$dati['sala']." order by 1 asc;";
    return $this->simple($sql);
  }

  public function getColonna(array $dati){
    $sql = "select colonna as val, trim(concat(note,' ',colonna)) as colonna from liste.scaffali where sala = ".$dati['sala']." and scaffale = ".$dati['scaffale']." order by 1";
    return $this->simple($sql);
  }

  // public function liste(int $tipo){}

  public function vocabolari(array $dati){
    $where = isset($dati['filter']) ? ' where '. $dati['filter']['field'] . "=" . $dati['filter']['value'] : '';
    $order = isset($dati['order']) ? $dati['order'] : '2';
    $sql = "select * from ". $dati['tab'] . $where. " order by ".$order." asc;";
    return $this->simple($sql);
  }
  public function autocomplete(array $dati){
    $sql = "select * from ". $dati['tab'] . " where ".$dati['field']." ilike '&".$dati['val']."&' order by value asc;";
    return $this->simple($sql);
  }

  public function delBiblioScheda(array $dati){
    $sql = "delete from biblio_scheda where biblio = :biblio and scheda = :scheda;";
    $res = $this->prepared($sql, $dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

  protected function addSection(string $tab, int $scheda, array $dati){
    $dati['scheda'] = $scheda;
    $sql = $this->buildInsert($tab,$dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }
  protected function updateSection(string $tab, int $scheda, array $dati){
    $filter = array("scheda" => $scheda);
    $sql = $this->buildUpdate($tab, $filter, $dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

  protected function delSection(string $tab, int $scheda){
    $dati = array("scheda" => $scheda);
    $sql = "delete from ".$tab." where scheda = :scheda;";
    $res = $this->prepared($sql, $dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

  protected function getSection(int $scheda, string $section){}

  public function listaSchede(array $dati = null){
    $where = '';
    $filter = [];
    if($dati && !empty($dati)){
      if(isset($dati['tipo'])){array_push($filter, "tsk = ".$dati['tipo']);}
      if(isset($dati['operatore'])){array_push($filter,' cmpn = '.$dati['operatore']);}
      if(isset($dati['catalogo'])){array_push($filter," nctn::text ilike '%".$dati['catalogo']."%'");}
      if(isset($dati['inventario'])){array_push($filter," inventario ilike '%".$dati['inventario']."%'");}
      if(isset($dati['titolo'])){array_push($filter," titolo ilike '%".$dati['titolo']."%'");}
      if(isset($dati['ogtd'])){array_push($filter," ogtd ilike '%".$dati['ogtd']."%'");}
      if(isset($dati['materia'])){array_push($filter," materia ilike '%".$dati['materia']."%'");}
      if(isset($dati['tecnica'])){array_push($filter," tecnica ilike '%".$dati['tecnica']."%'");}
      $where = ' where '.join(" and ",$filter);
    }
    $sql="select * from schede ".$where;
    return $this->simple($sql);
  }
  public function filtriScheda(){
    $out=[];
    $out['schedatori'] = $this->filtroSchedatori();
    $out['ogtd'] = $this->filtroCategorie();
    $out['materia'] = $this->filtroMateria();
    $out['tecnica'] = $this->filtroTecnica();
    return $out;
  }

  public function filtroSchedatori(int $id = null){
    $where = $id != null ? ' where id = '.$id : '';
    $sql = "select distinct u.id, concat(u.cognome,' ',u.nome) compilatore from scheda s inner join utenti u on s.cmpn = u.id ".$where." order by 2 asc;";
    return $this->simple($sql);
  }
  public function filtroCategorie(int $id = null){
    $where = $id != null ? ' where id = '.$id : '';
    $sql = "select * from (SELECT distinct 2 as tsk, lower(ogtd_1.value) AS ogtd FROM og_nu JOIN liste.ogtd ogtd_1 ON og_nu.ogtd = ogtd_1.id UNION SELECT distinct 1 as tsk, lower(ogtd_1.value) AS ogtd FROM og_ra JOIN liste.ra_cls_l4 ogtd_1 ON og_ra.l4 = ogtd_1.id) ogtd ".$where." order by 2 asc";
    return $this->simple($sql);
  }
  public function filtroMateria(int $id = null){
    $where = $id != null ? ' where materia.id = '.$id : '';
    $sql = "select distinct materia.id,materia.tsk, materia.value as materia from mtc inner join liste.materia on mtc.materia = materia.id ".$where." order by 3 asc;";
    return $this->simple($sql);
  }
  public function filtroTecnica(){
    $sql = "select distinct lower(unnest(string_to_array(tecnica,', '))) tecnica from mtc order by 1 asc;";
    return $this->simple($sql);
  }

  public function tagList(){
    $sql="select tag, count(*) from work.tags group by tag order by tag asc;";
    return $this->simple($sql);
  }

  public function search(array $dati){
    $field = [
      "s.id",
      "f.file",
      "g.classe",
      "g.ogtd",
      "lc.piano",
      "lc.sala",
      "sale.descrizione nome_sala",
      "lc.contenitore"
    ];
    $join = [
      "inner join lc on lc.scheda = s.id",
      "inner join liste.sale sale on lc.sala = sale.id",
      "inner join file f on f.scheda = s.id",
      "inner join gallery g on g.id = s.id"
    ];
    $tipo = $dati['tipo'] ?? 3;
    $filter = ["f.tipo = ".$tipo];
    if(isset($dati['comune'])){
      array_push($join, "inner join geolocalizzazione geo on geo.scheda = s.id");
      array_push($filter, "geo.comune = ".$dati['comune']);
    }
    if(isset($dati['piano'])){
      array_push($filter, "lc.piano = ".$dati['piano']);
    }
    if(isset($dati['sala'])){
      array_push($filter, "lc.sala = ".$dati['sala']);
    }
    if(isset($dati['contenitore'])){
      if($dati['piano'] > -1){
        $vetrina = $this->simple("select vetrina from liste.vetrine where id = ".$dati['contenitore'].";")[0];
      }
      array_push($filter, "lc.contenitore= '".$vetrina['vetrina']."'");
    }
    if(isset($dati['nctn'])){
      array_push($join, "inner join nctn_scheda nctn on nctn.scheda = s.id");
      array_push($filter, "nctn.nctn = ".$dati['nctn']);
    }
    if(isset($dati['inventario'])){
      array_push($join, "inner join inventario on inventario.scheda = s.id");
      array_push($filter, "inventario.inventario = ".$dati['inventario']);
    }
    if(isset($dati['modello'])){
      $schede = $this->simple("select scheda from file where tipo = 1;");
      $schede = array_column($schede, 'scheda');
      array_push($filter, "s.id in (".implode(',',$schede).")");
    }
    if(isset($dati['ids']) && is_array($dati['ids'])){
      array_push($filter, "s.id in (".implode(',',$dati['ids']).")");
    }
    if(isset($dati['principale'])){
      array_push($filter, "f.foto_principale = true");
    }
    if(isset($dati['tsk'])) {
      array_push($filter, "s.tsk = ".$dati['tsk']);
      if ($dati['tsk']==1) {
        array_push($join, "inner join og_ra on og_ra.scheda = s.id");
        array_push($join, "inner join liste.ra_cls_l3 cls on og_ra.l3 = cls.id");
        array_push($join, "inner join liste.ra_cls_l4 ogtd on og_ra.l4 = ogtd.id");
        if (isset($dati['cls'])) { array_push($filter, "cls.id = ".$dati['cls']); }
        if (isset($dati['ogtd'])) { array_push($filter, "ogtd.id = ".$dati['ogtd']); }
      }
      if ($dati['tsk']==2) {
        array_push($join, "inner join og_nu ON og_nu.scheda = s.id JOIN liste.ogtd ON og_nu.ogtd = ogtd.id LEFT JOIN liste.ogto ON og_nu.ogto = ogto.id");
        if (isset($dati['ogtd'])) { array_push($filter, "ogtd.id = ".$dati['ogtd']); }
      }
      if (isset($dati['materia'])) {
        array_push($field, "m.value materia");
        array_push($join, "inner join mtc on mtc.scheda = s.id inner join liste.materia m on mtc.materia = m.id");
        array_push($filter, "m.id = ".$dati['materia']);
      }
    }
    if (isset($dati['dtzgi']) || isset($dati['dtzgf']) || isset($dati['dtsi']) || isset($dati['dtsf'])) {
      array_push($join, "inner join dt on dt.scheda = s.id");
    }
    if (isset($dati['dtzgi'])) {
      array_push($field, "dtzgi.value dtzgi");
      array_push($join, "inner join liste.cronologia dtzgi on dt.dtzgi = dtzgi.id");
      array_push($filter, "dtzgi.id >= ".$dati['dtzgi']);
    }
    if (isset($dati['dtzgf'])) {
      array_push($field, "dtzgf.value dtzgf");
      array_push($join, "inner join liste.cronologia dtzgf on dt.dtzgf = dtzgf.id");
      array_push($filter, "dtzgf.id <= ".$dati['dtzgf']);
    }
    if (isset($dati['dtsi'])) {
      array_push($field, "dt.dtsi");
      array_push($filter, "dt.dtsi >= ".$dati['dtsi']);
    }
    if (isset($dati['dtsf'])) {
      array_push($field, "dt.dtsf");
      array_push($filter, "dt.dtsf <= ".$dati['dtsf']);
    }
    if (isset($dati['fts'])) {
      $tag = preg_replace('/[^A-Za-z0-9\-]/', ' ', $dati['fts']);
      $tag = preg_replace("/\\s+/m",' | ', $tag);
      array_push($join, "inner join da on da.scheda = s.id");
      array_push($filter, "da.fts @@ to_tsquery('".$tag."') ");
    }
    if (isset($dati['tags'])) {
      array_push($join, "inner join tag on tag.scheda = s.id");
      array_push($filter, "'{".join(',',$dati['tags'])."}' <@ tag.tags ");
    }

    
    $limit='';
    $offset='';
    if(isset($dati['page']) && isset($dati['limit'])){    
      $limit = " LIMIT " . $dati['limit'];
      $offset = " OFFSET " . ($dati['page'] - 1) * $dati['limit'];
    }

    $sqlTotalItems = "select count(*) from scheda s ". join(' ', $join)." where ". join(' and ', $filter).";";
    $sql = "select ".join(',', $field)." from scheda s ". join(' ', $join)." where ". join(' and ', $filter) . $limit . $offset . ";";
    // file_put_contents('/var/www/html/marta/workfile/db/query.log', $sqlTotalItems . PHP_EOL, FILE_APPEND);
    return ["totalItems" => $this->simple($sqlTotalItems)[0], "items" => $this->simple($sql), "sql" => $sql, "dati"=>$dati];
  }
}
?>
