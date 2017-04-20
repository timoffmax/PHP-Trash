<div class="row">
	<h2 class="text-primary">Report by Minute (<?=$this->getStartDate().' - '.$this->getEndDate()?>)</h2>
</div>
<div class="row report-body">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>
					<h4 class="text-success">Date/Operator</h4>
				</td>
				<?php $operators = array(); ?>
				<?php foreach ($report as $operatorName => $reportByOperator) : ?>
					<td><?=$operatorName?></td>
					<?php 
						$operators[$operatorName] = $operatorName; 
						foreach ($reportByOperator as $date => $calls) {
							if (array_key_exists($date, $reportByOperator)) {
								$dates[] = $date;
							}
						}
					?>
				<?php endforeach; ?>
				<?php asort($dates); ?>
			</tr>
		</thead>
		<tbody>	
		<?php $already_rendered = array(); ?>
				<?php foreach ($dates as $date): ?>
					<tr>					
						<td><?=$date?></td>
						<?php foreach ($operators as $operator): ?>						
							<?php if (array_key_exists($date, $report[$operator])): ?>
								<td><?=$report[$operator][$date]?></td>
							<?php else: ?>
								<td>0</td>													
							<?php endif ?>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>	

		</tbody>
	</table>
</div>
