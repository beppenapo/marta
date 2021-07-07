const API = 'api/scheda.php';
$(document).ready(function() {
  $("#tskTxt").text('RA - Reperto Archeologico');
  $("[name=tsk]").val(1);

  // NOTE: materia autocomplete
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'mtc', filter:1}
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
});

$("[name=submit]").on('click',function(e){ salvaScheda(e); });
