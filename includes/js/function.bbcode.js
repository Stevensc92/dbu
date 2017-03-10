function insertTag(startTag, endTag, textareaId, tagType)
{
	var field = document.getElementById(textareaId);
	var scroll = field.scrollTop;
	field.focus();
	
	if (window.ActiveXObject)
	{
		var textRange = document.selection.createRange();            
		var currentSelection = textRange.text;
	}
	else
	{
		var startSelection   = field.value.substring(0, field.selectionStart);
		var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
		var endSelection     = field.value.substring(field.selectionEnd);
	}
	if (tagType)
	{
		switch (tagType)
		{
			case "url":
				endTag = "[/url]";
					if (currentSelection)
					{
						if (currentSelection.indexOf("http://") == 0 || currentSelection.indexOf("https://") == 0 || currentSelection.indexOf("ftp://") == 0 || currentSelection.indexOf("www.") == 0)
						{
							var label = prompt("Quel est le libellé du lien ?") || "";
							startTag = "[url=" + currentSelection + "]";
							currentSelection = label;
						}
						else
						{
							var URL = prompt("Quelle est l'url ?");
							startTag = "[url=" + URL + "]";
						}
					} 
					else
					{
						var URL = prompt("Quelle est l'url ?") || "";
						var label = prompt("Quel est le libellé du lien ?") || "";
						startTag = "[url=" + URL + "]";
						currentSelection = label;                     
					}
				break;
			case "quote":
				endTag = "[/quote]";
					if (currentSelection)
					{
						if (currentSelection.length > 30)
						{
							var auteur = prompt("Quel est l'auteur de la citation ?") || "";
								startTag = "[quote nom=" + auteur + "]";
						}
						else
						{
							var citation = prompt("Quelle est la citation ?") || "";
							startTag = "[quote nom=" + currentSelection + "]";
							currentSelection = citation;    
						}
					}
					else
					{
						var auteur = prompt("Quel est l'auteur de la citation ?") || "";
						var citation = prompt("Quelle est la citation ?") || "";
						startTag = "[quote nom=" + auteur + "]";
						currentSelection = citation;    
					}
				break;
			case "spoiler":
				endTag = "[/spoiler]";
				if (currentSelection)
				{
					if (currentSelection.length > 30)
					{
						var auteur = prompt("Quel est le titre du spoiler?") || "";
						startTag = "[spoiler nom=" + auteur + "]";
					}
					else
					{
						var citation = prompt("Quel est le texte du spoiler ?") || "";
						startTag = "[spoiler nom=" + currentSelection + "]";
						currentSelection = citation;    
					}
				}
				else
				{
					var auteur = prompt("Quel est l'auteur du spoiler?") || "";
					var citation = prompt("Quel est le texte du spoiler ?") || "";
					startTag = "[spoiler nom=" + auteur + "]";
					currentSelection = citation;    
				}
				break;
		}
	}
	if (window.ActiveXObject)
	{
		textRange.text = startTag + currentSelection + endTag;
		textRange.moveStart('character', -endTag.length-currentSelection.length);
		textRange.moveEnd('character', -endTag.length);
		textRange.select();  
	}
	else
	{ // Ce n'est pas IE
		field.value = startSelection + startTag + currentSelection + endTag + endSelection;
		field.focus();
		field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
	}
	field.scrollTop = scroll;
}

