const API = 'api/scheda.php';
const SCHEDA = $("[name=scheda]").val();
$(document).ready(function() {
  $("#tskTxt").text('RA - Reperto Archeologico');
  $("[name=tsk]").val(1);
  $("[name=l3]").on('change',function(){
    let l3 = $(this).val();
    $("[name=l4]").prop('disabled', false);
    $("[name=l5]").prop('disabled', true);
    let data = {tab:'ra_cls_l4',field:'l3',val:l3,sel:'l4'}
    ogtdSel(data)
  })

  $("[name=l4]").on('change',function(){
    let l4 = $(this).val();
    $("[name=l5]").prop('disabled', false);
    let data = {tab:'ra_cls_l5',field:'l4',val:l4,sel:'l5'}
    ogtdSel(data)
  })

  if(window.location.pathname.split('/').pop().includes('-mod.php')){
    $("#countDesoChar").text(1000 - $("[name=deso]").val().length);
  }
  $("[name=deso]").keyup(function(){
    $("#countDesoChar").text(1000 - $(this).val().length);
  });
  gpMap();

  // NOTE: materia autocomplete
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'mtc', filter:2}
  })
  .done(function(data){
    $( "[name=materia]" ).autocomplete({
      minLength: 0,
      source: data['mtc'],
      change: function(event,ui){
        if(!ui.item){
          $(this).val('');
          $( "[name=tecnica],[name=addTecnica]" ).prop('disabled', true);
          alert('devi selezionare una materia!');
        }
        return false;
      },
      select: function(event,ui){
        $( "[name=tecnica], [name=addTecnica]" ).prop('disabled', false);
        $(this).val(ui.item.label);
        mtcWrap(ui.item);
        return false;
      }
    }).focus(function() {
      $(this).autocomplete('search', $(this).val())
    });

    $( "[name=tecnica]" ).autocomplete({
      minLength: 0,
      source: data['tcn'],
      change: function(event,ui){if(!ui.item){
        $(this).val('');
        $( "[name=addMtc]" ).prop('disabled', true);
        alert('devi selezionare un valore!');
      }},
      select: function(event,ui){
        $( "[name=tecnica]" ).val( ui.item.value );
        $( "[name=addMtc]" ).prop('disabled', false);
        return false;
      }
    }).focus(function() {
      $(this).autocomplete('search', $(this).val())
    });
  })
  .fail(function(data) { console.log(data); });
  $("[name=submit]").on('click',function(e){ salvaScheda(e); });
});

function ogtdSel(dati){
  sel = $("[name="+dati.sel+"]");
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'ogtdSel', dati:dati}
  })
  .done(function(data){
    let opts = [];
    let firstOpt = "<option value='' selected disabled>-- definizione --</option>";
    let noOpt = "<option value=''>-- nessuna specifica presente --</option>";
    if (data.length == 0) {
      opts.push(noOpt);
    } else {
      opts.push(firstOpt);
      data.forEach((item, i) => {
        let opt = "<option value='"+item.id+"'>"+item.value+"</option>";
        opts.push(opt);
      });
    }
    sel.html(opts.join(''));
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}
