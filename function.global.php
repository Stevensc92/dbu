<?php
/*
 *
 * Fichier function.global.php
 * Fonction global du site (hors jeu)
 *
 */

function is_co() // Fonction vérifiant si le membre est connecté
{
	if (isset($_SESSION['membre_id']))
		return true;
	else
		return false;
}

function HashPassword($password) // Function pour hasher les mdp
{
	// Méthode pour hasher les password
	$prefix = "dbuniverse";
	$suffix = "kassStev";
	$newPassword = $prefix.$password.$suffix;
	$password = sha1($newPassword);
	return $password;
}

function MyPrintR($var) // Fonction de debug
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function NumberFormat($nombre) // Fonction pour formater les nombres
{
	$Nombre = number_format($nombre, 0, ',', ' ');
	
	return $Nombre;
}

function parseZCode($content) // Fonction pour parser le code, le mettre en couleur etc
{
	// Fonction pour le BB code /!\ À améliorer
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
		'<img src="$1" alt="$1" />',
		'<br /><span class="citation">Citation</span><div class="citation2">$1</div>',
		'<br /><span class="citation">Citation : $1</span><div class="citation2">$2</div>',
		'<br /><span class="citation"><a href="$1">Citation</a></span><div class="citation2">$2</div>',
		'<span class="$1">$2</span>',
		'<div align="center">
			<div class="quotetitle">
				<input type="button" value="$1" onclick="if (this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display != \'\') { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'\'; this.innerText = \'\'; this.value = \'$1\'; } else { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'none\'; this.innerText = \'\'; this.value = \'$1\'; }" />
			</div>
			<div class="quotecontent">
				<div style="display:none;">
					$2
				</div>
			</div>
		</div>',
		'<ul>$1</ul>',
		'<li><span class="list"></span>&nbsp;$1</li>'
	); 
	
	$content = htmlspecialchars($content);
	$content = stripslashes($content);
	$content = preg_replace($zcode, $html, $content);
	
	// parsage des smilies
	$smiliesName = array(':magicien:', ':colere:', ':diable:', ':ange:', ':ninja:', '&gt;_&lt;', ':pirate:', ':zorro:', ':honte:', ':soleil:', ':\'\\(', ':waw:', ':\\)', ':D', ';\\)', ':p', ':lol:', ':euh:', ':\\(', ':o', ':colere2:', 'o_O', '\\^\\^', ':\\-°');
	$smiliesUrl  = array('magicien.png', 'angry.gif', 'diable.png', 'ange.png', 'ninja.png', 'pinch.png', 'pirate.png', 'zorro.png', 'rouge.png', 'soleil.png', 'pleure.png', 'waw.png', 'smile.png', 'heureux.png', 'clin.png', 'langue.png', 'rire.gif', 'unsure.gif', 'triste.png', 'huh.png', 'mechant.png', 'blink.gif', 'hihi.png', 'siffle.png');
	$smiliesPath = "http://www.siteduzero.com/Templates/images/smilies/";
	
	for ($i = 0, $c = count($smiliesName); $i < $c; $i++) {
		$content = preg_replace('`' . $smiliesName[$i] . '`isU', '<img src="' . $smiliesPath . $smiliesUrl[$i] . '" alt="smiley" />', $content);
	}
	
	// Rtours à la ligne
	$content = preg_replace('`\n`isU', '<br/>', $content); 
	
	return $content;
}

/*function addLog($log, $personnage, $pseudo_membre, $file, $line, $etat = "log", $type = "error")
{
	global $bdd;
	$Session = new Session();

	MyPrintR($log);
	return false;
	
	// Exemple d'utilisation 
	// addLog($sql->errorInfo(), __FILE__, __LINE__, "admin", "error|success");
	global $bdd;
	$mess = '';
	if($etat == '') $etat = 'log';
	if(is_array($log))
	{
		foreach($log as $Cle => $Value)
		{
			if(is_array($Value))
			{
				foreach($Value as $VCle => $VValue)
				{
					$mess .= "Clé : $Cle : $VCle => $VValue<br/>";
				}
			}
			else
			{
				$mess .= "Clé : $Cle => $Value<br/>";
			}
		}
	}
	else
	{
		$mess = $log;
	}
	switch($etat)
	{
		case "log":
			$addLog = $bdd->prepare("INSERT	INTO
										log(log, nom_personnage, nom_membre, file, ligne, date, type)
									VALUES
									(
										:log,
										:nom_personnage,
										:nom_membre,
										:file,
										:ligne,
										:date,
										:type
									)
									");
			$addLog->bindValue('log', $mess, PDO::PARAM_STR);
			$addLog->bindValue('nom_personnage', $personnage, PDO::PARAM_STR);
			$addLog->bindValue('nom_membre', $pseudo_membre, PDO::PARAM_STR);
			$addLog->bindValue('file', $file, PDO::PARAM_INT);
			$addLog->bindValue('ligne', $line, PDO::PARAM_INT);
			$addLog->bindValue('date', time(), PDO::PARAM_INT);
			$addLog->bindValue('type', $type, PDO::PARAM_STR);
			$addLog->execute();
		break;
		
		case "admin":
			$addLog = $bdd->prepare("INSERT	INTO
										log_admin(log, nom_personnage, nom_membre, file, ligne, date, type)
									VALUES
									(
										:log,
										:nom_personnage,
										:nom_membre,
										:file,
										:ligne,
										:date,
										:type
									)
									");
			$addLog->bindValue('log', $mess, PDO::PARAM_STR);
			$addLog->bindValue('nom_personnage', $personnage, PDO::PARAM_STR);
			$addLog->bindValue('nom_membre', $pseudo_membre, PDO::PARAM_STR);
			$addLog->bindValue('file', $file, PDO::PARAM_INT);
			$addLog->bindValue('ligne', $line, PDO::PARAM_INT);
			$addLog->bindValue('date', time(), PDO::PARAM_INT);
			$addLog->bindValue('type', $type, PDO::PARAM_STR);
			$addLog->execute();
		break;
	}
}*/

function addLog($log)
{
	MyPrintR($log);
}

function mepd($date)
{
	// Affichage de la date par rapport à un timestamp
	if(intval($date) == 0) return $date;
        
	$tampon = time();
	$diff = $tampon - $date;
        
	$dateDay = date('d', $date);
	$tamponDay = date('d', $tampon);
	$diffDay = $tamponDay - $dateDay;
        
	if($diff < 60 && $diffDay == '°')
	{
		return 'Il y a '.$diff.'s';
	}
        
	else if($diff < 600 && $diffDay == 0)
	{
		return 'Il y a '.floor($diff/60).'m et '.floor($diff%60).'s';
	}
        
	else if($diff < 3600 && $diffDay == 0)
	{
		return 'Il y a '.floor($diff/60).'m';
	}
        
	else if($diff < 7200 && $diffDay == 0)
	{
		return 'Il y a '.floor($diff/3600).'h et '.floor(($diff%3600)/60).'m';
	}
	
	else if($diff < 24*3600 && $diffDay == 0)
	{
		return 'Aujourd\'hui à '.date('H\hi', $date);
	}
	
	else if($diff < 48*3600 && $diffDay == 1)
	{
		return 'Hier à '.date('H\hi', $date);
	}
	
	else
	{
		return 'Le '.date('d/m/Y', $date).' à '.date('h\hi', $date).'';
	}
}
?>