function preview(textareaId, previewDiv)
{
	var field = textareaId.value;
	if (document.getElementById('previsualisation').checked && field)
	{
	var smiliesName = new Array(':magicien:', ':colere:', ':diable:', ':ange:', ':ninja:', '\]_\[', ':pirate:', ':zorro:', ':honte:', ':soleil:', ':\'\\(', ':waw:', ':\\)', ':D', ';\\)', ':p', ':lol:', ':euh:', ':\\(', ':o', ':colere2:', 'o_O', '\\^\\^', ':\\-°');
	var smiliesUrl  = new Array('magicien.png', 'angry.gif', 'diable.png', 'ange.png', 'ninja.png', 'pinch.png', 'pirate.png', 'zorro.png', 'rouge.png', 'soleil.png', 'pleure.png', 'waw.png', 'smile.png', 'heureux.png', 'clin.png', 'langue.png', 'rire.gif', 'unsure.gif', 'triste.png', 'huh.png', 'mechant.png', 'blink.gif', 'hihi.png', 'siffle.png');
	var smiliesPath = "http://www.siteduzero.com/Templates/images/smilies/";
		field = field.replace(/&/g, '&amp;');
		field = field.replace(/</g, '\[').replace(/>/g, '\]');
		field = field.replace(/\n/g, '<br/>').replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			field = field.replace(/\[b\]([\s\S]*?)\[\/b\]/g, '<strong>$1</strong>');
			field = field.replace(/\[i\]([\s\S]*?)\[\/i\]/g, '<em>$1</em>');
			field = field.replace(/\[u\]([\s\S]*?)\[\/u\]/g, '<ins>$1</ins>');
			field = field.replace(/\[s\]([\s\S]*?)\[\/s\]/g, '<del>$1</del>');
			field = field.replace(/\[url\]([\s\S]*?)\[\/url\]/g, '<a href="$1">$1</a>');
			field = field.replace(/\[url="([\s\S]*?)"\]([\s\S]*?)\[\/url\]/g, '<a href="$1" title="$2">$2</a>');
			field = field.replace(/\[img\]([\s\S]*?)\[\/img\]/g, '<img src="$1" alt="Image" />');
			field = field.replace(/\[quote nom=(.*?)\]([\s\S]*?)\[\/quote\]/g, '<br /><span class="citation">Citation : $1</span><div class="citation2">$2</div>');
			field = field.replace(/\[quote lien=(.*?)\"\]([\s\S]*?)\[\/quote\]/g, '<br /><span class="citation"><a href="$1">Citation</a></span><div class="citation2">$2</div>');
			field = field.replace(/\[quote nom=(.*?) lien=(.*?)\]([\s\S]*?)\[\/quote\]/g, '<br /><span class="citation"><a href="$2">Citation : $1</a></span><div class="citation2">$3</div>');
			field = field.replace(/\[quote lien=(.*?) nom=(.*?)\]([\s\S]*?)\[\/quote\]/g, '<br /><span class="citation"><a href="$1">Citation : $2</a></span><div class="citation2">$3</div>');
			field = field.replace(/\[quote\]([\s\S]*?)\[\/quote\]/g, '<br /><span class="citation">Citation</span><div class="citation2">$1</div>');
			field = field.replace(/\[size valeur=(.*?)\]([\s\S]*?)\[\/size\]/g, '<span class="$1">$2</span>');
			field = field.replace(/\[spoiler nom=(.*?)\]([\s\S]*?)\[\/spoiler\]/g, '<div class="c1" onclick="javascript:montrer_spoiler(\'spoiler2\')"><a href="#" onclick="return false">$1</a><dl style="visibility: hidden;" id="spoiler2"><dd>$2<br></dd></dl></div>');
			
		for (var i=0, c=smiliesName.length; i<c; i++)
		{
			field = field.replace(new RegExp(" " + smiliesName[i] + " ", "g"), "&nbsp;<img src=\"" + smiliesPath + smiliesUrl[i] + "\" alt=\"" + smiliesUrl[i] + "\" />&nbsp;");
		}
	document.getElementById(previewDiv).innerHTML = field;
	}
}

function getXMLHttpRequest()
{
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject)
	{
		if (window.ActiveXObject)
		{
			try
			{
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e)
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else
		{
			xhr = new XMLHttpRequest();
		}
	}
	else
	{
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
return xhr;
}


function view(textareaId, viewDiv)
{
	var content = encodeURIComponent(document.getElementById(textareaId).value);
	var xhr = getXMLHttpRequest();
	if (xhr && xhr.readyState != 0)
	{
		xhr.abort();
		delete xhr;
	}
	xhr.onreadystatechange = function()
	{
		if (xhr.readyState == 4 && xhr.status == 200)
		{
			document.getElementById(viewDiv).innterHTML = "<div style=\"text-align: center;\"><img src='images/ajax-loader.gif' /></div>";
			if(xhr.status == 200)
			{
				document.getElementById(viewDiv).innerHTML = xhr.responseText;
			}
		}
		else if (xhr.readyState == 3)
		{
			document.getElementById(viewDiv).innerHTML = "<div style=\"text-align: center;\"><img src='images/ajax-loader.gif' /><br/> (Si le chargement dure, merci de prévenir le webmaster.)</div>";
		}
	}
	xhr.open("POST", "p/view.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("string=" + content);
}
function viewf(textareaId, viewDiv)
{
	var content = encodeURIComponent(document.getElementById(textareaId).value);
	var xhr = getXMLHttpRequest();
	if (xhr && xhr.readyState != 0)
	{
		xhr.abort();
		delete xhr;
	}
	xhr.onreadystatechange = function()
	{
		if (xhr.readyState == 4 && xhr.status == 200)
		{
			document.getElementById(viewDiv).innerHTML = xhr.responseText;
		}
		else if (xhr.readyState == 3)
		{
			document.getElementById(viewDiv).innerHTML = "<div style=\"text-align: center;\">Chargement en cours... (Si le chargement dure, merci de prévenir le webmaster.)</div>";
		}
	}
	xhr.open("POST", "../p/view.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("string=" + content);
}
function montrer_spoiler(value)
{
	var actual=document.getElementById(value).style.visibility;
	if (actual=='visible')
	{
		document.getElementById(value).style.visibility='hidden';
	}
	else
	{
		document.getElementById(value).style.visibility='visible';
	}
}