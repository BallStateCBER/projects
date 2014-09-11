<?php
	$this->extend('DataCenter.default');
	$this->assign('sidebar', $this->element('sidebar'));
?>

<?php $this->start('subsite_title'); ?>
	<h1 id="subsite_title" class="max_width_padded">
		<a href="/">
			<?php echo Configure::read('data_center_subsite_title'); ?>
		</a>
	</h1>
<?php $this->end(); ?>

<?php echo $this->element('flash_messages', array(), array('plugin' => 'DataCenter')); ?>
<div id="content">
	<?php echo $this->fetch('content'); ?>
</div>