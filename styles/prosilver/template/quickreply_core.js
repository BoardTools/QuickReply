/* global phpbb, phpbb_seo, quickreply */
; (function ($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106

	/* plupload adjustment */
	if (quickreply.settings.pluploadEnabled) {
		phpbb.plupload.config.form_hook = quickreply.editor.mainForm;
	}

	var qr_slide_interval = (quickreply.settings.softScroll) ? quickreply.settings.scrollInterval : 0;

	/**
	 * Adds Ajax functionality to the specified element.
	 *
	 * @param {jQuery} element
	 */
	function qr_add_ajax(element) {
		element.find('[data-ajax]').each(function () {
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
		element.find('.display_post').click(function (e) {
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
	 * Marks the posts within the specified elements read.
	 *
	 * @param {jQuery} elements
	 */
	function qr_mark_read(elements) {
		var unread_posts = elements.find('.unreadpost');
		if (unread_posts.length) {
			var read_post_img = $('#qr_read_post_img').html();
			unread_posts.removeClass('unreadpost').find('.author span.imageset:first').replaceWith(read_post_img);
		}
	}

	/**
	 * Scrolls the page to the target element.
	 *
	 * @param {jQuery} target
	 */
	function qr_soft_scroll(target) {
		if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top
			}, qr_slide_interval);
		}
	}

	/**
	 * Shows an alert with an error message.
	 *
	 * @param {string} [text] Optional error description.
	 */
	function qr_ajax_error(text) {
		var alert = phpbb.alert(quickreply.language.AJAX_ERROR_TITLE, quickreply.language.AJAX_ERROR + ((text) ? '<br />' + text : ''));
		setTimeout(function () {
			$('#darkenwrapper').fadeOut(phpbb.alertTime, function () {
				alert.hide();
			});
		}, 3000);
	}

	/* Work with browser's history. */
	var qr_stop_history = false, qr_replace_history = false;
	if (quickreply.settings.ajaxSubmit || quickreply.settings.ajaxPagination) {
		$(window).on("popstate", function (e) {
			qr_stop_history = true;
			document.title = e.originalEvent.state.title;
			qr_ajax_reload(e.originalEvent.state.url);
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
	 * @param {bool}     [scroll_to_last]  Whether we need to scroll to the last post.
	 */
	function qr_ajax_reload(url, request_data, result_function, scroll_to_last) {
		if (url.indexOf('?') < 0) {
			url = url.replace(/&/, '?');
		}
		var url_hash = url.indexOf('#'), qr_get_unread = false, originalURL = url;
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
		setTimeout(function () {
			$loadingIndicator.finish().show();
		}, 100);
		var qr_request_method = 'GET', qr_data_object = {qr_cur_post_id: qr_current_post, qr_request: 1};
		if (quickreply.plugins.seo) {
			if (qr_get_unread) {
				var viewtopic_link = quickreply.editor.viewtopicLink;
				url = viewtopic_link + ((viewtopic_link.indexOf('?') < 0) ? '?' : '&') + 'view=unread';
			} else if (url.indexOf('hilit=') > -1) {
				url = url.replace(/(&amp;|&|\?)hilit=([^&]*)(&amp;|&)?/, function (str, p1, p2, p3) {
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
			error: function (e, text, ee) {
				if (qr_stop_history) {
					qr_stop_history = false;
				}
				qr_ajax_error();
			},
			success: function (res, x) {
				if (res.result) {
					function qr_show_response(elements, res_function) {
						var reply_pagination = $('#qr_pagination'),
							reply_submit_buttons = $('#qr_submit_buttons'),
							reply_form_submit_buttons = $(quickreply.editor.mainForm).children().children().children('.submit-buttons');
						$('.action-bar .pagination').html(reply_pagination.html());
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
						reply_pagination.remove();
						reply_submit_buttons.remove();
						handle_drops($('.action-bar .pagination'));
						qr_bind_pagination();
						qr_add_ajax(elements);
						quickreply.special.functions.qr_hide_subject(elements);
						$('#qr_posts').trigger('qr_loaded', [$(elements)]);
						var alert_box = $('#phpbb_alert'), alert_delay = (alert_box.is(':visible')) ? 3000 : 0;
						if (alert_box.is(':visible')) {
							alert_box.find('.alert_close').off('click').click(function (e) {
								phpbb.alert.close(alert_box, true);
								e.preventDefault();
							});
						}
						setTimeout(function () {
							$dark.fadeOut(phpbb.alertTime, function () {
								alert_box.hide();
								$(document).off('keydown.phpbb.alert');
							});
						}, alert_delay);
						$loadingIndicator.fadeOut(phpbb.alertTime);
						var qr_scroll_element = (typeof scroll_to_last !== "undefined") ? elements.children('.post').last() : ((qr_get_unread && $('.unreadpost').length) ? $('.unreadpost').first() : elements.children().first());
						if (typeof res_function === "function") {
							res_function(qr_scroll_element);
						}
					}

					if (res.insert) {
						qr_mark_read($('#qr_posts'));
						var reply_temp_container = $(quickreply.editor.tempContainer);
						reply_temp_container.html(res.result);
						qr_replace_history = true;
						qr_show_response(reply_temp_container, function (element) {
							if (quickreply.settings.softScroll) {
								reply_temp_container.slideDown(qr_slide_interval, function () {
									qr_responsive_links(reply_temp_container);
									$('#qr_posts').trigger('qr_completed', [reply_temp_container]);
									reply_temp_container.children().appendTo('#qr_posts');
									reply_temp_container.hide();
									if (quickreply.settings.enableScroll) {
										qr_soft_scroll(element);
									}
								});
							} else {
								var reply_posts = $('#qr_posts');
								reply_posts.trigger('qr_insert_before');
								// Restore the subject of the first post.
								if (quickreply.settings.hideSubject && !reply_posts.find('.post').length) {
									reply_temp_container.find('.post .postbody div h3').first().addClass('first').css('display', '');
								}
								reply_temp_container.show();
								qr_responsive_links(reply_temp_container);
								reply_posts.trigger('qr_completed', [reply_temp_container]);
								var reply_posts_inserted = reply_temp_container.children().appendTo('#qr_posts');
								reply_temp_container.hide();
								if (quickreply.settings.enableScroll) {
									qr_soft_scroll(reply_posts_inserted.first());
								}
							}
						});
					} else {
						var reply_posts = $('#qr_posts');
						if (quickreply.settings.softScroll) {
							reply_posts.slideUp(qr_slide_interval, function () {
								$(this).html(res.result);
								qr_show_response($(this), function (element) {
									reply_posts.slideDown(qr_slide_interval, function () {
										qr_responsive_links(reply_posts);
										reply_posts.trigger('qr_completed', [reply_posts]);
										if (quickreply.settings.enableScroll) {
											qr_soft_scroll(element);
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
								qr_soft_scroll(reply_posts);
							}
						}
					}
					if (typeof result_function === "function") {
						result_function();
					}
				} else if (res.captcha_refreshed) {
					$('#qr_captcha_container').slideUp(qr_slide_interval, function () {
						var alert_box = $('#phpbb_alert'), alert_delay = (alert_box.is(':visible')) ? 3000 : 0;
						if (alert_box.is(':visible')) {
							alert_box.find('.alert_close').off('click').click(function (e) {
								phpbb.alert.close(alert_box, true);
								e.preventDefault();
							});
						}
						setTimeout(function () {
							$dark.fadeOut(phpbb.alertTime, function () {
								alert_box.hide();
								$(document).off('keydown.phpbb.alert');
							});
						}, alert_delay);
						$loadingIndicator.fadeOut(phpbb.alertTime);
						$(this).html(res.captcha_result).slideDown(qr_slide_interval, function () {
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
	}

	/**
	 * pageJump function for QuickReply.
	 *
	 * @param {jQuery} item
	 */
	function qr_page_jump(item) {
		var page = parseInt(item.val());

		if (page !== null && !isNaN(page) && page === Math.floor(page) && page > 0) {
			var perPage = item.attr('data-per-page'),
				baseUrl = item.attr('data-base-url'),
				startName = item.attr('data-start-name');

			if (baseUrl.indexOf('?') === -1) {
				qr_ajax_reload(baseUrl + '?' + startName + '=' + ((page - 1) * perPage));
			} else {
				qr_ajax_reload(baseUrl.replace(/&amp;/g, '&') + '&' + startName + '=' + ((page - 1) * perPage));
			}
		}
	}

	/**
	 * pageJump function for QuickReply.
	 *
	 * @param {jQuery} item
	 */
	function qr_seo_page_jump(item) {
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
					qr_ajax_reload(base_url.replace(/&amp;/g, '&') + (phpEXtest ? '?' : '&') + start_name + '=' + phpbb_seo.page + anchor);
				} else {
					var ext = base_url.match(/\.[a-z0-9]+$/i);

					if (ext) {
						// location.ext => location-xx.ext
						qr_ajax_reload(base_url.replace(/\.[a-z0-9]+$/i, '') + phpbb_seo.delim_start + phpbb_seo.page + ext + anchor);
					} else {
						// location and location/ to location/pagexx.html
						var slash = base_url.match(/\/$/) ? '' : '/';
						qr_ajax_reload(base_url + slash + phpbb_seo.static_pagination + phpbb_seo.page + phpbb_seo.ext_pagination + anchor);
					}
				}
			} else {
				qr_ajax_reload(base_url + anchor);
			}
		}
	}

	/**
	 * Adds Ajax functionality for the pagination.
	 */
	function qr_bind_pagination() {
		if (quickreply.settings.ajaxPagination) {
			$('.action-bar .pagination a:not(.dropdown-trigger, .mark)').click(function (event) {
				event.preventDefault();
				//$(quickreply.editor.mainForm).off('submit').attr('action', $(this).attr('href')).submit();
				qr_ajax_reload($(this).attr('href'));
			});

			$('.action-bar .pagination a.mark:not([href="#unread"])').click(function (event) {
				event.preventDefault();
				qr_ajax_reload($(this).attr('href'));
			});
		}

		$('.action-bar .pagination a.mark[href="#unread"]').click(function (event) {
			event.preventDefault();
			var unread_posts = $('.unreadpost');
			qr_soft_scroll((unread_posts.length) ? unread_posts.first() : $('#qr_posts'));
		});

		$('.action-bar .pagination .page-jump-form :button').click(function () {
			var $input = $(this).siblings('input.inputbox');
			if (!quickreply.settings.ajaxPagination) {
				pageJump($input);
			} else if (quickreply.plugins.seo) {
				qr_seo_page_jump($input);
			} else {
				qr_page_jump($input);
			}
		});

		$('.action-bar .pagination .page-jump-form input.inputbox').on('keypress', function (event) {
			if (event.which === 13 || event.keyCode === 13) {
				event.preventDefault();
				if (!quickreply.settings.ajaxPagination) {
					pageJump($(this));
				} else if (quickreply.plugins.seo) {
					qr_seo_page_jump($(this));
				} else {
					qr_page_jump($(this));
				}
			}
		});

		$('.action-bar .pagination .dropdown-trigger').click(function () {
			var $dropdownContainer = $(this).parent();
			// Wait a little bit to make sure the dropdown has activated
			setTimeout(function () {
				if ($dropdownContainer.hasClass('dropdown-visible')) {
					$dropdownContainer.find('input.inputbox').focus();
				}
			}, 100);
		});
	}

	$('.action-bar .pagination').find('.page-jump-form :button').unbind('click');
	$('.action-bar .pagination').find('.page-jump-form input.inputbox').off('keypress');
	qr_bind_pagination();

	/* Save message when navigating the topic. */
	var restored_message = $(quickreply.editor.messageStorage).html();
	if (restored_message != '') {
		$('#message-box textarea').val(restored_message);
	}

	/* Save message for the full reply form. */
	$('.action-bar .buttons').find('.reply-icon, .locked-icon').click(function (e) {
		e.preventDefault();
		$(quickreply.editor.mainForm).off('submit').find('#qr_full_editor').off('click').click();
	});

	if (quickreply.settings.ajaxSubmit) {
		phpbb.addAjaxCallback('qr_ajax_submit', function (res) {
			if (res.success) {
				var reply_set_data = {qr_no_refresh: 1};
				if (res.merged === 'merged') {
					$.extend(reply_set_data, {qr_get_current: 1});
					if (quickreply.settings.softScroll) {
						$('#qr_posts').find('div.post').last().slideUp(qr_slide_interval, function () {
							var merged_post_id = $(this).attr('id');
							$('#qr_posts').find('.divider').last().remove();
							$(this).remove();
							var decoded_post = $('#decoded_' + merged_post_id), qr_author = $('#qr_author_' + merged_post_id);
							if (decoded_post.length) {
								decoded_post.remove();
							}
							if (qr_author.length) {
								qr_author.remove();
							}
						});
					}
					else {
						$('#qr_posts').one('qr_insert_before', function () {
							var reply_posts = $('#qr_posts'), merged_post = reply_posts.find('div.post').last(),
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
						});
					}
				}
				qr_ajax_reload(res.url.replace(/&amp;/ig, '&'), reply_set_data, function () {
					$('#qr_postform').trigger('ajax_submit_success');

					// Sending form
					$('input[name="post"]').removeAttr('data-clicked');
					$('#message-box textarea').val('').attr('style', 'height: 9em;');

					if ($('#preview').css('display') == 'block') {
						$('#preview').css('display', 'none');
						$('#preview h3').html('');
						$('#preview .content').html('');
						if (quickreply.settings.attachBox) {
							$('#preview').find('dl.attachbox').remove();
						}
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
						$('#preview').find('dl.attachbox').remove();
					}

					if (quickreply.settings.allowedGuest) {
						qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
					}
				}, true);
			} else {
				if (res.NEXT_URL) {
					var reply_set_data = {qr_no_refresh: 1};
					if (res.merged === 'merged') {
						$.extend(reply_set_data, {qr_get_current: 1});
						if (quickreply.settings.softScroll) {
							$('#qr_posts').find('div.post').last().slideUp(qr_slide_interval, function () {
								$('#qr_posts').find('.divider').last().remove();
								$(this).remove();
							});
						} else {
							$('#qr_posts').one('qr_insert_before', function () {
								$('#qr_posts').find('div.post').last().remove();
							});
						}
					}
					qr_ajax_reload(res.NEXT_URL.replace(/&amp;/ig, '&') + '#unread', reply_set_data, function () {
						if (quickreply.settings.allowedGuest) {
							qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
						}
					});
				} else if (res.preview) {
					$('#preview').css('display', 'block');
					$('#preview h3').html(res.PREVIEW_TITLE);
					$('#preview .content').html(res.PREVIEW_TEXT);
					if (quickreply.settings.attachBox) {
						$('#preview').find('dl.attachbox').remove();
						if (res.PREVIEW_ATTACH) {
							$('#preview .content').after(res.PREVIEW_ATTACH);
						}
					}
					if (quickreply.settings.enableScroll) {
						qr_soft_scroll($('#preview'));
					}
					$('#qr_postform').trigger('ajax_submit_preview');
				} else if (res.noapprove) {
					$('input[name="post"]').removeAttr('data-clicked');
					$('#message-box textarea').val('').attr('style', 'height: 9em;');

					if ($('#preview').css('display') == 'block') {
						$('#preview').css('display', 'none');
						$('#preview h3').html('');
						$('#preview .content').html('');
						if (quickreply.settings.attachBox) {
							$('#preview').find('dl.attachbox').remove();
						}
					}

					if ($('#colour_palette').is(':visible')) {
						if (quickreply.plugins.abbc3) {
							$('#abbc3_bbpalette').click();
						} else {
							$('#bbpalette').click();
						}
					}

					if (quickreply.settings.allowedGuest) {
						qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
					}
				} else if (res.post_update) {
					// Send the message again with the updated ID of the last post.
					$(quickreply.editor.mainForm)
						.find('input[name="qr_cur_post_id"], input[name="topic_cur_post_id"]').val(res.post_id)
						.end().find('input[name="post"]').click();
				} else if (quickreply.settings.allowedGuest) {
					qr_ajax_reload(document.location.href, {qr_captcha_refresh: 1});
				}
				// else qr_ajax_error();
			}
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
		container.find('.dropdown-container').each(function () {
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
		container.find('.linklist:not(.navlinks, [data-skip-responsive]), .postbody .post-buttons:not([data-skip-responsive])').each(function () {
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
				allLinks.each(function () {
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
				allLinks.each(function () {
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
					clone.filter('.rightside').each(function () {
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
					filterLastList.each(function () {
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
