<?php

function verifAuth($auth_necessaire)
{
	global $bdd;

	$sqlGetAuthMember = $bdd->prepare("SELECT
											rang
										FROM
											site_membres
										WHERE
											id = :id_membre
										");
	$sqlGetAuthMember->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
	$sqlGetAuthMember->execute();

	$AuthMember = $sqlGetAuthMember->fetch();

	$auth_membre = $AuthMember['rang'];

	if ($auth_membre >= $auth_necessaire)
		return true;
	else
		return false;
}

function zCode($texte)
{
	$texte = htmlspecialchars($texte);
	$texte = nl2br($texte);
	return $texte;
}
?>