<?php

/*!
  * Perlite v1.5.2 (https://github.com/secure-77/Perlite)
  * Author: sec77 (https://secure77.de)
  * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
*/

include('PerliteParsedown.php');


$avFiles = array();

$rootDir = getenv('NOTES_PATH');


if (empty($rootDir)) {
	
	# replace with your Vault name
	$rootDir = 'Demo';
}


$vaultName = $rootDir;

$hideFolders = getenv('HIDE_FOLDERS');
$lineBreaks = getenv('LINE_BREAKS');

if (empty($lineBreaks)) {
	$lineBreaks = true;
} else {
	$lineBreaks = filter_var($lineBreaks, FILTER_VALIDATE_BOOLEAN);
}

$about = '.about';
$index = 'README';

// add about and index to allowed files
$aboutpath = getFileInfos($rootDir . '/' . $about)[0];
$indexpath = getFileInfos($rootDir . '/' . $index)[0];
$aboutpath = '/' . $aboutpath;
$indexpath = '/' . $indexpath;
array_push($avFiles, $aboutpath);
array_push($avFiles, $indexpath);



// hide folders
if (strcmp($hideFolders, '')) {

	$hideFolders = explode(',', $hideFolders);
} else {
	$hideFolders = array();
}

// path management
if (!strcmp($rootDir, "")) {

	$rootDir = getcwd();
	$vaultName = mb_basename($rootDir);
	$startDir = "";
} else {
	$startDir = $rootDir;	
}

// custom sort function to prefer underscore
function cmp($a, $b)
{
	$aTemp = str_replace('_', '0', $a);
	$bTemp = str_replace('_', '0', $b);
	return strnatcasecmp($aTemp, $bTemp);
}

function menu($dir, $folder = '')
{

	global $avFiles;
	$html = '';
	// get all files from current dir
	$files = glob($dir . '/*');

	// sort array
	usort($files, "cmp");

	// iterate the folders 
	foreach ($files as $file) {
		if (is_dir($file)) {

			if (isValidFolder($file)) {

				// split Folder Infos
				$folder = getFolderInfos($file)[0];
				$folderClean = getFolderInfos($file)[1];
				$folderName = getFolderInfos($file)[2];
				$folderId = str_replace(' ', '_', $folderClean);
				$folderId = preg_replace('/[^A-Za-z\-]/', '_', $folderId);
				$folderId = '_' . $folderId;


				$html .= '
				<div class="nav-folder is-collapsed">
					<div class="nav-folder-title" data-bs-toggle="collapse" data-bs-target="#' . $folderId . '-collapse" aria-expanded="false" onClick="toggleNavFolder(event);">
						<div class="nav-folder-collapse-indicator collapse-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon right-triangle">
								<path d="M3 8L12 17L21 8"></path>
							</svg>
						</div>
						<div class="nav-folder-title-content">' . $folderName . '</div>
					</div>
					<div class="nav-folder-children collapse" id="' . $folderId . '-collapse" style="">
						<div style="width: 591px; height: 0.1px; margin-bottom: 0px;"></div>';
				$html .= menu($file, $folder . '/');
				$html .= '</div></div>';
			}
		}
	}

	// iterate the files 
	foreach ($files as $file) {
		if (isMDFile($file)) {

			$path = getFileInfos($file)[0];
			$mdFile = getFileInfos($file)[1];

			$path = '/' . $path;
			// push the the path to the array
			array_push($avFiles, $path);

			// URL Encode the Path for the JS call
			$pathClean = rawurlencode($path);
			$pathID = str_replace(' ', '_', $path);
			$pathID = preg_replace('/[^A-Za-z0-9\-]/', '_', $path);


			$html .= '
			<div class="nav-file">
				<div class="nav-file-title perlite-link" onclick=getContent("' . $pathClean . '"); id="' . $pathID . '"">
					<div class="nav-file-title-content">' . $mdFile . '</div>
				</div>
			</div>
			';
		}
	}

	return $html;
}

function doSearch($dir, $searchfor)
{

	// $Parsedown = new Parsedown();
	// $Parsedown->setSafeMode(false);

	//$cleanSearch = htmlspecialchars($searchfor, ENT_QUOTES);

	$result = search($dir, $searchfor);
	$content = $result;
	//$content = $Parsedown->text($result);

	if ($content === '') {
		$content = '<div class="search-empty-state">No matches found.</div>';
	}

	return $content;
}

