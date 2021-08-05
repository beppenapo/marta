$(".toggle-password").click(function() {
  $(this).find('i').toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).data("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
$("form .outputMsg").hide();
$('[type=submit]').on('click', function (e){
  form = $(this).data('form');
  login(e,form);
});

$("[name=cancelRescue]").on('click', function(event) {
  $("form[name=rescueForm] .outputMsg").hide().removeClass(function (index, className) {
    return (className.match (/(^|\s)text-\S+/g) || []).join(' ');
  }).html(spinner);
  $("[name=rescueEmail]").val('')
});
function login(el,f){
  let func = f == 'loginForm' ? 'login' : 'rescuePwd';
  let form = $("form[name="+f+"]");
  form.find(".outputMsg").hide().removeClass(function (index, className) {
    return (className.match (/(^|\s)text-\S+/g) || []).join(' ');
  }).html(spinner);
  isvalidate = form[0].checkValidity()
  if (isvalidate) {
    el.preventDefault()
    form.find(".outputMsg").fadeIn('fast');
    let dati={}
    dati.trigger = func;
    if (func == 'login') {
      dati.email=$("[name=email]").val()
      dati.pwd=$("[name=pwd]").val()
    }else {
      dati.email=$("[name=rescueEmail]").val()
    }
    $.ajax({url: 'api/login.php', type: 'POST', dataType: 'json', data: dati})
    .done(function(data) {
      let classe = data[1] == 1 ? 'text-success' : 'text-danger';
      form.find(".outputMsg").addClass(classe).html(data[0]);
      if (data[1] == 1) {
        setTimeout(function() { window.location.href = "dashboard.php" }, 5000);
      }
    })
    .fail(function(data) {
      form.find(".outputMsg").addClass('text-danger').html(data);
    });
  }
}
