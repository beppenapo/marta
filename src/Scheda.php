<?php
namespace Marta;
session_start();
use \Marta\Conn;
class Scheda extends Conn{
  public $db;
  function __construct(){}

  public function getScheda(int $id){
    $out=[];
    $sql = "
      select s.titolo, tsk.id as tskid, tsk.value as tsk, concat(lir.tipo,' - ', lir.definizione) as lir, concat(u.nome,' ',u.cognome) as cmpn, s.cmpd,  concat(fur.nome,' ',fur.cognome) as fur, nctn.nctn, coalesce(nullif(concat(i.prefisso,'-',i.inventario,'-',i.suffisso),'--'),'dato non inserito') inv
      from scheda s
      inner join liste.tsk on s.tsk = tsk.id
      inner join liste.lir on s.lir = lir.id
      inner join utenti u on s.cmpn = u.id
      inner join utenti fur on s.fur = fur.id
      inner join nctn_scheda ns on ns.scheda = s.id
      inner join nctn on ns.nctn = nctn.nctn
      left join inventario_scheda isc on isc.scheda = s.id
      left join inventario i on isc.inventario = i.id
      where s.id = ".$id.";";
    $scheda = $this->simple($sql);
    $out['scheda'] = $scheda[0];

    if($out['scheda']['tskid']==1){
      $sql = "select
      l1.value as cls1
      , l2.value as cls2
      , l3.value as cls3
      , l4.value as cls4
      , coalesce(l5.value,'dato non inserito') as cls5
      , coalesce(ogtt, 'dato non inserito') as ogtt
      from og_ra og
      inner join liste.ra_cls_l4 l4 on og.l4 = l4.id
      inner join liste.ra_cls_l3 l3 on og.l3 = l3.id
      inner join liste.ra_cls_l2 l2 on l3.l2 = l2.id
      inner join liste.ra_cls_l1 l1 on l2.l1 = l1.id
      left join liste.ra_cls_l5 l5 on og.l5 = l5.id
      where og.scheda = ".$id.";";
    }else {
      $out['og'] = 'og_nu';
    }
    $og = $this->simple($sql);
    $out['og'] = $og[0];

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
      if (isset($dati['ub'])) {$this->addSection('ub', $schedaId['field'], $dati['ub']);}
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
  public function setCrono(array $dati){
    $default =$this->simple("select max(dtsi) dtsi, min(dtsf) dtsf from liste.dts where dtzg = ".$dati['dtzg'].";");
    if (isset($dati['dtzs'])) {
      $complete = $this->simple("select dtsi, dtsf from liste.dts where dtzg = ".$dati['dtzg']." and dtzs = ".$dati['dtzs'].";");
    }
    if(empty($complete[0])){ return $default; }
    return $complete;
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

    return $opt;
  }
  public function listeRA(){
    $opt=[];
    $ogtd=array("tab"=>"liste.ra_cls_l4", "order"=>3);
    $ogtdArr=$this->vocabolari($ogtd);
    $opt['ogtd']=$this->buildSel($ogtdArr);

    $l3=array("tab"=>"liste.ra_cls_l3", "order"=>3);
    $l3Arr=$this->vocabolari($l3);
    $opt['l3']=$this->buildSel($l3Arr);

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

    $dtzg=array("tab"=>'liste.dtzg');
    $dtzgArr=$this->vocabolari($dtzg);
    $opt['dtzg']=$this->buildSel($dtzgArr);

    $dtzs=array("tab"=>'liste.dtzs');
    $dtzsArr=$this->vocabolari($dtzs);
    $opt['dtzs']=$this->buildSel($dtzsArr);

    $dtm=array("tab"=>'liste.dtm');
    $dtmArr=$this->vocabolari($dtm);
    $opt['dtm']=$this->buildSel($dtmArr);

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

  private function buildSel(array $dati){
    $res=[];
    foreach ($dati as $v) {
      array_push($res,"<option value='".$v['id']."'>".$v['value']."</option>");
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

  public function editScheda(array $dati){
    $this->begin();
    try {
    $id_scheda = (int)$dati['scheda']['id'];
      if($id_scheda<1){ throw new \Exception($schedaId['msg'], 1);  }
    $filter_scheda = ['id'=>$id_scheda];
      $schedaSql = $this->buildUpdate('scheda',$filter_scheda,$dati['scheda']);
    $this->prepared($schedaSql,$dati['scheda']);
      $this->editSection('og', $id_scheda, $dati['og'],1);
      $this->editSection('lc', $id_scheda, $dati['lc'],1);
      if (isset($dati['la'])) {$this->editSection('la', $id_scheda, $dati['la'],1);}
      if (isset($dati['re'])) {$this->editSection('re', $id_scheda, $dati['re'],1);}
      $this->editSection('dtz', $id_scheda, $dati['dtz'],1);
      if (isset($dati['dts'])) {$this->editSection('dts', $id_scheda, $dati['dts'],1);}
      $dtmVal = explode(',',$dati['dtm']['dtm']);
      $prim = 0; foreach ($dtmVal as $value) { $prim++; $this->editSection('dtm',$id_scheda,array("dtm"=>(int)$value),$prim);}
    $prim = 0;
      foreach ($dati['mtc'] as $val) {
    $prim++;
        $datiMtc = array('materia'=>$val['materia'], 'tecnica'=>$val['tecnica']);
        $this->editSection('mtc', $id_scheda, $datiMtc,$prim);
      }
      $this->editSection('mis', $id_scheda, $dati['mis'],1);
      $this->editSection('da', $id_scheda, $dati['da'],1);
      $this->editSection('co', $id_scheda, $dati['co'],1);
      $this->editSection('tu', $id_scheda, $dati['tu'],1);
      $this->editSection('ad', $id_scheda, $dati['ad'],1);
      if (isset($dati['ub'])) {$this->editSection('ub', $id_scheda, $dati['ub'], 1);}
      if (isset($dati['nu_do'])) {
		  if ($dati['nu_do']['ftax'] == 0) { $dati['nu_do']['ftax'] = NULL; }
		  if ($dati['nu_do']['ftap'] == 0) { $dati['nu_do']['ftap'] = NULL; }
		  $this->editSection('nu_do', $id_scheda, $dati['nu_do'], 1);
	  }
      $this->commit();
      return array("res"=>true, "msg"=>'La scheda è stata correttamente modificata', "id"=>$id_scheda);
      // return array("res"=>true, "msg"=>$out);
    } catch (\Exception $e) {
      // $this->rollback();
      return array("res"=>false, "msg"=>$e->getMessage());
    }
  }
  public function deleteScheda(int $id){}

  public function delbiblioref(int $id_scheda, int $id_biblio){
    $this->begin();
    try {
    $filter_scheda = ['scheda'=>$id_scheda, 'biblio'=>$id_biblio];
    $sqldel = $this->buildDelete('public.biblio_scheda',$filter_scheda);
    $this->prepared($sqldel);
    $this->commit();
    return array("res"=>true, "msg"=>'Il riferimento alla scheda bibliografica è stata correttamente eliminato');
    // return array("res"=>true, "msg"=>$out);
    } catch (\Exception $e) {
    // $this->rollback();
    return array("res"=>false, "msg"=>$e->getMessage());
    }
  }

  protected function addSection(string $tab, int $scheda, array $dati){
    $dati['scheda'] = $scheda;
    $sql = $this->buildInsert($tab,$dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

  protected function getSection(int $scheda, string $section){

  }

  protected function editSection(string $tab, int $scheda, array $dati, int $prim){
    $dati['scheda'] = $scheda;
    if ($prim == 1) {
      $filter_scheda = ['scheda'=>$scheda];
      $sqldel = $this->buildDelete($tab,$filter_scheda);
      $resdel = $this->prepared($sqldel);
      if (!$resdel) { throw new \Exception($resdel, 1);}
    }
    $sql = $this->buildInsert($tab,$dati);
    $res = $this->prepared($sql,$dati);
    if (!$res) { throw new \Exception($res, 1);}
    return $res;
  }

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
      , concat (dtzg.value,' ', dtzs.value) as cronologia
      , lc.piano
      , concat(loc.sala,' ', loc.descrizione) as sala
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
    INNER JOIN liste.dtzg on dt.dtzg = dtzg.id
    INNER JOIN liste.dtzs on dt.dtzs = dtzs.id
    INNER JOIN lc on lc.scheda = s.id
    INNER JOIN liste.sale as loc on lc.sala = loc.id
    INNER JOIN utenti u on s.cmpn = u.id
    ".$where."
    GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, ogtd.value, dtzg.value, dtzs.value, lc.piano, loc.sala, loc.descrizione

    UNION

    SELECT
      s.id
      , nctn.nctn
      , s.titolo
      , s.tsk
      , tsk.value as tipo
      , l4.value as ogtd
      , array_agg(m.value order by materia asc) as materia
      , concat (dtzg.value,' ', dtzs.value) as cronologia
      , lc.piano
      , concat(loc.sala,' ', loc.descrizione) as sala
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
    INNER JOIN liste.dtzg on dt.dtzg = dtzg.id
    INNER JOIN liste.dtzs on dt.dtzs = dtzs.id
    INNER JOIN lc on lc.scheda = s.id
    INNER JOIN liste.sale as loc on lc.sala = loc.id
    INNER JOIN utenti u on s.cmpn = u.id
    ".$where."
    GROUP BY s.id, nctn.nctn, s.titolo, s.tsk, tsk.value, l4.value, dtzg.value, dtzs.value, lc.piano, loc.sala, loc.descrizione
    order by nctn asc;";
    return $this->simple($sql);
  }
}
?>
