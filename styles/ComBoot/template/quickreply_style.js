/* global quickreply */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106

	/**
	 * Require special attention after style modifications:
	 * .post-body.panel-info - unread posts selector
	 */

	// Modify editor variables and add new ones.
	$.extend(quickreply.editor, {
		postSelector: 'div.post-body',
		postTitleSelector: '.panel-heading h3:first',
		totalPostsContainer: '.total-posts-container',
		paginationContainer: 'nav .pagination'
	});

	// Style-specific functions and features of QuickReply.
	quickreply.style = {};

	/**
	 * Sets the correct ID for the quick reply textarea.
	 */
	quickreply.style.setTextareaId = function() {
		$("#message").closest(".form-group").remove();
		$("#qr_message").attr("id", "message");
	};

	/**
	 * Initializes Ajax preview - creates preview container.
	 */
	quickreply.style.initPreview = function() {
		$(quickreply.editor.mainForm).before('<div id="preview" class="post-body panel panel-info" style="display: none; margin-top: 50px;"><div class="panel-heading"><h3></h3></div><div class="panel-body"><div class="content"></div></div></div>');
	};

	/**
	 * Marks the posts within the specified elements read.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.markRead = function(elements) {
		elements.find('.post-body.panel-info').removeClass('panel-info');
	};

	/**
	 * Opens quick reply form if it is collapsed.
	 */
	quickreply.style.showQuickReplyForm = function() {
		var $this = $(quickreply.editor.mainForm).find('.panel-heading span.clickable');
		if ($this.hasClass('panel-collapsed')) {
			$this.parents('.panel-collapsible').find('.panel-body, .panel-footer').show();
			$this.removeClass('panel-collapsed');
			$this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
		}
	};

	/**
	 * Gets the jQuery object for pagination elements.
	 *
	 * @returns {jQuery} For ComBoot we return several elements
	 */
	quickreply.style.getPagination = function() {
		var qe = quickreply.editor;
		return $(qe.totalPostsContainer + ', ' + qe.paginationContainer);
	};

	/**
	 * Applies the new pagination data.
	 * Used in Ajax response handling function
	 * when temporary container with the new data is available.
	 */
	quickreply.style.handlePagination = function() {
		var replyPagination = $('#qr_pagination'), postsCounter = $('#qr_total_posts');
		$(quickreply.editor.paginationContainer).html(replyPagination.html());
		$(quickreply.editor.totalPostsContainer).html(postsCounter.html());
		replyPagination.remove();
		postsCounter.remove();
	};

	/**
	 * Restores the subject of the first post.
	 * Used after inserting new posts if soft scroll is disabled.
	 *
	 * @param {jQuery} elements      jQuery object for posts.
	 * @param {jQuery} tempContainer jQuery object for the temporary container.
	 */
	quickreply.style.restoreFirstSubject = function(elements, tempContainer) {
		if (quickreply.settings.hideSubject && !elements.find('.post-body').length) {
			tempContainer.find(quickreply.editor.postTitleSelector).first().addClass('first').css('display', '').parents(quickreply.editor.postSelector).removeClass('hidden_subject');
		}
	};

	/**
	 * Adds Ajax functionality for the pagination.
	 */
	quickreply.style.bindPagination = function() {
		if (quickreply.settings.ajaxPagination) {
			$('nav .pagination a:not([href="#"])').click(function(event) {
				event.preventDefault();
				//$(quickreply.editor.mainForm).off('submit').attr('action', $(this).attr('href')).submit();
				quickreply.functions.qr_ajax_reload($(this).attr('href'));
			});

			$(quickreply.editor.totalPostsContainer).children('a:not([href="#unread"])').click(function(event) {
				event.preventDefault();
				quickreply.functions.qr_ajax_reload($(this).attr('href'));
			});
		}

		$(quickreply.editor.totalPostsContainer).children('a[href="#unread"]').click(function(event) {
			event.preventDefault();
			var unread_posts = $('.post-body.panel-info');
			quickreply.functions.qr_soft_scroll((unread_posts.length) ? unread_posts.first() : $('#qr_posts'));
		});

		$('.total-posts-container .page-jump-form :button').click(function() {
			var $input = $(this).parent().siblings('input.form-control');
			if (!quickreply.settings.ajaxPagination) {
				pageJump($input);
			} else if (quickreply.plugins.seo) {
				quickreply.functions.qr_seo_page_jump($input);
			} else {
				quickreply.functions.qr_page_jump($input);
			}
			$(quickreply.editor.totalPostsContainer).removeClass('open');
		});

		$('.total-posts-container .page-jump-form input.form-control').on('keypress', function(event) {
			if (event.which === 13 || event.keyCode === 13) {
				event.preventDefault();
				if (!quickreply.settings.ajaxPagination) {
					pageJump($(this));
				} else if (quickreply.plugins.seo) {
					quickreply.functions.qr_seo_page_jump($(this));
				} else {
					quickreply.functions.qr_page_jump($(this));
				}
				$(quickreply.editor.totalPostsContainer).removeClass('open');
			}
		});

		$('.total-posts-container .dropdown-toggle').click(function() {
			var $dropdownContainer = $(this).parent();
			// Wait a little bit to make sure the dropdown has activated
			setTimeout(function() {
				if ($dropdownContainer.hasClass('open')) {
					$dropdownContainer.find('input.form-control').focus();
				}
			}, 100);
		});
	};

	/**
	 * Adds initial Ajax functionality for the pagination.
	 */
	quickreply.style.initPagination = function() {
		$(window).on('load', function() {
			$('.total-posts-container .page-jump-form :button').unbind('click');
			$('.total-posts-container .page-jump-form input.form-control').off('keypress');
			quickreply.style.bindPagination();
		});
	};

	/**
	 * Save message for the full reply form.
	 */
	quickreply.style.setPostReplyHandler = function() {
		$('.row:has(.pagination)').find('.fa-lock, .fa-pencil-square-o').closest('a').click(function(e) {
			e.preventDefault();
			$(quickreply.editor.mainForm).off('submit').find('[name="preview"]').off('click').click();
		});
	};

	/**
	 * Gets the ID of the post for the specified element.
	 *
	 * @param {jQuery} element Inner jQuery element
	 * @returns {string}
	 */
	quickreply.style.getPostId = function(element) {
		return element.parents('.post-body').parent().attr('id').replace('p', '');
	};

	/**
	 * Gets quote buttons for the specified elements.
	 *
	 * @param {jQuery} elements jQuery elements, e.g. posts
	 * @returns {jQuery}
	 */
	quickreply.style.getQuoteButtons = function(elements) {
		return elements.find('.topic-buttons .fa-quote-left').parent('a');
	};

	/**
	 * Removes the subject box from the form.
	 */
	quickreply.style.removeSubjectBox = function() {
		$(quickreply.editor.mainForm).find('input[name="subject"]').closest(".form-group").remove();
	};

	/**
	 * Attaches onclick event to quote buttons in responsive menu.
	 *
	 * @param {jQuery}   elements Container with quote buttons
	 * @param {function} fn       Event handler function
	 */
	quickreply.style.responsiveQuotesOnClick = function(elements, fn) {
		// Do nothing - there is no responsive menu.
	};

	/**
	 * Generates an HTML string for a link from an object with parameters.
	 *
	 * @param {object} parameters Object with HTML attributes (href, id, class, title)
	 *                            and link text
	 * @returns {string}
	 */
	quickreply.style.makeLink = function(parameters) {
		if (typeof parameters !== 'object') {
			return '';
		}
		var link = '<a';
		link += (parameters.href) ? ' href="' + parameters.href + '"' : ' href="#"';
		if (parameters.id) {
			link += ' id="' + parameters.id + '"';
		}
		if (parameters.class) {
			link += ' class="' + parameters.class + '"';
		}
		if (parameters.title) {
			link += ' title="' + parameters.title + '"';
		}
		link += '>' + ((parameters.text) ? parameters.text : '') + '</a>';
		return link;
	};

	/**
	 * Generates an HTML element for a dropdown element.
	 *
	 * @param {Array}  contentRows    Array with content for the rows in a list
	 * @param {int}    pageX          X coordinate of the cursor
	 * @param {int}    pageY          Y coordinate of the cursor
	 * @param {string} [appendClass]  Optional dropdown class
	 * @returns {jQuery}
	 */
	quickreply.style.createDropdown = function(contentRows, pageX, pageY, appendClass) {
		if (!contentRows) {
			return $('');
		}
		var dropdownStyle = 'top: ' + (pageY + 8) + 'px; ', pointerStyle = '';
		if (pageX > 184) {
			dropdownStyle += 'margin-right: 0; left: auto; right: ' + ($('body').width() - pageX - 21) + 'px;';
			pointerStyle = 'left: auto; right: 10px;';
		} else {
			dropdownStyle += 'left: ' + (pageX - 21) + 'px;';
			pointerStyle = 'left: 10px; right: auto;';
		}
		appendClass = (appendClass) ? ' ' + appendClass : '';
		return $('<div class="popover bottom qr_dropdown' + appendClass + '" style="' + dropdownStyle + '"><div class="arrow" style="' + pointerStyle + '"></div><ul class="popover-content dropdown-menu"><li>' + contentRows.join('</li><li>') + '</li></ul></div>').appendTo('body');
	};

	/**
	 * Generates an HTML element for a QuickQuote dropdown element.
	 *
	 * @param {int} pageX X coordinate of the cursor
	 * @param {int} pageY Y coordinate of the cursor
	 * @returns {jQuery}
	 */
	quickreply.style.quickQuoteDropdown = function(pageX, pageY) {
		var listElements = [
			quickreply.style.makeLink({
				href: "#qr_postform",
				text: quickreply.language.INSERT_TEXT
			})
		];

		return quickreply.style.createDropdown(listElements, pageX, pageY, 'qr_quickquote');
	};

	/**
	 * Generates an HTML element for a QuickNick dropdown element.
	 *
	 * @param {int}    pageX          X coordinate of the cursor
	 * @param {int}    pageY          Y coordinate of the cursor
	 * @param {string} viewProfileURL URL to the profile page
	 * @param {jQuery} qrPMLink       jQuery element for a "Send PM" link
	 * @returns {jQuery}
	 */
	quickreply.style.quickNickDropdown = function(pageX, pageY, viewProfileURL, qrPMLink) {
		var listElements = [
			quickreply.style.makeLink({
				href: "#qr_postform",
				class: "qr_quicknick",
				title: quickreply.language.QUICKNICK_TITLE,
				text: quickreply.language.QUICKNICK
			})
		];

		if (quickreply.settings.quickNickPM && qrPMLink.length) {
			listElements.push(
				quickreply.style.makeLink({
					href: qrPMLink.attr('href'),
					class: "qr_reply_in_pm",
					title: quickreply.language.REPLY_IN_PM,
					text: quickreply.language.REPLY_IN_PM
				})
			);
		}

		listElements.push(
			quickreply.style.makeLink({
				href: viewProfileURL,
				class: "qr_profile",
				title: quickreply.language.PROFILE,
				text: quickreply.language.PROFILE
			})
		);

		return quickreply.style.createDropdown(listElements, pageX, pageY);
	};

	// Special handling for events.
	// Re-initialize to-top animation.
	$("#qr_posts").on("qr_loaded", function() {
		$('.to-top').click(function() {
			$('#back-to-top').tooltip('hide');
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});


	// Hide quick reply form after posting a reply.
	$("#qr_postform").on("ajax_submit_success", function() {
		var $this = $(quickreply.editor.mainForm).find('.panel-heading span.clickable');
		if (!$this.hasClass('panel-collapsed')) {
			$this.parents('.panel-collapsible').find('.panel-body, .panel-footer').slideUp();
			$this.addClass('panel-collapsed');
			$this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
		}
	});

})(jQuery, window, document);
