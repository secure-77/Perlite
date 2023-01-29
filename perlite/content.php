<?php

/*!
  * Perlite v1.5.2 (https://github.com/secure-77/Perlite)
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/


include('helper.php');

// check get params
if (isset($_GET['mdfile'])) {
	$requestFile = $_GET['mdfile'];
	
	if (is_string($requestFile)) {
		if (!empty($requestFile)) {
			parseContent($requestFile);
		}
	}	
}

// parse content for about modal
if (isset($_GET['about'])) {
	
	if (is_string($_GET['about'])) {
		parseContent('/' . $about);
	}	
}

// search request
if (isset($_GET['search'])) {

	$searchString = $_GET['search'];
	if (is_string($searchString)) {
		if (!empty($searchString)) {
			echo doSearch($rootDir,$searchString);
		}
	}
}


// parse content for home site
if (isset($_GET['home'])) {
	
	if (is_string($_GET['home'])) {
		parseContent('/' . $index);
	}	
}


// parse the md to html
function parseContent($requestFile) {

	global $path;
	global $cleanFile;
	global $rootDir;
	global $startDir;
	global $lineBreaks;


	//$Parsedown = new ParsedownExtra();
	$Parsedown = new PerliteParsedown();
	$Parsedown->setSafeMode(true);
	$Parsedown->setBreaksEnabled($lineBreaks);
	$cleanFile = '';

	// call menu again to refresh the array
	menu($rootDir);
	$path = '';

	// get and parse the content, return if no content is there
	$content = getContent($requestFile);
	if($content === '') {
		return;
	}

	$wordCount = str_word_count($content);
	$charCount = strlen($content);
	$content = $Parsedown->text($content);

	// define pathes for links
	$mdpath = $path;
	$path = $startDir . $path;

	// pdf links
	$replaces = '<a class="internal-link" target="_blank" rel="noopener noreferrer" href="'.$path .'/'.'\\2">\\2</a>';
	$pattern = array('/(\!\[\[)(.*?.pdf)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);
	
	// img links
	$replaces = '<p><a href="#" class="pop"><img class="images" alt="image not found" src="'. $path .'/\\2\\3'.'"/></a></p>';
	$pattern = array('/(\!\[\[)(.*?)(.png|.jpg|.jpeg|.gif|.bmp|.tif|.tiff)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);

	// handle internal site links
	// search for links outside of the current folder
	$pattern = array('/(\[\[)(?:\.\.\/)+(.*?)(\]\])/');
	$content = translateLink($pattern, $content, $path, false);

	// search for links in the same folder
	$pattern = array('/(\[\[)(.*?)(\]\])/');
	$content = translateLink($pattern, $content, $mdpath, true);
	
	
	// add some meta data
	$content = '
	<div style="display: none">
		<div class="mdTitleHide">'.$cleanFile.'</div>
		<div class="wordCount">'.$wordCount.'</div>
		<div class="charCount">'.$charCount.'</div>
	</div>' . $content;
	
	echo $content;
	return;

}

//internal links
function translateLink($pattern, $content, $path, $sameFolder) {
	
	return preg_replace_callback($pattern, 
	function($matches) use ($path, $sameFolder) {
		
		$newAbPath = $path;
		$pathSplit = explode("/",$path);
		$linkName = $matches[2];
		$linkFile = $matches[2];

		# handle custom internal obsidian links
		$splitLink = explode("|", $matches[2]);
		if (count($splitLink) > 1) {
			
			$linkFile = $splitLink[0];
			$linkName = $splitLink[1];
		}

		
		# handle internal popups
		$popupClass = '';
		$popUpIcon = '';
		
		if (count($splitLink) > 2) {

			$popupClass = ' internal-popup';
			$popUpIcon = '<svg class="popup-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon lucide-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3"></path><path d="M21 8V5a2 2 0 0 0-2-2h-3"></path><path d="M3 16v3a2 2 0 0 0 2 2h3"></path><path d="M16 21h3a2 2 0 0 0 2-2v-3"></path></svg>';
		}

		
		// do extra stuff to get the absolute path
		if ($sameFolder == false) {
			$countDirs = count(explode("../",$matches[0]));
			$countDirs = $countDirs -1;
			$newPath = array_splice($pathSplit, 1, -$countDirs);			
			$newAbPath = implode('/', $newPath);
		}

		
		$urlPath = $newAbPath. '/'. $linkFile;
		if (substr($urlPath,0,1) != '/') {
			$urlPath = '/' . $urlPath;
		}

		# split by # to keep the reference
		$splitLink = explode("#", $urlPath);
		if (count($splitLink) > 1) {
			
			$urlPath = $splitLink[0];
			$refName = $splitLink[1];
		}

		# replace amp back to & (comming from parsedown)
		$urlPath = str_replace('&amp;' , '&', $urlPath);
	
		$urlPath = rawurlencode($urlPath);
		if (strlen($refName) > 0) {
			$refName = '#'.$refName;
		}

	
		return '<a class="internal-link'.$popupClass.'" href="?link='.$urlPath.$refName.'">'. $linkName .'</a>'.$popUpIcon;
	}
,$content);
}


// read content from file
function getContent($requestFile) {
	global $avFiles;
	global $path;
	global $cleanFile;
	global $rootDir;
	$content = '';

	// check if file is in array
	if (in_array($requestFile,$avFiles, true)) {
		$cleanFile = $requestFile;
		$n = strrpos($requestFile,"/");
		$path = substr($requestFile,0,$n);
		$content .= file_get_contents($rootDir.$requestFile . '.md', true);
	}
	
	return $content;
}

?>