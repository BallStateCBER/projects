<div class="graphics form">
<?php echo $this->Form->create('Graphic');?>
	<fieldset>
		<legend><?php echo __('Add Graphic'); ?></legend>
	<?php
		echo $this->Form->input('release_id');
		echo $this->Form->input('title');
		echo $this->Form->input('url');
		echo $this->Form->input('image');
		echo $this->Form->input('weight');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Graphics'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Releases'), array('controller' => 'releases', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Release'), array('controller' => 'releases', 'action' => 'add')); ?> </li>
	</ul>
</div>
