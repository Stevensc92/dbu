<?php
if (is_co())
{
	$titre = 'Info Personnage';
	include_once(FUNCTION_DIR.'/function.personnage.php');

	$InfoPersonnage = getInfoPersonnage($_SESSION['membre_id'], $_SESSION['current_perso']);
	$StatPersonnage = getInfosStatsPerso($_SESSION['membre_id'], $_SESSION['current_perso']);
	$StatFightPersonnage = getInfosFightPerso($_SESSION['membre_id'], $_SESSION['current_perso']);
	$PuissancePersonnage = getPuissancePersonnage($_SESSION['membre_id'], $_SESSION['current_perso']);
	$ExpManquant = getExpManquant($_SESSION['membre_id'], $_SESSION['current_perso']);
	$PourcentExp = getPourcentExp($InfoPersonnage['experience'], $InfoPersonnage['level']);
	$AvatarPersonnage = getAvatarPerso($_SESSION['membre_id'], $_SESSION['current_perso']);

	$arrayStat = array('stats_puissance' => 'Puissance', 
						'stats_defense' => 'Défense',
						'stats_magie' => 'Magie', 
						'stats_chance' => 'Chance',
						'stats_vitesse' => 'Vitesse', 
						'stats_concentration' => 'Concentration',
						'stats_vie' => 'Vitalité', 
						'stats_energie' => 'Énergie'
						);

	$arrayFight = array('match_victoire' => 'Victoire',
					   'match_defaite' => 'Défaite',
					   'match_tuer' => 'Tué',
					   'match_nul' => 'Nul'
					   );

	if (isset($_GET['a']) && $_GET['a'] == "distribuer" && $InfoPersonnage['points_distrib'] > 0)
	{
		$titre .= " : Distribuer";

		if (isset($_POST['add_pts']))
		{
			array_pop($_POST);
			$pts_added = 0;

			$erreur = false;

			foreach ($_POST as $key => $value)
			{
				if (intval($value) || $value == 0)
					$pts_added += $value;
				else
				{
					echo $key .' : string';
					$erreur = true;
					$erreur_mess = "Vous devez inscrire uniquement des chiffres pour les points de caractéristique à distribuer.";
				}
			}

			if ($pts_added > $InfoPersonnage['points_distrib'])
				$Session->setFlash('Vous avez <span class="gras">uniquement '.$InfoPersonnage['points_distrib'].'</span> points de caractéristique à distribuer.');
			else if ($pts_added < 0)
				$Session->setFlash('Vous avez entré un nombre négatif');
			else if ($pts_added == 0)
				$Session->setFlash('Vous n\'avez ajouté aucun points de caractéristique');
			else if ($erreur === false)
			{
				$Puissance = $_POST['stats_puissance'];
				$Defense = $_POST['stats_defense'];
				$Magie = $_POST['stats_magie'];
				$Chance = $_POST['stats_chance'];
				$Vitesse = $_POST['stats_vitesse'];
				$Concentration = $_POST['stats_concentration'];
				$Vitalite = $_POST['stats_vie']*100;
				$Energie = $_POST['stats_energie']*5;

				$sqlAddPoints = $bdd->prepare("UPDATE
													jeu_liste_membre_perso
												SET
													stats_puissance = stats_puissance + :puissance,
													stats_defense = stats_defense + :defense,
													stats_magie = stats_magie + :magie,
													stats_chance = stats_chance + :chance,
													stats_vitesse = stats_vitesse + :vitesse,
													stats_concentration = stats_concentration + :concentration,
													stats_vie = stats_vie + :vie,
													stats_energie = stats_energie + :energie
												WHERE
													id_membre = :id_membre && id_perso = :id_perso
												");
				$sqlAddPoints->bindValue('puissance', $Puissance, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('defense', $Defense, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('magie', $Magie, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('chance', $Chance, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('vitesse', $Vitesse, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('concentration', $Concentration, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('vie', $Vitalite, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('energie', $Energie, PDO::PARAM_INT);
				$sqlAddPoints->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
				$sqlAddPoints->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
				$sqlAddPoints->execute();
				
				if($sqlAddPoints->rowCount() > 0)
				{
					$sqlLessPts = $bdd->prepare("UPDATE
													jeu_liste_membre_perso
												 SET
													points_distrib = points_distrib - :toto
												 WHERE
													id_membre = :id_membre && id_perso = :id_perso
												");
					$sqlLessPts->bindValue('toto', $pts_added, PDO::PARAM_INT);
					$sqlLessPts->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
					$sqlLessPts->bindValue('id_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
					$sqlLessPts->execute();
					
					if($sqlLessPts->rowCount() > 0)
					{
						addLog("A ajouté ".$pts_added." avec success", "", "", __FILE__,__LINE__, "", "success");
						$Session->setFlash('Vos points bonus ont bien été distribué.', 'success');
						header("Location: ".ROOTPATH."/?p=perso");
						die();
					}
				}
				else
				{
					addLog(array("Erreur d'ajout des points bonus", $sqlAddPoints->errorInfo()), "", "", __FILE__,__LINE__, "admin", "error");
					$Session->setFlash(ERR_INTERNE, 'error');
					header("Location: ".$_SESSION['last_url']);
					exit();
				}
			}
			else
			{
				if ($erreur_mess != '')
				{
					addLog("Erreur points à distribuer : ".$erreur_mess, "", "", __FILE__, __LINE__, "admin", "error");
					$Session->setFlash($erreur_mess);
				}
				else
				{
					addLog("Erreur quelconque pour l'ajouts des points bonus à distribuer", "", "", __FILE__, __LINE__, "admin", "error");
					$Session->setFlash(ERR_INTERNE);
				}
			}
		}

		include_once('includes/header.php');
		echo '<h1 class="pts_distrib">Il vous reste <span class="gras">'.$InfoPersonnage['points_distrib'].'</span> points de caracteristique à distribuer</h1>';
		?>
		<form method="post" action="#" id="pts_distrib">
			<table id="tableau">
					<tbody>
						<?php
						foreach ($StatPersonnage as $key => $value)
						{
							echo '<tr>
									<td><label for="'.$key.'">'.$arrayStat[$key].' : <span class="gras">'.$value.'</span></label></td> <td><input type="number" name="'.$key.'" id="'.$key.'" value="0" size="3" /></td>
							</tr>';
						}
						?>
						<tr>
							<td colspan="2" style="text-align: center;"><input type="submit" value="Ajouter" name="add_pts" />
						</tr>
				</tbody>
			</table>
		</form>
		<?php
	}
	else
	{
		include_once('includes/header.php');
		echo '<div id="info_personnage">';
			echo '<div id="infos_perso">';
				echo '<ul id="caracteristique">';
					foreach ($StatPersonnage as $key => $value)
						echo '<li><span class="carac_title">'.$arrayStat[$key].'</span>&nbsp;<span class="carac_chiffre">'.NumberFormat($value).'</span></li>';
				echo '</ul>';
				echo '<div class="stop_float"></div>';

				echo '<div id="avatar">';
					echo '<img src="'.ROOTPATH.'timthumb/timthumb.php?src='.$AvatarPersonnage.'&amp;cz=1&amp;w=178&amp;h=268" title="avatar" alt="avatar" />';
				echo '</div>';
			echo '</div>';

			echo '<div id="statistique">';
				echo '<h1 class="nom_personnage">'.$InfoPersonnage['nom_personnage'].'</h1>';

				echo '<ul class="info_fight liste">';
					echo '<li class="info_title">Statistique Fight</li>';
					foreach ($StatFightPersonnage as $key => $value)
						echo '<li><span class="carac_title">'.$arrayFight[$key].'</span>&nbsp;<span class="carac_chiffre">'.NumberFormat($value).'</span></li>';
				echo '</ul>';

				echo '<ul class="info_degat liste">';
					echo '<li class="info_title">Puissance</li>';
					foreach ($PuissancePersonnage as $key => $value)
						echo '<li><span class="carac_title">'.$key.'</span>&nbsp;<span class="carac_chiffre">'.$value.'</span></li>';
				echo '</ul>';
				if ($InfoPersonnage['points_distrib'] > 0)
				{
					echo '<span class="pts_distrib"><a href="'.ROOTPATH.'?p=perso&amp;a=distribuer">Vous avez <span class="gras">'.affich($InfoPersonnage['points_distrib']).'</span> points de caractéristique à distribuer.</a></span>';
				}
			echo '</div>';

			echo '<div id="experience">';
				echo '<span class="experience_chiffre">';
					echo '<span class="exp">'.NumberFormat($InfoPersonnage['experience']).'</span> <span class="title">point  d\'expérience</span>';
				echo '</span>';

				echo '<span class="bar_perso" title="'.$PourcentExp.'" style="margin-left:auto;">';
					echo '<span class="progression" style="width: '.$PourcentExp.'%">';
					echo '</span>';
				echo '</span>';

				echo '<span class="reste_exp">';
					echo 'Il vous manque <span class="strong">'.$ExpManquant.'</span> points d\'expérience avant de passer au niveau suivant.';
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}
}
else
{
	header("Location: ".$_SESSION['last_url']);
}
?>