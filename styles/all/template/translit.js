/**
 * Функция перевода транслита в нормальный русский язык добавлена 01/01/2002 Андреем Мазлиным
 * Updated in 2015.
 */
function translit() {
	var lat = new Array("je","jo","ayu","ay","aj","oju","oje","oja","oj","uj","yi","ya","ja","ju","yu","ja","y","zh","i'","shch","sch","ch","sh","ea","a","b","v","w","g","d","e","z","i","k","l","m","n","o","p","r","s","t","u","f","x","c","'e","'","`","j","h");

	var cyr = new Array("э","ё","aю","ай","ай","ою","ое","оя","ой","уй","ый","я","я","ю","ю","я","ы","ж","й","щ","щ","ч","ш","э","а","б","в","в","г","д","е","з","и","к","л","м","н","о","п","р","с","т","у","ф","х","ц","э","ь","ъ","й","х");

	var latcap = new Array("Yo","Jo","Ey","Je","Ay","Oy","Oj","Uy","Uj","Ya","Ja","Ju","Yu","Ja","Y","Zh","I'","Sch","Ch","Sh","Ea","Tz","A","B","V","W","G","D","E","Z","I","K","L","M","N","O","P","R","S","T","U","F","X","C","EA","J","H");

	var cyrcap = new Array("Ё","Ё","Ей","Э","Ай","Ой","Ой","Уй","Уй","Я","Я","Ю","Ю","Я","Ы","Ж","Й","Щ","Ч","Ш","Э","Ц","А","Б","В","В","Г","Д","Е","З","И","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Э","Й","Х");

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

	for (i = 0; i < latcap.length; i++) {
		eval('regexp = /'+latcap[i]+'/g');
		buf = buf.replace(regexp, cyrcap[i]);
	}

	for (i = 0; i < lat.length; i++) {
		eval('regexp = /'+lat[i]+'/g');
		buf = buf.replace(regexp, cyr[i]);
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
