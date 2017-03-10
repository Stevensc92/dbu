<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);
include_once('includes/session.class.php'); // <-- Gestion des erreurs/info du site
include_once('config/config.define.php'); // <-- Différents define du site
include_once('config/config.bdd.php'); // <-- Connexion à la base de donée
include_once(ARRAY_DIR.'/array.personnage.php');
include_once(FUNCTION_DIR.'/function.global.php'); // <-- Fonction global du site
include_once(FUNCTION_DIR.'/function.membre.php'); // <-- Fonction "propre" aux membres

global $ArrayPersonnage;
global $bdd;

$Session = new Session(); // Création de la class de Session dans la variable $Session pour pouvoir stocker les erreurs
if (is_co())
{
	$_SESSION['current_perso'] = getCurrentPerso($_SESSION['membre_id']);
	PasserNiveauCapsule($_SESSION['membre_id'], $_SESSION['current_perso']);
	UpdateConnecte("update");
	up_Token();
	
	$keys = array_keys($_GET);
	if (count($keys) > 0 && ($keys[0] == "p" || $keys[0] == "admin"))
	{
		if (file_exists($keys[0].'/'.$_GET[$keys[0]].'.php'))
		{
			require_once($keys[0].'/'.$_GET[$keys[0]].'.php');
			$_SESSION['last_url'] = LAST_URL;
		}
		else
		{
			require_once('error/404.php');
		}
	}
	else
	{
		include_once('includes/header.php'); // <-- Page header propre à toutes les pages
		$sqlGetNews = $bdd->query("SELECT
										id, membre_pseudo, titre, message, time
									 FROM
									 	site_news
									");
		while ($GetNews = $sqlGetNews->fetch())
		{
			echo '<div class="news">';
				echo '<span class="news-titre">'.$GetNews['titre'].'</span><br/>';
				echo '<span class="news-content">'.ParseZCode($GetNews['message']).'</span>';
			echo '</div>';
		}
	}
}
else if (isset($_GET['p']) && $_GET['p'] == 'valid')
{
	require_once('p/valid.php');
	$_SESSION['last_url'] = LAST_URL;
	include_once('includes/header.php');
}
else
{
	include_once('includes/header.php');
}
$Session->flash(); // Affichage des informations/erreurs à afficher
include_once('includes/footer.php');
?>