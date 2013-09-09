<div class="graphics view">
<h2><?php  echo __('Graphic');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Release'); ?></dt>
		<dd>
			<?php echo $this->Html->link($graphic['Release']['title'], array('controller' => 'releases', 'action' => 'view', $graphic['Release']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['url']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['image']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Weight'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['weight']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($graphic['Graphic']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Graphic'), array('action' => 'edit', $graphic['Graphic']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Graphic'), array('action' => 'delete', $graphic['Graphic']['id']), null, __('Are you sure you want to delete # %s?', $graphic['Graphic']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Graphics'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Graphic'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Releases'), array('controller' => 'releases', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Release'), array('controller' => 'releases', 'action' => 'add')); ?> </li>
	</ul>
</div>
