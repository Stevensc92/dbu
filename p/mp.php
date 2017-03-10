<?php
if (is_co())
{
	$titre = "Messagerie privé";
	$decal = true;
	$action_auto = array("consulter", "nouveau", "sended", "bloqued", "debloquer");

	$arrayLien = array(
						"" => "Boite de réception",
						"nouveau" => "Nouveau message privé",
						"sended" => "Message envoyé",
						"bloqued" => "Bloquer une personne"
					);

	if (isset($_GET['action']))
	{
		if (in_array($_GET['action'], $action_auto))
		{
			$action = $_GET['action'];

			switch ($action)
			{
				case "consulter":
					if (isset($_POST['reponse']))
					{
						if (!empty($_POST['textarea']) && isset($_POST['destinataire']) && intval($_POST['destinataire']) && isset($_POST['title']))
						{
							$message = $_POST['textarea'];
							$destinataire = intval($_POST['destinataire']);
							$titre = "Re : ".$_POST['title'];

							$sqlVerifExistMemberById = $bdd->prepare("SELECT
																		pseudo
																	  FROM
																	  	site_membres
																	  WHERE
																	  	id = :id
																	");
							$sqlVerifExistMemberById->bindValue('id', $destinataire, PDO::PARAM_INT);
							$sqlVerifExistMemberById->execute();

							if ($sqlVerifExistMemberById->rowCount() > 0)
							{
								$PseudoDest = $sqlVerifExistMemberById->fetch();
								$Liste = GetMembreMpBloqued($destinataire);
								if (!in_array($_SESSION['membre_id'], $Liste))
								{
									$sqlEnvoiMp = $bdd->prepare("INSERT INTO site_mp(
																				mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time
																			)
																VALUES(
																		:mp_expediteur, :mp_receveur, :mp_titre, :mp_text, :mp_time
																	)");
									$sqlEnvoiMp->bindValue('mp_expediteur', $_SESSION['membre_id'], PDO::PARAM_INT);
									$sqlEnvoiMp->bindValue('mp_receveur', $destinataire, PDO::PARAM_INT);
									$sqlEnvoiMp->bindValue('mp_titre', $titre, PDO::PARAM_INT);
									$sqlEnvoiMp->bindValue('mp_text', $message, PDO::PARAM_STR);
									$sqlEnvoiMp->bindValue('mp_time', time(), PDO::PARAM_INT);
									$sqlEnvoiMp->execute();

									if ($sqlEnvoiMp->rowCount() > 0)
									{
										$Session->setFlash("Le message privé a bien été envoyé à ".$PseudoDest['pseudo'], "success");
										header("Location: ".$_SESSION['last_url']);
										exit();
									}
									else
									{
										addLog(array("Erreur lors de l'envoie de message privé.", $sqlEnvoiMp->errorInfo()), $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
												__FILE__, __LINE__, "admin", "error");
										$Session->setFlash("Le message privé n'a pas été envoyé.");
										header("Location: ".$_SESSION['last_url']);
										exit();
									}
								}
								else
								{
									$Session->setFlash("Le membre ".$PseudoDest['pseudo']." vous a bloqué.", "info");
									header("Location: ".$_SESSION['last_url']);
									exit();
								}
							}
							else
							{
								$Session->setFlash("Le destinataire n'existe pas.");
								header("Location: ".$_SESSION['last_url']);
								exit();
							}
						}
						else
						{
							$Session->setFlash("Certains champs ne sont pas remplies.");
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
					}
					if (isset($_GET['id']) && intval($_GET['id']))
					{
						$mp_id = $_GET['id'];
						$sqlGetInfoMessage = $bdd->prepare("SELECT
																mp_id, mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, pseudo, id, mp_lu
															FROM
																site_mp
															INNER JOIN
																site_membres
															ON
																site_membres.id = site_mp.mp_expediteur
															WHERE
																mp_id = :mp_id
															");
						$sqlGetInfoMessage->bindValue('mp_id', $mp_id, PDO::PARAM_INT);
						$sqlGetInfoMessage->execute();

						if ($sqlGetInfoMessage->rowCount() > 0)
						{
							$InfoMessage = $sqlGetInfoMessage->fetch();

							if ($_SESSION['membre_id'] == $InfoMessage['mp_receveur'])
							{
								if ($InfoMessage['mp_lu'] == 0)
								{
									$sqlUpdateLuMp = $bdd->prepare("UPDATE
																		site_mp
																	SET
																		mp_lu = '1'
																	WHERE
																		mp_id = :mp_id
																	");
									$sqlUpdateLuMp->bindValue('mp_id', $mp_id, PDO::PARAM_INT);
									$sqlUpdateLuMp->execute();

									if ($sqlUpdateLuMp->rowCount() == 0)
									{
										addLog(array("Erreur lors de la modification de l'état du MP n°".$mp_id." de non lu à lu", $sqlUpdateLuMp->errorInfo()), $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
												__FILE__, __LINE__, "admin", "error");
									}
								}
								include('includes/header.php');
								?>
								<ul class="center">
									<?php
										$countArrayLien = count($arrayLien);
										$i = 1;
										foreach ($arrayLien as $lien => $texte)
										{
											if ($lien != "")
												$action_lien = "&amp;action=".$lien;
											else
												$action_lien = "";

											if (isset($_GET['action']) && $lien != "")
											{
												if (preg_match("#".$lien."#", $_SERVER['QUERY_STRING']))
													$hover = 'class="hover"';
												else
													$hover = '';
											}
											else if ($lien == "" && !isset($_GET['action']))
												$hover = 'class="hover"';
											else
												$hover = '';

											if ($action == "consulter" && $lien == "")
												$hover = 'class="hover"';

											echo ' <li><a href="'.ROOTPATH.'?p=mp'.$action_lien.'" '.$hover.'>'.$texte.'</a>';
											if ($i != $countArrayLien)
												echo " | ";
											echo '</li>';
											$i++;
										}
									?>
								</ul>
								<?php


								// MyPrintR($InfoMessage);

								echo '<fieldset class="accepte-fight" style="border-radius: 8px">';
									echo '<legend style="padding-top: 6px;">Sujet : '.affich($InfoMessage['mp_titre']).'</legend>';
									echo '<span class="mp-info">Par <span class="gras">'.affich($InfoMessage['pseudo']).'</span> <span class="italique">'.mepd($InfoMessage['mp_time']).'</span></span><hr/>';
									echo '<p>';
										 echo parseZCode($InfoMessage['mp_text']);
									echo '</p>';
								echo '</fieldset>';

								?>
								<div class="form">
									<form method="post" action="#">
										<input type="hidden" name="title" value="<?php echo $InfoMessage['mp_titre']; ?>" />
										<?php
										includeBbCode("header");
										?>
											<textarea name="textarea" id="textarea" cols="70" rows="15" autofocus required ><?php if(!empty($_POST['textarea'])) { echo $_POST['textarea']; } ?></textarea>
										<?php
										includeBbCode("bottom");
										?>
										<input type="hidden" value="<?php echo $InfoMessage['mp_expediteur']; ?>" name="destinataire" />
										<input type="submit" value="Répondre" name="reponse" class="input-submit"/>
									</form>
								</div>
								<?php
							}
							else
							{
								addLog("Affichage d'un message privé n'appartenant pas au membre.", $_SESSION['nom_personnage'], 
										$_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
								$Session->setFlash("Ce message privé ne vous appartient.");
								header("Location: ".$_SESSION['last_url']);
								exit();
							}
						}
						else
						{
							addLog(array("Affichage d'un message privé inexistant.",
										$sqlGetInfoMessage->errorInfo()),
									$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
									__FILE__, __LINE__, "admin", "error");
							$Session->setFlash("Ce message privé n'existe pas.");
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
					}
				break;

				case "nouveau":
					if (isset($_POST['new_mp']))
					{
						if (!empty($_POST['title']) && !empty($_POST['textarea']) && $_POST['dest'] != 'none')
						{
							$titre = $_POST['title'];
							$destinataire = $_POST['dest'];
							$message = $_POST['textarea'];

							if ($destinataire != $_SESSION['membre_id'])
							{
								$sqlVerifExistMemberById = $bdd->prepare("SELECT
																			pseudo
																		  FROM
																		  	site_membres
																		  WHERE
																		  	id = :id
																		");
								$sqlVerifExistMemberById->bindValue('id', $destinataire, PDO::PARAM_INT);
								$sqlVerifExistMemberById->execute();

								if ($sqlVerifExistMemberById->rowCount() > 0)
								{
									$PseudoDest = $sqlVerifExistMemberById->fetch();
									$Liste = GetMembreMpBloqued($destinataire);
									if (!in_array($_SESSION['membre_id'], $Liste))
									{
										$sqlEnvoiMp = $bdd->prepare("INSERT INTO site_mp(
																					mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time
																				)
																	VALUES(
																			:mp_expediteur, :mp_receveur, :mp_titre, :mp_text, :mp_time
																		)");
										$sqlEnvoiMp->bindValue('mp_expediteur', $_SESSION['membre_id'], PDO::PARAM_INT);
										$sqlEnvoiMp->bindValue('mp_receveur', $destinataire, PDO::PARAM_INT);
										$sqlEnvoiMp->bindValue('mp_titre', $titre, PDO::PARAM_INT);
										$sqlEnvoiMp->bindValue('mp_text', $message, PDO::PARAM_INT);
										$sqlEnvoiMp->bindValue('mp_time', time(), PDO::PARAM_INT);
										$sqlEnvoiMp->execute();

										if ($sqlEnvoiMp->rowCount() > 0)
										{
											$Session->setFlash("Le message privé a bien été envoyé.", "success");
											header("Location: ".$_SESSION['last_url']);
											exit();
										}
										else
										{
											addLog(array("Erreur lors de l'envoie de message privé.", $sqlEnvoiMp->errorInfo()), $_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
														__FILE__, __LINE__, "admin", "error");
											$Session->setFlash("Le message privé n'a pas été envoyé");
											header("Location: ".$_SESSION['last_url']);
											exit();
										}
									}
									else
									{
										$Session->setFlash('Le membre <span class="gras">'.$PseudoDest['pseudo'].' vous a bloqué.', 'info');
										header("Location: ".$_SESSION['last_url']);
										exit();
									}
								}
								else
								{
									$Session->setFlash("Le destinataire n'existe pas.");
									header("Location: ".$_SESSION['last_url']);
									exit();
								}
							}
							else
							{
								$Session->setFlash("Vous ne pouvez pas vous envoyer de message privé.");
								header("Location: ".$_SESSION['last_url']);
								exit();
							}
						}
					}
					include('includes/header.php');
					if (isset($_GET['id']) && intval($_GET['id']))
						$destinataire = intval($_GET['id']);
					else
						$destinataire = "";
					?>
					<ul class="center">
						<?php
							$countArrayLien = count($arrayLien);
							$i = 1;
							foreach ($arrayLien as $lien => $texte)
							{
								if ($lien != "")
									$action_lien = "&amp;action=".$lien;
								else
									$action_lien = "";

								if (isset($_GET['action']) && $lien != "")
								{
									if (preg_match("#".$lien."#", $_SERVER['QUERY_STRING']))
										$hover = 'class="hover"';
									else
										$hover = '';
								}
								else if ($lien == "" && !isset($_GET['action']))
									$hover = 'class="hover"';
								else
									$hover = '';

								echo ' <li><a href="'.ROOTPATH.'?p=mp'.$action_lien.'" '.$hover.'>'.$texte.'</a>';
								if ($i != $countArrayLien)
									echo " | ";
								echo '</li>';
								$i++;
							}
						?>
					</ul>

					<div class="form">
						<form method="post" action="#">
							<!--<input type="text" name="destinataire" placeholder="Pseudo du destinataire" require class="mp_input_pseudo" /><br/>-->
							<select name="dest" id="dest">
								<option value="none">Choisir un destinataire</option>
								<?php
								$sqlListeMembre = $bdd->prepare("SELECT
																	id, pseudo
																 FROM
																	site_membres
																 WHERE
																	id != :id && id != 1
																 ORDER BY
																	pseudo
																 ASC
																");
								$sqlListeMembre->bindValue('id', $_SESSION['membre_id'], PDO::PARAM_INT);
								$sqlListeMembre->execute();
								if($sqlListeMembre->rowCount() > 0)
								{
									while($ListeMembre = $sqlListeMembre->fetch())
									{
										if($destinataire == $ListeMembre['id'])
										{
											echo '<option value="'.$ListeMembre['id'].'" selected="selected">'.$ListeMembre['pseudo'].'</option>
											';
										}
										else
										{
											echo '<option value="'.$ListeMembre['id'].'">'.$ListeMembre['pseudo'].'</option>
											';
										}
									}
								}
								else
								{
									echo '<option value="none">Aucun membre à choisir</option>';
								}
								?>
							</select><br/>
							<input style="margin-bottom: 8px;" type="text" name="title" placeholder="Sujet" required />
							<?php
							includeBbCode("header");
							?>
								<textarea name="textarea" id="textarea" cols="70" rows="15" autofocus required ><?php if(!empty($_POST['textarea'])) { echo $_POST['textarea']; } ?></textarea>
							<?php
							includeBbCode("bottom");
							?>
							<input type="submit" value="Envoyer" name="new_mp" class="input-submit"/>
						</form>
					</div>
					<?php
				break;

				case "sended":
					if (isset($_GET['id']) && intval($_GET['id']))
					{
						$mp_id = intval($_GET['id']);

						$sqlVerifExistMp = $bdd->prepare("SELECT
															COUNT(mp_id), mp_expediteur
														  FROM
														  	site_mp
														  WHERE
														  	mp_id = :mp_id
														");
						$sqlVerifExistMp->bindValue('mp_id', $mp_id, PDO::PARAM_INT);
						$sqlVerifExistMp->execute();

						$ExistMp = $sqlVerifExistMp->fetch();

						if ($sqlVerifExistMp->rowCount() > 0)
						{
							if ($ExistMp['mp_expediteur'] == $_SESSION['membre_id'])
							{
								include('includes/header.php');
								?>
								<ul class="center">
									<?php
										$countArrayLien = count($arrayLien);
										$i = 1;
										foreach ($arrayLien as $lien => $texte)
										{
											if ($lien != "")
												$action_lien = "&amp;action=".$lien;
											else
												$action_lien = "";

											if (isset($_GET['action']) && $lien != "")
											{
												if (preg_match("#".$lien."#", $_SERVER['QUERY_STRING']))
													$hover = 'class="hover"';
												else
													$hover = '';
											}
											else if ($lien == "" && !isset($_GET['action']))
												$hover = 'class="hover"';
											else
												$hover = '';

											echo ' <li><a href="'.ROOTPATH.'?p=mp'.$action_lien.'" '.$hover.'>'.$texte.'</a>';
											if ($i != $countArrayLien)
												echo " | ";
											echo '</li>';
											$i++;
										}
									?>
								</ul>
								<?php
								$sqlGetInfoMessage = $bdd->prepare("SELECT
																		mp_id, mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, pseudo, id, mp_lu
																	FROM
																		site_mp
																	INNER JOIN
																		site_membres
																	ON
																		site_membres.id = site_mp.mp_expediteur
																	WHERE
																		mp_id = :mp_id
																	");
								$sqlGetInfoMessage->bindValue('mp_id', $mp_id, PDO::PARAM_INT);
								$sqlGetInfoMessage->execute();

								$InfoMessage = $sqlGetInfoMessage->fetch();

								echo '<fieldset class="accepte-fight" style="border-radius: 8px">';
									echo '<legend style="padding-top: 6px;">Sujet : '.affich($InfoMessage['mp_titre']).'</legend>';
									echo '<span class="mp-info">Par <span class="gras">'.affich($InfoMessage['pseudo']).'</span> <span class="italique">'.mepd($InfoMessage['mp_time']).'</span></span><hr/>';
									echo '<p>';
										 echo parseZCode($InfoMessage['mp_text']);
									echo '</p>';
								echo '</fieldset>';
							}
							else
							{
								addLog("Affichage d'un mp (envoyé) n'appartient pas au membre.", "", "", __FILE__, __LINE__, "admin", "error");
								$Session->setFlash("Le message privé ne vous appartient pas.");
								header("Location: ".$_SESSION['last_url']);
								exit();
							}
						}
						else
						{
							addLog("Affichage Mp inexsitant", "", "", __FILE__, __LINE__, "admin", "error");
							$Session->setFlash("Le messagep privé n'existe pas.");
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
					}
					else
					{
						include('includes/header.php');
						if (isset($_POST['selection']) && $_POST['selection'] != 'none' && isset($_POST['mp_id']))
						{
							$action = $_POST['selection'];
							switch ($action)
							{
								case "del":
									$nbEntries = count($_POST['mp_id']);
									$i = 0;
									$mpID = "";
									foreach ($_POST['mp_id'] as $key => $value)
									{
										if (intval($value))
										{
											$mpID .= $value;
											if ($i < $nbEntries-1)
												$mpID .= ', ';
											$i++;
										}
										else
										{
											addLog("Tentative de faille ? Case 'Supprimer' défaillante : ".$value, "", "", __FILE__, __LINE__, "admin", "error");
											break;
										}
									}

									$id_membre_expediteur = $_SESSION['membre_id'];

									$sqlUpdateLuMp = $bdd->query("UPDATE
																		site_mp
																	SET
																		supp_expediteur = '1'
																	WHERE
																		mp_id IN ($mpID) && mp_expediteur = $id_membre_expediteur
																	");
									//$sqlUpdateLuMp->bindValue('mp_id', $mpID, PDO::PARAM_STR);
									//$sqlUpdateLuMp->execute();

									$rowCount = $sqlUpdateLuMp->rowCount();
									if ($rowCount > 0)
									{
										if ($rowCount > 1)
											$Session->setFlash('Les messages ont bien été supprimés.', 'success');
										else
											$Session->setFlash('Le message a bien été supprimé.', 'success');
									}
									else
										$Session->setFlash(ERR_INTERNE);
								break;
							}
						}
						?>
						<ul class="center">
							<?php
							$countArrayLien = count($arrayLien);
							$i = 1;
							foreach ($arrayLien as $lien => $texte)
							{
								if ($lien != "")
									$action_lien = "&amp;action=".$lien;
								else
									$action_lien = "";

								if (isset($_GET['action']) && $lien != "")
								{
									if (preg_match("#".$lien."#", $_SERVER['QUERY_STRING']))
										$hover = 'class="hover"';
									else
										$hover = '';
								}
								else if ($lien == "" && !isset($_GET['action']))
									$hover = 'class="hover"';
								else
									$hover = '';

								echo ' <li><a href="'.ROOTPATH.'?p=mp'.$action_lien.'" '.$hover.'>'.$texte.'</a>';
								if ($i != $countArrayLien)
									echo " | ";
								echo '</li>';
								$i++;
							}						
							?>
						</ul>

						<?php
						$sqlGetNbMp = $bdd->prepare("SELECT
														COUNT(mp_id) as NbMp
													 FROM
													 	site_mp
													 WHERE
													 	mp_expediteur = :mp_expediteur
													");
						$sqlGetNbMp->bindValue('mp_expediteur', $_SESSION['membre_id'], PDO::PARAM_INT);
						$sqlGetNbMp->execute();
						$NbMp = $sqlGetNbMp->fetch();

						$TotoMp = $NbMp['NbMp'];
						$NombreMpPage = 15;
						$NombrePage = ceil($TotoMp / $NombreMpPage);

						if(isset($_GET['page']) && $NombrePage > 0)
						{
							$page = intval($_GET['page']);
							if ($page > $NombrePage) $page = $NombrePage;
						}
						else
						{
							$page = 1;
						}
						if(isset($_GET['page']) && $_GET['page'] != 1 && $TotoMp > 1)
						{
							$i = $_GET['page'] - 1;
							if ($i > $NombrePage) $i = $NombrePage - 1;
							echo ' <a href="'.ROOTPATH.'?p=mp&amp;action=sended&amp;page='.$i.'"><button>Précédente</button></a> ';
						}
						//On affiche les pages 1-2-3, etc.
						for ($i = 1 ; $i <= $NombrePage ; $i++)
						{
							if($i == $page) //On ne met pas de lien sur la page actuelle
							{
								echo ' <span class="page_a">'.$i.'</span> |';
							}
							else
							{
								echo ' <a href="'.ROOTPATH.'?p=mp&amp;action=sended&amp;page='.$i.'"><button>'.$i.'</button></a> |';
							}
						}
						if($NombrePage != 1)
						{
							if(!isset($_GET['page']) && $NombrePage > 1 && $TotoMp > 1)
							{
								$i = +2;
								echo ' <a href="'.ROOTPATH.'?p=mp&amp;action=sended&amp;page='.$i.'"><button>Suivante</button></a>';
							}
							elseif(isset($_GET['page']) && $_GET['page'] != $NombrePage && $page < $NombrePage)
							{
								$i = $_GET['page'] + 1;
								echo ' <a href="'.ROOTPATH.'?p=mp&amp;action=sended&amp;page='.$i.'"><button>Suivante</button></a>';
							}
						}

						$FirstMp = ($page - 1) * $NombreMpPage;
						?>
						<form method="post" action="#" id="formselected">
							<table class="tableau">
								<tr class="thead">
									<th class="liste_perso"><input type="checkbox" id="all"/></th>
									<th class="liste_perso">Etat</th>
									<th class="liste_perso">Titre</th>
									<th class="liste_perso">À</th>
									<th class="liste_perso">Date</th>
								</tr>
								<?php

								$sqlListeMP = $bdd->prepare("SELECT
																mp_id, mp_receveur, mp_titre, mp_time, mp_lu,
																pseudo, id
															 FROM
															 	site_mp
															 INNER JOIN 
															 	site_membres 
															 ON 
															 	site_mp.mp_receveur = site_membres.id
															 WHERE 
															 	mp_expediteur = :id_membre && supp_expediteur = '0'
															 ORDER BY 
															 	mp_time 
															 DESC
															 LIMIT
															 	:FirstMp, :NombreMpPage
															");
								$sqlListeMP->bindValue('id_membre', $_SESSION['membre_id']);
								$sqlListeMP->bindValue('FirstMp', $FirstMp, PDO::PARAM_INT);
								$sqlListeMP->bindValue('NombreMpPage', $NombreMpPage, PDO::PARAM_INT);
								$sqlListeMP->execute();
								if($sqlListeMP->rowCount() > 0)
								{
									while($ListeMP = $sqlListeMP->fetch())
									{
										if($ListeMP['mp_lu'] == 1)
										{
											$image = ROOTPATH.'/images/16x16/no_news.png';
											$alt = "Lu";
										}
										else
										{
											$image = ROOTPATH.'/images/16x16/news.png';
											$alt = "Non lu";
										}
										?>
											<tr class="liste_perso">
												<td class="liste_perso"><input type="checkbox" name="mp_id[]" value="<?php echo $ListeMP['mp_id']; ?>" class="check" /></td>
												<td class="liste_perso"><img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>"/></td>
												<td class="liste_perso"><a href="<?php echo ROOTPATH; ?>?p=mp&amp;action=sended&amp;id=<?php echo $ListeMP['mp_id']; ?>" class="no-lien"><?php echo $ListeMP['mp_titre']; ?></a></td>
												<td class="liste_perso"><a href="<?php echo ROOTPATH; ?>?p=profil&amp;id=<?php echo $ListeMP['id']; ?>" class="no-lien"><?php echo $ListeMP['pseudo']; ?></a></td>
												<td class="liste_perso"><?php echo mepd($ListeMP['mp_time']); ?></td>
											</tr>
										<?php
									}
								}
								else
								{
								?>
									<tr class="liste_perso no_fight">
										<td class="liste_perso"colspan="5">Vous n'avez envoyé aucun message privé.</td>
									</tr>
								<?php
								}
								?>
								<tr class="liste_perso">
									<td class="no_table" colspan="5">
										<select name="selection" onChange="document.getElementById('formselected').submit();">
											<option value="none">Pour la sélection</option>
											<?php
											$arrayOption = array(
																'del' => 'Supprimer'
																);
											foreach ($arrayOption as $key => $value)
											{
												echo '<option value="'.$key.'">'.$value.'</option>';
											}
											?>
										</select>
									</td>
								</tr>
							</table>
						</form>
						<input type="button" id="invert" value="Inverser la sélection"/>
						<?php
					}
				break;

				case "bloqued":
					include('includes/header.php');
					if(isset($_POST['bloque']))
					{
						if($_POST['bloqued_id'] != 'none' && intval($_POST['bloqued_id']))
						{
							$ListeDejaBloquer = GetMembreMpBloqued($_SESSION['membre_id']);
							$id = $_POST['bloqued_id'];
							$sqlRecListe = $bdd->prepare("SELECT
															mp_bloqued
														  FROM
															site_membres
														  WHERE
															id = :id
														");
							$sqlRecListe->bindValue('id', $_SESSION['membre_id'], PDO::PARAM_INT);
							$sqlRecListe->execute();
							if($sqlRecListe->rowCount() > 0)
							{
								if(!in_array($id, $ListeDejaBloquer))
								{
									$id = ','.$id;
									
									$Liste = $sqlRecListe->fetch();
									$liste_bloqued = $Liste['mp_bloqued'].$id;
									
									$sqlBloquer = $bdd->prepare("UPDATE
																	site_membres
																 SET 
																	mp_bloqued = :liste_bloqued
																 WHERE 
																 	id = :id
																");
									$sqlBloquer->bindValue('liste_bloqued', $liste_bloqued, PDO::PARAM_STR);
									$sqlBloquer->bindValue('id', $_SESSION['membre_id'], PDO::PARAM_INT);
									$sqlBloquer->execute();
									if($sqlBloquer->rowCount() > 0)
									{
										addLog("Le membre ".$_SESSION['membre_pseudo']." a bloqué le membre id ".$liste_bloqued, "",
												"", __FILE__, __LINE__, "admin", "success");
										$Session->setFlash('Le membre a bien été bloqué.', 'success');
									}
									else
									{
										addLog(array("Erreur lors du blocage d'un membre", $sqlBloquer->errorInfo()), "", "", __FILE__,__LINE__, "admin", "error");
										$Session->setFlash(ERR_INTERNE);
									}
								}
								else
								{
									$Session->setFlash('Vous avez déjà bloqué ce membre.', 'error');
								}
							}
							else
							{
								addLog(array("Erreur de récupération des membres déjà bloqués", $sqlRecListe->errorInfo()), "", "", __FILE__,__LINE__, "admin", "error");
								$Session->setFlash(ERR_INTERNE);
							}
						}
						else
						{
							$Session->setFlash('Merci de choisir un utilisateur à bloquer.');
						}
					}
					?>
					<ul class="center">
						<?php
							$countArrayLien = count($arrayLien);
							$i = 1;
							foreach ($arrayLien as $lien => $texte)
							{
								if ($lien != "")
									$action_lien = "&amp;action=".$lien;
								else
									$action_lien = "";

								if (isset($_GET['action']) && $lien != "")
								{
									if (preg_match("#".$lien."#", $_SERVER['QUERY_STRING']))
										$hover = 'class="hover"';
									else
										$hover = '';
								}
								else if ($lien == "" && !isset($_GET['action']))
									$hover = 'class="hover"';
								else
									$hover = '';

								echo ' <li><a href="'.ROOTPATH.'?p=mp'.$action_lien.'" '.$hover.'>'.$texte.'</a>';
								if ($i != $countArrayLien)
									echo " | ";
								echo '</li>';
								$i++;
							}
						?>
					</ul>
					<section class="content-bloqued">
						<div id="liste_bloqued">
							<span class="titre">Liste des membres bloqués</span><br/>
							<?php
							$sqlListeBloqued = $bdd->prepare("SELECT
																mp_bloqued
															  FROM
																site_membres
															  WHERE
																id = :id");
							$sqlListeBloqued->bindValue('id', $_SESSION['membre_id'], PDO::PARAM_INT);
							$sqlListeBloqued->execute();
							$ListeBloqued = $sqlListeBloqued->fetch();
							if($sqlListeBloqued->rowCount() > 0 )
							{
								if($ListeBloqued['mp_bloqued'] != '')
								{
								?>
									<ul class="membre">
									<?php
									$Liste = substr($ListeBloqued['mp_bloqued'], 1);
									$Liste = explode(",", $Liste);
									foreach($Liste as $id)
									{
										$sqlMembre = $bdd->prepare("SELECT
																		id, pseudo
																	FROM
																		site_membres
																	WHERE
																		id = :id");
										$sqlMembre->bindValue('id', $id, PDO::PARAM_INT);
										$sqlMembre->execute();
										$Membre = $sqlMembre->fetch();
										echo '<li class="membre">'.$Membre['pseudo'].'<a href="'.ROOTPATH.'?p=mp&amp;action=debloquer&amp;id='.$Membre['id'].'" class="close" title="Débloquer">x</a></li>';
									}
									?>
									</ul>
								<?php
								}
								else
								{
									echo 'Vous avez bloqué personne.';
								}
							}
							elseif($sqlListeBloqued->rowCount() == 0)
							{
								addLog($sqlListeBloqued->errorInfo(), __FILE__,__LINE__, "admin", "error");
								$Session->setFlash('Une erreur interne est survenue, un rapport a été envoyé.', 'error');
							}
							?>
						</div>
						
						<div id="bloqued">
							<form method="post" action="">
								<select name="bloqued_id">
									<option value="none">Choisir une personne à bloquer</option>
									<?php
									$Liste = GetMembreMpBloqued($_SESSION['membre_id']);
									$sqlListeMembre = $bdd->prepare("SELECT
																		id, pseudo
																	  FROM 
																		site_membres
																	  WHERE
																		id != :id && id != 1
																	");
									$sqlListeMembre->bindValue('id', $_SESSION['membre_id'], PDO::PARAM_INT);
									$sqlListeMembre->execute();
									if($sqlListeMembre->rowCount() > 0)
									{
										while($ListeMembre = $sqlListeMembre->fetch())
										{
											if(!isset($Nbr_membre))
											{
												$total = $sqlListeMembre->rowCount()-1;
												$Nbr_membre = $sqlListeMembre->rowCount()-1;
											}
											if(!in_array($ListeMembre['id'], $Liste) && $ListeMembre['id'] != 1)
											{
												$Nbr_membre++;
												echo '<option value="'.$ListeMembre['id'].'">'.$ListeMembre['pseudo'].'</option>';
											}
										}
										if($Nbr_membre == $total)
										{
											echo '<option value="none">Personne à bloquer</option>';
										}
									}
									else
									{
										echo '<option value="none">Aucune personne à bloquer</option>';
									}
									?>
								</select><br/>
								<input type="submit" value="Bloquer le membre" name="bloque" />
							</form>
						</div>
					</section>
					<?php
				break;

				case "debloquer":
					if(isset($_GET['id']) && intval($_GET['id']))
					{
						$id_membre = $_GET['id'];
						$Liste = GetMembreMpBloqued($_SESSION['membre_id']);
						$Liste_bloquer = '';
						if(in_array($id_membre, $Liste))
						{
							foreach($Liste as $id)
							{
								if($id != $id_membre)
								{
									$Liste_bloquer .= ','.$id;
								}
							}
						}
						$sqlDebloquer = $bdd->prepare("UPDATE
														site_membres
													   SET
														mp_bloqued = :mp_bloqued
													   WHERE
														id = :id_membre
													");
						$sqlDebloquer->bindValue('mp_bloqued', $Liste_bloquer, PDO::PARAM_STR);
						$sqlDebloquer->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
						$sqlDebloquer->execute();
						if($sqlDebloquer->rowCount() > 0)
						{
							addLog("Le membre ".$_SESSION['membre_pseudo']." a bien débloqué le membre id ".$id_membre, "",
									"", __FILE__, __LINE__, "admin", "success");
							$Session->setFlash('Le membre a bien été débloqué.', 'success');
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
						else
						{
							addLog(array("Erreur lors du déblocage du membre id ".$id_membre, $sqlDebloquer->errorInfo()), 
										"", "", __FILE__,__LINE__, "admin", "error");
							$Session->setFlash('Une erreur interne est survenue, un rapport a été envoyé.', 'error');
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
					}
					else
					{
						header("Location: ".$_SESSION['last_url']);
					}
				break;
			}
		}
		else
		{
			header("Location: ".ROOTPATH."?p=mp");
		}
	}
	else
	{
		include('includes/header.php');
		if (isset($_POST['selection']) && $_POST['selection'] != 'none' && isset($_POST['mp_id']))
		{
			$action = $_POST['selection'];
			switch ($action)
			{
				case "del":
					$nbEntries = count($_POST['mp_id']);
					$i = 0;
					$mpID = "";
					foreach ($_POST['mp_id'] as $key => $value)
					{
						if (intval($value))
						{
							$mpID .= $value;
							if ($i < $nbEntries-1)
								$mpID .= ', ';
							$i++;
						}
						else
						{
							addLog("Tentative de faille ? Case 'Supprimer' défaillante : ".$value, "", "", __FILE__, __LINE__, "admin", "error");
							break;
						}
					}

					$id_membre_receveur = $_SESSION['membre_id'];

					$sqlUpdateLuMp = $bdd->query("UPDATE
														site_mp
													SET
														supp_receveur = '1'
													WHERE
														mp_id IN ($mpID) && mp_receveur = $id_membre_receveur
													");
					//$sqlUpdateLuMp->bindValue('mp_id', $mpID, PDO::PARAM_STR);
					//$sqlUpdateLuMp->execute();

					$rowCount = $sqlUpdateLuMp->rowCount();
					if ($rowCount > 0)
					{
						if ($rowCount > 1)
							$Session->setFlash('Les messages ont bien été supprimés.', 'success');
						else
							$Session->setFlash('Le message a bien été supprimé.', 'success');
					}
					else
						$Session->setFlash(ERR_INTERNE);
				break;

				case "lu":
					$nbEntries = count($_POST['mp_id']);
					$i = 0;
					$mpID = "";
					foreach ($_POST['mp_id'] as $key => $value)
					{
						if (intval($value))
						{
							$mpID .= $value;
							if ($i < $nbEntries-1)
								$mpID .= ', ';
							$i++;
						}
						else
						{
							addLog("Tentative de faille ? Case 'Marquer comme lu' défaillante : ".$value, "", "", __FILE__, __LINE__, "admin", "error");
							break;
						}
					}

					$sqlUpdateLuMp = $bdd->query("UPDATE
														site_mp
													SET
														mp_lu = '1'
													WHERE
														mp_id IN ($mpID)
													");
					//$sqlUpdateLuMp->bindValue('mp_id', $mpID, PDO::PARAM_STR);
					//$sqlUpdateLuMp->execute();

					$rowCount = $sqlUpdateLuMp->rowCount();
					if ($rowCount > 0)
					{
						if ($rowCount > 1)
							$Session->setFlash('Les messages ont bien été marqués comme lu', 'success');
						else
							$Session->setFlash('Le message a bien été marqué comme lu', 'success');
					}
					else
						$Session->setFlash(ERR_INTERNE);
				break;

				case "nolu":
					$nbEntries = count($_POST['mp_id']);
					$i = 0;
					$mpID = "";
					foreach ($_POST['mp_id'] as $key => $value)
					{
						if (intval($value))
						{
							$mpID .= $value;
							if ($i < $nbEntries-1)
								$mpID .= ', ';
							$i++;
						}
						else
						{
							addLog("Tentative de faille ? Case 'Marquer comme non lu' défaillante : ".$value, "", "", __FILE__, __LINE__, "admin", "error");
							break;
						}
					}

					$sqlUpdateLuMp = $bdd->query("UPDATE
														site_mp
													SET
														mp_lu = '0'
													WHERE
														mp_id IN ($mpID)
													");
					//$sqlUpdateLuMp->bindValue('mp_id', $mpID, PDO::PARAM_STR);
					//$sqlUpdateLuMp->execute();

					$rowCount = $sqlUpdateLuMp->rowCount();
					if ($rowCount > 0)
					{
						if ($rowCount > 1)
							$Session->setFlash('Les messages ont bien été marqués comme non lu', 'success');
						else
							$Session->setFlash('Le message a bien été marqué comme non lu', 'success');
					}
					else
						$Session->setFlash(ERR_INTERNE);
				break;
			}
		}
		?>
			<ul class="center">
				<?php
				$countArrayLien = count($arrayLien);
				$i = 1;
				foreach ($arrayLien as $lien => $texte)
				{
					if ($lien != "")
						$action_lien = "&amp;action=".$lien;
					else
						$action_lien = "";

					if (isset($_GET['action']) && $lien != "")
					{
						if (preg_match("#".$lien."#", $_SERVER['QUERY_STRING']))
							$hover = 'class="hover"';
						else
							$hover = '';
					}
					else if ($lien == "" && !isset($_GET['action']))
						$hover = 'class="hover"';
					else
						$hover = '';

					echo ' <li><a href="'.ROOTPATH.'?p=mp'.$action_lien.'" '.$hover.'>'.$texte.'</a>';
					if ($i != $countArrayLien)
						echo " | ";
					echo '</li>';
					$i++;
				}
				?>
			</ul>
			<?php
			$sqlGetNbMp = $bdd->prepare("SELECT
											COUNT(mp_id) as NbMp
										 FROM
										 	site_mp
										 WHERE
										 	mp_receveur = :mp_receveur
										");
			$sqlGetNbMp->bindValue('mp_receveur', $_SESSION['membre_id'], PDO::PARAM_INT);
			$sqlGetNbMp->execute();
			$NbMp = $sqlGetNbMp->fetch();

			$TotoMp = $NbMp['NbMp'];
			$NombreMpPage = 15;
			$NombrePage = ceil($TotoMp / $NombreMpPage);

			if(isset($_GET['page']) && $NombrePage > 0)
			{
				$page = intval($_GET['page']);
				if ($page > $NombrePage) $page = $NombrePage;
			}
			else
			{
				$page = 1;
			}
			if(isset($_GET['page']) && $_GET['page'] != 1 && $TotoMp > 1)
			{
				$i = $_GET['page'] - 1;
				if ($i > $NombrePage) $i = $NombrePage - 1;
				echo ' <a href="'.ROOTPATH.'?p=mp&amp;page='.$i.'"><button>Précédente</button></a> ';
			}
			//On affiche les pages 1-2-3, etc.
			for ($i = 1 ; $i <= $NombrePage ; $i++)
			{
				if($i == $page) //On ne met pas de lien sur la page actuelle
				{
					echo ' <span class="page_a">'.$i.'</span> |';
				}
				else
				{
					echo ' <a href="'.ROOTPATH.'?p=mp&amp;page='.$i.'"><button>'.$i.'</button></a> |';
				}
			}
			if($NombrePage != 1)
			{
				if(!isset($_GET['page']) && $NombrePage > 1 && $TotoMp > 1)
				{
					$i = +2;
					echo ' <a href="'.ROOTPATH.'?p=mp&amp;page='.$i.'"><button>Suivante</button></a>';
				}
				elseif(isset($_GET['page']) && $_GET['page'] != $NombrePage && $TotoMp > 1)
				{
					$i = $_GET['page'] + 1;
					echo ' <a href="'.ROOTPATH.'?p=mp&amp;page='.$i.'"><button>Suivante</button></a>';
				}
			}

			$FirstMp = ($page - 1) * $NombreMpPage;
			?>
			<form method="post" action="#" id="formselected">
				<table class="tableau">
					<tr class="thead">
						<th class="liste_perso" scope="col"><input type="checkbox" id="all"/></th>
						<th class="liste_perso" scope="col">Etat</th>
						<th class="liste_perso" scope="col">Titre</th>
						<th class="liste_perso" scope="col">Par</th>
						<th class="liste_perso" scope="col">Date</th>
					</tr>
					
					<?php
					$sqlListeMP = $bdd->prepare("SELECT
													mp_id, mp_expediteur, mp_titre, mp_time, mp_lu,
													pseudo, id
												 FROM
												 	site_mp
												 INNER JOIN 
												 	site_membres 
												 ON 
												 	site_mp.mp_expediteur = site_membres.id
												 WHERE 
												 	mp_receveur = :id_membre && supp_receveur = '0'
												 ORDER BY 
												 	mp_time 
												 DESC
												 LIMIT
												 	:FirstMp, :NombreMpPage
												");
					$sqlListeMP->bindValue('id_membre', $_SESSION['membre_id']);
					$sqlListeMP->bindValue('FirstMp', $FirstMp, PDO::PARAM_INT);
					$sqlListeMP->bindValue('NombreMpPage', $NombreMpPage, PDO::PARAM_INT);
					$sqlListeMP->execute();
					if($sqlListeMP->rowCount() > 0)
					{
						while($ListeMP = $sqlListeMP->fetch())
						{
							if($ListeMP['mp_lu'] == 1)
							{
								$image = ROOTPATH.'/images/16x16/no_news.png';
								$alt = "Lu";
							}
							else
							{
								$image = ROOTPATH.'/images/16x16/news.png';
								$alt = "Non lu";
							}
							?>
								<tr class="liste_perso">
									<td class="liste_perso"><input type="checkbox" name="mp_id[]" value="<?php echo $ListeMP['mp_id']; ?>" class="check" /></td>
									<td class="liste_perso"><img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>"/></td>
									<td class="liste_perso"><a href="<?php echo ROOTPATH; ?>?p=mp&amp;action=consulter&amp;id=<?php echo $ListeMP['mp_id']; ?>" class="no-lien"><?php echo $ListeMP['mp_titre']; ?></a></td>
									<td class="liste_perso"><a href="<?php echo ROOTPATH; ?>?p=profil&amp;id=<?php echo $ListeMP['id']; ?>" class="no-lien"><?php echo $ListeMP['pseudo']; ?></a></td>
									<td class="liste_perso"><?php echo mepd($ListeMP['mp_time']); ?></td>
								</tr>
							<?php
						}
					}
					else
					{
					?>
						<tr class="liste_perso no_fight">
							<td class="liste_perso" colspan="5">Vous n'avez reçu aucun message privé.</td>
						</tr>
					<?php
					}
					?>
					<tr class="liste_perso">
						<td class="no_table" colspan="5">
							<select name="selection" onChange="document.getElementById('formselected').submit();">
								<option value="none">Pour la sélection</option>
								<?php
								$arrayOption = array(
													'del' => 'Supprimer',
													'lu' => 'Marquer comme lu',
													'nolu' => 'Marquer comme non lu'
													);
								foreach ($arrayOption as $key => $value)
								{
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
								?>
							</select>
						</td>
					</tr>
				</table>
			</form>
			<input type="button" id="invert" value="Inverser la sélection"/>
		<?php
	}
}
?>