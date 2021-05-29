<?php


$avFiles = array();

function menu($dir, $folder = ''){
	global $avFiles;
    $html = '';
	// get all files from current dir
	$files = glob($dir.'/*');

	foreach($files as $file){
		
		// in case of folder
		if(is_dir($file)){
			
			$folderName = basename($file);
			if (strcmp(substr($folderName,0,1),'.') !== 0 ) {
				$html .= '<li class="mb-1">';	
				$folder = str_replace(getcwd(). '/','',$file);
				$folderClean = str_replace('/','-',$folder);
				$folderClean = str_replace(' ','-',$folderClean);
				$html .= '<button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#'.$folderClean.'-collapse" aria-expanded="false">'.$folderName .'</button>';
				$html .= '<div class="collapse" id="'.$folderClean.'-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal pb-1">';				
				$html .= menu($file,$folder.'/');
				$html .= '</ul></div></li>';
			}
		// in case of file
		} else {
			
			$mdFile = basename($file, '.md');
			$fileinfo = pathinfo($file);
			if ($fileinfo["extension"] == "md") {
				$folderClean = str_replace(getcwd(),'',pathinfo($file)["dirname"]);
				$folderClean = substr($folderClean,1);
				if (!strcmp($folderClean,'')) {					
					$pathClean = $mdFile;
				} else {
					$pathClean = $folderClean . '/'. $mdFile; 
				}
                
				// push the the path to the array
				array_push($avFiles, $pathClean);
                $pathClean = rawurlencode($pathClean);

                $html .= '		<li>
                                    <a href="#" onclick=getContent("'. $pathClean .'"); class="link-light rounded">'. $mdFile .'</a>
                                </li>';
			} 			
		}
	}
	
	return $html;
}



?>
