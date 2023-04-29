<!DOCTYPE html>
<html>

<?php

/*!
  * Version v1.5.4
*/

include('helper.php');

$title = 'Perlite';
$menu = menu($rootDir);
$jsonGraphData = getfullGraph($rootDir);


?>

<!-- 
/*!
  * Perlite (https://github.com/secure-77/Perlite)  
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/
-->


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">

    <link href=".styles/app.css" type="text/css" rel="stylesheet">
    <?php echo loadSettings($rootDir); ?>

    <link id="highlight-js" rel="stylesheet" href=".styles/atom-one-dark.min.css">
    <link rel="stylesheet" href=".styles/perlite.css">
    <link rel="stylesheet" href=".styles/vis.min.css" />
    <link rel="stylesheet" href=".styles/katex.min.css">

    <script src=".js/jquery-3.6.1.min.js"></script>
    <script src=".js/highlight.min.js"></script>
    <script src=".js/bootstrap.bundle.min.js"></script>
    <script src=".js/vis-network.min.js"></script>
    <script src=".js/katex.min.js"></script>
    <script src=".js/auto-render.min.js"></script>
    <script src=".js/mermaid.min.js"></script>


</head>

<body class="theme-dark mod-windows is-frameless is-hidden-frameless obsidian-app show-inline-title show-view-header is-maximized" style="--zoom-factor:1; --font-text-size:15px;">
    <title><?php echo $title ?></title>

    <div class="titlebar">
        <div class="titlebar-inner">
            <div class="titlebar-button-container mod-left">
                <div class="titlebar-button mod-logo"><svg viewBox="0 0 100 100" width="18" height="18" class="logo-full">
                        <defs>
                            <linearGradient id="a" x1="82.85" y1="30.41" x2="51.26" y2="105.9" gradientTransform="matrix(1, 0, 0, -1, -22.41, 110.97)" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stop-color="#6c56cc"></stop>
                                <stop offset="1" stop-color="#9785e5"></stop>
                            </linearGradient>
                        </defs>
                        <polygon points="62.61,0 30.91,17.52 18,45.45 37.57,90.47 65.35,100 70.44,89.8 81,26.39 62.61,0" fill="#34208c"></polygon>
                        <polygon points="81,26.39 61.44,14.41 34.43,35.7 65.35,100 70.44,89.8 81,26.39" fill="url(#a)"></polygon>
                        <polygon points="81,26.39 81,26.39 62.61,0 61.44,14.41 81,26.39" fill="#af9ff4"></polygon>
                        <polygon points="61.44,14.41 62.61,0 30.91,17.52 34.43,35.7 61.44,14.41" fill="#4a37a0"></polygon>
                        <polygon points="34.43,35.7 37.57,90.47 65.35,100 34.43,35.7" fill="#4a37a0"></polygon>
                    </svg><svg viewBox="0 0 100 100" width="18" height="18" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="logo-wireframe">
                        <path d="M 30.91 17.52 L 34.43 35.7 M 61.44 14.41 L 62.61 0 M 34.43 35.7 L 37.57 90.47 M 81 26.39 L 61.44 14.41 L 34.43 35.7 L 65.35 100 M 62.61 0 L 30.91 17.52 L 18 45.45 L 37.57 90.47 L 65.35 100 L 70.44 89.8 L 81 26.39 L 62.61 0 Z"></path>
                    </svg></div>
            </div>
        </div>
    </div>

    <div class="app-container">
        <div class="horizontal-main-container">
            <div class="workspace is-left-sidedock-open">
                <div class="workspace-ribbon side-dock-ribbon mod-left">

                    <a href="."><img src="logo.svg" height="25" class="logo" alt="Perlite Logo"></a>
                    <div class="sidebar-toggle-button mod-left sidebar" aria-label="" aria-label-position="right">


                        <div class="clickable-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon sidebar-left">
                                <path d="M21 3H3C1.89543 3 1 3.89543 1 5V19C1 20.1046 1.89543 21 3 21H21C22.1046 21 23 20.1046 23 19V5C23 3.89543 22.1046 3 21 3Z"></path>
                                <path d="M10 4V20"></path>
                                <path d="M4 7H7"></path>
                                <path d="M4 10H7"></path>
                                <path d="M4 13H7"></path>
                            </svg></div>
                    </div>
                    <div class="side-dock-actions">
                        <div class="clickable-icon side-dock-ribbon-action" aria-label="Open graph view" aria-label-position="right"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-git-fork">
                                <circle cx="12" cy="18" r="3"></circle>
                                <circle cx="6" cy="6" r="3"></circle>
                                <circle cx="18" cy="6" r="3"></circle>
                                <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"></path>
                                <path d="M12 12v3"></path>
                            </svg></div>
                    </div>
                    <div class="side-dock-settings">
                        <div class="clickable-icon side-dock-ribbon-action" aria-label="Help" aria-label-position="right"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon help">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"></path>
                                <path d="M9.09009 9.00003C9.32519 8.33169 9.78924 7.76813 10.4 7.40916C11.0108 7.05019 12.079 6.94542 12.7773 7.06519C13.9093 7.25935 14.9767 8.25497 14.9748 9.49073C14.9748 11.9908 12 11.2974 12 14"></path>
                                <path d="M12 17H12.01"></path>
                            </svg></div>
                        <div class="clickable-icon side-dock-ribbon-action" aria-label="Settings" aria-label-position="right"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-settings">
                                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg></div>
                    </div>
                </div>
                <div class="workspace-split mod-horizontal mod-left-split" style="width: 450px;">
                    <hr class="workspace-leaf-resize-handle left-dock">
                    <div class="workspace-tabs mod-top mod-top-left-space">
                        <hr class="workspace-leaf-resize-handle">
                        <div class="workspace-tab-header-container">
                            <div class="workspace-tab-header-container-inner" style="--animation-dur:250ms;">
                                <div class="workspace-tab-header is-active" draggable="true" aria-label="Files" aria-label-delay="50" data-type="file-explorer">
                                    <div class="workspace-tab-header-inner">
                                        <div class="workspace-tab-header-inner-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-folder-closed">
                                                <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"></path>
                                                <path d="M2 10h20"></path>
                                            </svg></div>
                                        <div class="workspace-tab-header-inner-title">Files</div>
                                        <div class="workspace-tab-header-status-container"></div>
                                        <div class="workspace-tab-header-inner-close-button" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-x">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg></div>
                                    </div>
                                </div>
                                <div class="workspace-tab-header" draggable="true" aria-label="Search" aria-label-delay="50" data-type="search">
                                    <div class="workspace-tab-header-inner">
                                        <div class="workspace-tab-header-inner-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-search">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg></div>
                                        <div class="workspace-tab-header-inner-title">Search</div>
                                        <div class="workspace-tab-header-status-container"></div>
                                        <div class="workspace-tab-header-inner-close-button" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-x">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg></div>
                                    </div>
                                </div>
                            </div>
                            <div class="workspace-tab-header-new-tab"><span class="clickable-icon" aria-label="New tab"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-plus">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg></span></div>
                            <div class="workspace-tab-header-spacer"></div>
                            <div class="workspace-tab-header-tab-list"><span class="clickable-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-chevron-down">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg></span></div>
                        </div> <!-- left dock -->
                        <div class="workspace-tab-container">
                            <div class="workspace-leaf">
                                <hr class="workspace-leaf-resize-handle">
                                <div class="workspace-leaf-content" data-type="file-explorer">
                                    <div class="nav-header">
                                        <div class="nav-buttons-container">
                                            <div class="clickable-icon nav-action-button" aria-label="Collapse all" style="display: none"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-chevrons-down-up">
                                                    <path d="m7 20 5-5 5 5"></path>
                                                    <path d="m7 4 5 5 5-5"></path>
                                                </svg></div>
                                            <div class="clickable-icon nav-action-button" aria-label="Expand all"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-chevrons-up-down">
                                                    <path d="m7 15 5 5 5-5"></path>
                                                    <path d="m7 9 5-5 5 5"></path>
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="nav-files-container node-insert-event" style="position: relative;">
                                        <div class="nav-folder mod-root">
                                            <div class="nav-folder-title" data-path="/">
                                                <div class="nav-folder-collapse-indicator collapse-icon"></div>
                                                <div class="nav-folder-title-content"><?php echo $vaultName ?></div>
                                            </div>
                                            <div class="nav-folder-children">
                                                <div style="width: 612px; height: 0.1px; margin-bottom: 0px;"></div>
                                                <?php echo $menu ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="workspace-leaf" style="display: none;">
                                <!-- search container -->
                                <hr class="workspace-leaf-resize-handle">
                                <div class="workspace-leaf-content" data-type="search">
                                    <div class="nav-header">
                                        <div class="nav-buttons-container">
                                            <div class="clickable-icon nav-action-button is-active" aria-label="Collapse results"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-list">
                                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                                </svg></div>
                                        </div>
                                    </div>
                                    <div class="search-input-container"><input enterkeyhint="search" type="search" placeholder="Type to start search...">
                                        <div class="search-input-clear-button" aria-label="Clear search" style="display: none;"></div>
                                    </div>
                                    <div class="search-info-container" style="display: none;"></div>
                                    <div class="search-result-container mod-global-search node-insert-event" style="position: relative;">
                                        <div class="search-results-children" style="min-height: 0px;">
                                            <div style="width: 1px; height: 0.1px; margin-bottom: 0px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="workspace-split mod-vertical mod-root">
                    <hr class="workspace-leaf-resize-handle">
                    <div class="workspace-tabs mod-active mod-top mod-top-right-space">
                        <hr class="workspace-leaf-resize-handle">
                        <div class="workspace-tab-container">
                            <div class="workspace-leaf mod-active">
                                <hr class="workspace-leaf-resize-handle">
                                <div class="workspace-leaf-content" data-type="markdown" data-mode="source">
                                    <div class="view-header">

                                        <div class="view-actions mobile-display" style="display: flex">
                                            <div class="sidebar-toggle-button mod-left mobile-display" aria-label="" aria-label-position="right">
                                                <div class="clickable-icon">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-book-open">
                                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                                    </svg>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="view-header-nav-buttons" data-section="close" style="display: none">
                                            <div class="clickable-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-x">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg></div>
                                        </div>

                                        <div class="view-header-title-container mod-at-start">
                                            <div class="view-header-title-parent"></div>
                                            <div class="view-header-title" tabindex="-1"></div>
                                        </div>

                                        <div class="view-actions">
                                            <a class="clickable-icon view-action" aria-label="Click to edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-edit-3">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg></a>
                                            <a class="clickable-icon view-action" aria-label="Copy URL">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-link">
                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                </svg>
                                            </a>
                                            <div class="sidebar-toggle-button mod-right" aria-label="" aria-label-position="left">
                                                <div class="clickable-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon sidebar-right">
                                                        <path d="M3 3H21C22.1046 3 23 3.89543 23 5V19C23 20.1046 22.1046 21 21 21H3C1.89543 21 1 20.1046 1 19V5C1 3.89543 1.89543 3 3 3Z"></path>
                                                        <path d="M14 4V20"></path>
                                                        <path d="M20 7H17"></path>
                                                        <path d="M20 10H17"></path>
                                                        <path d="M20 13H17"></path>
                                                    </svg></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="view-content" style="padding: 0px; overflow: hidden; position: relative;">
                                        <div id="graph_content" style="display: none">
                                            <div id="graph_all"></div>
                                            <div id="loading-text" class="markdown-preview-view">0%</div>
                                            <div class="graph-controls is-close">
                                                <div class="clickable-icon graph-controls-button mod-close" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-x">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg></div>
                                                <div class="clickable-icon graph-controls-button mod-open" aria-label="Open graph settings"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-settings">
                                                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg></div>
                                                <div class="clickable-icon graph-controls-button mod-reset" aria-label="Restore default settings"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-rotate-ccw">
                                                        <path d="M3 2v6h6"></path>
                                                        <path d="M3 13a9 9 0 1 0 3-7.7L3 8"></path>
                                                    </svg></div>
                                                <div class="tree-item graph-control-section mod-filter">
                                                    <div class="tree-item-self">
                                                        <div class="tree-item-inner">
                                                            <header class="graph-control-section-header">Options</header>
                                                        </div>
                                                    </div>
                                                    <div class="tree-item-children">

                                                        <div class="setting-item mod-toggle">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name" aria-label="Show files that are not linked to any other file">Orphans</div>
                                                                <div class="setting-item-description"></div>
                                                            </div>
                                                            <div class="setting-item-control">
                                                                <div class="checkbox-container mod-small graphNoLinkOption is-enabled"><input type="checkbox" tabindex="0"></div>
                                                            </div>
                                                        </div>
                                                        <div class="setting-item mod-toggle">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name" aria-label="Show files that are not linked to any other file">Auto-Reload</div>
                                                                <div class="setting-item-description"></div>
                                                            </div>
                                                            <div class="setting-item-control">
                                                                <div class="checkbox-container mod-small graphAutoReloadOption is-enabled"><input type="checkbox" tabindex="0"></div>
                                                            </div>
                                                        </div>
                                                        <div class="setting-item">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name">Style</div>
                                                            </div>
                                                            <div class="setting-item-control">
                                                                <select id="graphStyleDropdown" class="dropdown">
                                                                    <option value="dynamic">Dynamic (Default)</option>
                                                                    <option value="continuous">Continuous</option>
                                                                    <option value="discrete">Discrete</option>
                                                                    <option value="diagonalCross">DiagonalCross</option>
                                                                    <option value="straightCross">StraightCross</option>
                                                                    <option value="horizontal">Horizontal</option>
                                                                    <option value="vertical">Vertical</option>
                                                                    <option value="curvedCW">CurvedCW</option>
                                                                    <option value="curvedCCW">CurvedCCW</option>
                                                                    <option value="cubicBezier">CubicBezier</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tree-item graph-control-section mod-display">
                                                    <div class="tree-item-self">
                                                        <div class="tree-item-inner">
                                                            <header class="graph-control-section-header">Display</header>
                                                        </div>
                                                    </div>
                                                    <div class="tree-item-children">
                                                        <div class="setting-item mod-slider">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name">Node size</div>
                                                                <div class="setting-item-description"></div>
                                                            </div>
                                                            <div class="setting-item-control"><input class="slider nodeSize" type="range" min="5" max="40" step="2"></div>

                                                        </div>
                                                        <div class="setting-item mod-slider">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name">Link thickness</div>
                                                                <div class="setting-item-description"></div>

                                                            </div>
                                                            <div class="setting-item-control"><input class="slider linkThickness" type="range" min="0.1" max="5" step="any"></div>

                                                        </div>
                                                        <div class="setting-item mod-slider">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name">Link distance</div>
                                                                <div class="setting-item-description"></div>
                                                            </div>
                                                            <div class="setting-item-control"><input class="slider linkDistance" type="range" min="30" max="1000" step="10"></div>

                                                        </div>

                                                        <div class="setting-item">
                                                            <div class="setting-item-info">
                                                                <div class="setting-item-name"></div>
                                                                <div class="setting-item-description"></div>
                                                            </div>
                                                            <div class="setting-item-control"><button id="graphReload" class="mod-cta">Reload</button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="markdown-reading-view" style="width: 100%; height: 100%; ">
                                            <div class="markdown-preview-view markdown-rendered node-insert-event allow-fold-headings show-indentation-guide allow-fold-lists" style="tab-size: 4;">
                                                <div class="markdown-preview-pusher" style="width: 1px; height: 0.1px; margin-bottom: 0px;"></div>
                                                <div class="inline-title" tabindex="-1" enterkeyhint="done"></div>
                                                <div id="mdContent"></div>
                                                <div class="graph-controls is-close">
                                                    <div class="clickable-icon graph-controls-button mod-close" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-x">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg></div>
                                                    <div class="clickable-icon graph-controls-button mod-open" aria-label="Open graph settings"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-settings">
                                                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg></div>
                                                    <div class="clickable-icon graph-controls-button mod-reset" aria-label="Restore text settings"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-rotate-ccw">
                                                            <path d="M3 2v6h6"></path>
                                                            <path d="M3 13a9 9 0 1 0 3-7.7L3 8"></path>
                                                        </svg></div>
                                                    <div class="tree-item graph-control-section mod-display">
                                                        <div class="tree-item-self">
                                                            <div class="tree-item-inner">
                                                                <header class="graph-control-section-header">Display</header>
                                                            </div>
                                                        </div>
                                                        <div class="tree-item-children">
                                                            <div class="setting-item mod-slider">
                                                                <div class="setting-item-info">
                                                                    <div class="setting-item-name">Font size</div>
                                                                    <div class="setting-item-description"></div>
                                                                </div>
                                                                <div class="setting-item-control"><input class="slider font-size" type="range" min="10" max="30" step="1"></div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="workspace-split mod-horizontal mod-right-split" style="width: 450px;">
                    <hr class="workspace-leaf-resize-handle right-dock">
                    <div class="workspace-tabs mod-top mod-top-right-space">
                        <hr class="workspace-leaf-resize-handle">
                        <div class="workspace-tab-container">
                            <!-- right dock -->
                            <div class="workspace-leaf mod-active">
                                <hr class="workspace-leaf-resize-handle">
                                <div class="workspace-leaf-content" data-type="backlink">
                                    <div class="view-header" style="display: none">
                                        <div class="clickable-icon view-header-icon" draggable="true" aria-label="Drag to rearrange"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon links-coming-in">
                                                <path d="M8.70467 12C8.21657 11.6404 7.81269 11.1817 7.52044 10.6549C7.22819 10.1281 7.0544 9.54553 7.01086 8.94677C6.96732 8.348 7.05504 7.74701 7.26808 7.18456C7.48112 6.62212 7.81449 6.11138 8.24558 5.68697L10.7961 3.17516C11.5978 2.41258 12.6716 1.99062 13.7861 2.00016C14.9007 2.0097 15.9668 2.44997 16.755 3.22615C17.5431 4.00234 17.9902 5.05233 17.9998 6.14998C18.0095 7.24763 17.5811 8.30511 16.8067 9.09467L15.9014 10"></path>
                                                <path d="M11.2953 8C11.7834 8.35957 12.1873 8.81831 12.4796 9.34512C12.7718 9.87192 12.9456 10.4545 12.9891 11.0532C13.0327 11.652 12.945 12.253 12.7319 12.8154C12.5189 13.3779 12.1855 13.8886 11.7544 14.313L9.20392 16.8248C8.40221 17.5874 7.32844 18.0094 6.21389 17.9998C5.09933 17.9903 4.03318 17.55 3.24504 16.7738C2.4569 15.9977 2.00985 14.9477 2.00016 13.85C1.99047 12.7524 2.41893 11.6949 3.19326 10.9053L4.09859 10"></path>
                                                <path d="M17 21L14 18L17 15"></path>
                                                <path d="M21 18H14"></path>
                                            </svg></div>
                                        <div class="view-header-title-container mod-at-start">
                                            <div class="view-header-title-parent"></div>
                                        </div>
                                        <div class="view-actions"><a class="clickable-icon view-action" aria-label="Unlink tab" style="display: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-link">
                                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                </svg></a><a class="clickable-icon view-action mod-pin-leaf" aria-label="Pin" style="display: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-pin">
                                                    <line x1="12" y1="17" x2="12" y2="22"></line>
                                                    <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"></path>
                                                </svg></a><a class="clickable-icon view-action" aria-label="More options"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-more-vertical">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="12" cy="5" r="1"></circle>
                                                    <circle cx="12" cy="19" r="1"></circle>
                                                </svg></a></div>
                                    </div>

                                    <!-- Grap Viewer -->
                                    <div class="view-content">

                                        <div class="nav-header">
                                            <div class="view-header-nav-buttons">
                                                <a class="clickable-icon view-action" aria-label="Open localGraph" style="display: none"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-git-fork">
                                                        <circle cx="12" cy="18" r="3"></circle>
                                                        <circle cx="6" cy="6" r="3"></circle>
                                                        <circle cx="18" cy="6" r="3"></circle>
                                                        <path d="M18 9v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9"></path>
                                                        <path d="M12 12v3"></path>
                                                    </svg></a>
                                                <a class="clickable-icon view-action" aria-label="Open outline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-list">
                                                        <line x1="8" y1="6" x2="21" y2="6" />
                                                        <line x1="8" y1="12" x2="21" y2="12" />
                                                        <line x1="8" y1="18" x2="21" y2="18" />
                                                        <line x1="3" y1="6" x2="3.01" y2="6" />
                                                        <line x1="3" y1="12" x2="3.01" y2="12" />
                                                        <line x1="3" y1="18" x2="3.01" y2="18" />
                                                    </svg>


                                                </a>
                                            </div>



                                        </div>

                                        <div class="backlink-pane node-insert-event" style="position: relative;">
                                            <div id="outline" class="outline" style="display: none">
                                                <h3>Table of Contents</h3>

                                                <div id="toc"></div>

                                            </div>
                                            <div class="tree-item-self" aria-label-position="left"><span class="tree-item-icon collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon right-triangle">
                                                        <path d="M3 8L12 17L21 8"></path>
                                                    </svg></span>
                                                <div class="tree-item-inner">Linked mentions</div>
                                                <div class="tree-item-flair-outer"><span class="tree-item-flair" id="nodeCount">0</span></div>

                                            </div>
                                            <div id="mynetwork"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="workspace-ribbon side-dock-ribbon mod-right is-collapsed"></div>
            </div>
        </div>
        <div class="status-bar">
            <div class="status-bar-item plugin-backlink mod-clickable"><span id="backlinksCount">0</span><span>&nbsp;backlinks</span></div>
            <div class="status-bar-item plugin-word-count">
                <span class="status-bar-item-segment" id="wordCount">0 words</span>
                <span class="status-bar-item-segment" id="charCount">0 characters</span>
            </div>
        </div>
    </div>
    <!-- Graph settings -->
    <div style="display: none">
        <div><?php echo $jsonGraphData; ?></div>
        <p class="graph-view color-line"></p>
        <p class="graph-view color-fill"></p>
        <p class="graph-view color-text"></p>
        <p class="graph-view color-fill-highlight"></p>
        <p class="graph-view color-fill-focused"></p>
        <p class="graph-view color-line-hightlight"></p>
        <p class="vault"><?php echo $vaultName ?></p>
        <p class="perliteTitle"><?php echo $title ?></p>
    </div>
    <!-- tool tip -->
    <div class="tooltip" style="top: 83.9531px; left: 1032.51px; width: 180.984px; height: 25px; display: none">
        <div class="tooltip-arrow" style="left: initial; right: 43px;"></div>
    </div>
    <!-- about modal -->
    <div id="about" class="modal-container mod-dim" style="display: none">
        <div class="modal-bg" style="opacity: 0.85;"></div>
        <div class="modal">
            <div class="modal-close-button"></div>
            <div class="modal-title"> <a href="."><img src="logo.svg" height="35" alt="Perlite Logo" style="padding-top: 10px"></a> Perlite</div>
            <div class="aboutContent modal-content"></div>
        </div>
    </div>
    <!-- perlite settings -->
    <div id="settings" class="modal-container mod-dim" style="display: none">
        <div class="modal-bg" style="opacity: 0.85;"></div>
        <div id="settings" class="modal mod-settings">
            <div class="modal-close-button"></div>
            <div class="modal-title">Perlite Settings</div>
            <div class="setting-item-description">Some settings need a page reload to take affect!</div>
            <div class="modal-content vertical-tabs-container">
                <div class="vertical-tab-content-container">
                    <div class="vertical-tab-content">
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Theme</div>
                                <div class="setting-item-description">Select installed theme</div>
                            </div>
                            <div class="setting-item-control">
                                <select id="themeDropdown" class="dropdown">
                                    <option value="">Default</option>
                                </select><button id="resetTheme" class="mod-cta">Reset</button>
                            </div>

                        </div>

                        <div class="setting-item mod-toggle">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Dark mode</div>
                                <div class="setting-item-description">Choose Perlite's default color scheme.</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="checkbox-container is-enabled darkModeOption"><input type="checkbox" tabindex="0"></div>
                            </div>
                        </div>

                        <div class="setting-item setting-item-heading">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Sizes</div>
                                <div class="setting-item-description"></div>
                            </div>
                            <div class="setting-item-control"></div>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Font size</div>
                                <div class="setting-item-description">Font size in pixels that affects reading
                                    view.</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="clickable-icon setting-editor-extra-setting-button" aria-label="Restore text settings">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-rotate-ccw">
                                        <path d="M3 2v6h6"></path>
                                        <path d="M3 13a9 9 0 1 0 3-7.7L3 8"></path>
                                    </svg>
                                </div><input class="slider font-size" type="range" min="10" max="30" step="1">
                            </div>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Panel sizes</div>
                                <div class="setting-item-description">Reset the panel sizes</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="clickable-icon setting-editor-extra-setting-button" aria-label="Restore panel settings">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-rotate-ccw">
                                        <path d="M3 2v6h6"></path>
                                        <path d="M3 13a9 9 0 1 0 3-7.7L3 8"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="setting-item setting-item-heading">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Mobile</div>
                                <div class="setting-item-description"></div>
                            </div>
                            <div class="setting-item-control"></div>
                        </div>

                        <div class="setting-item mod-toggle">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Pop Up</div>
                                <div class="setting-item-description">Open a popup by clicking on an internal link</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="checkbox-container popUpSetting"><input type="checkbox" tabindex="0"></div>
                            </div>
                        </div>

                        <div class="setting-item setting-item-heading">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Advanced</div>
                                <div class="setting-item-description"></div>
                            </div>
                            <div class="setting-item-control"></div>
                        </div>
                        <div class="setting-item mod-toggle">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Disable Pop Hovers</div>
                                <div class="setting-item-description">Disable popups by hover</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="checkbox-container disablePopUp"><input type="checkbox" tabindex="0"></div>
                            </div>
                        </div>
                        <div class="setting-item mod-toggle">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Show inline title</div>
                                <div class="setting-item-description">Displays the filename as an title inline with the
                                    file contents.</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="checkbox-container is-enabled inlineTitleOption"><input type="checkbox" tabindex="0"></div>
                            </div>
                        </div>
                        <div class="setting-item mod-toggle">
                            <div class="setting-item-info">
                                <div class="setting-item-name">Collapse Metadata</div>
                                <div class="setting-item-description">Collapse the Front Matter Metadata contents by default.</div>
                            </div>
                            <div class="setting-item-control">
                                <div class="checkbox-container metadataOption"><input type="checkbox" tabindex="0"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pop Hover -->
    <div class="popover hover-popover" style="display: none">
        <div class="markdown-embed is-loaded">
            <div class="markdown-embed-content">
                <div class="markdown-preview-view markdown-rendered node-insert-event show-indentation-guide">
                    <div class="markdown-preview-sizer markdown-preview-section" style="padding-bottom: 0px; min-height: 100%;">
                        <div class="markdown-preview-pusher" style="height: 0.1px; margin-bottom: 0px;"></div>
                        <div class="mod-header">
                            <div class="inline-title pophover-title" spellcheck="false" tabindex="-1" enterkeyhint="done"></div>
                            <div id='mdHoverContent'></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="markdown-embed-content" style="display: none;"></div>
        </div>


    </div>
    <!-- pop up -->
    <div id="popUp" class="modal-container mod-dim" style="display: none">
        <div class="modal-bg" style="opacity: 0.85;"></div>
        <div class="modal">
            <div class="modal-close-button"></div>
            <div class="popup-modal-title inline-title"></div>
            <div class="goToLink"></div>
            <div id='popUpContent' class="modal-content"></div>
        </div>
    </div>
    <!-- img modal -->
    <div id="img-modal" class="modal-container mod-dim" style="display: none">
        <div class="modal-bg" style="opacity: 0.85;"></div>
        <div class="modal">
            <div class="modal-close-button"></div>
            <div class="modal-title img-modal-title inline-title"></div>
            <div class="goToLink"></div>
            <div id='img-content' class="modal-content"></div>
        </div>
    </div>


    </div>
    <script src=".js/perlite.js"></script>
</body>

</html>