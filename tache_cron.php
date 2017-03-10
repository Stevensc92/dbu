<?php
session_start();
include_once('../config/connexion_sql.php');
include_once('header.php');
include_once('session.class.php');

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
{
	$req = $bdd->prepare('SELECT id ,membres_recompense, membres_recompense_affile FROM membres');
	$req->execute();
	$data = $req->fetch();

	$count_joueur = $bdd->prepare('SELECT COUNT(id) as nb_joueur FROM membres');
	$count_joueur->execute();
	$count = $count_joueur->fetch();

	while($data['id'] <= $count['nb_joueur'])
	{
		if($data['membres_recompense_affile'] >= 7)
		{
			$trop_de_recompense = $bdd->prepare('UPDATE membres SET membres_recompense_affile = 0');
			$trop_de_recompense->execute();
		}
		
		if($data['membres_recompense'] == 0)
		{
			$pas_jouer_ajourdhui = $bdd->prepare('UPDATE membres SET membres_recompense_affile = 0');
			$pas_jouer_ajourdhui->execute();
		}
		elseif($data['membres_recompense'] == 1)
		{
			$jouer_ajourdhui = $bdd->prepare('UPDATE membres SET membres_recompense = 0');
			$jouer_ajourdhui->execute();
		}
		
    
		$data['id']++;
	}
}
?>