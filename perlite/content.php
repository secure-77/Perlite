<?php

/*!
  * Perlite v1.4.2 RC (https://github.com/secure-77/Perlite)
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


// parse the md to html
function parseContent($requestFile) {

	global $path;
	global $cleanFile;
	global $rootDir;
	global $startDir;
	//$Parsedown = new ParsedownExtra();
	$Parsedown = new Parsedown();
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

	
	// define pathes for links
	$mdpath = $path;
	$path = $startDir . $path;

	// parse the content
	$content = $Parsedown->text($content);

	// pdf links
	$replaces = '<a target="_blank" rel="noopener noreferrer" href="'.$path .'/'.'\\2">\\2</a>';
	$pattern = array('/(\!\[\[)(.*.pdf)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);
	
	// img links
	$replaces = '<p><a href="#" class="pop"><img class="images" alt="image not found" src="'. $path .'/\\2\\3'.'"/></a></p>';
	$pattern = array('/(\!\[\[)(.*)(.png|.jpg|.jpeg|.gif|.bmp|.tif|.tiff)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);

	// handle internal site links
	$pathSplit = explode("/",$path);

		$pattern = array('/(\[\[)(\.\.\/)+(.*)(\]\])/');
		$content = preg_replace_callback($pattern, 
		function($matches) use ($pathSplit) {

			$countDirs = count(explode("../",$matches[0]));
			$countDirs = $countDirs -1;
			$newPath = array_splice($pathSplit, 1, -$countDirs);			
			$newAbPath = implode('/', $newPath);
			$urlPath = $newAbPath. '/'. $matches[3];
			if (substr($urlPath,0,1) != '/') {
				$urlPath = '/' . $urlPath;
			}
			$urlPath = rawurlencode($urlPath);
			return '<a href="?link='.$urlPath.'">'. $matches[3].'</a>';
		}
		,$content);
	
	
	// root site links
	$urlPath = rawurlencode($mdpath);
	$replaces = '<a href="?link='.$urlPath.'%2F'.'\\2">\\2</a>';
	$pattern = array('/(\[\[)(.*)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);

	// hide title
	$content = '<div class="mdTitleHide" style="display: none";>'.$cleanFile.'</div>' . $content;
	
	echo $content;
	return;

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