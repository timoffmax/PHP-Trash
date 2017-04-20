<div class="row">
	<h2 class="text-primary">Report by Hour (<?=$this->getStartDate().' - '.$this->getEndDate()?>)</h2>
</div>
<?php foreach ($report as $operatorName => $reportByOperator) : ?>
<div class="row report-body">
	<h3><?=$operatorName?></h3>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>
					<h4 class="text-success">Day/Hour</h4>
				</td>
				<?php
				$hours = array();

				for ($i=0; $i < 24; $i++) { 
					if (strlen($i) != 2) {
						$hours[] = '0'.$i;
					} else {
						$hours[] = $i;
					}
					echo "<td><strong>{$hours[$i]}</strong></td>";
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($reportByOperator as $dayNumber => $reportByDay) {
					echo "<tr>";
					echo "<td>{$dayNumber}</td>";
					foreach ($hours as $hour) {
						if (array_key_exists($hour, $reportByDay)) {
							echo '<td>'.$reportByDay[$hour].'</td>';
						} else {
							echo '<td>0</td>';
						}
					}
					echo "</tr>";
				}
			 ?>
		</tbody>
	</table>
</div>
<?php endforeach; ?>
