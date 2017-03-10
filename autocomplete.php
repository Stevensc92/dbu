<?php
if(isset($_GET['query']))
{
	include_once('config/config.define.php'); // <-- Différents define du site
	include_once('config/config.bdd.php'); // <-- Connexion à la base de donée
	include_once(FUNCTION_DIR.'/function.global.php'); // <-- Fonction global du site

	// Mot tapé par l'utilisateur
	$q = htmlentities($_GET['query']);

	$sqlGetPseudo = $bdd->prepare("SELECT
										pseudo
									FROM
										site_membres
									WHERE
										pseudo LIKE %:q
									LIMIT 0, 10
								");
	$sqlGetPseudo->bindValue('q', $q, PDO::PARAM_STR);
	$sqlGetPseudo->execute();

	// On parcourt les résultats de la requête SQL
	while($Pseudo = $sqlGetPseudo->fetch())
	{
	// On ajoute les données dans un tableau
		$pseudo['suggestions'][] = $Pseudo['pseudo'];
	}

	// On renvoie le données au format JSON pour le plugin
	echo json_encode($pseudo);

	addLog($pseudo, "", "", __FILE__, __LINE__, "admin");
}
?>