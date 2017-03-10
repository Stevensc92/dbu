<?php
/*
 *
 * Fichier function.fight.php
 * Fonctions relatives aux combats
 *
 */

function verifFormRound($round) // Retourne false si "undefined" est trouvé dans un round
{
	global $Session;

	foreach ($round as $key => $value)
	{
		if ($value == "undefined")
		{
			$key += 1;
			$Session->setFlash("Vous avez oublié de renseigner l'action pour le round ".$key);
			return false;
		}
	}
	return true;
}

function verifRound($atq, $def, $magie, $toto_atq) // Retourne false si la limite est atteint lors du combat
{
	global $Session;
	// $atq, $def & $magie sont des int (count du nombre de type dans le combat)

	if ($atq > LIMIT_ROUND_ATQ)
	{
		$Session->setFlash("Le nombre de tour d'attaque est limitée à ".LIMIT_ROUND_ATQ." par combat.");
		return false;
	}
	else if ($def > LIMIT_ROUND_DEF)
	{
		$Session->setFlash("Le nombre de tour de défense est limitée à ".LIMIT_ROUND_DEF." par combat.");
		return false;
	}
	else if ($magie > LIMIT_ROUND_MAG)
	{
		$Session->setFlash("Le nombre de tour de magie est limitée à ".LIMIT_ROUND_MAG." par combat.");
		return false;
	}
	else if ($toto_atq > LIMIT_ROUND_TOTO_ATQ)
	{
		$Session->setFlash("Le nombre de tour d'attaque totale (attaque + magie) est limitée à ".LIMIT_ROUND_TOTO_ATQ." par combat.");
		return false;
	}
	else
		return true;
}

function getDegat($id_membre, $id_perso, $action)
{
	$Session = new Session();

	$Puissance = getPuissancePersonnage($id_membre, $id_perso, 'combat');
	switch ($action)
	{
		case "attaque":
			$return = mt_rand($Puissance['degat_min'], $Puissance['degat_max']);
		break;

		case "defense":
			$return = mt_rand($Puissance['def_min'], $Puissance['def_max']);
		break;

		case "magie":
			$return = mt_rand($Puissance['magie_min'], $Puissance['magie_max']);
		break;

		default:
			addLog("Erreur de la fonction getDegat", __FILE__,__LINE__, "admin", "error");
			$Session->setFlash(ERR_INTERNE);
		break;
	}

	return $return;
}

