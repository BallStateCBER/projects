/* Scripts only used on pages accessed by admins */


function checkExtension(field, rules, i, options) { 
	var filename = field.val();
	if (filename == '') {
		// Error message already set by the 'required' rule
		return;
	}
	var valid_extensions = ['jpg', 'jpeg', 'gif', 'png'];
	var dot_pos = filename.lastIndexOf('.');
	if (dot_pos == -1) {
		return 'A valid file is required.';
	} else {
		var extension = filename.substring(dot_pos + 1).toLowerCase();
	}
	var valid = false;
	for (var i = 0; i < valid_extensions.length; i++) {
		if (valid_extensions.indexOf(extension) != -1) {
			valid = true;
			break;
		}
	}
	
	if (valid) {
		console.log('.'+extension+' is one of the allowed image extensions ('+valid_extensions.join(', ')+')');
	} else {
		console.log('.'+extension+' is not one of the allowed image extensions ('+valid_extensions.join(', ')+')');
		return '.'+extension+' is not one of the allowed image extensions ('+valid_extensions.join(', ')+')';
	}
}

/* Validate the partners field(s)
 * Used on 'add/edit a release' form */ 
function checkPartner(field, rules, i, options) {
	if ($('#ReleasePartnerId').val() == '' && $('#ReleaseNewPartner').val() == '') {
		return 'You must select or enter a client, partner, or sponsor';
	}
}

/* Create another row of input fields under 'linked graphics'
 * Used on 'add a release' form */
function addGraphic(form_id) {
	// Get and advance the key
	var i = $('body').data('graphics_iterator');
	$('body').data('graphics_iterator', i + 1);
	
	// Get the row to be copied
	var dummy_row = $('table.graphics tfoot .dummy_row').clone().removeClass('dummy_row');
	
	// Apply a unique key to each row
	dummy_row.find('input, select').each(function () {
		$(this).attr('id', this.id.replace('{i}', i));
		$(this).attr('name', this.name.replace('{i}', i));
		$(this).attr('class', this.className.replace('{i}', i));
		$(this).removeAttr('disabled');
	});
	
	// Set up the remove button
	dummy_row.find('a.remove_graphic').each(function () {
		$(this).click(function(event) {
			event.preventDefault();
			removeGraphic(this);
		});
	});
	
	// Set up the 'find report' button
	dummy_row.find('a.find_report').first().click(function(event) {
		event.preventDefault();
		toggleReportFinder(this, i);
	});
	
	// Add the now-unique row 
	$('table.graphics tbody').first().append(dummy_row);
	
	// Reset 'order' options
	updateOrderSelectors();
	
	// Restart the validation engine so that this row is included
	$('#'+form_id).validationEngine('attach');
	
	// Show the table head
	var thead = $('table.graphics thead').first();
	if (! thead.is(':visible')) {
		thead.show();
	}
}

// Sets the number of options in the 'order' selectors of the release form
function updateOrderSelectors() {
	var row_count = $('table.graphics tbody tr').length;
	$('table.graphics select').each(function () {
		var select = $(this);
		var selected = select.val();
		select.empty();
		for (var n = 1; n <= row_count; n++) {
			var option = $('<option value=""></option>');
			option.html(n);
			option.val(n - 1);
			if (selected == n - 1) {
				option.attr('selected', 'selected');
			}
			select.append(option);
		}
	});
}

// Called when a 'find report' button is clicked
// link: the link clicked
// i: the unique key for the corresponding 'linked graphics' row
function toggleReportFinder(link, i) {
	var existing_selection_box = $('#report_choices_'+i);
	
	// Open
	if (existing_selection_box.length == 0) {
		var cell = $(link).parents('td').first();
		loadReportFinder(cell, i);

	// Close
	} else {
		existing_selection_box.parents('tr').first().remove();
	}
}

// cell: the table cell that contains the input field to be populated
// i: the unique key of the row in the 'add/edit linked graphics' box 
function loadReportFinder(cell, i) {
	$.ajax({
		url: '/releases/list_reports/'+i,
		cache: false
	}).done(function(html) {
		setupReportFinder(html, cell, i);
	});
}

// html: the results of requesting /releases/list_reports/$i
// cell: the table cell that contains the input field to be populated
// i: the unique key of the row in the 'add/edit linked graphics' box
function setupReportFinder(html, cell, i) {
	var row = cell.parents('tr').first();
	var new_row = $('<tr><td colspan="4" class="report_choices"><div id="report_choices_'+i+'">'+html+'</div></td></tr>');
	new_row.insertAfter(row);
	new_row.find('a.report').click(function(event) {
		event.preventDefault();
		var report_filename = $(this).html().trim();
		cell.find('input').val('/reports/'+report_filename);
		new_row.remove();
	});
	new_row.find('a.close').click(function(event) {
		event.preventDefault();
		new_row.remove();
	});
	new_row.find('a.refresh').click(function(event) {
		event.preventDefault();
		var loading_img = $(this).find('img');
		loading_img.show();
		$.ajax({
			url: '/releases/list_reports/'+i,
			cache: false
		}).done(function(html) {
			new_row.remove();
			setupReportFinder(html, cell, i);
		}).fail(function() {
			alert('Sorry, there was a problem reloading the list of reports.');
			loading_img.hide();
		});
	});
	new_row.find('.sorting_options a').click(function(event) {
		event.preventDefault();
		var link = $(this); 
		link.addClass('selected');
		if (link.hasClass('newest')) {
			new_row.find('.sorting_options a.alphabetic').removeClass('selected');
			new_row.find('ul.newest').show();
			new_row.find('ul.alphabetic').hide();
		} else if (link.hasClass('alphabetic')) {
			new_row.find('.sorting_options a.newest').removeClass('selected');
			new_row.find('ul.newest').hide();
			new_row.find('ul.alphabetic').show();
		}
	});
}

