<div class="release">
	<h1>
		<?php echo $this->Html->link($release['Release']['title'], array(
			'controller' => 'releases', 'action' => 'view', 'id' => $release['Release']['id'], 'slug' => $release['Release']['slug']
		)); ?>
	</h1>

	<?php if ($this->Session->read('Auth.User')): ?>
		<span class="controls">
			<?php echo $this->Html->link(
				$this->Html->image('/data_center/img/icons/pencil.png').'Edit',
				array('controller' => 'releases', 'action' => 'edit', $release['Release']['id']),
				array('escape' => false)
			); ?>
			<?php echo $this->Form->postLink(
				$this->Html->image('/data_center/img/icons/cross.png').'Delete',
				array('controller' => 'releases', 'action' => 'delete', $release['Release']['id']),
				array('escape' => false),
				'Are you sure that you want to delete this release?'
			); ?>
			<?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $release['Release']['id']), null, __('Are you sure you want to delete # %s?', $release['Release']['id'])); ?>
		</span>
	<?php endif; ?>

	<table>
		<tbody>
			<tr>
				<td class="description_col">
					<?php echo $release['Release']['description']; ?>

					<?php if (! empty($release['Tag'])):?>
						<p class="tags">
							Tags:
							<?php
								$tag_links = array();
								foreach ($release['Tag'] as $tag) {
									$tag_links[] = $this->Html->link(
										$tag['name'],
										array('controller' => 'tags', 'action' => 'view', 'id' => $tag['id'], 'slug' => $tag['slug'])
									);
								}
								echo implode(', ', $tag_links);
							?>
						</p>
					<?php endif; ?>

					<?php if (! empty($release['Author'])):?>
						<p class="authors">
							<?php echo __n('Author', 'Authors', count($release['Author'])); ?>:
							<?php
								$author_links = array();
								foreach ($release['Author'] as $author) {
									$author_links[] = $this->Html->link(
										$author['name'],
										array(
											'controller' => 'authors',
											'action' => 'view',
											$author['id']
										)
									);
								}
								echo implode(', ', $author_links);
							?>
						</p>
					<?php endif; ?>

					<p class="partner">
						<?php echo $this->Html->link($release['Partner']['name'], array(
							'controller' => 'partners', 'action' => 'view', 'id' => $release['Partner']['id'], 'slug' => $release['Partner']['slug']
						)); ?>
					</p>
				</td>
				<td class="graphics_col <?php echo (count($release['Graphic']) > 1) ? 'graphics_col_double' : 'graphics_col_single'; ?>">
					<p class="date">
						Published <?php echo date('F j, Y', strtotime($release['Release']['released'])); ?>
					</p>

					<?php if (! empty($release['Graphic'])): ?>
						<table>
							<tr>
								<?php foreach ($release['Graphic'] as $k => $graphic): ?>
									<?php if ($k + 1 == count($release['Graphic']) && $k % 2 == 0): ?>
										<td>
											&nbsp;
										</td>
									<?php endif; ?>
									<td>
										<?php
											$img_src = '/img/releases/'.$graphic['dir'].'/'.$this->Graphic->thumbnail($graphic['image']);
											echo $this->Html->link(
												"<div class=\"graphic\"><img src=\"$img_src\" /></div>{$graphic['title']}",
												$graphic['url'],
												array('escape' => false)
											);
										?>
									</td>
									<?php if ($k % 2 == 1 && count($release['Graphic']) > $k + 1): ?>
										</tr>
										<tr>
									<?php endif; ?>
								<?php endforeach; ?>
							</tr>
						</table>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>