<?php



include('Parsedown.php');


$avFiles = array();
//$rootDir = 'notes/WriteUps';
$rootDir = getenv('NOTES_PATH');
$hideFolders = getenv('HIDE_FOLDERS');
$about = '.about';

// add about to allowed files
$path = getFileInfos($rootDir .'/' . $about)[0];
$path = '/'.$path;
array_push($avFiles, $path);



// hide folders
if (strcmp($hideFolders,'')) {

	$hideFolders = explode(',', $hideFolders);

} else {
	$hideFolders = array();
}


// path management
if (!strcmp($rootDir,"")) {

	$rootDir = getcwd();
	$base = "Notes";
	$startDir = "";

} else {

	$base = mb_basename($rootDir);
	$startDir = $rootDir;
		
}



function menu($dir, $folder = ''){
	
	global $avFiles;
    $html = '';
	// get all files from current dir
	$files = glob($dir.'/*');

	foreach($files as $file){
		
		// in case of folder
		if(is_dir($file)){
			
			if (isValidFolder($file)) {
				$html .= '<li class="mb-1">';	
				
				// split Folder Infos
				$folder = getFolderInfos($file)[0];
				$folderClean = getFolderInfos($file)[1];
				$folderName = getFolderInfos($file)[2];
				
				$html .= '<button class="btn btn-toggle pb-1 pt-1 nav-link border-0 d-inline-flex align-items-center collapsed" data-bs-toggle="collapse" data-bs-target="#'.$folderClean.'-collapse" aria-expanded="false">'.$folderName .'</button>';
				$html .= '<div class="collapse" id="'.$folderClean.'-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal pb-1">';				
				$html .= menu($file,$folder.'/');
				$html .= '</ul></div></li>';
			}
		// in case of file
		} else {
					
			// check if file is a md file 
			if (isMDFile($file)) {
				

				$path = getFileInfos($file)[0];
                $mdFile = getFileInfos($file)[1];
				
				$path = '/'.$path;
				// push the the path to the array
				array_push($avFiles, $path);

				// URL Encode the Path for the JS call
                $pathClean = rawurlencode($path);
				$pathID = str_replace(' ', '_', $path);
				$pathID = preg_replace('/[^A-Za-z0-9\-]/', '_', $path);

                $html .= '		<li>
                                    <a href="#" onclick=getContent("'. $pathClean .'"); id="'. $pathID .'" class="perlite-link">'. $mdFile .'</a>
                                </li>';
			} 			
		}
	}
	
	return $html;
}


function doSearch($dir, $searchfor) {

	$Parsedown = new Parsedown();
	$Parsedown->setSafeMode(false);

	$cleanSearch = htmlspecialchars($searchfor, ENT_QUOTES);
	
	$result = search($dir, $searchfor);
	$content = $Parsedown->text($result);
	
	return
	'<div class="searchTitle" style="display: none">Search results for: ' . $cleanSearch . '</div>
	<div class="lastSearch" style="display: none"><a href="#">open recent search</a></div>
	<br><br>'.$content;

}


function search($dir, $searchfor, $folder = '') {

	$files = glob($dir.'/*');
	$result = '';
	$matches = [];

	foreach($files as $file){
		
		// in case of folder
		if(is_dir($file)){
			
			if (isValidFolder($file)) {
				$folder = getFolderInfos($file)[0];
				$result .= search($file, $searchfor, $folder.'/');
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
				
				// finalise the regular expression, matching the whole line
				$pattern = "/^.*$pattern.*\$/mi";
				// search, and store all matching occurences in $matches
				if(preg_match_all($pattern, $contents, $matches)){
					$result .= '<a href="#" onclick="getContent(\'/'.$urlPathClean.'\');">' . $pathClean ."</a>\n";
					$result .= "```plaintext \n";				
					$result .= implode("\n", $matches[0]);					
					$result .= "\n```\n";
					$result .= "\n&nbsp;\n";
				}

			}

		}
	}


	return $result;

}

// check if file is a md file
function isMDFile($file) {

	$fileinfo = pathinfo($file);
	
			
	if( isset($fileinfo['extension']) AND strtolower($fileinfo['extension']) == 'md'){
		return true;
	}

	return false;
}

function getFileInfos($file) {

	global $rootDir;
	$mdFile = mb_basename($file);
	if (strcmp(substr($mdFile,-3), ".md") === 0) {
		$mdFile = substr($mdFile,0, -3);
	}
	
	$folderClean = str_replace('$' . $rootDir,'','$' . pathinfo($file)["dirname"]);
		
	$folderClean = substr($folderClean,1);
	if (!strcmp($folderClean,'')) {					
		$pathClean = $mdFile;
	} else {
		$pathClean = $folderClean . '/'. $mdFile; 
	}

	return [$pathClean,$mdFile];
}

function mb_basename($path) {
    if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    } else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    }
    return '';
}


function getFolderInfos($file) {
	
	global $rootDir;
	$folder = str_replace($rootDir. '/','',$file);
	$folderClean = str_replace('/','-',$folder);
	$folderClean = str_replace(' ','-',$folderClean);
	$folderName = mb_basename($file);

	return [$folder, $folderClean, $folderName];

}

function isValidFolder($file) {

	global $hideFolders;
	$folderName = mb_basename($file);

	// check if folder is in array
	if (in_array($folderName,$hideFolders, true)) {
		return false;
	}

	if (strcmp(substr($folderName,0,1),'.') !== 0 ) {
		return true;
	}

	return false;
}


function getfullGraph($rootDir) {
	

	$jsonMetadaFile = $rootDir.'/metadata.json';
	
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
			array_push($graphNodes, ['id'=>$nodeID, 'label'=>$node['fileName'], 'title'=>$nodePath]);
			$nodeID += 1;
		}
				
	}
	$targetId = -1;
	$sourceId = -1;

	foreach ($json_obj as $index => $node) {
								
		// create the linking between the nodes
		if (isset($node['links'])) {
			foreach ($node['links'] as $i => $links) {

				$source = "";
				$target = "";
				if (isset($node['relativePath'])) {
					$source = removeExtension($node['relativePath']);
				}

				if (isset($links['relativePath'])) {
					$target = removeExtension($links['relativePath']);
				}

				if ($source !== '' && $target !== '') {
					foreach ($graphNodes as $index => $element) {
						$elementTitle = $element['title'];
	
						if(strcmp($elementTitle,$target) == 0){
							$targetId = $element['id'];
						}
						if(strcmp($elementTitle,$source) == 0){
							$sourceId = $element['id'];
						}					
						if($targetId !== -1 && $sourceId !== -1) {
							array_push($graphEdges, ['from'=>$sourceId, 'to' => $targetId]);
							$targetId = -1;
							$sourceId = -1;
						}		
					}		

				}
				
						
			}
		}			
	}
	
	$myGraphNodes = json_encode($graphNodes,JSON_UNESCAPED_SLASHES);
	$myGraphEdges = json_encode($graphEdges,JSON_UNESCAPED_SLASHES);

	return '<div id="allGraphNodes" class="hide">'.$myGraphNodes.'</div><div id="allGraphEdges" class="hide">'.$myGraphEdges.'</div>';

}

function removeExtension($path) {

	return substr($path,0,-3);
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
