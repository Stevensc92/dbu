<?php
/*
 *
 * Function propres aux capsules
 * AfficherStat(id_capsule, type) : Retourne un tableau des statistiques de la capsule passé en param
 *
 */

function AfficherStat($id_capsule, $type, $level_capsule, $page = 'capsule')
{
	global $bdd;
	$Session = new Session();

	$sqlGetBonusStatCaps = $bdd->prepare("SELECT
												bonus
										  FROM
										  		jeu_level_capsule
										  WHERE
										  		id_type_capsule = :type_capsule && level = :level_capsule
										  ");
	$sqlGetBonusStatCaps->bindValue('type_capsule', $type, PDO::PARAM_INT);
	$sqlGetBonusStatCaps->bindValue('level_capsule', $level_capsule, PDO::PARAM_INT);
	$sqlGetBonusStatCaps->execute();

	if ($sqlGetBonusStatCaps->rowCount() == 0)
	{
		$Session->setFlash(ERR_INTERNE);
		addLog(array("Erreur d'obtention du bonus de niveau de la capsule, de la fonction AfficherStat.", $sqlGetBonusStatCaps->errorInfo()),
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		return false;
	}
	else
		$BonusStatCaps = $sqlGetBonusStatCaps->fetch();

	$sqlGetRealIdCaps = $bdd->prepare("SELECT 
											id_capsule 
										FROM 
											jeu_liste_membre_capsule 
										WHERE
											id = :id
										");
	$sqlGetRealIdCaps->bindValue('id', $id_capsule, PDO::PARAM_INT);
	$sqlGetRealIdCaps->execute();

	$RealIdCaps = $sqlGetRealIdCaps->fetch();

	switch($type)
	{
		case "1":
			$sql = "SELECT puissance, defense, magie, chance, vitesse, concentration, vie, energie";
			$jointure = '';
		break;
		
		case "2":
			$sql = "SELECT id_perso_require, jeu_liste_personnage.icone, energie";
			$jointure = "LEFT JOIN jeu_liste_personnage
						 ON jeu_liste_personnage.id_perso = jeu_liste_capsule.id_perso_require";
		break;
		
		case "3":
			$sql = "SELECT puissance, defense, magie, chance, vitesse, concentration, vie, energie, id_perso_require, jeu_liste_personnage.icone";
			$jointure = "LEFT JOIN jeu_liste_personnage
						 ON jeu_liste_personnage.id_perso = jeu_liste_capsule.id_perso_require";
		break;
	}
	$sqlGetStatCaps = $bdd->prepare($sql.", 
										level_capsule, experience, type
									 FROM
									 	jeu_liste_capsule
									 LEFT JOIN
										jeu_liste_membre_capsule
									 ON
										jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id
									".$jointure."
									 WHERE
									 	jeu_liste_capsule.id = :id_real_caps && type = :type && jeu_liste_membre_capsule.id = :id_capsule
									");
	$sqlGetStatCaps->bindValue('id_real_caps', $RealIdCaps['id_capsule'], PDO::PARAM_INT);
	$sqlGetStatCaps->bindValue('type', $type, PDO::PARAM_INT);
	$sqlGetStatCaps->bindValue('id_capsule', $id_capsule, PDO::PARAM_INT);
	$sqlGetStatCaps->execute();

	if ($sqlGetStatCaps->rowCount() > 0)
	{
		$Stat = $sqlGetStatCaps->fetch();

		//MyPrintR($Stat);
		if($page == 'capsule')
		{
			$StatCaps = '&lt;ul style=&quot;margin-top: 7px;&quot; &gt;&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Niveau : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['level_capsule'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Expérience : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['experience'].' Xp&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;br/&gt;';
		}
		else
		{
			$StatCaps = '&lt;ul style=&quot;margin-top: 7px;&quot; &gt;';
		}
		switch($Stat['type'])
		{
			case "1":
				$Stat['puissance'] += ($Stat['puissance'] * $BonusStatCaps['bonus']) / 100;
				$Stat['puissance'] = ($Stat['puissance'] > 0) ? floor($Stat['puissance']) : ceil($Stat['puissance']);
				$Stat['defense'] += ($Stat['defense'] * $BonusStatCaps['bonus']) / 100;
				$Stat['defense'] = ($Stat['defense'] > 0) ? floor($Stat['defense']) : ceil($Stat['defense']);
				$Stat['magie'] += ($Stat['magie'] * $BonusStatCaps['bonus']) / 100;
				$Stat['magie'] = ($Stat['magie'] > 0) ? floor($Stat['magie']) : ceil($Stat['magie']);
				$Stat['chance'] += ($Stat['chance'] * $BonusStatCaps['bonus']) / 100;
				$Stat['chance'] = ($Stat['chance'] > 0) ? floor($Stat['chance']) : ceil($Stat['chance']);
				$Stat['vitesse'] += ($Stat['vitesse'] * $BonusStatCaps['bonus']) / 100;
				$Stat['vitesse'] = ($Stat['vitesse'] > 0) ? floor($Stat['vitesse']) : ceil($Stat['vitesse']);
				$Stat['concentration'] += ($Stat['concentration'] * $BonusStatCaps['bonus']) / 100;
				$Stat['concentration'] = ($Stat['concentration'] > 0) ? floor($Stat['concentration']) : ceil($Stat['concentration']);
				$Stat['vie'] += ($Stat['vie'] * $BonusStatCaps['bonus']) / 100;
				$Stat['vie'] = ($Stat['vie'] > 0) ? floor($Stat['vie']) : ceil($Stat['vie']);
				$Stat['energie'] += ($Stat['energie'] * $BonusStatCaps['bonus']) / 100;
				$Stat['energie'] = ($Stat['energie'] > 0) ? floor($Stat['energie']) : ceil($Stat['energie']);

				$StatCaps .= '
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Puissance : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['puissance'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Défense : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['defense'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Magie : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['magie'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Chance : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['chance'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Vitesse : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['vitesse'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Concentration : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['concentration'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Vie : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['vie'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Énergie : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['energie'].'&lt;/span&gt;&lt;/span&gt;&lt;br/&gt; &lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;/li&gt;&lt;br/&gt;
							Appartient à : &lt;span style=&quot;color:blue;&quot;&gt;Tout le monde&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;/li&gt;';
			break;
			
			case "2":
				$StatCaps .= '&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Prix d\'utilisation : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['energie'].' énergie&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;br/&gt;
							Appartient à : &lt;img src=&quot;'.ROOTPATH.$Stat['icone'].'&quot; /&gt;&lt;/li&gt;';
			break;
			
			case "3":
				$StatCaps .= '
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Puissance : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['puissance'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Défense : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['defense'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Magie : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['magie'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Chance : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['chance'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Vitesse : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['vitesse'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Concentration : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['concentration'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;
							&lt;li&gt;&lt;span style=&quot;float: left;&quot;&gt;Vie : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['vie'].'&lt;/span&gt;&lt;/span&gt; &lt;span style=&quot;float: right;&quot;&gt;Énergie : &lt;span style=&quot;color:blue;&quot;&gt;'.$Stat['energie'].'&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/li&gt;&lt;br/&gt;
							Appartient à : &lt;img src=&quot;'.ROOTPATH.$Stat['icone'].'&quot; /&gt;&lt;span style=&quot;clear_all&quot;&gt;&lt;/span&gt;&lt;/li&gt;
				';
			break;
		}
		$StatCaps .= '&lt;/ul&gt;';
		return $StatCaps;
	}
	else
		return false;
}

function getRealStatCapsule($id_capsule, $level_capsule)
{
	global $bdd;
	$Session = new Session();

	$sqlGetStatCaps = $bdd->prepare("SELECT
										type, puissance, defense, magie, chance, vitesse, concentration,
										vie, energie, bonus
									FROM
										jeu_liste_capsule
									INNER JOIN
										jeu_level_capsule
									ON
										jeu_level_capsule.id_type_capsule = jeu_liste_capsule.type && jeu_level_capsule.level = :level_capsule
									WHERE
										jeu_liste_capsule.id = :id_capsule
									");
	$sqlGetStatCaps->bindValue('level_capsule', $level_capsule, PDO::PARAM_INT);
	$sqlGetStatCaps->bindValue('id_capsule', $id_capsule, PDO::PARAM_INT);
	$sqlGetStatCaps->execute();

	if ($sqlGetStatCaps->rowCount() > 0)
	{
		$StatCaps = $sqlGetStatCaps->fetch();

		switch ($StatCaps['type'])
		{
			case 1: // caps jaune
				$StatCaps['puissance'] += ($StatCaps['puissance'] * $StatCaps['bonus']) / 100;
				$StatCaps['puissance'] = ($StatCaps['puissance'] > 0) ? floor($StatCaps['puissance']) : ceil($StatCaps['puissance']);
				$StatCaps['defense'] += ($StatCaps['defense'] * $StatCaps['bonus']) / 100;
				$StatCaps['defense'] = ($StatCaps['defense'] > 0) ? floor($StatCaps['defense']) : ceil($StatCaps['defense']);
				$StatCaps['magie'] += ($StatCaps['magie'] * $StatCaps['bonus']) / 100;
				$StatCaps['magie'] = ($StatCaps['magie'] > 0) ? floor($StatCaps['magie']) : ceil($StatCaps['magie']);
				$StatCaps['chance'] += ($StatCaps['chance'] * $StatCaps['bonus']) / 100;
				$StatCaps['chance'] = ($StatCaps['chance'] > 0) ? floor($StatCaps['chance']) : ceil($StatCaps['chance']);
				$StatCaps['vitesse'] += ($StatCaps['vitesse'] * $StatCaps['bonus']) / 100;
				$StatCaps['vitesse'] = ($StatCaps['vitesse'] > 0) ? floor($StatCaps['vitesse']) : ceil($StatCaps['vitesse']);
				$StatCaps['concentration'] += ($StatCaps['concentration'] * $StatCaps['bonus']) / 100;
				$StatCaps['concentration'] = ($StatCaps['concentration'] > 0) ? floor($StatCaps['concentration']) : ceil($StatCaps['concentration']);
				$StatCaps['vie'] += ($StatCaps['vie'] * $StatCaps['bonus']) / 100;
				$StatCaps['vie'] = ($StatCaps['vie'] > 0) ? floor($StatCaps['vie']) : ceil($StatCaps['vie']);
				$StatCaps['energie'] += ($StatCaps['energie'] * $StatCaps['bonus']) / 100;
				$StatCaps['energie'] = ($StatCaps['energie'] > 0) ? floor($StatCaps['energie']) : ceil($StatCaps['energie']);
			break;

			case 3: // caps verte
				$StatCaps['puissance'] += ($StatCaps['puissance'] * $StatCaps['bonus']) / 100;
				$StatCaps['puissance'] = ($StatCaps['puissance'] > 0) ? floor($StatCaps['puissance']) : ceil($StatCaps['puissance']);
				$StatCaps['defense'] += ($StatCaps['defense'] * $StatCaps['bonus']) / 100;
				$StatCaps['defense'] = ($StatCaps['defense'] > 0) ? floor($StatCaps['defense']) : ceil($StatCaps['defense']);
				$StatCaps['magie'] += ($StatCaps['magie'] * $StatCaps['bonus']) / 100;
				$StatCaps['magie'] = ($StatCaps['magie'] > 0) ? floor($StatCaps['magie']) : ceil($StatCaps['magie']);
				$StatCaps['chance'] += ($StatCaps['chance'] * $StatCaps['bonus']) / 100;
				$StatCaps['chance'] = ($StatCaps['chance'] > 0) ? floor($StatCaps['chance']) : ceil($StatCaps['chance']);
				$StatCaps['vitesse'] += ($StatCaps['vitesse'] * $StatCaps['bonus']) / 100;
				$StatCaps['vitesse'] = ($StatCaps['vitesse'] > 0) ? floor($StatCaps['vitesse']) : ceil($StatCaps['vitesse']);
				$StatCaps['concentration'] += ($StatCaps['concentration'] * $StatCaps['bonus']) / 100;
				$StatCaps['concentration'] = ($StatCaps['concentration'] > 0) ? floor($StatCaps['concentration']) : ceil($StatCaps['concentration']);
				$StatCaps['vie'] += ($StatCaps['vie'] * $StatCaps['bonus']) / 100;
				$StatCaps['vie'] = ($StatCaps['vie'] > 0) ? floor($StatCaps['vie']) : ceil($StatCaps['vie']);
				$StatCaps['energie'] += ($StatCaps['energie'] * $StatCaps['bonus']) / 100;
				$StatCaps['energie'] = ($StatCaps['energie'] > 0) ? floor($StatCaps['energie']) : ceil($StatCaps['energie']);
			break;
		}
		return $StatCaps;
	}
	else
	{
		$Session->setFlash(ERR_INTERNE);
		addLog(array("Erreur d'obtention des statistiques de la capsule id -> ".$id_capsule." au niveau ".$level_capsule.".", $sqlGetStatCaps->errorInfo()),
					$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		return false;
	}
}

function equiperCapsule($id_capsule, $id_membre, $id_perso)
{
	global $bdd;
	$Session = new Session();
	require_once(FUNCTION_DIR.'/function.personnage.php');

	// Initialisation du nombre de capsule équipé simultanément
	$i_jaune = 0;
	$i_rouge = 0;
	$i_verte = 0;
	
	// Initialisation du message d'erreur
	$erreur = '';

	// On récupère les informations du personnage (pour modifier les statistiques)
	$InfosPerso = getInfoPersonnage($id_membre, $id_perso);

	// $id_capsule est un array on foreach son contenu
	foreach($id_capsule as $capsule)
	{
		// On va récupèrer l'id, le type, et le personnage requis de la capsule qu'on a dans le foreach
		$sqlGetInfoCapsule = $bdd->prepare("SELECT 
												id_capsule, type, id_perso_require, level_capsule, nom, id_perso_equipe
										  FROM 
										  		jeu_liste_membre_capsule
										  INNER JOIN
										  		jeu_liste_capsule
										  ON
										  		jeu_liste_capsule.id = jeu_liste_membre_capsule.id_capsule
										  WHERE 
										  		jeu_liste_membre_capsule.id = :id_capsule");
		$sqlGetInfoCapsule->execute(array('id_capsule' => $capsule));
		$reqGetInfoCapsule = $sqlGetInfoCapsule->fetch();

		// On vérifie que la capsule n'est pas équipé

		if ($reqGetInfoCapsule['id_perso_equipe'] == 0)
		{
			// On récupère les statistiques du personnage
			$InfoStatPerso = getInfosStatsPerso($id_membre, $id_perso);

			// On récupère les vrais stats de la capsule
			$InfosCaps = getRealStatCapsule($reqGetInfoCapsule['id_capsule'], $reqGetInfoCapsule['level_capsule']);

			switch($reqGetInfoCapsule['type']) // On switch les résultat des type pour savoir qu\'elle capsule on traite
			{
				case "1": // Capsule jaune
					$sqlVerifNbCapsuleJ = $bdd->prepare("SELECT *
														 FROM jeu_liste_membre_capsule
														 INNER JOIN jeu_liste_capsule
														 WHERE jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id &&
														 id_membre = :id_membre && id_perso_equipe = :id_current_perso && jeu_liste_capsule.type = :type");
					$sqlVerifNbCapsuleJ->execute(array('id_membre' => $id_membre,
													   'id_current_perso' => $id_perso,
													   'type' => $reqGetInfoCapsule['type']));
					$NbCapsEquipeJ = $sqlVerifNbCapsuleJ->rowCount();
					if($NbCapsEquipeJ == 5) // On vérifie le nombre de capsule jaune équipé
					{
						$erreur .= ' Le nombre maximale de capsule Jaune a été atteint.';
					}
					else
					{
						// On vérifie que les stats ne passent pas négatifs
						$NewPuissance = $InfoStatPerso['stats_puissance'] + $InfosCaps['puissance'];
						$NewDefense = $InfoStatPerso['stats_defense'] + $InfosCaps['defense'];
						$NewMagie = $InfoStatPerso['stats_magie'] + $InfosCaps['magie'];
						$NewChance = $InfoStatPerso['stats_chance'] + $InfosCaps['chance'];
						$NewVitesse = $InfoStatPerso['stats_vitesse'] + $InfosCaps['vitesse'];
						$NewConcentration = $InfoStatPerso['stats_concentration'] + $InfosCaps['concentration'];
						$NewVie = $InfoStatPerso['stats_vie'] + $InfosCaps['vie'];
						$NewEnergie = $InfoStatPerso['stats_energie'] + $InfosCaps['energie'];
						
						if($NewPuissance < 0 || $NewDefense < 0 || $NewMagie < 0 || $NewChance < 0 || $NewVitesse < 0 ||
							$NewConcentration < 0 || $NewVie < 0 || $NewEnergie < 0)
						{
							addLog($_SESSION['membre_pseudo'].' a tenté d\'équiper la capsule "'.$reqGetInfoCapsule['nom'].'"(jaune) sur "'.$InfosPerso['nom_personnage'].'" mais les stats ne le lui permettent pas.',
									$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "", "info");
							$erreur = 'Vous ne pouvez équiper cette capsule, vos caractéristiques ne vous le permettent pas.';
						}
						else
						{
							$sqlAjouterCaps = $bdd->prepare("UPDATE jeu_liste_membre_capsule
															 SET id_perso_equipe = :id_perso
															 WHERE id = :id_capsule && id_membre = :id_membre");
							$sqlAjouterCaps->execute(array('id_perso' => $id_perso,
														   'id_capsule' => $capsule,
														   'id_membre' => $id_membre));
						
							$sqlAjouterCarac = $bdd->prepare("UPDATE jeu_liste_membre_perso
															  SET stats_puissance = stats_puissance + :puissance,
																  stats_defense = stats_defense + :defense,
																  stats_magie = stats_magie + :magie,
																  stats_chance = stats_chance + :chance,
																  stats_vitesse = stats_vitesse + :vitesse,
																  stats_concentration = stats_concentration + :concentration,
																  stats_vie = stats_vie + :vie,
																  stats_energie = stats_energie + :energie
															  WHERE id_membre = :id_membre && id_perso = :id_current_perso");
							$sqlAjouterCarac->execute(array('puissance' => $InfosCaps['puissance'],
															'defense' => $InfosCaps['defense'],
															'magie' => $InfosCaps['magie'],
															'chance' => $InfosCaps['chance'],
															'vitesse' => $InfosCaps['vitesse'],
															'concentration' => $InfosCaps['concentration'],
															'vie' => $InfosCaps['vie'],
															'energie' => $InfosCaps['energie'],
															'id_membre' => $id_membre,
															'id_current_perso' => $id_perso));
							$i_jaune++;
						}
					}
				break;
				
				case "2": // Capsule rouge
					if($reqGetInfoCapsule['id_perso_require'] == $id_perso)
					{
						$sqlVerifNbCapsuleR = $bdd->prepare("SELECT *
															 FROM jeu_liste_membre_capsule
															 INNER JOIN jeu_liste_capsule
															 WHERE jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id &&
															 id_membre = :id_membre && id_perso_equipe = :id_current_perso && jeu_liste_capsule.type = :type");
						$sqlVerifNbCapsuleR->execute(array('id_membre' => $id_membre,
														   'id_current_perso' => $id_perso,
														   'type' => $reqGetInfoCapsule['type']));
						$NbCapsEquipeR = $sqlVerifNbCapsuleR->rowCount();
						if($NbCapsEquipeR == 3) // On vérifie le nombre de capsule jaune équipé
						{
							$erreur .= ' Le nombre maximale de capsule Rouge a été atteint.';
						}
						else
						{
						
							// On vérifie que les stats ne passent pas négatifs
							$NewPuissance = $InfoStatPerso['stats_puissance'] + $InfosCaps['puissance'];
							$NewDefense = $InfoStatPerso['stats_defense'] + $InfosCaps['defense'];
							$NewMagie = $InfoStatPerso['stats_magie'] + $InfosCaps['magie'];
							$NewChance = $InfoStatPerso['stats_chance'] + $InfosCaps['chance'];
							$NewVitesse = $InfoStatPerso['stats_vitesse'] + $InfosCaps['vitesse'];
							$NewConcentration = $InfoStatPerso['stats_concentration'] + $InfosCaps['concentration'];
							$NewVie = $InfoStatPerso['stats_vie'] + $InfosCaps['vie'];
							$NewEnergie = $InfoStatPerso['stats_energie'] + $InfosCaps['energie'];
							
							if($NewPuissance < 0 || $NewDefense < 0 || $NewMagie < 0 || $NewChance < 0 || $NewVitesse < 0 ||
								$NewConcentration < 0 || $NewVie < 0 || $NewEnergie < 0)
							{
								addLog($_SESSION['membre_pseudo'].' a tenté d\'équiper une capsule mais les stats ne le lui permettent pas.',
										__FILE__,__LINE__, "", "info");
								$erreur = 'Vous ne pouvez équiper cette capsule, vos caractéristiques ne vous le permettent pas.';
							}
							else
							{
								$sqlAjouterCaps = $bdd->prepare("UPDATE jeu_liste_membre_capsule
																 SET id_perso_equipe = :id_perso
																 WHERE id = :id_capsule && id_membre = :id_membre");
								$sqlAjouterCaps->execute(array('id_perso' => $id_perso,
															   'id_capsule' => $capsule,
															   'id_membre' => $id_membre));
							
								$sqlAjouterCarac = $bdd->prepare("UPDATE jeu_liste_membre_perso
																  SET stats_puissance = stats_puissance + :puissance,
																	  stats_defense = stats_defense + :defense,
																	  stats_magie = stats_magie + :magie,
																	  stats_chance = stats_chance + :chance,
																	  stats_vitesse = stats_vitesse + :vitesse,
																	  stats_concentration = stats_concentration + :concentration,
																	  stats_vie = stats_vie + :vie,
																	  stats_energie = stats_energie + :energie
																  WHERE id_membre = :id_membre && id_perso = :id_current_perso");
								$sqlAjouterCarac->execute(array('puissance' => $InfosCaps['puissance'],
																'defense' => $InfosCaps['defense'],
																'magie' => $InfosCaps['magie'],
																'chance' => $InfosCaps['chance'],
																'vitesse' => $InfosCaps['vitesse'],
																'concentration' => $InfosCaps['concentration'],
																'vie' => $InfosCaps['vie'],
																'energie' => $InfosCaps['energie'],
																'id_membre' => $id_membre,
																'id_current_perso' => $id_perso));
								$i_rouge++;
							}
						}
					}
					else
					{
						$erreur = 'Ce personnage ne peut équiper cette capsule Rouge.';
					}
				break;
				
				case "3": // Capsule verte
					if($reqGetInfoCapsule['id_perso_require'] == $id_perso)
					{
						$sqlVerifNbCapsuleV = $bdd->prepare("SELECT *
															 FROM jeu_liste_membre_capsule
															 INNER JOIN jeu_liste_capsule
															 WHERE jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id &&
															 id_membre = :id_membre && id_perso_equipe = :id_current_perso && jeu_liste_capsule.type = :type");
						$sqlVerifNbCapsuleV->execute(array('id_membre' => $id_membre,
														   'id_current_perso' => $id_perso,
														   'type' => $reqGetInfoCapsule['type']));
						$NbCapsEquipeV = $sqlVerifNbCapsuleV->rowCount();
						if($NbCapsEquipeV == 1) // On vérifie le nombre de capsule jaune équipé
						{
							$erreur .= ' Le nombre maximale de capsule Verte a été atteint.';
						}
						else
						{
						
							// On vérifie que les stats ne passent pas négatifs
							$NewPuissance = $InfoStatPerso['stats_puissance'] + $InfosCaps['puissance'];
							$NewDefense = $InfoStatPerso['stats_defense'] + $InfosCaps['defense'];
							$NewMagie = $InfoStatPerso['stats_magie'] + $InfosCaps['magie'];
							$NewChance = $InfoStatPerso['stats_chance'] + $InfosCaps['chance'];
							$NewVitesse = $InfoStatPerso['stats_vitesse'] + $InfosCaps['vitesse'];
							$NewConcentration = $InfoStatPerso['stats_concentration'] + $InfosCaps['concentration'];
							$NewVie = $InfoStatPerso['stats_vie'] + $InfosCaps['vie'];
							$NewEnergie = $InfoStatPerso['stats_energie'] + $InfosCaps['energie'];
							
							if($NewPuissance < 0 || $NewDefense < 0 || $NewMagie < 0 || $NewChance < 0 || $NewVitesse < 0 ||
								$NewConcentration < 0 || $NewVie < 0 || $NewEnergie < 0)
							{
								addLog($_SESSION['membre_pseudo'].' a tenté d\'équiper une capsule mais les stats ne le lui permettent pas.',
										$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__,__LINE__, "", "info");
								$erreur = 'Vous ne pouvez équiper cette capsule, vos caractéristiques ne vous le permettent pas.';
							}
							else
							{
								$sqlAjouterCaps = $bdd->prepare("UPDATE jeu_liste_membre_capsule
																 SET id_perso_equipe = :id_perso
																 WHERE id = :id_capsule && id_membre = :id_membre");
								$sqlAjouterCaps->execute(array('id_perso' => $id_perso,
															   'id_capsule' => $capsule,
															   'id_membre' => $id_membre));
							
								$sqlAjouterCarac = $bdd->prepare("UPDATE jeu_liste_membre_perso
																  SET stats_puissance = stats_puissance + :puissance,
																	  stats_defense = stats_defense + :defense,
																	  stats_magie = stats_magie + :magie,
																	  stats_chance = stats_chance + :chance,
																	  stats_vitesse = stats_vitesse + :vitesse,
																	  stats_concentration = stats_concentration + :concentration,
																	  stats_vie = stats_vie + :vie,
																	  stats_energie = stats_energie + :energie
																  WHERE id_membre = :id_membre && id_perso = :id_current_perso");
								$sqlAjouterCarac->execute(array('puissance' => $InfosCaps['puissance'],
																'defense' => $InfosCaps['defense'],
																'magie' => $InfosCaps['magie'],
																'chance' => $InfosCaps['chance'],
																'vitesse' => $InfosCaps['vitesse'],
																'concentration' => $InfosCaps['concentration'],
																'vie' => $InfosCaps['vie'],
																'energie' => $InfosCaps['energie'],
																'id_membre' => $id_membre,
																'id_current_perso' => $id_perso));
								$i_verte++;
							}
						}
					}
					else
					{
						$erreur = 'Ce personnage ne peut équiper cette capsule Verte.';
					}
				break;
			} // fin du switch
		} // fin vérification de la condition qui vérifie si la caps n'est pas équipé
		else
		{
			$erreur = "La/les capsule(s) est/sont déjà équipée(s).";
			addLog("Tentative de ré-équipage de capsule déjà équipée.", $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
					__FILE__, __LINE__, "admin", "error");
		}
	}
	if($erreur != '')
	{
		$Session->setFlash($erreur);
	}
	else
	{
		$message = '';
		if($i_jaune == 1)
		{
			$message .= $i_jaune.' capsule Jaune a été équipée.';
		}
		else if($i_jaune > 1)
		{
			$message .= $i_jaune.' capsules Jaune ont été équipées.';
		}
		else
		{
			$message .= '';
		}
		
		if($i_rouge == 1)
		{
			$message .= ' '.$i_rouge.' capsule Rouge a été équipée.';
		}
		else if($i_rouge > 1)
		{
			$message .= ' '.$i_rouge.' capsules Rouge ont été équipées.';
		}
		else
		{
			$message .= '';
		}
		
		if($i_verte == 1)
		{
			$message.= ' '.$i_verte.' capsule Verte a été équipée.';
		}
		else
		{
			$message.= '';
		}
		if($message != '');
		{
			$Session->setFlash($message, 'success');
		}
	}
}

function retirerCapsule($id_capsule, $id_membre, $id_perso)
{
	$Session = new Session();
	global $bdd;
	require_once(FUNCTION_DIR.'/function.personnage.php');

	$i = 0; // Variable permettant de savoir le nombre de capsule qui ont été retiré

	foreach($id_capsule as $id_capsule)
	{
		// On check d'abord si en modifiant les stats il y a une stats qui passe en négatif


		$sqlGetInfoCapsule = $bdd->prepare("SELECT 
												id_capsule, type, level_capsule, nom, id_perso_equipe
										  FROM 
										  		jeu_liste_membre_capsule
										  INNER JOIN
										  		jeu_liste_capsule
										  ON
										  		jeu_liste_capsule.id = jeu_liste_membre_capsule.id_capsule
										  WHERE 
										  		jeu_liste_membre_capsule.id = :id_capsule && id_membre = :id_membre && id_perso_equipe = :id_perso");
		$sqlGetInfoCapsule->bindValue('id_capsule', $id_capsule, PDO::PARAM_INT);
		$sqlGetInfoCapsule->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
		$sqlGetInfoCapsule->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
		$sqlGetInfoCapsule->execute();

		$getInfoCapsule = $sqlGetInfoCapsule->fetch();

		$InfosCaps = getRealStatCapsule($getInfoCapsule['id_capsule'], $getInfoCapsule['level_capsule']);

		$CurrentStatPerso = getInfoPersonnage($id_membre, $id_perso);

		$NomCaps = $getInfoCapsule['nom'];

		$prefix = "stats_";
		$array_stat = array(
					'0' => 'puissance',
					'1' => 'defense',
					'2' => 'magie',
					'3' => 'chance',
					'4' => 'vitesse',
					'5' => 'concentration',
					'6' => 'vie',
					'7' => 'energie'
			);
		// On boucle en fonction du nombre de stat, et on calcul

		// On vérifie que la capsule est bien équipée avant de la retirer
		if ($getInfoCapsule['id_perso_equipe'] != 0)
		{
			for ($iCalcul = 0; $iCalcul < 8; $iCalcul++)
			{
				$Calcul = ($CurrentStatPerso[$prefix.$array_stat[$iCalcul]] - $InfosCaps[$array_stat[$iCalcul]]);
				// Si le résultat est négatif, on retourne une erreur et on stop tout
				if($Calcul < 0)
				{
					$Session->setFlash('Vous ne pouvez pas retirer la capsule '.$NomCaps.', vos stats deviendront négatifs.');
					return false;
				}
			}

			$sqlRetirerCaps = $bdd->prepare("UPDATE 
												jeu_liste_membre_capsule
											 SET 
											 	id_perso_equipe = 0
											 WHERE 
											 	id = :id_capsule && id_membre = :id_membre && id_perso_equipe = :id_perso");
			$sqlRetirerCaps->bindValue('id_capsule', $id_capsule, PDO::PARAM_INT);
			$sqlRetirerCaps->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
			$sqlRetirerCaps->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
			$sqlRetirerCaps->execute();

			$sqlRetirerCarac = $bdd->prepare("UPDATE jeu_liste_membre_perso
											  SET stats_puissance = stats_puissance - :puissance,
												  stats_defense = stats_defense - :defense,
												  stats_magie = stats_magie - :magie,
												  stats_chance = stats_chance - :chance,
												  stats_vitesse = stats_vitesse - :vitesse,
												  stats_concentration = stats_concentration - :concentration,
												  stats_vie = stats_vie - :vie,
												  stats_energie = stats_energie - :energie
											  WHERE id_membre = :id_membre && id_perso = :id_current_perso");
			$sqlRetirerCarac->bindValue('puissance', $InfosCaps['puissance'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('defense', $InfosCaps['defense'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('magie', $InfosCaps['magie'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('chance', $InfosCaps['chance'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('vitesse', $InfosCaps['vitesse'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('concentration', $InfosCaps['concentration'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('vie', $InfosCaps['vie'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('energie', $InfosCaps['energie'], PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
			$sqlRetirerCarac->bindValue('id_current_perso', $id_perso, PDO::PARAM_INT);
			$sqlRetirerCarac->execute();

			$i++;
		}
		else
		{
			$Session->setFlash('La/les capsule(s) est/sont déjà retirée(s).');
			addLog("Tentative de re-retirage de capsule.", $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
					__FILE__, __LINE__, "admin", "error");
			return false;
		}
	}
	if($i > 1)
	{
		$Session->setFlash($i.' capsules ont été retirées.', 'success');
	}
	else
	{
		$Session->setFlash($i.' capsule a été retirée.', 'success');
	}
}

function GainExpCapsule($etat_combat, $id_membre, $id_perso)
{
	global $bdd;
	$Session = new Session();

	switch($etat_combat)
	{
		case "victoire":
			$gain_exp = 10;
		break;

		case "defaite":
			$gain_exp = 5;
		break;

		case "kill":
			$gain_exp = 20;
		break;

		case "nul":
			$gain_exp = 5;
		break;

		case "mort":
			$gain_exp = 0;
		break;

		default:
			$gain_exp = 0;
		break;
	}

	$sqlGainExpCapsule = $bdd->prepare("UPDATE
											jeu_liste_membre_capsule
										SET
											experience = experience + :experience
										WHERE
											id_membre = :id_membre && id_perso_equipe = :id_perso
										");
	$sqlGainExpCapsule->bindValue('experience', $gain_exp, PDO::PARAM_INT);
	$sqlGainExpCapsule->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlGainExpCapsule->bindValue('id_perso', $id_perso, PDO::PARAM_INT);
	$sqlGainExpCapsule->execute();

	if($sqlGainExpCapsule->rowCount() == 0)
	{
		addLog(array("Erreur d'ajout d'expérience aux capsule", $sqlGainExpCapsule->errorInfo()), 
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
				__FILE__, __LINE__, "admin", "error");
		$Session->setFlash(ERR_INTERNE);
	}
}
?>