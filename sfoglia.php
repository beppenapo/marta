<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it" dir="ltr">
  <head>
    <?php require('assets/meta.html'); ?>
    <link rel="stylesheet" href="css/sfoglia.css">
  </head>
  <body>
    <?php
      require('assets/loading.html'); 
      require('assets/headerMenu.php'); 
    ?>
    <?php if(isset($_SESSION['id'])) {require('assets/mainMenu.php');} ?>
    <main class="bg-light">
      <div id="mainTitle" class="my-2 py-2 bg-marta text-white">
        <div>
          <h2>Sfoglia il catalogo digitale del MArTA!</h2>
          <p>Cerca all'interno dell'archivio utilizzando i seguenti filtri di ricerca.<br />
            Il risultato dovrà soddisfare tutti i criteri di ricerca inseriti pertanto è importante utilizzare i filtri con criterio altrimenti il risultato potrebbe essere impreciso.</p>
        </div>
      </div>
      <div class="bg-white border-top border-bottom p-3">
        <div class="container">
          <div class="searchTip mb-5">
            <button class="btn btn-info btn-sm d-block" type="button" data-toggle="collapse" data-target="#searchTip" aria-expanded="false" aria-controls="searchTip">linee guida per una ricerca corretta</button>
            <div class="collapse" id="searchTip">
              <div class="card card-body">
                <p class="tipTitle">Linee guida generali</p>
                <p class="tipBody">
                  L'utente può effettuare una ricerca all'interno dell'archivio digitale del Museo utilizzando tutti i filtri a disposizione, il sistema cercherà tra le schede che contengono tutti i valori inseriti nei vari campi. Questo permette una ricerca molto approfondita ma, d'altra parte, utilizzando troppi filtri si rischia di ottenere una ricerca nulla. Un buon metodo è quello di partire da pochi filtri e, man mano, affinare la ricerca.<br />
                  Accanto ad ogni campo c'è un punto interrogativo, passando con il mouse al di sopra comparirà una breve spiegazione del campo.<br />
                  Tutti i campi si basano sui vocabolari forniti dall' <a href="http://www.iccd.beniculturali.it/" target="_blank" data-toggle="tooltip" title="linkal sito ufficiale">Istituto Centrale per il Catalogo e la Documentazione</a>
                </p>

                <p class="tipTitle">Seleziona scheda in base a numeri di inventario o NCTN</p>
                <p class="tipBody">L'utilizzo del "Numero di Catalogo Nazionale" o del "numero di inventario" come filtri di ricerca produrrà un risultato puntuale, ovvero ogni numero di catalogo o di inventario corrisponde ad uno specifico reperto, l'utilizzo di altri filtri potrebbe dare risultato nullo pertanto, nel caso in cui si conosca uno dei 2 dati, si consiglia di non usare altri filtri.</p>

                <p class="tipTitle">Filtra reperti con modello 3d</p>
                <p class="tipBody">L'utilizzo di questo filtro darà come risultato solo ed esclusivamente i reperti per i quali è presente anche un modello tridimensionale. Per una ricerca più accurata è possibile asociare altri tipi di filtri.</p>
                
                <p class="tipTitle">Caratteristiche funzionali e morfologiche</p>
                <p class="tipBody">Questo blocco di filtri permette di cercare utilizzando le caratteristiche tipologiche e morfologiche del reperto<br />Ogni campo è collegato agli altri, selezionando il "tipo" di reperto tra quelli a disposizione le altre liste si popoleranno con i valori specifici del campo scelto, ad esempio scegliendo "moneta" come tipo di reperto, il campo "materia" mostrerà solo le materie compatibili con le monete.<br />Il campo "Definizione" indica il nome comune del reperto ed è parte di una macro-categoria raprresentata dal campo "Categoria" che raggruppa i vari reperti in base alle loro caratteristiche funzionali. Poiché potrebbero esserci oggetti con lo stesso nome ma appartenenti a classi funzionali diverse (ad esempio "ago") è consigliabile selezionare anche una classe.</p>
                
                <p class="tipTitle">Cronologia</p>
                <p class="tipBody">Il blocco della cronologia permette di filtrare i reperti in base all'arco cronologico di utiilzzo così come individuato dalle fonti storiche e archeolgiche<br />I campi "Periodo" propongono una suddivisione cronologica basata sui secoli, mentre nei campi "Anno" è possibile inserire un anno specifico in formato numerico (negativo per gli anni a.C.).<br />Sia il periodo che l'anno sono suddivisi in "iniziale" e "finale" in modo da indicare un range nel quale ricercare i reperti.<br />L'utente può, quindi, utilizzare uno solo di questi 4 campi o più di uno, indicando solo il periodo o l'anno "iniziale" il sistema cercherà tutti i reperti attestati a partire dal valore scelto. Viceversa indicando solo il valore "finale", il sistema cercherà solo i reperti il cui utilizzo è attestato fino al valore scelto</p>
                
                <p class="tipTitle">Parole chiave</p>
                <p class="tipBody">Le parole chiave rappresentano grandi macro-categorie di reperti che possono essere ritenute simili concettualmente. Sono state individuate basandosi sulle parole "rappresentative" più ricorrenti inserite dagli operatori nei campi descrittivi delle schede. Tali parole sono state poi accorpate per "significato" e solo successivamente sono stati individuati macro-gruppi semantici.<br />L'utente può selezionare una o più parole parole chiave, il sistema cercherà tra le schede a cui sono state associate tutte le categorie scelte.</p>
                
                <p class="tipTitle">Ricerca libera</p>
                <p class="tipBody">La ricerca libera rappresenta il livello di approfondimento maggiore in quanto l'utente può scegliere parole specifiche, non presenti negli altri campi.<br />E' possibile inserire più parole separate da spazio in modo da affinare ulteriormente la ricerca.</p>
              </div>
            </div>
          </div>
          <?php if(!isset($_SESSION['id'])){?>
            <div class="alert alert-danger p-3 text-center">
              <p class="mb-0"><strong>I reperti presenti nei depositi non verranno mostrati nei risultati delle ricerche.</strong></p>
              <p>Per poter effettuare ricerche anche nei depositi è necessario possedere un account da ricercatore. Per ottenere un account da ricercatore, scrivere ad una delle seguenti mail motivando la richiesta:</p>
              <a href="mailto:sara.airo@cultura.gov.it" class="alert-link">sara.airo@cultura.gov.it</a><br>
              <a href="mailto:antonio.donnici@cultura.gov.it" class="alert-link">antonio.donnici@cultura.gov.it</a> 
            </div>
          <?php } ?>
          <form>
            <div class="form-row">
              <div class="col-md-8 mb-3">
                <h6>Seleziona scheda in base a numeri di inventario o NCTN</h6>
                <div class="d-inline-block" style="width:45%;margin-right:10px">
                  <label for="nctn" class="mb-2">Numero di catalogo (NCTN)</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <label class="input-group-text" data-toggle="tooltip" title="inserisci il numero di catlogo esatto, deve essere un numero di 6 cifre">
                        <i class="fa-solid fa-question"></i>
                      </label>
                    </div>
                    <input type="number" class="form-control filtro" data-filter="nctn" step="1" id="nctn" min="300000" max="999999" placeholder="numero di catalogo">
                  </div>
                </div>
                <div class="d-inline-block" style="width:45%;margin-right:10px">
                <label for="inventario" class="mb-2">Numero di inventario</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="inserisci il numero di inventario senza prefisso o suffisso, ad esempio per 1234-A inserire solo 1234">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <input type="number" class="form-control filtro" data-filter="inventario" step="1" id="inventario" placeholder="numero di inventario">
                </div>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <h6>Filtra reperti con modello 3d</h6>
                <label class="m-0">Modello</label>
                <div class="btn-group-toggle" data-toggle="buttons">
                  <label class="btn btn-sm btn-outline-marta m-1" id="modelloLabel">modello presente
                    <input type="checkbox" class="filtro" data-filter="modello" id="modello">
                  </label>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-6 mb-3">
                
              </div>
              <div class="col-6 mb-3">
                
              </div>
            </div>
            <div class="form-row">
              <div class="col">
                <h6 class="m-0">Filtra per caratteristiche funzionali e morfologiche</h6>
                <small class="text-secondary mb-3">Se vuoi utilizzare uno dei seguenti campi inizia scegliendo il "Tipo" di reperto, tale valore servirà da filtro per gli altri campi che verranno popolati dinamicamente</small>
              </div>
            </div>
            <div class="form-row">
              <div class="col-auto mb-3">
                <label for="tipo">Tipo</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="filtra per tipologia di reperto, scegli tra vari tipi di oggetti o cerca solo tra le monete">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <select class="form-control filtro" data-filter="tsk" id="tipo">
                    <option value="" selected>--scegli tipo--</option>
                    <option value="1">oggetto</option>
                    <option value="2">moneta</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="classe">Categoria</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Il campo rappresenta la macro-categoria a cui appartiene l'oggetto. Il campo farà da filtro per il campo definizione">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <select class="form-control filtro" data-filter="cls" id="classe">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="ogtd">Definizione</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Termine o locuzione che individua il bene oggetto della scheda in base alla connotazione funzionale e morfologica. Ogni definizione appartiene ad una macro-categoria individuata dal precedente campo classe. Per selezionare una definizione devi prima scegliere una classe">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <select class="form-control filtro" data-filter="ogtd" id="ogtd">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="materia">Materiale</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Scegliendo un termine dalla lista potrai filtrare in base al materiale con cui è fatto l'oggetto o una parte di esso">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <select class="form-control filtro" data-filter="materia" id="materia">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-row mt-3">
              <div class="col">
                <h6>Filtra per cronologia</h6>
              </div>
            </div>
            <div class="form-row">
              <div class="col-auto mb-3">
                <label for="dtzgi">Periodo iniziale</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Scegli l'arco temporale iniziale. Il campo filtra tutti i reperti il cui utilizzo o creazione partono dal periodo culturale o cronologico scelto">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <select class="form-control filtro crono" data-filter="dtzgi" id="dtzgi">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
              </div>
              <div class="col-auto mb-3">
                <label for="dtzgf">Periodo finale</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Scegli l'arco temporale finale. Il campo filtra tutti i reperti il cui utilizzo o creazione terminano in corrispondenza del periodo culturale o cronologico scelto">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <select class="form-control filtro crono" data-filter="dtzgf" id="dtzgf">
                    <option value="" selected>--seleziona valore--</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="dtsi">Anno iniziale</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Scegli l'anno iniziale. Il campo filtra tutti i reperti il cui utilizzo o creazione partono dall'anno specifico scelto. Inserisci un valore negativo per le cronologie a.C.">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <input type="number" class="form-control filtro" data-filter="dtsi" step="1" id="dtsi" placeholder="anno iniziale">
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="dtsf">Anno finale</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="Scegli l'anno finale. Il campo filtra tutti i reperti il cui utilizzo o creazione terminano in corrispondenza dell'anno specifico scelto. Inserisci un valore negativo per le cronologie a.C.">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <input type="number" class="form-control filtro" data-filter="dtsf" step="1" id="dtsf" placeholder="anno finale">
                </div>
              </div>
            </div>
            <div class="form-row mt-3">
              <div class="col">
                <h6 class="d-inline" data-toggle="tooltip" title="seleziona uno o più parole chiave da aggiungere alla tua ricerca"><i class="fa-solid fa-question"></i> Filtra per parole chiave</h6>
              </div>
            </div>
            <div class="form-row">
              <div class="col-auto mb-3">
                <div id="tagWrap"></div>
              </div>
            </div>
            <div class="form-row mt-3">
              <div class="col">
                <h6>Ricerca libera</h6>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-8 mb-3">
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">
                    <label class="input-group-text" data-toggle="tooltip" title="inserisci uno o più termini separati da uno spazio, il sistema cercherà le schede che contengono tutte le parole inserite nel campo. La ricerca può essere effettuaa anche con parole incomplete. La ricerca verrà effettuata in tutti i campi che contengono una descrizione estesa.">
                      <i class="fa-solid fa-question"></i>
                    </label>
                  </div>
                  <input type="search" class="form-control filtro" data-filter="fts" id="fullText" placeholder="inserisci parole">
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-marta" name="search"><i class="fa-solid fa-magnifying-glass"></i> avvia ricerca</button>
                <button type="button" class="btn btn-sm btn-marta invisible" name="clean"><i class="fa-solid fa-rotate"></i> pulisci filtri</button>
              </div>
              <div class="col-auto">
                <div id="searchMsg">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div id="totalItems"><h2></h2></div>
      <div id="wrapItems"></div>
    </main>
    <?php require('assets/footer.html'); ?>
    <?php require('assets/lib.html'); ?>
    <script src="js/function.js" charset="utf-8"></script>
    <script src="js/sfoglia.js" charset="utf-8"></script>
  </body>
</html>
