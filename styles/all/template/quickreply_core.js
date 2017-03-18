/* global phpbb, phpbb_seo, quickreply, plupload */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	'use strict';

	var qrSlideInterval = (quickreply.settings.softScroll) ? quickreply.settings.scrollInterval : 0,
		qrAlertTimer = null,
		$body = $('body');

	/***********************/
	/* Initial adjustments */
	/***********************/
	if (quickreply.settings.pluploadEnabled) {
		phpbb.plupload.config.form_hook = quickreply.editor.mainForm;
	}

	if (quickreply.settings.attachBox) {
		/* Fix for external links. */
		$('#file-list').on('click', 'a[href]', function() {
			$(this).attr('target', '__blank');
		});
	}

	/********************************/
	/* Classes and global functions */
	/********************************/
	initGlobalFunctions();

	quickreply.$ = new QrJqueryElements();
	quickreply.loading = new Loading();
	quickreply.ajax = new Ajax();
	quickreply.ajaxReload = new AjaxReload();
	quickreply.form = new QrForm();
	quickreply.preview = new Preview();

	quickreply.loading.init();
	quickreply.ajax.init();
	quickreply.form.init();

	/**
	 * Show the confirmation warning before unloading the page if the reply is still in the form.
	 */
	if (quickreply.settings.enableWarning) {
		$(window).on('beforeunload.quickreply', function() {
			if (quickreply.form.hasReply()) {
				return quickreply.language.WARN_BEFORE_UNLOAD;
			}
		});

		if (!quickreply.settings.ajaxSubmit) {
			quickreply.$.mainForm.submit(function() {
				$(window).off('beforeunload.quickreply');
			});
		}
	}

	/* Work with browser's history. */
	var qrStopHistory = false, qrReplaceHistory = false;
	if (quickreply.settings.ajaxSubmit || quickreply.settings.ajaxPagination) {
		$(window).on("popstate", function(e) {
			qrStopHistory = true;
			document.title = e.originalEvent.state.title;
			quickreply.ajaxReload.start(e.originalEvent.state.url);
		});

		/* Workaround for browser's cache. */
		if (phpbb.history.isSupported("state")) {
			$(window).on("unload", function() {
				var currentState = history.state, d = new Date();
				if (currentState !== null && currentState.replaced) {
					phpbb.history.replaceUrl(window.location.href + '&ajax_time=' + d.getTime(), document.title,
						currentState
					);
				}
			});

			var currentState = history.state;
			if (currentState !== null) {
				phpbb.history.replaceUrl(window.location.href.replace(/&ajax_time=\d*/i, ''), document.title,
					currentState
				);
			} else {
				phpbb.history.replaceUrl(window.location.href, document.title, {
					url: window.location.href,
					title: document.title
				});
			}
		}
	}

	/* Add Ajax functionality for the pagination. */
	quickreply.style.initPagination();

	/* Save message when navigating the topic. */
	var restoredMessage = $(quickreply.editor.messageStorage).html();
	if (restoredMessage !== '') {
		$(quickreply.editor.textareaSelector).val(restoredMessage);
	}

	/* Save message for the full reply form. */
	quickreply.style.setPostReplyHandler();

	if (quickreply.settings.ajaxSubmit) {
		phpbb.addAjaxCallback('qr_ajax_submit', quickreply.ajax.submitCallback);
	}

	/********************************/
	/* Classes and global functions */
	/********************************/
	function initGlobalFunctions() {
		/**
		 * Scrolls the page to the target element.
		 *
		 * @param {jQuery}        target      Element that we need to scroll to
		 * @param {string|jQuery} [container] Container element that needs scrolling (default: 'html,body')
		 */
		quickreply.functions.softScroll = function(target, container) {
			if (target.length) {
				container = container || 'html,body';
				$(container).animate({
					scrollTop: target.offset().top
				}, qrSlideInterval);
			}
		};

		/**
		 * Shows an alert with the specified message and sets a timeout.
		 *
		 * @param {string} title Title of the alert.
		 * @param {string} text  Text of the alert.
		 */
		quickreply.functions.alert = function(title, text) {
			var alert = phpbb.alert(title, text);
			qrAlertTimer = setTimeout(function() {
				$('#darkenwrapper').fadeOut(phpbb.alertTime, function() {
					alert.hide();
				});
			}, 5000);
		};

		/**
		 * Clear loading alert timeout
		 */
		quickreply.functions.clearLoadingTimeout = function() {
			if (qrAlertTimer !== null) {
				clearTimeout(qrAlertTimer);
				qrAlertTimer = null;
			}
			phpbb.clearLoadingTimeout();
		};

		/**
		 * The function for handling Ajax requests.
		 *
		 * @param {string}   url               Requested URL.
		 * @param {object}   [request_data]    Optional object with data parameters.
		 * @param {function} [result_function] The function to be called after successful result.
		 * @param {boolean}  [scroll_to_last]  Whether we need to scroll to the last post.
		 *
		 * @deprecated 1.1.0 - to be removed in 2.0.0
		 */
		quickreply.functions.qr_ajax_reload = function(url, request_data, result_function, scroll_to_last) {
			quickreply.ajaxReload.start(url, request_data, {
				scroll: (scroll_to_last) ? 'last' : '',
				callback: (typeof result_function === 'function') ? result_function : null
			});
		};

		/**
		 * pageJump function for QuickReply.
		 *
		 * @param {jQuery} item
		 */
		quickreply.functions.pageJump = function(item) {
			var page = parseInt(item.val(), 10);

			if (page !== null && !isNaN(page) && page === Math.floor(page) && page > 0) {
				var perPage = item.attr('data-per-page'),
					baseUrl = item.attr('data-base-url'),
					startName = item.attr('data-start-name');

				if (baseUrl.indexOf('?') === -1) {
					quickreply.ajaxReload.loadPage(baseUrl + '?' + startName + '=' + ((page - 1) * perPage));
				} else {
					quickreply.ajaxReload.loadPage(baseUrl.replace(/&amp;/g, '&') +
						'&' + startName + '=' + ((page - 1) * perPage));
				}
			}
		};

		/**
		 * pageJump function for QuickReply when SEO plugin is enabled.
		 *
		 * @param {jQuery} item
		 */
		quickreply.functions.seoPageJump = function(item) {
			var page = parseInt(item.val(), 10);

			if (page !== null && !isNaN(page) && page === Math.floor(page) && page > 0) {
				var per_page = item.attr('data-per-page'),
					base_url = item.attr('data-base-url'),
					start_name = item.attr('data-start-name'),
					anchor = '',
					anchor_parts = base_url.split('#');

				if (anchor_parts[1]) {
					base_url = anchor_parts[0];
					anchor = '#' + anchor_parts[1];
				}

				phpbb_seo.page = (page - 1) * per_page;

				if (phpbb_seo.page > 0) {
					var phpEXtest = false;

					if (start_name !== 'start' || base_url.indexOf('?') >= 0 ||
						(phpEXtest = base_url.match("/\." + phpbb_seo.phpEX + "$/i"))) {
						quickreply.ajaxReload.loadPage(base_url.replace(/&amp;/g, '&') +
							(phpEXtest ? '?' : '&') + start_name + '=' + phpbb_seo.page + anchor);
					} else {
						var ext = base_url.match(/\.[a-z0-9]+$/i);

						if (ext) {
							// location.ext => location-xx.ext
							quickreply.ajaxReload.loadPage(base_url.replace(/\.[a-z0-9]+$/i, '') +
								phpbb_seo.delim_start + phpbb_seo.page + ext + anchor);
						} else {
							// location and location/ to location/pagexx.html
							var slash = base_url.match(/\/$/) ? '' : '/';
							quickreply.ajaxReload.loadPage(base_url + slash +
								phpbb_seo.static_pagination + phpbb_seo.page + phpbb_seo.ext_pagination + anchor);
						}
					}
				} else {
					quickreply.ajaxReload.loadPage(base_url + anchor);
				}
			}
		};
	}

	/**
	 * Handles dropdowns for the specified container.
	 *
	 * @param {jQuery} $container
	 */
	function handleDrops($container) {
		/**
		 * Dropdowns
		 */
		$container.find('.dropdown-container').each(function() {
			var $this = $(this),
				$trigger = $this.find('.dropdown-trigger:first'),
				$contents = $this.find('.dropdown'),
				options = {
					direction: 'auto',
					verticalDirection: 'auto'
				},
				data;

			if (!$trigger.length) {
				data = $this.attr('data-dropdown-trigger');
				$trigger = data ? $this.children(data) : $this.children('a:first');
			}

			if (!$contents.length) {
				data = $this.attr('data-dropdown-contents');
				$contents = data ? $this.children(data) : $this.children('div:first');
			}

			if (!$trigger.length || !$contents.length) {
				return;
			}

			if ($this.hasClass('dropdown-up')) {
				options.verticalDirection = 'up';
			}
			if ($this.hasClass('dropdown-down')) {
				options.verticalDirection = 'down';
			}
			if ($this.hasClass('dropdown-left')) {
				options.direction = 'left';
			}
			if ($this.hasClass('dropdown-right')) {
				options.direction = 'right';
			}

			phpbb.registerDropdown($trigger, $contents, options);
		});
	}

	/**
	 * Handles responsive links for the specified container.
	 *
	 * @param {jQuery} $container
	 */
	function qrResponsiveLinks($container) {
		/**
		 * Responsive link lists
		 */
		var selector = '.linklist:not(.navlinks, [data-skip-responsive]),' +
			'.postbody .post-buttons:not([data-skip-responsive])';
		$container.find(selector).each(function() {
			var $this = $(this),
				filterSkip = '.breadcrumbs, [data-skip-responsive]',
				filterLast = '.edit-icon, .quote-icon, [data-last-responsive]',
				$linksAll = $this.children(),
				$linksNotSkip = $linksAll.not(filterSkip), // All items that can potentially be hidden
				$linksFirst = $linksNotSkip.not(filterLast), // The items that will be hidden first
				$linksLast = $linksNotSkip.filter(filterLast), // The items that will be hidden last
				persistent = $this.attr('id') === 'nav-main', // Does this list already have a menu (such as quick-links)?
				html = '<li class="responsive-menu hidden"><a href="javascript:void(0);" class="responsive-menu-link">&nbsp;</a><div class="dropdown hidden"><div class="pointer"><div class="pointer-inner" /></div><ul class="dropdown-contents" /></div></li>',
				slack = 3; // Vertical slack space (in pixels). Determines how sensitive the script is in determining whether a line-break has occured.

			// Add a hidden drop-down menu to each links list (except those that already have one)
			if (!persistent) {
				if ($linksNotSkip.is('.rightside')) {
					$linksNotSkip.filter('.rightside:first').before(html);
					$this.children('.responsive-menu').addClass('rightside');
				} else {
					$this.append(html);
				}
			}

			// Set some object references and initial states
			var $menu = $this.children('.responsive-menu'),
				$menuContents = $menu.find('.dropdown-contents'),
				persistentContent = $menuContents.find('li:not(.separator)').length,
				lastWidth = false,
				compact = false,
				responsive1 = false,
				responsive2 = false,
				copied1 = false,
				copied2 = false,
				maxHeight = 0;

			// Find the tallest element in the list (we assume that all elements are roughly the same height)
			$linksAll.each(function() {
				if (!$(this).height()) {
					return;
				}
				maxHeight = Math.max(maxHeight, $(this).outerHeight(true));
			});
			if (maxHeight < 1) {
				return; // Shouldn't be possible, but just in case, abort
			} else {
				maxHeight = maxHeight + slack;
			}

			function check() {
				var width = $body.width();
				// We can't make it any smaller than this, so just skip
				if (responsive2 && compact && (width <= lastWidth)) {
					return;
				}
				lastWidth = width;

				// Reset responsive and compact layout
				if (responsive1 || responsive2) {
					$linksNotSkip.removeClass('hidden');
					$menuContents.children('.clone').addClass('hidden');
					responsive1 = responsive2 = false;
				}
				if (compact) {
					$this.removeClass('compact');
					compact = false;
				}

				// Unhide the quick-links menu if it has "persistent" content
				if (persistent && persistentContent) {
					$menu.removeClass('hidden');
				} else {
					$menu.addClass('hidden');
				}

				// Nothing to resize if block's height is not bigger than tallest element's height
				if ($this.height() <= maxHeight) {
					return;
				}

				// STEP 1: Compact
				if (!compact) {
					$this.addClass('compact');
					compact = true;
				}
				if ($this.height() <= maxHeight) {
					return;
				}

				// STEP 2: First responsive set - compact
				if (compact) {
					$this.removeClass('compact');
					compact = false;
				}
				// Copy the list items to the dropdown
				if (!copied1) {
					var $clones1 = $linksFirst.clone();
					$menuContents.prepend($clones1.addClass('clone clone-first').removeClass('leftside rightside'));

					if ($this.hasClass('post-buttons')) {
						$('.button', $menuContents).removeClass('button icon-button');
						$('.responsive-menu-link', $menu).addClass('button icon-button').prepend('<span></span>');
					}
					copied1 = true;
				}
				if (!responsive1) {
					$linksFirst.addClass('hidden');
					responsive1 = true;
					$menuContents.children('.clone-first').removeClass('hidden');
					$menu.removeClass('hidden');
				}
				if ($this.height() <= maxHeight) {
					return;
				}

				// STEP 3: First responsive set + compact
				if (!compact) {
					$this.addClass('compact');
					compact = true;
				}
				if ($this.height() <= maxHeight) {
					return;
				}

				// STEP 4: Last responsive set - compact
				if (!$linksLast.length) {
					return; // No other links to hide, can't do more
				}
				if (compact) {
					$this.removeClass('compact');
					compact = false;
				}
				// Copy the list items to the dropdown
				if (!copied2) {
					var $clones2 = $linksLast.clone();
					$menuContents.prepend($clones2.addClass('clone clone-last').removeClass('leftside rightside'));
					copied2 = true;
				}
				if (!responsive2) {
					$linksLast.addClass('hidden');
					responsive2 = true;
					$menuContents.children('.clone-last').removeClass('hidden');
				}
				if ($this.height() <= maxHeight) {
					return;
				}

				// STEP 5: Last responsive set + compact
				if (!compact) {
					$this.addClass('compact');
					compact = true;
				}
			}

			if (!persistent) {
				phpbb.registerDropdown($menu.find('a.responsive-menu-link'), $menu.find('.dropdown'), false);
			}

			// If there are any images in the links list, run the check again after they have loaded
			$linksAll.find('img').each(function() {
				$(this).load(function() {
					check();
				});
			});

			check();
			$(window).resize(check);
		});
	}

	function QrJqueryElements() {
		this.qrPosts = $('#qr_posts');
		this.mainForm = $(quickreply.editor.mainForm);
		this.messageBox = this.mainForm.find('#message-box');
		this.textarea = $(quickreply.editor.textareaSelector);
	}

	function Loading() {
		var self = this, waitTimer = null, stopTimer = null;

		/**
		 * Clears loading timeouts.
		 */
		function clearTimeouts() {
			if (waitTimer) {
				clearTimeout(waitTimer);
				waitTimer = null;
			}
			if (stopTimer) {
				clearTimeout(stopTimer);
				stopTimer = null;
			}
		}

		/**
		 * Restarts loading timeouts.
		 */
		function setTimeouts() {
			clearTimeouts();
			waitTimer = setTimeout(function() {
				self.setExplain(quickreply.language.loading.WAIT, false, true);
			}, 10000);
			stopTimer = setTimeout(function() {
				self.stop(true);
				quickreply.ajax.error($('#darkenwrapper').attr('data-ajax-error-text-timeout'));
			}, 20000);
		}

		/**
		 * Shows loading indicator.
		 */
		this.start = function() {
			var $dark = $('#darkenwrapper'), $loadingText = $('#qr_loading_text');
			if (!$loadingText.is(':visible')) {
				quickreply.functions.clearLoadingTimeout();
				$('#phpbb_alert').hide();
				$dark.off('click');
				if (!$dark.is(':visible')) {
					$dark.fadeIn(phpbb.alertTime);
				}
				$loadingText.fadeIn(phpbb.alertTime);
			}
			setTimeouts();
		};

		/**
		 * Sets loading explanation text to inform the user about current state.
		 *
		 * @param {string}  text               HTML string with informative text
		 * @param {boolean} [showCancelButton] Whether we should display cancel button instead of setting new timeouts
		 * @param {boolean} [skipTimeouts]     Whether we should not refresh the timeouts
		 */
		this.setExplain = function(text, showCancelButton, skipTimeouts) {
			$('#qr_loading_cancel').fadeOut(phpbb.alertTime);
			$('#qr_loading_explain').fadeOut(phpbb.alertTime, function() {
				$(this).html(text).fadeIn(phpbb.alertTime);
				if (showCancelButton) {
					$('#qr_loading_cancel').fadeIn(phpbb.alertTime);
				}
			});
			if (showCancelButton) {
				clearTimeouts();
			} else if (!skipTimeouts) {
				setTimeouts();
			}
		};

		/**
		 * Hides loading indicator.
		 *
		 * @param {boolean} [keepDark] Whether we should not hide the dark
		 */
		this.stop = function(keepDark) {
			var $dark = $('#darkenwrapper'), $loadingText = $('#qr_loading_text');
			$loadingText.fadeOut(phpbb.alertTime);
			$('#qr_loading_explain').fadeOut(phpbb.alertTime, function() {
				$(this).html('');
				$('#qr_loading_cancel').hide();
			});
			if (!keepDark) {
				$dark.fadeOut(phpbb.alertTime);
			}
			clearTimeouts();
		};

		/**
		 * Keeps loading indicator shown.
		 */
		this.proceed = function() {
			$('#darkenwrapper').stop().fadeIn(phpbb.alertTime);
		};

		/**
		 * Initializes loading container.
		 */
		this.init = function() {
			$('#page-footer').append('<div id="qr_loading_text"><i class="fa fa-refresh fa-spin"></i><span>' +
				quickreply.language.loading.text + '</span><div id="qr_loading_explain"></div>' +
				'<div id="qr_loading_cancel"><span>' + quickreply.language.CANCEL_SUBMISSION + '</span></div></div>');

			$('#qr_loading_cancel').on('click', function() {
				quickreply.$.mainForm.off('ajax_submit_ready').trigger('ajax_submit_cancel');
				self.stop();
			});

			$(document).ajaxError(function() {
				var $loadingText = $('#qr_loading_text');
				if ($loadingText.is(':visible')) {
					$loadingText.fadeOut(phpbb.alertTime);
				}
			});
		};
	}

	function QrForm() {
		var self = this,
			smileyBoxDisplayed = false,
			hasAttachments = false,
			formHeight = null,
			formWidth = null;

		self.$ = $(quickreply.editor.mainForm);

		/**
		 * Checks whether quick reply form is in the specified mode.
		 *
		 * @param {string} type Form mode
		 * @returns {boolean}
		 */
		this.is = function(type) {
			return self.$.hasClass('qr_' + type + '_form');
		};

		/**
		 * Returns whether the user entered a message or added attachments.
		 *
		 * @returns {boolean}
		 */
		this.hasReply = function() {
			return !!($(quickreply.editor.textareaSelector).val() ||
			$('#file-list').children().not('#attach-row-tpl').length);
		};

		/**
		 * Returns whether plupload uploader object is available.
		 *
		 * @returns {boolean}
		 */
		function uploaderIsAvailable() {
			return phpbb.plupload && phpbb.plupload.uploader;
		}

		/**
		 * Shows/hides attachments existence notice with animation.
		 * The notice will be shown only when the form has at least one attachment.
		 *
		 * @param {string}        [visibility]       By default the notice will be shown,
		 *                                           'show' - to show the notice immediately, ignores animationOptions
		 *                                           'hide' - to hide the notice,
		 *                                           'toggle' - to toggle its visibility
		 * @param {object|number} [animationOptions] Custom animation options
		 */
		function setAttachNotice(visibility, animationOptions) {
			if (!hasAttachments) {
				return;
			}
			var slideFunction = (visibility === 'show') ? 'show' : (
				(visibility === 'toggle') ? 'slideToggle' : (
				(visibility === 'hide') ? 'slideUp' : 'slideDown'
			)), options = (visibility === 'show') ? null : ((animationOptions) ? animationOptions : qrSlideInterval);
			$('#qr_attach_notice').finish()[slideFunction](options);
		}

		/**
		 * Binds events to show/hide attachments existence notice.
		 */
		function initAttachNotice() {
			$(document).ready(function() {
				if (!uploaderIsAvailable()) {
					// Workaround for phpBB < 3.1.5
					return;
				}

				// Workaround for non-ajax form save feature.
				phpbb.plupload.uploader.bind('PostInit', function() {
					if ($('#file-list').children().length) {
						hasAttachments = true;
						$('#qr_attach_notice').show();
					}
				});

				// Workaround for drag-and-drop feature.
				phpbb.plupload.uploader.bind('FilesAdded', function() {
					setAttachNotice('hide');
					hasAttachments = true;
					if (!$(quickreply.editor.attachPanel).is(':visible')) {
						$(quickreply.editor.attachPanel).finish().slideDown(qrSlideInterval);
					}
				});

				// Workaround for file deletions (attach box can be hidden
				// if a user closes it before the file is deleted).
				phpbb.plupload.uploader.bind('FilesRemoved', function() {
					if (!phpbb.plupload.uploader.files.length) {
						hasAttachments = false;
						$('#qr_attach_notice').finish().slideUp(qrSlideInterval);
					}
				});
			});
		}

		/**
		 * Workaround for "Add files" button position calculation.
		 */
		function bindRefreshUploader() {
			if (uploaderIsAvailable()) {
				$('.qr_attach_button').one('click', function() {
					phpbb.plupload.uploader.refresh();
				});
			} else {
				// Workaround for phpBB < 3.1.5
				$('.qr_attach_button').attr('data-subpanel', 'attach-panel');
			}
		}

		/**
		 * Adds the button trigger for fixed quick reply form.
		 */
		function addToggleButton() {
			var $toggle = $('<div id="qr_show_fixed_form"><i class="fa fa-fw fa-comment"></i><span>' +
				quickreply.language.QUICKREPLY + '</span></div>').click(function() {
				quickreply.style.showQuickReplyForm();
				exitHidden();
			}).insertAfter(self.$);
			if (quickreply.settings.fixEmptyForm || self.hasReply()) {
				$toggle.hide();
			}
		}

		/**
		 * Prepares quick reply form.
		 */
		this.init = function() {
			quickreply.style.setTextareaId();

			if (quickreply.settings.formType > 0) {
				self.initFixed();
			}

			setDelayForAttachments();

			// Prevent topic_review false positive - we use our own function for checking new posts.
			self.$.find('input[name=topic_cur_post_id]').val(0);
		};

		/**
		 * Displays quick reply form if it is hidden.
		 */
		this.show = function() {
			quickreply.style.showQuickReplyForm();
			exitHidden();
		};

		/**
		 * Update hidden fields of quick reply form.
		 *
		 * @param {string} qrFields HTML string with new fields.
		 */
		this.updateFields = function(qrFields) {
			var formSubmitButtons = self.$.children().children().children('.submit-buttons');
			formSubmitButtons.children(':not(input[type="submit"])').remove();
			formSubmitButtons.prepend(qrFields);

			// Prevent topic_review false positive - we use our own function for checking new posts.
			self.$.find('input[name=topic_cur_post_id]').val(0);
		};

		/**
		 * Shows loading indicator and blocks form submission until all attachments have been uploaded.
		 */
		function setDelayForAttachments() {
			self.$.find(':submit').click(function(e) {
				if (self.checkAttachments()) {
					e.preventDefault();

					quickreply.loading.start();
					quickreply.loading.setExplain(quickreply.language.loading.ATTACHMENTS, true);

					self.$.on('ajax_submit_ready', function() {
						self.$.submit();
					});
				}
			});
		}

		/**
		 * Hides colour palette if it is visible.
		 */
		function hideColourPalette() {
			if ($('#colour_palette').is(':visible')) {
				if (quickreply.plugins.abbc3) {
					$('#abbc3_bbpalette').click();
				} else {
					$('#bbpalette').click();
				}
			}
		}

		/**
		 * Sets the height of smiley box equal to the height of message box.
		 */
		function fixSmileyHeight() {
			$('#smiley-box').css('height', quickreply.$.messageBox.height());
		}

		/**
		 * Opens smiley box.
		 */
		function openSmileyBox() {
			setSmileyBox();
			if (isMobile()) {
				$('#smiley-box').slideDown(qrSlideInterval);
			} else {
				$('#smiley-box').stop().animate({
					right: '0'
				}, 500);
			}
			self.$.addClass('with_smileys');
			smileyBoxDisplayed = true;
		}

		/**
		 * Closes smiley box.
		 */
		function closeSmileyBox() {
			if (isMobile()) {
				$('#smiley-box').slideUp(qrSlideInterval);
			} else {
				$('#smiley-box').stop().animate({
					right: '-1000px'
				}, 500);
				self.$.off('fullscreen.quickreply.smilies');
				$(quickreply.$.textarea).off('mousemove.quickreply.smilies');
			}
			self.$.removeClass('with_smileys');
			smileyBoxDisplayed = false;
		}

		/**
		 * Recalculates smiley box position (i.e. after window resize).
		 */
		function setSmileyBox() {
			if (self.is('extended')) {
				return;
			}
			if (isMobile()) {
				$('#smiley-box').css('height', '')[(smileyBoxDisplayed) ? 'show' : 'hide']();
				self.$.off('fullscreen.quickreply.smilies');
				$(quickreply.$.textarea).off('mousemove.quickreply.smilies');
			} else {
				fixSmileyHeight();
				$('#smiley-box').css('display', '').css('right', (smileyBoxDisplayed) ? '0' : '-1000px');
				self.$.on('fullscreen.quickreply.smilies', fixSmileyHeight);
				$(quickreply.$.textarea).on('mousemove.quickreply.smilies', fixSmileyHeight);
			}
		}

		/**
		 * Applies compact style to quick reply form.
		 *
		 * @param {boolean} [immediate] Whether we need to skip animation
		 */
		this.setCompact = function(immediate) {
			hideColourPalette();
			closeSmileyBox();

			var editorElements = quickreply.style.formEditorElements(quickreply.settings.fixEmptyForm).finish();
			if (immediate) {
				editorElements.hide();
			} else {
				editorElements.slideUp(qrSlideInterval);
			}

			setAttachNotice((immediate) ? 'show' : '');

			if (quickreply.settings.fixEmptyForm) {
				$('#qr_text_action_box, .qr_attach_button, .qr_smiley_button').hide();
				self.$.addClass('qr_compact_form');
				if (self.is('hidden')) {
					$('#qr_show_fixed_form').show();
				}

				if (!immediate) {
					self.setCheckExtendedInterval();
				} else {
					checkExtended();
				}
			} else {
				setHidden(immediate);
			}
		};

		/**
		 * Attaches click event to the specified button.
		 *
		 * @param {string|jQuery} trigger    Trigger element
		 * @param {string|jQuery} target     Target element
		 * @param {function}      [callback] Optional callback function
		 */
		function addButtonTrigger(trigger, target, callback) {
			$(trigger).click(function() {
				$(target).finish().slideToggle(qrSlideInterval);
				if (typeof callback === 'function') {
					callback();
				}
			});
		}

		/**
		 * Removes compact style from fixed form.
		 */
		this.setFixed = function() {
			if (!self.is('compact')) {
				return;
			}
			self.$.not('.qr_fullscreen_form')
				.find('.qr_smiley_button').delay(100).fadeIn().end()
				.find('.qr_attach_button').delay(200).fadeIn().end()
				.removeClass('qr_compact_form').css('overflow', 'hidden')
				.find('#qr_text_action_box, #qr_captcha_container, .submit-buttons')
				.finish().slideDown(qrSlideInterval).promise().done(function() {
				self.$.css('overflow', '');
			});
		};

		/**
		 * Sets the form width equal to the width of page-body container.
		 */
		function setFormWidth() {
			var $pageBody = $('#page-body');
			if (!$pageBody.length || self.is('extended') || self.is('fullscreen')) {
				return;
			}
			self.$.css('width', $pageBody.width());
		}

		/**
		 * Returns whether the page is displayed in mobile view.
		 *
		 * @returns {boolean}
		 */
		function isMobile() {
			return $(window).width() <= 700;
		}

		/**
		 * Initializes fixed form mode.
		 */
		this.initFixed = function() {
			$(quickreply.editor.textareaSelector).attr('placeholder', quickreply.language.TYPE_REPLY);

			quickreply.style.showQuickReplyForm();

			// Switch off Quick Reply Toggle Plugin
			$('#reprap').remove();

			self.$.finish();

			// Set up placeholder before making quick reply form fixed.
			$('<div id="qr_form_placeholder" />').css('height', self.$.height()).insertAfter(self.$);

			self.$.addClass('qr_fixed_form');

			quickreply.style.setAdditionalElements();

			// If there are no additional elements, hide the button.
			if (!$('.additional-element').length) {
				$('.qr_more_actions_button').hide();
			}

			if (quickreply.settings.attachBox) {
				initAttachNotice();
				bindRefreshUploader();
			}

			$('#qr_action_box').prependTo('#message-box');

			addToggleButton();
			if (!self.hasReply()) {
				self.$.addClass('no_transition');
				self.setCompact(true); // Should be set AFTER the toggle button has been added to the page.
				self.$.removeClass('no_transition');
			}

			// Now we can make it fixed for further transitions.
			$(quickreply.editor.textareaSelector).addClass('qr_fixed_textarea');

			// Add events.
			if (quickreply.plugins.abbc3) {
				addButtonTrigger(
					'.qr_bbcode_button',
					[
						'#abbc3_buttons',
						'#format-buttons:has(#register-and-translit)',
						'#register-and-translit:not(#format-buttons #register-and-translit)'
					].join(', '),
					hideColourPalette
				);
				$('#format-buttons').children(':not(#register-and-translit)').hide();
			} else {
				addButtonTrigger(
					'.qr_bbcode_button',
					'#format-buttons, #register-and-translit:not(#format-buttons #register-and-translit)',
					hideColourPalette
				);
			}
			addButtonTrigger('.qr_attach_button', quickreply.editor.attachPanel, function() {
				setAttachNotice('toggle');
				self.setFixed();
				quickreply.functions.softScroll($(quickreply.editor.attachPanel), self.$);
			});
			$('#qr_attach_notice').click(function() {
				$('.qr_attach_button:first').click();
			});
			addButtonTrigger('.qr_more_actions_button', '.qr_fixed_form .additional-element');

			$(quickreply.editor.textareaSelector).focus(function() {
				self.setFixed();
			});

			// Hide active dropdowns when click event happens outside
			$body.on('mousedown.quickreply.form', function(e) {
				var $parents = $(e.target).parents();
				if (!$parents.is(quickreply.editor.mainForm) &&
					!$(quickreply.editor.textareaSelector).val() &&
					!self.is('fullscreen') &&
					!self.is('extended')) {
					self.setCompact();
				}
			});

			$('.qr_form_hide_button').click(function() {
				setHidden();
			});

			$('.qr_smiley_button').click(function() {
				if (!smileyBoxDisplayed) {
					openSmileyBox();
				} else {
					closeSmileyBox();
				}
			});

			$('.qr_fullscreen_button').click(function() {
				if (self.is('fullscreen')) {
					self.exitFullscreen();
				} else {
					self.enterFullscreen();
				}
			});

			$(window).on('scroll resize', checkExtended);
			$(window).on('load', function() {
				// interval is needed because of form animation
				self.setCheckExtendedInterval(5000);
			});
			quickreply.$.qrPosts.click(self.setCheckExtendedInterval); // for spoilers and other toggles

			$(window).resize(setFormWidth);
			$(document).ready(setFormWidth);

			$(window).resize(setSmileyBox);
		};

		/**
		 * Checks whether we need to change the extended form state during the specified time period.
		 *
		 * @param {number} duration Time period for extension checker to work
		 * @param {number} interval Interval between each check
		 */
		function checkExtendedStateChange(duration, interval) {
			duration -= interval;
			checkExtended();

			if (duration > 0) {
				setTimeout(function() {
					checkExtendedStateChange(duration, interval);
				}, interval);
			}
		}

		/**
		 * Initializes the extended form state change checker function.
		 *
		 * @param {number} [duration] Time period for extension checker to work (1600 ms by default)
		 * @param {number} [interval] Interval between each check (200 ms by default)
		 */
		this.setCheckExtendedInterval = function(duration, interval) {
			duration = duration || 1600;
			interval = interval || 200;
			checkExtendedStateChange(duration, interval);
		};

		/**
		 * Initializes or exits extended standard form mode if needed.
		 */
		function checkExtended() {
			if (self.is('fullscreen')) {
				return;
			}

			var $window = $(window),
				$previous = self.$.prevAll(":visible:first"),
				scrollBottom = $window.scrollTop() + $window.height() - quickreply.style.getPageBottomValue(),
				formOffset = $previous.offset().top + $previous.height(),
				formHeight = self.is('hidden') ? 0 : self.$.height();

			if (scrollBottom - formHeight >= formOffset && !self.is('extended')) {
				setExtended();
			} else if (scrollBottom - (self.is('hidden') ? 0 : 48) < formOffset && self.is('extended')) {
				exitExtended();
			}
		}

		/**
		 * Sets extended standard form mode.
		 */
		function setExtended() {
			$('#qr_form_placeholder').remove();

			// Opens attachments form and refreshes the uploader: triggers necessary event.
			$('.qr_attach_button:first').click();

			self.$.finish().removeClass('qr_fixed_form qr_compact_form').addClass('no_transition qr_extended_form')
				.css('width', '');
			$(quickreply.editor.textareaSelector).removeClass('qr_fixed_textarea');

			$('#qr_action_box, #qr_text_action_box, #qr_show_fixed_form').hide();

			closeSmileyBox();
			$('#smiley-box').finish().css('left', '').css('right', '').css('height', '').css('display', '');

			quickreply.style.formEditorElements(true).finish().show();
			setAttachNotice('hide');

			setTimeout(function() {
				self.$.removeClass('no_transition');
			}, 0);
		}

		/**
		 * Exits extended standard form mode and sets fixed form mode.
		 */
		function exitExtended() {
			var placeholderHeight = self.$.height();

			quickreply.$.textarea.addClass('qr_fixed_textarea');
			self.$.finish().addClass('no_transition qr_fixed_form').removeClass('qr_extended_form');

			$('<div id="qr_form_placeholder" />').css('height', placeholderHeight).insertAfter(self.$);

			$('#qr_action_box, #qr_text_action_box, #qr_captcha_container, .submit-buttons').show();

			if (quickreply.$.textarea.val() || quickreply.$.textarea.is(':focus')) {
				quickreply.style.showQuickReplyForm();
				quickreply.style.formEditorElements().hide();
				setAttachNotice('show');
				if (self.is('hidden')) {
					$('#qr_show_fixed_form').show();
				} else {
					exitHidden(true);
				}
			} else {
				self.setCompact(true);
			}

			setFormWidth();
			setTimeout(function() {
				self.$.removeClass('no_transition');
			}, 0);
		}

		/**
		 * Hides fixed quick reply form in the bottom of the page.
		 *
		 * @param {boolean} [immediate] Whether we need to skip animation
		 */
		function setHidden(immediate) {
			var slideInterval = (immediate) ? 0 : qrSlideInterval;
			self.$.finish().animate({
				bottom: -self.$.height() + 'px'
			}, slideInterval).addClass('qr_hidden_form');
			$('#qr_show_fixed_form').show();
			checkExtended();
		}


		/**
		 * Restores fixed quick reply form from the bottom of the page.
		 *
		 * @param {boolean} [immediate] Whether we need to skip animation
		 */
		function exitHidden(immediate) {
			var slideInterval = (immediate) ? 0 : qrSlideInterval;
			self.$.finish().animate({
				bottom: quickreply.style.getPageBottomValue()
			}, slideInterval).removeClass('qr_hidden_form');
			$('#qr_show_fixed_form').hide();
			quickreply.$.textarea.focus();
		}

		/**
		 * Exits fullscreen mode and returns quick reply form to the previous mode.
		 */
		this.exitFullscreen = function() {
			$(document).off('keydown.quickreply.fullscreen');
			self.$.off('ajax_submit_success.quickreply.fullscreen');

			$body.add('html').css('overflow-y', '');
			hideColourPalette();

			$('.qr_fixed_form').animate({
				'maxHeight': '50%',
				'height': formHeight,
				'width': formWidth
			}, qrSlideInterval, function() {
				$(this).css('height', 'auto');
				setFormWidth();
			}).removeClass('qr_fullscreen_form');

			if (!self.is('compact')) {
				quickreply.style.formEditorElements().slideUp(qrSlideInterval);
				$('#qr_text_action_box, .qr_attach_button').show();
				setAttachNotice();
			} else {
				self.setCompact();
			}

			$('.qr_fullscreen_button').toggleClass('fa-arrows-alt fa-times')
				.attr('title', quickreply.language.FULLSCREEN);
			$(quickreply.editor.textareaSelector).addClass('qr_fixed_textarea');

			$('#preview').insertBefore(self.$);
			self.$.trigger('fullscreen-exit');
		};

		/**
		 * Displays quick reply form in fullscreen mode.
		 */
		function setFullscreen() {
			// Store current form height and width.
			formHeight = self.$.height();
			formWidth = self.$.width();

			$body.add('html').css('overflow-y', 'hidden');
			quickreply.style.formEditorElements(true).slideDown(qrSlideInterval);
			setAttachNotice('hide');
			$('#qr_text_action_box, .qr_attach_button').hide();

			$('.qr_fullscreen_button').toggleClass('fa-arrows-alt fa-times')
				.attr('title', quickreply.language.FULLSCREEN_EXIT);
			$(quickreply.editor.textareaSelector).removeClass('qr_fixed_textarea');

			$(document).on('keydown.quickreply.fullscreen', function(e) {
				if (e.keyCode === 27) {
					self.exitFullscreen();
					e.preventDefault();
					e.stopPropagation();
				}
			});

			self.$.on('ajax_submit_success.quickreply.fullscreen', self.exitFullscreen);

			self.$.trigger('fullscreen-before');
			$('.qr_fixed_form').addClass('qr_fullscreen_form').animate({
				'maxHeight': '100%',
				'height': '100%',
				'width': '100%'
			}, qrSlideInterval, function() {
				$('#preview').prependTo(self.$);
				self.$.trigger('fullscreen');
			});
		}

		/**
		 * Opens quick reply form in fullscreen mode.
		 */
		this.enterFullscreen = function() {
			setAttachNotice();
			quickreply.style.formEditorElements().slideUp(qrSlideInterval).promise().done(setFullscreen);
		};

		/**
		 * Checks whether some attachments are being uploaded.
		 * Workaround function for phpBB < 3.1.5.
		 */
		function attachmentsChecker() {
			if (!$('.file-progress:visible').length) {
				self.$.trigger('ajax_submit_ready');
			}
		}

		/**
		 * Checks whether some attachments are being uploaded.
		 * Sets an event trigger when the upload is completed.
		 *
		 * @returns {boolean}
		 */
		this.checkAttachments = function() {
			if (!quickreply.settings.attachBox) {
				return false;
			}

			self.$.off('ajax_submit_ready ajax_submit_cancel');

			if (uploaderIsAvailable()) {
				if (phpbb.plupload.uploader.state !== plupload.STOPPED) {
					phpbb.plupload.uploader.bind('UploadComplete', function() {
						self.$.trigger('ajax_submit_ready');
					});
					return true;
				}
			} else {
				// Workaround for phpBB < 3.1.5
				if ($('.file-progress:visible').length) {
					var attachInterval = setInterval(attachmentsChecker, 500);
					self.$.on('ajax_submit_ready ajax_submit_cancel', function() {
						clearInterval(attachInterval);
					});
					return true;
				}
			}

			return false;
		};

		/**
		 * Clears quick reply form (e.g. after submission).
		 */
		this.refresh = function() {
			$('input[name="post"]').removeAttr('data-clicked');
			$(quickreply.editor.textareaSelector).val('').attr('style', 'height: 9em;');

			if ($('#preview').is(':visible')) {
				quickreply.preview.set(); // Hide preview.
			}

			hideColourPalette();

			if (quickreply.settings.attachBox) {
				$('#file-list-container').css('display', 'none');
				$('#file-list').empty();
				phpbb.plupload.clearParams();

				setAttachNotice('hide');
				hasAttachments = false;
			}

			if (quickreply.settings.allowedGuest) {
				quickreply.ajaxReload.start(document.location.href, {qr_captcha_refresh: 1});
			}
		};
	}

	function Preview() {
		/**
		 * Shows/hides the preview block.
		 * By default the block will be hidden if no options are specified.
		 *
		 * @param {object} [options] Optional array of options.
		 */
		this.set = function(options) {
			var ops = {
				display: 'none',
				title: '',
				content: '',
				attachments: false,
				removeAttachBox: true
			}, $preview = $('#preview');
			if (options) {
				ops = $.extend(ops, options);
			}
			$preview.finish().css('display', ops.display)
				.find('h3').html(ops.title).end()
				.find('.content').html(ops.content).end();
			if (quickreply.settings.attachBox) {
				if (ops.removeAttachBox) {
					$preview.find('dl.attachbox').remove();
				}
				if (ops.attachments) {
					$preview.find('.content').after(ops.attachments);
				}
			}
		};

		/**
		 * Initializes Ajax preview block.
		 */
		this.init = function() {
			quickreply.style.initPreview();
			$('#preview_close').click(function() {
				$('#preview').slideUp(qrSlideInterval);
				quickreply.form.setCheckExtendedInterval(qrSlideInterval);
			}).attr('title', quickreply.language.PREVIEW_CLOSE);
		};
	}

	function Ajax() {
		/**
		 * Initializes some parts of Ajax functionality.
		 */
		this.init = function() {
			if (quickreply.settings.ajaxSubmit) {
				quickreply.preview.init();

				quickreply.$.mainForm.attr('data-ajax', 'qr_ajax_submit').submit(function() {
					quickreply.loading.start();

					var action = $(this).attr('action'), urlHash = action.indexOf('#');
					if (urlHash > -1) {
						$(this).attr('action', action.substr(0, urlHash));
					}

					var $clickedButton = $(this).find('input[type="submit"][data-clicked]');

					// Fix for phpBB 3.1.9
					if (!$clickedButton.length) {
						$clickedButton = $(this).find('input[name="post"]').attr('data-clicked', 'true');
					}

					if ($('#qr_loading_explain').is(':empty')) {
						switch ($clickedButton.attr('name')) {
							case "preview":
								quickreply.loading.setExplain(quickreply.language.loading.PREVIEW);
								break;
							case "post":
								quickreply.loading.setExplain(quickreply.language.loading.SUBMITTING);
								break;
						}
					}
				}).attr('data-overlay', false);
			}
		};

		/**
		 * Adds Ajax functionality to the specified element.
		 *
		 * @param {jQuery} element
		 */
		this.add = function(element) {
			element.find('[data-ajax]').each(function() {
				var $this = $(this);
				var ajax = $this.attr('data-ajax');
				var filter = $this.attr('data-filter');

				if (ajax !== 'false') {
					var fn = (ajax !== 'true') ? ajax : null;
					filter = (filter !== undefined) ? phpbb.getFunctionByName(filter) : null;

					phpbb.ajaxify({
						selector: this,
						refresh: $this.attr('data-refresh') !== undefined,
						filter: filter,
						callback: fn
					});
				}
			});

			/**
			 * Make the display post links to use JS
			 */
			element.find('.display_post').click(function(e) {
				// Do not follow the link
				e.preventDefault();

				var postId = $(this).attr('data-post-id');
				$('#post_content' + postId).show();
				$('#profile' + postId).show();
				$('#post_hidden' + postId).hide();
			});

			handleDrops(element);
		};

		/**
		 * Shows an alert with an error message.
		 *
		 * @param {string} [text] Optional error description
		 */
		this.error = function(text) {
			phpbb.alert(
				quickreply.language.AJAX_ERROR_TITLE,
				quickreply.language.AJAX_ERROR + ((text) ? '<br />' + text : '')
			);
		};

		/**
		 * Returns the requestData object for Ajax callback function.
		 * Used when new messages have been added to the topic.
		 *
		 * @param {boolean} merged Whether the post has been merged
		 * @returns {object}
		 */
		function getReplyData(merged) {
			var replySetData = {qr_no_refresh: 1};
			if (merged) {
				$.extend(replySetData, {qr_get_current: 1});
			}
			return replySetData;
		}

		/**
		 * The callback function for handling results of Ajax submission.
		 *
		 * @param {object}  res                  Response object
		 * @param {string}  [res.qr_fields]      HTML string with updated fields
		 * @param {string}  [res.status]         Result status type
		 * @param {boolean} [res.merged]         Whether the submitted post has been merged
		 * @param {string}  [res.url]            URL for the next request
		 * @param {string}  [res.PREVIEW_TITLE]  HTML string for preview title
		 * @param {string}  [res.PREVIEW_TEXT]   HTML string for preview text
		 * @param {string}  [res.PREVIEW_ATTACH] HTML string for preview attachments container
		 * @param {string}  [res.MESSAGE_TITLE]  Information notice
		 * @param {boolean} [res.error]          Whether there were any errors
		 */
		this.submitCallback = function(res) {
			if (res.qr_fields) {
				quickreply.form.updateFields(res.qr_fields);
			}

			if (typeof res.MESSAGE_TITLE !== 'undefined') {
				quickreply.loading.stop(true);
			} else {
				quickreply.loading.proceed();
			}

			switch (res.status) {
				case "success":
					quickreply.loading.setExplain(quickreply.language.loading.SUBMITTED);
					quickreply.ajaxReload.start(
						res.url.replace(/&amp;/ig, '&'), getReplyData(res.merged), {
							scroll: 'last',
							callback: function() {
								$('#qr_postform').trigger('ajax_submit_success');
								quickreply.form.refresh();
							}
						}
					);
					break;

				case "new_posts":
					quickreply.loading.setExplain(quickreply.language.loading.NEW_POSTS);
					quickreply.ajaxReload.start(
						res.url.replace(/&amp;/ig, '&'), getReplyData(res.merged), {
							scroll: 'unread',
							callback: function() {
								quickreply.loading.proceed();
								quickreply.functions.alert(
									quickreply.language.INFORMATION, quickreply.language.POST_REVIEW
								);
								if (quickreply.settings.allowedGuest) {
									quickreply.ajaxReload.start(document.location.href, {qr_captcha_refresh: 1});
								}
							}
						}
					);
					break;

				case "preview":
					var $preview = $('#preview');
					quickreply.preview.set({
						display: 'block',
						title: res.PREVIEW_TITLE,
						content: res.PREVIEW_TEXT,
						attachments: res.PREVIEW_ATTACH
					});
					quickreply.loading.stop();
					if (quickreply.settings.enableScroll) {
						var $container = (quickreply.form.is('fullscreen')) ? quickreply.editor.mainForm : false;
						quickreply.functions.softScroll($preview, $container);
					}
					$('#qr_postform').trigger('ajax_submit_preview', [$preview]);
					break;

				case "no_approve":
					quickreply.form.refresh();
					break;

				case "outdated_form":
					quickreply.loading.setExplain(quickreply.language.loading.NEW_FORM_TOKEN);

					// data-clicked attribute is cleared below, but we need to click the same button after the timeout.
					var $clickedButton = $(quickreply.editor.mainForm).find('input[data-clicked="true"]');

					// The timeout is needed because phpBB checks the time difference for 'lastclick'.
					setTimeout(function() {
						// Send the message again with the updated form token.
						$clickedButton.click();
					}, 2000);
					break;

				default:
					if (!res.error) {
						quickreply.loading.stop();
					}
					if (quickreply.settings.allowedGuest) {
						quickreply.ajaxReload.start(document.location.href, {qr_captcha_refresh: 1});
					}
					// else quickreply.ajax.error();
					break;
			}
			/* Fix for phpBB 3.1.9 */
			$(quickreply.editor.mainForm).find('input[data-clicked]').removeAttr('data-clicked');
		};
	}

	function AjaxReload() {
		var dataObject = {}, params = {}, requestMethod = '';

		var self = this;

		self.url = '';

		/**
		 * Parses the modified page and processes the results.
		 *
		 * @param {jQuery}   elements   Newly added elements
		 * @param {function} [callback] Callback function (receives the element for scrolling)
		 */
		function showResponse(elements, callback) {
			var submitButtons = $('#qr_submit_buttons');
			quickreply.form.updateFields(submitButtons.html());

			// Work with history.
			if (qrReplaceHistory) {
				qrReplaceHistory = false;
				phpbb.history.replaceUrl(submitButtons.attr('data-page-url'), submitButtons.attr('data-page-title'), {
					url: self.url,
					title: submitButtons.attr('data-page-title'),
					replaced: true
				});
				document.title = submitButtons.attr('data-page-title');
			} else if (qrStopHistory) {
				qrStopHistory = false;
			} else {
				phpbb.history.pushUrl(submitButtons.attr('data-page-url'), submitButtons.attr('data-page-title'), {
					url: self.url,
					title: submitButtons.attr('data-page-title')
				});
				document.title = submitButtons.attr('data-page-title');
			}

			// Cleanup - we used all needed information from this temporary element.
			submitButtons.remove();

			// Work with pagination.
			quickreply.style.handlePagination();
			handleDrops(quickreply.style.getPagination());
			quickreply.style.bindPagination();

			// Work with special features.
			quickreply.ajax.add(elements);
			quickreply.special.functions.qr_hide_subject(elements);

			// Done! Let's finish the loading process.
			$('#qr_posts').trigger('qr_loaded', [$(elements)]);

			// Callback function needs to stop loading itself. @TODO update
			if (typeof callback === "function") {
				callback(getScrollElement(elements));
			} //else {
			quickreply.loading.stop();
			//}
		}

		/**
		 * Gets jQuery element to scroll to.
		 *
		 * @param {jQuery} elements Container jQuery object
		 * @returns {jQuery}
		 */
		function getScrollElement(elements) {
			switch (params.scroll) {
				case 'last':
					return elements.find(quickreply.editor.postSelector).last();
				case 'unread':
					if ($(quickreply.editor.unreadPostSelector).length) {
						return $(quickreply.editor.unreadPostSelector).first();
					}
				/* falls through */
				default:
					return elements.children().first();
			}
		}

		/**
		 * Sets default values to parameters.
		 */
		function setDefaults() {
			dataObject = {
				qr_cur_post_id: $(quickreply.editor.mainForm).find('input[name="qr_cur_post_id"]').val(),
				qr_request: 1
			};
			params = {
				scroll: '',
				callback: null
			};
			requestMethod = 'GET';
		}

		/**
		 * Cleans request URL.
		 */
		function parseURL() {
			if (self.url.indexOf('?') < 0) {
				self.url = self.url.replace(/&/, '?');
			}
			var urlHash = self.url.indexOf('#');
			if (urlHash > -1) {
				if (self.url.substr(urlHash) === '#unread') {
					params.scroll = 'unread';
				}
				self.url = self.url.substr(0, urlHash);
			}
		}

		/**
		 * Special handler for SEO URLs.
		 */
		function handleSEO() {
			if (quickreply.plugins.seo) {
				if (params.scroll === 'unread') {
					var viewtopicLink = quickreply.editor.viewtopicLink;
					self.url = viewtopicLink + ((viewtopicLink.indexOf('?') < 0) ? '?' : '&') + 'view=unread';
				} else if (self.url.indexOf('hilit=') > -1) {
					self.url = self.url.replace(/(&amp;|&|\?)hilit=([^&]*)(&amp;|&)?/, function(str, p1, p2, p3) {
						$.extend(dataObject, {hilit: p2});
						return (p3) ? p1 : '';
					});
					requestMethod = 'POST';
				}
			}
		}

		/**
		 * Removes the post and related content from the page.
		 *
		 * @param {jQuery} $post jQuery selector for the post
		 */
		function removePost($post) {
			$post.closest('.post-container').remove();
		}

		/**
		 * Removes old content of the merged post.
		 */
		function prepareMergedPost() {
			var $mergedPost = quickreply.$.qrPosts.find(quickreply.editor.postSelector).last();
			if (quickreply.settings.softScroll) {
				$mergedPost.slideUp(qrSlideInterval, function() {
					removePost($(this));
				});
			} else {
				quickreply.$.qrPosts.one('qr_insert_before', function() {
					removePost($mergedPost);
				});
			}
		}

		/**
		 * Error response handler.
		 */
		function resultError() {
			if (qrStopHistory) {
				qrStopHistory = false;
			}
			quickreply.ajax.error();
		}

		/**
		 * Success response handler.
		 *
		 * @param {object}  res                     The result of the response
		 * @param {string}  [res.result]            Parsed HTML result
		 * @param {boolean} [res.insert]            Whether we need to insert the result, overwrite otherwise
		 * @param {boolean} [res.merged]            Whether last displayed post has been updated by merge
		 * @param {boolean} [res.captcha_refreshed] Whether the CAPTCHA data has been updated
		 * @param {string}  [res.captcha_result]    Parsed HTML for CAPTCHA container
		 * @param {string}  [res.MESSAGE_TEXT]      Information notice
		 */
		function resultSuccess(res) {
			if (res.result) {
				if (res.insert) {
					if (res.merged) {
						prepareMergedPost();
					}

					quickreply.style.markRead($('#qr_posts'));
					var $tempContainer = $(quickreply.editor.tempContainer);
					$tempContainer.html(res.result);
					qrReplaceHistory = true;
					showResponse($tempContainer, function(element) {
						if (quickreply.settings.softScroll) {
							$tempContainer.slideDown(qrSlideInterval, function() {
								qrResponsiveLinks($tempContainer);
								$('#qr_posts').trigger('qr_completed', [$tempContainer]);

								$tempContainer.children().appendTo('#qr_posts');
								$tempContainer.hide();

								if (quickreply.settings.enableScroll) {
									quickreply.functions.softScroll(element);
								}
							});
						} else {
							var reply_posts = $('#qr_posts');
							reply_posts.trigger('qr_insert_before');

							// Restore the subject of the first post.
							quickreply.style.restoreFirstSubject(reply_posts, $tempContainer);

							$tempContainer.show();
							qrResponsiveLinks($tempContainer);
							reply_posts.trigger('qr_completed', [$tempContainer]);

							var reply_posts_inserted = $tempContainer.children().appendTo('#qr_posts');
							$tempContainer.hide();

							if (quickreply.settings.enableScroll) {
								quickreply.functions.softScroll(reply_posts_inserted.first());
							}
						}
					});
				} else {
					var reply_posts = $('#qr_posts');
					if (quickreply.settings.softScroll) {
						reply_posts.slideUp(qrSlideInterval, function() {
							$(this).html(res.result);
							showResponse($(this), function(element) {
								reply_posts.slideDown(qrSlideInterval, function() {
									qrResponsiveLinks(reply_posts);
									reply_posts.trigger('qr_completed', [reply_posts]);
									if (quickreply.settings.enableScroll) {
										quickreply.functions.softScroll(element);
									}
								});
							});
						});
					} else {
						reply_posts.html(res.result);
						showResponse(reply_posts);
						qrResponsiveLinks(reply_posts);
						reply_posts.trigger('qr_completed', [reply_posts]);
						if (quickreply.settings.enableScroll) {
							quickreply.functions.softScroll(reply_posts);
						}
					}
				}
				if (typeof params.callback === "function") {
					params.callback();
				}
			} else if (res.captcha_refreshed) {
				$('#qr_captcha_container').slideUp(qrSlideInterval, function() {
					quickreply.loading.stop();
					$(this).html(res.captcha_result).slideDown(qrSlideInterval, function() {
						$('#qr_postform').trigger('qr_captcha_refreshed');
					});
				});
			} else {
				if (qrStopHistory) {
					qrStopHistory = false;
				}
				quickreply.ajax.error(res.MESSAGE_TEXT);
			}
		}

		/**
		 * Reload the page by submitting the form in the standard way.
		 * Non-Ajax alternative for saving entered reply (including attachments).
		 *
		 * @param {string} url Requested URL
		 */
		function standardReload(url) {
			$(window).off('beforeunload.quickreply');
			$(quickreply.editor.mainForm).off('submit').attr('action', url).submit();
		}

		/**
		 * Special handler for pagination.
		 * Determines whether we can perform the Ajax request.
		 *
		 * @param {string} url Page URL to load
		 */
		this.loadPage = function(url) {
			if (!quickreply.settings.ajaxPagination) {
				standardReload(url);
				return;
			}
			self.start(url);
		};

		/**
		 * The function for handling Ajax requests.
		 *
		 * @param {string}   url                      Requested URL
		 * @param {object}   [requestData]            Optional data object
		 * @param {object}   [requestParams]          Optional object with parameters
		 * @param {function} [requestParams.callback] The function to be called after successful result
		 * @param {string}   [requestParams.scroll]   'last' - if we need to scroll to the last post,
		 *                                            'unread' - if we need to scroll to the first unread post
		 */
		this.start = function(url, requestData, requestParams) {
			quickreply.loading.start();

			setDefaults();

			self.url = url;
			parseURL();
			handleSEO();

			if (requestData) {
				$.extend(dataObject, requestData);
			}
			if (requestParams) {
				$.extend(params, requestParams);
			}

			$.ajax({
				url: self.url,
				data: dataObject,
				method: requestMethod,
				error: resultError,
				success: resultSuccess,
				cache: false
			});
		};
	}
})(jQuery, window, document);