function search($dir, $searchfor, $folder = '')
{

	$files = glob($dir . '/*');
	$result = '';
	$matches = [];

	foreach ($files as $file) {


		// in case of folder
		if (is_dir($file)) {

			if (isValidFolder($file)) {
				$folder = getFolderInfos($file)[0];
				$result .= search($file, $searchfor, $folder . '/');
			}
		} else {

			if (isMDFile($file)) {

				$pathClean = getFileInfos($file)[0];
				$urlPathClean = rawurlencode($pathClean);

				// get the file contents, assuming the file to be readable (and exist)
				$contents = file_get_contents($file);

				$contents = $contents . $pathClean;
				// escape special characters in the query
				$pattern = preg_quote($searchfor, '/');

				// check if we search for an tag, if yes first parse the document to get the front matter tags
				if (substr($searchfor, 0, 1) === '#') {
					$Parsedown = new PerliteParsedown();
					$Parsedown->setSafeMode(true);
					$contents = $Parsedown->text($contents);
					$contents = strip_tags($contents);
				}

				// finalise the regular expression, matching the whole line
				$pattern = "/^.*$pattern.*\$/mi";
				// search, and store all matching occurences in $matches
				if (preg_match_all($pattern, $contents, $matches)) {

					$result .= '
					<br>
					<div class="tree-item search-result is-collapsed">
						<div class="tree-item-self search-result-file-title is-clickable">
							<div class="tree-item-icon collapse-icon" onclick="toggleSearchEntry(event);" style="">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon right-triangle">
									<path d="M3 8L12 17L21 8"></path>
								</svg>
							</div>
							<div class="tree-item-inner" onclick="getContent(\'/' . $urlPathClean . '\');">' . str_replace('/', ' / ', $pathClean) . '</div>
							<div class="tree-item-flair-outer"><span class="tree-item-flair">' . count($matches[0]) . '</span></div>
						</div>
					<div class="search-result-file-matches" style="display: none">
					<div style="width: 1px; height: 0.1px; margin-bottom: 0px;"></div><div class="search-result-file-match"><span>';

					// escape found string + highlight text
					$cleaned = array_map("htmlspecialchars", $matches[0]);
					//$cleaned =
					$out = str_ireplace($searchfor, '<span class="search-result-file-matched-text">' . $searchfor . '</span>',  $cleaned);
					$text = implode('</span></div><div class="search-result-file-match"><span>', $out);
					$result .= $text . '</span></div>

					</div>
				</div>';
				}
			}
		}
	}


	return $result;
}

// check if file is a md file
function isMDFile($file)
{

	$fileinfo = pathinfo($file);


	if (isset($fileinfo['extension']) and strtolower($fileinfo['extension']) == 'md') {
		return true;
	}

	return false;
}

function getFileInfos($file)
{

	global $rootDir;
	$mdFile = mb_basename($file);
	if (strcmp(substr($mdFile, -3), ".md") === 0) {
		$mdFile = substr($mdFile, 0, -3);
	}

	$folderClean = str_replace('$' . $rootDir, '', '$' . pathinfo($file)["dirname"]);

	$folderClean = substr($folderClean, 1);
	if (!strcmp($folderClean, '')) {
		$pathClean = $mdFile;
	} else {
		$pathClean = $folderClean . '/' . $mdFile;
	}

	return [$pathClean, $mdFile];
}

function mb_basename($path)
{
	if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
		return $matches[1];
	} else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
		return $matches[1];
	}
	return '';
}

function getFolderInfos($file)
{

	global $rootDir;
	$folder = str_replace($rootDir . '/', '', $file);
	$folderClean = str_replace('/', '-', $folder);
	$folderClean = str_replace(' ', '-', $folderClean);
	$folderName = mb_basename($file);

	return [$folder, $folderClean, $folderName];
}

function isValidFolder($file)
{

	global $hideFolders;
	$folderName = mb_basename($file);

	// check if folder is in array
	if (in_array($folderName, $hideFolders, true)) {
		return false;
	}

	if (strcmp(substr($folderName, 0, 1), '.') !== 0) {
		return true;
	}

	return false;
}

