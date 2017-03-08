<?php
try
{
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
					PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
					);
	$bdd = new PDO('mysql:host='.DB_HOST.';port=3306;dbname='.DB_NAME, DB_USER, DB_PASS, $options);
	// $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
	die('Erreur : '.$e->getMessage());
}
?>