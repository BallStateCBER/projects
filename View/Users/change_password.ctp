<h1 class="page_title">
	Change my password
</h1>
<?php echo $this->Form->create('User'); ?> 
<?php echo $this->Form->input('new_password', array('type' => 'password')); ?>
<?php echo $this->Form->input('confirm_password', array('type' => 'password')); ?>
<?php echo $this->Form->end(__('Change Password')); ?>
