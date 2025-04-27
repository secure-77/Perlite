<?php

/*!
 * Perlite v1.6 (https://github.com/secure-77/Perlite)
 * Author: sec77 (https://secure77.de)
 * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
 */

use Perlite\PerliteParsedown;

// load settings from settings.php if it exists instead of using environment variables
if (file_exists("settings.php")) {
	include "settings.php";
}

//
// default settings and variables
//


$avFiles = array();

// replace with your Vault Folder
if (empty($rootDir))
	$rootDir = empty(getenv('NOTES_PATH')) ? 'Demo' : getenv('NOTES_PATH');
$vaultName = $rootDir;

//if (empty($uriPath)) $uriPath = empty(getenv('URI_PATH')) ? '/perlite/' : getenv('URI_PATH');
if (empty($uriPath))
	$uriPath = empty(getenv('URI_PATH')) ? '/' : getenv('URI_PATH');



// hide folders
if (empty($hideFolders))
	$hideFolders = getenv('HIDE_FOLDERS');

// allow access to md files in hidden folders
if (!isset($hiddenFileAccess))
	$hiddenFileAccess = empty(getenv('HIDDEN_FILE_ACCESS')) ? false : filter_var(getenv('HIDDEN_FILE_ACCESS'), FILTER_VALIDATE_BOOLEAN);

// use absolut paths instead of relative paths
if (!isset($relPathes))
	$relPathes = empty(getenv('ABSOLUTE_PATHS')) ? false : filter_var(getenv('ABSOLUTE_PATHS'), FILTER_VALIDATE_BOOLEAN);

// Meta Tags infos
if (empty($siteTitle))
	$siteTitle = empty(getenv('SITE_TITLE')) ? 'Perlite' : getenv('SITE_TITLE');
if (empty($siteType))
	$siteType = empty(getenv('SITE_TYPE')) ? 'article' : getenv('SITE_TYPE');
if (empty($siteImage))
	$siteImage = empty(getenv('SITE_IMAGE')) ? 'https://raw.githubusercontent.com/secure-77/Perlite/main/screenshots/screenshot.png' : getenv('SITE_IMAGE');
if (!isset($siteURL))
	$siteURL = empty(getenv('SITE_URL')) ? 'https://perlite.secure77.de' : getenv('SITE_URL');
if (empty($siteLogo))
	$siteLogo = getenv("SITE_LOGO");
if (empty($siteDescription))
	$siteDescription = empty(getenv('SITE_DESC')) ? 'A web based markdown viewer optimized for Obsidian Notes' : getenv('SITE_DESC');
if (empty($siteName))
	$siteName = empty(getenv('SITE_NAME')) ? 'Perlite Demo' : getenv('SITE_NAME');
if (empty($siteHomepage))
	$siteHomepage = empty(getenv("SITE_HOMEPAGE")) ? $siteURL : getenv("SITE_HOMEPAGE");
if (empty($siteGithub))
	$siteGithub = getenv("SITE_GITHUB");
if (!isset($siteTwitter))
	$siteTwitter = getenv('SITE_TWITTER');

// Temp PATH for graph linking temp files
if (empty($tempPath))
	$tempPath = empty(getenv('TEMP_PATH')) ? sys_get_temp_dir() : getenv('TEMP_PATH');

// line breaks
if (!isset($lineBreaks))
	$lineBreaks = empty(getenv('LINE_BREAKS')) ? true : filter_var(getenv('LINE_BREAKS'), FILTER_VALIDATE_BOOLEAN);

// file types
if (empty($allowedFileLinkTypes))
	$allowedFileLinkTypes = empty(getenv('ALLOWED_FILE_LINK_TYPES')) ? ['pdf', 'mp4'] : explode(",", getenv('ALLOWED_FILE_LINK_TYPES'));

// disable PopHovers
if (empty($disablePopHovers))
	$disablePopHovers = empty(getenv('DISABLE_POP_HOVER')) ? "false" : getenv('DISABLE_POP_HOVER');

// show TOC
if (empty($showTOC))
	$showTOC = empty(getenv('SHOW_TOC')) ? "true" : getenv('SHOW_TOC');

// show local Graph
if (empty($showLocalGraph))
	$showLocalGraph = empty(getenv('SHOW_LOCAL_GRAPH')) ? "true" : getenv('SHOW_TOC');

// Set home page from env/settings
if (empty($index))
	$index = empty(getenv('HOME_FILE')) ? "README" : getenv('HOME_FILE');

// set default font size
if (empty($font_size))
	$font_size = empty(getenv('FONT_SIZE')) ? "15" : getenv('FONT_SIZE');

// Set safe mode from env/settings
if (!isset($htmlSafeMode))
	$htmlSafeMode = empty(getenv('HTML_SAFE_MODE')) ? true : filter_var(getenv('HTML_SAFE_MODE'), FILTER_VALIDATE_BOOLEAN);

