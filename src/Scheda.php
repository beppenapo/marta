<?php
namespace Marta;
session_start();
use \Marta\Conn;
class Scheda extends Conn{
  public $db;
  function __construct(){}
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
    // return([$sql,$dati]);
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
  public function getFoto(int $id = null){
    $filter = $id !== null ? "and scheda = ".$id : '';
    return $this->simple("select * from file where tipo = 3 ".$filter.";");
  }

  private function sezScheda(int $id){
    $sql = "select s.titolo, tsk.id as tskid, tsk.value as tsk, concat(lir.tipo,' - ', lir.definizione) as lir, concat(u.nome,' ',u.cognome) as cmpn, u.id as cmpid, s.cmpd, fur.id as furid, concat(fur.nome,' ',fur.cognome) as fur, nctn.nctn,i.id inventarioid, i.prefisso,i.inventario, i.suffisso, coalesce(nullif(concat(i.prefisso,'-',i.inventario,'-',i.suffisso),'--'),'dato non inserito') inv from scheda s inner join liste.tsk on s.tsk = tsk.id inner join liste.lir on s.lir = lir.id inner join utenti u on s.cmpn = u.id inner join utenti fur on s.fur = fur.id inner join nctn_scheda ns on ns.scheda = s.id inner join nctn on ns.nctn = nctn.nctn left join inventario_scheda isc on isc.scheda = s.id left join inventario i on isc.inventario = i.id where s.id = ".$id.";";
    $res = $this->simple($sql);
    return $res[0];
  }
  private function sezOg(int $id, int $tsk){
    if($tsk==1){
      //RA
      $sql = "select l1.value as cls1, l2.value as cls2,l3.id as cls3id, l3.value as cls3, l4.id as cls4id, l4.value as cls4,l5.id as cls5id, l5.value as cls5, ogtt from og_ra og inner join liste.ra_cls_l4 l4 on og.l4 = l4.id inner join liste.ra_cls_l3 l3 on og.l3 = l3.id inner join liste.ra_cls_l2 l2 on l3.l2 = l2.id inner join liste.ra_cls_l1 l1 on l2.l1 = l1.id left join liste.ra_cls_l5 l5 on og.l5 = l5.id where og.scheda = ".$id.";";
    }else {
      //NU
      $sql = "select ogr.value as ogr, og.ogtt, og.ogth, og.ogtl, ogto.value as ogto, ogts.value as ogts, ogtr.value as ogtr, ogtd.value as ogtd from og_nu og inner join liste.ogr on og.ogr = ogr.id inner join liste.ogtd on og.ogtd = ogtd.id left join liste.ogto on og.ogto = ogto.id left join liste.ogts on og.ogts = ogts.id left join liste.ogtr on og.ogtr = ogtr.id where og.scheda = ".$id.";";
    }
    $res = $this->simple($sql);
    return $res[0];
  }
  private function sezGp(int $id){
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
    $munsell = $this->simple("select * from munsell where scheda = ".$id.";");
    $out['munsell'] = $munsell[0];
    return $out;
  }

  private function sezLc(int $id){
    $sql ="select comune.pvcc, ldc.ldcn, lc.piano, lc.sala, lc.contenitore, lc.colonna, lc.ripiano, lc.cassetta from lc INNER JOIN liste.pvcc comune on lc.pvcc = comune.codice INNER JOIN ldc on lc.ldc = ldc.id where lc.scheda = ".$id.";";
    $lc = $this->simple($sql);
    return $lc[0];
  }

