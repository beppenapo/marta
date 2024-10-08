const ITEM = $("[name=item").val();
let filter = 0;

switch (ITEM) {
  case 'reperti':
    filter = 1;
    break;
  case 'monete':
    filter = 2;
    break;
  case 'immagini':
    filter = 3;
    break;
  case 'stereo':
    filter = 4;
    break;
  case 'modelli':
    filter = 5;
    break;
  default:
    break;
}

console.log(filter);

