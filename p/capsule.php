<?php
if (is_co())
{
	include(FUNCTION_DIR.'/function.capsule.php');
	$titre = "Inventaire";
	include('includes/header.php');

	if (isset($_POST['capsuleFormToBag']))
	{
		if (isset($_POST['capsuleEq']))
			retirerCapsule($_POST['capsuleEq'], $_SESSION['membre_id'], $_SESSION['current_perso']);
		else
			$Session->setFlash('Aucune capsule a retirer a été sélectionné.', 'info');
	}

	if (isset($_POST['capsuleFormToEq']))
	{
		if (isset($_POST['capsule']))
			equiperCapsule($_POST['capsule'], $_SESSION['membre_id'], $_SESSION['current_perso']);
		else
			$Session->setFlash('Aucune capsule a équiper a été sélectionné.', 'info');
	}

	if (isset($_POST['capsuleSell']))
	{
		echo 'capsuleSell';
	}

	$sqlGetCapsEquipe = $bdd->prepare("SELECT
											jeu_liste_membre_capsule.id as MembreCapsID, id_capsule, level_capsule, experience,
											type, nom
										FROM
											jeu_liste_membre_capsule
										INNER JOIN
											jeu_liste_capsule
										ON
											jeu_liste_capsule.id = jeu_liste_membre_capsule.id_capsule
										WHERE
											id_membre = :id_membre && jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id && 
											id_perso_equipe = :id_current_perso
										");
	$sqlGetCapsEquipe->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
	$sqlGetCapsEquipe->bindValue('id_current_perso', $_SESSION['current_perso'], PDO::PARAM_INT);
	$sqlGetCapsEquipe->execute();
	echo '<div id="caps_equiper">';
		echo '<form method="post" action="#">';
			echo '<span class="submit-button">';
				echo '<input type="submit" value="Retirer" name="capsuleFormToBag" class="CapsEq"/>';
			echo '</span>';
			echo '<div class="content">';
				echo '<ul>';
				while ($CapsEquipe = $sqlGetCapsEquipe->fetch())
				{
					if ($sqlGetCapsEquipe->rowCount() > 0)
					{
						switch ($CapsEquipe['type'])
						{
							case 1:
								$ImageCaps = CAPS_J;
							break;

							case 2:
								$ImageCaps = CAPS_R;
							break;

							case 3:
								$ImageCaps = CAPS_V;
							break;

							default:
								$ImageCaps = "Erreur";
							break;
						}
						$StatsCaps = AfficherStat($CapsEquipe['MembreCapsID'], $CapsEquipe['type'], $CapsEquipe['level_capsule']);
						echo '<li class="demo-default" title="'.$StatsCaps.'">';
							echo '<span>';
								echo '<input type="checkbox" name="capsuleEq[]" value="'.$CapsEquipe['MembreCapsID'].'"/><img src="'.$ImageCaps.'" />';
							echo '</span><br/>';
								echo $CapsEquipe['nom'];
						echo '</li>';
					}
				}
				echo '</ul>';
			echo '</div>';
		echo '</form>';
	echo '</div>';


	$sqlGetCapsInventaire = $bdd->prepare("SELECT
												jeu_liste_membre_capsule.id as MembreCapsID, id_capsule, level_capsule, experience,
												type, nom
											FROM
												jeu_liste_membre_capsule
											INNER JOIN
												jeu_liste_capsule
											ON
												jeu_liste_capsule.id = jeu_liste_membre_capsule.id_capsule
											WHERE
												id_membre = :id_membre && jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id && 
												jeu_liste_membre_capsule.id_perso_equipe = 0
											");
	$sqlGetCapsInventaire->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
	$sqlGetCapsInventaire->execute();

	echo '<div id="inventaire">';
		echo '<form method="post" action="#">';
			echo '<span class="submit-button">';
				echo '<input type="submit" value="Équiper" name="capsuleFormToEq" style="margin-right: 3px;"><input type="submit" value="Vendre" name="capsuleSell" />';
			echo '</span>';
			echo '<div class="content">';
				echo '<ul>';
				while ($CapsInventaire = $sqlGetCapsInventaire->fetch())
				{
					if ($sqlGetCapsInventaire->rowCount() > 0)
					{
						switch ($CapsInventaire['type'])
						{
							case 1:
								$ImageCaps = CAPS_J;
							break;

							case 2:
								$ImageCaps = CAPS_R;
							break;

							case 3:
								$ImageCaps = CAPS_V;
							break;

							default:
								$ImageCaps = "Erreur";
							break;
						}

						$StatsCaps = AfficherStat($CapsInventaire['MembreCapsID'], $CapsInventaire['type'], $CapsInventaire['level_capsule']);
						echo '<li class="demo-default" title="'.$StatsCaps.'">';
							echo '<span>';
								echo '<input type="checkbox" name="capsule[]" value="'.$CapsInventaire['MembreCapsID'].'"/><img src="'.$ImageCaps.'" />';
							echo '</span><br/>';
							echo $CapsInventaire['nom'].'';
						echo '</li>';
					}
				}
				echo '</ul>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
}
else
{
	header("Location: ".ROOTPATH);
}
?>