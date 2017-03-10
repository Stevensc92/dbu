<?php
if (isset($_SESSION['membre_id']))
{
	global $ArrayPersonnage;
	global $bdd;

	$ArrayPersonnage = array(0 => false); // On met la première clef 0 pour avoir un "vrai" tableau

	$sqlGetNamePersonnage = $bdd->query("SELECT
											id_perso, nom_personnage, short_name, icone
										 FROM
										 	jeu_liste_personnage
										 ");

	while($NamePersonnage = $sqlGetNamePersonnage->fetch())
	{
		array_push($ArrayPersonnage, $NamePersonnage);
	}

	$idCurrentPerso = $_SESSION['current_perso'];
	$_SESSION['nom_personnage'] = $ArrayPersonnage[$idCurrentPerso]['nom_personnage'];
}
?>