<?php
if (!is_co())
{
	if (isset($_POST['register']))
	{
		if (isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password_verif']) && isset($_POST['email']))
		{
			// On récupère les valeurs dans des variables et on les traites
			$pseudo = $_POST['pseudo'];
			$password = $_POST['password'];
			$password_verif = $_POST['password_verif'];
			$password_hash = HashPassword($password);
			$mail = $_POST['email'];

			include_once(ARRAY_DIR.'/array.invalidemail.php');
			include_once(FUNCTION_DIR.'/function.register.php');

			// On commence par vérifier si l'adresse mail est une vraie adresse mail
			if (VerifEmail($mail) && VerifValidateEmail($mail) && filtre_var($mail, "email"))
			{
				$sqlVerifExistDonnee = $bdd->prepare("SELECT
															COUNT(pseudo) AS Nbr_Pseudo, (SELECT
																								COUNT(mail)
																							FROM
																								site_membres
																							WHERE
																								mail = :mail) AS Nbr_Email
														FROM
															site_membres
														WHERE
															pseudo = :pseudo
														");
				$sqlVerifExistDonnee->bindValue('mail', $mail, PDO::PARAM_STR);
				$sqlVerifExistDonnee->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
				$sqlVerifExistDonnee->execute();

				$VerifExistDonnee = $sqlVerifExistDonnee->fetch();

				if (strlen($pseudo) <= 32)
				{
					if (VerifPseudo($pseudo))
					{
						if ($VerifExistDonnee['Nbr_Pseudo'] > 0)
							$erreur = "Le pseudo entré est déjà utilisé.";
						else
						{
							if ($VerifExistDonnee['Nbr_Email'] > 0)
								$erreur = "L'adresse mail est déjà utilisé.";
							else
							{
								if ($password == $password_verif)
								{
									$time = time();

									$sqlAddMember = $bdd->prepare("INSERT INTO 
																			site_membres(
																					last_refresh_fouille, pseudo, mdp, mail, inscription,
																					derniere_visite, ip
																				)
																	VALUES(
																		:last_refresh_fouille, :pseudo, :mdp, :mail, :inscription,
																		:derniere_visite, :ip
																	)
																");
									$sqlAddMember->bindValue('last_refresh_fouille', $time, PDO::PARAM_INT);
									$sqlAddMember->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
									$sqlAddMember->bindValue('mdp', $password_hash, PDO::PARAM_STR);
									$sqlAddMember->bindValue('mail', $mail, PDO::PARAM_STR);
									$sqlAddMember->bindValue('inscription', $time, PDO::PARAM_STR);
									$sqlAddMember->bindValue('derniere_visite', $time, PDO::PARAM_STR);
									$sqlAddMember->bindValue('ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
									$sqlAddMember->execute();

									if ($sqlAddMember->rowCount() > 0)
									{
										$id_membre = $bdd->lastInsertId();

										$sqlAddCharacter = $bdd->prepare("INSERT INTO
																				jeu_liste_membre_perso(
																						id_membre, id_perso
																					)
																			VALUES(
																					:id_membre, :id_perso
																				)
																		");
										$sqlAddCharacter->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
										$sqlAddCharacter->bindValue('id_perso', 1, PDO::PARAM_INT);
										$sqlAddCharacter->execute();

										if ($sqlAddCharacter->rowCount() > 0)
										{
											$sqlAddConfigMember = $bdd->prepare("INSERT INTO
																					site_membres_config(
																							id_membre
																						)
																				VALUES(
																					:id_membre
																					)
																				");
											$sqlAddConfigMember->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
											$sqlAddConfigMember->execute();

											if ($sqlAddConfigMember->rowCount() > 0)
											{
												if (MailInscription($mail, $pseudo, $password, $password_hash))
												{
													$success = "Votre inscription s'est bien déroulée. Un mail vous a été envoyé afin de valider votre inscription.";
													addLog("Le membre ".$pseudo." -> ".$id_membre." vient de s'inscrire, sous l'ip ".$_SERVER['REMOTE_ADDR'].". Le mail lui a été envoyé, et toutes les insertions également.", "", "", 
															__FILE__, __LINE__, "log", "success");
												}
												else
												{
													addLog("Le membre ".$pseudo." -> ".$id_membre." vient de s'inscrire, sous l'ip ".$_SERVER['REMOTE_ADDR']." cependant l'envoi du mail a échoué. 
															Mais toutes les insertions se sont déroulées", "", "", 
															__FILE__, __LINE__, "log", "success");
													$success = "Votre inscription s'est bien déroulée, cependant un mail aurait dû vous être envoyé. Contacter l'administrateur du site pour plus d'informations.";
												}
											}
											else
											{
												$Session->setFlash(ERR_INTERNE);
												$erreur = "Une erreur s'est produite durant votre inscription, veuillez recommencer.";

												if (DeleteMember_MemberPerso($id_membre))
												{
													addLog(array("Une erreur vient de se produire lors d'une inscription. Insertion de donnée dans la table site_membres_config échoué.<br/>
																	L'insertion précédente du membre ".$id_membre." sur la table site_membres & jeu_liste_membre_perso ont été supprimées.",
															$sqlAddConfigMember->errorInfo()),
														"", "", __FILE__, __LINE__, "admin", "error");
												}
												else
												{
													addLog(array("Une erreur vient de se produire lors d'une inscription. Insertion de donnée dans la table site_membres_config échoué.<br/>
																	L'insertion précédente du membre ".$id_membre." sur la table site_membres & jeu_liste_membre_perso n'ont pas été supprimées.",
															$sqlAddConfigMember->errorInfo()),
														"", "", __FILE__, __LINE__, "admin", "error");
												}
											}
										}
										else
										{
											$Session->setFlash(ERR_INTERNE);
											$erreur = "Une erreur s'est produite durant votre inscription, veuillez recommencer.";

											if (DeleteMember($id_membre))
											{
												addLog(array("Une erreur vient de se produire lors d'une inscription. Insertion de donnée dans la table jeu_liste_membre_perso échoué.<br/>
																L'insertion précédente du membre ".$id_membre." sur la table site_membres a été supprimées.",
														$sqlAddCharacter->errorInfo()),
													"", "", __FILE__, __LINE__, "admin", "error");
											}
											else
											{
												addLog(array("Une erreur vient de se produire lors d'une inscription. Insertion de donnée dans la table jeu_liste_membre_perso échoué.<br/>
																L'insertion précédente du membre ".$id_membre." sur la table site_membres n'a pas été supprimées.",
														$sqlAddCharacter->errorInfo()),
													"", "", __FILE__, __LINE__, "admin", "error");
											}
										}
									}
									else
									{
										$Session->setFlash(ERR_INTERNE);
										$erreur = "Une erreur s'est produite durant votre inscription, veuillez recommencer.";

										addLog(array("Une erreur vient de se produire lors d'une inscription. Insertion de donnée dans la table site_membres échouée.", $sqlAddMember->errorInfo()),
												"", "", __FILE__, __LINE__, "admin", "error");
									}
								}
								else
								{
									$erreur = "Les mots de passes sont différents.";
								}
							}
						}
					}
					else
					{
						$erreur = "Le pseudo ne peut contenir des caractères spéciaux (<>;/)";
					}
				}
				else
				{
					$erreur = "Le pseudo ne doit pas dépasser 32 caractères.";
				}
			}
			else
			{
				if (!VerifValidateEmail($mail))
					$erreur = "L'adresse email n'est pas valide.";
				else
					$erreur = "L'adresse mail a été signalé comme temporaire, merci d'utiliser une vraie adresse mail.";
			}
		}
	}
	else
	{
		echo 'form not submit';
	}
}
else
{
	header("Location: ".ROOTPATH);
}
?>