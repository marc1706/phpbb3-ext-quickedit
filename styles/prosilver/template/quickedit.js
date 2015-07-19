(function($) {  // Avoid conflicts with other libraries

"use strict";

// Holds the standard edit button click event during quickedit
phpbb.edit_button_event = [];

/**
 * This callback displays the quickedit area in place of the post that is being
 * edited. It will also ajaxify the cancel button.
 */
phpbb.addAjaxCallback('quickedit_post', function(res) {
	var $quickeditBox = $('#quickeditbox');

	if (res.POST_ID !== 'undefined' && res.POST_ID > 0 && !$quickeditBox.length) {
		var $post = $('#p' + res.POST_ID);

		$post.find('.content').hide();
		$(res.MESSAGE).insertAfter($post.find('.author'));

		// Enable code editor for text area
		phpbb.applyCodeEditor($post.find('textarea')[0]);

		var edit_link = $('#p' + res.POST_ID +' a.edit-icon');
		var edit_buttons = $('div[id^="p"]').filter(function() {
			return this.id.match(/^p+(?:([0-9]+))/);
		});

		// Cancel button will show post again
		$quickeditBox.find('input[name="cancel"]').click(function () {
			$('#quickeditbox').remove();
			$post.find('.content').show();

			// Remove cancel event from all other quickedit buttons
			edit_buttons.each(function() {
				// Only other edit buttons will trigger cancel
				if (this.id === 'p' + res.POST_ID) {
					return true;
				}
				var edit_button_id = '#' + this.id;
				var edit_button = $(edit_button_id + ' a.edit-icon');

				// Remove last click event. This should be the
				// one we added
				edit_button.each(function() {
					var event_handlers = $._data(this, 'events').click;
					event_handlers.pop();
				});
			});

			// Add edit button click event for quickedit back
			edit_link.each(function() {
				var event_handlers = $._data(this, 'events').click;
				event_handlers.splice(0, 0, phpbb.edit_button_event);
				// Remove full editor click event
				event_handlers.pop();
			});
			phpbb.edit_button_event = [];
			return false;
		});

		// Edit button will redirect to full editor
		edit_link.bind('click', function () {
			var $quickeditBox = $('#quickeditbox');
			if ($quickeditBox.find('input[name="preview"]') !== 'undefined') {
				$quickeditBox.find('input[name="preview"]').click();
			}
			return false;
		});

		// Clicking a different edit button will cancel the initial quickedit
		edit_buttons.each(function() {
			// Only the other edit buttons will trigger a cancel
			if (this.id === 'p' + res.POST_ID) {
				return true;
			}
			var edit_button_id = '#' + this.id;
			var edit_button = $(edit_button_id + ' a.edit-icon');
			var $quickeditBox = $('#quickeditbox');

			edit_button.bind('click', function() {
				$quickeditBox.find('input[name="cancel"]').trigger('click');
			});
		});

		// Remove edit button click event for quickedit
		edit_link.each(function() {
			var event_handlers = $._data(this, 'events').click;
			phpbb.edit_button_event = event_handlers.shift();
		});
	}
});

/**
 * Add Quickedit functionality to edit buttons
 */
phpbb.QuickeditAjaxifyEditButtons = function() {
	var editButtons = $('div[id^="p"]').filter(function() {
		return this.id.match(/^p+(?:([0-9]+))/);
	});

	editButtons.each(function() {
		var $this = $('#' + this.id + ' a.edit-icon'),
			fn;

		fn = 'quickedit_post';
		phpbb.ajaxify({
			selector: $this,
			refresh: false,
			callback: fn
		});
	});
};

$(document).ready(function() {
	var allowQuickeditDiv = $('div[data-allow-quickedit]');

	if (allowQuickeditDiv !== 'undefined' && allowQuickeditDiv.attr('data-allow-quickedit') === '1')
	{
		phpbb.QuickeditAjaxifyEditButtons();
	}
});


})(jQuery); // Avoid conflicts with other libraries
