let filter={};
let tags = [];
let root = parseInt($("#findWrap").width());
let item = screen.width > 1200 ? parseInt(root / 5) : parseInt(root / 3);
let dataset = [];
let pagenumber = 0;
let perpage = 30;
tagWrap(tagCerca)
getList({campo: 'dtzgi'})
getList({campo: 'dtzgf'})

$("select").on('change', function(){
  let campo = $(this).data('filter')
  let val = $(this).val();
  if(campo == 'tsk'){
    let disabled = val == 1 ? false : true;
    $("#classe").prop('disabled',disabled);
  }
  let dati = {campo: campo, val:val}
  if (val) { getList(dati)}
})

$("body").on('change', '#dtzgi', function(){
  let min = $('#dtzgi option:selected').data('start');
  let max = $('#dtzgi option:selected').data('end');
  $("#dtsi").val(min).attr({"min":min,"max":max});
  $("#dtsf").val(max).attr({"min":min});
})
$("body").on('change', '#dtzgf', function(){
  let min = $('#dtzgf option:selected').data('start');
  let max = $('#dtzgf option:selected').data('end');
  if ($("#dtzgi option:selected").val()) {
    $("#dtsi,#dtsf").attr({"max":max});
    $("#dtsf").val(max);
  }else {
    $("#dtsf").attr({"min":min,"max":max});
  }
})

$("[name=clean]").on('click', function(){
  $("[data-filter]").each(function(){
    if ($(this).is("input[type=number]") || $(this).is("input[type=search]") || $(this).is("select")){
      if (!$(this).is(':disabled')) {
        if ($(this).val()) { $(this).val(''); }
      }
    }
    if($(this).is(":checkbox:checked")){ $(this).prop('checked', false) }
    filter={};
    tags = [];
  });
  $("#tagWrap label").removeClass('active')
});

$("[name=search]").on('click', function(e){
  e.preventDefault();
  $("#searchMsg").removeClass('alert alert-danger alert-success').text('');
  if ($("#dtsi").val() && $("#dtsf").val() && parseInt($("#dtsf").val()) < parseInt($("#dtsi").val())) {
    $("#searchMsg").addClass('alert alert-danger').text("L'anno finale non può essere minore di quello iniziale");
    return false;
  }
  if ($("#fullText").val() && $.trim($("#fullText").val()).length < 2) {
    $("#searchMsg").addClass('alert alert-danger').text("Nella ricerca libera devi inserire parole di almeno 2 lettere");
    return false;
  }
  search()
})

function search(){
  $("#findWrap").html('');
  dataset = [];
  filter={};
  tags = [];

  $("[data-filter]").each(function(){
    if ($(this).is("input[type=number]") || $(this).is("input[type=search]") || $(this).is("select")){
      if (!$(this).is(':disabled')) {
        if ($(this).val()) {
          filter[$(this).data('filter')]=$(this).val();
        }
      }
    }
    if($(this).is(":checkbox:checked")){ tags.push($(this).val()) }
    if (tags.length > 0) {filter['tags']=tags}
  })
  if(Object.keys(filter).length == 0){
    $("#searchMsg").addClass('alert alert-danger').text('Per avviare una ricerca devi selezionare almeno un filtro');
    return false;
  }
  let opt = {url: 'api/scheda.php',type: 'POST', dataType: 'json', data: {trigger: 'search', dati:filter} }
  $.ajax(opt)
  .done(function(data){
    if(data.length == 0){
      $("#searchMsg").addClass('alert alert-danger').text('Nessun risultato corrispondente alla ricerca effettuata.');
      return false;
    }
    pagenumber = 0;
    $("#searchMsg").addClass('alert alert-success').text('immagini filtrate = '+data.length);
    data.forEach((item) => {dataset.push(item)});
    setTimeout(gallery(),500)
  })
  .fail(function(res) {
    console.log(res);
  });
}

function gallery(){
  console.log([pagenumber,perpage]);
  console.log(dataset);
  let i = dataset.slice(pagenumber * perpage, (pagenumber * perpage) + perpage);
  console.log(i);
  if (i.length > 0) {
    i.forEach((img) => {
      let div = $("<div/>",{class:'item'})
      .attr({"loading":"lazy"})
      .css({ "height":item})
      .appendTo('#findWrap')

      $("<div/>",{class:'itemBg animated'})
      .css({"background-image": "url("+fotoPath+img.file+")"})
      .appendTo(div)

      $("<div/>",{class:'itemTxt animated'})
      .html("<div><p>"+img.ogtd+"</p><small>"+img.classe+"</small></div>")
      .appendTo(div);

      let itemBtn = $("<div/>",{class:'itemBtn'}).appendTo(div);
      // let btnLikeDiv = $("<div/>",{class:'btnDiv btnLike', title:'aggiungi alla tua gallery'}).attr({"data-toggle":'tooltip'}).appendTo(itemBtn);
      // $("<i/>",{class:'fa-solid fa-heart text-white'}).appendTo(btnLikeDiv)
      // btnLikeDiv.on('click', function(){
      //   $(this).find('i').toggleClass('text-white text-danger');
      //   $(this).tooltip('hide')
      // })
      let btnViewDiv = $("<div/>",{class:'btnDiv btnView', title:'visualizza scheda'}).text('visualizza scheda').attr({"data-toggle":'tooltip'}).appendTo(itemBtn);
      $("<i/>",{class:'fa-solid fa-link text-white'}).prependTo(btnViewDiv)
      btnViewDiv.on('click', function(){
        $(this).tooltip('hide')
        window.location.href = 'schedaView.php?get='+img.id
      })
    });
  }
}

window.addEventListener('scroll',()=>{
  if(window.scrollY + window.innerHeight >= document.documentElement.scrollHeight){
    pagenumber++;
    gallery();
  }
})

function tagCerca(data){
  let tagContainer = $("#tagWrap");
  data.forEach((item, i) => {
    let div = $("<div/>",{class:'btn-group-toggle d-inline-block'}).attr('data-toggle','buttons').appendTo(tagContainer);
    let label = $("<label/>",{class:'btn btn-sm btn-outline-marta m-1'}).text(item.tag+' '+item.count).appendTo(div);
    $("<input/>",{type:'checkbox', value:item.tag, name:'tagBtn', class:'filtro'}).attr('data-filter','tags').appendTo(label);
  });
}

let maxCrono = [];
function getList(dati){
  $.ajax({url:'api/getList.php',type:'POST',dataType:'json',data:dati})
  .done(function(data){
    if(Object.keys(data).length==0){return false;}
    Object.keys(data).forEach(function(i,idx){
      let select = $("select[data-filter="+i+"]");
      select.html('<option value="" selected>--seleziona valore--</option>')
      data[i].forEach((item, i) => {
        let opt = $("<option>").val(item.id).text(item.value).appendTo(select)
        if(dati.campo == 'dtzgi'|| dati.campo == 'dtzgf'){
          opt.attr({"data-start":item.start, "data-end":item.end})
        }
      });
    })
  })
}

function minMax(campo){
  console.log(campo);
}
