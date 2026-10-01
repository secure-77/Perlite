<?php

/*!
 * Perlite v1.6.2 (https://github.com/secure-77/Perlite)
 * Author: sec77 (https://secure77.de)
 * Licensed under MIT (https://github.com/secure-77/Perlite/blob/main/LICENSE)
 */

use Perlite\PerliteParsedown;

//
// settings
//
// All settings are read from environment variables (e.g. docker compose env_file).
// Without docker, place a .env file next to the perlite folder (preferred, outside of the web root)
// or inside the perlite folder. Real environment variables take precedence over the .env file.
//

// parse a .env file (KEY=VALUE per line, # comments, optional quotes, optional "export " prefix)
function parseEnvFile($file)
{
	$vars = [];
	$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if ($lines === false)
		return $vars;

	foreach ($lines as $line) {
		$line = trim($line);
		if ($line === '' || $line[0] === '#' || strpos($line, '=') === false)
			continue;

		[$key, $value] = explode('=', $line, 2);
		$key = trim(preg_replace('/^export\s+/', '', trim($key)));
		$value = trim($value);

		if (strlen($value) >= 2 && ($value[0] === '"' || $value[0] === "'") && substr($value, -1) === $value[0]) {
			// quoted value, keep as is
			$value = substr($value, 1, -1);
		} else {
			// unquoted value, strip inline comments (" # comment")
			$value = trim(preg_replace('/\s+#.*$/', '', $value));
		}

		$vars[$key] = $value;
	}
	return $vars;
}

$envFileVars = [];
$envFileDir = '';
foreach ([dirname(__DIR__) . '/.env', __DIR__ . '/.env'] as $envFile) {
	if (is_file($envFile) && is_readable($envFile)) {
		$envFileVars = parseEnvFile($envFile);
		$envFileDir = dirname($envFile);
		break;
	}
}

// get a setting, empty values fall back to the default
function getSetting($key, $default = '')
{
	global $envFileVars;

	$value = getenv($key);
	if ($value === false || $value === '')
		$value = $envFileVars[$key] ?? '';

	return $value === '' ? $default : $value;
}

