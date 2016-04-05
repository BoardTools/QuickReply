/* global quickreply, grecaptcha */
; (function ($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106

	/*********************/
	/* Quick Nick Plugin */
	/*********************/
	function getHexColor(color) {
		color = color.replace(/\s/g,"");
		var colorRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i);
		colorHEX = '';
		for (var i=1;  i<=3; i++) {
			colorHEX += Math.round((colorRGB[i][colorRGB[i].length-1]=="%"?2.55:1)*parseInt(colorRGB[i])).toString(16).replace(/^(.)$/,'0$1');
		}
		return "#" + colorHEX;
	}
	function quickNick(link) {
		var nickname = link.text(),
			comma = (quickreply.settings.enableComma) ? ', ' : '\r\n',
			color = (link.hasClass('username-coloured')) ? link.css('color') : false,
			qr_color = (quickreply.settings.colouredNick && color) ? '=' + getHexColor(color) : '';
		if (!quickreply.settings.enableBBCode) insert_text(nickname + comma, false);
		else if (!quickreply.settings.quickNickRef) insert_text('[b]' + nickname + '[/b]' + comma, false);
		else insert_text('[ref' + qr_color + ']' + nickname + '[/ref]' + comma, false);
	};
	function qr_quicknick(evt, link, color) {
		// Get cursor coordinates
		if (!evt) evt = window.event;
		evt.preventDefault();
		var pageX = evt.pageX || evt.clientX + document.documentElement.scrollLeft; // FF || IE
		var pageY = evt.pageY || evt.clientY + document.documentElement.scrollTop;
		// Which mouse button is pressed?
		var key = evt.button || evt.which || null; // IE || FF || Unknown
		//Get nick and id
		var vievprofile_url = link.attr('href');
		var nickname = link.text();
		var qrNickAlert = $('<div class="dropdown" style="position: absolute; top: ' + (pageY + 8) + 'px; left: ' + (pageX > 184 ? pageX - 111 : pageX - 20) + 'px;"><ul class="panel dropdown-contents"><li class="post bg3"><a href="#postform" class="qr_quicknick" title="' + quickreply.language.QUICKNICK_TITLE + '">' + quickreply.language.QUICKNICK + '</a></li><li class="post bg3"><a href="' + vievprofile_url + '" class="qr_profile">' + quickreply.language.PROFILE + '</a></li></ul></div>').appendTo('body');
		var comma = quickreply.settings.enableComma ? ', ' : '\r\n';
		var qr_colour_nickname = quickreply.settings.colouredNick;
		var qr_color = (qr_colour_nickname && color) ? '=' + getHexColor(color) : '=#000000';
		$('a.qr_quicknick', qrNickAlert).mousedown(function() {
			quickNick(link);
			qrNickAlert.remove();
			return false;
		});
		$('a.qr_profile', qrNickAlert).mousedown(function(e) {
			e.preventDefault();
			document.location.href = vievprofile_url;
			qrNickAlert.remove();
			return false;
		});
		setTimeout(function() {
			$(document.body).mousedown(function() {
				qrNickAlert.remove();
				$(document.body).unbind('mousedown');
			});
		}, 10);
	}
	function insert_text(text) {
		var textarea;
		textarea = document.forms['postform'].elements['message'];
		textarea.value = textarea.value + text;
		textarea.focus();
	}
	$('p.author').on('click', 'a.username', function(e) {
		qr_quicknick(e, $(this), false);
	});
	$('p.author').on('click', 'a.username-coloured', function(e) {
		qr_quicknick(e, $(this), $(this).css('color'));
	});
	$(document).ready(function() {
		$('a.username, a.username-coloured').each(function() {
			$(this).attr('title', quickreply.language.QUICKNICK);
		});
	});
}) (jQuery, window, document);
