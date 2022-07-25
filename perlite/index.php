<!DOCTYPE html>
<html>

<?php
/*!
  * Version v1.4.4 (https://github.com/secure-77/Perlite)
*/
?>

<!-- 
/*!
  * Perlite (https://github.com/secure-77/Perlite)  
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/
-->


<head>
    <link id="bootswatch-theme" rel="stylesheet" href=".styles/slate.css">
    <link id="highlight-js" rel="stylesheet" href=".styles/a11y-dark.min.css">
    <link rel="stylesheet" href=".styles/vis.min.css" />
    <link rel="stylesheet" href=".styles/style.css">
    <link rel="stylesheet" href=".styles/katex.min.css">

    <script src=".js/jquery-3.6.0.min.js"></script>
    <script src=".js/highlight.min.js"></script>
    <script src=".js/bootstrap.bundle.min.js"></script>
    <script src=".js/vis-network.min.js"></script>
    <script src=".js/katex.min.js"></script>
    <script src=".js/auto-render.min.js"></script>
    <script src=".js/mermaid.min.js"></script>


    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <header>
        <div class="no-mobile"></div>
        <nav class="navbar-expand fixed-top navbar-dark">
            <div class="header-my">
                <div class="d-flex align-items-center justify-content-lg-start">
                    <a href="."><img src="logo.svg" height="35" class="me-3" alt="Perlite Logo"></a>

                    <ul class="navbar-nav col-12 col-lg-auto me-lg-auto mb-md-0">
                        <li class="nav-item px-2"><a class="nav-link" href="https://secure77.de" target="_blank"
                                rel="noopener noreferrer">Blog</a></li>
                        <li class="nav-item px-2"><a class="nav-link" id="about" href="#">About</a></li>
                    </ul>
                    <div class="no-mobile me-lg-5">
                        <li class="blockquote-footer perlite-bread text-info mdTitle">/</li>
                    </div>
                    <div class="text-end">
                        <a href="#" id="edit-btn" role="button" class="no-mobile btn btn-outline-secondary btn-lg visually-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                    />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"
                                    />
                            </svg>
                        </a>
                        <button id="fullscreen-btn" type="button" class="no-mobile btn btn-outline-secondary btn-lg" data-bs-toggle="modal"
                            data-bs-target="#contentModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                class="bi bi-fullscreen" viewBox="0 0 16 16">
                                <path
                                    d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z">
                                </>
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

$title = 'Perlite';
$menu = menu($rootDir);
$jsonGraphData = getfullGraph($rootDir);


?>
    <title><?php echo $title ?> </title>
    <div class="divider-top no-mobile"></div>
    <main>
        <div class="divider no-mobile"></div>
        <aside class="bd-aside sticky-nav align-self-start mb-xl-5 px-2">    
            <div class="nav-left p-1">
                <h4 class="headline pb-3" id="vault-name"><?php echo $base ?> </h4>
                    <form id="f1" class="search-my col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                        <input type="search" name="t1" class="form-control form-control-sm perlite-form"
                            placeholder="Search..." aria-label="Search">
                    </form>
                    <div class="me-lg-2 blockquote-footer searchFooter"><a href="#" id="expandGraph">open Graph</a>
                    </div>
                <div class="perlite-navigator">
                    <ul class="list-unstyled ps-0">
                        <?php echo $menu ?>
                    </ul>
                </div>
            </div>
        </aside>
        <div class="divider no-mobile"></div>
        <div class="markdown container-lg container-mobile" id="mdContent"></div>
        <div class="nav-right no-mobile">
            <div class="no-mobile me-lg-5">

                <div id="mynetwork"></div>
                <?php echo $jsonGraphData; ?>
            </div>

        </div>
    </main>


    
    <!-- Modals -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModal" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-break mdModalTitle"></h5>
                    <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mdModalBody"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="."><img src="perlite.svg" height="65" class="me-3" alt="Perlite Logo"></a>
                    <h5 class="modal-title aboutModalTitle"></h5>
                    <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                    <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                    <img src="" class="imagepreview">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="graphModal" tabindex="-1" aria-labelledby="graphModal" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div id="graphContainer" class="modal-content">
                <div class="modal-header">
                    <button id="theme-close-btn" type="button" class="btn-close btn-close-light" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body graphModalBody">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="toggleEmptyNodes">
                        <label class="form-check-label" for="toggleEmptyNodes">
                            <div>hide nodes without link</div>
                        </label>
                    </div>
                    <div id="mynetwork_modal"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src=".js/perlite.js"></script>
</body>

</html>