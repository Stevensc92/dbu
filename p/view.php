<script type="text/javascript">
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
</script>
<?php 

function parseZCode($content) {

	// Parsage des balises
	$zcode = array(  
		'`\[i\](.+)\[/i\]`isU',  
		'`\[b\](.+)\[/b\]`isU',
		'`\[s\](.+)\[/s\]`isU',
        '`\[u\](.+)\[/u\]`isU',
		'`\[url\](.+)\[/url\]`isU',
		'`\[url=(.+)\](.+)\[/url\]`isU',
		'`\[img\](.+)\[/img\]`isU',
		'`\[quote\](.+)\[/quote\]`isU',
		'`\[quote nom=(.+)\](.+)\[/quote\]`isU',
		'`\[quote lien=(.+)\](.+)\[/quote\]`isU',
		'`\[size valeur=(.+)\](.+)\[/size\]`isU',
		'`\[spoiler nom=(.+)\](.+)\[/spoiler\]`isU',
		'`\[liste](.+)\[/liste\]`isU',
		'`\[puce](.+)\[/puce\]`isU'
		
	);  
	
	$html = array(  
		'<em>$1</em>',  
		'<strong>$1</strong>',
		'<del>$1</del>',
        '<ins>$1</ins>',
		'<a href="$1">$1</a>',
		'<a href="$1">$2</a>',
		'<img src="$1" alt="Image utilisateur" />',
		'<br /><span class="citation">Citation</span><div class="citation2">$1</div>',
		'<br /><span class="citation">Citation : $1</span><div class="citation2">$2</div>',
		'<br /><span class="citation"><a href="$1">Citation</a></span><div class="citation2">$2</div>',
		'<span class="$1">$2</span>',
		'<div class="c1" onclick="javascript:montrer_spoiler(\'spoiler2\')"><a href="#" onclick="return false">$1</a><dl style="visibility: hidden;" id="spoiler2"><dd>$2<br/></dd></dl></div>',
		'<ul>$1</ul>',
		'<li><span class="liste"></span>&nbsp;$1</li>'
	); 
	
	$content = htmlspecialchars($content);
	$content = preg_replace($zcode, $html, $content);
	
	// parsage des smilies
	 $smiliesName = array(':magicien:', ':colere:', ':diable:', ':ange:', ':ninja:', '&gt;_&lt;', ':pirate:', ':zorro:', ':honte:', ':soleil:', ':\'\\(', ':waw:', ':\\)', ':D', ';\\)', ':p', ':lol:', ':euh:', ':\\(', ':o', ':colere2:', 'o_O', '\\^\\^', ':\\-°');
	$smiliesUrl  = array('magicien.png', 'angry.gif', 'diable.png', 'ange.png', 'ninja.png', 'pinch.png', 'pirate.png', 'zorro.png', 'rouge.png', 'soleil.png', 'pleure.png', 'waw.png', 'smile.png', 'heureux.png', 'clin.png', 'langue.png', 'rire.gif', 'unsure.gif', 'triste.png', 'huh.png', 'mechant.png', 'blink.gif', 'hihi.png', 'siffle.png');
	$smiliesPath = "http://www.siteduzero.com/Templates/images/smilies/";
	
	for ($i = 0, $c = count($smiliesName); $i < $c; $i++) {
		$content = preg_replace('`' . $smiliesName[$i] . '`isU', '<img src="' . $smiliesPath . $smiliesUrl[$i] . '" alt="smiley" />', $content);
	}
	
	// Rtours à la ligne
	$content = preg_replace('`\n`isU', '<br />', $content); 
	
	return $content;

}



if (isset($_POST["string"])) {
	$content = $_POST["string"];
	
	if (get_magic_quotes_gpc()) {
		$content = stripslashes($content);
	}

	echo parseZCode($content); // Ecriture du contenu parsé. 
}
?>
