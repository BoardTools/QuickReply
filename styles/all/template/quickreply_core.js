/* global phpbb, phpbb_seo, quickreply */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106

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

	if (quickreply.settings.ajaxSubmit) {
		$(quickreply.editor.mainForm).attr('data-ajax', 'qr_ajax_submit');
		quickreply.style.initPreview();
	}

	/**
	 * Sets margin-bottom for body element equal to quick reply form height.
	 */
	quickreply.functions.setBodyMarginBottom = function() {
		$('body').animate({
			'marginBottom': $(quickreply.editor.mainForm).height()
		});
	};

	/**
	 * Exits fullscreen mode of quick reply form.
	 */
	quickreply.functions.qr_exit_fullscreen = function() {
		$(document).off('keydown.quickreply.fullscreen');
		$(quickreply.editor.mainForm).find('.submit-buttons input[type="submit"]').off('click.quickreply.fullscreen');
		$('body').css('overflow-y', '');
		$(quickreply.editor.mainForm).find('#attach-panel, #format-buttons, .quickreply-title, .additional-element').slideUp();
		if (!$(quickreply.editor.textareaSelector).val()) {
			$(quickreply.editor.mainForm).find('.submit-buttons').slideUp(function() {
				quickreply.functions.setBodyMarginBottom();
			});
		}
		$('.qr_fixed_form').animate({
			'maxHeight': '50%',
			'height': 'auto'
		}).removeClass('qr_fullscreen_form');
		if (!$(quickreply.editor.mainForm).hasClass('qr_compact_form')) {
			$('#qr_text_action_box, .qr_attach_button').show();
		}
		$('.qr_fullscreen_button').toggleClass('fa-arrows-alt fa-times').attr('title', quickreply.language.FULLSCREEN);
		$(quickreply.editor.textareaSelector).addClass('qr_fixed_textarea');

		var $smileyBox = $('#smiley-box');
		if ($smileyBox.is(':visible')) {
			$smileyBox.hide().css('left', '-1000px');
		}
	};

	/**
	 * Whether quick reply form is in fullscreen mode.
	 *
	 * @returns {boolean}
	 */
	quickreply.functions.qr_is_fullscreen = function() {
		return $(quickreply.editor.mainForm).hasClass('qr_fullscreen_form');
	};

	if (quickreply.settings.formType > 0) {
		$(quickreply.editor.textareaSelector).attr('placeholder', quickreply.language.TYPE_REPLY);

		quickreply.style.showQuickReplyForm();

		// Switch off Quick Reply Toggle Plugin
		$("#reprap").remove();

		$(quickreply.editor.mainForm).finish().addClass('qr_fixed_form qr_compact_form');
		$(quickreply.editor.textareaSelector).addClass('qr_fixed_textarea');

		$('#message-box').siblings(':visible').not('.submit-buttons, #qr_action_box, #qr_text_action_box').addClass('additional-element').hide();
		$('#qr_text_action_box, .qr_attach_button').hide();
		quickreply.functions.setBodyMarginBottom();

		// Add events.
		$('.qr_bbcode_button').click(function() {
			$('#format-buttons').finish().slideToggle(function() {
				quickreply.functions.setBodyMarginBottom();
			});
		});
		$('.qr_attach_button').click(function() {
			$('#attach-panel').finish().slideToggle(function() {
				quickreply.functions.setBodyMarginBottom();
			});
		});
		$('.qr_more_actions_button').click(function() {
			$('.qr_fixed_form .additional-element').finish().slideToggle(function() {
				quickreply.functions.setBodyMarginBottom();
			});
		});

		$(quickreply.editor.textareaSelector).focus(function() {
			$(quickreply.editor.mainForm).not('.qr_fullscreen_form')
				.find('.qr_attach_button').delay(100).fadeIn().end()
				.removeClass('qr_compact_form')
				.find('#qr_text_action_box, .submit-buttons').slideDown(function() {
				quickreply.functions.setBodyMarginBottom();
			});
		});


		// Hide active dropdowns when click event happens outside
		$('body').on('mousedown.quickreply', function(e) {
			var $parents = $(e.target).parents();
			if (!$parents.is(quickreply.editor.mainForm)) {
				if (!$(quickreply.editor.textareaSelector).val()) {
					$('#qr_text_action_box, .qr_attach_button').hide();
					$(quickreply.editor.mainForm).not('.qr_fullscreen_form').addClass('qr_compact_form').find('#attach-panel, #format-buttons, .quickreply-title, .additional-element, .submit-buttons').slideUp(function() {
						quickreply.functions.setBodyMarginBottom();
					});
				}
			}

			var $smileyBox = $('#smiley-box');
			if (!$parents.is($smileyBox)) {
				if ($smileyBox.is(':visible')) {
					var setLocation = (quickreply.functions.qr_is_fullscreen()) ? {'right': '-1000px'} : {'left': '-1000px'};
					$smileyBox.animate(setLocation, function() {
						$(this).hide();
					});
				}
			}
		});

		$('.qr_smiley_button, #smiley-box a').click(function(e) {
			var $smileyBox = $('#smiley-box');
			function setSmileyBoxLocation(location, alternative) {
				$smileyBox.css(location, '-1000px').css(alternative, 'auto');
			}
			if (!$smileyBox.is(':visible')) {
				if (quickreply.functions.qr_is_fullscreen()) {
					setSmileyBoxLocation('right', 'left');
					$smileyBox.show().animate({
						'right': '70px'
					});
				} else {
					setSmileyBoxLocation('left', 'right');
					$smileyBox.show().animate({
						'left': '45px'
					});
				}
			}
		});

		$('.qr_fullscreen_button').click(function() {
			if (quickreply.functions.qr_is_fullscreen()) {
				quickreply.functions.qr_exit_fullscreen();
			} else {
				$('body').css('overflow-y', 'hidden');
				$(quickreply.editor.mainForm).find('#attach-panel, #format-buttons, .quickreply-title, .additional-element, .submit-buttons').slideDown();
				$('#qr_text_action_box, .qr_attach_button').hide();
				$('.qr_fixed_form').animate({
					'maxHeight': '100%',
					'height': '100%'
				}).addClass('qr_fullscreen_form');
				$('.qr_fullscreen_button').toggleClass('fa-arrows-alt fa-times').attr('title', quickreply.language.FULLSCREEN_EXIT);
				$(quickreply.editor.textareaSelector).removeClass('qr_fixed_textarea');

				var $smileyBox = $('#smiley-box');
				if ($smileyBox.is(':visible')) {
					$smileyBox.hide().css('right', '-1000px');
				}

				$(document).on('keydown.quickreply.fullscreen', function(e) {
					if (e.keyCode === 27) {
						quickreply.functions.qr_exit_fullscreen();
						e.preventDefault();
						e.stopPropagation();
					}
				});

				$(quickreply.editor.mainForm).find('.submit-buttons input[type="submit"]').on('click.quickreply.fullscreen', function() {
					quickreply.functions.qr_exit_fullscreen();
				});
			}
		});
	}

	var qr_slide_interval = (quickreply.settings.softScroll) ? quickreply.settings.scrollInterval : 0;

	/**
	 * Adds Ajax functionality to the specified element.
	 *
	 * @param {jQuery} element
	 */
	function qr_add_ajax(element) {
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

		handle_drops(element);
	}

	/**
	 * Scrolls the page to the target element.
	 *
	 * @param {jQuery} target
	 */
	quickreply.functions.qr_soft_scroll = function(target) {
		if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top
			}, qr_slide_interval);
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
		setTimeout(function() {
			$('#darkenwrapper').fadeOut(phpbb.alertTime, function() {
				alert.hide();
			});
		}, 3000);
	};

	/**
	 * Shows an alert with an error message.
	 *
	 * @param {string} [text] Optional error description.
	 */
	function qr_ajax_error(text) {
		quickreply.functions.alert(quickreply.language.AJAX_ERROR_TITLE, quickreply.language.AJAX_ERROR + ((text) ? '<br />' + text : ''));
	}

	/**
	 * Shows/hides the preview block.
	 * By default the block will be hidden if no options are specified.
	 *
	 * @param {object} [options] Optional array of options.
	 */
	function qr_set_preview(options) {
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
	 * Removes last post and related content from the page.
	 */
	function qr_remove_last_post() {
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
	 * Returns the request_data object for Ajax callback function.
	 * Used when new messages have been added to the topic.
	 *
	 * @param {boolean} merged Whether the post has been merged.
	 * @returns {object}
	 */
	function qr_get_reply_data(merged) {
		var reply_set_data = {qr_no_refresh: 1};
		if (merged) {
			$.extend(reply_set_data, {qr_get_current: 1});
			if (quickreply.settings.softScroll) {
				$('#qr_posts').find(quickreply.editor.postSelector).last().slideUp(qr_slide_interval, function() {
					qr_remove_last_post();
				});
			} else {
				$('#qr_posts').one('qr_insert_before', function() {
					qr_remove_last_post();
				});
			}
		}
		return reply_set_data;
	}

	/**
	 * Clears quick reply form (e.g. after submission).
	 */
	function qr_refresh_form() {
		$('input[name="post"]').removeAttr('data-clicked');
		$(quickreply.editor.textareaSelector).val('').attr('style', 'height: 9em;');

		if ($('#preview').is(':visible')) {
			qr_set_preview(); // Hide preview.
		}

		if ($('#colour_palette').is(':visible')) {
			if (quickreply.plugins.abbc3) {
				$('#abbc3_bbpalette').click();
			} else {
				$('#bbpalette').click();
			}
		}

		if (quickreply.settings.attachBox) {
			$('#file-list-container').css('display', 'none');
			$('#file-list').empty();
			phpbb.plupload.clearParams();
		}

		if (quickreply.settings.allowedGuest) {
			quickreply.functions.qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
		}
	}

	/* Work with browser's history. */
	var qr_stop_history = false, qr_replace_history = false;
	if (quickreply.settings.ajaxSubmit || quickreply.settings.ajaxPagination) {
		$(window).on("popstate", function(e) {
			qr_stop_history = true;
			document.title = e.originalEvent.state.title;
			quickreply.functions.qr_ajax_reload(e.originalEvent.state.url);
		});

		/* Workaround for browser's cache. */
		if (phpbb.history.isSupported("state")) {
			$(window).on("unload", function() {
				var current_state = history.state, d = new Date();
				if (current_state !== null && current_state.replaced) {
					phpbb.history.replaceUrl(window.location.href + '&ajax_time=' + d.getTime(), document.title, current_state);
				}
			});

			var current_state = history.state;
			if (current_state !== null) {
				phpbb.history.replaceUrl(window.location.href.replace(/&ajax_time=\d*/i, ''), document.title, current_state);
			} else {
				phpbb.history.replaceUrl(window.location.href, document.title, {
					url: window.location.href,
					title: document.title
				});
			}
		}
	}

	/**
	 * The function for handling Ajax requests.
	 *
	 * @param {string}   url               Requested URL.
	 * @param {object}   [request_data]    Optional object with data parameters.
	 * @param {function} [result_function] The function to be called after successful result.
	 * @param {boolean}  [scroll_to_last]  Whether we need to scroll to the last post.
	 */
	quickreply.functions.qr_ajax_reload = function(url, request_data, result_function, scroll_to_last) {
		if (url.indexOf('?') < 0) {
			url = url.replace(/&/, '?');
		}
		var url_hash = url.indexOf('#'), qr_get_unread = false;
		if (url_hash > -1) {
			qr_get_unread = (url.substr(url_hash) === '#unread');
			url = url.substr(0, url_hash);
		}
		var qr_current_post = $(quickreply.editor.mainForm).find('input[name="qr_cur_post_id"]').val();
		var $loadingIndicator = phpbb.loadingIndicator(), $dark = $('#darkenwrapper');
		phpbb.clearLoadingTimeout();
		if (!$dark.is(':visible') && $(window).width() >= 700) {
			$dark.fadeIn(phpbb.alertTime);
		}
		setTimeout(function() {
			$loadingIndicator.finish().show();
		}, 100);
		var qr_request_method = 'GET', qr_data_object = {qr_cur_post_id: qr_current_post, qr_request: 1};
		if (quickreply.plugins.seo) {
			if (qr_get_unread) {
				var viewtopic_link = quickreply.editor.viewtopicLink;
				url = viewtopic_link + ((viewtopic_link.indexOf('?') < 0) ? '?' : '&') + 'view=unread';
			} else if (url.indexOf('hilit=') > -1) {
				url = url.replace(/(&amp;|&|\?)hilit=([^&]*)(&amp;|&)?/, function(str, p1, p2, p3) {
					$.extend(qr_data_object, {hilit: p2});
					return (p3) ? p1 : '';
				});
				qr_request_method = 'POST';
			}
		}
		if (request_data) {
			$.extend(qr_data_object, request_data);
		}
		$.ajax({
			url: url,
			data: qr_data_object,
			method: qr_request_method,
			error: function(e, text, ee) {
				if (qr_stop_history) {
					qr_stop_history = false;
				}
				qr_ajax_error();
			},
			success: function(res, x) {
				if (res.result) {
					function qr_show_response(elements, res_function) {
						var reply_submit_buttons = $('#qr_submit_buttons'),
							reply_form_submit_buttons = $(quickreply.editor.mainForm).children().children().children('.submit-buttons');
						reply_form_submit_buttons.children(':not(input[type="submit"])').remove();
						reply_form_submit_buttons.prepend(reply_submit_buttons.html());
						if (qr_replace_history) {
							qr_replace_history = false;
							phpbb.history.replaceUrl(reply_submit_buttons.attr('data-page-url'), reply_submit_buttons.attr('data-page-title'), {
								url: url,
								title: reply_submit_buttons.attr('data-page-title'),
								replaced: true
							});
							document.title = reply_submit_buttons.attr('data-page-title');
						} else if (qr_stop_history) {
							qr_stop_history = false;
						} else {
							phpbb.history.pushUrl(reply_submit_buttons.attr('data-page-url'), reply_submit_buttons.attr('data-page-title'), {
								url: url,
								title: reply_submit_buttons.attr('data-page-title')
							});
							document.title = reply_submit_buttons.attr('data-page-title');
						}
						reply_submit_buttons.remove();
						quickreply.style.handlePagination();
						handle_drops(quickreply.style.getPagination());
						quickreply.style.bindPagination();
						qr_add_ajax(elements);
						quickreply.special.functions.qr_hide_subject(elements);
						$('#qr_posts').trigger('qr_loaded', [$(elements)]);
						var alert_box = $('#phpbb_alert'), alert_delay = (alert_box.is(':visible')) ? 3000 : 0;
						if (alert_box.is(':visible')) {
							alert_box.find('.alert_close').off('click').click(function(e) {
								phpbb.alert.close(alert_box, true);
								e.preventDefault();
							});
						}
						setTimeout(function() {
							$dark.fadeOut(phpbb.alertTime, function() {
								alert_box.hide();
								$(document).off('keydown.phpbb.alert');
							});
						}, alert_delay);
						$loadingIndicator.fadeOut(phpbb.alertTime);
						var qr_scroll_element = (typeof scroll_to_last !== "undefined") ? elements.find(quickreply.editor.postSelector).last() : ((qr_get_unread && $(quickreply.editor.unreadPostSelector).length) ? $(quickreply.editor.unreadPostSelector).first() : elements.children().first());
						if (typeof res_function === "function") {
							res_function(qr_scroll_element);
						}
					}

					if (res.insert) {
						quickreply.style.markRead($('#qr_posts'));
						var reply_temp_container = $(quickreply.editor.tempContainer);
						reply_temp_container.html(res.result);
						qr_replace_history = true;
						qr_show_response(reply_temp_container, function(element) {
							if (quickreply.settings.softScroll) {
								reply_temp_container.slideDown(qr_slide_interval, function() {
									qr_responsive_links(reply_temp_container);
									$('#qr_posts').trigger('qr_completed', [reply_temp_container]);
									reply_temp_container.children().appendTo('#qr_posts');
									reply_temp_container.hide();
									if (quickreply.settings.enableScroll) {
										quickreply.functions.qr_soft_scroll(element);
									}
								});
							} else {
								var reply_posts = $('#qr_posts');
								reply_posts.trigger('qr_insert_before');
								// Restore the subject of the first post.
								quickreply.style.restoreFirstSubject(reply_posts, reply_temp_container);
								reply_temp_container.show();
								qr_responsive_links(reply_temp_container);
								reply_posts.trigger('qr_completed', [reply_temp_container]);
								var reply_posts_inserted = reply_temp_container.children().appendTo('#qr_posts');
								reply_temp_container.hide();
								if (quickreply.settings.enableScroll) {
									quickreply.functions.qr_soft_scroll(reply_posts_inserted.first());
								}
							}
						});
					} else {
						var reply_posts = $('#qr_posts');
						if (quickreply.settings.softScroll) {
							reply_posts.slideUp(qr_slide_interval, function() {
								$(this).html(res.result);
								qr_show_response($(this), function(element) {
									reply_posts.slideDown(qr_slide_interval, function() {
										qr_responsive_links(reply_posts);
										reply_posts.trigger('qr_completed', [reply_posts]);
										if (quickreply.settings.enableScroll) {
											quickreply.functions.qr_soft_scroll(element);
										}
									});
								});
							});
						} else {
							reply_posts.html(res.result);
							qr_show_response(reply_posts);
							qr_responsive_links(reply_posts);
							reply_posts.trigger('qr_completed', [reply_posts]);
							if (quickreply.settings.enableScroll) {
								quickreply.functions.qr_soft_scroll(reply_posts);
							}
						}
					}
					if (typeof result_function === "function") {
						result_function();
					}
				} else if (res.captcha_refreshed) {
					$('#qr_captcha_container').slideUp(qr_slide_interval, function() {
						var alert_box = $('#phpbb_alert'), alert_delay = (alert_box.is(':visible')) ? 3000 : 0;
						if (alert_box.is(':visible')) {
							alert_box.find('.alert_close').off('click').click(function(e) {
								phpbb.alert.close(alert_box, true);
								e.preventDefault();
							});
						}
						setTimeout(function() {
							$dark.fadeOut(phpbb.alertTime, function() {
								alert_box.hide();
								$(document).off('keydown.phpbb.alert');
							});
						}, alert_delay);
						$loadingIndicator.fadeOut(phpbb.alertTime);
						$(this).html(res.captcha_result).slideDown(qr_slide_interval, function() {
							$('#qr_postform').trigger('qr_captcha_refreshed');
						});
					})
				} else {
					if (qr_stop_history) {
						qr_stop_history = false;
					}
					qr_ajax_error(res.MESSAGE_TEXT);
				}
			},
			cache: false
		});
	};

	/**
	 * pageJump function for QuickReply.
	 *
	 * @param {jQuery} item
	 */
	quickreply.functions.qr_page_jump = function(item) {
		var page = parseInt(item.val());

		if (page !== null && !isNaN(page) && page === Math.floor(page) && page > 0) {
			var perPage = item.attr('data-per-page'),
				baseUrl = item.attr('data-base-url'),
				startName = item.attr('data-start-name');

			if (baseUrl.indexOf('?') === -1) {
				quickreply.functions.qr_ajax_reload(baseUrl + '?' + startName + '=' + ((page - 1) * perPage));
			} else {
				quickreply.functions.qr_ajax_reload(baseUrl.replace(/&amp;/g, '&') + '&' + startName + '=' + ((page - 1) * perPage));
			}
		}
	};

	/**
	 * pageJump function for QuickReply.
	 *
	 * @param {jQuery} item
	 */
	quickreply.functions.qr_seo_page_jump = function(item) {
		var page = parseInt(item.val());

		if (page !== null && !isNaN(page) && page == Math.floor(page) && page > 0) {
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

				if (start_name !== 'start' || base_url.indexOf('?') >= 0 || (phpEXtest = base_url.match("/\." + phpbb_seo.phpEX + "$/i"))) {
					quickreply.functions.qr_ajax_reload(base_url.replace(/&amp;/g, '&') + (phpEXtest ? '?' : '&') + start_name + '=' + phpbb_seo.page + anchor);
				} else {
					var ext = base_url.match(/\.[a-z0-9]+$/i);

					if (ext) {
						// location.ext => location-xx.ext
						quickreply.functions.qr_ajax_reload(base_url.replace(/\.[a-z0-9]+$/i, '') + phpbb_seo.delim_start + phpbb_seo.page + ext + anchor);
					} else {
						// location and location/ to location/pagexx.html
						var slash = base_url.match(/\/$/) ? '' : '/';
						quickreply.functions.qr_ajax_reload(base_url + slash + phpbb_seo.static_pagination + phpbb_seo.page + phpbb_seo.ext_pagination + anchor);
					}
				}
			} else {
				quickreply.functions.qr_ajax_reload(base_url + anchor);
			}
		}
	};

	/* Add Ajax functionality for the pagination. */
	quickreply.style.initPagination();

	/* Save message when navigating the topic. */
	var restored_message = $(quickreply.editor.messageStorage).html();
	if (restored_message != '') {
		$('#message-box textarea').val(restored_message);
	}

	/* Save message for the full reply form. */
	quickreply.style.setPostReplyHandler();

	if (quickreply.settings.ajaxSubmit) {
		phpbb.addAjaxCallback('qr_ajax_submit', function(res) {
			switch (res.status) {
				case "success":
					quickreply.functions.qr_ajax_reload(res.url.replace(/&amp;/ig, '&'), qr_get_reply_data(res.merged), function() {
						$('#qr_postform').trigger('ajax_submit_success');
						qr_refresh_form();
					}, true);
				break;

				case "new_posts":
					quickreply.functions.qr_ajax_reload(res.NEXT_URL.replace(/&amp;/ig, '&') + '#unread', qr_get_reply_data(res.merged), function() {
						if (quickreply.settings.allowedGuest) {
							quickreply.functions.qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
						}
					});
				break;

				case "preview":
					var $preview = $('#preview');
					qr_set_preview({
						display: 'block',
						title: res.PREVIEW_TITLE,
						content: res.PREVIEW_TEXT,
						attachments: res.PREVIEW_ATTACH
					});
					if (quickreply.settings.enableScroll) {
						quickreply.functions.qr_soft_scroll($preview);
					}
					$('#qr_postform').trigger('ajax_submit_preview', [$preview]);
				break;

				case "no_approve":
					qr_refresh_form();
				break;

				case "post_update":
					// Send the message again with the updated ID of the last post.
					$(quickreply.editor.mainForm)
						.find('input[name="qr_cur_post_id"], input[name="topic_cur_post_id"]').val(res.post_id)
						.end().find('input[name="post"]').click();
				break;

				default:
					if (quickreply.settings.allowedGuest) {
						quickreply.functions.qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
					}
					// else qr_ajax_error();
				break;
			}
			/* Fix for phpBB 3.1.9 */
			$(quickreply.editor.mainForm).find('input[data-clicked]').removeAttr('data-clicked');
		});
	}

	/**
	 * Handles dropdowns for the specified container.
	 *
	 * @param {jQuery} container
	 */
	function handle_drops(container) {
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
	function qr_responsive_links(container) {
		/**
		 * Responsive link lists
		 */
		container.find('.linklist:not(.navlinks, [data-skip-responsive]), .postbody .post-buttons:not([data-skip-responsive])').each(function() {
			var $this = $(this),
				$body = $('body'),
				filterSkip = '.breadcrumbs, [data-skip-responsive]',
				filterLast = '.edit-icon, .quote-icon, [data-last-responsive]',
				persist = $this.attr('id') == 'nav-main',
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

})(jQuery, window, document);