function removeGraphic(button) {
	$(button).closest('tr').remove();
	updateOrderSelectors();
	
	// Hide table head if table body is empty
	// Show the table head
	var rows = $('table.graphics tbody').first().children().length;
	if (rows == 0) {
		$('table.graphics thead').first().hide();
	}
}

var releaseForm = {
	init: function () {
		$('#add_author_toggler').click(function (event) {
			event.preventDefault();
			$('#new_author').slideToggle();
		});
		$('#authors_container button').click(function (event) {
			event.preventDefault();
			var container = $(this).parent();
			container.slideUp(300, function () {
				container.remove();
			});
		});
		$('#add_author_button').click(function (event) {
			event.preventDefault();
			releaseForm.addAuthor();
		});
		$('#cancel_add_author_button').click(function (event) {
			event.preventDefault();
			$('#new_author').slideUp(300, function () {
				$(this).find('input').val('');
			});
		});
		$('#ReleaseAuthor').change(function (event) {
			releaseForm.selectAuthor();
		});
		$('#ReleaseForm').submit(function (event) {
			if (releaseForm.hasUnaddedAuthor()) {
				event.preventDefault();
				var author_name = $('#new_author input').val();
				alert('Please click "add" to add '+author_name+' to this release.');
			}
		});
		$('#ReleaseForm').validationEngine({
			autoHidePrompt: true,
			'custom_error_messages': {
				'.upload': {'required': {'message': 'You must upload a file'}},
				'.partner': {'required': {'message': ' '}}
			}
		});
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
		$('#footnote_upload_reports_handle').click(function(event) {
			event.preventDefault();
			$('#footnote_upload_reports').toggle();
		});
		$('#footnote_upload_graphics_handle').click(function(event) {
			event.preventDefault();
			$('#footnote_upload_graphics').toggle();
		});
		$('a.remove_graphic').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				removeGraphic(this);
			});
		});
		$('a.add_graphic').click(function(event) {
			event.preventDefault();
			addGraphic('ReleaseAddForm');
		});
	},
	hasUnaddedAuthor: function () {
		var input = $('#new_author input');
		return input.is(':visible') && input.val() != '';
	},
	addAuthor: function () {
		var name = $('#new_author input').val().replace('"', '\'');
		if (name == '') {
			return;
		}
		var li = $(
			'<li>'+
				name+
				'<input type="hidden" name="data[new_authors][]" value="'+name+'" />'+
				'<button>X</button>'+
			'</li>'
		);
		li.hide();
		li.children('button').click(function (event) {
			event.preventDefault();
			var container = $(this).parent();
			container.slideUp(300, function () {
				container.remove();
			});
		});
		$('#authors_container').append(li);
		li.slideDown();
		$('#new_author').slideUp(300, function () {
			$(this).find('input').val('');
		});
	},
	selectAuthor: function () {
		var select = $('#ReleaseAuthor');
		var author_id = select.val();
		selected = select.find('option:selected');
		selected.removeAttr('selected');
		
		if (author_id == '') {
			return;
		}

		// Do nothing if author is already selected
		if ($('#authors_container input[value='+author_id+']').length > 0) {
			return;
		}

		var author_name = selected.text();
		var li = $(
			'<li>'+
				author_name+
				'<input type="hidden" name="data[Author][Author][]" value="'+author_id+'" />'+
				'<button>X</button>'+
			'</li>'
		);
		li.hide();
		li.children('button').click(function (event) {
			event.preventDefault();
			var container = $(this).parent();
			container.slideUp(300, function () {
				container.remove();
			});
		});
		$('#authors_container').append(li);
		li.slideDown();
	},
	setupUploadify: function (params) {
		$('#upload_reports').uploadifive({
			uploadScript: '/releases/upload_reports',
			fileSizeLimit: params.fileSizeLimit,
			fileTypeExts: params.valid_extensions,
			formData: {
				timestamp: params.time,
				token: params.token,
				overwrite: false
			},
			onUploadFile: function(file) {
				$('#upload_reports').uploadify('settings', 'formData', {
					overwrite: $('#overwrite_reports').is(':checked')
				});
			},
			onUploadComplete: function(file, data, response) {
				if (data.indexOf('Error') == -1) {
					var classname = 'success';
				} else {
					var classname = 'error';
				}
				insertFlashMessage(data, classname);
			},
			onError: function(file, errorCode, errorMsg, errorString) {
				alert('There was an error uploading that file. Details are available in the browser console.');
				console.log('Upload error...');
				console.log('file: '+file);
				console.log('errorCode: '+errorCode);
				console.log('errorMsg: '+errorMsg);
				console.log('errorString: '+errorString);
			}
		});
	}
};