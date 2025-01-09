const ITEM = $("[name=item]").val() || 'reperti';
const ENDPOINT = "api/gallery.php";

const ITEMS_PER_PAGE = 24;
const FOTO = ITEM == 'stereo' ? "http://145.239.0.109/marta/file/stereo/" : "http://145.239.0.109/marta/file/foto/";
const WRAP = document.getElementById('wrapItems');

let totalPagesKnown = false;
let totalPages = 0;
let currentPage = 1;
let itemsLoaded = 0;
let isLoading = false;

WRAP.innerHTML=''

getData()

async function getData() {
  
  if (isLoading) return;
  isLoading = true;
  try {
    showLoading();
    const options ={ 
      method: 'POST', 
      body: new URLSearchParams({
        filter:ITEM,
        page: currentPage,
        limit: ITEMS_PER_PAGE
      }) 
    }
    const response = await fetch(ENDPOINT,options);
    if (!response.ok) { throw new Error('Errore durante la fetch: ' + response.status);}
    const json = await response.json();
    console.log(json.items);
    totalPages = Math.ceil(json.totalItems.count / ITEMS_PER_PAGE + 1);
    totalPagesKnown = true;
    itemsLoaded += json.items.length;
    let text = ITEM + ": "+ itemsLoaded + " / "+json.totalItems.count;
    $("#countItems>h3").text(text)


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
    
    hideLoading();
    isLoading = false;
    currentPage++;


  } catch (error) {
    console.log(error);
    throw error;
  }
}

window.addEventListener('scroll', () => {  
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 60) {
    if (!totalPagesKnown || currentPage < totalPages) {
      getData();
    }
  }
});