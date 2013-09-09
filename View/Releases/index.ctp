<div class="paging">
	<?php if ($this->Paginator->hasPrev()) echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => '')); ?>
	<?php if ($this->Paginator->hasNext()) echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')); ?>
</div>

<div class="releases">
	<?php foreach ($releases as $release): ?>
		<?php echo $this->element('release', compact('release')); ?>
	<?php endforeach; ?>
</div>

<div class="paging">
	<?php if ($this->Paginator->hasPrev()) echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')); ?>
	<?php echo $this->Paginator->numbers(array('separator' => '')); ?>
	<?php if ($this->Paginator->hasNext()) echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')); ?>
</div>

