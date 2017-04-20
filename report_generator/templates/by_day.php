<div class="row">
	<h2 class="text-primary">Report by Day (<?=$this->getStartDate().' - '.$this->getEndDate()?>)</h2>
</div>
<div class="row report-body">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>
					<h4 class="text-success">Operator/Day</h4>
				</td>
				<?php
					$days = array();
					for ($i = $this->startDay; $i <= $this->endDay; $i++) { 
						if (strlen($i) != 2) {
							$days[$i] = '0'.$i;
						} else {
							$days[$i] = $i;
						}

						echo "<td>{$days[$i]}</td>";
					}
				?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($report as $operatorName => $reportByOperator) : ?>
			<?php
				echo "<tr>";
				echo "<td>{$operatorName}</td>";
				foreach ($days as $day) {
					if (array_key_exists($day, $reportByOperator)) {
						echo '<td>'.$reportByOperator[$day].'</td>';
					}
					else {
						echo '<td>0</td>';
					}
				}
				echo "</tr>";
			 ?>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
