/// <reference path="./jquery-3.6.1.min.js" />

/*!
  * Perlite (https://github.com/secure-77/Perlite)
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/



// define home file
const homeFile = "README";


// get markdown content
function getContent(str, home = false, popHover = false) {

  // reset content if request is empty
  if (str.length == 0) {
    document.getElementById("mdContent").innerHTML = "";
    document.getElementsByClassName("modal-body")[0].innerHTML = "";
    return;
  } else {

    requestPath = "content.php?mdfile=" + str;

    if (home) {
      if ($("div.no-mobile").css("display") == "none") {
        return
      }
      requestPath = "content.php?home";

    }

    mdContent = $("#mdContent")[0]


    $.ajax({
      url: requestPath, success: function (result) {

        if (popHover == false) {

          // set content
          $("#mdContent").html(result);

          // set word and char count
          $("#wordCount").text($(".wordCount").text() + ' words');
          $("#charCount").text($(".charCount").text() + ' characters');

          // set Browser, document title and nav path
          var title = $("div.mdTitleHide").first().text();
          if (title) {

            hrefTitle = '<a href=?link=' + encodeURIComponent(title) + '>' + title + '</a>'
            title = title.substring(1)
            titleElements = title.split('/')
            title = titleElements.splice(-1)
            parentTitle = titleElements.join(' / ')
            if (parentTitle) {
              parentTitle = parentTitle + ' / ';
            }
            $("div.view-header-title-parent").text(parentTitle);
            $("div.view-header-title").text(title);
            $(".inline-title").text(title);

            $("title").text(title + ' - ' + $("p.vault").text() + ' - ' + $("p.perliteTitle").text());

            // set edit button url       
            $('.clickable-icon.view-action[aria-label="Click to edit"]')
              .attr("href", "obsidian://open?vault=" + encodeURIComponent($("p.vault").text()) + "&file=" + encodeURIComponent(title))

          }

          // Outlines
          var toc = "";
          var level = 0;

          document.getElementById("mdContent").innerHTML =
            document.getElementById("mdContent").innerHTML.replace(
              /<h([\d])>([^<]+)<\/h([\d])>/gi,
              function (str, openLevel, titleText, closeLevel) {

                if (openLevel != closeLevel) {
                  return str;
                }

                if (openLevel > level) {
                  toc += (new Array(openLevel - level + 1)).join('<div class="tree-item"><div class="tree-item-children">');
                } else if (openLevel < level) {
                  toc += (new Array(level - openLevel + 1)).join("</div></div>");
                }

                level = parseInt(openLevel);

                var anchor = titleText.replace(/ /g, "_");
                toc += '<div class="tree-item-self is-clickable"><a href="#' + anchor + '">' + titleText
                  + '</a></div>';

                return "<h" + openLevel + "><a name='" + anchor + "' >"
                  + "" + "</a>" + titleText + "</h" + closeLevel + ">";

              }
            );

          if (level) {
            toc += (new Array(level + 1)).join("</div></div>");
          }

          document.getElementById("toc").innerHTML = toc;


          // add Image Click popup
          $(".pop").on("click", function () {

            var path = $(this).find("img").attr("src");
            result = '<div class="modal-body imgModalBody"><img src="' + path + '" class="imagepreview"></div>';
            $("div.modal-content").html(result);
            $(".modal").css("width", "unset");
            $(".modal").css("height", "unset");
            $(".modal").css("max-width", "100%");
            $(".modal").css("max-height", "100%");
            $(".modal-title").text("Image preview");
            $(".modal-container.mod-dim").css("display", "flex");

          });


          // trigger graph render on side bar
          renderGraph(false, str);

          //resize graph on windows rezise
          $(window).resize(function () {
            renderGraph(false, str, false);
          });


          // update the url
          if (home == false) {
            window.history.pushState({}, "", location.protocol + '//' + location.host + location.pathname + "?link=" + str);
          }


          // on Tag click -> start search
          $('.tag').click(function (e) {

            e.preventDefault();

            target = $(e.target);
            $('.workspace-tab-header[data-type="search"]').click();
            $('*[type="search"]').val(this.text);
            search(this.text);

            // on mobile go to search
            if ($(window).width() < 990) {

              $('.workspace').addClass('is-left-sidedock-open');
              $('.mod-left-split').removeClass('is-sidedock-collapse');
              $('.mod-left').removeClass('is-collapsed');
              $('.workspace-ribbon.side-dock-ribbon.mod-left').css('display', 'flex');

            };

          });

          // Toogle Front Matter Meta Container
          $('.frontmatter-container-header').click(function (e) {

            e.preventDefault();

            if ($('.frontmatter-container').hasClass('is-collapsed')) {
              $('.frontmatter-container').removeClass('is-collapsed');
            } else {
              $('.frontmatter-container').addClass('is-collapsed');
            }

          });

          // on hover internal link

          var currentMousePos = { x: -1, y: -1 };
          $(document).mousemove(function (event) {
            currentMousePos.x = event.pageX;
            currentMousePos.y = event.pageY;
          });

          stopThis = false;
          // enter the hover box
          $('.popover.hover-popover').mouseenter(function (e) {
            stopThis = true
            $('.popover.hover-popover').css('display', 'unset');
          })
          // leave the hover box
          $('.popover.hover-popover').mouseleave(function (e) {
            e.preventDefault();

            hoverTimer = setTimeout(function () {

              $('.popover.hover-popover').css('display', 'none');
              stopThis = false;

            }, 500);
          })

          // leave the link
          $('.internal-link').mouseleave(function (e) {
            e.preventDefault();

            hoverTimer = setTimeout(function () {

              if (stopThis == false) {
                $('.popover.hover-popover').css('display', 'none');
              }
            }, 1200);
          })

          $('.internal-link').mouseenter(function (e) {
            e.preventDefault();

            // update position for hover element
            $('.popover.hover-popover').css({ top: currentMousePos.y, left: currentMousePos.x });

            const urlParams = new URLSearchParams(this.href.split('?')[1]);
            if (urlParams.has('link')) {
              var target = urlParams.get('link');
              target = encodeURIComponent(target);
            }
            // get content of link
            if (target) {
              getContent(target, false, true)
            }

          });

          //check setting if metadata is collapsed or not
          if ($('.metadataOption').hasClass('is-enabled')) {
            $('.frontmatter-container-header').trigger('click')
          }
          mdContent = $("#mdContent")[0]

          // handle pop up and hover
        } else {

          // set content
          $("#mdHoverContent").html(result);
          $("#popUpContent").html(result);

          // set title
          var title = $("div.mdTitleHide")[1].innerText;
          title = title.substring(1)
          titleElements = title.split('/')
          title = titleElements.splice(-1)
          $(".inline-title.pophover-title").text(title);
          $(".popup-modal-title").text(title);


          // show pophover
          $('.popover.hover-popover').css('display', 'unset');

          mdContent = $("#mdHoverContent")[0]

        }

        // highlight code     
        hljs.highlightAll();

        // run mobile settings
        isMobile();

        //render LaTeX (Katex)
        renderMathInElement(mdContent,
          {
            delimiters: [
              { left: "$$", right: "$$", display: true },
              { left: "\\[", right: "\\]", display: true },
              { left: "$", right: "$", display: false },
              { left: "\\(", right: "\\)", display: false }
            ]
          }
        );

        // clean internal links in mermaid elements
        var mermaids = document.getElementsByClassName("language-mermaid");

        for (var i = 0; i < mermaids.length; i++) {

          var mermaidLinks = mermaids[i].getElementsByTagName('a');

          for (f = 0; f < mermaidLinks.length;) {

            var linkElement = mermaidLinks[f]

            if (linkElement.getAttribute("href").startsWith("?link")) {

              var textonly = '[[' + linkElement.innerHTML + ']]';
              linkElement.replaceWith(textonly)
            }

          }

        }

        //render mermaid
        mermaid.init(undefined, document.querySelectorAll(".language-mermaid"));


      }
    });
  }
};

// vis js stuff
function renderGraph(modal, path = "", filter_emptyNodes = false) {

  // no graph found exit
  if ($("#allGraphNodes").length == 0 || $("#allGraphNodes").text == '[]') {
    console.log("Graph: no data found")
    return;
  }


  var visNodes = document.getElementById('allGraphNodes').innerHTML;
  var visEdges = document.getElementById('allGraphEdges').innerHTML;

  var jsonNodes = JSON.parse(visNodes);
  var jsonEdges = JSON.parse(visEdges);

  var currId = 0;
  path = decodeURIComponent(path);
  if (path == 'home') {
    path = '/' + homeFile;
  }


  // reset backlings count

  $('#backlinksCount').text(0);

  // get current node
  for (const x in jsonNodes) {
    if (path == ('/' + (jsonNodes[x]['title']).replace('&amp;', '&'))) {
      currId = jsonNodes[x]['id'];
      break;
    }
    else if (modal == false) {
      currId = -1;
    }
  }

  // cancel graph display if no node was found
  if (currId == -1) {
    return;
  }

  // Graph Defaults

  nodeSize = parseInt($('.slider.nodeSize').val())
  varLinkDistance = parseInt($('.slider.linkDistance').val())
  varLinkThickness = parseFloat($('.slider.linkThickness').val())
  varGraphStyle = $('#graphStyleDropdown').val()


  var options = {
    interaction: {
      hover: true,
    },
    // configure: {
    //   enabled: true,
    //   filter: 'nodes,edges',
    //   container: container,
    //   showButton: true
    // } ,
    edges: {
      length: varLinkDistance,
      width: varLinkThickness,
      color: getComputedStyle(document.querySelector('.graph-view.color-line')).color,
      smooth: {
        type: varGraphStyle,
        enabled: true,
      }
    },

    nodes: {
      shape: 'dot',
      size: nodeSize,
      font: {
        size: 16,
        color: getComputedStyle(document.querySelector('.graph-view.color-text')).color,
      },
      borderWidth: 1,
      color: {
        background: getComputedStyle(document.querySelector('.graph-view.color-fill')).color,
        border: getComputedStyle(document.querySelector('.graph-view.color-fill')).color,
        highlight: {
          border: getComputedStyle(document.querySelector('.graph-view.color-fill')).color,
          background: getComputedStyle(document.querySelector('.graph-view.color-fill')).color,
        },
        hover: {
          border: getComputedStyle(document.querySelector('.graph-view.color-fill')).color,
          background: getComputedStyle(document.querySelector('.graph-view.color-fill-highlight')).color,
        },
      },
    }
  };

  var network;

  // show the whole graph
  if (modal) {

    var container_modal = document.getElementById('graph_all');

    var nodes = new vis.DataSet(jsonNodes);
    var edges = new vis.DataSet(jsonEdges);

    edgeView = edges;
    nodeView = nodes;

    if (filter_emptyNodes) {
      nodeView = new vis.DataView(nodes, {
        filter: function (node) {
          connEdges = edgeView.get({
            filter: function (edge) {
              if (node.id == currId) {
                return true;
              };
              return (
                (edge.to == node.id) || (edge.from == node.id));
            }
          });
          return connEdges.length > 0;
        }
      });

    }

    // provide the data in the vis format
    var data = {
      nodes: nodeView,
      edges: edgeView
    };

    network = new vis.Network(container_modal, data, options);
    //network.selectNodes([currId]);
    var node = network.body.nodes[currId];
    node.setOptions({
      font: {
        size: 20
      },
      color: {
        background: getComputedStyle(document.querySelector('.graph-view.color-fill-focused')).color,
      },
    });



    // local Graph
  } else {

    var myNodes = [];
    var myEdges = [];

    options['edges']['length'] = 300;

    // add current node
    for (const x in jsonNodes) {
      jsonNodes[x]['label'] = (jsonNodes[x]['label']).replace('&amp;', '&')
      jsonNodes[x]['title'] = (jsonNodes[x]['title']).replace('&amp;', '&')
      if (path == ('/' + jsonNodes[x]['title'])) {
        myNodes.push(jsonNodes[x])
        curNode = myNodes[0]
        curNode.size = '20';
        curNode.color = {
          background: getComputedStyle(document.querySelector('.graph-view.color-fill-focused')).color,
        };

        break;
      }
    }


    function idExists(id) {
      return myNodes.some(function (el) {
        return el.id === id;
      });
    }


    // search linked nodes
    for (const y in jsonEdges) {
      if (currId == jsonEdges[y]['from']) {

        // add "To" node to the nodes
        for (const x in jsonNodes) {
          if (jsonEdges[y]['to'] == jsonNodes[x]['id']) {
            if (!idExists(jsonNodes[x]['id'])) {
              jsonNodes[x]['label'] = (jsonNodes[x]['label']).replace('&amp;', '&')
              jsonNodes[x]['title'] = (jsonNodes[x]['title']).replace('&amp;', '&')
              myNodes.push(jsonNodes[x])
            }
            break;
          }
        }

        // add the link
        myEdges.push(jsonEdges[y]);

        // search the backlinks
      } else if (currId == jsonEdges[y]['to']) {

        // add "From" node to the nodes
        for (const x in jsonNodes) {
          if (jsonEdges[y]['from'] == jsonNodes[x]['id']) {
            if (!idExists(jsonNodes[x]['id'])) {
              myNodes.push(jsonNodes[x])
            }
            break;
          }
        }

        // add the backlink
        myEdges.push(jsonEdges[y]);
        curr = $('#backlinksCount').text();
        $('#backlinksCount').text(parseInt(curr) + 1);
      }

    }

    // build network structure

    var nodes = new vis.DataSet(myNodes);
    var edges = new vis.DataSet(myEdges);


    var data = {
      nodes: nodes,
      edges: edges
    };

    // update linked mentions
    $("#nodeCount").text(nodes.length - 1);

    var container = document.getElementById('mynetwork');
    network = new vis.Network(container, data, options);


  }

  // jump to file function
  if (network) {

    network.on("click", function (properties) {

      if (!properties.nodes.length) return;
      var node = nodes.get(properties.nodes[0]);
      var glink = '?link=' + encodeURIComponent('/' + node.title);
      window.open(glink, "_self");
    });
  }

};

// change mobile settings
function isMobile() {

  if ($(window).width() < 990) {

    hideLeftMobile();

    //disable mousehover on mobile
    $('.internal-link').unbind("mouseenter");
    $('.internal-link').unbind("mouseleave");

    //override click for internal-links to use popUp instead
    if ( $('.popUpSetting').hasClass('is-enabled')) {
    $('.internal-link').click(function (e) {
      e.preventDefault();
      const urlParams = new URLSearchParams(this.href.split('?')[1]);
      if (urlParams.has('link')) {
        var target = urlParams.get('link');
        target = encodeURIComponent(target);
      }

      if (target) {
        getContent(target, false, true)
      }
      $("#popUp").css("display", "flex");
      $(".goToLink").html('<a href="' + this.href + '"> go to site</a><br><br>')
    })

    }
  }

};

function hideLeftMobile() {

  $('.workspace').removeClass('is-left-sidedock-open');
  $('.mod-left-split').addClass('is-sidedock-collapse');
  $('.mod-left').addClass('is-collapsed');
  $('.workspace-ribbon.side-dock-ribbon.mod-left').css('display', 'none');

};

// search  
function search(str) {
  if (str.length == 0) {
    $("div.search-results-children").html("");
    return;
  } else {

    str = encodeURIComponent(str);

    $.ajax({
      url: "content.php?search=" + str, success: function (result) {

        $("div.search-results-children").html(result);
        let preCodes = $("div.search-results-children").find("pre code")
        for (var i = 0; i < preCodes.length; i++) {
          hljs.highlightElement(preCodes[i]);
        }
      }
    });
  }
};

// edit button
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
};

// helper
function replaceClass(oldClass, newClass) {
  var elem = $("." + oldClass);
  elem.removeClass(oldClass);
  elem.addClass(newClass);
};

// search entry
function toggleSearchEntry(e) {

  el = $(e.target);
  //e.preventDefault();

  if (el.hasClass('svg-icon right-triangle')) {
    el = el.parent().parent().parent()
  } else if (el.hasClass('tree-item-icon collapse-icon')) {
    el = el.parent().parent()
  } else {
    return
  }

  if (el.hasClass('is-collapsed')) {
    el.removeClass('is-collapsed');
    el.find('.search-result-file-matches').css("display", "unset");

  } else {
    el.addClass('is-collapsed');
    el.find('.search-result-file-matches').css("display", "none");
  }

};

// nav menu stuff
function toggleNavFolder(e) {
  el = $(e.target);

  if (el.hasClass('nav-folder-title')) {
    el = el.parent()
  } else if (el.hasClass('nav-folder-collapse-indicator collapse-icon') || el.hasClass('nav-folder-title-content')) {
    el = el.parent().parent()
  } else if (el.hasClass('svg-icon right-triangle')) {
    el = el.parent().parent().parent()
  } else {
    return
  }

  if (el.hasClass('is-collapsed')) {
    el.removeClass('is-collapsed');

  } else {
    el.addClass('is-collapsed');
  }
};

function openNavMenu(target, openAll = false) {

  // open nav menu to target
  var navId = decodeURIComponent(target);
  linkname = navId.match(/([^\/]*)\/*$/)[1]

  // search and open tree reverse
  navId = navId.replace(/[^a-zA-Z0-9\-]/g, '_');
  var next = $('#' + navId).parent().closest('.collapse');

  do {
    next.collapse('show');
    next.parent().removeClass('is-collapsed')
    next = next.parent().closest('.collapse');
  }
  while (next.length != 0);


  // set focus to link
  var searchText = linkname;

  $("div").filter(function () {
    return $(this).text() === searchText;
  }).parent().addClass('perlite-link-active is-active');;

};

function hideTooltip() {
  $('.tooltip').css("display", "none")
};



// on document ready stuff
$(document).ready(function () {


  // load settings from storage
  // ----------------------------------------

  // text size
  if (localStorage.getItem('Font_size')) {
    $('body').css('--font-text-size', localStorage.getItem('Font_size') + 'px');
  }

  $('.slider.font-size').val(parseInt($('body').css('--font-text-size')));

  // inline title
  if (localStorage.getItem('InlineTitle') === 'hide') {
    $('.inlineTitleOption').removeClass('is-enabled')
    $('body').removeClass('show-inline-title')
  };

  // metadata
  if (localStorage.getItem('Metadata') === 'hide') {
    $('.metadataOption').addClass('is-enabled')
    $('.frontmatter-container').addClass('is-collapsed');
  };

  // light mode
  if (localStorage.getItem('lightMode') === 'true') {
    $('body').removeClass('theme-dark')
    $('body').addClass('theme-light')
    $('.darkModeOption').removeClass('is-enabled')
  };

  // popUp Setting
  if (localStorage.getItem('popUpEnabled') === 'true') {
    $('.popUpSetting').addClass('is-enabled')
  };



  // graph settings & defaults

  if (localStorage.getItem('Graph_Style')) {
    $('#graphStyleDropdown').val(localStorage.getItem('Graph_Style'))
  } else {
    $('#graphStyleDropdown').val('dynamic')
  }

  if (localStorage.getItem('Graph_NodeSize')) {
    $('.slider.nodeSize').val(localStorage.getItem('Graph_NodeSize'))
  } else {
    $('.slider.nodeSize').val(12)
  }

  if (localStorage.getItem('Graph_LinkDistance')) {
    $('.slider.linkDistance').val(localStorage.getItem('Graph_LinkDistance'))
  } else {
    $('.slider.linkDistance').val(150)
  }

  if (localStorage.getItem('Graph_LinkThickness')) {
    $('.slider.linkThickness').val(localStorage.getItem('Graph_LinkThickness'))
  } else {
    $('.slider.linkThickness').val(1)
  }

  if (localStorage.getItem('Graph_Orphans') === 'hide') {
    $('.graphNoLinkOption').removeClass('is-enabled')
  };

  if (localStorage.getItem('Graph_Autoreload') === 'no') {
    $('.graphAutoReloadOption').removeClass('is-enabled')
  };


  // panel sizes
  if (localStorage.getItem('leftSizePanel')) {
    $('.workspace-split.mod-horizontal.mod-left-split').css("width", localStorage.getItem('leftSizePanel'))
  };

  if (localStorage.getItem('rightSizePanel')) {
    $('.workspace-split.mod-horizontal.mod-right-split').css("width", localStorage.getItem('rightSizePanel'))
  };


  //check for graph and hide local graph if none exists
  if ($("#allGraphNodes").length == 0 || $("#allGraphNodes").text == '[]') {

    $('.clickable-icon.side-dock-ribbon-action[aria-label="Open graph view"]').css('display', 'none')
    $('.clickable-icon.view-action[aria-label="Open outline"]').css('display', 'none')
    $('.clickable-icon.view-action[aria-label="Open localGraph"]').css('display', 'none')
    $('#mynetwork').css('display', 'none')
    $('#outline').css('display', 'unset')

  }


  // direct links
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  var target = "";
  if (urlParams.has('link')) {
    var target = urlParams.get('link');
  }

  if (target != "") {

    target = encodeURIComponent(target)

    getContent(target);
    openNavMenu(target);

  } else {

    // load index page
    getContent("home", true);
  }


  // on search submit
  $('*[type="search"]').on('keypress', function (e) {
    if (e.which == 13) {
      search(this.value);
      return false;
    }

  });


  //mark current active menu item
  $('.perlite-link').click(function (e) {

    e.preventDefault();
    $('.perlite-link').removeClass('perlite-link-active is-active');

    $(this).addClass('perlite-link-active is-active');
  });



  // toggle left sidedock
  $('.sidebar-toggle-button.mod-left.sidebar').click(function (e) {

    e.preventDefault();


    if ($('.sidebar-toggle-button.mod-left.sidebar').hasClass('is-collapsed')) {
      $('.workspace').addClass('is-left-sidedock-open');
      $('.mod-left-split').removeClass('is-sidedock-collapse');
      $('.mod-left').removeClass('is-collapsed');

    } else {

      $('.workspace').removeClass('is-left-sidedock-open');
      $('.mod-left-split').addClass('is-sidedock-collapse');
      $('.mod-left').addClass('is-collapsed');
    }

  });


  $('.sidebar-toggle-button.mod-left.mobile-display').click(function (e) {

    if ($('.workspace-ribbon.side-dock-ribbon.mod-left').is(':hidden')) {
      $('.workspace-ribbon.side-dock-ribbon.mod-left').css('display', 'flex')
    } else {
      $('.workspace-ribbon.side-dock-ribbon.mod-left').css('display', 'none')
    }

  })



  // toggle right sidedock
  $('.sidebar-toggle-button.mod-right').click(function (e) {

    e.preventDefault();

    if ($('.sidebar-toggle-button.mod-right').hasClass('is-collapsed')) {
      $('.workspace').addClass('is-right-sidedock-open');
      $('.mod-right-split').removeClass('is-sidedock-collapse');
      $('.mod-right').removeClass('is-collapsed');

    } else {

      $('.workspace').removeClass('is-right-sidedock-open');
      $('.mod-right-split').addClass('is-sidedock-collapse');
      $('.mod-right').addClass('is-collapsed');
    }

  });


  // click search
  $('.workspace-tab-header[data-type="search"]').click(function (e) {
    e.preventDefault();

    $('.workspace-leaf-content[data-type="search"]').parent().css("display", "unset");
    $('.workspace-leaf-content[data-type="file-explorer"]').parent().css("display", "none");
    $('.workspace-tab-header[data-type="search"]').addClass('is-active mod-active');
    $('.workspace-tab-header[data-type="file-explorer"]').removeClass('is-active mod-active');

    // set focus to search field
    $('input[type=search]').focus();

  });

  // click file-explorer
  $('.workspace-tab-header[data-type="file-explorer"]').click(function (e) {
    e.preventDefault();
    $('.workspace-leaf-content[data-type="file-explorer"]').parent().css("display", "unset");
    $('.workspace-leaf-content[data-type="search"]').parent().css("display", "none");
    $('.workspace-tab-header[data-type="file-explorer"]').addClass('is-active mod-active');
    $('.workspace-tab-header[data-type="search"]').removeClass('is-active mod-active');
  });

  // click expand-search-all
  $('.clickable-icon.nav-action-button[aria-label="Collapse results"]').click(function (e) {
    e.preventDefault();

    if ($('.tree-item.search-result').hasClass('is-collapsed')) {
      $('.tree-item.search-result').removeClass('is-collapsed');
      $('.search-result-file-matches').css("display", "unset");
      $('.clickable-icon.nav-action-button[aria-label="Collapse results"]').removeClass('is-active');

    } else {
      $('.tree-item.search-result').addClass('is-collapsed');
      $('.search-result-file-matches').css("display", "none");
      $('.clickable-icon.nav-action-button[aria-label="Collapse results"]').addClass('is-active');
    }
  });


  // click expand-file-explorer-all
  $('.clickable-icon.nav-action-button[aria-label="Expand all"]').click(function (e) {
    e.preventDefault();
    target = $(e.target)

    $('.nav-folder-children.collapse').collapse('show');
    $('.nav-folder').removeClass('is-collapsed');
    $('.clickable-icon.nav-action-button[aria-label="Collapse all"]').css('display', 'unset');
    target.css('display', 'none');

  });


  // click collapse file-explorer-all
  $('.clickable-icon.nav-action-button[aria-label="Collapse all"]').click(function (e) {
    e.preventDefault();
    target = $(e.target)

    $('.nav-folder-children').collapse('hide');
    $('.nav-folder').addClass('is-collapsed');
    $('.clickable-icon.nav-action-button[aria-label="Expand all"]').css('display', 'unset');
    target.css('display', 'none');

  });


  // copy URL function
  $('.clickable-icon.view-action[aria-label="Copy URL"]').click(function (e) {
    e.preventDefault();
    target = $(e.target)
    var text = window.location.href;
    $('.tooltip').css("top", target.offset().top + 39);
    $('.tooltip').css("left", target.offset().left);
    $('.tooltip').css("height", "25px");
    $('.tooltip').css("display", "unset");

    navigator.clipboard.writeText(text).then(function () {
      $('.tooltip').text("URL copied to clipboard!");
    }, function (err) {
      $('.tooltip').text("Could not copy URL");
      console.error('Async: Could not copy URL: ', err);
    });

    setTimeout(hideTooltip, 1500);
  });



  // rezise Handler right
  const rightDockContainer = $('.workspace-split.mod-horizontal.mod-right-split')
  $('.workspace-leaf-resize-handle.right-dock').mousedown(function (e) {

    e.preventDefault()

    $(document).mouseup(function (e) {
      $(document).unbind('mousemove')
      localStorage.setItem('rightSizePanel', rightDockContainer.css("width"))
    });

    $(document).mousemove(function (e) {
      e.preventDefault()
      rightDockContainer.css("width", $(document).width() - e.pageX)

    });

  });


  // rezise Handler left
  const leftDockContainer = $('.workspace-split.mod-horizontal.mod-left-split')
  $('.workspace-leaf-resize-handle.left-dock').mousedown(function (e) {

    e.preventDefault()


    $(document).mouseup(function (e) {
      $(document).unbind('mousemove')
      localStorage.setItem('leftSizePanel', leftDockContainer.css("width"))
    });

    $(document).mousemove(function (e) {
      e.preventDefault()
      leftDockContainer.css("width", e.pageX)

    });

  });



  //  Global Settings and Event Handler
  // --------------------------------

  // load themes
  var dropDownValues = '<option value="Default">Obsidian Default</option>';
  var perliteDefault = ""
  $('.theme').each(function (i) {
    themeName = $(this).data('themename');
    themeId = $('.theme')[i].id;
    dropDownValues += '<option value="' + themeId + '">' + themeName + '</option>'

    // get current active
    if (!$('.theme')[i].disabled) {
      perliteDefault = $('.theme')[i].id;
    }

  })

  // fill dropdown
  $('#themeDropdown').html(dropDownValues);

  // change theme
  $("#themeDropdown").change(function (e) {
    target = $(e.target)

    // disable all themes
    $('.theme').attr("disabled", 'disabled');

    //enable selected if its not default 
    selectedTheme = target.val()

    if (selectedTheme !== 'Default') {
      $('#' + target.val()).attr('disabled', false);
    }

    localStorage.setItem('Theme', target.val());

  });

  //set active theme
  if (localStorage.getItem('Theme')) {
    $('#themeDropdown').val(localStorage.getItem('Theme'));
    $("#themeDropdown").trigger('change');

  } else {
    $('#themeDropdown').val(perliteDefault);
  }


  // reset Theme
  $('#resetTheme').click(function () {
    $('#themeDropdown').val(perliteDefault);
    $('#themeDropdown').change();
    localStorage.removeItem('Theme');
  })

  // text size input slider
  $('.slider.font-size').click(function (e) {
    e.preventDefault();
    target = $(e.target)

    $('body').css('--font-text-size', target.val() + 'px')
    localStorage.setItem('Font_size', target.val());

    $('.slider.font-size').val(parseInt($('body').css('--font-text-size')));

  });

  // Textsize Restore Defaults Button
  $('.clickable-icon[aria-label="Restore text settings"]').click(function (e) {
    e.preventDefault();

    $('body').css('--font-text-size', '15px')
    localStorage.removeItem('Font_size')
    $('.slider.font-size').val(parseInt($('body').css('--font-text-size')));

  });

  // Panelsize Restore Defaults Button
  $('.clickable-icon[aria-label="Restore panel settings"]').click(function (e) {
    e.preventDefault();

    localStorage.removeItem('rightSizePanel')
    localStorage.removeItem('leftSizePanel')

    $('.workspace-split.mod-horizontal.mod-left-split').css("width", "450px")
    $('.workspace-split.mod-horizontal.mod-right-split').css("width", "450px")
  });



  // inLine Title Option
  $('.inlineTitleOption').click(function (e) {
    e.preventDefault();
    target = $('.inlineTitleOption')

    if (target.hasClass('is-enabled')) {
      target.removeClass('is-enabled')
      $('body').removeClass('show-inline-title')
      localStorage.setItem('InlineTitle', 'hide');

    } else {
      target.addClass('is-enabled')
      $('body').addClass('show-inline-title')
      localStorage.removeItem('InlineTitle');

    }
  });

  // Darkmode / Lightmode change 
  $('.darkModeOption').click(function (e) {
    e.preventDefault();
    target = $('.darkModeOption')

    if (target.hasClass('is-enabled')) {
      target.removeClass('is-enabled')

      $('body').removeClass('theme-dark')
      $('body').addClass('theme-light')
      localStorage.setItem('lightMode', 'true');

    } else {
      target.addClass('is-enabled')
      $('body').removeClass('theme-light')
      $('body').addClass('theme-dark')
      localStorage.removeItem('lightMode');

    }
  });

  // PopUp change
    $('.popUpSetting').click(function (e) {
      e.preventDefault();
      target = $('.popUpSetting')
  
      if (target.hasClass('is-enabled')) {
        target.removeClass('is-enabled')
        localStorage.removeItem('popUpEnabled');     
  
      } else {
        target.addClass('is-enabled')    
        localStorage.setItem('popUpEnabled', 'true');
  
      }
    });


  // collapse Metadata Option
  $('.metadataOption').click(function (e) {
    e.preventDefault();
    target = $('.metadataOption')

    if (target.hasClass('is-enabled')) {
      target.removeClass('is-enabled')
      if ($('.frontmatter-container').hasClass('is-collapsed')) {
        $('.frontmatter-container').removeClass('is-collapsed');
      }
      localStorage.removeItem('Metadata');

    } else {
      target.addClass('is-enabled')

      if (!$('.frontmatter-container').hasClass('is-collapsed')) {
        $('.frontmatter-container').addClass('is-collapsed');
        localStorage.setItem('Metadata', 'hide');
      }
    }
  });



  //  Graph Settings and Event Handler
  // --------------------------------
  // open Graph
  $('.clickable-icon.side-dock-ribbon-action[aria-label="Open graph view"]').click(function (e) {
    e.preventDefault();

    str = document.getElementsByClassName('perlite-link-active');
    isMobile();

    if (str[0] != undefined) {
      str = str[0].getAttribute('onclick');
      str = str.substring(0, str.length - 3);
      str = str.substring(12, str.length);

    } else {
      str = "";
    }
    var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
    renderGraph(true, str, showNoLinks);

    if ($('.view-header-nav-buttons[data-section="close"]').is(':hidden')) {
      // show graph and close button
      $('.view-header-nav-buttons[data-section="close"]').css('display', 'flex');
      $('#graph_content').css('display', 'unset');
      $('.markdown-reading-view').css('display', 'none');

      // hide right side-dock
      $('.workspace').removeClass('is-right-sidedock-open');
      $('.mod-right-split').addClass('is-sidedock-collapse');
      $('.mod-right').addClass('is-collapsed');

    } else {

      $('.view-header-nav-buttons[data-section="close"]').click();

    }


  });

  // close Graph
  $('.view-header-nav-buttons[data-section="close"]').click(function (e) {
    e.preventDefault();

    // hide graph and close button
    $('.view-header-nav-buttons[data-section="close"]').css('display', 'none');
    $('#graph_content').css('display', 'none');
    $('.markdown-reading-view').css('display', 'flex');

    // show right side-dock
    $('.workspace').addClass('is-right-sidedock-open');
    $('.mod-right-split').removeClass('is-sidedock-collapse');
    $('.mod-right').removeClass('is-collapsed');
  });
  // open Graph settings
  $('.clickable-icon.graph-controls-button.mod-open[aria-label="Open graph settings"]').click(function (e) {
    e.preventDefault();

    target = $(e.target)
    $('.graph-controls').removeClass('is-close')
  });

  // close Graph settings
  $('.clickable-icon.graph-controls-button.mod-close[aria-label="Close"]').click(function (e) {
    e.preventDefault();
    $('.graph-controls').addClass('is-close')
  });

  // Graph Show Links Option (Orphans)
  $('.graphNoLinkOption').click(function (e) {
    e.preventDefault();
    target = $('.graphNoLinkOption')

    if (target.hasClass('is-enabled')) {
      target.removeClass('is-enabled')

      if ($('.graphAutoReloadOption').hasClass('is-enabled')) {
        renderGraph(true, str, true);
      }

      localStorage.setItem('Graph_Orphans', 'hide');


    } else {
      target.addClass('is-enabled')
      if ($('.graphAutoReloadOption').hasClass('is-enabled')) {
        renderGraph(true, str, false);
      }
      localStorage.removeItem('Graph_Orphans');
    }
  });

  // Graph Auto-reload Option
  $('.graphAutoReloadOption').click(function (e) {
    e.preventDefault();
    target = $('.graphAutoReloadOption')

    if (target.hasClass('is-enabled')) {
      target.removeClass('is-enabled')
      localStorage.setItem('Graph_Autoreload', 'no');

    } else {
      target.addClass('is-enabled')
      localStorage.removeItem('Graph_Autoreload');
    }
  });

  // Graph Node Size Option
  $('.nodeSize').click(function (e) {
    e.preventDefault();
    target = $(e.target)

    $('#nodeSizeVal').text(target.val())

    if ($('.graphAutoReloadOption').hasClass('is-enabled')) {
      var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
      renderGraph(true, str, showNoLinks);
    }

    localStorage.setItem('Graph_NodeSize', target.val());

  });
  // Graph Link Distance Option
  $('.linkDistance').click(function (e) {
    e.preventDefault();
    target = $(e.target)

    $('#linkDistanceVal').text(target.val())
    var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
    if ($('.graphAutoReloadOption').hasClass('is-enabled')) {
      var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
      renderGraph(true, str, showNoLinks);
    }
    localStorage.setItem('Graph_LinkDistance', target.val());

  });
  // Graph Link Thickness Option
  $('.linkThickness').click(function (e) {
    e.preventDefault();
    target = $(e.target)

    $('#linkThicknessVal').text(target.val())
    var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
    if ($('.graphAutoReloadOption').hasClass('is-enabled')) {
      var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
      renderGraph(true, str, showNoLinks);
    }
    localStorage.setItem('Graph_LinkThickness', target.val());

  });

  // Graph Style Change
  $("#graphStyleDropdown").change(function (e) {
    e.preventDefault();
    target = $(e.target)
    if ($('.graphAutoReloadOption').hasClass('is-enabled')) {
      var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
      renderGraph(true, str, showNoLinks);
    }
    localStorage.setItem('Graph_Style', target.val());
  });

  // Graph Reload Button
  $("#graphReload").click(function (e) {

    var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
    renderGraph(true, str, showNoLinks);

  });

  // Graph Restore Defaults Button
  $('.clickable-icon.graph-controls-button.mod-reset[aria-label="Restore default settings"]').click(function (e) {
    e.preventDefault();

    if (!$('.graphNoLinkOption').hasClass('is-enabled')) {
      $('.graphNoLinkOption').addClass('is-enabled')
    }

    if (!$('.graphAutoReloadOption').hasClass('is-enabled')) {
      $('.graphAutoReloadOption').addClass('is-enabled')
    }

    $('.slider.linkThickness').val(1)
    $('.slider.linkDistance').val(150)
    $('.slider.nodeSize').val(12)
    $('#graphStyleDropdown').val('dynamic')

    localStorage.removeItem('Graph_Orphans');
    localStorage.removeItem('Graph_Autoreload');
    localStorage.removeItem('Graph_Style');
    localStorage.removeItem('Graph_LinkDistance');
    localStorage.removeItem('Graph_LinkThickness');
    localStorage.removeItem('Graph_NodeSize');


    var showNoLinks = !$(".graphNoLinkOption").hasClass('is-enabled')
    renderGraph(true, str, showNoLinks);

  });





  //  Modal Event Handler
  // --------------------------------

  // info modal
  $('.clickable-icon.side-dock-ribbon-action[aria-label="Help"]').click(function (e) {
    $.ajax({
      url: "content.php?about", success: function (result) {

        $("div.aboutContent").html(result);
        $("#about").css("display", "flex");
        //$(".modal-title").html('Perlite');
        // hljs.highlightAll();

      }
    });

  });

  // setting modal
  $('.clickable-icon.side-dock-ribbon-action[aria-label="Settings"]').click(function (e) {

    $("#settings").css("display", "flex");

  });

  // close modal
  $('.modal-close-button').click(function (e) {
    $("#settings").css("display", "none");
    $("#about").css("display", "none");
    $("#popUp").css("display", "none");
  });

  // local Graph & Outline Swith
  $('.clickable-icon.view-action[aria-label="Open outline"]').click(function (e) {

    $('.clickable-icon.view-action[aria-label="Open outline"]').css('display', 'none')
    $('.clickable-icon.view-action[aria-label="Open localGraph"]').css('display', 'unset')

    $('#mynetwork').css('display', 'none')
    $('#outline').css('display', 'unset')
  });

  $('.clickable-icon.view-action[aria-label="Open localGraph"]').click(function (e) {

    $('.clickable-icon.view-action[aria-label="Open outline"]').css('display', 'unset')
    $('.clickable-icon.view-action[aria-label="Open localGraph"]').css('display', 'none')

    $('#mynetwork').css('display', 'unset')
    $('#outline').css('display', 'none')
  });




  // init mermaid
  mermaid.initialize({ startOnLoad: false, 'securityLevel': 'Strict', 'theme': 'dark' });

});

