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

	$base = basename($rootDir);
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
				
				$html .= '<button class="btn btn-toggle btn-hover-dark d-inline-flex text-info fw-bold align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#'.$folderClean.'-collapse" aria-expanded="false">'.$folderName .'</button>';
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
                                    <a href="#" onclick=getContent("'. $pathClean .'"); id="'. $pathID .'" class="link-light rounded">'. $mdFile .'</a>
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
	'<div class="searchTitle" style="display: none">Search for: ' . $cleanSearch . '</div>
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

			$fileinfo = pathinfo($file);
			if (isMDFile($file)) {

				$pathClean = getFileInfos($file)[0];
				$urlPathClean = rawurlencode($pathClean);
							
				// get the file contents, assuming the file to be readable (and exist)
				$contents = file_get_contents($file);
				// escape special characters in the query
				$pattern = preg_quote($searchfor, '/');
				
				// finalise the regular expression, matching the whole line
				$pattern = "/^.*$pattern.*\$/m";
				// search, and store all matching occurences in $matches
				if(preg_match_all($pattern, $contents, $matches)){
					$result .= '### <a href="#" onclick="getContent(\'/'.$urlPathClean.'\');">' . $pathClean ."</a>\n";
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
	
	if ($fileinfo["extension"] == "md") {
		return true;
	} 

	return false;
}

function getFileInfos($file) {

	global $rootDir;
	$mdFile = basename($file, '.md');
	$folderClean = str_replace('$' . $rootDir,'','$' . pathinfo($file)["dirname"]);
		
	$folderClean = substr($folderClean,1);
	if (!strcmp($folderClean,'')) {					
		$pathClean = $mdFile;
	} else {
		$pathClean = $folderClean . '/'. $mdFile; 
	}

	return [$pathClean,$mdFile];
}

function getFolderInfos($file) {
	
	global $rootDir;
	$folder = str_replace($rootDir. '/','',$file);
	$folderClean = str_replace('/','-',$folder);
	$folderClean = str_replace(' ','-',$folderClean);
	$folderName = basename($file);

	return [$folder, $folderClean, $folderName];

}

function isValidFolder($file) {

	global $hideFolders;
	$folderName = basename($file);

	// check if folder is in array
	if (in_array($folderName,$hideFolders, true)) {
		return false;
	}

	if (strcmp(substr($folderName,0,1),'.') !== 0 ) {
		return true;
	}

	return false;
}

?>