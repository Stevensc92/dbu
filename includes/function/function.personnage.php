<?php
/*
 *
 * Fichier function.personnage.php
 * Fonction relatives aux personnages
 *
 */

function getInfoPersonnage($id_membre, $id_perso)
{
	global $bdd;
	global $ArrayPersonnage;

	$Session = new Session();
	
	$sqlInfosPerso = $bdd->prepare("SELECT 
										*, nom_personnage
									FROM 
										jeu_liste_membre_perso
									LEFT JOIN 
										jeu_liste_personnage
									ON 
										jeu_liste_personnage.id_perso = jeu_liste_membre_perso.id_perso
									WHERE 
										id_membre = :id_membre && jeu_liste_membre_perso.id_perso = :id_perso
									");
	$sqlInfosPerso->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlInfosPerso->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlInfosPerso->execute();

	if ($sqlInfosPerso->rowCount() > 0)
	{
		$InfosPerso = $sqlInfosPerso->fetch();
	}
	else
	{
		addLog(array("Erreur lors de l'obtention des informations, de la fonction getInfoPersonnage.", $sqlInfosPerso->errorInfo()),
					 $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE);
		$InfosPerso='';
	}
	return $InfosPerso;
}

function getInfosStatsPerso($id_membre, $id_perso)
{
	global $bdd;
	global $ArrayPersonnage;

	$Session = new Session();

	$sqlStats = $bdd->prepare("SELECT
									stats_puissance, stats_defense, stats_magie,
									stats_chance, stats_vitesse, stats_concentration,
									stats_vie, stats_energie
								FROM
									jeu_liste_membre_perso
								WHERE
									id_membre = :id_membre && id_perso = :id_perso
								");
	$sqlStats->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlStats->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlStats->execute();

	if ($sqlStats->rowCount() > 0)
		$Stats = $sqlStats->fetch();
	else
	{
		addLog(array("Erreur lors de l'obtention des statistiques, de la fonction getInfosStatsPerso.<br/>
						".$sqlStats->rowCount()." lignes affectées.", 
						$sqlStats->errorInfo()),
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE);
		$Stats = "";
	}
	
	return $Stats;
}

function getInfosFightPerso($id_membre, $id_perso)
{
	global $bdd;
	global $ArrayPersonnage;
	$Session = new Session();
	
	$sqlStatFight = $bdd->prepare("SELECT
										match_victoire, match_defaite,
										match_tuer, match_nul
									FROM
										jeu_liste_membre_perso
									WHERE
										id_membre = :id_membre && id_perso = :id_perso
									");
	$sqlStatFight->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlStatFight->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlStatFight->execute();
	if ($sqlStatFight->rowCount() > 0)
	{
		$StatFight = $sqlStatFight->fetch();
		return $StatFight;
	}
	else
	{

		addLog(array("Erreur lors de l'obtention des infos fight, de la fonction getInfosFightPerso.", $sqlStatFight->errorInfo()),
				 $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE, 'error');
		$StatFight = '';
		return $StatFight;
	}
}

function getPuissancePersonnage($id_membre, $id_perso, $where = 'perso')
{
	global $bdd;
	$Session = new Session();
	
	$Infos = getInfosStatsPerso($id_membre, $id_perso);
	
	$Puissance = $Infos['stats_puissance'];
	$Defense = $Infos['stats_defense'];
	$Magie = $Infos['stats_magie'];
	$Chance = $Infos['stats_chance'];
	$Vitesse = $Infos['stats_vitesse'];
	$Concentration = $Infos['stats_concentration'];

	$DegatMax = ($Puissance > 0) ? ceil( ceil(exp(2)) * pow($Puissance, 0.85)) : 0;
	$DegatMin = ($Puissance > 0) ? ceil($DegatMax * 0.65) : 0;
	//$DegatMax = ceil(exp(2.85));

	$DefenseMax = ($Defense > 0) ? ceil( ceil(exp(2)) * pow($Defense, 0.756)) : 0;
	$DefenseMin = ($Defense > 0) ? ceil($DefenseMax * 0.40) : 0;
	
	//$MagieMax = ($Magie > 0) ? ceil($Magie*($Magie/2)/1.5) : 0;
	$MagieMax = ($Magie > 0) ? ceil( ceil(exp(2.85)) * pow($Magie, 0.75)) : 0;
	$MagieMin = ($Magie > 0) ? ceil($MagieMax * 0.65) : 0;
	
	if ($where == 'perso')
	{
		$Puissance = array('Dégâts Max' => $DegatMax, 'Dégâts Min' => $DegatMin, 'Défense Max' => $DefenseMax,
					   'Défense Min' => $DefenseMin, 'Magie Max' => $MagieMax, 'Magie Min' => $MagieMin);
	}
	else if ($where == 'combat')
	{
		$Puissance = array(
							'degat_max' => $DegatMax,
							'degat_min' => $DegatMin,
							'def_max' => $DefenseMax,
							'def_min' => $DefenseMin,
							'magie_max' => $MagieMax,
							'magie_min' => $MagieMin
						);
	}
	return $Puissance;
}

function getExpManquant($id_membre, $id_perso)
{
	global $bdd;
	global $ArrayPersonnage;
	$Session = new Session();
	
	$sqlNiveauPerso = $bdd->prepare("SELECT
										level, experience
									 FROM
										jeu_liste_membre_perso
									 WHERE
										id_membre = :id_membre && id_perso = :id_perso
									");
	$sqlNiveauPerso->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlNiveauPerso->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlNiveauPerso->execute();

	if ($sqlNiveauPerso->rowCount() > 0)
	{
		$NiveauPerso = $sqlNiveauPerso->fetch();
		$Niveau = $NiveauPerso['level'];
		$Experience = $NiveauPerso['experience'];
		
		if ($Niveau != 50)
		{
			$Niveau+= 1;
			$sqlExpRequired = $bdd->prepare("SELECT
												exp_required
											 FROM
												jeu_level
											 WHERE
												level = :level
											");
			$sqlExpRequired->bindValue('level', $Niveau, PDO::PARAM_INT);
			$sqlExpRequired->execute();
			if ($sqlExpRequired->rowCount() > 0)
			{
				$ExpRequired = $sqlExpRequired->fetch();
				
				$ExpRequired = $ExpRequired['exp_required'];
				
				$ExpManquant = $ExpRequired - $Experience;
				$ExpManquant = number_format($ExpManquant, 0, ',', ' ');
			}
			else
			{
				addLog(array("Erreur d'obtention de l'expérience requise, de la fonction getExpManquant.", $sqlExpRequired->errorInfo()),
						$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "admin", "error");
				$Session->setFlash(ERR_INTERNE);
				$ExpManquant = '';
			}
		}
		else
		{
			$ExpManquant = 'Vous avez atteint le niveau maximum.';
		}
	}
	else
	{
		addLog(array("Erreur d'obtention du niveau et de l'expérience, de la fonction getExpManquant.", $sqlNiveauPerso->errorInfo()),
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE, 'error');
		$ExpManquant = '';
	}
	
	return $ExpManquant;
}

function getPourcentExp($experience, $niveau)
{
	global $bdd;
	global $ArrayPersonnage;
	$Session = new Session();

	if ($niveau-1 == 1)
	{
		$niveau = $niveau + 1;
		$sqlAvoirExperience = $bdd->prepare("SELECT 
												exp_required 
											 FROM 
											 	jeu_level
										   	 WHERE 
										   	 	level = :niveau
										   	");
		$sqlAvoirExperience->bindValue('niveau', $niveau, PDO::PARAM_INT);
		$sqlAvoirExperience->execute();

		if ($sqlAvoirExperience->rowCount() > 0)
		{
			$reqAvoirExperience = $sqlAvoirExperience->fetch();
			
			$experience_requise = $reqAvoirExperience['exp_required'];
			
			$pourcent = ceil(($experience * 100) / $experience_requise);
		}
		else
		{
			$Session->setFlash(ERR_INTERNE);
			addLog(array("Erreur d'obtention de l'expérience requise, de la fonction getPourcentExp.", $sqlAvoirExperience->errorInfo()), $_SESSION['nom_personnage'],
					$_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
			$pourcent = 0;
		}
	}
	else
	{
		$sqlAvoirExpNiveauPrecedent = $bdd->prepare("SELECT exp_required FROM jeu_level
												   WHERE level = :niveau");
		$sqlAvoirExpNiveauPrecedent->execute(array('niveau' => $niveau));
		$reqAvoirExpNiveauPrecedent = $sqlAvoirExpNiveauPrecedent->fetch();
		
		$niveau = $niveau + 1;
		
		$sqlAvoirExpNiveauSuivant = $bdd->prepare("SELECT exp_required FROM jeu_level
												 WHERE level = :niveau");
		$sqlAvoirExpNiveauSuivant->execute(array('niveau' => $niveau));
		$reqAvoirExpNiveauSuivant = $sqlAvoirExpNiveauSuivant->fetch();
		
		$experience_requise = $reqAvoirExpNiveauSuivant['exp_required'] - $reqAvoirExpNiveauPrecedent['exp_required'];
		
		$experience = $experience - $reqAvoirExpNiveauPrecedent['exp_required'];
		$pourcent = ceil(($experience * 100) / $experience_requise);
		if($pourcent > 100)
		{
			$pourcent = 100;
		}
		else if($pourcent < 0)
		{
			$pourcent = 0;
		}
		else
		{
			$pourcent = $pourcent;
		}
	}
	return $pourcent;
}

function getAvatarPerso($id_membre, $id_perso)
{
	global $bdd;
	$Session = new Session();
	// On récupère les informations du personnage, pour savoir quel avatar doit avoir le personnage
	
	$sqlGetLevel = $bdd->prepare("SELECT
										level 
									FROM 
										jeu_liste_membre_perso
								   	WHERE 
								   		id_membre = :id_membre && id_perso = :id_perso
								");
	$sqlGetLevel->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlGetLevel->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlGetLevel->execute();

	if ($sqlGetLevel->rowCount() > 0)
	{
		$reqGetLevel = $sqlGetLevel->fetch();
		
		$sqlGetAvatar = $bdd->prepare("SELECT
											chemin_image
									   	FROM 
									   		jeu_liste_perso_avatar
									   	WHERE 
									   		id_perso = :id_perso && level = :level
									   	");
		$sqlGetAvatar->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
		$sqlGetAvatar->bindValue('level', $reqGetLevel['level'], PDO::PARAM_INT);
		$sqlGetAvatar->execute();

		if ($sqlGetAvatar->rowCount() > 0)
		{
			$reqGetAvatar = $sqlGetAvatar->fetch();
			
			$lien_avatar = ROOTPATH.$reqGetAvatar['chemin_image'];
			return $lien_avatar;
		}
		else
		{
			addLog(array("Erreur d'obtention de l'avatar, de la fonction getAvatarPerso", $sqlGetAvatar->errorInfo()),
					$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
			$Session->setFlash(ERR_INTERNE);
			return false;
		}
	}
	else
	{
		addLog(array("Erreur d'obtention du niveau, de la fonction getAvatarPerso", $sqlGetLevel->errorInfo()),
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE);
		return false;
	}
}


function calculKiPerso($id_membre, $id_perso)
{
	global $bdd;
	// On calcul le ki propre au personnage en suivant les critères
	/* Stats : nb = ki
	force : 1 = 500
	defense : 1 = 250
	magie : 1 = 200
	chance : 1 = 0
	vitesse : 1 = 100
	concentration : 1 = 100
	*/

	$sqlAvoirStats = $bdd->prepare("SELECT 
										stats_puissance, stats_defense, stats_magie,
								 		stats_chance, stats_vitesse, stats_concentration
								  	FROM 
								  		jeu_liste_membre_perso
								 	WHERE 
								 		id_membre = :id_membre && id_perso = :id_perso");
	$sqlAvoirStats->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlAvoirStats->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlAvoirStats->execute();

	if ($sqlAvoirStats->rowCount() > 0)
	{
		$reqAvoirStats = $sqlAvoirStats->fetch();
		
		$ki_puissance = $reqAvoirStats['stats_puissance'] * 500;
		$ki_defense = $reqAvoirStats['stats_defense'] * 250;
		$ki_magie = $reqAvoirStats['stats_magie'] * 200;
		$ki_vitesse = $reqAvoirStats['stats_vitesse'] * 100;
		$ki_concentration = $reqAvoirStats['stats_concentration'] * 100;
		
		$ki_total = $ki_puissance + $ki_defense + $ki_magie + $ki_vitesse + $ki_concentration;
		return $ki_total;
	}
	else
	{
		addLog(array("Erreur d'obtention du ki de la fonction calculKiPerso()", $sqlAvoirStats->errorInfo()), 
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE);
		return false;
	}
}

function GainZenis($id_membre, $id_current_perso, $etat_fight)
{
	$InfosPerso = getInfoPersonnage($id_membre, $id_current_perso);
	
	switch($etat_fight)
	{
		case "victoire":
			$zenis_minimum = 300*$InfosPerso['level'];
			$zenis_maximum = 500+$zenis_minimum;
			$gain_zenis = rand($zenis_minimum, $zenis_maximum);
		break;
		
		case "defaite":
			$zenis_minimum = 200*$InfosPerso['level'];
			$zenis_maximum = 400+$zenis_minimum;
			$gain_zenis = rand($zenis_minimum, $zenis_maximum);
		break;
	}
	
	return $gain_zenis;
}

function GainExp($id_membre, $id_current_perso, $etat_fight)
{
	$InfosPerso = getInfoPersonnage($id_membre, $id_current_perso);

	switch($etat_fight)
	{
		case "victoire":
			$exp_minimum = 1000*$InfosPerso['level'];
			$exp_maximum = 1000+$exp_minimum;
			$gain_exp = rand($exp_minimum, $exp_maximum);
		break;
		
		case "defaite":
			$exp_minimum = 800*$InfosPerso['level'];
			$exp_maximum = 1000+$exp_minimum;
			$gain_exp = rand($exp_minimum, $exp_maximum);
		break;
	}
	
	return $gain_exp;
}
?>