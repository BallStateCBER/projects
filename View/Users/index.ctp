<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>

<p>
	<?php echo $this->Html->link(
		'Add a New User',
		array(
			'action' => 'add'
		)
	); ?>
</p>

<div class="users_index">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>
					<?php echo $this->Paginator->sort('name'); ?>
				</th>
				<th>
					<?php echo $this->Paginator->sort('email'); ?>
				</th>
				<th class="actions">
					<?php echo __('Actions'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
				<tr>
					<td>
						<?php echo h($user['User']['name']); ?>
					</td>
					<td>
						<a href="mailto:<?php echo $user['User']['email']; ?>">
							<?php echo $user['User']['email']; ?>
						</a>
					</td>
					<td class="actions">
						<?php echo $this->Html->link(
							'Edit',
							array(
								'action' => 'edit',
								$user['User']['id']
							)
						); ?>
						<?php echo $this->Form->postLink(
							'Delete',
							array(
								'action' => 'delete',
								$user['User']['id']
							),
							null,
							__('Are you sure you want to delete %s\'s account?', $user['User']['name'])
						); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="paging">
		<?php
			if ($this->Paginator->hasPrev()) {
				echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
			}
			echo $this->Paginator->numbers(array('separator' => ''));
			if ($this->Paginator->hasNext()) {
				echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
			}
		?>
	</div>
</div>