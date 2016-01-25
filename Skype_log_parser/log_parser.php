<!DOCTYPE html>
<html>
<head>
	<title>Skype log parser</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<div class="out">
		<div class='form'>
			<form action="statistics.php" method="post" enctype="multipart/form-data">
			<div class="main">
				<div class="field">
					<label for "file_log">Лог-файл: </label>
					<input type="file" name="file_log">
				</div>
				 <div class="field">
					<label for "file_black_list">Файл чёрного списка: </label>
					<input type="file" name="file_black_list">
				</div>
				<button type="submit">Получить статистику</button>
			</div>
			</form>	
		</div>
	</div>

</body>
</html>
