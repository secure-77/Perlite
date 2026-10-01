<?php

/*!
 * Perlite v1.6.3 (https://github.com/secure-77/Perlite)
 * Author: sec77 (https://secure77.de)
 * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
 */

use Perlite\PerliteCanvas;
use Perlite\PerliteParsedown;

require_once __DIR__ . '/vendor/autoload.php';
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
			echo doSearch($rootDir, $searchString);
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
function parseContent($requestFile)
{

	global $path;
	global $cleanFile;
	global $isCanvas;
	global $rootDir;
	global $startDir;
	global $uriPath;
	global $avFiles;
	global $hideFolders;
	global $canvasIframeEmbeds;


	// call menu again to refresh the array
	menu($rootDir);
	$path = '';

	// get and parse the content, return if no content is there
	$content = getContent($requestFile);
	if ($content === '') {
		return;
	}


	if ($isCanvas) {

		// render the canvas, notes are embedded relative to their own folder
		$canvas = new PerliteCanvas('getParser', $rootDir, $startDir, $uriPath, $avFiles, $hideFolders, $canvasIframeEmbeds);
		$content = $canvas->render($content, $path);
		$wordCount = $canvas->getWordCount();
		$charCount = $canvas->getCharCount();
	} else {

		$wordCount = str_word_count($content);
		$charCount = strlen($content);
		$content = getParser($path)->text($content);
	}


	// add some meta data
	$content = '
	<div style="display: none">
		<div class="mdTitleHide">' . $cleanFile . '</div>
		<div class="wordCount">' . $wordCount . '</div>
		<div class="charCount">' . $charCount . '</div>
	</div>' . $content;
	$cleanFile = '';

	echo $content;
	return;

}


// get a markdown parser for a note in the given folder
function getParser($folder)
{
	global $startDir;
	global $uriPath;
	global $lineBreaks;
	global $allowedFileLinkTypes;
	global $htmlSafeMode;
	global $absolutePath;
	global $niceLinks;

	// Relative or absolute pathes
	if ($absolutePath) {
		$parserPath = $startDir;
	} else {
		$parserPath = $startDir . $folder;
	}

	$Parsedown = new PerliteParsedown($parserPath, $uriPath, $niceLinks, $allowedFileLinkTypes);
	$Parsedown->setSafeMode($htmlSafeMode);
	$Parsedown->setBreaksEnabled($lineBreaks);

	return $Parsedown;
}


// read content from file
function getContent($requestFile)
{
	global $avFiles;
	global $path;
	global $cleanFile;
	global $isCanvas;
	global $rootDir;
	$content = '';
	$isCanvas = false;

	// check if file is in array
	if (in_array($requestFile, $avFiles, true)) {
		$cleanFile = $requestFile;
		$n = strrpos($requestFile, "/");
		$path = substr($requestFile, 0, $n);

		// canvas files are stored with extension, notes without
		$isCanvas = isCanvasFile($requestFile) && is_file($rootDir . $requestFile);
		$fileName = $isCanvas ? $requestFile : $requestFile . '.md';
		$content .= file_get_contents($rootDir . $fileName, true);
	}

	return $content;
}

?>