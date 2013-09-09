<?php if ($query): ?>
	<?php
		// Taken from http://us2.php.net/manual/en/function.str-ireplace.php
		function highlight($str2, $str1) {
			$kwicLen = strlen($str1);
			$kwicArray = array();
			$pos = 0;
			$count = 0;
			while ($pos !== FALSE) {
				$pos = stripos($str2,$str1,$pos);
				if ($pos !== FALSE) {
					$kwicArray[$count]['kwic'] = substr($str2,$pos,$kwicLen);
					$kwicArray[$count++]['pos']  = $pos;
					$pos++;
				}
			}
			for ($I=count($kwicArray)-1;$I>=0;$I--) {
				$kwic = '<strong>'.$kwicArray[$I]['kwic'].'</strong>';
				$str2 = substr_replace($str2,$kwic,$kwicArray[$I]['pos'],$kwicLen);
			}
			return $str2;
		}
		
		$count = $this->Paginator->counter('{:count}');
	?>
	
	<h1 class="page_title">
		<?php echo $count; ?> Result<?php echo $count == 1 ? '' : 's'; ?> for "<?php echo $query; ?>"
	</h1>
	
	<?php if (empty($releases)): ?>
		<p>
			No results found.
		</p>
	<?php else: ?>
		<ul class="search_results unstyled">
			<?php foreach ($releases as $release): ?>
				<li>
					<span class="date">
						<?php echo date('F j, Y', strtotime($release['Release']['released'])); ?>
					</span>
					<?php echo $this->Html->link(
						//'dsflkj asdlkjfsd alkdfj lsdakj sdflkj asdfjldfkj dflaskj asdflkjsdf alkadfj adfjladfkj dfaslkjadf slkadfj adfjlasdfk jasdfljk asdfljk asdfjl jasdflkj asdflkj asdfjlkasdfj asdfljk asdflkj asdfjl lasdfkj asdfljk asdflkjasdf lasdfkj asdflkj ',
						$release['Release']['title'],
						array(
							'controller' => 'releases', 
							'action' => 'view', 
							'id' => $release['Release']['id'], 
							'slug' => $release['Release']['slug']
						),
						array('class' => 'title')
					); ?>
					<p>
						<?php $description = strip_tags($release['Release']['description']); ?>
						<?php if (stripos($description, $query) === false): ?>
							<?php echo $this->Text->truncate($description); ?>
						<?php else: ?>
							<?php
								$description = highlight($description, $query);
								echo $this->Text->excerpt($description, $query);
							?>
						<?php endif; ?>
					</p>
				</li>
			<?php endforeach; ?>
		</ul>
		
		<?php $this->Paginator->options(array(
			'sort' => false
		)); ?>
		
		<?php if ($this->Paginator->hasPrev()): ?>
			<?php echo $this->Paginator->prev(); ?>
		<?php endif; ?>
		
		<?php echo $this->Paginator->numbers(array()); ?>
		
		<?php if ($this->Paginator->hasNext()): ?>
			<?php echo $this->Paginator->next(); ?>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php if (! empty($tags)): ?>
		<p class="search_results_tags">
			<?php
				$tag_list = array(); 
				foreach ($tags as $tag) {
					$tag_list[] = $this->Html->link(
						ucwords($tag['Tag']['name']), 
						array(
							'controller' => 'tags', 
							'action' => 'view', 
							'id' => $tag['Tag']['id'], 
							'slug' => $tag['Tag']['slug']
						)
					);
				}
			?>
			You can also try browsing projects and publications with
			<?php if (count($tag_list) == 1): ?>
				the tag <?php echo $tag_list[0]; ?>.
			<?php else: ?>
				these tags: <?php echo implode(', ', $tag_list); ?>
			<?php endif; ?>
		</p>
	<?php endif; ?>
	
<?php else: ?>

	<h1 class="page_title">
		Search
	</h1>
	
	<p>
		Please enter a search term to find related projects and publications.
	</p>

<?php endif; ?>