// Custom Site Section
if (!isset($customSection))
	$customSection = '';
if ($siteLogo and empty($customSection)) {
	$customSection = '<div class="sm-site-title">&nbsp;</div>
                                    <div class="custom-page">
					                  <img class="custom-page-logo" src="' . $uriPath . $siteLogo . '" alt="Custom Logo">
					                  <div> &nbsp;</div>';

	$customSection = $customSection . '
	                                  <div class="sm-site-desc"><i>' . $siteDescription . '</i></div>
					                  <div>
									    <ul class="social-media-list">';

	if (!empty($siteGithub)) {
		$customSection = $customSection . '
		                                  <li>
		                                    <a href="' . $siteGithub . '">
										      <img class="social-logo" src="' . $uriPath . '.styles/github-color.svg" alt="Github Logo">
										    </a>
										  </li>';
	}

	if (!empty($siteTwitter)) {
		$customSection = $customSection . '
		                                  <li>
		                                    <a href="https://x.com/' . substr($siteTwitter, 1) . '">
										      <img class="social-logo" src="' . $uriPath . '.styles/x-color.svg" alt="X Logo">
										    </a>
										  </li>';
	}

	$customSection = $customSection . '
						                  <li>
	                                        <a href="' . $siteHomepage . '">
									          <img class="social-logo" src="' . $uriPath . '.styles/fontawesome-color.svg" alt="Homepage Logo">
									        </a>
									      </li>
					                    </ul>';

	$customSection = $customSection . '
	                                  </div>
	                                </div>';
}


$about = '.about';

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

	global $hiddenFileAccess;
	global $avFiles;
	$html = '';
	// get all files from current dir
	$files = glob($dir . '/*');

	// sort array
	usort($files, "cmp");

	// iterate the folders 
	foreach ($files as $file) {
		if (is_dir($file)) {

			// check if we want to hide the folder
			if (isValidFolder($file)) {

				// split Folder Infos
				$folder = getFolderInfos($file)[0];
				$folderClean = getFolderInfos($file)[1];
				$folderName = getFolderInfos($file)[2];
				$folderId = str_replace(' ', '_', $folderClean);
				$folderId = preg_replace('/[^A-Za-z\-]/', '_', $folderId);
				$folderId = '_' . $folderId;


				$html .= '
				<div class="tree-item nav-folder is-collapsed">
					<div class="tree-item-self is-clickable mod-collapsible nav-folder-title" data-bs-toggle="collapse" data-bs-target="#' . $folderId . '-collapse" aria-expanded="false" onClick="toggleNavFolder(event);" style="margin-left: 0px !important; padding-left: 24px !important;">
						<div class="tree-item-icon collapse-icon nav-folder-collapse-indicator is-collapsed">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-icon right-triangle"><path d="M3 8L12 17L21 8"></path></svg>
						</div>
						<div class="tree-item-inner nav-folder-title-content">' . $folderName . '</div>
					</div>
					<div class="tree-item-children nav-folder-children collapse" id="' . $folderId . '-collapse" style="">
						<div style="width: 591px; height: 0.1px; margin-bottom: 0px;"></div>';
				$html .= menu($file, $folder . '/');
				$html .= '</div></div>';
			} else if($hiddenFileAccess) {
				// dont list the folder but add the file to the array
				menu($file);
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
			<div class="tree-item nav-file">
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
					$out = str_ireplace($searchfor, '<span class="search-result-file-matched-text">' . $searchfor . '</span>', $cleaned);
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

function isCached($jsonMetadaFile, $metadaTempFileSum)
{
	if (is_file($metadaTempFileSum)) {
		$md5_envsum = file_get_contents($metadaTempFileSum);
		$md5_filesum = md5_file($jsonMetadaFile);

		if ($md5_envsum === $md5_filesum) {

			return true;
		}
	}

	return false;
}

function getfullGraph($rootDir)
{

	global $tempPath;
	global $vaultName;

	$jsonMetadaFile = $rootDir . '/metadata.json';
	$metadaTempFile = $tempPath . '/metadata_' . $vaultName . '.temp';
	$metadaTempFileSum = $tempPath . '/metadata_' . $vaultName . '.md5';



	if (!is_file($jsonMetadaFile)) {
		return;
	}

	// check if metadata file has changed
	if (is_file($metadaTempFile) and isCached($jsonMetadaFile, $metadaTempFileSum)) {
		return file_get_contents($metadaTempFile);
	}

	// metadata has changed / was not cached
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
			$thisNodeID = $nodeID;  // does not get overwritten when a tag node gets created

			// add node to the graph
			array_push($graphNodes, ['id' => $nodeID, 'label' => $node['fileName'], 'title' => $nodePath]);
			$nodeID += 1;

			// create tag nodes if they don't already exist
			if (isset($node["tags"])) {
				foreach ($node["tags"] as $tag) {
					$tag = "#" . $tag;

					$tagID = -1;
					$tagExists = false;
					foreach ($graphNodes as $graphNode) {
						if ($graphNode["label"] == $tag) {
							$tagID = $graphNode["id"];
							$tagExists = true;
						}
					}

					if (!$tagExists) {
						$tagID = $nodeID;

						$tag = ["id" => $nodeID, "label" => $tag, "title" => $tag, "group" => "tag"];

						array_push($graphNodes, $tag);
						$nodeID += 1;
					}

					array_push($graphEdges, ["from" => $thisNodeID, "to" => $tagID]);
				}
			}
		}
	}
	$targetId = -1;
	$sourceId = -1;

	// create links
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
							$edgeExists = false;

							foreach ($graphEdges as $edge) {
								if ($edge['from'] === $sourceId && $edge['to'] === $targetId) {
									$edgeExists = true;
									break;
								}
								if ($edge['to'] === $sourceId && $edge['from'] === $targetId) {
									$edgeExists = true;
									break;
								}
							}
							if ($targetId !== -1 && $sourceId !== -1) {
								if (!$edgeExists) {
									array_push($graphEdges, ['from' => $sourceId, 'to' => $targetId]);
								}
								$targetId = -1;
								$sourceId = -1;
							}
						}
					}
				}
			}
		}
	}

	foreach ($graphEdges as $graphEdge) {
		foreach ($graphNodes as &$graphNode) {
			if ($graphEdge["from"] == $graphNode["id"] or $graphEdge["to"] == $graphNode["id"]) {
				$nodeValue = 0;

				if (isset($graphNode["value"])) {
					$nodeValue = $graphNode["value"];
				}

				$nodeValue += 1;

				$graphNode["value"] = $nodeValue;
			}
		}
	}

	$myGraphNodes = json_encode($graphNodes, JSON_UNESCAPED_SLASHES);
	$myGraphEdges = json_encode($graphEdges, JSON_UNESCAPED_SLASHES);

	// write tempfile and store sum
	$metadaTempFile_handler = fopen($metadaTempFile, "w") or die("Unable to open file!");
	$graphHTML = '<div id="allGraphNodes" style="display: none">' . $myGraphNodes . '</div><div id="allGraphEdges" style="display: none">' . $myGraphEdges . '</div>';
	fwrite($metadaTempFile_handler, $graphHTML);
	fclose($metadaTempFile_handler);

	$metadaTempFile_handler = fopen($metadaTempFileSum, "w") or die("Unable to open file!");
	$md5_filesum = md5_file($jsonMetadaFile);
	fwrite($metadaTempFile_handler, $md5_filesum);
	fclose($metadaTempFile_handler);

	return $graphHTML;
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


