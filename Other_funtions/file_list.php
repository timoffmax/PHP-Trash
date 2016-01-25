<?php 
	
	function file_list($path = ".") {

		$dir = opendir($path);

		while ($file = readdir($dir)) {
			
			if (is_file($file)) {
				echo $file . "<br>";
			}
			
		}

		closedir($dir);
	
	}

?>