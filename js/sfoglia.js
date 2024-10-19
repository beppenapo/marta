$(document).ajaxStart(showLoading).ajaxStop(hideLoading);
$(document).ready(function() {setActiveFilters();});

const ITEMS_PER_PAGE = 24;
const FOTO = "http://91.121.82.80/marta/file/foto/";
const WRAP = document.getElementById('wrapItems');
const cardTemplate = document.createElement('template');

let maxCrono = [];
let totalPagesKnown = false;
let totalPages = 0;
let currentPage = 1;
let itemsLoaded = 0;

let isLoading = false;

cardTemplate.innerHTML = `
  <div class="card">
    <div class="card-body p-0">
      <div class="img"></div>
      <div class="text">
        <h6 class="card-title"></h6>
        <p class="card-text"></p>
      </div>
    </div>
    <div class="card-footer">
      <a href="" class="btn btn-sm btn-marta text-white card-url">
        <i class="fa-solid fa-link"></i> scheda
      </a>
    </div>
  </div>
`;

$("select").on('change', function(){
  let campo = $(this).data('filter')
  let val = $(this).val();
  if(campo == 'tsk'){
    let disabled = val == 1 ? false : true;
    $("#classe").prop('disabled',disabled);
  }
  let dati = {campo: campo, val:val}
  if (val) { getList2(dati)}
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
  localStorage.removeItem("filters");
  totalPagesKnown = false;
  totalPages = 0;
  currentPage = 1;
  itemsLoaded = 0;
  WRAP.innerHTML='';
  $(this).addClass('invisible');
  $("#totalItems > h2").text('');
  $("#tagWrap label, #modelloLabel").removeClass('active')
  $("[data-filter]").each(function(){
    if ($(this).is("input[type=number], input[type=search], select")) {
      $(this).val("");
    } else if ($(this).is(":checkbox")) {
      $(this).prop("checked", false);
    }
  });
});

$("[name=search]").on('click', function(e){
  e.preventDefault();
  localStorage.removeItem('filters');
  $("#searchMsg").removeClass('alert alert-danger alert-success').text('');
  if ($("#dtsi").val() && $("#dtsf").val() && parseInt($("#dtsf").val()) < parseInt($("#dtsi").val())) {
    $("#searchMsg").addClass('alert alert-danger').text("L'anno finale non può essere minore di quello iniziale");
    return false;
  }
  if ($("#fullText").val() && $.trim($("#fullText").val()).length < 2) {
    $("#searchMsg").addClass('alert alert-danger').text("Nella ricerca libera devi inserire parole di almeno 2 lettere");
    return false;
  }
  $("[data-filter]").each(function() {
    let key = $(this).data('filter');
    if ($(this).is("input[type=number], input[type=search], select, :checkbox:checked")) {
      if (!$(this).is(':disabled') && $(this).val()) {
        setFilters(key,$(this).val(),'update')
      }
    }
  })
  if(checkFilters() == 0){
    $("#searchMsg").addClass('alert alert-danger').text('devi impostare almeno un filtro di ricerca!');
    return false;
  }
  WRAP.innerHTML=''
  currentPage = 1;
  itemsLoaded = 0;
  $("[name=clean]").removeClass('invisible');
  setFilters('principale', 'true', 'update');
  let filters = getFilters();
  gallery(filters);
})

function search(filters){
  return new Promise((resolve,reject) => {
    const data = {
      trigger: 'search',
      dati:filters,
      page: currentPage,
      limit: ITEMS_PER_PAGE
    }
    $.ajax({
      url: 'api/scheda.php',
      type: 'POST', 
      dataType: 'json', 
      data: data,
      success: function(response) {
        if (response.error){reject(response.error);}else{resolve(response);}
      },
      error: function(xhr, status, error) {
        reject(`Errore AJAX: ${error}`);
      }
    })
  })
}

async function gallery(filters){
  if (isLoading) return;
  isLoading = true;
  try {
    const json = await search(filters);
    console.log(json);
    totalPages = Math.ceil(json.totalItems.count / ITEMS_PER_PAGE + 1);
    totalPagesKnown = true;
    itemsLoaded += json.items.length;
    let text = json.items == 0 ? 'nessun reperto corrispondente ai tuoi criteri di ricerca' : itemsLoaded + " reperti caricati su "+json.totalItems.count+ " reperti totali trovati";
    $("#totalItems > h2").text(text);
    json.items.forEach(item => {
      const newCard = cardTemplate.content.cloneNode(true);
      const cardImage = newCard.querySelector('.img');
      const cardTitle = newCard.querySelector('.card-title');
      const cardText = newCard.querySelector('.card-text');
      const cardUrl = newCard.querySelector('.card-url');
      cardImage.style.backgroundImage = 'url("'+FOTO+item.file+'")';
      cardTitle.textContent = item.classe;
      cardText.textContent = item.ogtd;
      cardUrl.href = "schedaView.php?get="+item.id
      WRAP.appendChild(newCard);
    });
    isLoading = false;
    currentPage++;
  } catch (error) {
    console.error('Errore:', error);
  }
}
const totalItems = document.getElementById('totalItems');
const divInitialOffset = totalItems.offsetTop;
window.addEventListener('scroll', () => {  
  // infinite scroll
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 60) {
    if (totalPagesKnown && currentPage < totalPages) {
      let filters = getFilters();
      gallery(filters)
    }
  }

  // Controllo per il comportamento del div fisso
  if (window.scrollY >= divInitialOffset - 60) {
    totalItems.classList.add('fixed');
  } else {
    totalItems.classList.remove('fixed');
  }
});


function tagCerca(data){
  let tagContainer = $("#tagWrap");
  data.forEach((item, i) => {
    let div = $("<div/>",{class:'btn-group-toggle d-inline-block'}).attr('data-toggle','buttons').appendTo(tagContainer);
    let label = $("<label/>",{class:'btn btn-sm btn-outline-marta m-1 '}).text(item.tag+' '+item.count).appendTo(div);
    $("<input/>",{type:'checkbox', value:item.tag, name:'tagBtn', class:'filtro'}).attr('data-filter','tags').appendTo(label);
  });
}



function getList2(dati){
  return new Promise((resolve, reject) => {
    $.ajax({
      url:'api/getList.php',
      type:'POST',
      dataType:'json',
      data:dati,
      success: function(data){
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
        resolve();
      },
      error: function(err) {
        reject(err);
      }
    })
  })
}

function minMax(campo){
  console.log(campo);
}
