<div>
	<p>
		<input type="button" value="Gras" onclick="insertTag('[b]', '[/b]', 'textarea')" />
		<input type="button" value="Italique" onclick="insertTag('[i]', '[/i]', 'textarea')" />
		<input type="button" value="Souligner" onclick="insertTag('[u]', '[/u]', 'textarea')" />
		<input type="button" value="Barré" onclick="insertTag('[s]', '[/s]', 'textarea')" />
		<input type="button" value="URL" onclick="insertTag('', '', 'textarea', 'url')" />
		<input type="button" value="Image" onclick="insertTag('[img]', '[/img]', 'textarea')" />
		<input type="button" value="Quote" onclick="insertTag('', '', 'textarea', 'quote')" />
		<input type="button" value="Spoiler" onclick="insertTag('', '', 'textarea', 'spoiler')" />
		<br/>
		<select onchange="insertTag('\[size valeur=' + this.options[this.selectedIndex].value + '\]', '\[/size\]', 'textarea');">
			<option value="none" class="selected" selected="selected">Taille</option>
			<option value="ttpetit">Très très petit</option>
			<option value="tpetit">Très petit</option>
			<option value="petit">Petit</option>
			<option value="gros">Gros</option>
			<option value="tgros">Très gros</option>
			<option value="ttgros">Très très gros</option>
		</select>
		<!--<select onchange="insertTag('\[align valeur=' + this.options[this.selectedIndex].value + '\]', '\[/align\]', 'textarea');">
			<option value="none" class="selected" selected="selected">Alignement</option>
			<option value="left">Gauche</option>
			<option value="center">Centrer</option>
			<option value="right">Droite</option>
			<option value="justify">Justifier</option>
		</select>--><br/>
		<img src="http://users.teledisnet.be/web/mde28256/smiley/smile.gif" alt=":)" onclick="insertTag(' :) ', '', 'textarea');" />
		<img src="http://users.teledisnet.be/web/mde28256/smiley/unsure2.gif" alt=":euh:" onclick="insertTag(' :euh: ', '', 'textarea');" />
		<?php
		$smiliesName = array(':magicien:', ':colere:', ':diable:', ':ange:', ':ninja:', '&gt;_&lt;', ':pirate:', ':zorro:', ':honte:', ':soleil:', ':\'\\(', ':waw:', ':\\)', ':D', ';\\)', ':p', ':lol:', ':euh:', ':\\(', ':o', ':colere2:', 'o_O', '\\^\\^', ':\\-°');
		$smiliesUrl  = array('magicien.png', 'angry.gif', 'diable.png', 'ange.png', 'ninja.png', 'pinch.png', 'pirate.png', 'zorro.png', 'rouge.png', 'soleil.png', 'pleure.png', 'waw.png', 'smile.png', 'heureux.png', 'clin.png', 'langue.png', 'rire.gif', 'unsure.gif', 'triste.png', 'huh.png', 'mechant.png', 'blink.gif', 'hihi.png', 'siffle.png');
		for($i=1; $i <=24; $i++)
		{
			echo '<img src="http://www.siteduzero.com/Templates/images/smilies/'.$smiliesUrl[$i-1].'" alt="'.$smiliesName[$i-1].'" onclick="insertTag(\' '.$smiliesName[$i-1].' \', \'\', \'textarea\');" />';
		}
		?>
	</p>
</div>