<div class="partners form">
<?php echo $this->Form->create('Partner');?>
	<fieldset>
		<legend><?php echo __('Add Partner'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Partners'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Releases'), array('controller' => 'releases', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Release'), array('controller' => 'releases', 'action' => 'add')); ?> </li>
	</ul>
</div>
