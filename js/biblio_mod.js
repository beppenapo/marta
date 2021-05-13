const API = 'api/biblio.php';
const ID = $("[name=idScheda]").val()
let y = getData();
$("#anno").prop("max",y['y']);
$.ajax({ url: 'api/biblio.php', type: 'POST', dataType: 'json', data: {trigger: 'listaTipo'}})
.done(function(data) {
  $("<option/>",{text:'-- seleziona tipologia --', value:'', selected:true, disabled:true}).appendTo('select[name=tipo]');
  data.forEach(function(v,i){
    $("<option/>",{text:v.value, value:v.id}).appendTo('select[name=tipo]');
  })
})
.fail(function() { console.log("error"); });

$.ajax({ url: 'api/biblio.php', type: 'POST', dataType: 'json', data: {trigger: 'getScheda', id:ID} })
.done(function(data) {
  let scheda = data['scheda'];
  scheda['tipoid'] > 1 ? $(".raccoltaWrap").show() : $(".raccoltaWrap").hide();
  $("[name=tipo] option[value="+scheda['tipoid']+"]").prop('selected', true);
  $("[name=titolo_raccolta]").val(scheda['titolo_raccolta']);
  $("[name=curatore]").val(scheda['curatore']);
  $("[name=titolo]").val(scheda['titolo']);
  $("[name=autore").val(scheda['autore']);
  $("[name=altri_autori").val(scheda['altri_autori']);
  $("[name=anno").val(scheda['anno']);
  $("[name=editore").val(scheda['editore']);
  $("[name=luogo").val(scheda['luogo']);
  $("[name=isbn").val(scheda['isbn']);
  $("[name=url").prop("href",scheda['url']).val(scheda['url']);
})
.fail(function() {console.log("error");});

$("body").on('change', '[name=tipo]', function(){
  let v = $(this).val();
  if (v>1) {
    $(".raccoltaWrap").show();
    $("[name=titolo_raccolta]").prop("required",true);
  }else {
    $(".raccoltaWrap").hide();
    $("[name=titolo_raccolta]").prop("required",false);
  }
});

$('[name=submit]').on('click', function (e) {
  form = $("#modBiblioForm");
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    e.preventDefault();
    dati = {};
    dati.id = ID;
    dati.tipo = $("[name=tipo]").val();
    dati.titolo = $("[name=titolo]").val();
    dati.autore = $("[name=autore]").val();
    if ($("[name=altri_autori]").val()) {dati.altri_autori = $("[name=altri_autori]").val();}
    if ($("[name=tipo]").val() > 1) {dati.titolo_raccolta = $("[name=titolo_raccolta]").val();}
    if ($("[name=curatore]").val()) {dati.curatore = $("[name=curatore]").val();}
    if ($("[name=editore]").val()) {dati.editore = $("[name=editore]").val();}
    if ($("[name=anno]").val()) {dati.anno = $("[name=anno]").val();}
    if ($("[name=luogo]").val()) {dati.luogo = $("[name=luogo]").val();}
    if ($("[name=isbn]").val()) {dati.isbn = $("[name=isbn]").val();}
    if ($("[name=url]").val()) {dati.url = $("[name=url]").val();}
    $.ajax({
      url: API,
      type: 'POST',
      dataType: 'json',
      data: {trigger : 'editScheda', dati}
    })
    .done(function(data) {
      data.url='bibliografia.php';
      createToast(data);
    })
    .fail(function(){console.log("error");});
  }
});
