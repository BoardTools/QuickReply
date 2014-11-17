//
// Функции перевода транслита в нормальный русский язык добавлена 01/01/2002 Андреем Мазлиным
//
var lat = new Array("je","jo","ayu","ay","aj","oju","oje","oja","oj","uj","yi","ya","ja","ju","yu","ja","juju","aja","y","zh","i'","shch","sch","ch","sh","ea","a","b","v","w","g","d","e","z","i","k","l","m","n","o","p","r","s","t","u","f","x","c","ea","'e","'","`","j","h");
var cyr = new Array("э","ё","aю","ай","ай","ою","ое","оя","ой","уй","ый","я","я","ю","ю","я","юю","ая","ы","ж","й","щ","щ","ч","ш","э","а","б","в","в","г","д","е","з","и","к","л","м","н","о","п","р","с","т","у","ф","х","ц","э","э","ь","ъ","й","х");

var latcap = new Array("Yo","Jo","Ey","Je","Ay","Oy","Oj","Uy","Uj","Ya","Ja","Ju","Yu","Ja","Y","Zh","I'","Sch","Ch","Sh","Ea","Tz","A","B","V","W","G","D","E","Z","I","K","L","M","N","O","P","R","S","T","U","F","X","C","EA","J","H");
var cyrcap = new Array("Ё","Ё","Ей","Э","Ай","Ой","Ой","Уй","Уй","Я","Я","Ю","Ю","Я","Ы","Ж","Й","Щ","Ч","Ш","Э","Ц","А","Б","В","В","Г","Д","Е","З","И","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Э","Й","Х");

function translit()
{
	var buf = new String;
	var selection = false;
	var txtarea = document.forms[form_name].elements[text_name];
	var regexp;
	var i;
	
	if (document.selection)
	{
		selection = document.selection.createRange().text;
	}
	
	if (selection)
	{
		buf = selection;
	}
	else
	{
		buf = txtarea.value;
	}
	
	for (i = 0; i < latcap.length; i++)
	{
		eval('regexp = /'+latcap[i]+'/g');
		buf = buf.replace(regexp, cyrcap[i]);
	}
	
	for (i = 0; i < lat.length; i++)
	{
		eval('regexp = /'+lat[i]+'/g');
		buf = buf.replace(regexp, cyr[i]);
	}

	if (selection)
	{
		eval('regexp = /'+selection+'/');
		txtarea.value = txtarea.value.replace(regexp, buf);
	}
	else
	{
		txtarea.value = buf;
	}
	
	selection = '';
	
	txtarea.focus();

	return;
}
