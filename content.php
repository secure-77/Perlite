<?php

include('helper.php');


if (isset($_GET['file'])) {

	$requestFile = $_GET['file'];
	parseContent($requestFile);

}

if (isset($_GET['about'])) {

	parseContent('README');

}


if (isset($_GET['search'])) {

	$searchString = $_GET['search'];
	echo doSearch(getcwd(),$searchString);

}


function parseContent($requestFile) {

	global $path;
	global $cleanFile;
	$Parsedown = new Parsedown();
	$Parsedown->setSafeMode(true);

	$cleanFile = '';

	menu(getcwd());

	$path = '';

	// get and parse the content
	$content = getContent($requestFile);
	$content = $Parsedown->text($content);
	$content = str_replace('![[','<img class="images" alt="image not in same folder" src="' . $path .'/',$content);
	$content = str_replace(']]','"/>',$content);
	$content = '<div class="mdTitleHide" style="display: none";>'.$cleanFile.'</div>' . $content;
	echo $content;


}

function getContent($requestFile) {
	global $avFiles;
	global $path;
	global $cleanFile;
	$content = '';
	if (in_array($requestFile,$avFiles, true)) {
		$cleanFile = $requestFile;
		$n = strrpos($requestFile,"/");
		$path = substr($requestFile,0,$n);
		$content .= file_get_contents($requestFile . '.md', true);
	} else {
		$content .= "not allowed";
	}
	return $content;
}


?>