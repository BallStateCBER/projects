<a href="#" class="close">Cancel</a>
<a href="#" class="refresh"><img src="/data_center/img/loading_small.gif" style="display: none;" /> Refresh</a>

<?php if (empty($files_newest)): ?>
	No reports have been uploaded.
<?php else: ?>
	<strong>Select a report to link this graphic to</strong>
	<span class="sorting_options">
		Sort: 
		<a href="#" class="newest selected">Newest</a>
		<a href="#" class="alphabetic">Alphabetic</a>
	</span>
	<ul class="unstyled newest">
		<?php foreach ($files_newest as $timestamp => $info): ?>
			<li>
				<a href="#" class="report">
					<?php echo $info['filename']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<ul class="unstyled alphabetic" style="display: none;">
		<?php foreach ($files_alphabetic as $filename => $info): ?>
			<li>
				<a href="#" class="report">
					<?php echo $info['filename']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>