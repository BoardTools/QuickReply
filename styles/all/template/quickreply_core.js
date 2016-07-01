/* global phpbb, phpbb_seo, quickreply */
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
		quickreply.style.setTextareaId();

		/* Fix for external links. */
		$('#file-list').on('click', 'a[href]', function() {
			$(this).attr('target', '__blank');
		});
	}

	/**
	 * Show the confirmation warning before unloading the page if the reply is still in the form.
	 */
	$(window).on('beforeunload.quickreply', function() {
		if ($(quickreply.editor.textareaSelector).val()) {
			return quickreply.language.WARN_BEFORE_UNLOAD;
		}
	});

	/********************************/
	/* Classes and global functions */
	/********************************/
	initGlobalFunctions();

	quickreply.$ = new QrJqueryElements();
	quickreply.loading = new Loading();
	quickreply.ajax = new Ajax();
	quickreply.ajaxReload = new AjaxReload();
	quickreply.form = new QrForm();

	quickreply.ajax.init();
	quickreply.form.init();

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
				var current_state = history.state, d = new Date();
				if (current_state !== null && current_state.replaced) {
					phpbb.history.replaceUrl(window.location.href + '&ajax_time=' + d.getTime(), document.title,
						current_state
					);
				}
			});

			var current_state = history.state;
			if (current_state !== null) {
				phpbb.history.replaceUrl(window.location.href.replace(/&ajax_time=\d*/i, ''), document.title,
					current_state
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
		 * @param {jQuery} target
		 */
		quickreply.functions.qr_soft_scroll = function(target) {
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, qrSlideInterval);
			}
		};

		/**
		 * Shows an alert with the specified message.
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
			}, 3000);
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
		quickreply.functions.qr_page_jump = function(item) {
			var page = parseInt(item.val(), 10);

			if (page !== null && !isNaN(page) && page === Math.floor(page) && page > 0) {
				var perPage = item.attr('data-per-page'),
					baseUrl = item.attr('data-base-url'),
					startName = item.attr('data-start-name');

				if (baseUrl.indexOf('?') === -1) {
					quickreply.functions.qr_ajax_reload(baseUrl + '?' + startName + '=' + ((page - 1) * perPage));
				} else {
					quickreply.functions.qr_ajax_reload(baseUrl.replace(/&amp;/g, '&') +
						'&' + startName + '=' + ((page - 1) * perPage));
				}
			}
		};

		/**
		 * pageJump function for QuickReply.
		 *
		 * @param {jQuery} item
		 */
		quickreply.functions.qr_seo_page_jump = function(item) {
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
						quickreply.functions.qr_ajax_reload(base_url.replace(/&amp;/g, '&') +
							(phpEXtest ? '?' : '&') + start_name + '=' + phpbb_seo.page + anchor);
					} else {
						var ext = base_url.match(/\.[a-z0-9]+$/i);

						if (ext) {
							// location.ext => location-xx.ext
							quickreply.functions.qr_ajax_reload(base_url.replace(/\.[a-z0-9]+$/i, '') +
								phpbb_seo.delim_start + phpbb_seo.page + ext + anchor);
						} else {
							// location and location/ to location/pagexx.html
							var slash = base_url.match(/\/$/) ? '' : '/';
							quickreply.functions.qr_ajax_reload(base_url + slash +
								phpbb_seo.static_pagination + phpbb_seo.page + phpbb_seo.ext_pagination + anchor);
						}
					}
				} else {
					quickreply.functions.qr_ajax_reload(base_url + anchor);
				}
			}
		};
	}

	/**
	 * Shows/hides the preview block.
	 * By default the block will be hidden if no options are specified.
	 *
	 * @param {object} [options] Optional array of options.
	 */
	function setPreview(options) {
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
		$preview.css('display', ops.display)
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
	}

	/**
	 * Handles dropdowns for the specified container.
	 *
	 * @param {jQuery} container
	 */
	function handleDrops(container) {
		/**
		 * Dropdowns
		 */
		container.find('.dropdown-container').each(function() {
			var $this = $(this),
				trigger = $this.find('.dropdown-trigger:first'),
				contents = $this.find('.dropdown'),
				options = {
					direction: 'auto',
					verticalDirection: 'auto'
				},
				data;

			if (!trigger.length) {
				data = $this.attr('data-dropdown-trigger');
				trigger = data ? $this.children(data) : $this.children('a:first');
			}

			if (!contents.length) {
				data = $this.attr('data-dropdown-contents');
				contents = data ? $this.children(data) : $this.children('div:first');
			}

			if (!trigger.length || !contents.length) {
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

			phpbb.registerDropdown(trigger, contents, options);
		});
	}

	/**
	 * Handles responsive links for the specified container.
	 *
	 * @param {jQuery} container
	 */
	function qrResponsiveLinks(container) {
		/**
		 * Responsive link lists
		 */
		container.find('.linklist:not(.navlinks, [data-skip-responsive]), .postbody .post-buttons:not([data-skip-responsive])').each(function() {
			var $this = $(this),
				filterSkip = '.breadcrumbs, [data-skip-responsive]',
				filterLast = '.edit-icon, .quote-icon, [data-last-responsive]',
				persist = $this.attr('id') === 'nav-main',
				allLinks = $this.children(),
				links = allLinks.not(filterSkip),
				html = '<li class="responsive-menu" style="display:none;"><a href="javascript:void(0);" class="responsive-menu-link">&nbsp;</a><div class="dropdown" style="display:none;"><div class="pointer"><div class="pointer-inner" /></div><ul class="dropdown-contents" /></div></li>',
				filterLastList = links.filter(filterLast),
				slack = 1; // Vertical slack space (in pixels). Determines how sensitive the script is in determining whether a line-break has occured.

			if (!persist) {
				if (links.is('.rightside')) {
					links.filter('.rightside:first').before(html);
					$this.children('.responsive-menu').addClass('rightside');
				} else {
					$this.append(html);
				}
			}

			var item = $this.children('.responsive-menu'),
				menu = item.find('.dropdown-contents'),
				compact = false,
				responsive = false,
				copied = false;

			function check() {
				// Unhide the quick-links menu if it has content
				if (persist) {
					item.addClass('hidden');
					if (menu.find('li:not(.separator, .clone)').length || (responsive && menu.find('li.clone').length)) {
						item.removeClass('hidden');
					}
				}

				// Reset responsive and compact layout
				if (responsive) {
					responsive = false;
					$this.removeClass('responsive');
					links.css('display', '');
					if (!persist) {
						item.css('display', 'none');
					}
				}

				if (compact) {
					compact = false;
					$this.removeClass('compact');
				}

				// Find tallest element
				var maxHeight = 0;
				allLinks.each(function() {
					if (!$(this).height()) {
						return;
					}
					maxHeight = Math.max(maxHeight, $(this).outerHeight(true));
				});

				if (maxHeight < 1) {
					return;
				}

				// Nothing to resize if block's height is not bigger than tallest element's height
				if ($this.height() <= (maxHeight + slack)) {
					return;
				}

				// Enable compact layout, find tallest element, compare to height of whole block
				compact = true;
				$this.addClass('compact');

				var compactMaxHeight = 0;
				allLinks.each(function() {
					if (!$(this).height()) {
						return;
					}
					compactMaxHeight = Math.max(compactMaxHeight, $(this).outerHeight(true));
				});

				if ($this.height() <= (maxHeight + slack)) {
					return;
				}

				// Compact layout did not resize block enough, switch to responsive layout
				compact = false;
				$this.removeClass('compact');
				responsive = true;

				if (!copied) {
					var clone = links.clone(true);
					clone.filter('.rightside').each(function() {
						if (persist) {
							$(this).addClass('clone');
						}
						menu.prepend(this);
					});

					if (persist) {
						menu.prepend(clone.not('.rightside').addClass('clone'));
					} else {
						menu.prepend(clone.not('.rightside'));
					}

					menu.find('li.leftside, li.rightside').removeClass('leftside rightside');
					menu.find('.inputbox').parents('li:first').css('white-space', 'normal');

					if ($this.hasClass('post-buttons')) {
						$('.button', menu).removeClass('button icon-button');
						$('.responsive-menu-link', item).addClass('button icon-button').prepend('<span></span>');
					}
					copied = true;
				}
				else {
					menu.children().css('display', '');
				}

				item.css('display', '');
				$this.addClass('responsive');

				// Try to not hide filtered items
				if (filterLastList.length) {
					links.not(filterLast).css('display', 'none');

					maxHeight = 0;
					filterLastList.each(function() {
						if (!$(this).height()) {
							return;
						}
						maxHeight = Math.max(maxHeight, $(this).outerHeight(true));
					});

					if ($this.height() <= (maxHeight + slack)) {
						menu.children().filter(filterLast).css('display', 'none');
						return;
					}
				}

				// If even responsive isn't enough, use both responsive and compact at same time
				compact = true;
				$this.addClass('compact');

				links.css('display', 'none');
			}

			if (!persist) {
				phpbb.registerDropdown(item.find('a.responsive-menu-link'), item.find('.dropdown'));
			}

			check();
			$(window).resize(check);
		});
	}

	// /**
	//  * Sets the Parent class for the Child class.
	//  *
	//  * @param {function} Parent Parent constructor
	//  * @param {function} Child  Child constructor
	//  */
	// function makeChild(Parent, Child) {
	// 	function F() {}
	// 	F.prototype = Parent.prototype;
	// 	Child.prototype = new F();
	// 	Child.prototype.constructor = Child;
	// 	Child.parent = Parent;
	// }

	function QrJqueryElements() {
		this.qrPosts = $('#qr_posts');
		this.mainForm = $(quickreply.editor.mainForm);
		this.textarea = $(quickreply.editor.textareaSelector);
	}

	function Loading() {
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
		};

		/**
		 * Sets loading explanation text to inform the user about current state.
		 *
		 * @param {string} text HTML string with informative text.
		 */
		this.setExplain = function(text) {
			$('#qr_loading_explain').fadeOut(phpbb.alertTime, function() {
				$(this).html(text).fadeIn(phpbb.alertTime);
			});
		};

		/**
		 * Hides loading indicator.
		 *
		 * @param {boolean} [keepDark] Whether we should not hide the dark.
		 */
		this.stop = function(keepDark) {
			var $dark = $('#darkenwrapper'), $loadingText = $('#qr_loading_text');
			$loadingText.fadeOut(phpbb.alertTime);
			$('#qr_loading_explain').fadeOut(phpbb.alertTime, function() {
				$(this).html('');
			});
			if (!keepDark) {
				$dark.fadeOut(phpbb.alertTime);
			}
		};

		/**
		 * Keeps loading indicator shown.
		 */
		this.proceed = function() {
			$('#darkenwrapper').stop().fadeIn(phpbb.alertTime);
		};
	}

	function QrForm() {
		this.type = 'fixed';

		var self = this;

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
		 * Prepares quick reply form.
		 */
		this.init = function() {
			if (quickreply.settings.formType > 0) {
				self.setFixed();
			}
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
		};

		/**
		 * Sets padding-bottom for body element equal to quick reply form height.
		 */
		function setBodyPaddingBottom() {
			$body.css('padding-bottom', self.$.height() + 'px');
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
		 * Applies compact style to quick reply form.
		 *
		 * @param {boolean} [skipBottomAnimation] Whether we do not need to animate the body
		 */
		this.setCompact = function(skipBottomAnimation) {
			var animationOptions = (skipBottomAnimation) ? qrSlideInterval : {
				duration: qrSlideInterval,
				progress: setBodyPaddingBottom
			};
			$('#qr_text_action_box, .qr_attach_button').hide();
			hideColourPalette();
			$(quickreply.editor.mainForm).addClass('qr_compact_form');
			quickreply.style.formEditorElements(true).slideUp(animationOptions);
		};

		/**
		 * Initializes fixed form mode.
		 */
		this.setFixed = function() {
			$(quickreply.editor.textareaSelector).attr('placeholder', quickreply.language.TYPE_REPLY);

			quickreply.style.showQuickReplyForm();

			// Switch off Quick Reply Toggle Plugin
			$("#reprap").remove();

			$(quickreply.editor.mainForm).finish().addClass('qr_fixed_form qr_compact_form');
			$(quickreply.editor.textareaSelector).addClass('qr_fixed_textarea');

			quickreply.style.setAdditionalElements();
			$('#qr_text_action_box, #qr_captcha_container, .qr_attach_button').hide();
			setTimeout(setBodyPaddingBottom, 500);

			// Add events.
			$('.qr_bbcode_button').click(function() {
				$('#format-buttons').finish().slideToggle({
					duration: qrSlideInterval,
					progress: setBodyPaddingBottom
				});
				hideColourPalette();
			});
			$('.qr_attach_button').click(function() {
				$(quickreply.editor.attachPanel).finish().slideToggle({
					duration: qrSlideInterval,
					progress: setBodyPaddingBottom
				});
			});
			$('.qr_more_actions_button').click(function() {
				$('.qr_fixed_form .additional-element').finish().slideToggle({
					duration: qrSlideInterval,
					progress: setBodyPaddingBottom
				});
			});

			$(quickreply.editor.textareaSelector).focus(function() {
				$(quickreply.editor.mainForm).not('.qr_fullscreen_form')
					.find('.qr_attach_button').delay(100).fadeIn().end()
					.removeClass('qr_compact_form')
					.find('#qr_text_action_box, #qr_captcha_container, .submit-buttons').slideDown({
					duration: qrSlideInterval,
					progress: setBodyPaddingBottom
				});
			});

			// Hide active dropdowns when click event happens outside
			$body.on('mousedown.quickreply', function(e) {
				var $parents = $(e.target).parents();
				if (!$parents.is(quickreply.editor.mainForm)) {
					if (!$(quickreply.editor.textareaSelector).val() &&
						!$(quickreply.editor.mainForm).hasClass('qr_fullscreen_form')) {
						self.setCompact();
					}
				}

				var $smileyBox = $('#smiley-box');
				if (!$parents.is($smileyBox)) {
					if ($smileyBox.is(':visible')) {
						var setLocation = (self.is('fullscreen')) ? {'right': '-1000px'} : {'left': '-1000px'};
						$smileyBox.animate(setLocation, qrSlideInterval, function() {
							$(this).hide();
						});
					}
				}
			});

			$('.qr_smiley_button, #smiley-box a').click(function() {
				var $smileyBox = $('#smiley-box');

				function setSmileyBoxLocation(location, alternative) {
					$smileyBox.css(location, '-1000px').css(alternative, 'auto');
				}

				if (!$smileyBox.is(':visible')) {
					if (self.is('fullscreen')) {
						setSmileyBoxLocation('right', 'left');
						$smileyBox.show().animate({
							'right': '70px'
						}, qrSlideInterval);
					} else {
						setSmileyBoxLocation('left', 'right');
						$smileyBox.show().animate({
							'left': '45px'
						}, qrSlideInterval);
					}
				}
			});

			$('.qr_fullscreen_button').click(function() {
				if (self.is('fullscreen')) {
					self.exitFullscreen();
				} else {
					self.enterFullscreen();
				}
			});
		};

		/**
		 * Exits fullscreen mode and returns quick reply form to the previous mode.
		 */
		this.exitFullscreen = function() {
			$(document).off('keydown.quickreply.fullscreen');
			$(quickreply.editor.mainForm).find('.submit-buttons input[type="submit"]').off('click.quickreply.fullscreen');

			$body.css('overflow-y', '');
			hideColourPalette();
			$('.qr_fixed_form').animate({
				'maxHeight': '50%',
				'height': 'auto'
			}, qrSlideInterval).removeClass('qr_fullscreen_form');

			if (!self.is('compact')) {
				quickreply.style.formEditorElements().slideUp(qrSlideInterval);
				$('#qr_text_action_box, .qr_attach_button').show();
			} else {
				self.setCompact(true);
			}

			$('.qr_fullscreen_button').toggleClass('fa-arrows-alt fa-times').attr('title', quickreply.language.FULLSCREEN);
			$(quickreply.editor.textareaSelector).addClass('qr_fixed_textarea');

			var $smileyBox = $('#smiley-box');
			if ($smileyBox.is(':visible')) {
				$smileyBox.hide().css('left', '-1000px');
			}

			$(quickreply.editor.mainForm).trigger('fullscreen-exit');
		};

		/**
		 * Opens quick reply form in fullscreen mode.
		 */
		this.enterFullscreen = function() {
			$body.css('overflow-y', 'hidden');
			quickreply.style.formEditorElements(true).slideDown(qrSlideInterval);
			$('#qr_text_action_box, .qr_attach_button').hide();
			$('.qr_fixed_form').animate({
				'maxHeight': '100%',
				'height': '100%'
			}, qrSlideInterval).addClass('qr_fullscreen_form');
			$('.qr_fullscreen_button').toggleClass('fa-arrows-alt fa-times')
				.attr('title', quickreply.language.FULLSCREEN_EXIT);
			$(quickreply.editor.textareaSelector).removeClass('qr_fixed_textarea');

			var $smileyBox = $('#smiley-box');
			if ($smileyBox.is(':visible')) {
				$smileyBox.hide().css('right', '-1000px');
			}

			$(document).on('keydown.quickreply.fullscreen', function(e) {
				if (e.keyCode === 27) {
					self.exitFullscreen();
					e.preventDefault();
					e.stopPropagation();
				}
			});

			$(quickreply.editor.mainForm).find('.submit-buttons input[type="submit"]')
				.on('click.quickreply.fullscreen', function() {
					self.exitFullscreen();
				});

			$(quickreply.editor.mainForm).trigger('fullscreen');
		};

		/**
		 * Clears quick reply form (e.g. after submission).
		 */
		this.refresh = function() {
			$('input[name="post"]').removeAttr('data-clicked');
			$(quickreply.editor.textareaSelector).val('').attr('style', 'height: 9em;');

			if ($('#preview').is(':visible')) {
				setPreview(); // Hide preview.
			}

			hideColourPalette();

			if (quickreply.settings.attachBox) {
				$('#file-list-container').css('display', 'none');
				$('#file-list').empty();
				phpbb.plupload.clearParams();
			}

			if (quickreply.settings.allowedGuest) {
				quickreply.ajaxReload.start(document.location.href, {qr_captcha_refresh: 1});
			}
		};
	}

	function Ajax() {
		this.init = function() {
			if (quickreply.settings.ajaxSubmit) {
				$(quickreply.editor.mainForm).attr('data-ajax', 'qr_ajax_submit');
				quickreply.style.initPreview();

				$(quickreply.editor.mainForm).submit(function() {
					var action = $(this).attr('action'), url_hash = action.indexOf('#');
					if (url_hash > -1) {
						$(this).attr('action', action.substr(0, url_hash));
					}

					quickreply.loading.start();

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

			$('#page-footer').append('<div id="qr_loading_text"><i class="fa fa-refresh fa-spin"></i><span>' +
				quickreply.language.loading.text + '</span><div id="qr_loading_explain"></div></div>');

			$(document).ajaxError(function() {
				var $loadingText = $('#qr_loading_text');
				if ($loadingText.is(':visible')) {
					$loadingText.fadeOut(phpbb.alertTime);
				}
			});
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
		 * @param {string} [text] Optional error description.
		 */
		this.error = function(text) {
			quickreply.functions.alert(
				quickreply.language.AJAX_ERROR_TITLE,
				quickreply.language.AJAX_ERROR + ((text) ? '<br />' + text : '')
			);
		};

		/**
		 * Removes last post and related content from the page.
		 */
		function removeLastPost() {
			var reply_posts = $('#qr_posts'), merged_post = reply_posts.find(quickreply.editor.postSelector).last(),
				merged_post_id = merged_post.attr('id');
			merged_post.remove();
			reply_posts.find('.divider').last().remove();
			var decoded_post = $('#decoded_' + merged_post_id), qr_author = $('#qr_author_' + merged_post_id);
			if (decoded_post.length) {
				decoded_post.remove();
			}
			if (qr_author.length) {
				qr_author.remove();
			}
		}

		/**
		 * Returns the requestData object for Ajax callback function.
		 * Used when new messages have been added to the topic.
		 *
		 * @param {boolean} merged Whether the post has been merged.
		 * @returns {object}
		 */
		function getReplyData(merged) {
			var replySetData = {qr_no_refresh: 1};
			if (merged) {
				$.extend(replySetData, {qr_get_current: 1});
				if (quickreply.settings.softScroll) {
					$('#qr_posts').find(quickreply.editor.postSelector).last().slideUp(qrSlideInterval, function() {
						removeLastPost();
					});
				} else {
					$('#qr_posts').one('qr_insert_before', function() {
						removeLastPost();
					});
				}
			}
			return replySetData;
		}

		this.submitCallback = function(res) {
			if (res.qr_fields) {
				quickreply.form.updateFields(res.qr_fields);
			}

			// @todo make own alert.
			if (typeof res.MESSAGE_TITLE !== 'undefined') {
				//alert = phpbb.alert(res.MESSAGE_TITLE, res.MESSAGE_TEXT);
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
					quickreply.ajaxReload.start(
						res.NEXT_URL.replace(/&amp;/ig, '&') + '#unread', getReplyData(res.merged), {
							callback: function() {
								if (quickreply.settings.allowedGuest) {
									quickreply.ajaxReload.start(document.location.href, {qr_captcha_refresh: 1});
								}
							}
						}
					);
					break;

				case "preview":
					var $preview = $('#preview');
					setPreview({
						display: 'block',
						title: res.PREVIEW_TITLE,
						content: res.PREVIEW_TEXT,
						attachments: res.PREVIEW_ATTACH
					});
					quickreply.loading.stop();
					if (quickreply.settings.enableScroll) {
						quickreply.functions.qr_soft_scroll($preview);
					}
					$('#qr_postform').trigger('ajax_submit_preview', [$preview]);
					break;

				case "no_approve":
					quickreply.form.refresh();
					break;

				case "post_update":
					// Send the message again with the updated ID of the last post.
					$(quickreply.editor.mainForm)
						.find('input[name="qr_cur_post_id"], input[name="topic_cur_post_id"]').val(res.post_id).end()
						.find('input[name="post"]').click();
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
						quickreply.functions.qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
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
		 * @param {jQuery}   elements   Newly added elements.
		 * @param {function} [callback] Callback function (receives the element for scrolling).
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

		function parseURL() {
			if (self.url.indexOf('?') < 0) {
				self.url = self.url.replace(/&/, '?');
			}
			var url_hash = self.url.indexOf('#');
			if (url_hash > -1) {
				if (self.url.substr(url_hash) === '#unread') {
					params.scroll = 'unread';
				}
				self.url = self.url.substr(0, url_hash);
			}
		}

		function handleSEO() {
			if (quickreply.plugins.seo) {
				if (params.scroll === 'unread') {
					var viewtopic_link = quickreply.editor.viewtopicLink;
					self.url = viewtopic_link + ((viewtopic_link.indexOf('?') < 0) ? '?' : '&') + 'view=unread';
				} else if (self.url.indexOf('hilit=') > -1) {
					self.url = self.url.replace(/(&amp;|&|\?)hilit=([^&]*)(&amp;|&)?/, function(str, p1, p2, p3) {
						$.extend(dataObject, {hilit: p2});
						return (p3) ? p1 : '';
					});
					requestMethod = 'POST';
				}
			}
		}

		function resultError() {
			if (qrStopHistory) {
				qrStopHistory = false;
			}
			quickreply.ajax.error();
		}

		function resultSuccess(res) {
			if (res.result) {
				if (res.insert) {
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
									quickreply.functions.qr_soft_scroll(element);
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
								quickreply.functions.qr_soft_scroll(reply_posts_inserted.first());
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
										quickreply.functions.qr_soft_scroll(element);
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
							quickreply.functions.qr_soft_scroll(reply_posts);
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

		function standardReload(url) {
			$(quickreply.editor.mainForm).off('submit').attr('action', url).submit();
		}

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
			if (!quickreply.settings.ajaxPagination) {
				standardReload(url);
				return;
			}

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
