<?php

include('helper.php');


if (isset($_GET['file'])) {

	$requestFile = $_GET['file'];
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
	
	// // image path management
	// if (strcmp($startDir,"") == 0) {
	// 	//$startPoint = explode('/', $path);
	// 	//$path = $startPoint [1];
	// } else {

	// 	$path = $startDir;
	// }

	$path = $startDir . $path;


	$content = $Parsedown->text($content);
	$content = str_replace('![[','<a href="#" class="pop"><img class="images" alt="image not in same folder" src="'. $path .'/',$content);
	$content = str_replace(']]','"/></a>',$content);
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
	} else {
		$content .= "not allowed";
	}
	return $content;
}


?>