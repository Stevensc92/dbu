<?php
if (is_co())
{
	if (isset($_GET['view']) && $_GET['view'] == 'topic')
	{
		if (isset($_GET['id']) && intval($_GET['id']))
		{
			$topic_id = intval($_GET['id']);

			$sqlGetExistTopic = $bdd->prepare("SELECT
													COUNT(*) as Exist, topic_suppr
												FROM
													forum_topic
												WHERE
													topic_id = :topic_id
												");
			$sqlGetExistTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
			$sqlGetExistTopic->execute();

			$ExistTopic = $sqlGetExistTopic->fetch(PDO::FETCH_OBJ);
			
			if ($ExistTopic->Exist > 0 && $ExistTopic->topic_suppr == 0)
			{
				$sqlGetAuthView = $bdd->prepare("SELECT
													forum_auth_view
												 FROM
												 	forum_forum
												 WHERE
												 	forum_id = (SELECT
												 					topic_forum_id
												 				FROM
												 					forum_topic
												 				WHERE
												 					topic_id = :topic_id
												 				)
												");
				$sqlGetAuthView->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
				$sqlGetAuthView->execute();

				$AuthView = $sqlGetAuthView->fetch(PDO::FETCH_OBJ);

				if (verifAuth($AuthView->forum_auth_view))
				{
					$sqlGetInfoTopic = $bdd->prepare("SELECT
														topic_titre, topic_genre, topic_locked, topic_resolved,
														forum_nom, forum_id, forum_auth_post, forum_auth_topic, forum_auth_annonce, forum_auth_modo
													 FROM
													 	forum_topic
													 LEFT JOIN
													 	forum_forum
													 ON
													 	forum_forum.forum_id = forum_topic.topic_forum_id
													 WHERE
													 	topic_id = :topic_id
													");
					$sqlGetInfoTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
					$sqlGetInfoTopic->execute();

					$InfoTopic = $sqlGetInfoTopic->fetch(PDO::FETCH_OBJ);


					/*
					 *
					 * Répondre
					 *
					 */

					if (isset($_POST['message-send']))
					{
						if (isset($_POST['__token']) && isset($_POST['message']))
						{
							if (check_Token($_POST['__token'])) // on vérifie la validité du token
							{
								if (trim($_POST['message']) != "") // on retire les espaces en début et fin de chaîne et vérifions s'il n'est pas vide
								{
									if (verifAuth($InfoTopic->forum_auth_post)) // Si le membre a le droit de posté
									{
										//On commence à faire toutes les requêtes nécessaire à l'ajout du message

										// On ajoute le message dans le topic 

										$sqlAddMessage = $bdd->prepare("INSERT INTO
																			forum_post(
																				post_id_createur, post_texte, post_time, post_topic_id, post_forum_id
																			)
																		VALUES(
																			:post_id_createur, :post_texte, :post_time, :post_topic_id, :post_forum_id
																		)
																		");
										$sqlAddMessage->bindValue('post_id_createur', $_SESSION['membre_id'], PDO::PARAM_INT);
										$sqlAddMessage->bindValue('post_texte', $_POST['message'], PDO::PARAM_STR);
										$sqlAddMessage->bindValue('post_time', time(), PDO::PARAM_INT);
										$sqlAddMessage->bindValue('post_topic_id', $topic_id, PDO::PARAM_INT);
										$sqlAddMessage->bindValue('post_forum_id', $InfoTopic->forum_id, PDO::PARAM_INT);
										$sqlAddMessage->execute();

										$nouveauPost = $bdd->lastInsertId();

										if ($sqlAddMessage->rowCount() > 0) // Si le message s'est bien ajouté
										{
											// On ajoute maintenant +1 au compteur du nombre de message du membre
											$sqlUpdateNbPostMember = $bdd->prepare("UPDATE
																						site_membres
																					SET
																						nb_post = nb_post + 1
																					WHERE
																						id = :id_membre
																					");
											$sqlUpdateNbPostMember->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
											$sqlUpdateNbPostMember->execute();


											if ($sqlUpdateNbPostMember->rowCount() > 0) // Si l'update du nb de post du membre a réussi
											{
												// On modifie les données du topic
												$sqlUpdateTopic = $bdd->prepare("UPDATE
																					forum_topic
																				 SET
																				 	topic_nb_message = topic_nb_message + 1, topic_last_post_id = :topic_last_post_id
																				 WHERE
																				 	topic_id = :topic_id && topic_forum_id = :topic_forum_id
																				 ");
												$sqlUpdateTopic->bindValue('topic_last_post_id', $nouveauPost, PDO::PARAM_INT);
												$sqlUpdateTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
												$sqlUpdateTopic->bindValue('topic_forum_id', $InfoTopic->forum_id, PDO::PARAM_INT);
												$sqlUpdateTopic->execute();

												if ($sqlUpdateTopic->rowCount() > 0) // Si la modification du topic à réussi
												{
													// On modifie les données du forum
													$sqlUpdateForum = $bdd->prepare("UPDATE
																						forum_forum
																					 SET
																					 	forum_last_post_id = :last_post_id, forum_nb_post = forum_nb_post + 1
																					 WHERE
																					 	forum_id = :forum_id
																					");
													$sqlUpdateForum->bindValue('last_post_id', $nouveauPost, PDO::PARAM_INT);
													$sqlUpdateForum->bindValue('forum_id', $InfoTopic->forum_id, PDO::PARAM_INT);
													$sqlUpdateForum->execute();

													if ($sqlUpdateForum->rowCount() > 0) // Si la modification du forum a réussi
													{
														addLog("Un message a été ajouté dans le topic $topic_titre [$topic_id].", "", "", __FILE__, __LINE__, "log", "success");
														$Session->setFlash("Le message a bien été ajouté.", 'success');
														header("Location: ".$_SESSION['last_url']);
														exit();
														// ON METTRA EN LIGNE UNE FOIS LE SYSTÈME GÉRER
														// On modifie les données de topic_view (système lu/non-lu)
														/*
														$sqlUpdateView = $bdd->prepare("UPDATE
																							forum_topic_view
																						SET
																							tv_post_id = :nouveauPost, tv_poste = '1'
																						WHERE
																							tv_id_membre = :id_membre && tv_topic_id = :topic_id
																						");
														$sqlUpdateView->bindValue('nouveauPost', $nouveauPost, PDO::PARAM_INT);
														$sqlUpdateView->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
														$sqlUpdateView->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
														$sqlUpdateView->execute();

														if ($sqlUpdateView->rowCount() > 0) // Si la modification de view a réussi
														{
															addLog("Un message a été ajouté dans le topic $topic_titre [$topic_id].", "", "", __FILE__, __LINE__, "log", "success");
															$Session->setFlash("Le message a bien été ajouté.", 'success');
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si modification view réussi // L:157
														else
														{
															addLog("Une erreur s'est produite lors de la modification des données de la table forum_topic_view pour le topic[$topic_id].", "", "",
																	__FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														}*/
													} // Fin si modification forum réussi // L:140
													else
													{
														addLog("Une erreur s'est produite lors de la modification des données de la table forum_forum pour le forum [".$InfoTopic->forum_id."]", "", "",
																__FILE__, __LINE__, "admin", "error");
														$Session->setFlash(ERR_INTERNE);
														header("Location: ".$_SESSION['last_url']);
														exit();
													}
												} // Fin si modification topic réussi // L:127
												else
												{
													addLog("Une erreur s'est produite lors de la modification des données de la table forum_topic pour le topic_id [$topic_id]", "", "",
															__FILE__, __LINE__, "admin", "error");
													$Session->setFlash(ERR_INTERNE);
													header("Location: ".$_SESSION['last_url']);
													exit();
												}
											} // fin si update nb post réussi // L:108
											else
											{
												$Session->setFlash(ERR_INTERNE);
												addLog("Une erreur s'est passé lors de l'édition du nombre de post (+1).", "", "", __FILE__, __LINE__, "admin", "error");
												header("Location: ".$_SESSION['last_url']);
												exit();
											}
										} // Fin si ajout de message a réussi // L:95
										else
										{
											$Session->setFlash(ERR_INTERNE);
											$_SESSION['form_mess'] = $_POST['message'];
											addLog("Une erreur s'est passé durant l'insertion de message dans le topic [$topic_id]", "", "", __FILE__, __LINE__, "admin", "error");
											header("Location: ".$_SESSION['last_url']);
											exit();
										}
									} // Fin vérification si membre à droit de poster
									else
									{
										addLog("Droit de poster dans le topic [$topic_id] impossible.", "", "", __FILE__, __LINE__, "admin", "error");
										$Session->setFlash("Vous n'avez pas le droit de poster dans ce topic.");
										header("Location: ".$_SESSION['last_url']);
										exit();
									}
								} // fin vérification si message n'est pas vide // L:74
								else
								{
									$Session->setFlash("Votre message ne peut être vide.");
									header("Location: ".$_SESSION['last_url']);
									exit();
								}
							} // fin si token est bon
							else
							{
								$Session->setFlash(ERR_CSRF);
								up_Token(0);
								addLog(ERR_LOG_CSRF, "", "", __FILE__, __LINE__, "admin", "error");
								header("Location: ".$_SESSION['last_url']);
								exit();
							}
						} // Fin si champ token et message existent // L:70
						else
						{
							$Session->setFlash(ERR_FORM);
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
					} // Fin si formulaire pour répondre est soumis // L:68

					/*
					 *
					 * ACTIONS MODÉRATION
					 *
					 */
						if (isset($_POST['actions']) && verifAuth($InfoTopic->forum_auth_modo))
						{
							switch ($_POST['actions'])
							{
								case "lock":
									$sqlLockTopic = $bdd->prepare("UPDATE
																		forum_topic
																	SET
																		topic_locked = '1'
																	WHERE
																		topic_id = :topic_id
																	");
									$sqlLockTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
									$sqlLockTopic->execute();

									if ($sqlLockTopic->rowCount() > 0)
									{
										$log = array("Le topic '".$InfoTopic->topic_titre." id ".$topic_id."' a bien été vérouillé", "success");
										$flash = array("Le topic a bien été vérouillé", "success");
									}
									else
									{
										$log = array("Le topic '".$InfoTopic->topic_titre." id ".$topic_id."' n'a pas été vérouillé.", "error");
										$flash = array("Le topic n'a pas été vérouillé", "error");
									}
								break;

								case "unlock":
									$sqlUnLockTopic = $bdd->prepare("UPDATE
																		forum_topic
																	SET
																		topic_locked = '0'
																	WHERE
																		topic_id = :topic_id
																	");
									$sqlUnLockTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
									$sqlUnLockTopic->execute();

									if ($sqlUnLockTopic->rowCount() > 0)
									{
										$log = array("Le topic '".$InfoTopic->topic_titre."' id '".$topic_id."' a bien été dévérouillé.", "success");
										$flash = array("Le topic a bien été dévérouillé", "success");
									}
									else
									{
										$log = array("Le topic '".$InfoTopic->topic_titre."' id '".$topic_id."' n'a pas été dévérouillé.", "error");
										$flash = array("Le topic n'a pas été dévérouillé.", "error");
									}
								break;

								case "resolve":
									$sqlResolveTopic = $bdd->prepare("UPDATE
																		forum_topic
																	  SET
																	  	topic_resolved = '1'
																	  WHERE
																	  	topic_id = :topic_id
																	");
									$sqlResolveTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
									$sqlResolveTopic->execute();

									if ($sqlResolveTopic->rowCount() > 0)
									{
										$log = array("Le topic '".$InfoTopic->topic_titre."' id '".$topic_id."' a bien été mis en résolu.", "success");
										$flash = array("Le topic a bien été mis en résolu.", "success");
									}
									else
									{
										$log = array("Le topic '".$InfoTopic->topic_titre."' id '".$topic_id."' n'a pas été mis en résolu", "error");
										$flash = array("Le topic n'a pas été mis en résolu.", "error");
									}
								break;

								case "unresolve":
									$sqlUnResolveTopic = $bdd->prepare("UPDATE
																			forum_topic
																		SET
																			topic_resolved = '0'
																		WHERE
																			topic_id = :topic_id
																		");
									$sqlUnResolveTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
									$sqlUnResolveTopic->execute();

									if ($sqlUnResolveTopic->rowCount() > 0)
									{
										$log = array("Le topic '".$InfoTopic->topic_titre."' id '".$topic_id."' n'est plus mis en résolu.", "success");
										$flash = array("Le topic n'est plus mis en résolu", "success");
									}
									else
									{
										$log = array("Le topic '".$InfoTopic->topic_titre."' id '".$topic_id."' n'a pas été retiré des topics résolus.", "error");
										$flash = array("Le topic n'a pas été rétiré des topics résolu.");
									}
								break;
							}

							addLog($log[0], "", "", __FILE__, __LINE__, "admin", $log[1]);
							$Session->setFlash($flash[0], $flash[1]);
							header("Location: ".$_SESSION['last_url']);
							exit();
						}
					
						/*
						 *
						 * Gestion de suppression des messages
						 *
						 */

						if (isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['message'])) // Si toutes les méthodes GET existent et sont correctes
						{
							if (intval($_GET['message'])) // Si la valeur de $_GET['message'] est bien un chiffre 
							{
								// On vérifie d'abord si le membre a le droit de supprimer le message
								if (verifAuth($InfoTopic->forum_auth_modo))
								{
									// Puis on vérifie la validité du token et si le token existe dans l'url
									if (isset($_GET['token']) && check_Token($_GET['token']))
									{
										$message_id = $_GET['message'];
										// On vérifie si le message existe
										$sqlVerifExistMessage = $bdd->prepare("SELECT
																					post_id, post_suppr, forum_topic.topic_first_post_id, forum_topic.topic_last_post_id, post_id_createur
																				FROM
																					forum_post
																				LEFT JOIN
																					forum_topic
																				ON
																					forum_topic.topic_id = :topic_id
																				WHERE
																					post_id = :post_id
																				");
										$sqlVerifExistMessage->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
										$sqlVerifExistMessage->bindValue('post_id', $message_id, PDO::PARAM_INT);
										$sqlVerifExistMessage->execute();

										$ExistMessage = $sqlVerifExistMessage->fetch();
										
										if ($sqlVerifExistMessage->rowCount() > 0) // Si le message existe
										{
											if ($ExistMessage['post_suppr'] != 1) // Si le message n'est pas déjà supprimé on le supprime
											{
												// On vérifie si le message est le premier ou dernier du topic
												if ($ExistMessage['topic_first_post_id'] == $message_id) // On vérifie si le message est le premier du topic
												{
													$Session->setFlash('test', "success", true);
													header("Location: ".$_SESSION['last_url']);
													exit();
												} // Fin si message premier topic // L:394
												else if ($ExistMessage['topic_last_post_id'] == $message_id) // si le message est le dernier du topic
												{
													// On commence par supprimer le post.
													$sqlDeleteMess = $bdd->prepare("UPDATE
																						forum_post
																					SET
																						post_suppr = '1'
																					WHERE
																						post_id = :post_id
																					");
													$sqlDeleteMess->bindValue('post_id', $message_id, PDO::PARAM_INT);
													$sqlDeleteMess->execute();

													if ($sqlDeleteMess->rowCount() == 0) // Si la requête del mess a échoué
													{
														addLog("Erreur lors de la suppression du message [$message_id].", "", "", __FILE__, __LINE__, "admin", "error");
														$Session->setFlash(ERR_INTERNE);
														header("Location: ".$_SESSION['last_url']);
														exit();
													} // Fin si requête del mess échoué // L:411
													else
													{
														// On va modifier le dernier message du topic, en récupérant l'id le plus récent du topic
														$sqlGetRecentPostId = $bdd->prepare("SELECT
																								post_id
																							 FROM
																							 	forum_post
																							 WHERE
																							 	post_topic_id = :topic_id && post_suppr = '0'
																							 ORDER BY
																							 	post_id
																							 DESC
																							 LIMIT 0,1
																							");
														$sqlGetRecentPostId->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
														$sqlGetRecentPostId->execute();

														if ($sqlGetRecentPostId->rowCount() == 0) // Si requête  get recent_post id topic a échoué
														{
															addLog("Erreur lors de l'obtention du dernier post_id pour viewtopic.", "", "", __FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si requête get recent post_id échoué

														$RecentPostIdTopic = $sqlGetRecentPostId->fetch();

														// On fait pareil donc pour l'affichage du topic dans la page viewforum
														$sqlGetRecentPostIdForForum = $bdd->prepare("SELECT
																										post_id
																									 FROM
																									 	forum_post
																									 WHERE
																									 	post_forum_id = :forum_id && post_suppr = '0'
																									 ORDER BY
																									 	post_id
																									 DESC
																									 LIMIT 0,1
																									");
														$sqlGetRecentPostIdForForum->bindValue('forum_id', $InfoTopic->forum_id, PDO::PARAM_INT);
														$sqlGetRecentPostIdForForum->execute();

														if ($sqlGetRecentPostIdForForum->rowCount() == 0) // Si requête get recent_post id forum a échoué
														{
															addLog("Erreur lors de l'obtention du dernier post_id pour viewforum.", "", "", __FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si requête get recent post_id forum échoué

														$RecentPostIdForum = $sqlGetRecentPostIdForForum->fetch();

														// On update le topic last post et - 1 au nb de message
														$sqlUpdateTopicLastPost = $bdd->prepare("UPDATE
																									forum_topic
																								 SET
																								 	topic_last_post_id = :new_topic_last_post_id, topic_nb_message = topic_nb_message - 1
																								 WHERE
																								 	topic_id = :topic_id
																								 ");
														$sqlUpdateTopicLastPost->bindValue('new_topic_last_post_id', $RecentPostIdTopic['post_id'], PDO::PARAM_INT);
														$sqlUpdateTopicLastPost->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
														$sqlUpdateTopicLastPost->execute();

														if ($sqlUpdateTopicLastPost->rowCount() == 0) // Si requête up forum topic last post id a échoué
														{
															addLog("Erreur lors de l'édition du topic_last_post_id pour le topic[$topic_id]", "", "", __FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si requête up forum topic last post échoué

														$sqlUpdateForumLastPost = $bdd->prepare("UPDATE
																									forum_forum
																								 SET
																								 	forum_last_post_id = :new_forum_last_post_id, forum_nb_post = forum_nb_post - 1
																								 WHERE
																								 	forum_id = :forum_id
																								");
														$sqlUpdateForumLastPost->bindValue('new_forum_last_post_id', $RecentPostIdForum['post_id'], PDO::PARAM_INT);
														$sqlUpdateForumLastPost->bindValue('forum_id', $InfoTopic->forum_id, PDO::PARAM_INT);
														$sqlUpdateForumLastPost->execute();

														if ($sqlUpdateForumLastPost->rowCount() == 0) // Si requête update forum last_post && -1 échoué
														{
															addLog("Erreur lors de l'édition du forum_last_post_id pour le forum[".$InfoTopic->forum_id."]", "", "", __FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si requête update forum last_post && -1 échoué

														// On enlève -1 au nb de message du membre

														$sqlUpdateNbPostMember = $bdd->prepare("UPDATE
																									site_membres
																								SET
																									nb_post = nb_post - 1
																								WHERE
																									id = :id_membre
																								");
														$sqlUpdateNbPostMember->bindValue('id_membre', $ExistMessage['post_id_createur'], PDO::PARAM_INT);
														$sqlUpdateNbPostMember->execute();

														if ($sqlUpdateNbPostMember->rowCount() == 0) // Si requête up nb post membre a échoué
														{
															addLog("Erreur lors de l'édition du nb_post pour le membre {$ExistMessage['post_id_createur']}.", "", "", __FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si requête up nb post membre a échoué

														addLog("Le dernier message du topic [$topic_id] a bien été supprimé. Toutes les modifications ont bien été réussie.", "", "", __FILE__, __LINE__,
																"admin", "error");
														$Session->setFlash("Le message a bien été supprimé", "success");
														header("Location: ".$_SESSION['last_url']);
														exit();
													}
												} // Fin si message dernier topic // L:396
												else // sinon message normal
												{
													// On commence par supprimer le post.
													$sqlDeleteMess = $bdd->prepare("UPDATE
																						forum_post
																					SET
																						post_suppr = '1'
																					WHERE
																						post_id = :post_id
																					");
													$sqlDeleteMess->bindValue('post_id', $message_id, PDO::PARAM_INT);
													$sqlDeleteMess->execute();

													if ($sqlDeleteMess->rowCount() == 0) // Si la requête del mess a échoué
													{
														addLog("Erreur lors de la suppression du message [$message_id].", "", "", __FILE__, __LINE__, "admin", "error");
														$Session->setFlash(ERR_INTERNE);
														header("Location: ".$_SESSION['last_url']);
														exit();
													} // Fin si requête del mess échoué // L:414
													else
													{
														// On retire 1 au nombre de post du membre
														$sqlUpdateNbPostMember = $bdd->prepare("UPDATE
																									site_membres
																								SET
																									nb_post = nb_post - 1
																								WHERE
																									id = :id_membre
																								");
														$sqlUpdateNbPostMember->bindValue('id_membre', $ExistMessage['post_id_createur'], PDO::PARAM_INT);
														$sqlUpdateNbPostMember->execute();

														if ($sqlUpdateNbPostMember->rowCount() == 0) // Si la requête up nb post à échoué
														{
															addLog("Erreur lors de l'édition du nombre de post du membre[".$ExistMessage['post_id_createur']."]", "", "", __FILE__, __LINE__, "admin", "error");
															$Session->setFlash(ERR_INTERNE);
															header("Location: ".$_SESSION['last_url']);
															exit();
														} // Fin si requête up nb post échoué // L:434
														else
														{
															// On retire 1 au nombre de post du topic
															$sqlUpdateNbPostTopic = $bdd->prepare("UPDATE
																										forum_topic
																									SET
																										topic_nb_message = topic_nb_message - 1
																									WHERE
																										topic_id = :topic_id
																									");
															$sqlUpdateNbPostTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
															$sqlUpdateNbPostTopic->execute();

															if ($sqlUpdateNbPostTopic->rowCount() == 0) // Si la requête up nb post topic a échoué
															{
																addLog("Erreur lors de l'édition du nombre de post du topic[$topic_id].", "", "", __FILE__, __LINE__, "admin", "error");
																$Session->setFlash(ERR_INTERNE);
																header("Location: ".$_SESSION['last_url']);
																exit();
															} // Fin si requête up nb post topic échoué // L:454
															else
															{
																// On retire 1 au nombre de post du forum
																$sqlUpdateNbPostForum = $bdd->prepare("UPDATE
																											forum_forum
																										SET
																											forum_nb_post = forum_nb_post - 1
																										WHERE
																											forum_id = :forum_id
																										");
																$sqlUpdateNbPostForum->bindValue('forum_id', $InfoTopic->forum_id, PDO::PARAM_INT);
																$sqlUpdateNbPostForum->execute();

																if ($sqlUpdateNbPostForum->rowCount() == 0) // Si la requête up nb post forum a échoué
																{
																	addLog("Erreur lors de l'édition du nombre de post du forum[.".$InfoTopic->forum_id."]", "", "", __FILE__, __LINE__, "admin", "error");
																	$Session->setFlash(ERR_INTERNE);
																	header("Location: ".$_SESSION['last_url']);
																	exit();
																} // Fin si requête up nb post forum échoué // L:474
																else
																{
																	addLog("Le message de ".getPseudoMembre($ExistMessage['post_id_createur'])." a bien été supprimé du topic[$topic_id].", "", "",
																			__FILE__, __LINE__, "admin", "success");
																	$Session->setFlash("Le message a bien été supprimé.", "success");
																	header("Location: ".$_SESSION['last_url']);
																	exit();
																}
															}
														}
													}
												} // Fin si message normal // L:402
											} // Fin si message non supprimé // L:391
										} // Fin si message existe // L:389
										else
										{
											addLog("Suppression d'un message non existant", "", "", __FILE__, __LINE__, "admin", "error");
											$Session->setFlash("Le message n'existe pas.");
											header("Location: ".$_SESSION['last_url']);
											exit();
										}
									}
									else
									{
										addLog("Jeton CSRF invalide. Suppression message.", "", "", __FILE__, __LINE__, "admin", "error");
										$Session->setFlash("Le jeton CSRF est invalide.");
										up_Token(0);
										header("Location: ".$_SESSION['last_url']);
										exit();
									}
								}
								else
								{
									addLog("Actions : Supprimer message; impossible.", "", "", __FILE__, __LINE__, "admin", "error");
								}
							}
							else
							{
								addLog(array($_GET, "Tentative de corruption de la fonction supprimer les messages."), "", "", __FILE__, __LINE__, "admin", "error");
							}
						}


						/*
						 *
						 * Gestion de modification des messages 
						 *
						 */

					
						if (isset($_POST['upMessage']))
						{
							if (isset($_POST['idPost']) && intval($_POST['idPost']) && isset($_POST['__token']))
							{
								if (check_Token($_POST['__token'])) // Si le token est bon
								{
									$post_id = intval($_POST['idPost']);

									$sqlGetPosteurMessage = $bdd->prepare("SELECT
																				post_id_createur, post_texte
																			FROM
																				forum_post
																			WHERE
																				post_id = :post_id
																			");
									$sqlGetPosteurMessage->bindValue('post_id', $post_id, PDO::PARAM_INT);
									$sqlGetPosteurMessage->execute();

									$PosteurMessage = $sqlGetPosteurMessage->fetch();
									if ($sqlGetPosteurMessage->rowCount() > 0) // On vérifie si le message existe
									{
										// Si le membre a le droit de modifier le message ou que le membre modifie son propre message
										if ($_SESSION['membre_id'] == $PosteurMessage['post_id_createur'] || verifAuth($InfoTopic->forum_auth_modo)) 
										{
											$sqlUpMessage = $bdd->prepare("UPDATE
																				forum_post
																			SET
																				post_texte = :new_post
																			WHERE
																				post_id = :post_id
																			");
											$sqlUpMessage->bindValue('new_post', $_POST['message'], PDO::PARAM_STR);
											$sqlUpMessage->bindValue('post_id', $_POST['idPost'], PDO::PARAM_INT);
											$sqlUpMessage->execute();

											if ($sqlUpMessage->rowCount() > 0) // Si le message a bien été modifié
											{
												$sqlUpInfoEdit = $bdd->prepare("UPDATE
																					forum_post
																				SET
																					post_edit = '1', post_edit_time = :time_edit, post_edit_id_membre = :id_membre
																				WHERE
																					post_id = :post_id
																				");
												$sqlUpInfoEdit->bindValue('time_edit', time(), PDO::PARAM_INT);
												$sqlUpInfoEdit->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
												$sqlUpInfoEdit->bindValue('post_id', $post_id, PDO::PARAM_INT);
												$sqlUpInfoEdit->execute();

												if ($sqlUpInfoEdit->rowCount() > 0) // Si les infos d'édition ont bien été faite
												{
													$Session->setFlash("Le message a bien été édité !", "success");
													addLog("Le message de ".getPseudoMembre($PosteurMessage['post_id_createur'])." a bien été édité par ".getPseudoMembre($_SESSION['membre_id']),
															"", "", __FILE__, __LINE__, "admin", "error");
													header("Location: ".$_SESSION['last_url']);
													exit();
												} // Fin si info édition message réussie \\ L:302
												else
												{
													$sqlReturnMessageNormal = $bdd->prepare("UPDATE
																								forum_post
																							 SET
																							 	post_texte = :last_mess_post
																							 WHERE
																							 	post_id = :post_id
																							");
													$sqlReturnMessageNormal->bindValue('last_mess_post', $PosteurMessage['post_texte'], PDO::PARAM_STR);
													$sqlReturnMessageNormal->bindValue('post_id', $post_id, PDO::PARAM_INT);
													$sqlReturnMessageNormal->execute();

													if ($sqlReturnMessageNormal->rowCount() > 0) // Si le message est bien revenue à la normale
													{
														$Session->setFlash(ERR_INTERNE);
														addLog("Une erreur est survenue durant la modification d'un message ($post_id) sur les informations de l'édition.<br/>
																Le message a été réinitialisé.", "", "", __FILE__, __LINE__, "admin", "error");
														header("Location: ".$_SESSION['last_url']);
														exit();
													} // Fin si message revenue à la normale \\ L:323
													else
													{
														$Session->setFlash(ERR_INTERNE);
														addLog("Une erreur est survenue durant la modification d'un message ($post_id) sur les informations de l'édition, et la réinitialisation du message a échoué.",
																"", "", __FILE__, __LINE__, "admin", "error");
														header("Location: ".$_SESSION['last_url']);
														exit();
													}
												}
											} // Fin si message a bien été modifié \\ L:288
											else
											{
												addLog("Une erreur est survenue lors de l'édition du message ($post_id).", "", "", __FILE__, __LINE__, "admin", "error");
												$Session->setFlash(ERR_INTERNE);
												header("Location: ".$_SESSION['last_url']);
												exit();
											}
										} // Fin vérification si membre permission, ou propre message \\ L:275
										else
										{
											addLog("Édition de message interdite. Le message a éditer n'appartient pas au membre tentant l'édition.", "", "", __FILE__, __LINE__, "admin", "error");
											$Session->setFlash("Ce n'est pas votre message ou vous n'avez pas le droit de modifier le message d'un autre membre.");
											header("Location: ".$_SESSION['last_url']);
											exit();
										}
									} // Fin vérification si message existe \\ L:272
									else
									{
										addLog("Tentative de modification de message n'existant pas.", "", "", __FILE__, __LINE__, "admin", "error");
										$Session->setFlash("Le message n'existe pas.");
										header("Location: ".$_SESSION['last_url']);
										exit();
									}
								} // Fin "si token est bon" \\ L:257
								else
								{
									addlog(ERR_LOG_CSRF, "", "", __FILE__, __LINE__, "admin", "error");
									$Session->setFlash(ERR_CSRF);
									header("Location: ".$_SESSION['last_url']);
									exit();
								}
							}
						}

					/*
					 *
					 * FIN ACTIONS MODÉRATION
					 *
					 */

					$titre = $InfoTopic->topic_titre;
					include('../includes/header.php');


					?>
					<ul class="center">
						<li><a href="<?php echo FORUM; ?>">Index - </a></li>
						<li><a href="<?php echo FORUM; ?>?view=forum&amp;id=<?php echo $InfoTopic->forum_id; ?>"><?php echo afficherVarBdd($InfoTopic->forum_nom); ?> - </a></li>
						<li><a href="<?php echo FORUM; ?>?view=topic&amp;id=<?php echo $topic_id; ?>" class="hover"><?php echo afficherVarBdd($InfoTopic->topic_titre); ?></a></li>
					</ul>
					<?php
					$sqlGetMessageTopic = $bdd->prepare("SELECT
															post_id, post_texte, post_time, post_edit, post_edit_time, post_edit_id_membre, post_suppr, post_id_createur,
															pseudo, inscription, avatar, signature, rang, nb_post
														 FROM
														 	forum_post
														 LEFT JOIN
														  	site_membres
														 ON
														 	site_membres.id = forum_post.post_id_createur
														 WHERE
														 	post_topic_id = :topic_id
														 ORDER BY
														 	post_id
														 ");
					$sqlGetMessageTopic->bindValue('topic_id', $topic_id, PDO::PARAM_INT);
					$sqlGetMessageTopic->execute();

					?>
					<?php
					if ($InfoTopic->topic_resolved == 1)
					{
					?>
						<div id="resolve-topic">
							<p class="text-resolve"><img src="<?php echo ROOTPATH; ?>images/forum/resolu.png" alt="resolu"/><span class="txt">Ce topic est résolu</span></p>
						</div>
					<?php
					}

					if ($InfoTopic->topic_locked == 1)
					{
						?>
						<div id="locked-topic">
							<p class="text-locked"><img src="<?php echo ROOTPATH; ?>images/forum/lock.png" alt="Sujet vérouillé" /><span class="txt">Ce topic est vérouillé</span></p>
						</div>
						<?php
					}

					if (verifAuth($InfoTopic->forum_auth_modo))
					{
						if ($InfoTopic->topic_locked == 0)
						{
							$value_locked = "lock";
							$name_locked = "Vérouiller le sujet";
						}
						else
						{
							$value_locked = "unlock";
							$name_locked = "Déverouiller le sujet";
						}

						if ($InfoTopic->topic_resolved == 0)
						{
							$value_resolve = "resolve";
							$name_resolve = "Mettre en résolu le sujet";
						}
						else
						{
							$value_resolve = "unresolve";
							$name_resolve = "Retirer le sujet en résolu";
						}
						?>
						<div id="actions">
							<form method="post" id="form-action">
								<select name="actions" onChange="document.getElementById('form-action').submit();">
									<option value="none">Actions</option>
									<option value="<?php echo $value_locked; ?>"><?php echo $name_locked; ?></option>
									<option value="<?php echo $value_resolve; ?>"><?php echo $name_resolve; ?></option>
								</select>
							</form>
						</div>
						<?php
					}
					?>
					<table class="tableau">
						<?php
						while ($MessageTopic = $sqlGetMessageTopic->fetch(PDO::FETCH_OBJ))
						{
							if ($MessageTopic->post_suppr != 1)
							{
								$rangMembre = getRang($MessageTopic->post_id_createur);
								?>
								<tr class="thead" id="p_<?php echo $MessageTopic->post_id; ?>" height="45px;" >
									<th class="liste_perso" width="200px" style="padding-top: 15px;">
										<a href="<?php echo ROOTPATH; ?>?p=profil&amp;id=<?php echo $MessageTopic->post_id_createur; ?>" style="text-decoration: none;" class="a-profil">
											<span class="<?php echo $rangMembre[1]; ?>"><?php echo $rangMembre[0]; ?></span> 
											<?php echo afficherVarBdd($MessageTopic->pseudo); ?>
										</a>
									</th>
									<th class="liste_perso">
										<?php 
										echo mepd($MessageTopic->post_time);

										if (verifAuth($InfoTopic->forum_auth_modo) || $MessageTopic->post_id_createur == $_SESSION['membre_id'])
										{
											if (verifAuth($InfoTopic->forum_auth_modo))
											{
												echo '<span class="message-moderation">
														<span id="inputUpdate">
															<a href="#p_'.$MessageTopic->post_id.'" class="button" onclick="javascript:modifierMessage(\''.$MessageTopic->post_id.'\', \''.$_SESSION['__token'].'\');">Modifier</a>
														</span>
														<a href="'.FORUM.'?view=topic&amp;id='.$topic_id.'&amp;action=supprimer&amp;message='.$MessageTopic->post_id.'&amp;token='.$_SESSION['__token'].'" class="button">Supprimer</a>
													</span>';
											}
											else
											{
												echo '<span class="message-moderation">
														<span id="inputUpdate">
															<a href="#p_'.$MessageTopic->post_id.'" class="button" onclick="javascript:modifierMessage(\''.$MessageTopic->post_id.'\');">Modifier</a>
														</span>
													</span>';
											}
										}
										?>
										<span class="id_post">
											<a href="<?php echo FORUM; ?>?view=topic&amp;id=<?php echo $topic_id; ?>#p_<?php echo $MessageTopic->post_id; ?>">#<?php echo $MessageTopic->post_id; ?></a>
										</span>
									</th>
								</tr>

								<tr class="liste_perso">
									<td class="liste_perso topic-info-membre">
										<?php
										if ($MessageTopic->avatar != '')
											echo '<img src="'.$MessageTopic->avatar.'" alt="Avatar de '.afficherVarBdd($MessageTopic->pseudo).'" />';
										else
											echo '<img src="'.ROOTPATH.'images/forum/inconnu.jpg" alt="Pas d\'avatar" />';
										?>
										<br/>
										Inscrit <?php echo mepd($MessageTopic->inscription); ?><br/>
										Nombre de message : <?php echo NumberFormat($MessageTopic->nb_post); ?><br/>

										<?php
										if ($MessageTopic->post_id_createur != $_SESSION['membre_id'])
										{
											echo '<a href="'.ROOTPATH.'?p=mp&amp;action=nouveau&amp;id='.$MessageTopic->post_id_createur.'" target="_blank" class="a">Lui envoyer un message privé</a>';
										}
										?>
									</td>
									<td class="liste_perso topic-message">
										<?php
										echo '<div id="m_'.$MessageTopic->post_id.'">'.zCode($MessageTopic->post_texte).'</div>'; 

										if ($MessageTopic->post_edit == 1)
										{
											echo '<div class="mess-edit">Édité par '.getPseudoMembre($MessageTopic->post_edit_id_membre).' '.mepd($MessageTopic->post_edit_time).'</div>';
										}
										if ($MessageTopic->signature != '')
										{
											echo '<span class="signature"><hr/>';
											echo zCode($MessageTopic->signature).'</span>';
										}
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</table>
					<div id="repondre">
						<form method="post" action="#">
							<input type="hidden" name="__token" value="<?php echo $_SESSION['__token']; ?>" /><br/>
							<textarea name="message" style="max-width:850px; min-width:850px; min-height:50px; max-height:350px;" rows="10" cols="100"><?php 
								if (isset($_SESSION['form_mess']))
								{
									echo $_SESSION['form_mess'];
									unset($_SESSION['form_mess']);
								}
								?></textarea><br/>
							<input type="submit" value="Répondre" name="message-send" />
						</form>
					</div>
					<?php
				}
				else
				{
					addLog("Droit de lecture insuffisant sur le topic : ".$topic_id, "", "", __FILE__, __LINE__, "admin", "error");
					$Session->setFlash(ERR_AUTH_VIEW);
					header("Location: ".$_SESSION['last_url']);
					exit();
				}
			}
			else
			{
				addLog("Affichage du topic n'existant pas, id : ".$topic_id, "", "", __FILE__, __LINE__, "admin", "error");
				$Session->setFlash("Le topic n'existe pas ou a été supprimé.");
				header("Location: ".$_SESSION['last_url']);
				exit();
			}
		}
		else
		{
			$Session->setFlash(ERR_INTERNE);
			header("Location: ".$_SESSION['last_url']);
			exit();
		}
	}
}
else
{
	$Session->setFlash(ERR_IS_NOT_CO);
	header("Location: ".ROOTPATH);
	exit();
}
?>