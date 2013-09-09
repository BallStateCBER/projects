<div class="paging">
	<?php
		if ($this->Paginator->hasPrev()) {
			echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		}
		echo $this->Paginator->numbers(array('separator' => ''));
		if ($this->Paginator->hasNext()) {
			echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		}
	?>
</div>
