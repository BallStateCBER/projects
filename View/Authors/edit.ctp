<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>

<p>
	<?php echo $this->Html->link(
		'Back to Authors',
		array(
			'action' => 'index'
		)
	); ?>
</p>

<div class="authors form">
	<?php echo $this->Form->create('Author'); ?>
	<?php echo $this->Form->input('id'); ?>
	<?php echo $this->Form->input('name'); ?>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>