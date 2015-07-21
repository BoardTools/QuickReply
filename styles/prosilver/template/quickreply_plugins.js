/* global quickreply */
; (function ($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	/*****************************/
	/* Not Change Subject Plugin */
	/*****************************/
	if (quickreply.settings.unchangedSubject) {
		$(document).ready(function () {
			$("#subject").attr('disabled', 'disabled').css('color', 'grey');
		});
	}

	/*********************/
	/* Ctrl+Enter Plugin */
	/*********************/
	if (quickreply.settings.ctrlEnter) {
		$('#message-box textarea').keydown(function (event) {
			if (event.ctrlKey && (event.keyCode == 13 || event.keyCode == 10)) {
				$(this).parents('form').find('input[name="post"]').click();
			}
		});
	}

	/**************************************/
	/* Quick Quote and Full Quote Plugins */
	/**************************************/
	function qr_insert_quote(qr_post_id) {
		var qr_post_author = $('#qr_author_p' + qr_post_id),
			nickname = qr_post_author.text(),
			user_profile_url = qr_post_author.attr('data-url').replace(/^\.[\/\\]/, quickreply.editor.boardURL).replace(/(&amp;|&|\?)sid=[0-9a-f]{32}(&amp;|&)?/, function (str, p1, p2) { return (p2) ? p1 : ''; }),
			qr_user_name = (quickreply.settings.quickQuoteLink && user_profile_url && quickreply.settings.enableBBCode) ? '[url=' + user_profile_url + ']' + nickname + '[/url]' : nickname;

		// Link to the source post
		var qr_bbpost = (quickreply.settings.sourcePost) ? '[post]' + qr_post_id + '[/post] ' : '';

		var message_name = 'decoded_p' + qr_post_id;
		var theSelection = '';
		var divarea = false;
		var clientPC = navigator.userAgent.toLowerCase(); // Get client info
		var is_ie = ((clientPC.indexOf('msie') !== -1) && (clientPC.indexOf('opera') === -1));
		var i;

		if (document.all) {
			divarea = document.all[message_name];
		} else {
			divarea = document.getElementById(message_name);
		}

		// Get text selection - not only the post content :(
		// IE9 must use the document.selection method but has the *.getSelection so we just force no IE
		if (window.getSelection && !is_ie && !window.opera) {
			theSelection = window.getSelection().toString();
		} else if (document.getSelection && !is_ie) {
			theSelection = document.getSelection();
		} else if (document.selection) {
			theSelection = document.selection.createRange().text;
		}

		if (theSelection === '' || typeof theSelection === 'undefined' || theSelection === null) {
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
		}

		if (theSelection) {
			if (quickreply.settings.enableBBCode) {
				insert_text('[quote="' + qr_user_name + '"]' + qr_bbpost + theSelection + '[/quote]\r');
			} else {
				insert_text(qr_user_name + ' ' + quickreply.language.WROTE + ':' + '\n');
				var lines = split_lines(theSelection);
				for (i = 0; i < lines.length; i++) {
					insert_text('> ' + lines[i] + '\n');
				}
			}
		}
	}

	/**********************/
	/* Quick Quote Plugin */
	/**********************/
	if (quickreply.settings.quickQuote) {
		$('#qr_posts').on('mouseup', '.content', function (evt) {
			// Get cursor coordinates
			var pageX = evt.pageX || evt.clientX + document.documentElement.scrollLeft; // FF || IE
			var pageY = evt.pageY || evt.clientY + document.documentElement.scrollTop;
			// Which mouse button is pressed?
			var key = evt.button || evt.which || null; // IE || FF || Unknown

			var qr_post_id = $(this).parent().attr('id').replace('post_content', '');

			setTimeout(function () { // Timeout prevents popup when clicking on selected text
				var sel = '';
				if (window.getSelection)
					sel = window.getSelection().toString();
				else if (document.getSelection)
					sel = document.getSelection();
				else if (document.selection)
					sel = document.selection.createRange().text;

				if (sel && key <= 1) { // If text selected && right mouse button not pressed
					var qrAlert = $('<div class="dropdown" style="top: ' + (pageY + 8) + 'px; left: ' + (pageX > 184 ? pageX - 184 : pageX - 20) + 'px;"><div class="pointer"' + (pageX > 184 ? (' style="left: auto; right: 10px;"') : '') + '><div class="pointer-inner"></div></div><ul class="dropdown-contents dropdown-nonscroll"><li><a href="#qr_postform" style="font-size: 11px !important;">' + quickreply.language.INSERT_TEXT + '</a></li></ul></div>').appendTo('body');
					function qr_insert_quickquote() {
						qr_insert_quote(qr_post_id);
						qrAlert.remove();
						return false;
					}
					$('a', qrAlert).mousedown(qr_insert_quickquote);
					$(qrAlert).mousedown(qr_insert_quickquote);
					function qr_alert_remove(e) {
						qrAlert.remove();
						$(document.body).unbind('mousedown', qr_alert_remove);
					}
					setTimeout(function () {
						$(document.body).mousedown(qr_alert_remove);
					}, 10);
				}
			}, 0);
		});
	}

	/*********************/
	/* Full Quote Plugin */
	/*********************/
	if (quickreply.settings.fullQuote) {
		function qr_full_quote(e, elements) {
			elements.find('.post-buttons .quote-icon').click(function (e) {
				e.preventDefault();
				var $this = $(this),
					qr_post_id = $this.parents('.post').attr('id').replace('p', '');
				qr_insert_quote(qr_post_id);
			});
		}

		$(document).ready(function (e) {
			qr_full_quote(e, $('#qr_posts'));
		});
		$('#qr_posts').on('qr_loaded', qr_full_quote);
	}

	/*********************/
	/* Quick Nick Plugin */
	/*********************/
	if (quickreply.settings.quickNick) {
		function getHexColor(color) {
			color = color.replace(/\s/g, "");
			var colorRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i);
			colorHEX = '';
			for (var i = 1; i <= 3; i++) {
				colorHEX += Math.round((colorRGB[i][colorRGB[i].length - 1] == "%" ? 2.55 : 1) * parseInt(colorRGB[i])).toString(16).replace(/^(.)$/, '0$1');
			}
			return "#" + colorHEX;
		}

		function qr_quicknick(evt, link, color) {
			// Get cursor coordinates
			if (!evt) evt = window.event;
			evt.preventDefault();
			var pageX = evt.pageX || evt.clientX + document.documentElement.scrollLeft; // FF || IE
			var pageY = evt.pageY || evt.clientY + document.documentElement.scrollTop;
			// Which mouse button is pressed?
			var key = evt.button || evt.which || null; // IE || FF || Unknown

			//Get nick and id
			var viewprofile_url = link.attr('href');
			var nickname = link.text();
			var qr_pm_link = link.parents('.post').find('.contact-icon.pm-icon').parent('a');

			var qrNickAlert = $('<div class="dropdown" style="top: ' + (pageY + 8) + 'px; left: ' + (pageX > 184 ? pageX - 111 : pageX - 20) + 'px;"><div class="pointer"' + (pageX > 184 ? (' style="left: auto; right: 10px;"') : '') + '><div class="pointer-inner"></div></div><ul class="dropdown-contents dropdown-nonscroll"><li><a href="#qr_postform" class="qr_quicknick" style="font-size: 11px !important;" title="' + quickreply.language.QUICKNICK_TITLE + '">' + quickreply.language.QUICKNICK + '</a></li>' + ((quickreply.settings.quickNickPM && qr_pm_link.length) ? '<li><a href="' + qr_pm_link.attr('href') + '" class="qr_reply_in_pm" style="font-size: 11px !important;" title="' + quickreply.language.REPLY_IN_PM + '">' + quickreply.language.REPLY_IN_PM + '</a></li>' : '') + '<li><a href="' + viewprofile_url + '" class="qr_profile" style="font-size: 11px !important;" title="' + quickreply.language.PROFILE + '">' + quickreply.language.PROFILE + '</a></li></ul></div>').appendTo('body');

			var comma = (quickreply.settings.enableComma) ? ', ' : '\r\n',
				qr_color = (quickreply.settings.colouredNick && color) ? '=' + getHexColor(color) : '';
			$('a.qr_quicknick', qrNickAlert).mousedown(function () {
				if (!quickreply.settings.enableBBCode) insert_text(nickname + comma, false);
				else if (!quickreply.settings.quickNickRef) insert_text('[b]' + nickname + '[/b]' + comma, false);
				else insert_text('[ref' + qr_color + ']' + nickname + '[/ref]' + comma, false);
				qrNickAlert.remove();
				return false;
			});
			$('a', qrNickAlert).mousedown(function (e) {
				e.preventDefault();
				return false;
			});
			function qr_alert_remove(e) {
				qrNickAlert.remove();
				$(document.body).unbind('mousedown', qr_alert_remove);
			}
			setTimeout(function () {
				$(document.body).mousedown(qr_alert_remove);
			}, 10);
		}

		/* Ajax Submit */
		$('#qr_posts').on('click', 'a.username', function (e) {
			qr_quicknick(e, $(this), false);
		});

		$('#qr_posts').on('click', 'a.username-coloured', function (e) {
			qr_quicknick(e, $(this), $(this).css('color'));
		});

		function quicknick_handle_posts(e, elements) {
			elements.find('a.username, a.username-coloured').each(function () {
				$(this).attr('title', quickreply.language.QUICKNICK);
			});
		}

		$(document).ready(function (e) {
			quicknick_handle_posts(e, $('#qr_posts'));
		});
		$('#qr_posts').on('qr_loaded', quicknick_handle_posts);

		/**********************/
		/* Live Search Plugin */
		/**********************/
		if (quickreply.plugins.liveSearch) {
			$(document).ready(function () {
				$('#topics_live_search').parent().before('<li><a id="qr_quicknick_live_search" href="#qr_postform" title="' + quickreply.language.QUICKNICK_TITLE + '">' + quickreply.language.QUICKNICK + '</a></li><li class="separator"></li>');
			});

			$('#leavesearch').on('click', '#qr_quicknick_live_search', function () {
				var comma = (quickreply.settings.enableComma) ? ', ' : '',
					nickname = $('#user_live_search').val();

				insert_text('[ref]' + nickname + '[/ref]' + comma, false);
			});
		}
	}

	/*******************/
	/* ABBC 3.1 Plugin */
	/*******************/
	if (quickreply.plugins.abbc3) {
		/* Ajax Submit */
		$('#qr_posts').on('qr_loaded', function (e, elements) {
			var bbvideo = elements.find('.bbvideo');
			if (bbvideo.length > 0) {
				bbvideo.bbvideo();
			}
		});
		$('#qr_postform').on('ajax_submit_preview', function () {
			var bbvideo = $('#preview').find('.bbvideo');
			if (bbvideo.length > 0) {
				bbvideo.bbvideo();
			}
		});
	}
})(jQuery, window, document);