function getfullGraph($rootDir)
{


	$jsonMetadaFile = $rootDir . '/metadata.json';

	if (!is_file($jsonMetadaFile)) {
		return;
	}

	$jsonData = file_get_contents($jsonMetadaFile);

	if ($jsonData === false) {
		return;
	}

	$json_obj = json_decode($jsonData, true);
	if ($json_obj === null) {
		return;
	}

	$graphNodes = array();
	$graphEdges = array();

	$currentNode = -1;


	$nodeID = 0;
	// create nodes
	foreach ($json_obj as $id => $node) {

		$nodePath = removeExtension($node['relativePath']);

		// check if node from the  json file really exists
		if (checkArray($nodePath)) {
			// add node to the graph
			array_push($graphNodes, ['id' => $nodeID, 'label' => $node['fileName'], 'title' => $nodePath]);
			$nodeID += 1;
		}
	}
	$targetId = -1;
	$sourceId = -1;

	foreach ($json_obj as $index => $node) {

		$nodePath = removeExtension($node['relativePath']);

		// check if node from the json file really exists
		if (checkArray($nodePath)) {

			// create the linking between the nodes
			if (isset($node['links'])) {
				foreach ($node['links'] as $i => $links) {

					$source = "";
					$target = "";
					if (isset($node['relativePath'])) {
						$tempPath = removeExtension($node['relativePath']);
						if (checkArray($tempPath)) {
							$source = $tempPath;
							$tempPath = null;
						}
					}

					if (isset($links['relativePath'])) {

						$tempPath = removeExtension($links['relativePath']);
						if (checkArray($tempPath)) {
							$target = $tempPath;
							$tempPath = null;
						}
						
					}

					if ($source !== '' && $target !== '') {
						foreach ($graphNodes as $index => $element) {
							$elementTitle = $element['title'];

							if (strcmp($elementTitle, $target) == 0) {
								$targetId = $element['id'];
							}
							if (strcmp($elementTitle, $source) == 0) {
								$sourceId = $element['id'];
							}
							if ($targetId !== -1 && $sourceId !== -1) {
								array_push($graphEdges, ['from' => $sourceId, 'to' => $targetId]);
								$targetId = -1;
								$sourceId = -1;
							}
						}
					}
				}
			}
		}
	}

	$myGraphNodes = json_encode($graphNodes, JSON_UNESCAPED_SLASHES);
	$myGraphEdges = json_encode($graphEdges, JSON_UNESCAPED_SLASHES);

	return '<div id="allGraphNodes" style="display: none">' . $myGraphNodes . '</div><div id="allGraphEdges" style="display: none">' . $myGraphEdges . '</div>';
}

function removeExtension($path)
{

	return substr($path, 0, -3);
}

// check if node is in array
function checkArray($requestNode)
{
	global $avFiles;
	$requestNode = '/' . $requestNode;

	if (in_array($requestNode, $avFiles, true)) {

		return true;
	}

	return false;
}


function loadThemes($rootDir)
{

	// get themes

	$themes = "";
	$folders = glob($rootDir . '/.obsidian/themes/*');
	$appearanceFile = $rootDir . '/.obsidian/appearance.json';
	$defaultTheme = "";



	if (is_file($appearanceFile)) {
		$jsonData = file_get_contents($appearanceFile);
		if ($jsonData) {
			$json_obj = json_decode($jsonData, true);
			if ($json_obj) {

				// if theme is set, set it as default
				if (array_key_exists('cssTheme', $json_obj)) {
					$defaultTheme = $json_obj["cssTheme"];
				}
			}
		}
	}



	// iterate the folders 
	foreach ($folders as $folder) {
		if (is_dir($folder)) {

			$folderName = getFolderInfos($folder)[2];
			$folderClean = str_replace(' ', '_', $folderName);
			$themePath = $rootDir . '/.obsidian/themes/' . $folderName . '/theme.css';

			if ($defaultTheme === $folderName) {

				$themes .= '<link data-themename="'.$folderName.'" class="theme" id="'.$folderClean.'" href="' . $themePath . '" type="text/css" rel="stylesheet">';
			} else {

				$themes .= '<link data-themename="'.$folderName.'" class="theme" id="'.$folderClean.'" href="' . $themePath . '" type="text/css" rel="stylesheet" disabled="disabled">';
			}
		}
	}

	return $themes;
}