function beforeReadFight($id_fight)
{
	global $Session;
	global $bdd;

	$sqlGetDonneeFight = $bdd->prepare("SELECT
											id_combat, id_membre_attaquant, id_perso_attaquant, id_membre_defenseur, id_perso_defenseur
										FROM
											jeu_liste_combat
										WHERE
											id_combat = :id_combat
										");
	$sqlGetDonneeFight->bindValue('id_combat', $id_fight, PDO::PARAM_INT);
	$sqlGetDonneeFight->execute();

	if ($sqlGetDonneeFight->rowCount() > 0)
	{
		$DonneeFight = $sqlGetDonneeFight->fetch();

		$PseudoAdv = getPseudoMembre($DonneeFight['id_membre_attaquant']);
		$InfosPersoAdv = getInfoPersonnage($DonneeFight['id_membre_attaquant'], $DonneeFight['id_perso_attaquant']);
		$PseudoDef = getPseudoMembre($DonneeFight['id_membre_defenseur']);
		$InfosPersoDef = getInfoPersonnage($DonneeFight['id_membre_defenseur'], $DonneeFight['id_perso_defenseur']);
		?>
		<div id="combat">
			<div id="block_adversaire">
				<span class="combat-name-m_p">
					<?php 
						echo ucfirst($PseudoAdv).' [<span style="color:red;">'.$InfosPersoAdv['nom_personnage'].'</span>]'; 
					?>
				</span>
				<img src="<?php echo getAvatarPerso($DonneeFight['id_membre_attaquant'], $DonneeFight['id_perso_attaquant']); ?>" alt="<?php echo $InfosPersoAdv['nom_personnage']; ?>"/>
			</div>

			<div class="combat-vs">
				<img src="<?php echo TIMTHUMB; ?>images/Fight/vs.png&amp;h=200&amp;w=150" />
			</div>

			<div id="block_defenseur">
				<span class="combat-name-m_p">
					<?php 
						echo ucfirst($PseudoDef).' [<span style="color:red;">'.$InfosPersoDef['nom_personnage'].'</span>]';
					?>
				</span>
				<img src="<?php echo getAvatarPerso($DonneeFight['id_membre_defenseur'], $DonneeFight['id_perso_defenseur']); ?>" alt="<?php echo $InfosPersoDef['nom_personnage']; ?>" />
			</div>

			<div class="combat-go">
				<a href="#" onclick="javascript:lancer();">
					<img src="<?php echo TIMTHUMB; ?>images/Fight/fight.png&amp;w=275&amp;h=139" />
				</a>
			</div>
		</div>
		<?php
	}
	else
	{
		$Session->setFlash(ERR_INTERNE);
		addLog(array("Erreur d'obtention des données du fight id : ".$id_fight, $sqlGetDonneeFight->errorInfo()), 
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		return false;
	}
}

function ReadFight($id_fight)
{
	global $bdd;
	global $ArrayPersonnage;
	$Session = new Session();

	require_once(FUNCTION_DIR."/function.capsule.php");
	// On récupère tout les infos à savoir pour le déroulement du combat
	$sqlGetRoundFight = $bdd->prepare("SELECT
											*
										FROM
											jeu_liste_combat
										WHERE
											id_combat = :id_fight
									");
	$sqlGetRoundFight->bindValue('id_fight', $id_fight, PDO::PARAM_INT);
	$sqlGetRoundFight->execute();
	$RoundFight = $sqlGetRoundFight->fetch();

	// On récupère le pseudo des deux joueurs du combat pour les afficher
	$sqlGetPseudo_Adv_Def = $bdd->prepare("SELECT
												pseudo as pseudo_attaquant, (SELECT
																				pseudo
																			 FROM
																			 	site_membres
																			 WHERE
																			 	id = :id_membre_defenseur
																			) as pseudo_defenseur
											FROM
												site_membres
											WHERE
												id = :id_membre_attaquant
											");
	$sqlGetPseudo_Adv_Def->bindValue('id_membre_defenseur', $RoundFight['id_membre_defenseur'], PDO::PARAM_INT);
	$sqlGetPseudo_Adv_Def->bindValue('id_membre_attaquant', $RoundFight['id_membre_attaquant'], PDO::PARAM_INT);
	$sqlGetPseudo_Adv_Def->execute();
	$GetPseudo_Adv_Def = $sqlGetPseudo_Adv_Def->fetch();

	// On initialise toutes les variables de l'attaquant pour le combat
	$PseudoAdv = $GetPseudo_Adv_Def['pseudo_attaquant'];
	$AvatarPersoAdv = getAvatarPerso($RoundFight['id_membre_attaquant'], $RoundFight['id_perso_attaquant']);
	$InfosPersoAdv = getInfoPersonnage($RoundFight['id_membre_attaquant'], $RoundFight['id_perso_attaquant']);
	$PuissancePersoAdv = getPuissancePersonnage($RoundFight['id_membre_attaquant'], $RoundFight['id_perso_attaquant'], 'combat');
	$TotalLifeAdv = $InfosPersoAdv['stats_vie'];
	$CurrentLifeAdv = $TotalLifeAdv;
	$CurrentManaAdv = ($InfosPersoAdv['stats_energie'] * 100) / ($InfosPersoAdv['stats_energie'] == 0 ? 1 : $InfosPersoAdv['stats_energie']);
	$ShortNamePersoAdv = $ArrayPersonnage[$RoundFight['id_perso_attaquant']]['short_name'];

	// On initialise maintenant toutes les variables du défenseur pour le combat
	$PseudoDef = $GetPseudo_Adv_Def['pseudo_defenseur'];
	$AvatarPersoDef = getAvatarPerso($RoundFight['id_membre_defenseur'], $RoundFight['id_perso_defenseur']);
	$InfosPersoDef = getInfoPersonnage($RoundFight['id_membre_defenseur'], $RoundFight['id_perso_defenseur']);
	$PuissancePersoDef = getPuissancePersonnage($RoundFight['id_membre_defenseur'], $RoundFight['id_perso_defenseur'], 'combat');
	$TotalLifeDef = $InfosPersoDef['stats_vie'];
	$CurrentLifeDef = $TotalLifeDef;
	$CurrentManaDef = ($InfosPersoDef['stats_energie'] * 100) / ($InfosPersoDef['stats_energie'] == 0 ? 1 : $InfosPersoDef['stats_energie']);
	$ShortNamePersoDef = $ArrayPersonnage[$RoundFight['id_perso_defenseur']]['short_name'];


	// Ici on initialise les variables pour le tableau de résultat
		// Côté attaquant
	$TotoDegatLanceAdv = 0;
	$TotoDegatParedAdv = 0;
	$TotoDegatInfliAdv = 0;
		// Côté défenseur
	$TotoDegatLanceDef = 0;
	$TotoDegatParedDef = 0;
	$TotoDegatInfliDef = 0;

	// On boucle le nombre de round
	for ($i = 1; $i < 8; $i++)
	{
		// Ici on switch les actions afin de savoir ce qu'on doit faire dans le tour de l'attaquant
		switch($RoundFight['round'.$i.'_attaquant'])
		{
			case "attaque":
				$TotoDegatLanceAdv += $RoundFight['round'.$i.'_attaquant_chiffre'];
				$CurrentAttaqueAdv = $RoundFight['round'.$i.'_attaquant_chiffre'];
				$AfficherAttaqueAdv = true;
				$PercentAttaqueAdv = ($CurrentAttaqueAdv * 100) / $PuissancePersoAdv['degat_max'];

				$CurrentDefenseAdv = $PuissancePersoAdv['def_min'];
				$PercentDefenseAdv = ($CurrentDefenseAdv * 100) / $PuissancePersoAdv['def_max'];
			break;

			case "defense":
				$CurrentAttaqueAdv = 0;
				$PercentAttaqueAdv = ($CurrentAttaqueAdv * 100) / $PuissancePersoAdv['degat_max'];
				$AfficherAttaqueAdv = false;
				$CurrentDefenseAdv = $RoundFight['round'.$i.'_attaquant_chiffre'];
				$PercentDefenseAdv = ($CurrentDefenseAdv * 100) / $PuissancePersoAdv['def_max'];
			break;

			case "magie":
				$AfficherAttaqueAdv = false;
			break;
		}

		// Maintenant on switch les actions du défenseur
		switch($RoundFight['round'.$i.'_defenseur'])
		{
			case "attaque":
				$TotoDegatLanceDef += $RoundFight['round'.$i.'_defenseur_chiffre'];
				$CurrentAttaqueDef = $RoundFight['round'.$i.'_defenseur_chiffre'];
				$AfficherAttaqueDef = true;
				$PercentAttaqueDef = ($CurrentAttaqueDef * 100) / $PuissancePersoDef['degat_max'];

				$CurrentDefenseDef = $PuissancePersoDef['def_min'];
				$PercentDefenseDef = ($CurrentDefenseDef * 100) / $PuissancePersoDef['def_max'];
			break;

			case "defense":
				$CurrentAttaqueDef = 0;
				$PercentAttaqueDef = ($CurrentAttaqueDef * 100) / $PuissancePersoDef['degat_max'];
				$AfficherAttaqueDef = false;
				$CurrentDefenseDef = $RoundFight['round'.$i.'_defenseur_chiffre'];
				$PercentDefenseDef = ($CurrentDefenseDef * 100) / $PuissancePersoDef['def_max'];
			break;

			case "magie":
				$AfficherAttaqueDef = false;
			break;
		}

		// On calcule ici les dégâts à INFLIGER en prenant en compte la défense de l'adversaire et de l'attaquant
			// Et on ajoute les dégâts infligé dans la variable TotoDegatInfliAdv/Def pour les résultat
		$DegatInfligeAdv = ($CurrentAttaqueAdv > 0) ? $CurrentAttaqueAdv - $CurrentDefenseDef : 0;
		$DegatInfligeAdv = ($DegatInfligeAdv < 0) ? 0 : $DegatInfligeAdv;
		$TotoDegatInfliAdv += $DegatInfligeAdv;

		$DegatInfligeDef = ($CurrentAttaqueDef > 0) ? $CurrentAttaqueDef - $CurrentDefenseAdv : 0;
		$DegatInfligeDef = ($DegatInfligeDef < 0) ? 0 : $DegatInfligeDef;
		$TotoDegatInfliDef += $DegatInfligeDef;

		// Puis maintenant on calcule la vie qu'il reste aux deux personnages
		$CurrentLifeAdv -= $DegatInfligeDef;
		$PercentCurrentLifeAdv = ($CurrentLifeAdv * 100) / $TotalLifeAdv;
		$PercentCurrentLifeAdv = ($PercentCurrentLifeAdv < 0) ? 0 : $PercentCurrentLifeAdv;

		$CurrentLifeDef -= $DegatInfligeAdv;
		$PercentCurrentLifeDef = ($CurrentLifeDef * 100) / $TotalLifeDef;
		$PercentCurrentLifeDef = ($PercentCurrentLifeDef < 0) ? 0 : $PercentCurrentLifeDef;


		// On inverse le pourcentage obtenu pour avoir une barre de pourcentage de dégât, défense et vie bonne
		$PercentAttaqueDef = 100 - $PercentAttaqueDef;
		$PercentDefenseDef = 100 - $PercentDefenseDef;
		$PercentCurrentLifeDef = 100 - $PercentCurrentLifeDef;
		if ($i != 1) $style = 'style="display:none;"';
		else $style = "";
		?>
		<div id="read-fight<?php echo $i; ?>" <?php echo $style; ?> class="cadreFight">
			<div class="read-round">
				<div class="infos-perso-adv">
					<span class="infos-perso-adv-life">
						<span class="infos-perso-adv-pseudo"><?php echo $PseudoAdv; ?></span>
						<span class="bar-life">
							<span class="progression-life" style="width: <?php echo $PercentCurrentLifeAdv ?>%">
								<span title="<?php echo $PercentCurrentLifeAdv ?>%" class="percent-life"></span>
							</span>
						</span>
						<span class="current-life">Vie : <span class="gras false"><?php echo $CurrentLifeAdv; ?></span></span>
					</span>

					<span class="infos-perso-adv-avatar">
						<img src="<?php echo TIMTHUMB.$AvatarPersoAdv.'&amp;w=104&amp;h=154' ?>" />
					</span>

					<div class="stop_float"></div>

					<span class="infos-perso-adv-mana">
						<span class="current-mana">Mana : <span class="gras false"><?php echo $InfosPersoAdv['stats_energie']; ?></span></span>
						<span class="bar-mana">
							<span class="progression-mana" style="width: <?php echo $CurrentManaAdv; ?>">
								<span title="<?php echo $CurrentManaAdv ?>%" class="percent-mana"></span>
							</span>
						</span>
					</span>
				</div>

				<div class="round-letter">
					<?php
					if ($i < 8)
					{
					?>
						<button class="button-nextRound" onclick="javascript:nextRound(<?php echo $i; ?>);" >Suite</button>
						<p style="font-size:20px;">Round</p>
						<p style="font-size:20px; color:red; text-align:center;"><?php echo $i; ?></p>
					<?php
					}
					else
					{
					?>
						<p style="font-size:20px;">Fin</p>
					<?php
					}
					?>
				</div>

				<div class="infos-perso-def">
					<span class="infos-perso-def-pseudo"><?php echo $PseudoDef; ?></span>
					<span class="infos-perso-def-life">
						<span class="bar-life-right">
							<span class="progression-life-right" style="width: <?php echo $PercentCurrentLifeDef; ?>%">
								<span title="<?php echo $PercentCurrentLifeDef ?>%" class="percent-life-right"></span>
							</span>
						</span>
						<span class="current-life"><span class="gras false"><?php echo $CurrentLifeDef; ?></span> : Vie</span>
					</span>

					<span class="infos-perso-def-avatar">
						<img src="<?php echo TIMTHUMB.$AvatarPersoDef.'&amp;w=104&amp;h=154' ?>" />
					</span>

					<div class="stop_float"></div>

					<span class="infos-perso-def-mana">
						<span class="current-mana"><span class="gras false"><?php echo $InfosPersoDef['stats_energie']; ?></span> : Mana</span>
						<span class="bar-mana-right">
							<span class="progression-mana-right" style="width: <?php echo $CurrentManaDef; ?>">
								<span title="<?php echo $CurrentManaDef ?>%" class="percent-mana-right"></span>
							</span>
						</span>
					</span>
				</div>

				<div class="stop_float"></div>

				<div class="infos-damage-defense">
					<div class="infos-damage-perso-adv">
						<span class="current-damage">Attaque : <span class="gras false"><?php echo $CurrentAttaqueAdv; ?></span></span>
						<span class="bar-damage">
							<span class="progression-damage" style="width: <?php echo $PercentAttaqueAdv; ?>%">
								<span title="damage"></span>
							</span>
						</span>

						<span class="current-defense">Défense : <span class="gras false"><?php echo $CurrentDefenseAdv; ?></span></span>
						<span class="bar-defense">
							<span class="progression-defense" style="width: <?php echo $PercentDefenseAdv; ?>%;">
								<span title="defense"></span>
							</span>
						</span>
					</div>

					<div class="infos-damage-perso-def">
						<span class="current-damage"><span class="gras false"><?php echo $CurrentAttaqueDef; ?></span> : Attaque</span>
						<span class="bar-damage-right">
							<span class="progression-damage-right" style="width: <?php echo $PercentAttaqueDef; ?>%;">
								<span title="damage"></span>
							</span>
						</span>

						<span class="current-defense"><span class="gras false"><?php echo $CurrentDefenseDef; ?></span> : Défense</span>
						<span class="bar-defense-right">
							<span class="progression-defense-right" style="width: <?php echo $PercentDefenseDef; ?>%;">
								<span title="defense"></span>
							</span>
						</span>
					</div>
				</div>

				<div class="match">
					<div class="terrain" style="background-image:url(<?php echo ROOTPATH; ?>/images/fight/terrain/<?php echo $RoundFight['terrain']; ?>.png); width:512px; height:208px;">
						<div class="terrain-left-perso">
							<span class="degat"><?php if($AfficherAttaqueAdv == true) echo $DegatInfligeAdv; ?></span>
							<img src="<?php echo ROOTPATH; ?>/images/jeu_gif/<?php echo $ShortNamePersoAdv; ?>/<?php echo $RoundFight['round'.$i.'_attaquant']; ?>-left.gif"/>
						</div>

						<div class="terrain-right-perso">
							<span class="degat"><?php if($AfficherAttaqueDef == true) echo $DegatInfligeDef; ?></span>
							<img src="<?php echo ROOTPATH; ?>/images/jeu_gif/<?php echo $ShortNamePersoDef; ?>/<?php echo $RoundFight['round'.$i.'_defenseur']; ?>-right.gif"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<?php
		// Si a la fin du round (affichage) un des joueurs n'a pu de vie, on arrête le combat
		if($CurrentLifeDef < 0 || $CurrentLifeAdv < 0)
			break;
	}
	// Fin de la boucle

	// Si jamais il y a un mort (ou plusieurs) on fait les vérifications ici
	$GainAdv = 1;
	$GainDef = 1;
	if ($CurrentLifeAdv > 0 && $CurrentLifeDef < 0) // Adversaire en vie, défenseur mort
	{
		$MotAdv = "Tueur";
		$MotDef = "Mort";
		$GainAdv = 1.5;
		$GainDef = 1;
		$AffichageMort = true;
		$GainExpCapsuleAdv = "kill";
		$GainExpCapsuleDef = "mort";
	}
	else if ($CurrentLifeDef > 0 && $CurrentLifeAdv < 0)
	{
		$MotDef = "Tueur";
		$MotAdv = "Mort";
		$GainDef = 1.5;
		$GainAdv = 1;
		$AffichageMort = true;
		$GainExpCapsuleDef = "kill";
		$GainExpCapsuleAdv = "mort";
	}
	else if ($CurrentLifeAdv < 0 && $CurrentLifeDef < 0)
	{
		$MotDef = "Mort";
		$MotAdv = "Mort";
		$GainAdv = 1;
		$GainDef = 1;
		$AffichageMort = true;
		$GainExpCapsuleAdv = "nul";
		$GainExpCapsuleDef = "nul";
	}

	// On calcule les dégâts parés durant le combat pour les deux personnages
	$TotoDegatParedAdv = $TotoDegatLanceDef - $TotoDegatInfliDef;
	$TotoDegatParedDef = $TotoDegatLanceAdv - $TotoDegatInfliAdv;

	$PseudoVainqueur = ($TotoDegatInfliAdv > $TotoDegatInfliDef) ? $PseudoAdv : $PseudoDef;
	if($TotoDegatInfliAdv == $TotoDegatInfliDef)
		$PseudoVainqueur = "null";

	// On fait les récompenses de zénis et expérience
	if ($PseudoVainqueur == $PseudoAdv) // On check si le vainqueur c'est l'adversaire
	{
		$idVainqueur = array('id_membre' => $RoundFight['id_membre_attaquant'],
							 'id_perso' => $RoundFight['id_perso_attaquant']
							 );
		$idPerdant = array('id_membre' => $RoundFight['id_membre_defenseur'],
							'id_perso' => $RoundFight['id_perso_defenseur']
							);
		$victoireAdv = 1;
		$defaiteAdv = 0;
		$tuerAdv = (isset($MotAdv) && $MotAdv == "Tueur") ? 1 : 0;
		$mortAdv = 0;
		$nulAdv = 0;
		$GainExpCapsuleAdv = "victoire";

		$victoireDef = 0;
		$defaiteDef = 1;
		$tuerDef = 0;
		$mortDef = (isset($MotDef) && $MotDef == "Mort") ? 1 : 0;
		$nulDef = 0;
		$GainExpCapsuleDef = "defaite";

		if ($RoundFight['gain_exp_adv'] == 0) // Si le match a été lancé pour la première fois, aucun gain a été attribué
		{
			// Donc on initialise les variables de gain
			$GainZenisAdv = GainZenis($idVainqueur['id_membre'], $idVainqueur['id_perso'], 'victoire');
			$GainExpAdv = GainExp($idVainqueur['id_membre'], $idVainqueur['id_perso'], 'victoire');

			$GainZenisAdv = $GainZenisAdv * $GainAdv;
			$GainExpAdv = $GainExpAdv * $GainAdv;

			$GainZenisDef = GainZenis($idPerdant['id_membre'], $idPerdant['id_perso'], 'defaite');
			$GainExpDef = GainExp($idPerdant['id_membre'], $idPerdant['id_perso'], 'defaite');

			$GainZenisDef = $GainZenisDef * $GainDef;
			$GainExpDef = $GainExpDef * $GainDef;
		}
		else // Sinon les gains sont ceux qui ont été ajoutés dans la bdd
		{
			$GainZenisAdv = $RoundFight['gain_zenis_adv'];
			$GainExpAdv = $RoundFight['gain_exp_adv'];

			$GainZenisDef = $RoundFight['gain_zenis_def'];
			$GainExpDef = $RoundFight['gain_exp_def'];
		}
	} 
	else if ($PseudoVainqueur == $PseudoDef) // Sinon c'est le défenseur
	{
		$idVainqueur = array('id_membre' => $RoundFight['id_membre_defenseur'],
							'id_perso' => $RoundFight['id_perso_defenseur']
							);
		$idPerdant = array('id_membre' => $RoundFight['id_membre_attaquant'],
							 'id_perso' => $RoundFight['id_perso_attaquant']
							 );
		$victoireDef = 1;
		$defaiteDef = 0;
		$tuerDef = (isset($MotDef) && $MotDef == "Tueur") ? 1 : 0;
		$mortDef = 0;
		$nulDef = 0;
		$GainExpCapsuleDef = "victoire";

		$victoireAdv = 0;
		$defaiteAdv = 1;
		$tuerAdv = 0;
		$mortAdv = (isset($MotAdv) && $MotAdv == "Mort") ? 1 : 0;
		$nulAdv = 0;
		$GainExpCapsuleAdv = "defaite";

		// Même chose que précedemment
		if ($RoundFight['gain_exp_adv'] == 0)
		{
			$GainZenisDef = GainZenis($idVainqueur['id_membre'], $idVainqueur['id_perso'], 'victoire');
			$GainExpDef = GainExp($idVainqueur['id_membre'], $idVainqueur['id_perso'], 'victoire');

			$GainZenisDef = $GainZenisDef * $GainDef;
			$GainExpDef = $GainExpDef * $GainDef;

			$GainZenisAdv = GainZenis($idPerdant['id_membre'], $idPerdant['id_perso'], 'defaite');
			$GainExpAdv = GainExp($idPerdant['id_membre'], $idPerdant['id_perso'], 'defaite');

			$GainZenisAdv = $GainZenisAdv * $GainAdv;
			$GainExpAdv = $GainExpAdv * $GainAdv;
		}
		else
		{
			$GainZenisAdv = $RoundFight['gain_zenis_adv'];
			$GainExpAdv = $RoundFight['gain_exp_adv'];

			$GainZenisDef = $RoundFight['gain_zenis_def'];
			$GainExpDef = $RoundFight['gain_exp_def'];
		}
	}
	else // Sinon y'a match nul
	{
		if ($PseudoVainqueur == "null")
		{
			$match_nul = true;
			$idVainqueur = array('id_membre' => $RoundFight['id_membre_attaquant'],
								 'id_perso' => $RoundFight['id_perso_attaquant']
								 );
			$idPerdant = array('id_membre' => $RoundFight['id_membre_defenseur'],
								'id_perso' => $RoundFight['id_perso_defenseur']
								);
			$victoireAdv = 0;
			$defaiteAdv = 1;
			$tuerAdv = 0;
			$mortAdv = (isset($MotAdv) && $MotAdv == "Mort") ? 1 : 0;
			$nulAdv = 1;

			$victoireDef = 0;
			$defaiteDef = 1;
			$tuerDef = 0;
			$mortDef = (isset($MotDef) && $MotDef == "Mort") ? 1 : 0;
			$nulDef = 1;

			if ($RoundFight['gain_exp_adv'] == 0)
			{
				$GainZenisDef = GainZenis($idVainqueur['id_membre'], $idVainqueur['id_perso'], 'defaite') * $GainDef;
				$GainExpDef = GainExp($idVainqueur['id_membre'], $idVainqueur['id_perso'], 'defaite') * $GainDef;

				$GainZenisDef = $GainZenisDef * $GainDef;
				$GainExpDef = $GainExpDef * $GainDef;

				$GainZenisAdv = GainZenis($idPerdant['id_membre'], $idPerdant['id_perso'], 'defaite') * $GainAdv;
				$GainExpAdv = GainExp($idPerdant['id_membre'], $idPerdant['id_perso'], 'defaite') * $GainAdv;

				$GainZenisAdv = $GainZenisAdv * $GainAdv;
				$GainExpAdv = $GainExpAdv * $GainAdv;
			}
			else
			{
				$GainZenisAdv = $RoundFight['gain_zenis_adv'];
				$GainExpAdv = $RoundFight['gain_exp_adv'];

				$GainZenisDef = $RoundFight['gain_zenis_def'];
				$GainExpDef = $RoundFight['gain_exp_def'];
			}
		}
	}

	if (isset($match_nul) && $match_nul == true)
		$match_nul = 1;
	else
		$match_nul = 0;

	// On commence à entrer toutes les données qu'il faut ! en commençant par vérifier que l'état du combat est égal à 0 !
	if ($RoundFight['etat_fight'] == 0)
	{
		// On modifie l'état du combat en y ajoutant l'id du vainqueur et du perdant
		$sqlUpdateEtatFight = $bdd->prepare("UPDATE
												jeu_liste_combat
											 SET
											 	etat_fight = '2', victoire = :id_vainqueur, 
											 	defaite = :id_perdant, match_nul = :match_nul,
												gain_exp_adv = :gain_exp_adv, gain_zenis_adv = :gain_zenis_adv,
												gain_exp_def = :gain_exp_def, gain_zenis_def = :gain_zenis_def
											 WHERE
											 	id_combat = :id_combat
											 ");
		$sqlUpdateEtatFight->bindValue('id_vainqueur', $idVainqueur['id_membre'], PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('id_perdant', $idPerdant['id_membre'], PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('match_nul', $match_nul, PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('gain_exp_adv', $GainExpAdv, PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('gain_zenis_adv', $GainZenisAdv, PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('gain_exp_def', $GainExpDef, PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('gain_zenis_def', $GainZenisDef, PDO::PARAM_INT);
		$sqlUpdateEtatFight->bindValue('id_combat', $id_fight, PDO::PARAM_INT);
		$sqlUpdateEtatFight->execute();
		
		$GainExpCapsuleAdv = GainExpCapsule($GainExpCapsuleAdv, $RoundFight['id_membre_attaquant'], $RoundFight['id_perso_attaquant']);
		$GainExpCapsuleDef = GainExpCapsule($GainExpCapsuleDef, $RoundFight['id_membre_defenseur'], $RoundFight['id_perso_defenseur']);
		// Si le fight a bien été modifié alors on poursuit notre enchaînement de requête !
		if($sqlUpdateEtatFight->rowCount() > 0)
		{
			// On commence par ajouter les gains d'expérience et les défaite victoire etc puis les zénis..
			// On commence d'abord par l'adversaire
			$sqlUpdateStatPersoAdv = $bdd->prepare("UPDATE
														jeu_liste_membre_perso
													SET
														experience = experience + :experience,
														match_victoire = match_victoire + :victoire,
														match_defaite = match_defaite + :defaite,
														match_tuer = match_tuer + :tuer,
														match_mort = match_mort + :mort,
														match_nul = match_nul + :nul
													WHERE
														id_membre = :id_membre && id_perso = :id_perso
												  ");
			$sqlUpdateStatPersoAdv->bindValue('experience', $GainExpAdv, PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('victoire', $victoireAdv, PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('defaite', $defaiteAdv, PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('tuer', $tuerAdv, PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('mort', $mortAdv, PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('nul', $nulAdv, PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('id_membre', $RoundFight['id_membre_attaquant'], PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->bindValue('id_perso', $RoundFight['id_perso_attaquant'], PDO::PARAM_INT);
			$sqlUpdateStatPersoAdv->execute();

			if($sqlUpdateStatPersoAdv->rowCount() > 0) // On vérifie que la requête s'est bien executée
			{
				// On insère les zénis de l'attaquant
				$sqlUpdateZenisAdv = $bdd->prepare("UPDATE
														site_membres
													SET
														zenis = zenis + :zenis
													WHERE
														id = :id_membre
													");
				$sqlUpdateZenisAdv->bindValue('zenis', $GainZenisAdv, PDO::PARAM_INT);
				$sqlUpdateZenisAdv->bindValue('id_membre', $RoundFight['id_membre_attaquant'], PDO::PARAM_INT);
				$sqlUpdateZenisAdv->execute();
				// Si la requête échoue, erreur
				if ($sqlUpdateZenisAdv->rowCount() == 0)
				{
					addLog(array("Erreur d'attribution des zénis pour l'attaquant", $sqlUpdateZenisAdv->errorInfo()), __FILE__,__LINE__, "admin", "error");
					$Session->setFlash(ERR_INTERNE);
				}
				else // sinon on continu
				{
					// Et maintenant on fait pour le défenseur
					$sqlUpdateStatPersoDef = $bdd->prepare("UPDATE
																jeu_liste_membre_perso
															SET
																experience = experience + :experience,
																match_victoire = match_victoire + :victoire,
																match_defaite = match_defaite + :defaite,
																match_tuer = match_tuer + :tuer,
																match_mort = match_mort + :mort,
																match_nul = match_nul + :nul
															WHERE
																id_membre = :id_membre && id_perso = :id_perso
															");
					$sqlUpdateStatPersoDef->bindValue('experience', $GainExpDef, PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('victoire', $victoireDef, PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('defaite', $defaiteDef, PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('tuer', $tuerDef, PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('mort', $mortDef, PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('nul', $nulDef, PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('id_membre', $RoundFight['id_membre_defenseur'], PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->bindValue('id_perso', $RoundFight['id_perso_defenseur'], PDO::PARAM_INT);
					$sqlUpdateStatPersoDef->execute();

					if($sqlUpdateStatPersoDef->rowCount() > 0) // On vérifie que la requête s'est bien executée
					{
						// Maintenant on ajoute les zénis
						$sqlUpdateZenisDef = $bdd->prepare("UPDATE
																site_membres
															SET
																zenis = zenis + :zenis
															WHERE
																id = :id_membre
															");
						$sqlUpdateZenisDef->bindValue('zenis', $GainZenisDef, PDO::PARAM_INT);
						$sqlUpdateZenisDef->bindValue('id_membre', $RoundFight['id_membre_defenseur'], PDO::PARAM_INT);
						$sqlUpdateZenisDef->execute();

						if($sqlUpdateZenisDef->rowCount() == 0)
						{
							addLog(array("Erreur d'attribution des zénis pour le défenseur", $sqlUpdateZenisDef->errorInfo()), __FILE__,__LINE__, "admin", "error");
							$Session->setFlash(ERR_INTERNE);
						}
					}
					else
					{
						addLog(array("Erreur de modification des stats de combat pour le défenseur", $sqlUpdateStatPersoDef->errorInfo(), $sqlUpdateStatPersoDef), __FILE__, __LINE__, "admin", "error");
						$Session->setFlash(ERR_INTERNE);
					}
				}
			}
			else
			{
				addLog(array("Les statistiques de combat n'ont pas été modifiée pour l'attaquant.", $sqlUpdateStatPersoAdv->errorInfo(), $sqlUpdateStatPersoAdv), __FILE__, __LINE__, "admin", "error");
				$Session->setFlash(ERR_INTERNE);
			}
		}
		else
		{
			addLog(array("Erreur lors de la modification de l'état du combat.", $sqlUpdateEtatFight->errorInfo()), __FILE__,__LINE__, "admin", "error");
			$Session->setFlash(ERR_INTERNE);
			return false;
		}
	}

	// Tableau des scores
	echo '<table id="tableau" class="recap" style="width:auto; margin:auto; margin-top: 25px; display: none;">';
		echo '<tr class="liste_perso" style="text-align: center;">';
			echo '<td colspan="2"><span class="gras">Récapitulatif</span></td>';
		echo '</tr>';
		
		echo '<tr class="thead">';
			echo '<th scope="col">'.$PseudoAdv.'</th>';
			echo '<th scope="col">'.$PseudoDef.'</th>';
		echo '</tr>';
		
		echo '<tbody>';
			echo '<tr class="liste_perso">';
				echo '<td>Dégât lancé : <span class="gras">'.$TotoDegatLanceAdv.'</span></td>';
				echo '<td>Dégât lancé : <span class="gras">'.$TotoDegatLanceDef.'</span></td>';
			echo '</tr>';
			
			echo '<tr class="liste_perso">';
				echo '<td>Dégât paré : <span class="gras">'.$TotoDegatParedAdv.'</span></td>';
				echo '<td>Dégât paré : <span class="gras">'.$TotoDegatParedDef.'</span></td>';
			echo '</tr>';
		
			echo '<tr class="liste_perso">';
				echo '<td>Dégât infligé : <span class="gras">'.$TotoDegatInfliAdv.'</span></td>';
				echo '<td>Dégât infligé : <span class="gras">'.$TotoDegatInfliDef.'</span></td>';
			echo '</tr>';

			echo '<tr class="liste_perso">';
				echo '<td>Expérience gagné : <span class="gras false">'.ceil($GainExpAdv).'</span></td>';
				echo '<td>Expérience gagné : <span class="gras false">'.ceil($GainExpDef).'</span></td>';
			echo '</tr>';

			echo '<tr class="liste_perso">';
				echo '<td>Zénis gagné : <span class="gras false">'.ceil($GainZenisAdv).'z</span></td>';
				echo '<td>Zénis gagné : <span class="gras false">'.ceil($GainZenisDef).'z</span></td>';
			echo '</tr>';

			if (isset($AffichageMort) && $AffichageMort == true)
			{
				echo '<tr class="liste_perso">';
					echo '<td><span class="gras false">'.$MotAdv.'</span></td>';
					echo '<td><span class="gras false">'.$MotDef.'</span></td>';
				echo '</tr>';
			}

			echo '<tr class="liste_perso" style="text-align: center;">';
				echo '<td colspan="2">';
					if ($PseudoVainqueur != "null")
						echo 'Vainqueur : <span class="gras">'.$PseudoVainqueur.'</span>';
					else
						echo '<span class="gras">Pas de vainqueur</span>';
				echo '</td>';
			echo '</tr>';
		echo '</tbody>';
	echo '</table>';
}
?>