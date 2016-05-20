/* global quickreply */
/**
 * This file is made for prosilver-based styles.
 * Other styles may require some changes, copy this file
 * to the directory of your style and modify it accordingly.
 *
 * Note: you can also modify quickreply.editor object for your style.
 */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106

	// Style-specific functions and features of QuickReply.
	quickreply.style = {};

	/**
	 * Sets the correct ID for the quick reply textarea.
	 */
	quickreply.style.setTextareaId = function() {
		$("#message-box").find("textarea").first().attr('id', 'message');
	};

	/**
	 * Initializes Ajax preview - creates preview container.
	 */
	quickreply.style.initPreview = function() {
		$(quickreply.editor.mainForm).before('<div id="preview" class="post bg2" style="display: none; margin-top: 50px;"><div class="inner"><div class="postbody"><h3></h3><div class="content"></div></div></div></div>');
	};

	/**
	 * Marks the posts within the specified elements read.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.markRead = function(elements) {
		var unread_posts = elements.find('.unreadpost');
		if (unread_posts.length) {
			var read_post_img = $('#qr_read_post_img').html();
			unread_posts.removeClass('unreadpost').find('.author span.imageset:first').replaceWith(read_post_img);
		}
	};

	/**
	 * Opens quick reply form if it is collapsed.
	 */
	quickreply.style.showQuickReplyForm = function() {
		/*****************************/
		/* Quick Reply Toggle Plugin */
		/*****************************/
		if ($("#reprap:not(.ouvert)").length) {
			$('#reprap input[type=submit]').click();
		}
	};

	/**
	 * Gets the jQuery object for pagination elements.
	 *
	 * @returns {jQuery}
	 */
	quickreply.style.getPagination = function() {
		return $('.action-bar .pagination');
	};

	/**
	 * Applies the new pagination data.
	 * Used in Ajax response handling function
	 * when temporary container with the new data is available.
	 */
	quickreply.style.handlePagination = function() {
		var reply_pagination = $('#qr_pagination');
		quickreply.style.getPagination().html(reply_pagination.html());
		reply_pagination.remove();
	};

	/**
	 * Restores the subject of the first post.
	 * Used after inserting new posts if soft scroll is disabled.
	 *
	 * @param {jQuery} elements      jQuery object for posts.
	 * @param {jQuery} tempContainer jQuery object for the temporary container.
	 */
	quickreply.style.restoreFirstSubject = function(elements, tempContainer) {
		if (quickreply.settings.hideSubject && !elements.find('.post').length) {
			tempContainer.find('.post .postbody div h3').first().addClass('first').css('display', '');
		}
	};

	/**
	 * Adds Ajax functionality for the pagination.
	 */
	quickreply.style.bindPagination = function() {
		if (quickreply.settings.ajaxPagination) {
			$('.action-bar .pagination a:not(.dropdown-trigger, .mark)').click(function(event) {
				event.preventDefault();
				//$(quickreply.editor.mainForm).off('submit').attr('action', $(this).attr('href')).submit();
				quickreply.functions.qr_ajax_reload($(this).attr('href'));
			});

			$('.action-bar .pagination a.mark:not([href="#unread"])').click(function(event) {
				event.preventDefault();
				quickreply.functions.qr_ajax_reload($(this).attr('href'));
			});
		}

		$('.action-bar .pagination a.mark[href="#unread"]').click(function(event) {
			event.preventDefault();
			var unread_posts = $('.unreadpost');
			quickreply.functions.qr_soft_scroll((unread_posts.length) ? unread_posts.first() : $('#qr_posts'));
		});

		$('.action-bar .pagination .page-jump-form :button').click(function() {
			var $input = $(this).siblings('input.inputbox');
			if (!quickreply.settings.ajaxPagination) {
				pageJump($input);
			} else if (quickreply.plugins.seo) {
				quickreply.functions.qr_seo_page_jump($input);
			} else {
				quickreply.functions.qr_page_jump($input);
			}
		});

		$('.action-bar .pagination .page-jump-form input.inputbox').on('keypress', function(event) {
			if (event.which === 13 || event.keyCode === 13) {
				event.preventDefault();
				if (!quickreply.settings.ajaxPagination) {
					pageJump($(this));
				} else if (quickreply.plugins.seo) {
					quickreply.functions.qr_seo_page_jump($(this));
				} else {
					quickreply.functions.qr_page_jump($(this));
				}
			}
		});

		$('.action-bar .pagination .dropdown-trigger').click(function() {
			var $dropdownContainer = $(this).parent();
			// Wait a little bit to make sure the dropdown has activated
			setTimeout(function() {
				if ($dropdownContainer.hasClass('dropdown-visible')) {
					$dropdownContainer.find('input.inputbox').focus();
				}
			}, 100);
		});
	};

	/**
	 * Adds initial Ajax functionality for the pagination.
	 */
	quickreply.style.initPagination = function() {
		$(window).on('load', function() {
			$('.action-bar .pagination').find('.page-jump-form :button').unbind('click');
			$('.action-bar .pagination').find('.page-jump-form input.inputbox').off('keypress');
			quickreply.style.bindPagination();
		});
	};

	/**
	 * Save message for the full reply form.
	 */
	quickreply.style.setPostReplyHandler = function() {
		$('.action-bar .buttons').find('.reply-icon, .locked-icon').click(function(e) {
			e.preventDefault();
			$(quickreply.editor.mainForm).off('submit').find('#qr_full_editor').off('click').click();
		});
	};

	/**
	 * Gets the ID of the post for the specified element.
	 *
	 * @param {jQuery} element Inner jQuery element
	 * @returns {string}
	 */
	quickreply.style.getPostId = function(element) {
		return element.parents('.post').attr('id').replace('p', '');
	};

	/**
	 * Gets quote buttons for the specified elements.
	 *
	 * @param {jQuery} elements jQuery elements, e.g. posts
	 * @returns {jQuery}
	 */
	quickreply.style.getQuoteButtons = function(elements) {
		return elements.find('.post-buttons .quote-icon').not('.responsive-menu .quote-icon');
	};

	quickreply.style.getAllQuoteButtons = function(elements) {
		return elements.find('.post-buttons .quote-icon');
	};

	quickreply.style.getLastQuoteButton = function(elements) {
		return elements.find('.post-container:last-child .post-buttons .quote-icon');
	};

	/**
	 * Hides the subject box from the form.
	 */
	quickreply.style.hideSubjectBox = function() {
		$("#subject").closest("dl").hide();
	};

	/**
	 * Attaches onclick event to quote buttons in responsive menu.
	 *
	 * @param {jQuery}   elements Container with quote buttons
	 * @param {function} fn       Event handler function
	 */
	quickreply.style.responsiveQuotesOnClick = function(elements, fn) {
		elements.find('.post-buttons .responsive-menu').on('click', '.quote-icon', fn);
	};

	quickreply.style.setQuickQuoteButton = function(elements) {
		var title = elements.attr('title');
		var responsive_text = elements.children('span').text();
		
		elements.addClass('qr-quickquote');
		elements.attr('title', quickreply.language.QUICKQUOTE_TITLE);
		elements.children('span').text(quickreply.language.QUICKQUOTE_TEXT);
	}

	quickreply.style.removeQuickQuoteButton = function(elements) {
		elements.removeClass('qr-quickquote');
		elements.attr('title', quickreply.language.REPLY_WITH_QUOTE);
		elements.children('span').text(quickreply.language.BUTTON_QUOTE);
	}

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
	 * @param {string} dropdownStyle  Style of dropdown element
	 * @param {string} [pointerStyle] Optional style of pointer element
	 * @param {string} [appendClass]  Optional dropdown class
	 * @returns {jQuery}
	 */
	quickreply.style.createDropdown = function(contentRows, dropdownStyle, pointerStyle, appendClass) {
		if (!contentRows) {
			return $('');
		}
		appendClass = (appendClass) ? ' ' + appendClass : '';
		pointerStyle = (pointerStyle) ? ' style="' + pointerStyle + '"' : '';
		return $('<div class="dropdown qr_dropdown' + appendClass + '" style="' + dropdownStyle + '"><div class="pointer"' + pointerStyle + '><div class="pointer-inner"></div></div><ul class="dropdown-contents dropdown-nonscroll"><li>' + contentRows.join('</li><li>') + '</li></ul></div>').appendTo('body');
	};

	/**
	 * Generates an HTML element for a QuickQuote dropdown element.
	 *
	 * @param {int} pageX X coordinate of the cursor
	 * @param {int} pageY Y coordinate of the cursor
	 * @returns {jQuery}
	 */
	quickreply.style.quickQuoteDropdown = function(pageX, pageY) {
		var dropdownStyle = 'top: ' + (pageY + 8) + 'px; ', pointerStyle = '', listElements = [
			quickreply.style.makeLink({
				href: "#qr_postform",
				text: quickreply.language.INSERT_TEXT
			})
		];

		if (pageX > 184) {
			dropdownStyle += 'margin-right: 0; left: auto; right: ' + ($('body').width() - pageX - 20) + 'px;';
			pointerStyle = 'left: auto; right: 10px;';
		} else {
			dropdownStyle += 'left: ' + (pageX - 20) + 'px;';
		}
		return quickreply.style.createDropdown(listElements, dropdownStyle, pointerStyle, 'qr_quickquote');
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
		var dropdownStyle = 'top: ' + (pageY + 8) + 'px; ', pointerStyle = '', listElements = [
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

		if (pageX > 184) {
			dropdownStyle += 'left: ' + (pageX - 111) + 'px;';
			pointerStyle = 'left: auto; right: 10px;';
		} else {
			dropdownStyle += 'left: ' + (pageX - 20) + 'px;';
		}
		return quickreply.style.createDropdown(listElements, dropdownStyle, pointerStyle);
	};
	//quickreply.style. = function() {
	//
	//};
})(jQuery, window, document);
