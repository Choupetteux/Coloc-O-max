function addCurrentPage(){
	$(document).ready(function() { 
    var url = window.location.pathname;
    url = url.splice(url.length-1, 1);
    $(document.body).append(url);
  })
}