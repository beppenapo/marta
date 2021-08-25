$(document).ready(function() {
  const scheda = parseInt($("[name=schedaId]").val());
  const nctn = parseInt($("[name=nctnId]").val());
  $(".list-group-item > span").each(function(){
    if ($(this).text()=='dato non inserito') {
      $(this).removeClass('font-weight-bold').addClass('text-secondary')
    }
  })
  if ($("[name=mapInit]").val() > 0) {
    $("#mappa").css("height",$("#mappa").width()/2);
    mapInit()
  }

  $("[name=delBiblioScheda]").on('click', function() {
    dati = {}
    dati.biblio = $(this).data('biblio');
    dati.scheda = $(this).data('scheda');
    if (window.confirm("vuoi davvero eliminare l'associazione tra il record bibliografico scelto e la presente scheda?")) {
      delBiblioScheda(dati);
    }
  });
  $("[name=eliminaScheda]").on('click', function(event) {
    dati = {}
    dati.id = parseInt(scheda);
    dati.nctn = parseInt(nctn);
    if (window.confirm("Eliminando la scheda eliminerai anche tutti i dati collegati come file, immagini ecc.\nSei sicuro di voler procedere con l'eliminazione?")) {
      delScheda(dati);
    }
  })

  getFoto(scheda);
});

function delScheda(dati){
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'delScheda', dati:dati}
  })
  .done(function(data){
    console.log(data);
    obj={}
    obj.res=data
    obj.msg = data === true ? 'La scheda e tutti gli oggetti collegati sono stati eliminati.' : data.msg;
    obj.classe = data === true ? 'bg-success' : 'bg-danger';
    obj.url='schede.php';
    obj.btn = [];
    obj.btn.push("<a href='"+obj.url+"' class='btn btn-light btn-sm'>ok</a>");
    toast(obj);
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function delBiblioScheda(dati){
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'delBiblioScheda', dati:dati}
  })
  .done(function(data){
    obj={}
    obj.res=data
    obj.msg = data === true ? 'il riferimento bibliografico è stato eliminato' : 'Errore nella query: '+data.msg;
    obj.url = 'schedaView.php?get='+$("[name=schedaId]").val();
    obj.btn = [];
    obj.btn.push("<a href='"+obj.url+"' class='btn btn-light btn-sm'>ok</a>");
    toast(obj);
  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });
}

function mapInit(){
  let x = parseFloat($("[name=gpdpx]").val()).toFixed(4)
  let y = parseFloat($("[name=gpdpy]").val()).toFixed(4)
  let epsg = parseInt($("[name=epsg]").val())
  console.log([x,y,epsg]);
}

$("#fotoModal").hide();
function getFoto(scheda){
  $('.fotoWrap').html('');
  let wrapWidth = $(".fotoWrap").innerWidth();
  let w,folder;
  switch (true) {
    case screen.width < 768:
      w = '100%';
      folder = 'file/foto/small/';
    break;
    case screen.width < 1200:
      w = (wrapWidth / 2) - 5 +"px";
      folder = 'file/foto/medium/';
    break;
    case screen.width >= 1200:
      w = (wrapWidth / 3) - 5 +"px";
      folder = 'file/foto/large/';
    break;
  }
  $.ajax({
    url: "api/scheda.php",
    type: 'POST',
    dataType: 'json',
    data: {trigger:'getFoto', id:scheda}
  })
  .done(function(data){
    data.forEach((item, i) => {
      let div = $("<div/>",{class:'fotoDiv'}).css({"width":w,"height":w,"background-image": "url("+folder+item.file+")"});
      let overlay = $("<div/>",{class:'fotoOverlay animated'}).html('<i class="bi bi-arrows-fullscreen text-white"></i>').appendTo(div);
      div.appendTo('.fotoWrap')
      div.on('click', () => {
        $("#divImgOrig").css({"background-image":"url(file/foto/orig/"+item.file+")"});
        $("#fotoModal").fadeIn('fast');
        $("#closeModal").on('click', (e) => {
          e.preventDefault();
          $("#fotoModal").fadeOut('fast');
        })
        $("#downloadImg").attr("href","file/foto/orig/"+item.file);
        $("#delImg").on('click', (e) => {
          e.preventDefault();
          dati={id:item.id, scheda:item.scheda, file:item.file}
          if (window.confirm("Sei sicuro di voler eliminare l'immagine? Se confermi l'immagine verrà definitivamente eliminata dal server e non sarà più possibile recuperarla.")) {
            delImg(dati);
          }
        })
      })
    });

  })
  .fail(function (jqXHR, textStatus, error) {
    console.log("Post error: " + error);
  });

  function delImg(dati){
    $.ajax({
      url: "api/file.php",
      type: 'POST',
      dataType: 'json',
      data: {trigger:'delImg', dati:dati}
    })
    .done(function(data){
      obj={}
      obj.res=data
      obj.msg = data === true ? "l'immagine è stata eliminata" : 'Errore nella query: '+data.msg;
      obj.btn = [];
      obj.btn.push("<button type='button' class='btn btn-sm btn-light' name='dismiss' data-dismiss='toast'>ok</button>");
      toast(obj);
      getFoto(dati.scheda)
    })
    .fail(function (jqXHR, textStatus, error) {
      console.log("Post error: " + error);
    });
  }
}
