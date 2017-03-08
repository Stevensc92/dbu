<?php
/*
 *
 *	Configuration du forum, valeur à récupérer de la base de donnée
 *
 */


$sqlGetConfigForum = $bdd->query("SELECT
										config_nom, config_value
									FROM
										forum_config
									");

$ConfigForum = array();

while ($temp = $sqlGetConfigForum->fetch())
{
	$ConfigForum[$temp['config_nom']] = $temp['config_value'];
}
?>