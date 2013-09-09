<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>
<table class="partners">
	<?php foreach ($partners as $partner): ?>
		<tr>
			<td><?php echo h($partner['Partner']['name']); ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), array(
					'controller' => 'partners', 
					'action' => 'edit', 
					$partner['Partner']['id']
				)); ?>
				<?php
					$prompt = 'Are you sure you want to delete this?';
					if (! empty($partner['Release'])) {
						$count = count($partner['Release']);
						$prompt .= " $count release".($count > 1 ? 's' : '').' will be affected.';
					}
					echo $this->Form->postLink(__('Delete'), 
						array(
							'controller' => 'partners', 
							'action' => 'delete', 
							$partner['Partner']['id']
						), 
						null, 
						$prompt
					);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>