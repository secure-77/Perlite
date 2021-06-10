<html>
<head>
  <link rel="stylesheet" href=".styles/bootstrap.css">
  <link rel="stylesheet" href=".styles/a11y-dark.css">
  <link rel="stylesheet" href=".styles/style.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src=".js/highlight.pack.js"></script>
  <script src=".js/bootstrap.bundle.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>



<body class="bg-dark">

<header class="p-3 bg-dark text-white">
  <div class="no-mobile"></div>    
  <nav class="navbar pt-3 fixed-top navbar-dark bg-dark">


  <div class="header-my">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="."><img src="logo.svg" height="65" class="me-3" alt="Secure77"></a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
              <li><a href="https://secure77.de" target="_blank" class="nav-link px-2 text-white">Blog</a></li>
              <li><a href="#" class="nav-link px-2 text-white" id="about">About</a></li>
            </ul>
            <div class="no-mobile mdTitle me-lg-5"></div> 
            <form id="f1" class="search-my col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
              <input type="search" name="t1" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>
            
            <div class="text-end">
              <button type="button" class="no-mobile btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#contentModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen" viewBox="0 0 16 16">
                  <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"></path>
                </svg>
                <span class="visually-hidden">Button</span>
              </button>
          </div>
        </div>
        <div id="showLastSearch"></div>
  </div>



  </nav>
</header>




<?php

include('helper.php');

$title = '#### Perlite ####';
$menu = menu(getcwd());


echo '
<title>'.$title.'</title>

<main>
<div class="divider"></div>
<aside class="bd-aside sticky-nav text-muted align-self-start mb-3 mb-xl-5 px-2">	
		<div class="flex-shrink-0 nav-selector p-3 bg-black">
    <h4 class="headline pb-3 mb-4 border-bottom">Notes</h4>
			<ul class="list-unstyled ps-0">	
				'.$menu.'	
			</ul>
		</div>
</aside>
<div class="divider"></div>
<div class="markdown container-lg container-mobile bg-dark text-white" id="mdContent">
</div>
</div>
</main>

<!-- Modals -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModal" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title mdModalTitle"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mdModalBody"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title aboutModalTitle"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body aboutModalBody">     
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModal" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title searchModalTitle">Search</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body searchModalBody">     
      </div>
    </div>
  </div>
</div>



<script>


  function getContent(str) {
    if (str.length == 0) {
      document.getElementById("mdContent").innerHTML = "";
      document.getElementsByClassName("modal-body")[0].innerHTML = "";
      return;
    } else {
    
      $.ajax({url: "content.php?file=" + str, success: function(result){
        $("#mdContent").html(result);
        $("div.mdModalBody").html(result);		      
        var title = $("div.mdTitleHide").first().html();
        $("div.mdTitle").html(title);
        $("h5.mdModalTitle").html(title);
        hljs.highlightAll();
        isMobile();
        $("#searchModal").modal("hide");
      }});   
    }
  };

  function isMobile() {      
    var is_mobile = false;
   
    if( $("div.no-mobile").css("display")=="none") {
        is_mobile = true;   
    }
   
    if (is_mobile == true) {
      $("#contentModal").modal("show");
    }
 };


  
function search(str) {
  if (str.length == 0) {
    document.getElementById("mdContent").innerHTML = "";
    document.getElementsByClassName("modal-body")[0].innerHTML = "";
    return;
  } else {
  
    $.ajax({url: "content.php?search=" + str, success: function(result){
      $("div.searchModalBody").html(result);		
      var title = $("div.searchTitle").first().html();
      $("h5.searchModalTitle").html(title);
      hljs.highlightAll();
      $("#searchModal").modal("show");
      var lastSearch = $("div.lastSearch").first().html();
      $("#showLastSearch").html(lastSearch);		
    }});   
  }
};


document.getElementById("f1").onsubmit = function() {
    
    search(this.t1.value);
    return false;
};

document.getElementById("showLastSearch").onclick = function () {
  $("#searchModal").modal("show");
};

document.getElementById("about").onclick = function () {
  $.ajax({url: "content.php?about", success: function(result){
    $("div.aboutModalBody").html(result);		
    var title = $("div.searchTitle").first().html();
    $("h5.aboutModalTitle").html("Perlite");
    hljs.highlightAll();
    $("#aboutModal").modal("show");		
  }}); 
};


</script>
';

?>
</body>
</html>