<?php
// Common form used for adding and editing
?>

<?php
	$this->Html->script('admin', array('inline' => false));

	// Set up validation
	$this->Html->script('jquery.validationEngine', array('inline' => false));
	$this->Html->script('jquery.validationEngine-en', array('inline' => false));
	$this->Html->css('validationEngine.jquery', null, array('inline' => false));
	if ($mode == 'add') {
		$form_id = 'ReleaseAddForm';
	} elseif ($mode == 'edit') {
		$form_id = 'ReleaseEditForm';
	}
	$this->Js->buffer("
		$('#$form_id').validationEngine({
			autoHidePrompt: true,
			'custom_error_messages': {
				'.upload': {'required': {'message': 'You must upload a file'}},
				'.partner': {'required': {'message': ' '}}
			}
		});
	");

	/* $i is the next key to be applied to a new input row.
	 * It begins at zero (or the highest key of data['Graphic'] + 1)
	 * and needs to be provided to jQuery. */
	if (isset($this->request->data['Graphic']) && ! empty($this->request->data['Graphic'])) {
		$i = 1 + max(array_keys($this->request->data['Graphic']));
	} else {
		$i = 0;
	}
	$this->Js->buffer("$('body').data('graphics_iterator', $i);");
?>

<h1 class="page_title">
	<?php echo $title_for_layout; ?>
</h1>
<?php
	echo $this->Form->create('Release', array('type' => 'file'));
	if ($mode == 'edit') {
		echo $this->Form->input('id', array('type' => 'hidden', 'value' => $release_id));
	}
	echo $this->Form->input('title', array('class' => 'validate[required]'));
	echo $this->Form->input('released', array(
		'type' => 'date',
		'dateFormat' => 'MDY',
		'label' => 'Date Published',
		'minYear' => 2001,
		'maxYear' => date('Y')
	));
?>

<?php if (empty($partners)): ?>
	<?php echo $this->Form->input('new_partner', array(
		'type' => 'text',
		'label' => 'Client, Partner, or Sponsor',
		'class' => 'validate[required]'
	)); ?>
<?php else: ?>
	<div id="choose_partner">
		<?php echo $this->Form->input('partner_id', array(
			'options' => $partners,
			'label' => 'Client, Partner, or Sponsor',
			'empty' => true,
			'after' => ' <a href="#" id="add_partner_button">Add new</a>',
			'class' => 'partner validate[funcCall[checkPartner]]'
		)); ?>
	</div>
	<div id="add_partner" style="display: none;">
		<?php echo $this->Form->input('new_partner', array(
			'type' => 'text',
			'label' => 'Client, Partner, or Sponsor',
			'after' => ' <a href="#" id="choose_partner_button">Choose from list</a>',
			'class' => 'partner validate[funcCall[checkPartner]]'
		)); ?>
	</div>
	<?php $this->Js->buffer("
		$('#add_partner_button').click(function (event) {
			event.preventDefault();
			$('#ReleasePartnerId').val('');
			$('#choose_partner').hide();
			$('#add_partner').show();
		});
		$('#choose_partner_button').click(function (event) {
			event.preventDefault();
			$('#ReleaseNewPartner').val('');
			$('#choose_partner').show();
			$('#add_partner').hide();
		});
	"); ?>
<?php endif; ?>

<?php echo $this->Form->input('description', array('class' => 'validate[required]')); ?>

<fieldset class="reports">
	<legend>
		Upload Reports
		<a href="#" id="footnote_upload_reports_handle">
			<img src="/data_center/img/icons/information.png" alt="More info" />
		</a>
		<?php $this->Js->buffer("
			$('#footnote_upload_reports_handle').click(function(event) {
				event.preventDefault();
				$('#footnote_upload_reports').toggle();
			});
		"); ?>
	</legend>
	<?php
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
	?>
	<ul class="footnote" style="display: none;" id="footnote_upload_reports">
		<li>Click on <strong>Select Files</strong> above to upload one or more documents.</li>
		<li>Files must have one of the following extensions: <?php echo $this->Text->toList($report_filetypes, 'or'); ?>.</li>
		<li>Files larger than <?php echo $upload_mb; ?>MB will need to be uploaded via FTP client.</li>
		<li>These files will be uploaded to a reports folder and can be linked to with linked graphics or in a release's description.</li>
	</ul>
	<?php
		$this->Html->script('/uploadify/jquery.uploadify.min.js', array('inline' => false));
		$this->Html->css('uploadify.css', null, array('inline' => false));

		$valid_extensions = array();
		foreach ($report_filetypes as $ext) {
			$valid_extensions[] = '*.'.$ext;
		}

		$this->Js->buffer("
			$(function() {
				$('#upload_reports').uploadify({
					swf: '/uploadify/uploadify.swf',
					uploader: '/releases/upload_reports',
					fileTypeExts: '".implode('; ', $valid_extensions)."',
					formData: {
						timestamp: ".time().",
						token: '".md5('saltyseeberprojects'.time())."',
						overwrite: false
					},
					onUploadStart: function(file) {
						if ($('#overwrite_reports').is(':checked')) {
							$('#upload_reports').uploadify('settings', 'formData', {overwrite: true});
						}
					},
					onUploadSuccess: function(file, data, response) {
						if (data.indexOf('Error') == -1) {
							var classname = 'success';
						} else {
							var classname = 'error';
						}
						insertFlashMessage(data, classname);
						console.log('Upload result: '+data);
					},
					onUploadError: function(file, errorCode, errorMsg, errorString) {
						console.log('Upload error...');
						console.log('file: '+file);
						console.log('errorCode: '+errorCode);
						console.log('errorMsg: '+errorMsg);
						console.log('errorString: '+errorString);
					}
				});
			});
		");
	?>
	<input type="file" name="file_upload" id="upload_reports" />
	<input type="checkbox" name="overwrite" value="1" id="overwrite_reports" />
	<label for="overwrite_reports">
		Overwrite reports with the same filename
	</label>
</fieldset>

<fieldset class="graphics">
	<legend>
		Linked Graphics
		<a href="#" id="footnote_upload_graphics_handle">
			<img src="/data_center/img/icons/information.png" alt="More info" />
		</a>
		<?php $this->Js->buffer("
			$('#footnote_upload_graphics_handle').click(function(event) {
				event.preventDefault();
				$('#footnote_upload_graphics').toggle();
			});
		"); ?>
	</legend>
	<ul class="footnote" style="display: none;" id="footnote_upload_graphics">
		<li>Images must be .jpg, .jpeg, .gif, or .png.</li>
		<li>Thumbnails (max 195&times;195px) will be automatically generated.</li>
		<li>Graphics with lower order-numbers are displayed first.</li>
	</ul>
	<?php $rows_prepopulated = (isset($this->request->data['Graphic']) && ! empty($this->request->data['Graphic'])); ?>
	<table class="graphics">
		<thead <?php if (! $rows_prepopulated): ?>style="display: none;"<?php endif; ?>>
			<th>Remove</th>
			<th>File</th>
			<th>Title</th>
			<th>Link URL</th>
			<th>Order</th>
		</thead>
		<tbody>
			<?php if ($rows_prepopulated): ?>
				<?php foreach ($this->request->data['Graphic'] as $k => $g): ?>
					<tr>
						<?php if ($mode == 'add'): ?>
							<td>
								<a href="#" class="remove_graphic">
									<img src="/data_center/img/icons/cross.png" alt="Remove" />
								</a>
								<?php $this->Js->buffer("
									$('a.remove_graphic').each(function() {
										$(this).click(function(event) {
											event.preventDefault();
											removeGraphic(this);
										});
									});
								"); ?>
							</td>
							<td>
								<?php echo $this->Form->input("Graphic.$k.image", array(
									'type' => 'file',
									'label' => false,
									'class' => 'validate[required] upload'
								)); ?>
							</td>
						<?php elseif ($mode == 'edit'): ?>
							<td>
								<?php echo $this->Form->input("Graphic.$k.remove", array(
									'type' => 'checkbox',
									'label' => false
								)); ?>
								<?php $this->Js->buffer("
									$('a.remove_graphic').each(function() {
										$(this).click(function(event) {
											event.preventDefault();
											removeGraphic(this);
										});
									});
								"); ?>
							</td>
							<td>
								<?php
									$img_url = '/img/releases/';
									$img_url .= $this->request->data['Graphic'][$k]['dir'].'/';
									$img_url .= $this->Graphic->thumbnail($this->request->data['Graphic'][$k]['image']);
								?>
								<img src="<?php echo $img_url; ?>" />
								<?php foreach (array('id', 'dir', 'image') as $field): ?>
									<?php echo $this->Form->input("Graphic.$k.$field", array(
										'value' => $this->request->data['Graphic'][$k][$field],
										'type' => 'hidden'
									)); ?>
								<?php endforeach; ?>
							</td>
						<?php endif; ?>
						<td>
							<?php echo $this->Form->input("Graphic.$k.title", array(
								'label' => false,
								'class' => "validate[condRequired[Graphic{$k}Image]]"
							)); ?>
						</td>
						<td>
							<?php echo $this->Form->input("Graphic.$k.url", array(
								'label' => false,
								'class' => "validate[condRequired[Graphic{$k}Image]]",
								'after' =>  '<a href="#" title="Find report" class="find_report" id="find_report_button_'.$k.'"><img src="/data_center/img/icons/magnifier.png" alt="Find report" /></a>'
							)); ?>
							<?php $this->Js->buffer("
								$('#find_report_button_$k').click(function(event) {
									event.preventDefault();
									toggleReportFinder(this, $k);
								});
							"); ?>
						</td>
						<td>
							<?php echo $this->Form->input("Graphic.$k.weight", array(
								'label' => false,
								'type' => 'select',
								'options' => range(1, count($this->request->data['Graphic']))
							)); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr class="add_graphic">
				<th colspan="4">
					<a href="#" class="add_graphic">
						<img src="/data_center/img/icons/plus.png" /> Add a linked graphic
					</a>
					<?php $this->Js->buffer("
						$('a.add_graphic').click(function(event) {
							event.preventDefault();
							addGraphic('ReleaseAddForm');
						});
					"); ?>
				</th>
			</tr>
			<tr class="dummy_row">
				<td>
					<a href="#" class="remove_graphic">
						<img src="/data_center/img/icons/cross.png" alt="Remove" />
					</a>
				</td>
				<td>
					<?php echo $this->Form->input("Graphic.{i}.image", array(
						'type' => 'file',
						'label' => false,
						'disabled' => true,
						'required' => true,
						'class' => 'validate[required,funcCall[checkExtension]] upload'
					)); ?>
				</td>
				<td>
					<?php echo $this->Form->input("Graphic.{i}.title", array(
						'label' => false,
						'disabled' => true,
						'required' => true,
						'class' => 'validate[condRequired[Graphic{i}Image]]'
					)); ?>
				</td>
				<td>
					<?php echo $this->Form->input("Graphic.{i}.url", array(
						'label' => false,
						'disabled' => true,
						'required' => true,
						'class' => 'validate[condRequired[Graphic{i}Image]',
						'after' => ' <a href="#" title="Find report" class="find_report"><img src="/data_center/img/icons/magnifier.png" alt="Find report" /></a>'
					)); ?>
				</td>
				<td>
					<?php
						if (isset($this->request->data['Graphic'])) {
							$options = range(1, count($this->request->data['Graphic']) + 1);
						} else {
							$options = array(1);
						}
						echo $this->Form->input("Graphic.{i}.weight", array(
							'label' => false,
							'disabled' => true,
							'type' => 'select',
							'options' => $options
						));
					?>
				</td>
			</tr>
		</tfoot>
	</table>
</fieldset>

<?php
	$this->Html->css(
		'/jquery_ui/css/smoothness/jquery-ui-1.10.4.custom.min.css',
		array(
			'inline' => false
		)
	);
	$this->Html->script(
		'/jquery_ui/js/jquery-ui-1.10.4.custom.min.js',
		array(
			'inline' => false
		)
	);
	echo $this->element(
		'tags/editor',
		array(
			'available_tags' => $available_tags,
			'selected_tags' => isset($this->request->data['Tag']) ? $this->request->data['Tag'] : array(),
			'hide_label' => true,
			'allow_custom' => true
		),
		array(
			'plugin' => 'DataCenter'
		)
	);
	echo $this->Form->end('Submit');
	echo $this->element(
		'DataCenter.rich_text_editor_init',
		array(
			'customConfig' => Configure::read('ckeditor_custom_config')
		)
	);
?>