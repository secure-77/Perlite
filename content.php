<?php


include('Parsedown.php');
include('helper.php');

$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

$requestFile = $_GET['file'];
$cleanFile = '';

menu(getcwd());

$path = '';

// get and parse the content
$content = getContent($requestFile);
$content = $Parsedown->text($content);
$content = str_replace('![[','<img class="images" src="' . $path .'/',$content);
$content = str_replace(']]','"/>',$content);
$content = '<div class="mdTitleHide" style="display: none";>'.$cleanFile.'</div>' . $content;


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
echo $content;
?>