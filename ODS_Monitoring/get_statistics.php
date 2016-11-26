<?php require_once('db_connect.php') ?>

<ul>

<?php

	#Select description and IP for identity servers
	$query = "SELECT Note,IP FROM servers_status GROUP BY IP ORDER BY Note";
	$names = mysqli_query($connection, $query) or die(mysqli_error($connection));

	#Select data for certain servers
	foreach ($names as $name) {
		$description = $name['Note'];
		$ip = $name['IP'];

		$query = "SELECT Date, Quantity_of_calls, Load_average FROM servers_status WHERE IP = '".$ip."' ORDER BY ID DESC LIMIT 1";
		$status_history = mysqli_query($connection, $query) or die(mysqli_error($connection));

#Construct separate table for each server
echo <<<END
		<li>
			<div class="statistics">
		 	<table>
		 		<caption>
    				$description - $ip
  				</caption>
				<thead>
					<tr>
						<th>Date</th>
						<th>Calls</th>
						<th>Load average</th>
					</tr>
				</thead>

				<tbody>
END;

		foreach ($status_history as $status) { 

						echo "\t<tr>\n";
							echo "\t\t<td>".$status['Date']."</td>\n";
							echo "\t\t<td>".$status['Quantity_of_calls']."</td>\n";
							echo "\t\t<td>".$status['Load_average']."</td>\n";
						echo "\t</tr>\n";

		}

echo <<<END
				</tbody>
			</table>
			</div>
		</li>
END;

	}

?>

</ul>