function getBoolSetting($key, $default)
{
	$value = getSetting($key);
	return $value === '' ? $default : filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

function getListSetting($key, $default)
{
	$value = getSetting($key);
	return $value === '' ? $default : array_map('trim', explode(',', $value));
}


//
// default settings and variables
//

$avFiles = array();

// --- General Settings ---
$rootDir = rtrim(getSetting('NOTES_PATH', 'Demo'), '/\\');

// NOTES_PATH can also be the docker compose host path (e.g. ./perlite/Demo), relative to the .env file,
// use it as folder relative to the perlite folder if it is located inside
if ($rootDir !== '' && !is_dir(__DIR__ . '/' . $rootDir) && $envFileDir !== '') {
	$notesPath = realpath($envFileDir . '/' . $rootDir);
	$perlitePath = realpath(__DIR__);
	if ($notesPath !== false && strpos($notesPath, $perlitePath . DIRECTORY_SEPARATOR) === 0)
		$rootDir = str_replace('\\', '/', substr($notesPath, strlen($perlitePath) + 1));
}

$vaultName = getSetting('VAULT_NAME', mb_basename($rootDir));
$index = getSetting('HOME_FILE', 'README');
$siteTitle = getSetting('SITE_TITLE', 'Perlite');

// --- Frontend Settings ---
$lineBreaks = getBoolSetting('LINE_BREAKS', true);
$disablePopHovers = getBoolSetting('DISABLE_POP_HOVER', false);
$showTOC = getBoolSetting('SHOW_TOC', true);
$showLocalGraph = getBoolSetting('SHOW_LOCAL_GRAPH', true);
$font_size = getSetting('FONT_SIZE', '15');
$hideFolders = getSetting('HIDE_FOLDERS');
$niceLinks = getBoolSetting('NICE_LINKS', true);

// --- Advanced Settings ---
$hiddenFileAccess = getBoolSetting('HIDDEN_FILE_ACCESS', false);
$absolutePath = getBoolSetting('ABSOLUTE_PATHS', false);
$uriPath = getSetting('URI_PATH', '/');
$htmlSafeMode = getBoolSetting('HTML_SAFE_MODE', true);
$useZettelkastenFilenames = getBoolSetting('ZETTELKASTEN_FILENAMES_ENABLED', false);
$highlightJSLangs = getListSetting('HIGHLIGHTJS_LANGS', ['powershell']);
$allowedFileLinkTypes = getListSetting('ALLOWED_FILE_LINK_TYPES', ['pdf', 'mp4']);
$treeVisibleExtensions = array_filter(array_map('strtolower', getListSetting('TREE_VISIBLE_EXTENSIONS', [])), 'strlen');
$canvasIframeEmbeds = strtolower(getSetting('CANVAS_IFRAME_EMBEDS', 'click'));
if (!in_array($canvasIframeEmbeds, ['off', 'click', 'auto'], true))
	$canvasIframeEmbeds = 'click';
$tempPath = getSetting('TEMP_PATH', sys_get_temp_dir());

// --- Metadata Settings ---
$siteType = getSetting('SITE_TYPE', 'article');
$siteImage = getSetting('SITE_IMAGE', 'https://raw.githubusercontent.com/secure-77/Perlite/main/screenshots/screenshot.png');
$siteURL = getSetting('SITE_URL', 'https://perlite.secure77.de');
$siteDescription = getSetting('SITE_DESC', 'A web based markdown viewer optimized for Obsidian Notes');
$siteName = getSetting('SITE_NAME', 'Perlite Demo');

// --- Profile Settings ---
$siteLogo = getSetting('SITE_LOGO');
$siteHomepage = getSetting('SITE_HOMEPAGE', $siteURL);
$siteGithub = getSetting('SITE_GITHUB');
$siteTwitter = getSetting('SITE_TWITTER');

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
	$vaultName = getSetting('VAULT_NAME', mb_basename($rootDir));
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
  global $useZettelkastenFilenames;
	global $treeVisibleExtensions;
	global $uriPath;
	global $startDir;
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

  // Iterate the files
  foreach ($files as $file) {
    if (isMDFile($file)) {
      $pathInfo = getFileInfos($file);
      $relativePathForURL = $pathInfo[0];
      $baseFilenameWithoutExtension = $pathInfo[1];

      $displayTitle = "";

      if ($useZettelkastenFilenames) {
        $displayTitle = getDisplayTitle($file);
      } else {
        $displayTitle = $baseFilenameWithoutExtension;
      }

      $urlClickPath = '/' . $relativePathForURL;
      array_push($avFiles, $urlClickPath);
      $pathCleanForJS = rawurlencode($urlClickPath);
      
      // Create a unique ID for the HTML element
      $elementId = 'fileid-' . preg_replace('/[^A-Za-z0-9\-_]/', '_', $urlClickPath);
      $elementId = str_replace('/', '_', $elementId); // Replace slashes for cleaner ID

      $html .= '
      <div class="tree-item nav-file">
          <div class="nav-file-title perlite-link" onclick="getContent(\'' . $pathCleanForJS . '\');" id="' . htmlspecialchars($elementId) . '">
              <div class="nav-file-title-content">' . htmlspecialchars($displayTitle) . '</div>
          </div>
      </div>
      ';
    } else if (isCanvasFile($file)) {
      // canvas file, stored with extension to not collide with a note of the same name
      $urlClickPath = '/' . getFileInfos($file)[0];
      array_push($avFiles, $urlClickPath);
      $pathCleanForJS = rawurlencode($urlClickPath);

      $elementId = 'fileid-' . preg_replace('/[^A-Za-z0-9\-_]/', '_', $urlClickPath);
      $elementId = str_replace('/', '_', $elementId);

      $html .= '
      <div class="tree-item nav-file">
          <div class="nav-file-title perlite-link" onclick="getContent(\'' . $pathCleanForJS . '\');" id="' . htmlspecialchars($elementId) . '">
              <div class="nav-file-title-content">' . htmlspecialchars(pathinfo($file, PATHINFO_FILENAME)) . '</div>
              <div class="nav-file-tag">canvas</div>
          </div>
      </div>
      ';
    } else if (isTreeVisibleFile($file)) {
      // non md file, link it for download (served directly by the webserver)
      $relativePath = getFileInfos($file)[0];
      $fileName = mb_basename($file);
      $urlPath = ($startDir !== '' ? $startDir . '/' : '') . $relativePath;
      $fileURL = $uriPath . implode('/', array_map('rawurlencode', explode('/', $urlPath)));

      $html .= '
      <div class="tree-item nav-file">
          <a class="nav-file-title perlite-download-link" href="' . htmlspecialchars($fileURL) . '" download="' . htmlspecialchars($fileName) . '">
              <div class="nav-file-title-content">' . htmlspecialchars(pathinfo($fileName, PATHINFO_FILENAME)) . '</div>
              <div class="nav-file-tag">' . htmlspecialchars(strtolower(pathinfo($fileName, PATHINFO_EXTENSION))) . '</div>
          </a>
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

// check if file is an obsidian canvas file
function isCanvasFile($file)
{
	return strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'canvas';
}

// check if a non md file should be listed in the navigation (TREE_VISIBLE_EXTENSIONS)
function isTreeVisibleFile($file)
{
	global $treeVisibleExtensions;

	if (empty($treeVisibleExtensions) || !is_file($file))
		return false;

	$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	return $ext !== '' && $ext !== 'md' && in_array($ext, $treeVisibleExtensions, true);
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

function getDisplayTitle($filePath) {
    $content = @file_get_contents($filePath);
    if ($content === false) {
        // Fallback to filename if file can't be read
        return getFileInfos($filePath)[1];
    }

    // 1. Try to get title from frontmatter
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $frontmatterMatches)) {
        $frontmatterRaw = $frontmatterMatches[1];
        if (preg_match('/^title:\s*(.*?)\s*$/im', $frontmatterRaw, $titleMatches)) {
            $title = trim($titleMatches[1]);
            $title = trim($title, "'\"");
            if (!empty($title)) {
                return $title;
            }
        }
    }

    // 2. Try to get the first H1 heading
    if (preg_match('/^#\s+(.*?)\s*$/m', $content, $h1Matches)) {
        $h1Title = trim($h1Matches[1]);
        if (!empty($h1Title)) {
            return $h1Title;
        }
    }

    // 3. Fallback to filename (obtained via getFileInfos)
    return getFileInfos($filePath)[1];
}

function getfullGraph($rootDir)
{

	global $tempPath;

	$vaultFolder = mb_basename($rootDir);
	$jsonMetadaFile = $rootDir . '/metadata.json';
	$metadaTempFile = $tempPath . '/metadata_' . $vaultFolder . '.temp';
	$metadaTempFileSum = $tempPath . '/metadata_' . $vaultFolder . '.md5';



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
	global $highlightJSLangs;


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

				$themes .= '<link data-themename="' . $folderName . '" class="theme" id="' . $folderClean . '" href="' . $themePath . '" type="text/css" rel="stylesheet">
	';
			} else {

				$themes .= '<link data-themename="' . $folderName . '" class="theme" id="' . $folderClean . '" href="' . $themePath . '" type="text/css" rel="stylesheet" disabled="disabled">
	';
			}
		}
	}

	// default settings
	$defaultSettings = '<link id="disablePopHovers" data-option="' . ($disablePopHovers ? 'true' : 'false') . '">';
	$defaultSettings .= '<link id="showTOC" data-option="' . ($showTOC ? 'true' : 'false') . '">';
	$defaultSettings .= '<link id="showLocalGraph" data-option="' . ($showLocalGraph ? 'true' : 'false') . '">';
	$defaultSettings .= '<link id="index" data-option="' . $index . '">';
	$defaultSettings .= '<link id="uri_path" data-option="' . $uriPath . '">';


	// Meta Tags
	$defaultSettings .=
	'
		
	<!--  Essential META Tags -->
    <meta property="og:title" content="' . $siteTitle . '">
    <meta property="og:type" content="' . $siteType . '" />
    <meta property="og:image" content="' . $siteImage . '">
    <meta property="og:url" content="' . $siteURL . '">
    <meta name="twitter:card" content="summary_large_image">

    <!--  Non-Essential, But Recommended -->
    <meta property="og:description" content="' . $siteDescription . '">
    <meta property="og:site_name" content="' . $siteName . '">
    <meta name="twitter:image:alt" content="Page Callout">
    <meta name="twitter:site" content="' . $siteTwitter . '">';



	// highlight.js languages
	//$highlightLangs = explode(',', $highlightJSLangs);

	foreach ($highlightJSLangs as $lang) {
		$defaultSettings .= '
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/'. trim($lang) .'.min.js"></script>';
	}

	return $themes . $defaultSettings;
}
