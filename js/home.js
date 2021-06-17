$(document)
.ajaxStart(function(){console.log('...loading');})
.ajaxStop(function(){$("#loadingDiv").remove();});

// stat();
