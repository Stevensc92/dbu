<?php
session_start();
header('Content-type: text/html; charset=utf-8');

include_once('../includes/session.class.php'); // <-- Gestion des erreurs/info du site
include_once('../config/config.define.php'); // <-- Différents define du site
include_once('../config/config.bdd.php'); // <-- Connexion à la base de donée
include_once('../'.ARRAY_DIR.'/array.personnage.php');
include_once('../'.FUNCTION_DIR.'/function.global.php'); // <-- Fonction global du site
include_once('../'.FUNCTION_DIR.'/function.membre.php'); // <-- Fonction "propre" aux membres
include('../'.FUNCTION_DIR.'/function.forum.php');
include('../config/config.forum.php');

if (is_co())
{
	$decal = true;
	$Session = new Session();
	
	if (isset($_GET['change_perso']) && intval($_GET['change_perso']))
		ChangePerso($_GET['change_perso'], $_SESSION['membre_id']);
		
	$_SESSION['current_perso'] = getCurrentPerso($_SESSION['membre_id']);
	$dir_forum = true;
	
	/*
	 *
	 *	Gestion des "actions" pour le forum 
     *	View : Visionnage d'un topic ou forum
	 */
	$keys = array_keys($_GET);
	if(count($keys) > 0 && ($keys[0] == "view"))
	{
		if(file_exists($keys[0].'/'.$_GET[$keys[0]].'.php'))
		{
			require($keys[0].'/'.$_GET[$keys[0]].'.php');
			$_SESSION['last_url'] = $_SERVER["REQUEST_URI"];
		}
		else
		{
			require('../error/404.php');
		}
	}
	else
	{
		$titre = "Forum";
		include('../includes/header.php');
		
		/* Index du forum, on affiche les diverses catégories ainsi que les forums leur appartenant */
		$sqlGetInfoForum = $bdd->prepare("SELECT
											cat_id, cat_nom,
											forum_id, forum_cat_id, forum_nom, forum_desc, forum_locked, 
											forum_last_post_id,	forum_nb_topic, forum_nb_post, forum_auth_view,
											topic_id, topic_nb_message, topic_last_post_id, topic_titre,
											post_id, post_time, post_id_createur,
											pseudo, id,
											tv_id_membre, tv_post_id, tv_poste
										FROM
											forum_categorie
										LEFT JOIN
											forum_forum
										ON
											forum_categorie.cat_id = forum_forum.forum_cat_id
										LEFT JOIN
											forum_post
										ON
											forum_post.post_id = forum_forum.forum_last_post_id
										LEFT JOIN
											forum_topic
										ON
											forum_topic.topic_id = forum_post.post_topic_id
										LEFT JOIN
											site_membres
										ON
											site_membres.id = forum_post.post_id_createur
										LEFT JOIN
											forum_topic_view
										ON
											forum_topic.topic_forum_id = forum_topic_view.tv_forum_id AND forum_topic_view.tv_id_membre = :id_membre
										WHERE
											forum_auth_view <= :forum_auth_view
										GROUP BY
											forum_id
										ORDER BY
											cat_ordre, forum_ORDRE ASC
										");
		$sqlGetInfoForum->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
		$sqlGetInfoForum->bindValue('forum_auth_view', $_SESSION['membre_rang'], PDO::PARAM_INT);
		$sqlGetInfoForum->execute();
		

		$categorie = NULL;
		?>
		<table class="tableau">
		<?php
		if ($sqlGetInfoForum->rowCount() < 1)
		{
			echo '<p class="false">Problème d\'affichage et/ou aucun forum à afficher.</p>';
		}
		else
		{
			while ($Forum = $sqlGetInfoForum->fetch())
			{
				if($categorie != $Forum['cat_id'])
				{
					//Si c'est une nouvelle catégorie on l'affiche		   
					$categorie = $Forum['cat_id'];
					?>
					<tr class="thead">
						<th class="liste_perso" width="30"></th>
						<th class="liste_perso"><?php echo afficherVarBdd($Forum['cat_nom']); ?></th>
						<th class="liste_perso">Sujets</th>
						<th class="liste_perso">Messages</th>
						<th class="liste_perso">Dernier message</th>
					</tr>
					<?php
				}

				if (verifAuth($Forum['forum_auth_view'])) // Vérification si le membre peut voir le forum
				{
					if ($Forum['forum_locked'] == "1") // Si le forum est vérouiller, l'image sera une image indiquant que c'est vérouillé
					{
						$image_info = "lock";
						$alt = "Ce forum est vérouillé";
					}
					else // Sinon on regarde si ça a été déjà vu ou non
					{
						// ici, si a déjà été vu on mets l'image déjà vu
						$image_info = 'news';
						$alt = "Nouveaux messages";

						// sinon on met celle qui indique un nouveau message
						//$image_info = 'no_news';
						//$alt = "Pas de nouveaux messages";

						// Si il n'y a aucun message dans le forum
						//$image_info = 'no_news';
						//$alt = "Il n'y a pas encore de message dans ce forum";
					}
				?>
					<tr class="liste_perso">
						<td class="liste_perso"><img class="image-info" src="<?php echo ROOTPATH; ?>images/forum/<?php echo $image_info; ?>.png" alt="<?php echo $alt; ?>" /></td>
						<td class="liste_perso">
							<a href="<?php echo FORUM; ?>?view=forum&amp;id=<?php echo $Forum['forum_id']; ?>" class="forum-lien"><?php echo $Forum['forum_nom']; ?></a><br/>
							<span class="forum-desc"><?php echo $Forum['forum_desc']; ?></span>
						</td>
						<td class="liste_perso"><?php echo $Forum['forum_nb_topic']; ?></td>
						<td class="liste_perso"><?php echo $Forum['forum_nb_post']; ?></td>
						<td class="liste_perso">
							<?php
							if ($Forum['forum_last_post_id'] == '0')
							{
								echo 'Pas de message';
							}
							else
							{
								$mess_par_page = $ConfigForum['post_par_page'];
								$nb_message = $Forum['topic_nb_message'];
								$nb_page = ceil($nb_message / $mess_par_page);

								$direct_link_post = ($nb_page > 1) ? '&amp;page='.$nb_page.'#p_'.$Forum['post_id'] : '#p_'.$Forum['post_id'];
								echo '<span class="info-sujet">Par <a href="'.ROOTPATH.'?p=profil&amp;id='.$Forum['id'].'">'.$Forum['pseudo'].'</a><br/>
										'.mepd($Forum['post_time']).'<br/>
										Dans <a href="'.FORUM.'?view=topic&amp;id='.$Forum['topic_id'].$direct_link_post.'">'.afficherVarBdd($Forum['topic_titre']).'</a></span>';
							}
							?>
						</td>
					</tr>
				<?php
				}
			}
		}
		?>
		</table>
		<?php
	}
	$Session->flash();
	$_SESSION['last_url'] = $_SERVER["REQUEST_URI"];
	include('../includes/footer.php');
}
else
{
	header("Location: ".ROOTPATH);
}

?>