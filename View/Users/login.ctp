<h1 class="page_title">
	Log in
</h1>
<?php echo $this->Form->create('User'); ?> 
<?php echo $this->Form->input('email'); ?>
<?php echo $this->Form->input('password'); ?>
<?php echo $this->Form->input('auto_login', array(
	'type' => 'checkbox', 
	'label' => array('text' => ' Log me in automatically', 'style' => 'display: inline;'),
	'checked' => true
)); ?>
<?php echo $this->Form->end(__('Login')); ?>
