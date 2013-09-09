<h1 class="page_title">
	Projects and Publications with <?php echo $partner['Partner']['name']; ?>
</h1>

<?php if (empty($partner['Release'])): ?>
	<p>
		No associated projects or publications could be found. 
	</p>
<?php else: ?>
	<table class="releases">
		<?php foreach ($partner['Release'] as $release): ?>
			<tr>
				<td>
					<?php echo date('F j, Y', strtotime($release['released'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link(
						$release['title'],
						array('controller' => 'releases', 'action' => 'view', 'id' => $release['id'], 'slug' => $release['slug'])
					); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>