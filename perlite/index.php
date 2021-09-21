<html>
<head>
  <link id="bootswatch-theme" rel="stylesheet" href=".styles/darkly.css">
  <link id="highlight-js" rel="stylesheet" href=".styles/a11y-dark.css">
  <link rel="stylesheet" href=".styles/style.css">

  <script src=".js/jquery-3.6.0.min.js"></script>
  <script src=".js/highlight.min.js"></script>
  <script src=".js/bootstrap.bundle.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>



<body>

<header class="p-3">
  <div class="no-mobile"></div>    
  <nav class="navbar navbar-expand fixed-top navbar-dark">


  <div class="header-my">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
<!--             <a href="/"><svg class="me-3" height="65" id="Ebene_1" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 802.43 494.67">          
               <path class="logo-light" d="M223.58,535.81s0,27.2-31.16,27.2H98.57V530.22h30.8v5.95a86.29,86.29,0,0,0,31.16,5.58c8.65,0,35.13-2.52,35.13-11.89,0-20.53-97.09-67.91-97.09-67.91s0-24.68,29.9-24.68h95.11v33H192.42v-5.95s-9-5.4-24.5-5.4a79.7,79.7,0,0,0-27.74,5.4C148.1,466.81,223.58,499.06,223.58,535.81Z" transform="translate(-98.57 -252.71)"/><path class="logo-light" d="M278.55,462.85v24.32h62v22.34h-62v26.66h63.77V530.4h30.62c0,32.07-29.36,32.61-32.42,32.61H247.21V437.27h93.31c2.16,0,32.42,0,32.42,31.34H342.32v-5.76Z" transform="translate(-98.57 -252.71)"/><path class="logo-light" d="M522.48,525.36v6.48c0,.18,0,31.17-28.82,31.17H427.37s-31.16,0-31.16-31.17V468.07s.18-31,31.16-31H491s31.52,0,31.52,31v5.77h-30.8v-6.49h-62V531.3h62v-5.94Z" transform="translate(-98.57 -252.71)"/><path class="logo-light" d="M703.13,430.92s57.42,0,57.42,57.42H612.26s90.87,100.36,90.87,259h-53.8c0-159.13-114.38-259-114.38-259V430.92Z" transform="translate(-98.57 -252.71)"/><path class="logo-light" d="M732.82,569.18s-57.41,0-57.41-57.42H823.69s-90.87-100.36-90.87-259h53.8c0,159.14,114.38,259,114.38,259v57.42Z" transform="translate(-98.57 -252.71)"/>
            </svg></a> -->
            <a href="."><img src="logo.svg" height="65" class="me-3" alt="Perlite Logo"></a>

            <ul class="navbar-nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
              <li class="nav-item px-2"><a class="nav-link" href="https://secure77.de" target="_blank">Blog</a></li>
              <li class="nav-item px-2"><a class="nav-link" id="about" href="#">About</a></li>
            </ul>
            <div class="no-mobile text-info mdTitle me-lg-5"></div> 
            <div class="me-lg-2" id="showLastSearch"></div>
            <form id="f1" class="search-my col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
              <input type="search" name="t1" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggleTheme">
                <label class="form-check-label" for="toggleTheme"></label>
              </div>
            <div class="text-end">
              <button type="button" class="no-mobile btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#contentModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen" viewBox="0 0 16 16">
                  <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"></path>
                </svg>
                <span class="visually-hidden">Button</span>
              </button>
          </div>
        </div>    
  </div>
  </nav>
</header>




<?php

include('helper.php');

$title = '# Perlite';
$menu = menu($rootDir);

echo '
<title>'.$title.'</title>

<main>
<div class="divider"></div>
<aside class="bd-aside sticky-nav text-muted align-self-start mb-3 mb-xl-5 px-2">	
		<div class="flex-shrink-0 nav-selector p-3 bg-black">
    <h4 class="headline pb-3 mb-4 border-bottom">'.$base.'</h4>
			<ul class="list-unstyled ps-0">	
				'.$menu.'	
			</ul>
		</div>
</aside>
<div class="divider"></div>
<div class="markdown container-lg container-mobile" id="mdContent">
</div>
</div>
</main>
';?>


<!-- Modals -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModal" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-break mdModalTitle"></h5>
        <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mdModalBody"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <a href="."><img src="logo.svg" height="65" class="me-3" alt="Perlite Logo"></a>
        <h5 class="modal-title aboutModalTitle"></h5>
        <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body aboutModalBody">     
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModal" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title searchModalTitle">Search</h5>
        <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body searchModalBody">     
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="imgModal" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div id="imgContainer" class="modal-content">
      <div class="modal-body imgModalBody" onclick="$('#imgModal').modal('hide');">
      <img src="" class="imagepreview" style="" >     
      </div>
    </div>
  </div>
</div>


<script src=".js/perlite.js"></script>




</body>
</html>