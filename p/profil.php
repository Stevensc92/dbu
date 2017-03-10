<?php
if (is_co())
{
	if (isset($_GET['id']) && intval($_GET['id']))
	{
		$id_membre = intval($_GET['id']);

		$sqlRecMembre = $bdd->prepare("SELECT
											*
										FROM
											site_membres
										LEFT JOIN 
											site_connectes
										ON 
											site_connectes.connectes_id = site_membres.id
										LEFT JOIN
											jeu_liste_membre_perso
										ON
											jeu_liste_membre_perso.id_perso = site_membres.id_current_perso && jeu_liste_membre_perso.id_membre = site_membres.id
										LEFT JOIN
											jeu_liste_personnage
										ON
											jeu_liste_personnage.id_perso = site_membres.id_current_perso
										WHERE
											id = :id
									");
		$sqlRecMembre->bindValue('id', $id_membre, PDO::PARAM_INT);
		$sqlRecMembre->execute();

		if ($sqlRecMembre->rowCount() > 0)
		{
			$decal = true;

			$InfoMember = $sqlRecMembre->fetch();

			$titre = "Profil de ".$InfoMember['pseudo'];
			include('includes/header.php');
			$sqlNbrPerso = $bdd->prepare("SELECT
											COUNT(id_membre) AS Nbr_Perso
										  FROM
											jeu_liste_membre_perso
										  WHERE
											id_membre = :id_membre
										");
			$sqlNbrPerso->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
			$sqlNbrPerso->execute();
			$NbrPerso = $sqlNbrPerso->fetch();
			$NbrPerso = $NbrPerso['Nbr_Perso'];

			$Avatar = getAvatarPerso($InfoMember['id'], $InfoMember['id_current_perso']);
			$rangMembre = getRang($InfoMember['id']);
			/* <span class="<?php echo $rangMembre[1]; ?>"><?php echo $rangMembre[0]; ?></span> */

			$sVictoire = ($InfoMember['match_victoire'] > 1) ? 's' : '';
			$sDefaite = ($InfoMember['match_defaite'] > 1) ? 's' : '';
			$sKill = ($InfoMember['match_tuer'] > 1) ? 's' : '';
			$sNul = ($InfoMember['match_nul'] > 1) ? 's' : '';
			?>
			<section id="profil">
				<aside class="left">
					<img src="<?php echo TIMTHUMB.$Avatar; ?>&amp;w=181&amp;h=270&amp;cz=1" alt="Avatar de <?php echo $ArrayPersonnage[$InfoMember['id_current_perso']]['nom_personnage']; ?>" />
				</aside>

				<article class="right">
					<section class="info">
						<h1>Profil de <span class="pseudo"><?php echo $InfoMember['pseudo']; ?></span></h1>
						<h2>Informations générales</h2>
						<ul>
							<li>Personnage en cours d'utilisation : <span class="info-chiffre"> <?php echo $InfoMember['nom_personnage']; ?> </span></li>
							<li>Zénis : <span class="info-chiffre"> <?php echo NumberFormat($InfoMember['zenis']); ?> </span></li>
							<li>Niveau : <span class="info-chiffre"> <?php echo $InfoMember['level']; ?> </span></li>
							<li>Expérience : <span class="info-chiffre"> <?php echo NumberFormat($InfoMember['experience']); ?> </span></li>
							<li>Ki : <span class="info-chiffre"> <?php echo NumberFormat(calculKiPerso($InfoMember['id'], $InfoMember['id_current_perso'])); ?> </span></li>
							<li>Nombre de Victoire<?php echo $sVictoire; ?> : <span class="info-chiffre"> <?php echo NumberFormat($InfoMember['match_victoire']); ?> </span></li>
							<li>Nombre de Défaite<?php echo $sDefaite; ?> : <span class="info-chiffre"> <?php echo NumberFormat($InfoMember['match_defaite']); ?> </span></li>
							<li>Nombre de Kill<?php echo $sKill; ?> : <span class="info-chiffre"> <?php echo NumberFormat($InfoMember['match_tuer']); ?> </span></li>
							<li>Nombre de match<?php echo $sNul; ?> nul<?php echo $sNul; ?> : <span class="info-chiffre"> <?php echo NumberFormat($InfoMember['match_nul']); ?> </span></li>
							<li>Nombre de personnages débloqués : <span class="info-chiffre"> <?php echo $NbrPerso; ?> </span></li>
						</ul>
						<h2>Informations complémentaires</h2>
						<ul class="complem">
							<li>
								<?php
								if($InfoMember['connectes_id'] == $InfoMember['id'])
								{
								?>
									<span class="actif"><img src="<?php echo ROOTPATH; ?>images/profil/online.png" /><?php echo ucfirst($InfoMember['pseudo']); ?> est connecté</span>
								<?php
								}
								else
								{
								?>
									<span class="inactif"><img src="<?php echo ROOTPATH; ?>images/profil/offline.png" /><?php echo ucfirst($InfoMember['pseudo']); ?> est déconnecté</span>
								<?php
								}
								?>
							</li>
							<li>Inscrit depuis : <?php echo mepd($InfoMember['inscription']); ?></li>
							<li>Dernière visite : <?php echo mepd($InfoMember['derniere_visite']); ?></li>
							<li><a href="<?php echo ROOTPATH; ?>?p=mp&amp;action=nouveau&amp;id=<?php echo $id_membre; ?>">Envoyer un message privé</a></li>
						</ul>
					</section>

					<section class="search">
						<form method="post" 
					</section>
				</article>
			</section>
			<?php
		}
		else
		{
			header("Location: ".$_SESSION['last_url']);
		}
	}
	else
	{
		header("Location: ".ROOTPATH."?p=profil&id=".$_SESSION['membre_id']);
	}
}
else
{
	header("Location: ".ROOTPATH);
}
?>