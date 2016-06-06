/* global quickreply, grecaptcha */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	/*****************************/
	/* Not Change Subject Plugin */
	/*****************************/
	if (quickreply.settings.unchangedSubject) {
		if (quickreply.settings.hideSubjectBox) {
			$(document).ready(function() {
				quickreply.style.hideSubjectBox();
			});
		} else {
			$(document).ready(function() {
				$(quickreply.editor.mainForm).find('input[name="subject"][type="text"]')
					.attr('disabled', 'disabled').css('color', 'grey');
			});
		}
	}

	/*********************/
	/* Ctrl+Enter Plugin */
	/*********************/
	if (quickreply.settings.ctrlEnter) {
		$(quickreply.editor.textareaSelector).keydown(function(event) {
			if (event.ctrlKey && (event.keyCode == 13 || event.keyCode == 10)) {
				$(this).parents('form').find('input[name="post"]').click();
			}
		});
	}
	
	/********************/
	/* Helper functions */
	/********************/
	/**
	 * Get text selection - not only the post content :(
	 * IE9 must use the document.selection method but has the *.getSelection so we just force no IE
	 *
	 * @returns {string}
	 */
	function qr_getSelection() {
		var sel = '';
		if (window.getSelection) {
			sel = window.getSelection().toString();
		} else if (document.getSelection) {
			sel = document.getSelection();
		} else if (document.selection) {
			sel = document.selection.createRange().text;
		}
		return sel;
	}

	/**
	 * Returns the formatted post content.
	 *
	 * @param {string} qr_post_id   ID of the current post
	 * @returns {string}
	 */
	function qr_getPostContent(qr_post_id) {
		var theSelection = '', message_name = 'decoded_p' + qr_post_id, divarea = false;

		if (document.all) {
			divarea = document.all[message_name];
		} else {
			divarea = document.getElementById(message_name);
		}

		if (divarea.innerHTML) {
			theSelection = divarea.innerHTML.replace(/<br>/ig, '\n');
			theSelection = theSelection.replace(/<br\/>/ig, '\n');
			theSelection = theSelection.replace(/&lt\;/ig, '<');
			theSelection = theSelection.replace(/&gt\;/ig, '>');
			theSelection = theSelection.replace(/&amp\;/ig, '&');
			theSelection = theSelection.replace(/&nbsp\;/ig, ' ');
		} else if (document.all) {
			theSelection = divarea.innerText;
		} else if (divarea.textContent) {
			theSelection = divarea.textContent;
		} else if (divarea.firstChild.nodeValue) {
			theSelection = divarea.firstChild.nodeValue;
		}

		return theSelection;
	}

	/**
	 * Gets cursor coordinates.
	 *
	 * @param {Event} evt jQuery Event object
	 */
	function qr_getCoordinates(evt) {
		if (evt.type == 'touchstart') {
			evt.pageX = evt.originalEvent.touches[0].pageX;
			evt.pageY = evt.originalEvent.touches[0].pageY;
		}
		qr_pageX = evt.pageX || evt.clientX + document.documentElement.scrollLeft; // FF || IE
		qr_pageY = evt.pageY || evt.clientY + document.documentElement.scrollTop;
	}

	/**************************************/
	/* Quick Quote and Full Quote Plugins */
	/**************************************/
	/**
	 * Inserts a quote from the specified post to quick reply textarea.
	 *
	 * @param {string} qr_post_id    The ID of the post
	 * @param {string} selected_text Selected text
	 */
	quickreply.functions.insertQuote = function(qr_post_id, selected_text) {
		var qr_post_author = $('#qr_author_p' + qr_post_id),
			nickname = qr_post_author.text(),
			user_profile_url = qr_post_author.attr('data-url').replace(/^\.[\/\\]/, quickreply.editor.boardURL)
				.replace(/(&amp;|&|\?)sid=[0-9a-f]{32}(&amp;|&)?/, function(str, p1, p2) {
				return (p2) ? p1 : '';
			}),
			qr_user_name = (quickreply.settings.quickQuoteLink && user_profile_url && quickreply.settings.allowBBCode) ?
				'[url=' + user_profile_url + ']' + nickname + '[/url]' : nickname;

		// Link to the source post
		var qr_bbpost = (quickreply.settings.sourcePost) ? '[post]' + qr_post_id + '[/post] ' : '';

		//var clientPC = navigator.userAgent.toLowerCase(); // Get client info
		//var is_ie = ((clientPC.indexOf('msie') !== -1) && (clientPC.indexOf('opera') === -1)); // Зачем? Нигде не используется
		var i;

		if (selected_text) {
			quickreply.style.showQuickReplyForm();
			if (quickreply.settings.allowBBCode) {
				insert_text('[quote="' + qr_user_name + '"]' + qr_bbpost +
					selected_text.replace(/(\[attachment.*?\]|\[\/attachment\])/g, '') + '[/quote]\r');
			} else {
				insert_text(qr_user_name + ' ' + quickreply.language.WROTE + ':' + '\n');
				var lines = split_lines(selected_text);
				for (i = 0; i < lines.length; i++) {
					insert_text('> ' + lines[i] + '\n');
				}
			}
		}
	};

	/**********************/
	/* Quick Quote Plugin */
	/**********************/
	if (quickreply.settings.quickQuote) {
		var qrAlert = false;

		function qr_alert_remove(e) {
			if (qrAlert) {
				qrAlert.remove();
				$(document.body).unbind('mousedown', qr_alert_remove);
				qrAlert = false;
			}
		}

		function qr_quickquote(evt, element) {
			var $target = $(evt.target), $element = element || $(this);
			if ($target.is('a') && $target.parents('.codebox').length) {
				return;
			}

			qr_getCoordinates(evt);
			// Which mouse button is pressed?
			var key = evt.button || evt.which || null; // IE || FF || Unknown

			var qr_post_id = quickreply.style.getPostId($element);

			setTimeout(function() { // Timeout prevents popup when clicking on selected text
				var sel = qr_getSelection();

				if (sel && key <= 1) { // If text selected && right mouse button not pressed
					function qr_insert_quickquote() {
						quickreply.functions.insertQuote(qr_post_id, sel);
						qr_alert_remove();
						return false;
					}

					if (qrAlert) {
						qr_alert_remove();
					}
					qrAlert = quickreply.style.quickQuoteDropdown(qr_pageX, qr_pageY).mousedown(qr_insert_quickquote);
					setTimeout(function() {
						$(document.body).one('mousedown', qr_alert_remove);
					}, 10);
				}
			}, 0);
		}

		var reply_posts = $('#qr_posts'), quickquote_cancel_event = false;

		function qr_handle_quickquote(evt) {
			qr_quickquote(evt, $(this));
			if (!quickquote_cancel_event) {
				reply_posts.on('mousemove', '.content', qr_quickquote);
				$(document.body).one('mouseup', function(evt) {
					reply_posts.off('mousemove', '.content', qr_quickquote);
					quickquote_cancel_event = false;
				});
				quickquote_cancel_event = true;
			}
		}

		if ('ontouchstart' in window) {
			reply_posts.on('mousedown touchstart', '.content', qr_handle_quickquote);
		}
		reply_posts.on('mouseup', '.content', qr_quickquote);
	}

	/*********************/
	/* Full Quote Plugin */
	/*********************/
	if (quickreply.settings.fullQuote || quickreply.settings.quickQuoteButton) {
		function qr_add_full_quote(e, element) {
			e.preventDefault();
			var qr_post_id = quickreply.style.getPostId(element);
			var theSelection = '';

			if (quickreply.settings.quickQuoteButton) {
				theSelection = qr_getSelection();
			}

			if (quickreply.settings.fullQuote && !element.hasClass('qr-quickquote')) {
				if (theSelection === '' || typeof theSelection === 'undefined' || theSelection === null) {
					theSelection = qr_getPostContent(qr_post_id);
				}
			}

			if (theSelection != '') {
				quickreply.functions.insertQuote(qr_post_id, theSelection);
			} else {
				quickreply.functions.alert(quickreply.language.ERROR, quickreply.language.NO_FULL_QUOTE);
			}
		}

		function qr_full_quote(e, elements) {
			if (quickreply.settings.quickQuoteButton) {
				var quote_buttons = null, last_quote_button = null;
				if (!quickreply.settings.fullQuote || !quickreply.settings.fullQuoteAllowed) {
					// Style all quote buttons
					quote_buttons = quickreply.style.getAllQuoteButtons(elements);
					quickreply.style.setQuickQuoteButton(quote_buttons);
				} else if (!quickreply.settings.lastQuote && quickreply.style.isLastPage()) {
					// Style only last quote button
					quote_buttons = quickreply.style.getAllQuoteButtons($('#qr_posts'));
					last_quote_button = quickreply.style.getLastQuoteButton(elements);
					quickreply.style.removeQuickQuoteButton(quote_buttons);
					quickreply.style.setQuickQuoteButton(last_quote_button);
				}
			}

			quickreply.style.getQuoteButtons(elements).click(function(e) {
				qr_add_full_quote(e, $(this));
			});
		}

		function qr_full_quote_responsive(e) {
			qr_add_full_quote(e, $(this));
			var $container = $(this).parents('.dropdown-container'),
				$trigger = $container.find('.dropdown-trigger:first'),
				data;

			if (!$trigger.length) {
				data = $container.attr('data-dropdown-trigger');
				$trigger = data ? $container.children(data) : $container.children('a:first');
			}
			$trigger.click();
		}

		$(window).on('load', function(e) {
			var reply_posts = $('#qr_posts');
			qr_full_quote(e, reply_posts);
			quickreply.style.responsiveQuotesOnClick(reply_posts, qr_full_quote_responsive);
		});
		$('#qr_posts').on('qr_completed', function(e, elements) {
			qr_full_quote(e, elements);
			quickreply.style.responsiveQuotesOnClick(elements, qr_full_quote_responsive);
		});
	}

	/*********************/
	/* Quick Nick Plugin */
	/*********************/
	/**
	 * Inserts the nickname of the specified user to quick reply textarea.
	 *
	 * @param {jQuery} link jQuery element for the user profile link
	 */
	quickreply.functions.quickNick = function(link) {
		var nickname = link.text(),
			comma = (quickreply.settings.enableComma) ? ', ' : '\r\n',
			color = (link.hasClass('username-coloured')) ? link.css('color') : false,
			qr_color = (quickreply.settings.colouredNick && color) ? '=' + quickreply.functions.getHexColor(color) : '';
		quickreply.style.showQuickReplyForm();
		if (!quickreply.settings.allowBBCode) {
			insert_text(nickname + comma, false);
		} else if (!quickreply.settings.quickNickRef) {
			insert_text('[b]' + nickname + '[/b]' + comma, false);
		} else {
			insert_text('[ref' + qr_color + ']' + nickname + '[/ref]' + comma, false);
		}
	};

	if (quickreply.settings.quickNick && !quickreply.settings.quickNickUserType) {
		function qr_quicknick(evt, link) {
			// Get cursor coordinates
			if (!evt) {
				evt = window.event;
			}
			evt.preventDefault();
			qr_getCoordinates(evt);

			// Get nick and id
			var viewprofile_url = link.attr('href');
			var qr_pm_link = link.parents('.post').find('.contact-icon.pm-icon').parent('a');

			var qrNickAlert = quickreply.style.quickNickDropdown(qr_pageX, qr_pageY, viewprofile_url, qr_pm_link);

			$('a.qr_quicknick', qrNickAlert).mousedown(function() {
				quickreply.functions.quickNick(link);
				qrNickAlert.remove();
				return false;
			});
			$('a', qrNickAlert).mousedown(function(e) {
				e.preventDefault();
				return false;
			});
			function qr_alert_remove(e) {
				qrNickAlert.remove();
				$(document.body).unbind('mousedown', qr_alert_remove);
			}

			setTimeout(function() {
				$(document.body).mousedown(qr_alert_remove);
			}, 10);
		}

		/* Ajax Submit */
		$('#qr_posts').on('click', quickreply.editor.profileLinkSelector, function(e) {
			qr_quicknick(e, $(this));
		});

		function quicknick_handle_posts(e, elements) {
			elements.find(quickreply.editor.profileLinkSelector).each(function() {
				$(this).attr('title', quickreply.language.QUICKNICK);
			});
		}

		$(document).ready(function(e) {
			quicknick_handle_posts(e, $('#qr_posts'));
		});
		$('#qr_posts').on('qr_loaded', quicknick_handle_posts);
	}

	if (quickreply.settings.quickNickString ||
		(quickreply.settings.quickNick && quickreply.settings.quickNickUserType)) {
		$('#qr_posts').on('click', '.qr_quicknick', function(e) {
			e.preventDefault();
			var link = $(this).parent().find(quickreply.editor.profileLinkSelector);
			quickreply.functions.quickNick(link);
		})
	}

	if (quickreply.settings.quickNick || quickreply.settings.quickNickString) {
		/**********************/
		/* Live Search Plugin */
		/**********************/
		if (quickreply.plugins.liveSearch) {
			$(document).ready(function() {
				$('#topics_live_search').parent()
					.before('<li><a id="qr_quicknick_live_search" href="#qr_postform" title="' +
						quickreply.language.QUICKNICK_TITLE + '">' + quickreply.language.QUICKNICK +
						'</a></li><li class="separator"></li>');
			});

			$('#leavesearch').on('click', '#qr_quicknick_live_search', function() {
				var comma = (quickreply.settings.enableComma) ? ', ' : '',
					nickname = $('#user_live_search').val();
				quickreply.style.showQuickReplyForm();
				insert_text('[ref]' + nickname + '[/ref]' + comma, false);
			});
		}
	}

	/*******************/
	/* ABBC 3.1 Plugin */
	/*******************/
	if (quickreply.plugins.abbc3) {
		var qr_abbc3_bbvideo = function(e, elements) {
			var bbvideo = elements.find('.bbvideo');
			if (bbvideo.length > 0) {
				bbvideo.bbvideo();
			}
		};

		/* Ajax Submit */
		$('#qr_posts').on('qr_completed', qr_abbc3_bbvideo);
		$('#qr_postform').on('ajax_submit_preview', qr_abbc3_bbvideo);
	}

	/*********************/
	/* reCAPTCHA2 Plugin */
	/*********************/
	if (quickreply.plugins.reCAPTCHA2) {
		$('#qr_postform').on('qr_captcha_refreshed', function(e) {
			var recaptcha2_wrapper = $('.g-recaptcha');
			recaptcha2_wrapper.html('');
			grecaptcha.render(document.getElementsByClassName('g-recaptcha')[0], {
				'sitekey': recaptcha2_wrapper.attr('data-sitekey')
			});
		});
	}
})(jQuery, window, document);
