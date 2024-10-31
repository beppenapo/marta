$(document).ajaxStart(showLoading).ajaxStop(hideLoading);

const localStorageItem = 'esplora';
const svgOpt = {fit: true, center: true}

const ITEMS_PER_PAGE = 24;
const FOTO = "http://91.121.82.80/marta/file/foto/";
const WRAP = document.getElementById('wrapItems');

let totalPagesKnown = false;
let totalPages = 0;
let currentPage = 1;
let itemsLoaded = 0;
let isLoading = false;

const totalItems = document.getElementById('totalItems');
const divInitialOffset = totalItems.offsetTop;

$(document).ready(function() {
  if(checkFilters(localStorageItem) > 0){
    let checkPiano = getFilters(localStorageItem);
    if(checkPiano['piano']){
      loadSvg('img/piante/piano'+checkPiano['piano']+'.svg');
    }
    
  }
  setActiveFilters(localStorageItem);
  introMuseo()

  $("[name=piani]").on('click', function(){
    let p = $(this).val()
    $("#fuoriVetrinaTxt").hide();
    loadSvg('img/piante/piano'+p+'.svg');
    setFilters(localStorageItem,"piano",p,"update")
    setFilters(localStorageItem,"sala",true,"remove")
    setFilters(localStorageItem,"contenitore",true,"remove")
    setActiveFilters(localStorageItem);
  })
  $("#fuoriVetrinaTxt").hide();
});

function introMuseo(){
  return new Promise((resolve,reject) => {
    $.ajax({
      url: 'api/info.php',
      type: 'POST', 
      dataType: 'json', 
      data: {trigger: 'introMuseo'},
      success: function(response) {
        if (response.error){
          reject(response.error);
        }else{
          console.log(response);
          $("#totEsposto").text(response.totEsposto)
          $("#totDeposito").text(response.totDeposito)
          $("#saleP0").text(response.totSale.find(item => item.piano === 0).count)
          $("#saleP1").text(response.totSale.find(item => item.piano === 1).count)
          $("#saleP2").text(response.totSale.find(item => item.piano === 2).count)
          $("#stanze").text(response.totSale.find(item => item.piano === -1).count)
          $("#repertiP0").text(response.repertiPiano.find(item => item.piano === 0).count)
          $("#repertiP1").text(response.repertiPiano.find(item => item.piano === 1).count)
          $("#repertiP2").text(response.repertiPiano.find(item => item.piano === 2).count)
          $("#repertiP-1").text(response.repertiPiano.find(item => item.piano === -1).count)
          $("#vetrineP1").text(response.vetrine.find(item => item.piano === 1).count)
          $("#vetrineP2").text(response.vetrine.find(item => item.piano === 2).count)
          $("#scaffali").text(response.scaffali.find(item => item.piano === -1).count)
          $("#casseforti").text(response.tipoScaffale.find(item => item.note === 'cassaforte').count)
          $("#monetieri").text(response.tipoScaffale.find(item => item.note === 'monetiere').count)
          $("#repertiMonetieri").text(response.repertiCassefortiMonetieri.find(item => item.contenitore === '2').count)
          $("#repertiCasseforti").text(response.repertiCassefortiMonetieri.find(item => item.contenitore === '1').count)
          $("#fvP1").text(response.fuoriVetrina.find(item => item.piano === 1).count)
          $("#fvP2").text(response.fuoriVetrina.find(item => item.piano === 2).count)

          resolve(response);
        }
      },
      error: function(xhr, status, error) {
        reject(`Errore AJAX: ${error}`);
      }
    })
  })
}


async function loadSvg(svg){
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
          let sala = $(this).prop('id').slice(1)
          setFilters(localStorageItem,'contenitore',true,'remove')
          setFilters(localStorageItem,'piano',piano,"update")
          setFilters(localStorageItem,'sala',sala,"update")
          setActiveFilters(localStorageItem);
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
          setFilters(localStorageItem,'piano',piano,"update")
          setFilters(localStorageItem,'sala',sala,"update")
          setFilters(localStorageItem,'contenitore',contenitore,"update")
          setActiveFilters(localStorageItem);
        }
      });
    })
  });
}

function setInfo(el,info){
  let pianoInt = $("[name=piani]:checked").val()
  let pianoText = $("[name=piani]:checked").next('span').text()
  let title = pianoText; 
  let text, tipoContenitore, reperti;
  $("#resTitle, #resSubTitle").html('')
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
  // console.log(currentDataset);
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
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 60) {
    if (totalPagesKnown && currentPage < totalPages) {
      let filters = getFilters(localStorageItem);
      gallery(filters)
    }
  }

  // Controllo per il comportamento del div fisso
  if (window.scrollY >= divInitialOffset - 60) {
    totalItems.classList.add('fixed');
  } else {
    totalItems.classList.remove('fixed');
  }
})

async function setActiveFilters(item) {
  let filters = getFilters(item);
  if (filters && Object.keys(filters).length > 0) {
    $('[name=piani]').prop('checked', false);
    $('[name=piani][value="'+filters['piano']+'"]').prop('checked', true);
    // loadSvg('img/piante/piano'+filters['piano']+'.svg');
    WRAP.innerHTML=''
    currentPage = 1;
    itemsLoaded = 0;
    await gallery(filters)
  }else{
    sessionStorage.removeItem(localStorageItem);
    $("[name=piani][value=0]").prop('checked',true)
    loadSvg('img/piante/piano0.svg');
    setFilters(localStorageItem,"piano",0,"update")
    setFilters(localStorageItem,"principale","true","update")
    let filters = getFilters(localStorageItem);
    gallery(filters);
    // getSchedeByLocation('piano', {piano:init})
  }
}

