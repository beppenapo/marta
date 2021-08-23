if (window.FileReader && window.Blob) {
  $("[name=carica]").hide();
  $("#upload").on('change', function(event) {
    $("#imageResult").attr("src",'#');
    $("figcaption").text('');
    var file = event.target.files[0];
    // let size = checkSize(file.size);
    // if(size > 10){
    //   $("#msg").text("Attenzione, immagine troppo grande! Il massimo consentito è di 10mb mentre l'immagine caricata è di " + size + "mb. \n Riduci l'immagine e riprova");
    //   return;
    // }
    getHeader(encodeURI(file.name), file, checkType);
  });

} else {
  // File and Blob are not supported
  $("#msg").text("Mi dispiace ma sembra che il tuo browser non supporti il FileReader, senza il quale non è possibile leggere le immagini da caricare");
}

function getHeader(url, file, callback) {
  var fileReader = new FileReader();
  fileReader.onloadend = function(e) {
    var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
    var header = "";
    for (var i = 0; i < arr.length; i++) {
      header += arr[i].toString(16);
    }
    callback(url, file, header);
  };
  fileReader.readAsArrayBuffer(file);
}
function printImage(file) {
  var fr = new FileReader();
  fr.onloadend = function() {
    $('#imageResult').attr('src', fr.result);
    $("figcaption").text(file.name);
    $("#msg").text("Anteprima immagine");
    $("[name=carica]").fadeIn('slow');
  };
  fr.readAsDataURL(file);
}

function checkType(url, file, headerString){
  if(!checkMIME(headerString)){
    $("#msg").text("Attenzione, sono consentite solo immagini di tipo jpg o jpeg. Controlla bene l'estensione o il mimeType e riprova");
    return;
  };

  printImage(file);
  saveImg(url,file);
}
function checkSize(bytes){ return (bytes / 1000000) }
function checkMIME(header){
  return ["ffd8ffe0", "ffd8ffe1", "ffd8ffe2", "ffd8ffe3", "ffd8ffe8"].includes(header);
}
function saveImg(url, file){
  $("form[name='uploadFile']").on("submit", function(e) {
    e.preventDefault();
    var dati = new FormData();
    dati.append('file', file);
    dati.append('scheda', $("[name=scheda]").val());
    dati.append('tipo', $("[name=tipo]").val());
    dati.append('trigger', 'uploadImage');
    $.ajax({
      url: "api/file.php",
      type: "POST",
      data: dati,
      dataType: 'json',
      success: function (data) {
        data.url='schedaView.php?get='+$("[name=scheda]").val();
        data.btn = [];
        data.btn.push("<button type='button' class='btn btn-light btn-sm' name='continua'>carica nuova immagine</button>");
        data.btn.push("<a href='"+data.url+"' class='btn btn-light btn-sm'>termina inserimento</a>");
        toast(data);
        $("#msg").text('');
      },
      cache: false,
      contentType: false,
      processData: false
    });
  });
}
