<?php

include('helper.php');


if (isset($_GET['mdfile'])) {
	$requestFile = $_GET['mdfile'];
	
	if (is_string($requestFile)) {
		if (!empty($requestFile)) {
			parseContent($requestFile);
		}
	}	
}


if (isset($_GET['about'])) {
	
	if (is_string($_GET['about'])) {
		parseContent('/' . $about);
	}	
}


if (isset($_GET['search'])) {

	$searchString = $_GET['search'];
	if (is_string($searchString)) {
		if (!empty($searchString)) {
			echo doSearch($rootDir,$searchString);
		}
	}
}



function parseContent($requestFile) {

	global $path;
	global $cleanFile;
	global $rootDir;
	global $startDir;
	$Parsedown = new Parsedown();
	$Parsedown->setSafeMode(true);

	$cleanFile = '';

	menu($rootDir);

	$path = '';

	// get and parse the content
	$content = getContent($requestFile);
	

	// define pathes for links
	$mdpath = $path;
	$path = $startDir . $path;


	$content = $Parsedown->text($content);


	// pdf links
	$replaces = '<a target="_blank" href="'.$path .'/'.'\\2">\\2</a>';
	$pattern = array('/(\!\[\[)(.*.pdf)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);
	
	// img links
	$replaces = '<a href="#" class="pop"><img class="images" alt="image not in same folder" src="'. $path .'/\\2\\3'.'"/></a>';
	$pattern = array('/(\!\[\[)(.*)(.png|.jpg|.jpeg|.gif|.bmp|.tif|.tiff)(\]\])/');
	$content = preg_replace($pattern, $replaces ,$content);

	// site links
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

}

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