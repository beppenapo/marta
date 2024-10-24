$(document).ajaxStart(showLoading).ajaxStop(hideLoading);
const localStorageItem = 'sfoglia';
$(document).ready(function() {setActiveFilters(localStorageItem);});

const ITEMS_PER_PAGE = 24;
const FOTO = "http://91.121.82.80/marta/file/foto/";
const WRAP = document.getElementById('wrapItems');

let maxCrono = [];
let totalPagesKnown = false;
let totalPages = 0;
let currentPage = 1;
let itemsLoaded = 0;

let isLoading = false;

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
  sessionStorage.removeItem(localStorageItem);
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
  sessionStorage.removeItem(localStorageItem);
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
        setFilters(localStorageItem,key,$(this).val(),'update')
      }
    }
  })
  if(checkFilters(localStorageItem) == 0){
    $("#searchMsg").addClass('alert alert-danger').text('devi impostare almeno un filtro di ricerca!');
    return false;
  }
  WRAP.innerHTML=''
  currentPage = 1;
  itemsLoaded = 0;
  $("[name=clean]").removeClass('invisible');
  setFilters(localStorageItem, 'principale', 'true', 'update');
  let filters = getFilters(localStorageItem);
  gallery(filters);
})

const totalItems = document.getElementById('totalItems');
const divInitialOffset = totalItems.offsetTop;
window.addEventListener('scroll', () => {  
  // infinite scroll
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


async function setActiveFilters(item) {
  let filters = getFilters(item);
  if (filters && Object.keys(filters).length > 0) {    
    const listRequests = [
      // {campo: 'tsk', val: 1},
      // {campo: 'tsk', val: 2},
      {campo: 'dtzgi'},
      {campo: 'dtzgf'}
    ];
    Object.keys(filters).forEach(key => { 
      if(key == 'tsk'){listRequests.push({campo: 'tsk', val: filters[key]})} 
    })
    for (const params of listRequests) { await getList2(params); }
    await tagWrap(tagCerca)
    await gallery(filters)

    Object.keys(filters).forEach(key => {
      let value = filters[key];
      if ($(`select[data-filter="${key}"]`).length) {
        $(`select[data-filter="${key}"]`).val(value).prop('selected', true);
      }
      if ($(`input[type="text"][data-filter="${key}"], input[type="search"][data-filter="${key}"], input[type="number"][data-filter="${key}"]`).length) {
        $(`input[type="text"][data-filter="${key}"], input[type="search"][data-filter="${key}"], input[type="number"][data-filter="${key}"]`).val(value);
      }
      if(key == 'modello'){
        $("#modelloLabel").addClass('active')
        $("#modello").prop('checked', true)
      }
      if (Array.isArray(value)) {
        value.forEach(val => {
          $(`input[type="checkbox"][data-filter="${key}"][value="${val}"]`).prop('checked', true);
          $(`label:has(input[type="checkbox"][data-filter="${key}"][value="${val}"])`).addClass('active');
        });
      }
    });
    $("[name=clean]").removeClass('invisible');
  }else{
    await tagWrap(tagCerca)
    await getList2({campo: 'dtzgi'})
    await getList2({campo: 'dtzgf'})
  }
}