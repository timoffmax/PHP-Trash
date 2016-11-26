<?php require_once('db_connect.php') ?>

<html>
<head>
	<title>ODS Servers Monitoring</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	<!-- Autoload script to div "content" and autorefresh -->
	<script>  
	    function show()  
	    {  
	        $.ajax({  
	            url: "get_statistics.php",  
	            cache: false,  
	            success: function(html){  
	                $("#content").html(html);  
	            }  
	        });  
	    }

	    $(document).ready(function(){  
	        show();  
	        setInterval('show()',1000);  
	    });  
	</script>	  

</head>
<body>
	
	<!-- Block for load main script (get_statistics.php) -->
	<div id="content"></div>

</body>
</html>
