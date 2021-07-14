$(document).ready(function() {
  $(".list-group-item > span").each(function(){
    if ($(this).text()=='dato non inserito') {
      $(this).removeClass('font-weight-bold').addClass('text-secondary')
    }
  })
});