  public function getScheda(int $id){
    $out=[];
    $out['scheda'] = $this->sezScheda($id);
    $out['og'] = $this->sezOg($id,$out['scheda']['tskid']);
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

  public function addScheda(array $dati){
    try {
      $this->begin();
      $schedaSql = $this->buildInsert('scheda',$dati['scheda']);
      $schedaSql = rtrim($schedaSql, ";") . " returning id;";
      $schedaId = $this->returning($schedaSql,$dati['scheda']);
      $this->prepared("insert into stato_scheda(scheda) values (:scheda);", array("scheda"=>$schedaId['field']));
      if (isset($dati['inventario'])) {
        $invSql = $this->buildInsert('inventario',$dati['inventario']);
        $invSql = rtrim($invSql, ";") . " returning id;";
        $invId = $this->returning($invSql,$dati['inventario']);
        $inv_scheda = array("scheda"=>$schedaId['field'], "inventario"=>$invId['field']);
        $sql = $this->buildInsert("inventario_scheda",$inv_scheda);
        $this->prepared($sql,$inv_scheda);
      }
      $this->addSection('ad', $schedaId['field'], $dati['ad']);
      $this->addSection('co', $schedaId['field'], $dati['co']);
      $this->addSection('da', $schedaId['field'], $dati['da']);
      $this->addSection('dt', $schedaId['field'], $dati['dt']);
      $this->addSection('lc', $schedaId['field'], $dati['lc']);
      $this->addSection('mis', $schedaId['field'], $dati['mis']);
      $this->addSection('tu', $schedaId['field'], $dati['tu']);
      foreach ($dati['dtm'] as $value) {$this->addSection('dtm',$schedaId['field'],array("dtm"=>(int)$value));}
      foreach ($dati['mtc'] as $val) {
        $datiMtc = array('materia'=>$val['materia'], 'tecnica'=>$val['tecnica']);
        $this->addSection('mtc', $schedaId['field'], $datiMtc);
      }
      if(isset($dati['og_ra'])) {$this->addSection('og_ra', $schedaId['field'], $dati['og_ra']);}
      if (isset($dati['og_nu'])) {$this->addSection('og_nu', $schedaId['field'], $dati['og_nu']);}
      if (isset($dati['ub'])) {
        unset($dati['ub']['ubDelSection']);
        unset($dati['ub']['toggleSection']);
        $this->addSection('ub', $schedaId['field'], $dati['ub']);
      }
      if (isset($dati['gp'])) {$this->addSection('gp', $schedaId['field'], $dati['gp']);}
      if (isset($dati['rcg'])) {$this->addSection('rcg', $schedaId['field'], $dati['rcg']);}
      if (isset($dati['dsc'])) {$this->addSection('dsc', $schedaId['field'], $dati['dsc']);}
      if (isset($dati['ain'])) {$this->addSection('ain', $schedaId['field'], $dati['ain']);}
      if (isset($dati['an'])) {$this->addSection('an', $schedaId['field'], $dati['an']);}
      if (isset($dati['nctn_scheda'])) {
        $nctn = $dati['nctn_scheda'];
        $this->addSection('nctn_scheda', $schedaId['field'], $dati['nctn_scheda']);
        $this->setNctn(array("nctn"=>$dati['nctn_scheda']['nctn'],"libero" => 'f'));
      }else {
        $nctn = $this->getNctn();
        $this->addSection('nctn_scheda', $schedaId['field'], array("nctn"=>$nctn['nctn']));
        $this->setNctn(array("nctn"=>$nctn['nctn'],"libero" => 'f'));
      }
      $this->commit();
      return array("res"=>true,"msg"=>'La scheda è stata correttamente salvata.<br/>Inserisci un nuovo record o accedi alla pagina di visualizzazione della scheda creata, dalla quale sarà possibile aggiungere bibliografia, file o immagini, e dalla quale sarà possibile duplicare i dati per creare nuove schede più velocemente', "scheda"=>$schedaId['field'], "nctn"=>$nctn['nctn']);
    } catch (\Exception $e) {
      return array("res"=>false,"msg"=>$e->getMessage());
    }
  }

  public function editScheda(array $dati){
    try {
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

      if (isset($dati['inventario']['old_inventario']) && !isset($dati['inventario']['inventario'])) {
        $invSql = "delete from inventario where id=:id;";
        $x = $this->prepared($invSql,array("id"=>$dati['inventario']['old_inventario']));
        if(!$x){throw new \Exception($x['msg'], 1);}
      }
      if (!isset($dati['inventario']['old_inventario']) && isset($dati['inventario']['inventario'])) {
        $invSql = $this->buildInsert('inventario',$dati['inventario']);
        $invSql = rtrim($invSql, ";") . " returning id;";
        $x = $invId = $this->returning($invSql,$dati['inventario']);
        if(!$x){throw new \Exception($x['msg'], 1);}
        $inv_scheda = array("scheda"=>$scheda, "inventario"=>$invId['field']);
        $sql = $this->buildInsert("inventario_scheda",$inv_scheda);
        $x = $this->prepared($sql,$inv_scheda);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }
      if (isset($dati['inventario']['old_inventario']) && isset($dati['inventario']['inventario'])){
        $filtro = array("id"=>$dati['inventario']['old_inventario']);
        unset($dati['inventario']['old_inventario']);
        $invSql = $this->buildUpdate("inventario", $filtro, $dati['inventario']);
        $x = $this->prepared($invSql,$dati['inventario']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }

      if(isset($dati['og_ra'])){
        $ograSql = $this->buildUpdate("og_ra", $filtroScheda, $dati['og_ra']);
        $x = $this->prepared($ograSql,$dati['og_ra']);
        if(!$x){throw new \Exception($x['msg'], 1);}
      }

      // if(isset($dati['ub']['ubSection']) && isset($dati['ub']['toggleSection']) && isset($dati['ub']['invn'])){
      //   $ubSql = "delete from ub where scheda = :scheda;";
      //   $x = $this->prepared($ubSql,array("scheda"=>$scheda));
      //   if(!$x){throw new \Exception($x['msg'], 1);}
      // }
      // if(!isset($dati['ub']['ubSection']) && isset($dati['ub']['toggleSection']) && isset($dati['ub']['invn'])){
      //   unset($dati['ub']['toggleSection']);
      //   $ubSql = $this->buildUpdate("ub", $filtroScheda, $dati['ub']);
      //   $x = $this->prepared($ubSql,$dati['ub']);
      //   if(!$x){throw new \Exception($x['msg'], 1);}
      // }
      // if(isset($dati['ub']['ubSection']) && isset($dati['ub']['toggleSection']) && !isset($dati['ub']['invn'])){
      //   unset($dati['ub']['ubSection']);
      //   unset($dati['ub']['toggleSection']);
      //   $x = $this->addSection('ub', $scheda, $dati['ub']);
      //   if(!$x){throw new \Exception($x['msg'], 1);}
      // }

      $adSql = $this->buildUpdate("ad", $filtroScheda, $dati['ad']);
      $x = $this->prepared($adSql,$dati['ad']);
      if(!$x){throw new \Exception($x['msg'], 1);}

      $coSql = $this->buildUpdate("co", $filtroScheda, $dati['co']);
      $x = $this->prepared($coSql,$dati['co']);
      if(!$x){throw new \Exception($x['msg'], 1);}

      $daSql = $this->buildUpdate("da", $filtroScheda, $dati['da']);
      $x = $this->prepared($daSql,$dati['da']);
      if(!$x){throw new \Exception($x['msg'], 1);}

      if(!isset($dati['mis']['misr'])){$dati['mis']['misr']=null;}
      $misSql = $this->buildUpdate("mis", $filtroScheda, $dati['mis']);
      $x = $this->prepared($misSql,$dati['mis']);
      if(!$x){throw new \Exception($x['msg'], 1);}

      if(!isset($dati['dt']['dtsi']) || !isset($dati['dt']['dtsf'])){
        $dati['dt']['dtsi'] = null;
        $dati['dt']['dtsf'] = null;
      }
      $dtSql = $this->buildUpdate("dt", $filtroScheda, $dati['dt']);
      $x = $this->prepared($dtSql,$dati['dt']);
      if(!$x){throw new \Exception($x['msg'], 1);}

      $lcSql = "delete from lc where scheda = :scheda";
      $x = $this->prepared($lcSql,array("scheda"=>$scheda));
      if(!$x){throw new \Exception($x['msg'], 1);}
      $this->addSection('lc', $scheda, $dati['lc']);

      $this->commit();
      // return array("res"=>true,"msg"=>'La scheda è stata correttamente modificata.');
      return array("res"=>true,"msg"=>$x, "sql"=>$misSql);
    } catch (\Exception $e) {
      return array("res"=>false,"msg"=>$e->getMessage());
    }

  }

  public function delScheda(array $dati){
    $updateDati = array('libero' => true, 'nctn' => $dati['nctn']);
    $deleteDati = array('id' => $dati['id']);
    $field = array('libero' => true);
    $filter = array('nctn' => $dati['nctn']);
    $updateSql = "update nctn set libero = :libero where nctn = :nctn;";
    $deleteSql = "delete from scheda where id = :id;";
    $this->begin();
    $res = $this->prepared($updateSql, $updateDati);
    if (!$res) { throw new \Exception($res, 1);}
    $res = $this->prepared($deleteSql,$deleteDati);
    if (!$res) { throw new \Exception($res, 1);}
    $this->commit();
    return $res;
  }

  public function nctnList(){ return $this->simple("select nctn from nctn where libero = true order by nctn asc;"); }
  public function furList(){ return $this->simple("select id, concat(cognome,' ',nome) fur from utenti where classe = 4 order by 2 asc;"); }
  public function munsellList(){ return $this->simple("select code from liste.munsell order by code asc;"); }
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
      WHEN piano = 3 THEN 'Terzo piano'
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
    $ogtdArr=$this->vocabolari($ogtd);
    $opt['ogtd']=$this->buildSel($ogtdArr);

    $ogr=array("tab"=>'liste.ogr');
    $ogrArr=$this->vocabolari($ogr);
    $opt['ogr']=$this->buildSel($ogrArr);

    $ogto=array("tab"=>'liste.ogto');
    $ogtoArr=$this->vocabolari($ogto);
    $opt['ogto']=$this->buildSel($ogtoArr);

    $ogts=array("tab"=>'liste.ogts');
    $ogtsArr=$this->vocabolari($ogts);
    $opt['ogts']=$this->buildSel($ogtsArr);

    $ogtr=array("tab"=>'liste.ogtr');
    $ogtrArr=$this->vocabolari($ogtr);
    $opt['ogtr']=$this->buildSel($ogtrArr);

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
    $sql = "select distinct ".$f." as c, note from liste.".$dati['contenitore']." where sala = ".$dati['sala']." order by 1 asc;";
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

  protected function getSection(int $scheda, string $section){}

  public function listaSchede(array $dati = null){
    $where = '';
    $filter = [];
    if($dati && !empty($dati)){
      if(isset($dati['stato'])){array_push($filter, "stato_scheda.".$dati['stato']['field']." = '".$dati['stato']['value']."'");}
      if(isset($dati['tipo'])){array_push($filter, "s.tsk = ".$dati['tipo']);}
      if(isset($dati['usr'])){array_push($filter,' s.cmpn = '.$dati['usr']);}
      $where = ' where '.join(" and ",$filter);
    }
    $sql="SELECT
      s.id
      , nctn.nctn
      , s.titolo
      , s.tsk
      , tsk.value as tipo
      , ogtd.value as ogtd
      , array_agg(m.value order by materia asc) as materia
      , dtzgi.value as dtzgi
      , dtzgf.value as dtzgf
      , lc.piano
      , loc.sala
      , loc.descrizione as nome_sala
    from scheda s
    INNER JOIN stato_scheda on stato_scheda.scheda = s.id
    INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
    INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
    INNER JOIN liste.tsk as tsk on s.tsk = tsk.id
    INNER JOIN og_nu on og_nu.scheda = s.id
    INNER JOIN liste.ogtd as ogtd on og_nu.ogtd = ogtd.id
    INNER JOIN mtc on mtc.scheda = s.id
    INNER JOIN liste.materia as m on mtc.materia = m.id
    INNER JOIN dt on dt.scheda = s.id
    INNER JOIN liste.cronologia dtzgi on dt.dtzgi = dtzgi.id
    INNER JOIN liste.cronologia dtzgf on dt.dtzgf = dtzgf.id
    INNER JOIN lc on lc.scheda = s.id
    INNER JOIN liste.sale as loc on lc.sala = loc.id
    INNER JOIN utenti u on s.cmpn = u.id
    ".$where."
    GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, ogtd.value, dtzgi.value, dtzgf.value, lc.piano, loc.sala, loc.descrizione

    UNION

    SELECT
      s.id
      , nctn.nctn
      , s.titolo
      , s.tsk
      , tsk.value as tipo
      , l4.value as ogtd
      , array_agg(m.value order by materia asc) as materia
      , dtzgi.value as dtzgi
      , dtzgf.value as dtzgf
      , lc.piano
      , loc.sala
      , loc.descrizione as nome_sala
    from scheda s
    INNER JOIN stato_scheda on stato_scheda.scheda = s.id
    INNER JOIN nctn_scheda on nctn_scheda.scheda = s.id
    INNER JOIN nctn on nctn_scheda.nctn = nctn.nctn
    INNER JOIN liste.tsk as tsk on s.tsk = tsk.id
    INNER JOIN og_ra on og_ra.scheda = s.id
    INNER JOIN liste.ra_cls_l4 as l4 on og_ra.l4 = l4.id
    INNER JOIN mtc on mtc.scheda = s.id
    INNER JOIN liste.materia as m on mtc.materia = m.id
    INNER JOIN dt on dt.scheda = s.id
    INNER JOIN liste.cronologia dtzgi on dt.dtzgi = dtzgi.id
    INNER JOIN liste.cronologia dtzgf on dt.dtzgf = dtzgf.id
    INNER JOIN lc on lc.scheda = s.id
    INNER JOIN liste.sale as loc on lc.sala = loc.id
    INNER JOIN utenti u on s.cmpn = u.id
    ".$where."
    GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, l4.value, dtzgi.value, dtzgf.value, lc.piano, loc.sala, loc.descrizione
    order by nctn asc;";
    return $this->simple($sql);
  }
}
?>
