$(document)
.ajaxStart(function(){$("#loadingDiv").removeClass('invisible');})
.ajaxStop(function(){$("#loadingDiv").addClass('invisible');});

$(document).ready(function() {
  $(".alert").hide();
  $.ajax({ url: 'api/usr.php', type: 'POST', dataType: 'json', data: {trigger: 'classList'}})
  .done(function(data) {
    data.forEach(function(v,i){
      $("<option/>",{text:v.classe, value:v.id}).appendTo('select[name=classe]');
    })
  })
  .fail(function() { console.log("error"); });
  $("form[name=handleUser] button[type=submit]").on('click',function(e){
    sendData(e);
  });
  if ($("[name=utente]").val()) {
    $("[name=attivo]").prop('checked', false);
    $.ajax({ url: 'api/usr.php', type: 'POST', dataType: 'json', data: {trigger: 'getUser',id:$("[name=utente]").val()}})
    .done(function(data) {
      let u = data[0];
      $("[name=cognome]").val(u.cognome);
      $("[name=nome]").val(u.nome);
      $("[name=cellulare]").val(u.cellulare);
      $("[name=email]").val(u.email);
      $("[name=classe]>option[value="+u.idclasse+"]").prop('selected', true);
      var radio = u.attivo == true ? 'attiva' : 'disattiva';
      $("[name=attivo][value='"+u.attivo+"']").prop('checked', true);
    })
    .fail(function() { console.log("error"); });
  }
});

function sendData(el){
  let form = $("form[name=handleUser]");
  let alert = form.find(".alert");
  let action = form.data('action');
  alert.hide().removeClass(function (index, className) {
    return (className.match (/(^|\s)alert-\S+/g) || []).join(' ');
  });
  isvalidate = form[0].checkValidity()
  if (isvalidate) {
    el.preventDefault()
    alert.fadeIn('fast');
    let dati={}
    dati.trigger = action+'Usr';
    if ($("[name=utente]").val()) {dati.id = $("[name=utente]").val();}
    dati.cognome = $("[name=cognome]").val();
    dati.nome = $("[name=nome]").val();
    dati.cellulare = $("[name=cellulare]").val();
    dati.email = $("[name=email]").val();
    dati.classe = $("[name=classe]").val();
    dati.attivo = $("[name=attivo]:checked").val();
    $.ajax({url: 'api/usr.php', type: 'POST', dataType: 'json', data: dati})
    .done(function(data) {
      console.log(data);
      let classe = data[1] == 1 ? 'alert-success' : 'alert-danger';
      alert.addClass(classe).html(data[0]);
      if (data[1] == 1) {
        setTimeout(function() { window.location.href = "usr.php" }, 3000);
      }
    })
    .fail(function(data) {
      alert.addClass('alert-danger').html(data);
    });
  }
}
