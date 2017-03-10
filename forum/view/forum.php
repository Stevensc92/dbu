<?php
if (is_co())
{
	$decal = true;
	if (isset($_GET['id']) && intval($_GET['id']))
	{
		$forum_id = $_GET['id'];
		$sqlExistForum = $bdd->prepare("SELECT
											COUNT(forum_id) as Nbr, forum_auth_view, forum_locked, forum_auth_topic, forum_nb_topic, forum_auth_modo
										  FROM
										  	forum_forum
										  WHERE
										  	forum_id = :forum_id
										");
		$sqlExistForum->bindValue('forum_id', $forum_id, PDO::PARAM_INT);
		$sqlExistForum->execute();

		$ExistForum = $sqlExistForum->fetch();

		if ($ExistForum['Nbr'] > 0)
		{
			if (verifAuth($ExistForum['forum_auth_view']))
			{
				$sqlInfoForum = $bdd->prepare("SELECT
													forum_nom
												FROM
													forum_forum
												WHERE
													forum_id = :forum_id
												");
				$sqlInfoForum->bindValue('forum_id', $forum_id, PDO::PARAM_INT);
				$sqlInfoForum->execute();

				$InfoForum = $sqlInfoForum->fetch(PDO::FETCH_OBJ);

				$titre = "Forum - ".$InfoForum->forum_nom;
				include('../includes/header.php');

				?>
				<ul class="center">
					<li><a href="<?php echo FORUM; ?>">Index - </a></li>
					<li><a href="<?php echo FORUM; ?>?view=forum&amp;id=<?php echo $forum_id; ?>" class="hover"><?php echo afficherVarBdd($InfoForum->forum_nom); ?></a></li>
				</ul>

				<p>
					<?php
					if ($ExistForum['forum_locked'] == 1) // Si le forum est vérouillé
					{
						if (verifAuth($ExistForum['forum_auth_modo'])) // Si un membre(modo) à le droit nécessaire de poster un new sujet dans un forum vérouillé
						{
							echo '<a href="'.FORUM.'?view=forum&amp;id='.$forum_id.'&amp;a=new" class="submit">Nouveau sujet</a>';
						}
						else // Sinon c'est un membre normal
						{
							echo "Ce forum est vérouillé";
						}
					}
					else if (verifAuth($ExistForum['forum_auth_topic'])) // Sinon si un membre (normal) à le droit de créer un sujet
					{
						echo '<a href="'.FORUM.'?view=forum&amp;id='.$forum_id.'&amp;a=new" class="submit">Nouveau sujet</a>';
					}
					?>
				</p>
				<br/>
				<?php
				$sqlGetTopicAnnonce = $bdd->prepare("SELECT
														topic_id, topic_titre, topic_id_createur, topic_vu, topic_time, topic_nb_message, topic_last_post_id, topic_locked, topic_resolved,
														pseudo_createur.pseudo as membre_pseudo_createur, pseudo_last_post.pseudo as membre_pseudo_last_post, post_id, post_id_createur, post_time,
														tv_id_membre, tv_post_id, tv_poste
													FROM
														forum_topic
													INNER JOIN
														site_membres pseudo_createur 
													ON 
														pseudo_createur.id = forum_topic.topic_id_createur
													INNER JOIN
														forum_post
													ON
														forum_topic.topic_last_post_id = forum_post.post_id
													INNER JOIN
														site_membres pseudo_last_post
													ON
														pseudo_last_post.id = forum_post.post_id_createur
													LEFT JOIN
														forum_topic_view
													ON
														forum_topic.topic_id = forum_topic_view.tv_topic_id AND forum_topic_view.tv_id_membre = :id_membre
													WHERE
														topic_genre = '2' && forum_topic.topic_forum_id = :forum_id && topic_suppr = '0'
													ORDER BY
														topic_last_post_id DESC
													");
				$sqlGetTopicAnnonce->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
				$sqlGetTopicAnnonce->bindValue('forum_id', $forum_id, PDO::PARAM_INT);
				$sqlGetTopicAnnonce->execute();

				if ($sqlGetTopicAnnonce->rowCount() > 0)
				{
				?>
					<table class="tableau">
						<tr class="thead">
							<th class="liste_perso" width="30"></th>
							<th class="liste_perso">Sujets</th>
							<th class="liste_perso">Message</th>
							<th class="liste_perso">Vus</th>
							<th class="liste_perso">Dernier message</th>
						</tr>

						<?php
						while ($TopicAnnonce = $sqlGetTopicAnnonce->fetch(PDO::FETCH_OBJ))
						{
							/*
							 *
							 * Gestion de l'image à afficher (nouveau message, pas de nouveau message, vérouillé)
							 *
							 */

							if ($TopicAnnonce->topic_locked == 1)
							{
								$alt = "Ce sujet est vérouillé";
								$img = ROOTPATH."images/forum/lock.png";
							}
							else
							{
								$alt = "Pas de nouveau message";
								$img = ROOTPATH."images/forum/no_news.png";
							}
						?>
							<tr class="liste_perso">
								<td class="liste_perso"><img class="image-info" src="<?php echo $img; ?>" alt="<?php echo $alt; ?>" /></td>
								<td class="liste_perso">
									Annonce : 
									<a href="<?php echo FORUM; ?>?view=topic&amp;id=<?php echo $TopicAnnonce->topic_id; ?>" class="href-sujet">
										<span class="titre-sujet forum-lien"><?php echo afficherVarBdd($TopicAnnonce->topic_titre); ?></span><br/>
										<span class="info-sujet">Par <?php echo afficherVarBdd($TopicAnnonce->membre_pseudo_createur); ?> <?php echo mepd($TopicAnnonce->topic_time); ?></span>
									</a>
								</td>
								<td class="liste_perso"><?php echo afficherVarBdd($TopicAnnonce->topic_nb_message); ?></td>
								<td class="liste_perso"><?php echo afficherVarBdd($TopicAnnonce->topic_vu); ?></td>
								<td class="liste_perso">
									<?php
									$nb_page = ceil($TopicAnnonce->topic_nb_message / $ConfigForum['post_par_page']);

									$direct_link_post = ($nb_page > 1) ? '&amp;page='.$nb_page.'#p_'.$TopicAnnonce->post_id : '#p_'.$TopicAnnonce->post_id;
									?>
									<a href="<?php echo FORUM; ?>?view=topic&amp;id=<?php echo $TopicAnnonce->topic_id.$direct_link_post; ?>" class="last-post">
										Par <?php echo afficherVarBdd($TopicAnnonce->membre_pseudo_last_post); ?><br/>
										<?php echo mepd($TopicAnnonce->post_time); ?>
									</a>
								</td>
							</tr>
						<?php
						}
						?>
					</table>
				<?php
				}

				$sqlGetTopic = $bdd->prepare("SELECT
												topic_id, topic_titre, topic_id_createur, topic_vu, topic_time, topic_nb_message, topic_last_post_id, topic_locked, topic_resolved,
												pseudo_createur.pseudo as membre_pseudo_createur, pseudo_last_post.pseudo as membre_pseudo_last_post, post_id, post_id_createur, post_time,
												tv_id_membre, tv_post_id, tv_poste
											FROM
												forum_topic
											INNER JOIN
												site_membres pseudo_createur 
											ON 
												pseudo_createur.id = forum_topic.topic_id_createur
											INNER JOIN
												forum_post
											ON
												forum_topic.topic_last_post_id = forum_post.post_id
											INNER JOIN
												site_membres pseudo_last_post
											ON
												pseudo_last_post.id = forum_post.post_id_createur
											LEFT JOIN
												forum_topic_view
											ON
												forum_topic.topic_id = forum_topic_view.tv_topic_id AND forum_topic_view.tv_id_membre = :id_membre
											WHERE
												topic_genre = '1' && forum_topic.topic_forum_id = :forum_id && topic_suppr = '0'
											ORDER BY
												topic_last_post_id DESC
											");
				$sqlGetTopic->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
				$sqlGetTopic->bindValue('forum_id', $forum_id, PDO::PARAM_INT);
				$sqlGetTopic->execute();

				if ($sqlGetTopic->rowCount() > 0)
				{
				?>
					<table class="tableau">
						<tr class="thead">
							<th class="liste_perso" width="30"></th>
							<th class="liste_perso">Sujets</th>
							<th class="liste_perso">Message</th>
							<th class="liste_perso">Vus</th>
							<th class="liste_perso">Dernier message</th>
						</tr>

						<?php
						while ($Topic = $sqlGetTopic->fetch(PDO::FETCH_OBJ))
						{
							if ($Topic->topic_resolved == 1)
							{
								$resolu = '<img src="'.ROOTPATH.'images/forum/resolu.png" alt="resolu" />';
								$class = ' class="text-resolve" ';
							}
							else
							{
								$resolu = '';
								$class = '';
							}

							/*
							 *
							 * Gestion de l'image à afficher (nouveau message, pas de nouveau message, vérouillé)
							 *
							 */

							if ($Topic->topic_locked == 1)
							{
								$alt = "Ce sujet est vérouillé";
								$img = ROOTPATH."images/forum/lock.png";
							}
							else
							{
								$alt = "Pas de nouveau message";
								$img = ROOTPATH."images/forum/no_news.png";
							}
						?>
							<tr class="liste_perso">
								<td class="liste_perso"><img class="image-info" src="<?php echo $img; ?>" alt="<?php echo $alt; ?>" /></td>
								<td class="liste_perso">
									<a href="<?php echo FORUM; ?>?view=topic&amp;id=<?php echo $Topic->topic_id; ?>" class="href-sujet">
										<p <?php echo $class; ?>><?php echo $resolu; ?> <span class="titre-sujet forum-lien txt"> <?php echo afficherVarBdd($Topic->topic_titre); ?></span></p><br/>
										<span class="info-sujet">Par <?php echo afficherVarBdd($Topic->membre_pseudo_createur); ?> <?php echo mepd($Topic->topic_time); ?></span>
									</a>
								</td>
								<td class="liste_perso"><?php echo afficherVarBdd($Topic->topic_nb_message); ?></td>
								<td class="liste_perso"><?php echo afficherVarBdd($Topic->topic_vu); ?></td>
								<td class="liste_perso">
									<?php
									$nb_page = ceil($Topic->topic_nb_message / $ConfigForum['post_par_page']);

									$direct_link_post = ($nb_page > 1) ? '&amp;page='.$nb_page.'#p_'.$Topic->post_id : '#p_'.$Topic->post_id;
									?>
									<a href="<?php echo FORUM; ?>?view=topic&amp;id=<?php echo $Topic->topic_id.$direct_link_post; ?>" class="last-post">
										Par <?php echo afficherVarBdd($Topic->membre_pseudo_last_post); ?><br/>
										<?php echo mepd($Topic->post_time); ?>
									</a>
								</td>
							</tr>
						<?php
						}
						?>
					</table>
				<?php
				}


			}
			else
			{
				$Session->setFlash(ERR_AUTH_VIEW);
				header("Location: ".$_SESSION['last_url']);
				exit();
			}
		}
		else
		{
			header("Location: ".$_SESSION['last_url']);
		}

	}
	else
	{
		header("Location: ".$_SESSION['last_url']);
	}
}
else
{
	$Session->setFlash(ERR_IS_NOT_CO);
	header("Location: ".ROOTPATH);
	exit();
}
?>