<?php
/*
 *
 * Fichier function.membre.php
 * Fonction relatives aux membres concernant le jeu ou le site
 *
 */

function getCurrentPerso($id_membre)
{
	global $bdd;
	$Session = new Session();

	$sqlGetCurrentPerso = $bdd->prepare("SELECT
											COUNT(id) as Nbr, id_current_perso
										 FROM
										 	site_membres
										 WHERE
										 	id = :id_membre
										 ");
	$sqlGetCurrentPerso->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlGetCurrentPerso->execute();

	$CurrentPerso = $sqlGetCurrentPerso->fetch();

	if ($CurrentPerso['Nbr'] > 0)
		return $CurrentPerso['id_current_perso'];
	else
	{
		$Session->setFlash(ERR_INTERNE);
		addLog(array("Une erreur s'est produite lors de l'obtention de l'id current perso du membre ".$_SESSION['membre_pseudo']."->".$id_membre, $sqlGetCurrentPerso->errorInfo()),
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		return false;
	}
}

function getRang($id_membre)
{
		/*
	0 = Bloqué (rien afficher)
	1 = Membre (rien afficher)
	2 = Animateur (afficher)
	3 = Modérateur (afficher)
	4 = Administrateur (afficher)
	5 = Webmaster (afficher)
	6 = Robot (afficher)
	*/
	global $bdd;
	
	$sqlGetRangForum = $bdd->prepare("SELECT 
											rang
									  FROM 
									  		site_membres
									  WHERE 
									  	id = :id");
	$sqlGetRangForum->bindValue('id', $id_membre, PDO::PARAM_INT);
	$sqlGetRangForum->execute();
	$RangForum = $sqlGetRangForum->fetch();
	switch($RangForum['rang'])
	{
		case 0:
			$infos = array('', '');
		break;
		
		case 1:
			$infos = array('', '');
		break;
		
		case 2:
			$infos = array('[ANIM]', 'animateur');
		break;
		
		case 3:
			$infos = array('[MODO]', 'moderateur');
		break;
		
		case 4:
			$infos = array('[ADMIN]', 'administrateur');
		break;
		
		case 5:
			$infos = array('[WEB]', 'webmaster');
		break;
		
		case 6:
			$infos = array('[BOT]', 'robot');
		break;
	}
	
	return $infos;
}

function getPseudoMembre($id_membre)
{
	global $bdd;

	$sqlGetPseudoMembre = $bdd->prepare("SELECT pseudo FROM site_membres WHERE id = :id_membre");
	$sqlGetPseudoMembre->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlGetPseudoMembre->execute();

	if ($sqlGetPseudoMembre->rowCount() > 0)
	{
		$PseudoMembre = $sqlGetPseudoMembre->fetch();
		return $PseudoMembre['pseudo'];
	}
	else
	{
		addLog(array("Erreur d'obtention du pseudo d'un membre de la fonction getPseudoMembre()", $sqlGetPseudoMembre->errorInfo()),
				$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'], __FILE__, __LINE__, "admin", "error");
		return false;
	}
}

function PasserNiveauCapsule($id_membre, $id_current_perso)
{
	global $bdd;
	global $Session;

	$sqlGetCapsule = $bdd->prepare("SELECT
										id_capsule, level_capsule, experience, exp_require, id_perso_equipe, nom
									FROM
										jeu_liste_membre_capsule
									INNER JOIN
										jeu_liste_capsule
									ON
										jeu_liste_membre_capsule.id_capsule = jeu_liste_capsule.id
									INNER JOIN
										jeu_level_capsule
									ON
										jeu_liste_capsule.type = jeu_level_capsule.id_type_capsule && jeu_level_capsule.level = jeu_liste_membre_capsule.level_capsule + 1
									WHERE
										id_membre = :id_membre
									");
	$sqlGetCapsule->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlGetCapsule->execute();

	$message = '';
	while($Capsule = $sqlGetCapsule->fetch())
	{
		if ($Capsule['experience'] >= $Capsule['exp_require'] && $Capsule['level_capsule'] < 5)
		{
			$sqlUpCapsule = $bdd->prepare("UPDATE
												jeu_liste_membre_capsule
											SET
												level_capsule = level_capsule + 1
											WHERE
												id_capsule = :id_capsule && id_membre = :id_membre
											");
			$sqlUpCapsule->bindValue('id_capsule', $Capsule['id_capsule'], PDO::PARAM_INT);
			$sqlUpCapsule->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
			$sqlUpCapsule->execute();

			if($sqlUpCapsule->rowCount() == 0)
			{
				addLog(array("Erreur lors de la montée de niveau d'une capsule, capsule id : ".$Capsule['id_capsule'], $sqlUpCapsule->errorInfo()),
						$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
					__FILE__, __LINE__, "admin", "error");
				$Session->setFlash(ERR_INTERNE);
			}
			else
			{

				addLog("La capsule id[".$Capsule['id_capsule']."] est bien passé niveau supérieur.", 
						$_SESSION['nom_personnage'], $_SESSION['membre_pseudo'],
						__FILE__, __LINE__, "log", "success");
				$message .= "La capsule ".$Capsule['nom']." vient de passer au niveau supérieur.<br/>";
			}
		}
	}

	if ($message != '')
		$Session->setFlash($message, "success");
}
?>