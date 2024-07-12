let svgOpt = {
  // viewportSelector: '.svg-pan-zoom_viewport'
  // , panEnabled: true
  controlIconsEnabled: true
  // , zoomEnabled: true
  // , dblClickZoomEnabled: true
  // , mouseWheelZoomEnabled: true
  // , preventMouseEventsDefault: true
  // , zoomScaleSensitivity: 0.2
  // , minZoom: 0.5
  // , maxZoom: 10
  , fit: true
  // , contain: false
  // , center: true
  // , refreshRate: 'auto'
  // , beforeZoom: function(){}
  // , onZoom: function(){}
  // , beforePan: function(){}
  // , onPan: function(){}
  // , onUpdatedCTM: function(){}
  // , customEventsHandler: {}
  // , eventsListenerElement: null
}
let wrap = $("#findWrap");
let wrapWidth = wrap.width();
let itemMeasure = parseInt((wrapWidth / 5)-10);
let schede=[];
let pagenumber = 0;
let perpage = 20;
$(document).ready(function() {
  loadSvg('img/piante/piano1.svg');
  getSchedeByLocation('piano', {piano:1})

  $("[name=piani]").on('click', function(){
    let title;
    let p = $(this).val()
    $("#fuoriVetrinaTxt").hide();
    if(p == 0){
      $("#svgWrap").html('<div class="alert alert-danger w-75 mx-auto"><h3>La pianta di questo piano non è disponibile</h3></div>');
    }else{
      loadSvg('img/piante/piano'+p+'.svg');
    }
    getSchedeByLocation('piano',{piano:p})
  })
  $("#fuoriVetrinaTxt").hide();
});


/* www.flaticon.com */

function loadSvg(svg){
  $("#svgWrap").html('').load(svg,{},function(){
    let svgEl = svgPanZoom('#pianta', svgOpt);
    $("#pianta #sale>g").each(function(i,v){
      $(this).on({
        mouseenter: function(){
          $(this).find('path, rect').addClass('salaHover');
        },
        mouseleave: function(){
          $(this).find('path, rect').removeClass('salaHover');
        },
        click: function(){
          $("#pianta *").removeClass('salaActive').removeClass('contenitoreActive')
          $(this).find('path, rect').addClass('salaActive');
          let piano = $("[name=piani]:checked").val()
          let dati = {piano:piano,sala:$(this).prop('id').slice(1)};
          getSchedeByLocation('sala', dati)
        }
      });
    })

    $("#pianta #vetrine g > g, #pianta #scaffali g").each(function(i,v){
      $(this).on({
        mouseenter: function(){
          $(this).find('path, rect').addClass('contenitoreHover');
        },
        mouseleave: function(){
          $(this).find('path, rect').removeClass('contenitoreHover');
        },
        click: function(){
          $("#pianta *").removeClass('salaActive').removeClass('contenitoreActive')
          $(this).find('path, rect').addClass('contenitoreActive');
          let piano = $("[name=piani]:checked").val()
          let sala = $(this).prop('id').split('_')[0].slice(1);
          let contenitore = $(this).prop('id').split('_').pop().slice(1);
          let dati = {piano:piano, sala:sala, contenitore:contenitore}
          getSchedeByLocation('contenitore', dati)
        }
      });
    })
  });
}

function getSchedeByLocation(el, filter){
  if(filter && typeof filter === 'object' && !Array.isArray(filter) && Object.keys(filter).length>0){
    // window.scrollTo(500,500);
    wrap.html('');
    schede=[];
    pagenumber = 0;
    $.ajax({
      type: "POST",
      url: "api/sale.php",
      dataType: 'json',
      data: {trigger: 'getSchedeByLocation', filter:filter}
    })
    .done(function(data){
      console.log(data);
      info = {sale:data.sale.count, contenitori:data.contenitori.count}
      if(data.schede){
        if(el == 'sala'){info.sala=data.schede[0].sala;}
        if(el == 'contenitore'){
          info.sala=data.schede[0].sala;
          info.contenitore=data.schede[0].contenitore;
        }
        data.schede.forEach(function(item,i){ schede.push(item); });
        info.reperti = data.schede.length
      }
      setInfo(el,info)
      loadGallery()
    })
  }else{
    console.error('devi passare un array o non deve essere vuota');
  }
}

function setInfo(el,info){
  let pianoInt = $("[name=piani]:checked").val()
  let pianoText = $("[name=piani]:checked").next('span').text()
  let title = pianoText; 
  let text, tipoContenitore, reperti;
  $("#resTitle, #resSubTitle").html('')
  console.log(info);
  if (!info.reperti) {
    $("<div/>",{class:'alert alert-warning mx-auto text-center'}).text('Non sono presenti reperti schedati per la sala o il contenitore selezionati.').appendTo(wrap);
    return false;
  }
  if (pianoInt == -1) {
    tipoContenitore = info.contenitori == 1 ? 'scaffale' : 'scaffali';
  }else {
    tipoContenitore = info.contenitori == 1 ? 'vetrina' : 'vetrine';
  }

  reperti = info.reperti == 1 ? info.reperti + ' reperto' : info.reperti + ' reperti';
  if (info.contenitori == 0) {reperti += ' fuori vetrina';}

  if (el == 'piano') {
    text = "Il "+pianoText+" è suddiviso in <span class='font-weight-bold'>" + info.sale + " sale</span> nelle quali sono presenti <span class='font-weight-bold'>"+ info.contenitori +" "+ tipoContenitore +"</span> per un totale di <span class='font-weight-bold'>"+ reperti + '</span>';
  }

  if (el == 'sala') {
    title += ', sala '+info.sala;
    text = "Nella sala selezionata sono presenti <span class='font-weight-bold'>"+ info.contenitori +" "+ tipoContenitore +"</span> per un totale di <span class='font-weight-bold'>"+ reperti + '</span>';
  }
  if (el == 'contenitore') {
    let el = pianoInt == -1 ? 'scaffale' : 'vetrina';
    let incipit = pianoInt == -1 ? 'Nello scaffale selezionato' : 'Nella vetrina selezionata';
    title += ', sala '+info.sala+', '+el+' '+info.contenitore;
    text = incipit + " sono presenti <span class='font-weight-bold'>"+ reperti + '</span>';
  }
  $("#resTitle").html(title);
  $("#resSubTitle").html(text)
}



function loadGallery(){
  let currentDataset = schede.slice(pagenumber * perpage, (pagenumber * perpage) + perpage);
  console.log(currentDataset);
  if (currentDataset.length > 0){
    currentDataset.forEach(function(item){
      let div = $("<div/>",{class:'item bg-white shadow', title:'visualizza scheda'})
      .attr({"data-toggle":'tooltip',"loading":"lazy"})
      .css({
        "width":itemMeasure
        , "height":itemMeasure
        , "background-image": "url("+fotoPath+item.file+")"
      })
      .on('click', function(){window.location.href = 'schedaView.php?get='+item.id})
      .appendTo(wrap)
      let legenda = $("<div/>",{class:'text-center legenda'}).appendTo(div)
      $("<small/>",{class:'d-block'}).text('Piano: '+item.piano+' Sala: '+item.sala).appendTo(legenda)
      $("<small/>",{class:'d-block'}).text(item.classe).appendTo(legenda)
      $("<h5/>",{class:'p-0'}).text(item.ogtd).appendTo(legenda)
    });
  }
}

window.addEventListener('scroll',()=>{
  if(window.scrollY + window.innerHeight >= document.documentElement.scrollHeight){
    pagenumber++;
    loadGallery();
  }
})
