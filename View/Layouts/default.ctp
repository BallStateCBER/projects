<?php $this->extend('DataCenter.default'); ?>
<?php $this->assign('sidebar', $this->element('sidebar')); ?>
<?php $this->start('subsite_title'); ?>
	<h1 id="subsite_title" class="max_width_padded">
		<a href="/">
			<?php echo Configure::read('data_center_subsite_title'); ?>
		</a>
	</h1>
<?php $this->end(); ?>
<div id="content">
	<?php echo $this->fetch('content'); ?>
</div>