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
	function QrHelper() {
		this._selection = '';
		this._replyPosts = $('#qr_posts');

		var self = this,
			clientPC = navigator.userAgent.toLowerCase(), // Get client info
			isIE = ((clientPC.indexOf('msie') !== -1) && (clientPC.indexOf('opera') === -1));

		/**
		 * Get text selection - not only the post content :(
		 * IE9 must use the document.selection method but has the *.getSelection so we just force no IE
		 *
		 * @returns {string}
		 */
		self._getSelection = function() {
			self._selection = '';

			if (window.getSelection && !isIE && !window.opera) {
				self._selection = window.getSelection().toString();
			} else if (document.getSelection && !isIE) {
				self._selection = document.getSelection();
			} else if (document.selection) {
				self._selection = document.selection.createRange().text;
			}
		};

		/**
		 * Gets cursor coordinates.
		 *
		 * @param {Event} evt jQuery Event object
		 * @returns {object}
		 */
		self._getCoordinates = function(evt) {
			if (evt.type == 'touchstart') {
				evt.pageX = evt.originalEvent.touches[0].pageX;
				evt.pageY = evt.originalEvent.touches[0].pageY;
			}

			return {
				x: evt.pageX || evt.clientX + document.documentElement.scrollLeft, // FF || IE
				y: evt.pageY || evt.clientY + document.documentElement.scrollTop
			};
		}
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
	function QrQuote() {
		QrHelper.apply(this, arguments);

		this._postID = 0;

		var self = this;

		function getUserName() {
			var postAuthor = $('#qr_author_p' + self._postID),
				nickname = postAuthor.text(),
				userProfileUrl = postAuthor.attr('data-url').replace(/^\.[\/\\]/, quickreply.editor.boardURL)
					.replace(/(&amp;|&|\?)sid=[0-9a-f]{32}(&amp;|&)?/,
						function(str, p1, p2) {
							return (p2) ? p1 : '';
						}
					);
			return (quickreply.settings.quickQuoteLink && userProfileUrl && quickreply.settings.allowBBCode) ?
								'[url=' + userProfileUrl + ']' + nickname + '[/url]' : nickname;
		}

		function getBBpost() {
			return (quickreply.settings.sourcePost) ? '[post]' + self._postID + '[/post] ' : '';
		}

		self._insertQuote = function() {
			var username = getUserName(),
				bbpost = getBBpost(); // Link to the source post

			if (self._selection) {
				quickreply.style.showQuickReplyForm();

				if (quickreply.settings.allowBBCode) {
					insert_text('[quote="' + username + '"]' + bbpost + self._selection + '[/quote]\r');
				} else {
					insert_text(username + ' ' + quickreply.language.WROTE + ':' + '\n');

					var lines = split_lines(self._selection);

					for (var i = 0; i < lines.length; i++) {
						insert_text('> ' + lines[i] + '\n');
					}
				}
			}
		}
	}

	/**********************/
	/* Quick Quote Plugin */
	/**********************/
	if (quickreply.settings.quickQuote) {
		var QuickQuote = new function() {
			QrQuote.apply(this, arguments);

			var self = this,
				qrAlert = false,
				quickQuoteCancelEvent = false;

			function qrAlertRemove(e) {
				if (qrAlert) {
					qrAlert.remove();
					$(document.body).unbind('mousedown', qrAlertRemove);
					qrAlert = false;
				}
			}

			function insertQuickQuote() {
				self._insertQuote();
				qrAlertRemove();
				return false;
			}

			function addQuickQuote(evt, element) {
				var $target = $(evt.target), $element = element || $(this);

				if ($target.is('a') && $target.parents('.codebox').length) {
					return;
				}

				var coordinates = self._getCoordinates(evt);
				// Which mouse button is pressed?
				var key = evt.button || evt.which || null; // IE || FF || Unknown

				self._postID = quickreply.style.getPostId($element);

				setTimeout(function() { // Timeout prevents popup when clicking on selected text
					self._getSelection();

					if (self._selection && key <= 1) { // If text selected && right mouse button not pressed
						if (qrAlert) {
							qrAlertRemove();
						}

						qrAlert = quickreply.style.quickQuoteDropdown(coordinates.x, coordinates.y)
									.mousedown(insertQuickQuote);

						setTimeout(function() {
							$(document.body).one('mousedown', qrAlertRemove);
						}, 10);
					}
				}, 0);
			}

			function handleQuickQuote(evt) {
				addQuickQuote(evt, $(this));

				if (!quickQuoteCancelEvent) {
					self._replyPosts.on('mousemove', '.content', addQuickQuote);

					$(document.body).one('mouseup', function(evt) {
						self._replyPosts.off('mousemove', '.content', addQuickQuote);
						quickQuoteCancelEvent = false;
					});

					quickQuoteCancelEvent = true;
				}
			}

			self.init = function() {
				if ('ontouchstart' in window) {
					self._replyPosts.on('mousedown touchstart', '.content', handleQuickQuote);
				}

				self._replyPosts.on('mouseup', '.content', addQuickQuote);
			};
		};

		QuickQuote.init();
	}

	/*********************/
	/* Full Quote Plugin */
	/*********************/
	if (quickreply.settings.fullQuote || quickreply.settings.quickQuoteButton) {
		var FullQuote = new function() {
			QrQuote.apply(this, arguments);

			var self = this;

			function getPostContent() {
				self._selection = '';

				var messageName = 'decoded_p' + self._postID, divarea = false;

				if (document.all) {
					divarea = document.all[messageName];
				} else {
					divarea = document.getElementById(messageName);
				}

				if (divarea.innerHTML) {
					self._selection = divarea.innerHTML.replace(/<br>/ig, '\n');
					self._selection = self._selection.replace(/<br\/>/ig, '\n');
					self._selection = self._selection.replace(/&lt\;/ig, '<');
					self._selection = self._selection.replace(/&gt\;/ig, '>');
					self._selection = self._selection.replace(/&amp\;/ig, '&');
					self._selection = self._selection.replace(/&nbsp\;/ig, ' ');
				} else if (document.all) {
					self._selection = divarea.innerText;
				} else if (divarea.textContent) {
					self._selection = divarea.textContent;
				} else if (divarea.firstChild.nodeValue) {
					self._selection = divarea.firstChild.nodeValue;
				}

				self._selection = self._selection.replace(/(\[attachment.*?\]|\[\/attachment\])/g, '');
			}

			function addFullQuote(e, element) {
				e.preventDefault();
				self._postID = quickreply.style.getPostId(element);
				self._selection = '';

				if (quickreply.settings.quickQuoteButton) {
					self._getSelection();
				}

				if (quickreply.settings.fullQuote && !element.hasClass('qr-quickquote')) {
					if (self._selection === '' || typeof self._selection === 'undefined' || self._selection === null) {
						getPostContent();
					}
				}

				if (self._selection != '') {
					self._insertQuote();
				} else { //@TODO учитывать права доступа!!
					quickreply.functions.alert(quickreply.language.ERROR, quickreply.language.NO_FULL_QUOTE);
				}
			}

			function qrFullQuote(e, elements) { //@TODO неочевидное название функции, придумать новое
				if (quickreply.settings.quickQuoteButton) {
					var quoteButtons = null, lastQuoteButton = null;

					if (!quickreply.settings.fullQuote || !quickreply.settings.fullQuoteAllowed) {
						// Style all quote buttons
						quoteButtons = quickreply.style.getQuoteButtons(elements, 'all');

						quickreply.style.setQuickQuoteButton(quoteButtons);

					} else if (!quickreply.settings.lastQuote && quickreply.style.isLastPage()) {
						// Style only last quote button
						quoteButtons = quickreply.style.getQuoteButtons(this._replyPosts, 'all');
						lastQuoteButton = quickreply.style.getQuoteButtons(elements, 'last');

						quickreply.style.removeQuickQuoteButton(quoteButtons);
						quickreply.style.setQuickQuoteButton(lastQuoteButton);
					}
				}

				quickreply.style.getQuoteButtons(elements).click(function(e) {
					addFullQuote(e, $(this));
				});
			}

			function qrFullQuoteResponsive(e) {
				addFullQuote(e, $(this));

				var $container = $(this).parents('.dropdown-container'),
					$trigger = $container.find('.dropdown-trigger:first'),
					data;

				if (!$trigger.length) {
					data = $container.attr('data-dropdown-trigger');
					$trigger = data ? $container.children(data) : $container.children('a:first');
				}

				$trigger.click();
			}

			self.init = function() {
				$(window).on('load', function(e) {
					qrFullQuote(e, self._replyPosts);
					quickreply.style.responsiveQuotesOnClick(self._replyPosts, qrFullQuoteResponsive);
				});

				$('#qr_posts').on('qr_completed', function(e, elements) {
					qrFullQuote(e, elements);
					quickreply.style.responsiveQuotesOnClick(elements, qrFullQuoteResponsive);
				});
			}
		};

		FullQuote.init();
	}

	/*********************/
	/* Quick Nick Plugin */
	/*********************/
	/**
	 * Inserts the nickname of the specified user to quick reply textarea.
	 *
	 * @param {jQuery} link jQuery element for the user profile link
	 */
	if (quickreply.settings.quickNick || quickreply.settings.quickNickString) {
		var QuickNick = new function() {
			QrHelper.apply(this, arguments);

			var self = this,
				comma = (quickreply.settings.enableComma) ? ', ' : '\r\n';

			function quickNickIsDropdown() {
				return !!(quickreply.settings.quickNick && !quickreply.settings.quickNickUserType);
			}

			function quickNickIsString() {
				return !!(
					quickreply.settings.quickNickString ||
					(
						quickreply.settings.quickNick
						&& quickreply.settings.quickNickUserType
					)
				);
			}

			function qrQuickNick(evt, link) {
				// Get cursor coordinates
				if (!evt) {
					evt = window.event;
				}
				evt.preventDefault();
				var coordinates = self._getCoordinates(evt);

				// Get nick and id
				var viewprofile_url = link.attr('href');
				var qr_pm_link = link.parents('.post').find('.contact-icon.pm-icon').parent('a');

				var qrNickAlert = quickreply.style.quickNickDropdown(
					coordinates.x, coordinates.y, viewprofile_url, qr_pm_link
				);

				function qrAlertRemove(e) {
					qrNickAlert.remove();
					$(document.body).unbind('mousedown', qrAlertRemove);
				}

				$('a.qr_quicknick', qrNickAlert).mousedown(function() {
					insertQuickNick(link);
					qrNickAlert.remove();
					return false;
				});

				$('a', qrNickAlert).mousedown(function(e) {
					e.preventDefault();
					return false;
				});

				setTimeout(function() {
					$(document.body).mousedown(qrAlertRemove);
				}, 10);
			}

			function quickNickHandlePosts(e, elements) {
				elements.find(quickreply.editor.profileLinkSelector).each(function() {
					$(this).attr('title', quickreply.language.QUICKNICK);
				});
			}

			function insertQuickNick(link) {
				var nickname = link.text(),
					color = (link.hasClass('username-coloured')) ? link.css('color') : false,
					qrColor = (quickreply.settings.colouredNick && color) ? '=' + quickreply.functions.getHexColor(color) : '';

				quickreply.style.showQuickReplyForm();

				if (!quickreply.settings.allowBBCode) {
					insert_text(nickname + comma, false);
				} else if (!quickreply.settings.quickNickRef) {
					insert_text('[b]' + nickname + '[/b]' + comma, false);
				} else {
					insert_text('[ref' + qrColor + ']' + nickname + '[/ref]' + comma, false);
				}
			}

			self.init = function() {
				if (quickNickIsDropdown()) {
					$(document).ready(function(e) {
						quickNickHandlePosts(e, self._replyPosts);
					});
					self._replyPosts.on('qr_loaded', quickNickHandlePosts);

					/* Ajax Submit */
					self._replyPosts.on('click', quickreply.editor.profileLinkSelector, function(e) {
						qrQuickNick(e, $(this));
					});
				}

				if (quickNickIsString()) {
					self._replyPosts.on('click', '.qr_quicknick', function(e) {
						e.preventDefault();
						var link = $(this).parent().find(quickreply.editor.profileLinkSelector);
						insertQuickNick(link);
					})
				}
			}
		};

		QuickNick.init();

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
