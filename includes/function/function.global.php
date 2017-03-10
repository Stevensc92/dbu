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
		'<span class="italique">$1</span>',  
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
	
	$content = nl2br($content);

	return $content;
}

function affich($var)
{
	$var = htmlspecialchars($var);
	return $var; 
}

function addLog($log, $personnage, $pseudo_membre, $file, $line, $etat = "log", $type = "error")
{	
	// Exemple d'utilisation 
	// addLog($sql->errorInfo(), __FILE__, __LINE__, "admin", "error|success");
	global $bdd;
	$mess = '';
	$personnage = (isset($_SESSION['nom_personnage'])) ? $_SESSION['nom_personnage'] : '';
	$pseudo_membre = (isset($_SESSION['membre_pseudo'])) ? $_SESSION['membre_pseudo'] : '';
	
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

function afficherVarBdd($var)
{
	return stripslashes(htmlspecialchars($var, ENT_QUOTES));
}

function includeBbCode($what = "header")
{
	if ($what == "header")
		include(BBCODE_DIR."header.php");
	else if ($what == "bottom")
		include(BBCODE_DIR."bottom.php");
}

function GetMembreMpBloqued($id_membre) // Retourne la liste des personnes bloqués par l'id du membre $id_membre
{
	global $bdd;
	
	$sqlListeMpBloqued = $bdd->prepare("SELECT
											mp_bloqued
										FROM
											site_membres
										WHERE
											id = :id
										");
	$sqlListeMpBloqued->bindValue('id', $id_membre, PDO::PARAM_INT);
	$sqlListeMpBloqued->execute();
	if($sqlListeMpBloqued->rowCount() > 0)
	{
		$ListeMpBloqued = $sqlListeMpBloqued->fetch();
		$Liste = $ListeMpBloqued['mp_bloqued'];
		$Liste = substr($Liste, 1);
		$Liste = explode(',', $Liste);
		return $Liste;
	}
	else
	{
		$Liste = '';
		addLog(array("Erreur d'obtention de la colonne mp_bloqued", $sqlListeMpBloqued->errorInfo()), $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "admin", "error");
		return $Liste;
	}
}

function filtre_var($var, $type_filtre, $options = "")
{
	/*
	 *
	 *	Possibilité type filtre :
	 *	email => FILTER_VALIDATE_EMAIL
	 *	ip => FILTER_VALIDATE_IP
	 *		ipv6 => $options = FILTER_FLAG_IPV6
	 *	int => FILTER_VALIDATE_INT
	 *
	 */

	switch ($type_filtre)
	{
		case "email":
			$filtre = FILTER_VALIDATE_EMAIL;
		break;

		case "ip":
			if ($options != "" && $options == "ipv6")
				$flag = FILTER_FLAG_IPV6;

			$filtre = FILTER_VALIDATE_IP;
		break;

		case "int":
			$filtre = FILTER_VALIDATE_INT;
		break;
	}

	if (isset($flag))
	{
		if (filter_var($var, $filtre, $flag))
			return true;
		else
			return false;
	}
	else
	{
		if (filter_var($var, $filtre))
			return true;
		else
			return false;
	}
}

function UpdateConnecte($var)
{
	global $bdd;
	$Session = new Session();

	// On vérifie la valeur de $var
	// si $var == update, on modifie la ligne déjà existante
	// sinon (si var == add) on ajoute une nouvelle ligne

	if ($var == "update")
	{
		$ip_membre = $_SERVER['REMOTE_ADDR'];
		$id_membre = $_SESSION['membre_id'];

		$sqlUpdateConnecte = $bdd->prepare("UPDATE
												site_connectes
											SET
												connectes_actualisation = :new_time, connectes_ip = :ip
											WHERE
												connectes_id = :id_membre
											");
		$sqlUpdateConnecte->bindValue('new_time', time(), PDO::PARAM_INT);
		$sqlUpdateConnecte->bindValue('ip', $ip_membre, PDO::PARAM_STR);
		$sqlUpdateConnecte->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
		$sqlUpdateConnecte->execute();

		if ($sqlUpdateConnecte->rowCount() == 0)
		{
			$Session->setFlash(ERR_INTERNE);
			addLog(array("Erreur lors de l'utilisation de la fonction UpdateConnecte() sur l'update.", $sqlUpdateConnecte->errorInfo()), "", "", __FILE__, __LINE__, "admin", "error");
		}
	}
	else if ($var == "add")
	{
		$ip_membre = $_SERVER['REMOTE_ADDR'];
		$id_membre = $_SESSION['membre_id'];

		$sqlAddConnecte = $bdd->prepare("INSERT INTO
											site_connectes(connectes_actualisation, connectes_id, connectes_ip, connectes_membre)
										VALUES(
												:time_co, :id_membre, :ip_membre, '1'
											)
										ON DUPLICATE KEY
											UPDATE
												connectes_id = :update_id_membre, connectes_ip = :update_ip_membre, connectes_actualisation = :update_time
										");
		$sqlAddConnecte->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
		$sqlAddConnecte->bindValue('ip_membre', $ip_membre, PDO::PARAM_STR);
		$sqlAddConnecte->bindValue('time_co',  time(), PDO::PARAM_INT);
		$sqlAddConnecte->bindValue('update_id_membre', $id_membre, PDO::PARAM_INT);
		$sqlAddConnecte->bindValue('update_ip_membre', $ip_membre, PDO::PARAM_INT);
		$sqlAddConnecte->bindValue('update_time', time(), PDO::PARAM_INT);
		$sqlAddConnecte->execute();

		if ($sqlAddConnecte->rowCount() == 0)
		{
			$Session->setFlash(ERR_INTERNE);
			addLog(array("Erreur lors de l'utilisation de la fonction UpdateConnecte() sur l'insertion de donnée.", $sqlAddConnecte->errorInfo()), "", "", __FILE__, __LINE__, "admin", "error");
		}
	}
	else if ($var == "delete")
	{
		$id_membre = $_SESSION['membre_id'];

		$sqlDelConnecte = $bdd->prepare("DELETE FROM
											site_connectes
										 WHERE
										 	connectes_id = :id_membre
										");
		$sqlDelConnecte->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
		$sqlDelConnecte->execute();

		if ($sqlDelConnecte->rowCount() == 0)
		{
			$Session->setFlash(ERR_INTERNE);
			addLog(array("Erreur lors de l'utilisation de la fonction UpdateConnecte() sur la suppression de donnée.", $sqlDelConnecte->errorInfo()), "", "", __FILE__, __LINE__, "admin", "error");
		}
	}
}

function generate_Token($nb_char)
{
	/*
	 *
	 * 0 => min-letter
	 * 1 => up-letter
	 * 2 => number
	 *
	 */
	$caractere = array(
					0 => "abcdefghijklmnopqrstuvwxyz",
					1 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
					2 => "0123456789"
				);

	$__token = "";

	// Size of string
	for ($i = 0; $i < $nb_char; $i++)
	{
		$key = mt_rand(0, 2); // key of array

		$max_size_key = (strlen($caractere[$key])) -1;
		$random = mt_rand(0, $max_size_key);

		$__token .= $caractere[$key][$random];
	}

	return $__token;
}

function up_Token($limit = 1800)
{
	// $limit = time  of duration token
	$time = time();
	$diff = $time - $_SESSION['__token_time'];
	if ($diff > $limit)
	{
		$_SESSION['__token'] = generate_Token(35);
		$_SESSION['__token_time'] = time();
	}
}

function check_Token($token)
{
	if ($token == $_SESSION['__token'])
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>