function loadSettings($rootDir)
{

	global $disablePopHovers;
	global $showTOC;
	global $showLocalGraph;
	global $index;
	global $siteTitle;
	global $siteType;
	global $siteImage;
	global $siteURL;
	global $siteDescription;
	global $siteName;
	global $siteTwitter;
	global $uriPath;


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
			$themePath = $uriPath . $rootDir . '/.obsidian/themes/' . $folderName . '/theme.css';

			if ($defaultTheme === $folderName) {

				$themes .= '<link data-themename="' . $folderName . '" class="theme" id="' . $folderClean . '" href="' . $themePath . '" type="text/css" rel="stylesheet">';
			} else {

				$themes .= '<link data-themename="' . $folderName . '" class="theme" id="' . $folderClean . '" href="' . $themePath . '" type="text/css" rel="stylesheet" disabled="disabled">';
			}
		}
	}


	// Meta Tags
	$defaultSettings =
		'<!--  Essential META Tags -->
    <meta property="og:title" content="' . $siteTitle . '">
    <meta property="og:type" content="' . $siteType . '" />
    <meta property="og:image" content="' . $siteImage . '">
    <meta property="og:url" content="' . $siteURL . '">
    <meta name="twitter:card" content="summary_large_image">

    <!--  Non-Essential, But Recommended -->
    <meta property="og:description" content="' . $siteDescription . '">
    <meta property="og:site_name" content="' . $siteName . '">
    <meta name="twitter:image:alt" content="Page Callout">

    <!--  Non-Essential, But Required for Analytics -->
    <meta name="twitter:site" content="' . $siteTwitter . '">';

	// default settings
	$defaultSettings .= '<link id="disablePopHovers" data-option="' . $disablePopHovers . '"</link>';
	$defaultSettings .= '<link id="showTOC" data-option="' . $showTOC . '"</link>';
	$defaultSettings .= '<link id="showLocalGraph" data-option="' . $showLocalGraph . '"</link>';
	$defaultSettings .= '<link id="index" data-option="' . $index . '"</link>';


	return $themes . $defaultSettings;
}
