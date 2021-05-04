const API = 'api/scheda_biblio.php';

  if (id_scheda > 0) {
	  // NOTE: bibliografia autocomplete
	  $.ajax({
		url: API,
		type: 'POST',
		dataType: 'json',
		data: {trigger:'vocabolari', tab:'public.bibliografia'}
	  })
	  .done(function(data){
		$( "[name=biblioexist]" ).autocomplete({
		  minLength: 0,
		  source: data,
		  change: function(event,ui){
			return false;
		  },
		  select: function(event,ui){
			$(this).val(ui.item.label);
			$('#biblioexistval').val(ui.item.id);
			return false;
		  }
		}).focus(function() {
		  $(this).autocomplete('search', $(this).val())
		});
	  })
	  .fail(function(data) { console.log(data); });
  }
  
 ////////// mostra dati x EDIT
  if (action == 'edit' || action == 'view') {
  $.ajax({
    url: API,
    type: 'POST',
    dataType: 'json',
    data: {trigger:'getScheda', id:id_biblio}
  })
  .done(function(data) {
    //////////////// inizio passaggio dati
    let c = data.length;
    let id = data[0].id;
    let titolo = data[0].titolo;
    $('#titolo').val(titolo);
	let tipo = data[0].tipo;
	$("[name=tipo]").val(tipo);
	let autore = data[0].autore;
	$("[name=autore]").val(autore);
	let altri_autori = data[0].altri_autori;
	$("[name=altri_autori]").val(altri_autori);
	let titolo_raccolta = data[0].titolo_raccolta;
	$("[name=titolo_raccolta]").val(titolo_raccolta);
	let editore = data[0].editore;
	$("[name=editore]").val(editore);
	let anno = data[0].anno;
	$("[name=anno]").val(anno);
	let luogo = data[0].luogo;
	$("[name=luogo]").val(luogo);
	let isbn = data[0].isbn;
	$("[name=isbn]").val(isbn);
	let url = data[0].url;
	$("[name=url]").val(url);
	let pagine = data[0].pagine;
	$("[name=pagine]").val(pagine);
	if (action == 'view') { $('input, select').prop('disabled', true); }
    ////////////////////// fine passaggio dati
  })
  .fail(function(data) { console.log(data); });
  }
  ////////// fine mostra dati x EDIT
 
$('[name=elimina_scheda]').on('click', function (e) {
	form = $("#formScheda");
	e.preventDefault();
  if (!confirm("Attenzione! Stai per elimnare la scheda bibliografica: procedere?")){
	return false;
  }else{
	$(".tastischeda").hide();
	let trigger = "deleteScheda";
	$.ajax({
	url: API,
	type: 'POST',
	dataType: 'json',
	data: {trigger : trigger, id:id_biblio}
	})
	.done(function(data) {
	//console.log(data);
	if (data.res === true || data.msg == 'There is no active transaction') {
	  $(".toast").addClass('bg-success');
	  $(".toast-body").html('La scheda bibliografica è stata correttamente eliminata');
	  $(".toast").toast({delay:3000});
	  $(".toast").toast('show');
	  $('.toast').on('hidden.bs.toast', function () {
	  $(".toast").removeClass('bg-success');
	  window.location.href = 'scheda_biblio_lista.php';
	  })
	  window.setTimeout(function(){ window.location.href = 'scheda_biblio_lista.php'; },3000);
	}else {
	  $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
	  $("#headerTxt").html('Errore nella query');
	  $(".toast>.toast-body").html(data.msg);
	  $(".toast").toast({delay:3000});
	  $(".toast").toast('show');
	  $('.toast').on('hidden.bs.toast', function () {
	  $(".toast").removeClass('bg-danger');
	  })
	  $(".tastischeda").show();
	}
	})
	.fail(function() {console.log("error"); });
  }
});

