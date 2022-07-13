<?php

/*!
  * Perlite v1.4.4 (https://github.com/secure-77/Perlite)
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
	//$Parsedown = new ParsedownExtra();
	$Parsedown = new PerliteParsedown();
	$Parsedown->setSafeMode(true);
	$Parsedown->setBreaksEnabled(true);
	$cleanFile = '';

	// call menu again to refresh the array
	menu($rootDir);
	$path = '';

	// get and parse the content, return if no content is there
	$content = getContent($requestFile);
	if($content === '') {
		return;
	}

	// Ignore YAML front matter
	$pattern = '/^[\s\r\n]?---[\s\r\n]?$/sm';
	$parts = preg_split($pattern, PHP_EOL.ltrim($content));

	// parse the content if no yaml found
	if (count($parts) < 3) {
		$content = $Parsedown->text($content);
	
		// front-matter present
	} else {
		$matter = trim($parts[1]);
		$body = implode(PHP_EOL.'---'.PHP_EOL, array_slice($parts, 2));

		$content = $Parsedown->text($body ?? "");
	}

	// define pathes for links
	$mdpath = $path;
	$path = $startDir . $path;

	// pdf links
	$replaces = '<a target="_blank" rel="noopener noreferrer" href="'.$path .'/'.'\\2">\\2</a>';
	$pattern = array('/(\!\[\[)(.*?.pdf)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);
	
	// img links
	$replaces = '<p><a href="#" class="pop"><img class="images" alt="image not found" src="'. $path .'/\\2\\3'.'"/></a></p>';
	$pattern = array('/(\!\[\[)(.*?)(.png|.jpg|.jpeg|.gif|.bmp|.tif|.tiff)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);

	// support marmaid
	// $replaces = '<div class="mermaid">';
	// $pattern = array('/<code class="language-mermaid">/');
	// $content = preg_replace($pattern, $replaces ,$content);

	// handle internal site links
	// search for links outside of the current folder
	$pattern = array('/(\[\[)(?:\.\.\/)+(.*?)(\]\])/');
	$content = translateLink($pattern, $content, $path, false);

	// search for links in the same folder
	$pattern = array('/(\[\[)(.*?)(\]\])/');
	$content = translateLink($pattern, $content, $mdpath, true);
	
	
	// hide title
	$content = '<div class="mdTitleHide" style="display: none";>'.$cleanFile.'</div>' . $content;
	
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
		$urlPath = rawurlencode($urlPath);
		return '<a href="?link='.$urlPath.'">'. $linkName .'</a>';
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