<h2>
	Clients, Partners, and Sponsors
</h2>
<ul class="partners">
	<?php foreach ($sidebar_vars['partners'] as $partner): ?>
		<li>
			<?php echo $this->Html->link($partner['Partner']['name'], array(
				'controller' => 'partners', 
				'action' => 'view', 
				'id' => $partner['Partner']['id'], 
				'slug' => $partner['Partner']['slug'] 
			)); ?>
		</li>
	<?php endforeach; ?>
</ul>

<h2>
	Topics
</h2>
<ul class="tags unstyled">
	<?php foreach ($sidebar_vars['tags'] as $tag): ?>
		<li>
			<?php echo $this->Html->link(ucwords($tag['name']), array(
				'controller' => 'tags', 
				'action' => 'view', 
				'id' => $tag['id'], 
				'slug' => $tag['slug'] 
			)); ?>
		</li>
	<?php endforeach; ?>
</ul>

<h2>
	Publishing Date
</h2>
<ul class="unstyled">
	<?php foreach ($sidebar_vars['years'] as $year): ?>
		<li>
			<?php echo $this->Html->link($year, array(
				'controller' => 'releases', 
				'action' => 'year', 
				'year' => $year 
			)); ?>
		</li>
	<?php endforeach; ?>
</ul>

<h2>
	Search
</h2>
<?php echo $this->Form->create(
	'Release', 
	array(
		'method' => 'get', 
		'url' => array('controller' => 'releases', 'action' => 'search')
	)
); ?>
<?php echo $this->Form->input('q', array('label' => false)); ?>
<?php echo $this->Form->end('Search'); ?>

<?php if ($this->Session->read('Auth.User')): ?>
	<h2>
		Administration
	</h2>
	<ul class="unstyled">
		<li><?php echo $this->Html->link('Add Release', array('controller' => 'releases', 'action' => 'add')); ?></li>
		<li><?php echo $this->Html->link('Add User', array('controller' => 'users', 'action' => 'add')); ?></li>
		<li><?php echo $this->Html->link('Edit Clients / Partners / Sponsors', array('controller' => 'partners', 'action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('Edit Tags', array('controller' => 'tags', 'action' => 'edit')); ?></li>
		<li><?php echo $this->Html->link('Change my password', array('controller' => 'users', 'action' => 'change_password')); ?></li>
		<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
	</ul>
<?php else: ?>
	<?php echo $this->Html->link(
		'Admin login', 
		array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => false), 
		array('id' => 'login_link')
	); ?>
<?php endif; ?>