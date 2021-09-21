// get markdown content
  function getContent(str) {
    if (str.length == 0) {
      document.getElementById("mdContent").innerHTML = "";
      document.getElementsByClassName("modal-body")[0].innerHTML = "";
      return;
    } else {
    
      $.ajax({url: "content.php?mdfile=" + str, success: function(result){
        
        // set content + fullscreen modal
        $("#mdContent").html(result);
        $("div.mdModalBody").html(result);		      
        var title = $("div.mdTitleHide").first().html();
        title = '<a href="' + window.location.origin  +'?link=' + encodeURIComponent(title) + '">' + title + '<a/>'
        $("div.mdTitle").html(title);
        $("h5.mdModalTitle").html(title);
        
        // highlight code
        hljs.highlightAll();
        
        // run mobile settings
        isMobile();
        
        // Hide Search
        $("#searchModal").modal("hide");
        
        // Image Click
        $(".pop").on("click", function() {
            var path = $(this).find("img").attr("src");
            $(".imagepreview").attr("src",path);
            $("#imgModal").modal("show");     
        });		
      }});   
    }
  };

// change mobile settings
  function isMobile() {      
    var is_mobile = false;
   
    if( $("div.no-mobile").css("display")=="none") {
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




// toggle theme
function toggleTheme(mode) {

  if (mode == "light") {

    document.getElementById("highlight-js").setAttribute("href", ".styles/a11y-light.css");
    document.getElementById("bootswatch-theme").setAttribute("href", ".styles/flatly.css");

    replaceClass("link-light", "link-dark");
    replaceClass("btn-close-light", "btn-close-dark");
    replaceClass("navbar-dark", "navbar-light");
    replaceClass("logo-light", "logo-dark");
    replaceClass("btn-hover-dark", "btn-hover-light");

    document.cookie = 'theme=light; path=/';

  } else {

    document.getElementById("highlight-js").setAttribute("href", ".styles/a11y-dark.css");
    document.getElementById("bootswatch-theme").setAttribute("href", ".styles/darkly.css");

    replaceClass("link-dark", "link-light");
    replaceClass("btn-close-dark", "btn-close-light");
    replaceClass("navbar-light", "navbar-dark");
    replaceClass("logo-dark", "logo-light");
    replaceClass("btn-hover-light", "btn-hover-dark");

    document.cookie = 'theme=dark; path=/';

  }

}


function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

function replaceClass(oldClass, newClass) {
  var elem = $("."+oldClass);
  elem.removeClass(oldClass);
  elem.addClass(newClass);
}


// general stuff
$(document).ready(function() {

  // set theme
  if (getCookie("theme")) {
    if (getCookie("theme") == "light") {
      $("#toggleTheme").prop("checked","true");
      toggleTheme("light");
    }
  }

  // theme toggle
  $("#toggleTheme").change(function() {
    if ($(this).prop("checked")) {
      toggleTheme("light");
    } else {
      toggleTheme("dark");
    }
 });

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
    navId = navId.replace(/[^a-zA-Z0-9\-]/g,'_');
    var next = $('#'+navId).parent().closest('.collapse');
    do  {
        next.collapse('show'); 
        next = next.parent().closest('.collapse');

      }
    while (next.length != 0);
      
  }


  // on search submit
  document.getElementById("f1").onsubmit = function() {
    
    search(this.t1.value);
    return false;
  };

  // show last search
  document.getElementById("showLastSearch").onclick = function () {
    $("#searchModal").modal("show");
  };

  // about modal
  document.getElementById("about").onclick = function () {
    $.ajax({url: "content.php?about", success: function(result){
      $("div.aboutModalBody").html(result);		
      var title = $("div.searchTitle").first().html();
      $("h5.aboutModalTitle").html("Perlite");
      hljs.highlightAll();
      $("#aboutModal").modal("show");		
    }}); 
  };


});

