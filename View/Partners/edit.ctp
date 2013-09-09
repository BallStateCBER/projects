<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>
<?php echo $this->Form->create('Partner');?>
<?php
	echo $this->Form->input('id');
	echo $this->Form->input('name');
?>
<?php echo $this->Form->end(__('Submit'));?>
