<?php
session_start();
include_once('../config/connexion_sql.php');
include_once('header.php');
include_once('session.class.php');

$Session = new Session();

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
{
	$req = $bdd->prepare('SELECT id, membres_recompense, membres_recompense_affile FROM membres Where id = :membres_id');
	$req->bindValue(':membres_id',$_SESSION['id'],PDO::PARAM_INT);
	$req->execute();
	$data = $req->fetch();

	if($data['membres_recompense'] == 0)
	{
		$zenie = 5000;
		$jour_co = intval($data['membres_recompense_affile']);
		$recompense = $zenie + (500 * $jour_co);

		$Session->setFlash('Bonjour ' .$_SESSION['pseudo']. '<br /> Suite à votre connexion journalière nous vous offrons: <br /> zenie : '.$recompense.'z <br /> une autre recompense vous sera donner demain');
		$Session->flash();

		$maj_rec = $bdd->prepare('UPDATE membres SET membres_recompense_affile = membres_recompense_affile +1 WHERE id = :membres_id');
		$maj_rec->bindValue(':membres_id',$_SESSION['id'],PDO::PARAM_INT);
		$maj_rec->execute();

		$ajout_rec = $bdd->prepare('UPDATE membres SET berrys = berrys + :zenie WHERE id = :membres_id');
		$ajout_rec->bindValue(':membres_id',$_SESSION['id'],PDO::PARAM_INT);
		$ajout_rec->bindValue(':zenie',$recompense,PDO::PARAM_INT);
		$ajout_rec->execute();

		$rec_deja_donner = $bdd->prepare('UPDATE membres SET membres_recompense = 1 WHERE id = :membres_id');
		$rec_deja_donner->bindValue(':membres_id',$_SESSION['id'],PDO::PARAM_INT);
		$rec_deja_donner->execute();
	}
}

?>