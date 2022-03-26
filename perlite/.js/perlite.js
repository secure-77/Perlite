/*!
  * Perlite (https://github.com/secure-77/Perlite)
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/


// get markdown content
function getContent(str) {

  // reset content if request is empty
  if (str.length == 0) {
    document.getElementById("mdContent").innerHTML = "";
    document.getElementsByClassName("modal-body")[0].innerHTML = "";
    return;
  } else {

    $.ajax({
      url: "content.php?mdfile=" + str, success: function (result) {

        // set content + fullscreen modal
        $("#mdContent").html(result);
        $("div.mdModalBody").html(result);
        var title = $("div.mdTitleHide").first().html();
        if (title) {
          title = '<a href=?link=' + encodeURIComponent(title) + '>' + title + '</a>'
          $("li.mdTitle").html(title);
          $("h5.mdModalTitle").html(title);
        }

        // highlight code
        hljs.highlightAll();

        // run mobile settings
        isMobile();

        // render LaTeX
        renderMathInElement(document.getElementById("mdContent"), 
        {
          delimiters: [
              {left: "$$", right: "$$", display: true},
              {left: "\\[", right: "\\]", display: true},
              {left: "$", right: "$", display: false},
              {left: "\\(", right: "\\)", display: false}
          ]
      }      
        );

        // Hide Search
        $("#searchModal").modal("hide");

        // add Image Click popup
        $(".pop").on("click", function () {
          var path = $(this).find("img").attr("src");
          $(".imagepreview").attr("src", path);
          $("#imgModal").modal("show");
        });

        // mark external links
        function link_is_external(link_element) {
          return (link_element.host !== window.location.host);
        }

        var links = document.getElementsByTagName('a');
        for (var i = 0; i < links.length; i++) {
          if (link_is_external(links[i]) && (links[i].classList.contains('nav-link') < 1)) {
            links[i].classList.add('external-link');
          }
        }

        // trigger graph render
        renderGraph(false, str);


        // update the url
        window.history.pushState({},"", "/?link="+str);
      }
    });




  }
};

// vis js stuff
function renderGraph(modal, path = "", filter_emptyNodes = false) {


  
  var visNodes = document.getElementById('allGraphNodes').innerHTML;
  var visEdges = document.getElementById('allGraphEdges').innerHTML;

  var jsonNodes = JSON.parse(visNodes);
  var jsonEdges = JSON.parse(visEdges);

  var currId = 0;
  path = decodeURIComponent(path);


  // get current node
  for (const x in jsonNodes) {
    if (path == ('/' + jsonNodes[x]['title'])) {
      currId = jsonNodes[x]['id'];
      break;
    }
  }

  //container = document.getElementById('mdContent');

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
      length: 200, // Longer edges between nodes.
      // smooth: {
      //   type: "cubicBezier"
      // }
    },

    nodes: {
      shape: 'dot',
      size: 15,
      font: {
        size: 16,
        color: '#ffffff',
        face: 'NerdFonts'
      },
      borderWidth: 1,
      color: {
        background: '#3a3f44',
        border: '#6d8e98',
        highlight: {
          border: '#5bc0de',
          background: '#3a3f44',
        },
        hover: {
          border: '#5bc0de',
          background: '#6d8e98',
        },
      },
    }
  };

  var network;

  // show the hole graph
  if (modal) {


    var container_modal = document.getElementById('mynetwork_modal');

    var nodes = new vis.DataSet(jsonNodes);
    var edges = new vis.DataSet(jsonEdges);

    edgeView = edges;
    nodeView = nodes;

    if (filter_emptyNodes) {
      nodeView = new vis.DataView(nodes, {
        filter: function (node) {
          connEdges = edgeView.get({
            filter: function (edge) {
              if(node.id == currId) {
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
            background: '#ffbf00',
          },
      });   
    


    // filter the graph to the desired nodes and edges
  } else {


    var myNodes = [];
    var myEdges = [];


    // add current node
    for (const x in jsonNodes) {
      if (path == ('/' + jsonNodes[x]['title'])) {
        myNodes.push(jsonNodes[x])
        curNode = myNodes[0]
        curNode.size = '20';
        curNode.color = {
          background: '#ffbf00',
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
      }

    }

    // build network structure

    var nodes = new vis.DataSet(myNodes);
    var edges = new vis.DataSet(myEdges);


    var data = {
      nodes: nodes,
      edges: edges
    };


    var container = document.getElementById('mynetwork');
    network = new vis.Network(container, data, options);


  }

  // jump to file function
  if (network) {

    network.on("doubleClick", function (properties) {

      if (!properties.nodes.length) return;
      var node = nodes.get(properties.nodes[0]);
      var glink = '?link=' + encodeURIComponent('/' + node.title);
      window.open(glink, "_self");
    });
  }

}



// change mobile settings
function isMobile() {
  var is_mobile = false;

  if ($("div.no-mobile").css("display") == "none") {
    is_mobile = true;
  }

  if (is_mobile == true) {
    $("#contentModal").modal("show");
  }
};

// search  
function search(str) {
  if (str.length == 0) {
    document.getElementById("mdContent").innerHTML = "";
    document.getElementsByClassName("modal-body")[0].innerHTML = "";
    return;
  } else {

    $.ajax({
      url: "content.php?search=" + str, success: function (result) {
        $("div.searchModalBody").html(result);
        var title = $("div.searchTitle").first().html();
        $("h5.searchModalTitle").html(title);
        hljs.highlightAll();
        $("#searchModal").modal("show");
        var lastSearch = $("div.lastSearch").first().html();
        $("#showLastSearch").html(lastSearch);
      }
    });
  }
};



function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

function replaceClass(oldClass, newClass) {
  var elem = $("." + oldClass);
  elem.removeClass(oldClass);
  elem.addClass(newClass);
}


// general stuff
$(document).ready(function () {


  // set filter option
  if (getCookie("filterNodes")) {
    if (getCookie("filterNodes") == "noLink") {
      $("#toggleEmptyNodes").prop("checked", true);
    } else {
      $("#toggleEmptyNodes").prop("checked", false);
    }
  };


  // filter nodes toggle
  $("#toggleEmptyNodes").change(function () {
    if ($(this).prop("checked")) {
      document.cookie = 'filterNodes=noLink; path=/';
      renderGraph(true, str, true);
    } else {
      document.cookie = 'filterNodes=none; path=/'; 
      renderGraph(true, str, false);
    }
  });

  // check for graph and hide open grap link if none exists
  if (!document.getElementById("allGraphNodes") || document.getElementById("allGraphNodes").innerHTML == '[]' ) {
    document.getElementById('expandGraph').classList.add('hide');
  }


  // direct links
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  var target = "";
  if (urlParams.has('link')) {
    var target = urlParams.get('link');
  }

  if (target != "") {
    getContent(target);

    // open nav menu to target
    var navId = decodeURIComponent(target);
    linkname = navId.match(/([^\/]*)\/*$/)[1]

    // search and open tree reverse
    navId = navId.replace(/[^a-zA-Z0-9\-]/g, '_');
    var next = $('#' + navId).parent().closest('.collapse');

    do {
      next.collapse('show');
      next = next.parent().closest('.collapse');
    }
    while (next.length != 0);

    // set focus to link
    var aTags = document.getElementsByTagName("a");
    var searchText = linkname;
    var found;

    for (var i = 0; i < aTags.length; i++) {
      if (aTags[i].textContent == searchText) {
        found = aTags[i];
        found.classList.add('perlite-link-active');
        found.focus();
        break;
      }
    }
  }


  // on search submit
  document.getElementById("f1").onsubmit = function () {

    search(this.t1.value);
    return false;
  };


 // show graph modal
  document.getElementById("expandGraph").onclick = function () {
    $("#graphModal").modal("show");
  };

  // render graph and check current file to highlight node
  $('#graphModal').on('shown.bs.modal', function () {

    str = document.getElementsByClassName('perlite-link-active');

    if (str[0] != undefined) {
      str = str[0].getAttribute('onclick');
      str = str.substring(0, str.length - 3);
      str = str.substring(12, str.length);

    } else {
      str = "";
    }
    if ($("#toggleEmptyNodes").prop('checked')){
        renderGraph(true, str, true);
        return;    
      }
    
    renderGraph(true, str, false);
  });

  // about modal
  document.getElementById("about").onclick = function () {
    $.ajax({
      url: "content.php?about", success: function (result) {
        $("div.aboutModalBody").html(result);
        var title = $("div.searchTitle").first().html();
        $("h5.aboutModalTitle").html("Perlite");
        hljs.highlightAll();
        $("#aboutModal").modal("show");
      }
    });
  };

  // mark current active menu item
  $('a.perlite-link').click(function (e) {

    e.preventDefault();
    $('a.perlite-link').removeClass('perlite-link-active');

    $(this).addClass('perlite-link-active');
  });


});