$('[name=addbiblioexist]').on('click', function (e) {
	e.preventDefault();
	if (id_scheda > 0) {
		let trigger = "insbiblioinscheda";
		let biblioadd = $('#biblioexistval').val();
		if (biblioadd < 1) {
		  alert("Attenzione, devi selezionare una bibliografia!");
		  return false;
		}
		$(".tastischeda").hide();
		$.ajax({
		  url: API,
		  type: 'POST',
		  dataType: 'json',
		  data: {trigger: trigger, id_scheda:id_scheda, id_biblio:biblioadd}
		})
		.done(function(data) {
		  //console.log(data);
		  if (data.res === true || data.msg == 'There is no active transaction') {
			$(".toast").addClass('bg-success');
			$(".toast-body").html('Associazione con la scheda bibliografica effettuata correttamente.');
			$(".toast").toast({delay:3000});
			$(".toast").toast('show');
			$('.toast').on('hidden.bs.toast', function () {
			  $(".toast").removeClass('bg-success');
			  if (id_scheda > 0) { window.location.href = 'scheda.php?act='+act_scheda+'&id='+id_scheda+'&tipo='+tipoScheda+'#divbiblio'; }else{ location.reload(); }
			})
			window.setTimeout(function(){ if (id_scheda > 0) { window.location.href = 'scheda.php?act='+act_scheda+'&id='+id_scheda+'&tipo='+tipoScheda+'#divbiblio'; }else{ location.reload(); } },3000);
		  }else {
			$(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
			$("#headerTxt").html('Errore nella query');
			$(".toast>.toast-body").html(data.msg);
			$(".toast").toast({delay:3000});
			$(".toast").toast('show');
			$('.toast').on('hidden.bs.toast', function () {
			  $(".toast").removeClass('bg-danger');
			})
			$(".tastischeda").show();
		  }
		})
		.fail(function() {console.log("error"); });
	}else{
		return false;
	}
});

$('[name=submit]').on('click', function (e) {
  isvalidate = $("#formScheda")[0].checkValidity()
  if (isvalidate) {
	var mtcVal = [];
	form = $("#formScheda");
	e.preventDefault();
	dati = {};
	tab = [];
	$("[data-table]").each(function(){tab.push($(this).data('table'));})
	tab = tab.filter((v, p) => tab.indexOf(v) == p);
	$.each(tab,function(i,v){ dati[v]={} })
	dati.bibliografia.tipo = $("[name=tipo]").val();
	if (dati.bibliografia.tipo == "") {
	  alert("Attenzione, devi inserire un tipo per la bibliografia!");
	  return false;
	}
	dati.bibliografia.autore = $("[name=autore]").val();
	if (dati.bibliografia.autore == "") {
	  alert("Attenzione, devi inserire un autore per la bibliografia!");
	  return false;
	}
	dati.bibliografia.titolo = $("[name=titolo]").val();
	if (dati.bibliografia.titolo == "") {
	  alert("Attenzione, devi inserire un titolo per la bibliografia!");
	  return false;
	}
  dati.bibliografia.altri_autori = $("[name=altri_autori]").val();
  dati.bibliografia.titolo_raccolta = $("[name=titolo_raccolta]").val();
  dati.bibliografia.editore = $("[name=editore]").val();
  dati.bibliografia.anno = $("[name=anno]").val();
  dati.bibliografia.luogo = $("[name=luogo]").val();
  dati.bibliografia.isbn = $("[name=isbn]").val();
  dati.bibliografia.url = $("[name=url]").val();
  dati.bibliografia.pagine = $("[name=pagine]").val();
  let trigger = "addScheda";
  let insomodiftxt = "inserita";
  if (action == "edit" && id_biblio > 0) {
	trigger = "editScheda";
	insomodiftxt = "modificata";
	dati.bibliografia.id = [];
	dati.bibliografia.id = id_biblio ;
  }
  if (id_scheda > 0) {
	dati.biblio_scheda.id_scheda = [];
	dati.biblio_scheda.id_scheda = id_scheda ;
  }
  //console.log(dati);
  $(".tastischeda").hide();
	$.ajax({
	  url: API,
	  type: 'POST',
	  dataType: 'json',
	  data: {trigger : trigger,  dati}
	})
	.done(function(data) {
	  //console.log(data);
	  if (data.res === true || data.msg == 'There is no active transaction') {
		$(".toast").addClass('bg-success');
		$(".toast-body").html('La scheda bibliografica è stata correttamente '+insomodiftxt);
		$(".toast").toast({delay:3000});
		$(".toast").toast('show');
		$('.toast').on('hidden.bs.toast', function () {
		  $(".toast").removeClass('bg-success');
		  if (id_scheda > 0) { window.location.href = 'scheda.php?act='+act_scheda+'&id='+id_scheda+'&tipo='+tipoScheda+'#divbiblio'; }else{ location.reload(); }
		})
		window.setTimeout(function(){ if (id_scheda > 0) { window.location.href = 'scheda.php?act='+act_scheda+'&id='+id_scheda+'&tipo='+tipoScheda+'#divbiblio'; }else{ location.reload(); } },3000);
	  }else {
		$(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
		$("#headerTxt").html('Errore nella query');
		$(".toast>.toast-body").html(data.msg);
		$(".toast").toast({delay:3000});
		$(".toast").toast('show');
		$('.toast').on('hidden.bs.toast', function () {
		  $(".toast").removeClass('bg-danger');
		})
		$(".tastischeda").show();
	  }
	})
	.fail(function() {console.log("error"); });
  }
})

function listaScheda(tiposcheda){
  $('#divListaScheda').html('');
  let txtSearch = $("[name=txtSearch]").val();
  let newdata = "";
  $.ajax({
    url: API,
    type: 'POST',
    dataType: 'json',
    data: {trigger:'listaScheda', txtSearch:txtSearch}
  })
  .done(function(data) {
    let c = data.length;
    for (let x = 0; x < c; x++) {
      let id = data[x].id;
      let tipo = data[x].tipo;
      let titolo = data[x].titolo;
      let autore = data[x].autore;
      let classoodeven = ""; if (x%2 != 0) { classoodeven = " lista_even"; }
      newdata += "<div class='row lista"+classoodeven+"'><div class='col'><a href='scheda_biblio.php?act=edit&id_biblio="+id+"' target='_self' title='Visualizza / Modifica scheda'>"+titolo+"  ("+tipo+")<span class='lista_compilatore'>"+autore+"</span></a></div></div>";
    }
    $('#divListaBiblio').html(newdata);
  })
  .fail(function(data) { console.log(data); });
}
