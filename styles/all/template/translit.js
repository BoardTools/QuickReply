/**
 * Функция перевода транслита в нормальный русский язык добавлена 01/01/2002 Андреем Мазлиным
 * Updated in 2015.
 */
function translit() {
	var buf = '', selection = null, txtarea = document.forms[form_name].elements[text_name], regexp, i, start, end;

	if (document.selection) {
		selection = document.selection.createRange();
		buf = selection.text;
	}
	else if (typeof (txtarea.selectionStart) == "number") {
		start = txtarea.selectionStart;
		end = txtarea.selectionEnd;
		if (start != end)
		{
			buf = txtarea.value.substr(start, end - start);
		}
		else
		{
			buf = txtarea.value;
		}
	}
	else {
		buf = txtarea.value;
	}

	for (i = 0; i < quickreply.settings.forain_lang_cap.length; i++) {
		eval('regexp = /'+quickreply.settings.forain_lang_cap[i]+'/g');
		buf = buf.replace(regexp, quickreply.settings.this_lang_cap[i]);
	}

	for (i = 0; i < quickreply.settings.forain_lang.length; i++) {
		eval('regexp = /'+quickreply.settings.forain_lang[i]+'/g');
		buf = buf.replace(regexp, quickreply.settings.this_lang[i]);
	}

	if (selection) {
		selection.text = buf;
	}
	else {
		if (start != end) {
			txtarea.value = txtarea.value.substr(0, start) + buf + txtarea.value.substr(end);
		}
		else {
			txtarea.value = buf;
		}
	}
	txtarea.focus();
}
