<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>

<div id="author_releases">
	<?php if (empty($author['Release'])): ?>
		<p>
			This author is not currently associated with any releases.
		</p>
	<?php else: ?>
		<p>
			The following releases were created or contributed to by <?php echo $author_name; ?>:
		</p>
		<ul>
			<?php foreach ($author['Release'] as $release): ?>
				<li>
					<?php echo $this->Html->link(
						$release['title'],
						array(
							'controller' => 'releases',
							'action' => 'view',
							'id' => $release['id'],
							'slug' => $release['slug']
						)
					); ?>
					<br />
					<span class="date">
						<?php
							$timestamp = strtotime($release['released']);
							echo date('F j, Y', $timestamp)
						?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
