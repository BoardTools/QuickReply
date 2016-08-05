/* global quickreply */

// Initial adjustments.
var quickreply = {};

phpbb.plupload = {};

// External plugins compatible with QuickReply.
quickreply.plugins = {};

// Configuration settings for QuickReply.
quickreply.settings = {
	ajaxPagination: false,
	ajaxSubmit: true,
	allowBBCode: true,
	allowedGuest: false,
	attachBox: true,
	colouredNick: true,
	ctrlEnter: true,
	enableBBCode: true,
	enableComma: true,
	enableScroll: true,
	enableWarning: false,
	fixEmptyForm: true,
	formType: 1,
	fullQuote: true,
	fullQuoteAllowed: true,
	hideSubjectBox: false,
	lastQuote: true,
	pluploadEnabled: false,
	quickNick: true,
	quickNickString: false,
	quickNickUserType: false,
	quickNickRef: true,
	quickNickPM: true,
	quickQuote: true,
	quickQuoteButton: true,
	quickQuoteLink: false,
	saveReply: true,
	scrollInterval: 500,
	softScroll: true,
	sourcePost: true,
	unchangedSubject: false
};

// Useful variables for QuickReply.
quickreply.editor = {
	attachPanel: '#attach-panel',
	boardURL: './',
	currentPost: '',
	mainForm: '#qr_postform',
	messageStorage: '#qr_message',
	postSelector: 'div.post',
	postTitleSelector: '.postbody div h3:first',
	profileLinkSelector: 'a.username, a.username-coloured',
	tempContainer: '#qr_temp_container',
	textareaSelector: '#message-box textarea',
	unreadPostSelector: '.unreadpost',
	viewtopicLink: './'
};

// Global functions for QuickReply.
quickreply.functions = {
	/**
	 * Converts RGB function to the hex string.
	 *
	 * @param {string} color Color in RGB function format
	 * @returns {string}
	 */
	getHexColor: function(color) {
		color = color.replace(/\s/g, "");
		var colorRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i), colorHEX = '';
		for (var i = 1; i <= 3; i++) {
			colorHEX += Math.round((colorRGB[i][colorRGB[i].length - 1] == "%" ? 2.55 : 1) *
				parseInt(colorRGB[i])).toString(16).replace(/^(.)$/, '0$1');
		}
		return "#" + colorHEX;
	},
	/**
	 * Generates an HTML string for a link from an object with parameters.
	 *
	 * @param {object} parameters Object with HTML attributes
	 *                            (href, id, className, title) and link text
	 * @returns {string}
	 */
	makeLink: function(parameters) {
		if (typeof parameters !== 'object') {
			return '';
		}
		var link = '<a';
		link += (parameters.href) ? ' href="' + parameters.href + '"' : ' href="#"';
		if (parameters.id) {
			link += ' id="' + parameters.id + '"';
		}
		if (parameters.className) {
			link += ' class="' + parameters.className + '"';
		}
		if (parameters.title) {
			link += ' title="' + parameters.title + '"';
		}
		link += '>' + ((parameters.text) ? parameters.text : '') + '</a>';
		return link;
	}
};

// Language variables for QuickReply.
quickreply.languageArray = {
	en: {
		demo: {
			NOT_AVAILABLE: 'Sorry, this feature is not available in this demo.<br>You can test it on an original phpBB board.'
		},
		FULLSCREEN: 'Fullscreen editor',
		FULLSCREEN_EXIT: 'Exit fullscreen',
		INFORMATION: 'Information',
		loading: {
			text: 'Loading',
			SUBMITTED: 'Demo loading completed.',
			SUBMITTING: 'No data has been submitted because this is a demo.<br>Displaying loading text for you...'
		},
		TYPE_REPLY: 'Type your reply here...'
	},
	ru: {
		demo: {
			NOT_AVAILABLE: 'Извините, данная функция недоступна в этой демонстрации.<br>Вы можете протестировать её на оригинальной конференции phpBB.'
		},
		FULLSCREEN: 'Полноэкранный редактор',
		FULLSCREEN_EXIT: 'Выйти из полноэкранного режима',
		INFORMATION: 'Информация',
		loading: {
			text: 'Загрузка',
			SUBMITTED: 'Демонстрационная загрузка завершена.',
			SUBMITTING: 'Данные не были отправлены, поскольку это демонстрация.<br>Показ текста загрузки для вас...'
		},
		TYPE_REPLY: 'Введите свой ответ здесь...'
	}
};

quickreply.language = quickreply.languageArray.en;

// Initialize QuickReply demo.
$(document).ready(function() {
	$(window).off('beforeunload.quickreply');

	$('[data-toggle="tooltip"]').tooltip();

	function demoSubmission(e) {
		e.preventDefault();
		quickreply.loading.start();
		quickreply.loading.setExplain(quickreply.language.loading.SUBMITTING);
		setTimeout(function() {
			quickreply.loading.stop(true);
			phpbb.alert(quickreply.language.INFORMATION, quickreply.language.loading.SUBMITTED);
		}, 5000);
	}

	quickreply.$.mainForm.submit(demoSubmission)
		.find(".submit-buttons button").off().click(demoSubmission).end()
		.find("#add_files").off().click(function(e) {
		e.preventDefault();
		phpbb.alert(quickreply.language.INFORMATION, quickreply.language.demo.NOT_AVAILABLE);
	});

	$('<div id="header-nav"></div><div id="footer-nav"></div>').css('height', '0').appendTo('body');
});
