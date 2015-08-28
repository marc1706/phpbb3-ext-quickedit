(function($) {  // Avoid conflicts with other libraries

/* global phpbb, jQuery */

"use strict";

// Holds the standard edit button click event during quickedit
phpbb.editButtonEvent = [];

/**
 * This callback displays the quickedit area in place of the post that is being
 * edited. It will also ajaxify the cancel button.
 */
phpbb.addAjaxCallback('quickedit_post', function(res) {
	var quickeditBoxId = '#quickeditbox';

	if (res.POST_ID && res.POST_ID > 0 && !$(quickeditBoxId).length) {
		var $post = $('#p' + res.POST_ID);

		$post.find('.content').hide();
		$(res.MESSAGE).insertAfter($post.find('.author'));

		// Now we can initialize this variable
		var $quickeditBox = $(quickeditBoxId);

		// Enable code editor for text area
		phpbb.applyCodeEditor($post.find('textarea')[0]);

		var editLink = $('#p' + res.POST_ID +' a.edit-icon');
		var editButtons = $('div[id^="p"]').filter(function() {
			return this.id.match(/^p+(?:([0-9]+))/);
		});

		// Cancel button will show post again
		$quickeditBox.find('input[name="cancel"]').click(function() {
			$('#quickeditbox').remove();
			$post.find('.content').show();

			// Remove cancel event from all other quickedit buttons
			editButtons.each(function() {
				// Only other edit buttons will trigger cancel
				if (this.id === 'p' + res.POST_ID) {
					return true;
				}
				var editButtonId = '#' + this.id;
				var editButton = $(editButtonId + ' a.edit-icon');

				// Remove last click event. This should be the
				// one we added
				editButton.each(function() {
					var eventHandlers = $._data(this, 'events').click;
					eventHandlers.pop();
				});
			});

			// Add edit button click event for quickedit back
			editLink.each(function() {
				var eventHandlers = $._data(this, 'events').click;
				eventHandlers.splice(0, 0, phpbb.editButtonEvent);
				// Remove full editor click event
				eventHandlers.pop();
			});
			phpbb.editButtonEvent = [];
			return false;
		});

		// Edit button will redirect to full editor
		editLink.bind('click', function() {
			var $quickeditBox = $('#quickeditbox');
			if ($quickeditBox.find('input[name="preview"]') !== 'undefined') {
				$quickeditBox.find('input[name="preview"]').click();
			}
			return false;
		});

		// Clicking a different edit button will cancel the initial quickedit
		editButtons.each(function() {
			// Only the other edit buttons will trigger a cancel
			if (this.id === 'p' + res.POST_ID) {
				return true;
			}
			var editButtonId = '#' + this.id;
			var editButton = $(editButtonId + ' a.edit-icon');
			var $quickeditBox = $('#quickeditbox');

			editButton.bind('click', function() {
				$quickeditBox.find('input[name="cancel"]').trigger('click');
			});
		});

		// Remove edit button click event for quickedit
		editLink.each(function() {
			var eventHandlers = $._data(this, 'events').click;
			phpbb.editButtonEvent = eventHandlers.shift();
		});
	}
});

/**
 * Add Quickedit functionality to edit buttons
 */
phpbb.QuickeditAjaxifyEditButtons = function(elements) {
	var editButtons = elements.find('div[id^="p"]').filter(function() {
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

		// Close dropdown in responsive design
		$this.filter(function() {
			return !!$(this).closest('.responsive-menu').length;
		}).click(function() {
			var $container = $(this).parents('.dropdown-container'),
				$trigger = $container.find('.dropdown-trigger:first'),
				data;

			if (!$trigger.length) {
				data = $container.attr('data-dropdown-trigger');
				$trigger = data ? $container.children(data) : $container.children('a:first');
			}
			$trigger.click();
		});
	});
};

$(window).on('load', function() {
	var allowQuickeditDiv = $('div[data-allow-quickedit]');

	if (allowQuickeditDiv !== 'undefined' && allowQuickeditDiv.attr('data-allow-quickedit') === '1')
	{
		phpbb.QuickeditAjaxifyEditButtons($(document));

		// Compatibility with QuickReply Reloaded extension
		$('#qr_posts').on('qr_completed', function(e, elements) {
			phpbb.QuickeditAjaxifyEditButtons(elements);
		});
	}
});


})(jQuery); // Avoid conflicts with other libraries
