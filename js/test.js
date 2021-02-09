$(document).ready(function() {
  $.ajax({
    url: 'api/connector.php',
    type: 'POST',
    dataType: 'json',
    data: {trigger: 'test'}
  })
  .done(function(data) {
    console.log(data);
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });

});
