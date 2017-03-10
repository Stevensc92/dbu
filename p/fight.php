<?php
if (is_co())
{
	$titre = "Fight";
	$decal = true;
	include(FUNCTION_DIR.'/function.fight.php');
	include(FUNCTION_DIR.'/function.personnage.php');

	$arrayGetAction = array("accepte", "refuse", "voir", "save", "unsave");
	if (isset($_GET['a']) && in_array($_GET['a'], $arrayGetAction) && isset($_GET['f']) && intval($_GET['f']))
	{
		$action = $_GET['a'];
		$id_fight = (int) intval($_GET['f']);

		$sqlVerifID_Exist = $bdd->prepare("SELECT
											etat_fight, id_membre_defenseur, id_perso_defenseur, saved
										  FROM
										  	jeu_liste_combat
										  WHERE
										  	id_combat = :id_combat
										  ");
		$sqlVerifID_Exist->bindValue('id_combat', $id_fight, PDO::PARAM_INT);
		$sqlVerifID_Exist->execute();

		if ($sqlVerifID_Exist->rowCount() > 0)
		{
			$VerifEtatFight = $sqlVerifID_Exist->fetch();
			switch ($action)
			{
				case "accepte":
					if ($VerifEtatFight['etat_fight'] == 0)
					{
						// On commence à vérifier si le combat appartient au membre et au personnage en cours
						$sqlGetDonneeFight = $bdd->prepare("SELECT
																id_combat, id_membre_attaquant, id_perso_attaquant, id_membre_defenseur, id_perso_defenseur
															FROM
																jeu_liste_combat
															WHERE
																id_combat = :id_combat && id_membre_defenseur = :id_membre &&
																id_perso_defenseur = :id_perso
															");
						$sqlGetDonneeFight->bindValue('id_combat', $id_fight, PDO::PARAM_INT);
						$sqlGetDonneeFight->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
						$sqlGetDonneeFight->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
						$sqlGetDonneeFight->execute();

						if ($sqlGetDonneeFight->rowCount() > 0)
						{
							$good = false;
							if (isset($_POST['accepteFight']))
							{
								$Round1 = $_POST['round1'];
								$Round2 = $_POST['round2'];
								$Round3 = $_POST['round3'];
								$Round4 = $_POST['round4'];
								$Round5 = $_POST['round5'];
								$Round6 = $_POST['round6'];
								$Round7 = $_POST['round7'];
								$Verification = $Round1.'-'.$Round2.'-'.$Round3.'-'.$Round4.'-'.$Round5.'-'.$Round6.'-'.$Round7;
								//Verification du nombre d'action par type du combat
								$Nb_Attaque = substr_count($Verification, 'attaque');
								$Nb_Defense = substr_count($Verification, 'defense');
								$Nb_Magie = substr_count($Verification, 'magie');

								$verifFormRound = array();
								// On met sous array les actions du round
								for ($i = 1; $i < 8; $i++)
									array_push($verifFormRound, $_POST['round'.$i]);
								
								if (verifFormRound($verifFormRound))
								{
									if (verifRound($Nb_Attaque, $Nb_Defense, $Nb_Magie, ($Nb_Magie + $Nb_Attaque)))
									{
										$good = true;
										/* On initialise les variables pour le terrain du combat, puis les chiffres du combat */
										$Round1_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round1);
										$Round2_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round2);
										$Round3_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round3);
										$Round4_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round4);
										$Round5_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round5);
										$Round6_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round6);
										$Round7_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round7);
										
										$sqlEnvoiFight = $bdd->prepare("UPDATE 
																			jeu_liste_combat
																		SET 
																			round1_defenseur = :round1, 
																			round1_defenseur_chiffre = :round1_chiffre,
																			round2_defenseur = :round2,
																			round2_defenseur_chiffre = :round2_chiffre,
																			round3_defenseur = :round3,
																			round3_defenseur_chiffre = :round3_chiffre,
																			round4_defenseur = :round4,
																			round4_defenseur_chiffre = :round4_chiffre,
																			round5_defenseur = :round5, 
																			round5_defenseur_chiffre = :round5_chiffre,
																			round6_defenseur = :round6,
																			round6_defenseur_chiffre = :round6_chiffre,
																			round7_defenseur = :round7, 
																			round7_defenseur_chiffre = :round7_chiffre
																		WHERE
																			id_combat = :id_fight && id_membre_defenseur = :id_membre &&
																			id_perso_defenseur = :id_perso
																		");
										$sqlEnvoiFight->bindValue('round1', $Round1, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round1_chiffre', $Round1_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('round2', $Round2, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round2_chiffre', $Round2_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('round3', $Round3, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round3_chiffre', $Round3_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('round4', $Round4, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round4_chiffre', $Round4_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('round5', $Round5, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round5_chiffre', $Round5_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('round6', $Round6, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round6_chiffre', $Round6_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('round7', $Round7, PDO::PARAM_STR);
										$sqlEnvoiFight->bindValue('round7_chiffre', $Round7_chiffre, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('id_fight', $id_fight, PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
										$sqlEnvoiFight->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);

										$sqlEnvoiFight->execute();
										if ($sqlEnvoiFight->rowCount() > 0)
										{
											include("includes/header.php");
											$DonneeFight = $sqlGetDonneeFight->fetch();
									
											readFight($id_fight);
										}
									}
								}
							}
							if ($good == false)
							{
								include('includes/header.php');
								// On sélectionne les infos du combats qu'on accepte
								$sqlGetInfoFight = $bdd->prepare("SELECT
																	id_membre_attaquant, id_perso_attaquant, nom_personnage, pseudo, level
																  FROM
																  	jeu_liste_combat
																  INNER JOIN
																  	site_membres
																  ON
																  	site_membres.id = jeu_liste_combat.id_membre_attaquant
																  INNER JOIN
																  	jeu_liste_personnage
																  ON
																  	jeu_liste_personnage.id_perso = jeu_liste_combat.id_perso_attaquant
																  INNER JOIN
																  	jeu_liste_membre_perso
																  ON
																  	jeu_liste_membre_perso.id_membre = jeu_liste_combat.id_membre_attaquant && 
																  	jeu_liste_membre_perso.id_perso = jeu_liste_combat.id_perso_attaquant
																  WHERE
																  	id_combat = :id_combat && id_membre_defenseur = :id_membre &&
																  	id_perso_defenseur = :id_perso
																");
								$sqlGetInfoFight->bindValue('id_combat', $id_fight, PDO::PARAM_INT);
								$sqlGetInfoFight->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
								$sqlGetInfoFight->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
								$sqlGetInfoFight->execute();

								$InfoFight = $sqlGetInfoFight->fetch();

								$AvatarAdversaire = getAvatarPerso($InfoFight['id_membre_attaquant'], $InfoFight['id_perso_attaquant']);
								?>
								<fieldset class="accepte-fight">
									<legend><img src="<?php echo ROOTPATH; ?>images/fight/combat.png" alt="Combat" title="Combat"/></legend>
									<p>Sélectionner vos actions pour le combat contre 
										<span class="gras"><?php echo $InfoFight['pseudo']; ?></span><span class="personnage">[<?php echo $InfoFight['nom_personnage']; ?>]</span></p>
									<div id="recapAdversaire">
										<img src="<?php echo $AvatarAdversaire; ?>" /><br/>
										Niveau <span class="gras"><?php echo $InfoFight['level']; ?></span><br/>
										Ki : <span class="gras"><?php echo calculKiPerso($InfoFight['id_membre_attaquant'], $InfoFight['id_perso_attaquant']); ?></span>
									</div>
									<div id="accepteFight">
										<form method="post" action="#">
											<table id="selectRound">
												<tr>
													<th></th>
													<th></th>
													<th></th>
												</tr>
												<?php
												for ($i = 1; $i < 8; $i++)
												{
													$sqlListeMagiePerso = $bdd->prepare("SELECT 
																								*
																						 FROM 
																						 		jeu_liste_membre_capsule
																						 LEFT JOIN 
																						 		jeu_liste_capsule 
																						 ON 
																						 		jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id
																						 WHERE 
																						 		id_perso_equipe = :id_current_perso && jeu_liste_capsule.type = '2' && id_membre = :id_membre");
													$sqlListeMagiePerso->bindValue('id_current_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
													$sqlListeMagiePerso->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
													$sqlListeMagiePerso->execute();
													echo '<tr class="round">';
														if ($i % 2)
														{
															?>
															<td>Round <?php echo $i; ?></td>
															<td>
																<select class="selectRound" name="round<?php echo $i; ?>">
																	<option value="undefined" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "undefined") { echo "selected"; } ?> >Sélectionner l'action</option>
																	<option value="attaque" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "attaque") { echo "selected"; } ?> >Attaquer</option>
																	<option value="defense" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "defense") { echo "selected"; } ?> >Défendre</option>
																	<?php
																	while($ListeMagiePerso = $sqlListeMagiePerso->fetch())
																	{
																		switch($ListeMagiePerso['degat'])
																		{
																			case 1.5:
																				$name = '1';
																			break;
																		
																			case 2.0:
																				$name = '2';
																			break;
																			
																			case 3.0:
																				$name = '3';
																			break;
																		}

																		$var = "magie_".$name;
																		if (isset($_POST['round'.$i]) && $_POST['round'.$i] == $var)
																			$selected = "selected";
																		else
																			$selected = "";
																		echo '<option value="'.$var.'" '.$selected.'>'.$ListeMagiePerso['nom'].'</option>';
																	}
																	?>
																</select>
															</td>
															<td></td>
															<?php
														}
														else
														{
															?>
															<td>Round <?php echo $i; ?></td>
															<td></td>
															<td>
																<select class="selectRound" name="round<?php echo $i; ?>">
																	<option value="undefined" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "undefined") { echo "selected"; } ?> >Sélectionner l'action</option>
																	<option value="attaque" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "attaque") { echo "selected"; } ?> >Attaquer</option>
																	<option value="defense" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "defense") { echo "selected"; } ?> >Défendre</option>
																	<?php
																	while($ListeMagiePerso = $sqlListeMagiePerso->fetch())
																	{
																		switch($ListeMagiePerso['degat'])
																		{
																			case 1.5:
																				$name = '1';
																			break;
																		
																			case 2.0:
																				$name = '2';
																			break;
																			
																			case 3.0:
																				$name = '3';
																			break;
																		}
																		$var = "magie_".$name;
																		if (isset($_POST['round'.$i]) && $_POST['round'.$i] == $var)
																			$selected = "selected";
																		else
																			$selected = "";
																		echo '<option value="'.$var.'" '.$selected.'>'.$ListeMagiePerso['nom'].'</option>';
																	}
																	?>
																</select>
															</td>
															<?php
														}
													echo '</tr>';
												}
												?>
												<tr>
													<td colspan="3">
														<input type="submit" value="Envoyer les défis !" name="accepteFight" />
													</td>
												</tr>
											</table>
										</form>
									</div>
								</fieldset>
								<?php
							}
						}
						else
						{
							addLog(array("Tentative d'acceptation de fight d'un autre membre. Fight id n°".$id_fight, $sqlGetDonneeFight->errorInfo()),
									$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
							$Session->setFlash("Ce n'est pas votre défis.");
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
					}
					else
					{
						$Session->setFlash("Le combat a déjà été accepté.", "info");
						header("Location: ".ROOTPATH."?p=fight");
						exit();
					}
				break;

				case "refuse": // On refuse le combat
					// On vérifie d'abord que le combat n'est pas déjà refusé
					if ($VerifEtatFight['etat_fight'] == 0 )
					{
						if ($VerifEtatFight['id_membre_defenseur'] == $_SESSION['membre_id'] && $VerifEtatFight['id_perso_defenseur'] == $_SESSION['current_perso'])
						{
							$sqlDeniedFight = $bdd->prepare("UPDATE
																jeu_liste_combat
															 SET
															 	etat_fight = '1'
															 WHERE
															 	id_combat = :id_combat
															");
							$sqlDeniedFight->bindValue('id_combat', $id_fight, PDO::PARAM_INT);
							$sqlDeniedFight->execute();

							if ($sqlDeniedFight->rowCount() > 0)
							{
								addLog("Le fight a bien été refusé : id n°".$id_fight,
										$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "success");
								$Session->setFlash('Le fight a été refusé.', 'success');

							}
							else
							{
								addLog(array("Le fight n'as pas pu être refusé : id n°".$id_fight, $sqlDeniedFight->errorInfo()),
										$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
								$Session->setFlash(ERR_INTERNE);
							}
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
						else
						{
							addLog("Un membre a essayé de refuser le fight appartenant au membre id ".$VerifEtatFight['id_membre_defenseur']." au personnage id ".$VerifEtatFight['id_perso_defenseur'], 
									$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
							$Session->setFlash("Ce n'est pas votre défis.");
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
					}
					else
					{
						addLog("Le fight id n°".$id_fight." est déjà refusé.", $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
								__FILE__, __LINE__, "admin", "error");
						$Session->setFlash('Le combat est déjà refusé.', 'info');
						header("Location: ".ROOTPATH."?p=fight");
						exit();
					}
				break;

				case "voir":
					include('includes/header.php');
					beforeReadFight($id_fight);
					echo '<div id="read-fight" style="display: none;">';
					readFight($id_fight);
					echo '</div>';
				break;

				case "save":
					if ($VerifEtatFight['saved'] == 0)
					{
						$sqlUpdateSavedFight = $bdd->prepare("UPDATE
																jeu_liste_combat
															  SET
															  	saved = '1'
															  WHERE
															  	id_combat = :id_fight
															");
						$sqlUpdateSavedFight->bindValue('id_fight', $id_fight, PDO::PARAM_INT);
						$sqlUpdateSavedFight->execute();

						if ($sqlUpdateSavedFight->rowCount() == 0)
						{
							addLog(array("Erreur lors de la sauvegarde du fight id ".$id_fight, $sqlUpdateSavedFight->errorInfo()),
									$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__,
									"admin", "erro");
							$Session->setFlash(ERR_INTERNE);
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
						else
						{
							addLog("Le fight id n°".$id_fight." a bien été sauvegardé.", $_SESSION['nom_personnage'],
									$_SESSION['membre_pseudo'], __FILE__, __LINE__, "log", "success");
							$Session->setFlash("Le fight a bien été sauvegardé.", "success");
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
					}
					else
					{
						$Session->setFlash("Le fight est déjà sauvegardé.", "info");
						header("Location: ".ROOTPATH."?p=fight");
						exit();
					}
				break;

				case "unsave";
					if ($VerifEtatFight['saved'] == 1)
					{
						$sqlUpdateSavedFight = $bdd->prepare("UPDATE
																jeu_liste_combat
															  SET
																saved = '0'
															  WHERE
															  	id_combat = :id_fight
															");
						$sqlUpdateSavedFight->bindValue('id_fight', $id_fight, PDO::PARAM_INT);
						$sqlUpdateSavedFight->execute();

						if ($sqlUpdateSavedFight->rowCount() == 0)
						{
							addLog(array("Erreur lors de la unsauvegarde du fight id ".$id_fight, $sqlUpdateSavedFight->errorInfo()),
									$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__,
									"admin", "erro");
							$Session->setFlash(ERR_INTERNE);
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
						else
						{
							addLog("Le fight id n°".$id_fight." a bien été supprimé des fights sauvés.", $_SESSION['nom_personnage'],
									$_SESSION['membre_pseudo'], __FILE__, __LINE__, "log", "success");
							$Session->setFlash("Le fight a bien été retiré des fights sauvegardés.", "success");
							header("Location: ".ROOTPATH."?p=fight");
							exit();
						}
					}
					else
					{
						$Session->setFlash("Le fight ne fait pas parti des fights sauvegardés.", "info");
						header("Location: ".ROOTPATH."?p=fight");
						exit();
					}
				break;
			} // Fin du switch des actions (accepte, refuse, voir, save, unsave)
		} // Fin du if si l'id du fight est correct
		else
		{
			addLog(array("Le combat id n°".$id_fight." n'existe pas.", $sqlVerifID_Exist->errorInfo()),
					$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
			header("Location: ".ROOTPATH."?p=fight");
		}
	}
	else
	{
		if (isset($_POST['sendFight']) || isset($_POST['sendFightByRound'])) // Si le bouton pour envoyer les combats est soumis
		{
			if (isset($_POST['sendFight']) && !empty($_POST['idPerso']) && (count($_POST['idPerso']) <= LIMIT_FIGHT) || isset($_POST['sendFightByRound']))
			{
				if (isset($_POST['sendFightByRound'])) // Deuxième partie pour envoyer les fights -> sélection des rounds
				{
					if(substr($_POST['idPerso'], -1) == '/') // on retire le dernier / de la chaine
					{
						$_POST['idPerso'] = substr($_POST['idPerso'], 0, -1);
					}

					$Round1 = $_POST['round1'];
					$Round2 = $_POST['round2'];
					$Round3 = $_POST['round3'];
					$Round4 = $_POST['round4'];
					$Round5 = $_POST['round5'];
					$Round6 = $_POST['round6'];
					$Round7 = $_POST['round7'];
					$Verification = $Round1.'-'.$Round2.'-'.$Round3.'-'.$Round4.'-'.$Round5.'-'.$Round6.'-'.$Round7;
					//Verification du nombre d'action par type du combat
					$Nb_Attaque = substr_count($Verification, 'attaque');
					$Nb_Defense = substr_count($Verification, 'defense');
					$Nb_Magie = substr_count($Verification, 'magie');

					$verifFormRound = array();
					// On met sous array les actions du round
					for ($i = 1; $i < 8; $i++)
						array_push($verifFormRound, $_POST['round'.$i]);
					
					if (verifFormRound($verifFormRound))
					{
						if (verifRound($Nb_Attaque, $Nb_Defense, $Nb_Magie, ($Nb_Magie + $Nb_Attaque)))
						{
							$NbFight = explode('/', $_POST['idPerso']);
							foreach($NbFight as $NbFight)
							{
								$id_membre[] = explode('-', $NbFight);
							}

							$i = 0;

							while($i < count($id_membre))
							{
								/* On initialise les variables pour le terrain du combat, puis les chiffres du combat */
								$Terrain_Combat = mt_rand(1,10);
								$Round1_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round1);
								$Round2_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round2);
								$Round3_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round3);
								$Round4_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round4);
								$Round5_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round5);
								$Round6_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round6);
								$Round7_chiffre = getDegat($_SESSION['membre_id'], $_SESSION['current_perso'], $Round7);

								/* On vérifie que la personne n'a pas déjà envoyé de combat à la personne */
								$sqlVerif = $bdd->prepare("SELECT
																*
														   FROM 
														   		jeu_liste_combat
														   WHERE 
														   		id_membre_attaquant = :id_membre_attaquant && id_perso_attaquant = :id_perso_attaquant &&
																id_membre_defenseur = :id_membre_defenseur && id_perso_defenseur = :id_perso_defenseur");
								$sqlVerif->bindValue('id_membre_attaquant', $_SESSION['membre_id'], PDO::PARAM_INT);
								$sqlVerif->bindValue('id_perso_attaquant', $_SESSION['current_perso'], PDO::PARAM_INT);
								$sqlVerif->bindValue('id_membre_defenseur', $id_membre[$i][0], PDO::PARAM_INT);
								$sqlVerif->bindValue('id_perso_defenseur', $id_membre[$i][1], PDO::PARAM_INT);
								if($sqlVerif->rowCount() > 0)
								{
									$i = $i;
								}
								else
								{
									$sqlEnvoiFight = $bdd->prepare("INSERT INTO 
																		jeu_liste_combat(id_membre_attaquant, id_perso_attaquant,
																						 round1_attaquant, round1_attaquant_chiffre,
																						 round2_attaquant, round2_attaquant_chiffre,
																						 round3_attaquant, round3_attaquant_chiffre,
																						 round4_attaquant, round4_attaquant_chiffre,
																						 round5_attaquant, round5_attaquant_chiffre,
																						 round6_attaquant, round6_attaquant_chiffre,
																						 round7_attaquant, round7_attaquant_chiffre,
																						 id_membre_defenseur, id_perso_defenseur, 
																						 terrain, date)
																	VALUES (:id_membre, :id_current_perso, 
																			:round1, :round1_chiffre,
																			:round2, :round2_chiffre,
																			:round3, :round3_chiffre,
																			:round4, :round4_chiffre,
																			:round5, :round5_chiffre,
																			:round6, :round6_chiffre,
																			:round7, :round7_chiffre,
																			:id_membre_defenseur, :id_perso_defenseur, 
																			:terrain, :date)");
									$sqlEnvoiFight->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('id_current_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round1', $Round1, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round1_chiffre', $Round1_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round2', $Round2, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round2_chiffre', $Round2_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round3', $Round3, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round3_chiffre', $Round3_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round4', $Round4, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round4_chiffre', $Round4_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round5', $Round5, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round5_chiffre', $Round5_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round6', $Round6, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round6_chiffre', $Round6_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('round7', $Round7, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('round7_chiffre', $Round7_chiffre, PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('id_membre_defenseur', $id_membre[$i][0], PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('id_perso_defenseur', $id_membre[$i][1], PDO::PARAM_INT);
									$sqlEnvoiFight->bindValue('terrain', $Terrain_Combat, PDO::PARAM_STR);
									$sqlEnvoiFight->bindValue('date', time(), PDO::PARAM_INT);

									$sqlEnvoiFight->execute();
									$i++;
								}
							} // Fin de la boucle pour envoyer les combats

							if($sqlEnvoiFight->rowCount() > 0)
							{
								if($i == 1)
								{
									$Session->setFlash($i.' défi a été envoyé.', 'success');
								}
								else
								{
									$Session->setFlash($i.' défis ont été envoyés.', 'success');
								}
							}
							else
							{
								$Session->setFlash('Une erreur s\'est produite lors de l\'envoi des défis.', 'error');
							}
							header("Location: ".ROOTPATH."/?p=fight");
							exit();
						} // Fin du if (verifRound());
					} // Fin du if (verifFormRound());
				} // Fin du if (isset($_POST['sendFormByRound'])) (sélection des rounds)

				include('includes/header.php');
				?>
				<h1 class="title-sendfight">Sélection des rounds</h1>
				<div id="choiceRound">
					<form method="post" action="#">
						<table id="selectRound">
							<tr>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<?php
							for ($i = 1; $i < 8; $i++)
							{
								$sqlListeMagiePerso = $bdd->prepare("SELECT 
																			*
																	 FROM 
																	 		jeu_liste_membre_capsule
																	 LEFT JOIN 
																	 		jeu_liste_capsule 
																	 ON 
																	 		jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id
																	 WHERE 
																	 		id_perso_equipe = :id_current_perso && jeu_liste_capsule.type = '2' && id_membre = :id_membre");
								$sqlListeMagiePerso->bindValue('id_current_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
								$sqlListeMagiePerso->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
								$sqlListeMagiePerso->execute();
								echo '<tr class="round">';
									if ($i % 2)
									{
										?>
										<td>Round <?php echo $i; ?></td>
										<td>
											<select class="selectRound" name="round<?php echo $i; ?>">
												<option value="undefined" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "undefined") { echo "selected"; } ?> >Sélectionner l'action</option>
												<option value="attaque" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "attaque") { echo "selected"; } ?> >Attaquer</option>
												<option value="defense" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "defense") { echo "selected"; } ?> >Défendre</option>
												<?php
												while($ListeMagiePerso = $sqlListeMagiePerso->fetch())
												{
													switch($ListeMagiePerso['degat'])
													{
														case 1.5:
															$name = '1';
														break;
													
														case 2.0:
															$name = '2';
														break;
														
														case 3.0:
															$name = '3';
														break;
													}

													$var = "magie_".$name;
													if (isset($_POST['round'.$i]) && $_POST['round'.$i] == $var)
														$selected = "selected";
													else
														$selected = "";
													echo '<option value="'.$var.'" '.$selected.'>'.$ListeMagiePerso['nom'].'</option>';
												}
												?>
											</select>
										</td>
										<td></td>
										<?php
									}
									else
									{
										?>
										<td>Round <?php echo $i; ?></td>
										<td></td>
										<td>
											<select class="selectRound" name="round<?php echo $i; ?>">
												<option value="undefined" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "undefined") { echo "selected"; } ?> >Sélectionner l'action</option>
												<option value="attaque" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "attaque") { echo "selected"; } ?> >Attaquer</option>
												<option value="defense" <?php if(isset($_POST['round'.$i]) && $_POST['round'.$i] == "defense") { echo "selected"; } ?> >Défendre</option>
												<?php
												while($ListeMagiePerso = $sqlListeMagiePerso->fetch())
												{
													switch($ListeMagiePerso['degat'])
													{
														case 1.5:
															$name = '1';
														break;
													
														case 2.0:
															$name = '2';
														break;
														
														case 3.0:
															$name = '3';
														break;
													}
													$var = "magie_".$name;
													if (isset($_POST['round'.$i]) && $_POST['round'.$i] == $var)
														$selected = "selected";
													else
														$selected = "";
													echo '<option value="'.$var.'" '.$selected.'>'.$ListeMagiePerso['nom'].'</option>';
												}
												?>
											</select>
										</td>
										<?php
									}
								echo '</tr>';
							}
							?>
							<tr>
								<td colspan="3">
									<input type="hidden" name="idPerso" value="<?php if(is_array($_POST['idPerso'])) foreach($_POST['idPerso'] as $var) echo $var; else echo $_POST['idPerso']; ?>" />
									<input type="submit" value="Envoyer les défis !" name="sendFightByRound" />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<?php
			}
			else
			{
				$Session->setFlash("Vous avez sélectionné personne à défier");
				header("Location: ".$_SESSION['last_url']);
				exit();
			}
		}
		else
		{
			include('includes/header.php');

			if (isset($_POST['sendFight']) && empty($_POST['idPerso']))
				$Session->setFlash('Vous avez sélectionné aucune personne à défier');
			else if (isset($_POST['sendFight']) && (count($_POST['idPerso']) > LIMIT_FIGHT))
				$Session->setFlash('La limite est de '.LIMIT_FIGHT.' combats à envoyer simultanéments');
			// Affichage des membres à défier

			$sqlListePerso = $bdd->prepare("SELECT 
												site_membres.pseudo, site_membres.id, 
												jeu_liste_membre_perso.level AS PersoLevel, jeu_liste_membre_perso.id_perso,
												match_victoire, match_defaite, match_tuer, experience
											FROM 
												jeu_liste_membre_perso
											LEFT JOIN 
												site_membres 
											ON 
												jeu_liste_membre_perso.id_membre = site_membres.id
											LEFT JOIN 
												jeu_liste_personnage 
											ON
												jeu_liste_membre_perso.id_perso = jeu_liste_personnage.id_perso
											WHERE 
												id_membre != :id_membre && rang != '0'
											ORDER by 
												jeu_liste_membre_perso.experience ASC, site_membres.pseudo ASC");
			$sqlListePerso->bindValue('id_membre', $_SESSION['membre_id']);
			$sqlListePerso->execute();

			echo '<h1 class="title-tableau">Défier quelqu\'un <span class="title-info">('.LIMIT_FIGHT.' combats simultanéments)</span></h1>';
			echo '<form method="post" action="#">';
				echo '<table class="tableau">';
					echo '<thead>';
						echo '<tr class="liste_perso">';
							echo '<th class="liste_perso"></th>';
							echo '<th class="liste_perso">Pseudo</th>';
							echo '<th class="liste_perso">Personnage</th>';
							echo '<th class="liste_perso">Niveau</th>';
							echo '<th class="liste_perso">Expérience</th>';
							echo '<th class="liste_perso">Victoires</th>';
							echo '<th class="liste_perso">Défaites</th>';
							echo '<th class="liste_perso">Kills</th>';
							echo '<th class="liste_perso">Ki</th>';
						echo '</tr>';
					echo '</thead>';

					echo '<tbody>';
					if ($sqlListePerso->rowCount() > 0)
					{
						while ($ListePerso = $sqlListePerso->fetch())
						{
							$sqlVerif = $bdd->prepare("SELECT 
															*
													   FROM 
													   		jeu_liste_combat
													   WHERE 
													   		id_membre_attaquant = :id_membre_attaquant && id_perso_attaquant = :id_perso_attaquant &&
															id_membre_defenseur = :id_membre_defenseur && id_perso_defenseur = :id_perso_defenseur");
							$sqlVerif->bindValue('id_membre_attaquant', $_SESSION['membre_id'], PDO::PARAM_INT);
							$sqlVerif->bindValue('id_perso_attaquant', $_SESSION['current_perso'], PDO::PARAM_INT);
							$sqlVerif->bindValue('id_membre_defenseur', $ListePerso['id'], PDO::PARAM_INT);
							$sqlVerif->bindValue('id_perso_defenseur', $ListePerso['id_perso'], PDO::PARAM_INT);
							$sqlVerif->execute();

							if ($sqlVerif->rowCount() == 0)
							{

								$rangMembre = getRang($ListePerso['id']);

								if ($rangMembre[0] != "" && $rangMembre[1] != "")
									$styleRang = '<span class="'.$rangMembre[1].'">'.$rangMembre[0].'</span>';
								else
									$styleRang = "";

								echo '<tr>';
									echo '<td class="liste_perso"><input type="checkbox" name="idPerso[]" value="'.$ListePerso['id'].'-'.$ListePerso['id_perso'].'/" /></td>';
									echo '<td class="liste_perso">'.$styleRang.' <a href="'.ROOTPATH.'?p=profil&amp;id='.$ListePerso['id'].'" class="href-pseudo-fight" >'.$ListePerso['pseudo'].'</a></td>';
									echo '<td class="liste_perso">'.$ArrayPersonnage[$ListePerso['id_perso']]['nom_personnage'].'</td>';
									echo '<td class="liste_perso">'.$ListePerso['PersoLevel'].'</td>';
									echo '<td class="liste_perso">'.$ListePerso['experience'].'</td>';
									echo '<td class="liste_perso">'.$ListePerso['match_victoire'].'</td>';
									echo '<td class="liste_perso">'.$ListePerso['match_defaite'].'</td>';
									echo '<td class="liste_perso">'.$ListePerso['match_tuer'].'</td>';
									echo '<td class="liste_perso">'.calculKiPerso($ListePerso['id'], $ListePerso['id_perso']).'</td>';
								echo '</tr>';
							}
						}
						echo '<tr class="submit liste_perso">';
							echo '<td colspan="9"><input type="submit" value="Envoyer" name="sendFight" /></td>';
						echo '</tr>';
					}
					else
					{
						echo '<tr class="liste_perso">';
							echo '<td class="td-mult liste_perso" colspan="9">Il n\'y a plus personne à défier pour le moment, revient plus tard.</td>';
						echo '</tr>';
					}
					echo '</tbody>';
				echo '</table>';
			echo '</form>';


			// Affichage des défis reçus en attente

			$sqlListeFight = $bdd->prepare("SELECT 
												*, jeu_liste_membre_perso.level AS PersoLevel
											FROM 
												jeu_liste_combat
											LEFT JOIN 
												jeu_liste_membre_perso 
											ON 
												jeu_liste_combat.id_membre_attaquant = jeu_liste_membre_perso.id_membre
											LEFT JOIN 
												jeu_liste_personnage 
											ON 
												jeu_liste_combat.id_perso_attaquant = jeu_liste_personnage.id_perso
											LEFT JOIN 
												site_membres 
											ON 
												jeu_liste_combat.id_membre_attaquant = site_membres.id
											WHERE 
												jeu_liste_combat.id_perso_attaquant = jeu_liste_membre_perso.id_perso && id_membre_defenseur = :id_membre && id_perso_defenseur = :id_perso");
			$sqlListeFight->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
			$sqlListeFight->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
			$sqlListeFight->execute();

			echo '<h1 class="title-tableau">Défis reçu</h1>';
			echo '<form method="post" action="#">';
				echo '<table class="tableau">';
					echo '<thead>';
						echo '<th class="liste_perso">Pseudo</th>';
						echo '<th class="liste_perso">Personnage</th>';
						echo '<th class="liste_perso">Niveau</th>';
						echo '<th class="liste_perso">Expérience</th>';
						echo '<th class="liste_perso">Ki</th>';
						echo '<th class="liste_perso">Date</th>';
						echo '<th class="liste_perso">État</th>';
						echo '<th class="liste_perso"></th>';
					echo '</thead>';

					echo '<tbody>';
						if ($sqlListeFight->rowCount() > 0)
						{
							while ($ListeFight = $sqlListeFight->fetch())
							{
								$rangMembre = getRang($ListeFight['id']);

								if ($rangMembre[0] != "" && $rangMembre[1] != "")
									$styleRang = '<span class="'.$rangMembre[1].'">'.$rangMembre[0].'</span>';
								else
									$styleRang = "";

								echo '<tr class="liste_perso">';
									echo '<td class="liste_perso">'.$styleRang.' <a href="'.ROOTPATH.'?p=profil&amp;id='.$ListeFight['id'].'" class="href-pseudo-fight" >'.$ListeFight['pseudo'].'</a></td>';
									echo '<td class="liste_perso">'.$ListeFight['nom_personnage'].'</td>';
									echo '<td class="liste_perso">'.$ListeFight['PersoLevel'].'</td>';
									echo '<td class="liste_perso">'.$ListeFight['experience'].'</td>';
									echo '<td class="liste_perso">'.calculKiPerso($ListeFight['id'], $ListeFight['id_perso']).'</td>';
									echo '<td class="liste_perso">'.mepd($ListeFight['date']).'</td>';
									echo '<td class="liste_perso">';
										switch($ListeFight['etat_fight'])
										{
											case "0":
											?>
												<a style="text-decoration: none;" href="<?php echo ROOTPATH; ?>?p=fight&amp;a=accepte&amp;f=<?php echo $ListeFight['id_combat']; ?>">
													<img src="<?php echo ROOTPATH; ?>images/fight/accepte.png" />
												</a>/ 
												<a href="<?php echo ROOTPATH; ?>?p=fight&amp;a=refuse&amp;f=<?php echo $ListeFight['id_combat']; ?>">
													<img src="<?php echo ROOTPATH; ?>images/fight/refuse.png" />
												</a>
											<?php
											break;
											
											case "1":
											?>
											<img src="<?php echo ROOTPATH; ?>images/fight/refuse.png" alt="Refusé" title="Refusé"/>
											<?php
											break;
											
											case "2":
												if($ListeFight['victoire'] == $_SESSION['membre_id'])
												{
													echo '<a href="'.ROOTPATH.'?p=fight&amp;a=voir&amp;f='.$ListeFight['id_combat'].'" class="no-lien">Gagné</a>';
												}
												else
												{
													echo '<a href="'.ROOTPATH.'?p=fight&amp;a=voir&amp;f='.$ListeFight['id_combat'].'" class="no-lien">Défaite</a>';
												}
											break;
										}
									echo '</td>';
									echo '<td class="liste_perso">';
										if ($ListeFight['etat_fight'] == 2)
										{
											switch ($ListeFight['saved'])
											{
												case 0:
													$class = "entypo-floppy";
													$action = "save";
													$title = "Sauver le fight";
												break;

												case 1:
													$class = "icon-remove";
													$action = "unsave";
													$title = "Retirer le fight sauvegardé";
												break;
											}
											echo '<a href="'.ROOTPATH.'?p=fight&amp;a='.$action.'&amp;f='.$ListeFight['id_combat'].'" class="no-lien"><span class="'.$class.'" title="'.$title.'"></span></a>';
										}
									echo '</td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr class="liste_perso">';
								echo '<td class="td-mult liste_perso" colspan="8">Vous n\'avez reçu aucun défi.</td>';
							echo '</tr>';
						}
					echo '</tbody>';
				echo '</table>';
			echo '</form>';

			// Affichage des défis envoyés

			$sqlListeFightSended = $bdd->prepare("SELECT 
													*, jeu_liste_membre_perso.level AS PersoLevel
												FROM 
													jeu_liste_combat
												LEFT JOIN 
													jeu_liste_membre_perso 
												ON 
													jeu_liste_combat.id_membre_defenseur = jeu_liste_membre_perso.id_membre
												LEFT JOIN 
													jeu_liste_personnage 
												ON 
													jeu_liste_combat.id_perso_defenseur = jeu_liste_personnage.id_perso
												LEFT JOIN 
													site_membres 
												ON 
													jeu_liste_combat.id_membre_defenseur = site_membres.id
												WHERE 
													jeu_liste_combat.id_perso_defenseur = jeu_liste_membre_perso.id_perso && id_membre_attaquant = :id_membre && id_perso_attaquant = :id_perso
												ORDER BY date DESC");
			$sqlListeFightSended->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
			$sqlListeFightSended->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
			$sqlListeFightSended->execute();

			echo '<h1 class="title-tableau">Défis envoyé</h1>';
			echo '<form method="post" action="#">';
				echo '<table class="tableau">';
					echo '<thead>';
						echo '<th class="liste_perso">Pseudo</th>';
						echo '<th class="liste_perso">Personnage</th>';
						echo '<th class="liste_perso">Niveau</th>';
						echo '<th class="liste_perso">Expérience</th>';
						echo '<th class="liste_perso">Ki</th>';
						echo '<th class="liste_perso">Date</th>';
						echo '<th class="liste_perso">État</th>';
						echo '<th class="liste_perso"></th>';
					echo '</thead>';

					echo '<tbody>';
						if ($sqlListeFightSended->rowCount() > 0)
						{
							while ($ListeFightSended = $sqlListeFightSended->fetch())
							{
								$rangMembre = getRang($ListeFightSended['id']);

								if ($rangMembre[0] != "" && $rangMembre[1] != "")
									$styleRang = '<span class="'.$rangMembre[1].'">'.$rangMembre[0].'</span>';
								else
									$styleRang = "";
								echo '<tr class="liste_perso">';
									echo '<td class="liste_perso">'.$styleRang.' <a href="'.ROOTPATH.'?p=profil&amp;id='.$ListeFightSended['id'].'" class="href-pseudo-fight" >'.$ListeFightSended['pseudo'].'</a></td>';
									echo '<td class="liste_perso">'.$ListeFightSended['nom_personnage'].'</td>';
									echo '<td class="liste_perso">'.$ListeFightSended['PersoLevel'].'</td>';
									echo '<td class="liste_perso">'.$ListeFightSended['experience'].'</td>';
									echo '<td class="liste_perso">'.calculKiPerso($ListeFightSended['id'], $ListeFightSended['id_perso']).'</td>';
									echo '<td class="liste_perso">'.mepd($ListeFightSended['date']).'</td>';
									echo '<td class="liste_perso">';
										switch($ListeFightSended['etat_fight'])
										{
											case "0":
											?>
												En attente
											<?php
											break;
											
											case "1":
											?>
												<img src="<?php echo ROOTPATH; ?>images/fight/refuse.png" alt="Refusé" title="Refusé"/>
											<?php
											break;
											
											case "2":
												if($ListeFightSended['victoire'] == $_SESSION['membre_id'])
												{
													echo '<a href="'.ROOTPATH.'?p=fight&amp;a=voir&amp;f='.$ListeFightSended['id_combat'].'" class="no-lien">Gagné</a>';
												}
												else
												{
													echo '<a href="'.ROOTPATH.'?p=fight&amp;a=voir&amp;f='.$ListeFightSended['id_combat'].'" class="no-lien">Défaite</a>';
												}
											break;
										}
									echo '</td>';
									echo '<td class="liste_perso">';
										if ($ListeFightSended['etat_fight'] == 2)
										{
											switch ($ListeFightSended['saved'])
											{
												case 0:
													$class = "entypo-floppy";
													$action = "save";
													$title = "Sauver le fight";
												break;

												case 1:
													$class = "icon-remove";
													$action = "unsave";
													$title = "Retirer le fight sauvegardé";
												break;
											}
											echo '<a href="'.ROOTPATH.'?p=fight&amp;a='.$action.'&amp;f='.$ListeFightSended['id_combat'].'" class="no-lien"><span class="'.$class.'" title="'.$title.'"></span></a>';
										}
									echo '</td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr class="liste_perso">';
								echo '<td class="td-mult liste_perso" colspan="8">Vous n\'avez envoyé aucun défi.</td>';
							echo '</tr>';
						}
					echo '</tbody>';
				echo '</table>';
			echo '</form>';

			/*$sqlListeFightSended = $bdd->prepare("SELECT 
													*, jeu_liste_membre_perso.level AS PersoLevel
												FROM 
													jeu_liste_combat
												LEFT JOIN 
													jeu_liste_membre_perso 
												ON 
													jeu_liste_combat.id_membre_defenseur = jeu_liste_membre_perso.id_membre
												LEFT JOIN 
													jeu_liste_personnage 
												ON 
													jeu_liste_combat.id_perso_defenseur = jeu_liste_personnage.id_perso
												LEFT JOIN 
													site_membres 
												ON 
													jeu_liste_combat.id_membre_defenseur = site_membres.id
												WHERE 
													saved == 1
												ORDER BY date DESC");
			$sqlListeFightSended->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
			$sqlListeFightSended->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
			$sqlListeFightSended->execute();

			echo '<h1 class="title-tableau">Défis sauvegardés</h1>';
			echo '<form method="post" action="#">';
				echo '<table class="tableau">';
					echo '<thead>';
						echo '<th class="liste_perso">Pseudo</th>';
						echo '<th class="liste_perso">Personnage</th>';
						echo '<th class="liste_perso">Niveau</th>';
						echo '<th class="liste_perso">Expérience</th>';
						echo '<th class="liste_perso">Ki</th>';
						echo '<th class="liste_perso">Date</th>';
						echo '<th class="liste_perso">État</th>';
						echo '<th class="liste_perso"></th>';
					echo '</thead>';

					echo '<tbody>';
						if ($sqlListeFightSended->rowCount() > 0)
						{
							while ($ListeFightSended = $sqlListeFightSended->fetch())
							{
								$rangMembre = getRang($ListeFightSended['id']);

								if ($rangMembre[0] != "" && $rangMembre[1] != "")
									$styleRang = '<span class="'.$rangMembre[1].'">'.$rangMembre[0].'</span>';
								else
									$styleRang = "";
								echo '<tr>';
									echo '<td>'.$styleRang.' <a href="'.ROOTPATH.'?p=profil&amp;id='.$ListeFightSended['id'].'" class="href-pseudo-fight" >'.$ListeFightSended['pseudo'].'</a></td>';
									echo '<td>'.$ListeFightSended['nom_personnage'].'</td>';
									echo '<td>'.$ListeFightSended['PersoLevel'].'</td>';
									echo '<td>'.$ListeFightSended['experience'].'</td>';
									echo '<td>'.calculKiPerso($ListeFightSended['id'], $ListeFightSended['id_perso']).'</td>';
									echo '<td>'.mepd($ListeFightSended['date']).'</td>';
									echo '<td>';
										switch($ListeFightSended['etat_fight'])
										{
											case "0":
											?>
												En attente
											<?php
											break;
											
											case "1":
											?>
												<img src="<?php echo ROOTPATH; ?>images/fight/refuse.png" alt="Refusé" title="Refusé"/>
											<?php
											break;
											
											case "2":
												if($ListeFightSended['victoire'] == $_SESSION['membre_id'])
												{
													echo '<a href="'.ROOTPATH.'?p=fight&amp;a=voir&amp;f='.$ListeFightSended['id_combat'].'">Gagné</a>';
												}
												else
												{
													echo '<a href="'.ROOTPATH.'?p=fight&amp;a=voir&amp;f='.$ListeFightSended['id_combat'].'">Défaite</a>';
												}
											break;
										}
									echo '</td>';
									echo '<td><a href="'.ROOTPATH.'?p=fight&amp;a=save&amp;f='.$ListeFightSended['id_combat'].'" class="no-lien"><span class="entypo-floppy"></span></a></td>';
								echo '</tr>';
							}
						}
						else
						{
							echo '<tr>';
								echo '<td class="td-mult" colspan="8">Vous n\'avez envoyé aucun défi.</td>';
							echo '</tr>';
						}
					echo '</tbody>';
				echo '</table>';
			echo '</form>';*/
		}
	}
}
else
{
	header("Location: ".ROOTPATH);
}
?>