<?php
if (is_co())
{
	$titre = "Infos Personnages";
	include('includes/header.php');

	$sqlListePerso = $bdd->prepare("SELECT
										*, nom_personnage, chemin_image
									FROM
										jeu_liste_membre_perso
									LEFT JOIN
										jeu_liste_personnage
									ON
										jeu_liste_personnage.id_perso = jeu_liste_membre_perso.id_perso
									LEFT JOIN
										jeu_liste_perso_avatar
									ON
										jeu_liste_perso_avatar.level = jeu_liste_membre_perso.level && 
										jeu_liste_perso_avatar.id_perso = jeu_liste_membre_perso.id_perso
									WHERE
										id_membre = :id_membre
									");
	$sqlListePerso->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
	$sqlListePerso->execute();
	
	$sqlToto = $bdd->prepare("SELECT
								COUNT(*) AS TotoPerso, SUM(level) as TotoLevel,
								SUM(experience) AS TotoExp, SUM(match_victoire) AS TotoVictoire,
								SUM(match_defaite) AS TotoDefaite, SUM(match_tuer) AS TotoKill
							  FROM
								jeu_liste_membre_perso
							  WHERE
								id_membre = :id_membre
							");
	$sqlToto->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
	$sqlToto->execute();
	$Toto = $sqlToto->fetch();

	echo '<div id="other-content">';
		if ($sqlListePerso->rowCount() > 0)
		{
			while($Perso = $sqlListePerso->fetch())
			{
				$percent = getPourcentExp($Perso['experience'], $Perso['level']);
				$sqlNbFight = $bdd->prepare("SELECT
												COUNT(*) as nb_fight
											 FROM
												jeu_liste_combat
											 WHERE
												id_membre_defenseur = :id_membre && id_perso_defenseur = :id_perso && etat_fight = 0
											");
				$sqlNbFight->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
				$sqlNbFight->bindValue('id_perso', $Perso['id_perso'], PDO::PARAM_INT);
				$sqlNbFight->execute();
				
				$NbFight = $sqlNbFight->fetch();
				$NbFight = $NbFight['nb_fight'];

				echo '<div class="bloc_perso">';
					echo '<h1>'.$Perso['nom_personnage'].'</h1>';
					
					echo '<span class="avatar"><img src="'.TIMTHUMB.$Perso['chemin_image'].'&amp;w=80&amp;h=134&amp;cz=1" /></span>';
					
					echo '<ul>';
						echo '<li><span class="carac_title">Niveau :</span> <span class="carac_chiffre">'.$Perso['level'].'</span>';
						echo '<li><span class="carac_title">Expérience :</span> <span class="carac_chiffre">'.NumberFormat($Perso['experience']).'</span></li>';
						echo '<li class="barre">';
							echo '<span class="bar-experience">';
								echo '<span class="bar-progression" style="width: '.$percent.'%">';
								echo '</span>';
							echo '</span>';
						echo '</li>';
						echo '<li><span class="carac_title">Ki :</span> <span class="carac_chiffre">'.NumberFormat(calculKiPerso($Perso['id_membre'], $Perso['id_perso'])).'</span></li>';
						echo '<li><span class="carac_title">Fight reçu :</span> <span class="carac_chiffre">'.$NbFight.'</span></li>';
					echo '</ul>';
				echo '</div>'; // Fin div.bloc_perso
			}
		}

		echo '<div id="cumul">';
			echo '<h1>Cumul</h1>';
			echo '<ul class="cumul">';
				echo '<li><span class="cumul_title">Nombre de personnage débloqué :</span> <span class="cumul_chiffre">'.$Toto['TotoPerso'].'</span></li>';
				echo '<li><span class="cumul_title">Cumul de niveaux :</span> <span class="cumul_chiffre">'.$Toto['TotoLevel'].'</span></li>';
				echo '<li><span class="cumul_title">Cumul d\'expérience :</span> <span class="cumul_chiffre">'.NumberFormat($Toto['TotoExp']).'</span></li>';
				echo '<li><span class="cumul_title">Cumul de victoire :</span> <span class="cumul_chiffre">'.NumberFormat($Toto['TotoVictoire']).'</span></li>';
				echo '<li><span class="cumul_title">Cumul de défaite :</span> <span class="cumul_chiffre">'.NumberFormat($Toto['TotoDefaite']).'</span></li>';
				echo '<li><span class="cumul_title">Cumul de kill :</span> <span class="cumul_chiffre">'.NumberFormat($Toto['TotoKill']).'</span></li>';
			echo '</ul>';
		echo '</div>'; // Fin div#cumul

	echo '</div>'; // Fin div#other-content
}
else
{
	header("Location: ".ROOTPATH);
}