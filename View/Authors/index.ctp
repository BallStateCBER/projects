<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>

<p>
	<?php echo $this->Html->link(
		'Add a New Author',
		array(
			'action' => 'add'
		)
	); ?>
</p>

<div id="authors_index">
	<?php if (empty($authors)): ?>

		<p>
			No authors found.
		</p>

	<?php else: ?>

		<?php echo $this->element('pagination'); ?>

		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>
						<?php echo $this->Paginator->sort('name', 'Author'); ?>
					</th>
					<th class="actions">
						<?php echo __('Actions'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($authors as $author): ?>
					<tr>
						<td>
							<?php echo h($author['Author']['name']); ?>
						</td>
						<td class="actions">
							<?php echo $this->Html->link(__('View'), array('action' => 'view', $author['Author']['id'])); ?>
							<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $author['Author']['id'])); ?>
							<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $author['Author']['id']), null, __('Are you sure you want to delete this author?', $author['Author']['id'])); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php echo $this->element('pagination'); ?>

	<?php endif; ?>
</div>
