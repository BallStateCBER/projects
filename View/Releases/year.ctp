<h1 class="page_title">
	Projects and Publications in <?php echo $year; ?>
</h1>
	
<?php if (empty($releases)): ?>
	<p>
		No projects or publications from that year could be found. 
	</p>
<?php else: ?>
	<table class="releases">
		<?php foreach ($releases as $release): ?>
			<tr>
				<td>
					<?php echo date('F j, Y', strtotime($release['Release']['released'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link(
						$release['Release']['title'],
						array('controller' => 'releases', 'action' => 'view', 'id' => $release['Release']['id'], 'slug' => $release['Release']['slug'])
					); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>