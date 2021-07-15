$(document).ready(function() {
  $(".list-group-item > span").each(function(){
    if ($(this).text()=='dato non inserito') {
      $(this).removeClass('font-weight-bold').addClass('text-secondary')
    }
  })
  $("#mappa").css("height",$("#mappa").width()/2);
  if ($("[name=mapInit]").val() > 0) {mapInit()}
});

function mapInit(){
  let x = parseFloat($("[name=gpdpx]").val()).toFixed(4)
  let y = parseFloat($("[name=gpdpy]").val()).toFixed(4)
  let epsg = parseInt($("[name=epsg]").val())
  console.log([x,y,epsg]);
}
