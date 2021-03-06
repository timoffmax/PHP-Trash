<!DOCTYPE html>
<html>
<head>
	<title>ATA Report</title>
	<style type="text/css">
		h1, h2, h3, h4, h5, h6,
		.h1, .h2, .h3, .h4, .h5, .h6 {
		  margin-bottom: 0.5rem;
		  font-family: inherit;
		  font-weight: 500;
		  line-height: 1.1;
		  color: inherit;
		}

		.table {
		  width: 100%;
		  max-width: 100%;
		  margin-bottom: 1rem;
		}

		.table th,
		.table td {
		  padding: 0.75rem;
		  vertical-align: top;
		  border-top: 1px solid #eceeef;
		  text-align: center
		}

		.table thead th {
		  vertical-align: bottom;
		  border-bottom: 2px solid #eceeef;
		}

		.table tbody + tbody {
		  border-top: 2px solid #eceeef;
		}

		.table .table {
		  background-color: #fff;
		}
		.table-striped tbody tr:nth-of-type(odd) {
		  background-color: rgba(0, 0, 0, 0.05);
		}
	</style>
</head>
<body>
	<h1>List of problem ATA</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>№</th>
				<th>Peer</th>
				<th>IP</th>
				<th>Date</th>
			<tr>
		</thead>
<?php 
	foreach ($ata_list as $key => $ata) :
?>
		<tr>
			<td><?php echo ++$key; ?></td>
			<td><?php echo $ata->peer; ?></td>
			<td><?php echo $ata->ip; ?></td>
			<td><?php echo $ata->date; ?></td>
		</tr>
<?php
	endforeach;
 ?>
		</tr>
	</table>
</body>
</html>