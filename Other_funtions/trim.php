<?php 
	
	function user_trim($text) {

		mysqli_escape_string(trim(strip_tags($text)));
		#mysql_real_escape_string();
	
	}

 ?>