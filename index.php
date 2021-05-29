<html>
<head>
<link rel="stylesheet" href=".styles/bootstrap.css">
<link rel="stylesheet" href=".styles/a11y-dark.css">
<link rel="stylesheet" href=".styles/style.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src=".js/highlight.pack.js"></script>
<script src=".js/bootstrap.bundle.min.js"></script>

</head>

<header class="p-3 bg-dark text-white">
    <div class="container-my">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
	  <img src="logo.svg" width="100" class="me-3" alt="Secure77"> 
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="#" class="nav-link px-2 text-white">Blog</a></li>
          <li><a href="#" class="nav-link px-2 text-white">About</a></li>
        </ul>
		<div class="mdTitle me-lg-5"></div> 
        <form id="f1" class="search-my col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
          <input type="search" name="t1" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
        </form>

        <div class="text-end">
		<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#contentModal">
	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen" viewBox="0 0 16 16">
<path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"></path>
</svg>
	<span class="visually-hidden">Button</span>
  </button>

        </div>
      </div>
    </div>
  </header>



<body class="bg-dark">

<?php

include('helper.php');

$title = '#### Perlite ####';
$menu = menu(getcwd());


echo '
<title>'.$title.'</title>

<main>
<div class="divider"></div>
<aside class="bd-aside sticky-xl-top text-muted align-self-start mb-3 mb-xl-5 px-2">
	<h2 class="h6 pt-4 pb-3 mb-4 border-bottom">Notes</h2>
		<div class="flex-shrink-0 nav-selector p-3 bg-black">
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

<!-- Modal -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
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
		  $("div.modal-body").html(result);		 
		}});

	$(document).ajaxComplete(function () {
			hljs.highlightAll();
			var title = $("div.mdTitleHide").first().text();
			$("div.mdTitle").html(title);
			$("h5.modal-title").html(title);
	  }); 
	}
  }


  function findString(str) {
    if (parseInt(navigator.appVersion) < 4) return;
    var strFound;
    if (window.find) {
        // CODE FOR BROWSERS THAT SUPPORT window.find
        strFound = self.find(str);
        if (strFound && self.getSelection && !self.getSelection().anchorNode) {
            strFound = self.find(str)
        }
        if (!strFound) {
            strFound = self.find(str, 0, 1)
            while (self.find(str, 0, 1)) continue
        }
    } else if (navigator.appName.indexOf("Microsoft") != -1) {
        // EXPLORER-SPECIFIC CODE        
        if (TRange != null) {
            TRange.collapse(false)
            strFound = TRange.findText(str)
            if (strFound) TRange.select()
        }
        if (TRange == null || strFound == 0) {
            TRange = self.document.body.createTextRange()
            strFound = TRange.findText(str)
            if (strFound) TRange.select()
        }
    } else if (navigator.appName == "Opera") {
        alert("Opera browsers not supported, sorry...")
        return;
    }
    if (!strFound) alert("String \'" + str + "\' not found!")
        return;
};

document.getElementById("f1").onsubmit = function() {
    findString(this.t1.value);
    return false;
};










</script>
';

?>
</body>
</html>