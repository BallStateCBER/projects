<?php if ($tag_id): ?>
	<h1 class="page_title">
		Edit Tag
	</h1>
	<div class="tags form">
		<?php echo $this->Form->create('Tag');?>
		<?php echo $this->Form->input('name'); ?>
		<?php echo $this->Form->end(__('Update'));?>
	</div>
<?php else: ?>
	<h1 class="page_title">
		Select a Tag to Edit
	</h1>
	<table class="edit_tags">
		<thead>
			<tr>
				<th class="tags">Tag</th>
				<th class="actions">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($tags as $tag_id => $tag_name): ?>
				<tr>
					<td class="tags">
						<?php echo $this->Html->link(
							ucwords($tag_name), 
							array(
								'controller' => 'tags', 
								'action' => 'edit', 
								$tag_id
							)
						); ?>
					</td>
					<td class="actions">
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tag_id)); ?>
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tag_id), null, __('Are you sure you want to delete this tag?', $tag_id)); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>