var strength = {
  0: "Pessima scelta ☹",
  1: "Scelta discutibile ☹",
  2: "Password debole ☹",
  3: "Buona ☺",
  4: "Ottima scelta ☻"
}
var color = {
  0: "black",
  1: "red",
  2: "orange",
  3: "orange",
  4: "green"
}
$(document).ready(function() {
  $(".alert").hide();
  $("[name=new]").on('input', function() {
    if ($(this).val().length >= 8) {
      var result = zxcvbn($(this).val());
      $('#password-strength-meter').val(result.score);
      $('#password-strength-text').html("<span id='strength' class='d-block m-0 p-0' style='color:"+color[result.score]+"'>"+strength[result.score]+"</span><small class='text-muted'>"+result.feedback.warning+" "+result.feedback.suggestions+"</small>");
    }else {
      $('#password-strength-text').html("");
    }
  });

  $(".toggle-password").click(function() {
    $(this).find('i').toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).data("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $("form[name=handleUser] button[type=submit]").on('click',function(e){ sendData(e); });
});

function sendData(el){
  let form = $("form[name=handleUser]");
  let alert = form.find(".alert");
  alert.hide().removeClass(function (index, className) {
    return (className.match (/(^|\s)alert-\S+/g) || []).join(' ');
  });
  isvalidate = form[0].checkValidity();
  if (isvalidate) {
    el.preventDefault()
    alert.fadeIn('fast');
    let dati={}
    dati.trigger = 'modificaPassword';
    dati.id = $("[name=utente]").val();
    dati.pwd = $("[name=old]").val();
    dati.new = $("[name=new]").val();
    var confirm = $("[name=confirm]").val();
    if(dati.new !== confirm){
      alert.addClass('alert-danger').html('Attenzione, le password non coincidono!<br/>Controlla e riprova');
    }else {
      $.ajax({url: 'api/usr.php', type: 'POST', dataType: 'json', data: dati})
      .done(function(data) {
        let classe = data[1] == 1 ? 'alert-success' : 'alert-danger';
        alert.addClass(classe).html(data[0]);
        if (data[1] == 1) {
          setTimeout(function() { window.location.href = "dashboard.php" }, 3000);
        }
      })
      .fail(function(data) {
        alert.addClass('alert-danger').html(data);
      });
    }
  }
}
