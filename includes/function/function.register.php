<?php
/*
 *
 * Fichier function.register.php
 * Fonctions relatives pour l'inscription des membres
 *
 */


function VerifEmail($email)
{
	global $ArrayEmailInvalid;
	
	$count = count($ArrayEmailInvalid);
	
	foreach ($ArrayEmailInvalid as $key => $value)
		if (preg_match("#".$value."#", $email))
			return false;
	return true;
}

function VerifValidateEmail($email)
{
	global $Session;

	if (!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#is', $email))
		return false;
	else
		return true;
}

function VerifPseudo($pseudo)
{
	if (preg_match("#[<|>|;|/]#", $pseudo))
		return false;
	return true;
}

function MailInscription($mail = "stevensc92@gmail.com", $pseudo = "test", $passe = "test", $passhash = "testnimp")
{
	// Envoie de mail lors de l'inscription pour valider les comptes
	$to = $mail;
	$subject = 'Confirmation inscription :'.TITRESITE;
	$message = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
		<head>
			<title>Inscription sur '.TITRESITE.'</title>
            <style type="text/css">
            .id_result
            {
                font-weight : bold;
            }
            .lien
            {
                text-decoration : underline;
            }
            </style>
		</head>
		<body>
			<span style="color:red; font-style:2em; display:block; text-align:center;"><h1>Inscription réussie sur '.TITRESITE.' !</h1></span>
            <div style="padding:10px; margin:auto; text-indent:20px; font-style:italic;"><br/>
				Nous souhaitons vous remercier de vous être inscrit sur '.TITRESITE.'. Nous faisons un rappel de vos identifiants en cas d\'oubli :<br/>
				-------------------------------------------------------------------<br/>
				Pseudo : <span class="id_result">'.htmlspecialchars($pseudo, ENT_QUOTES).'</span><br/>
				Mot de passe : <span class="id_result">'.htmlspecialchars($passe, ENT_QUOTES).'</span><br/>
				-------------------------------------------------------------------<br/>
				Afin de continuer votre inscription et donc rejoindre la communauté, vous devez valider votre compte en cliquant sur ce <a href="'.ROOTPATH.'?p=valid&amp;pseudo='.htmlspecialchars($pseudo, ENT_QUOTES).'&amp;mdp='.htmlspecialchars($passhash,ENT_QUOTES).'">lien</a>.<br/>
				Si le lien ne fonctionne pas ou a du mal à s\'ouvrir, copier coller cette adresse :<br/>
				<span class="lien">'.ROOTPATH.'?p=valid&amp;pseudo='.htmlspecialchars($pseudo, ENT_QUOTES).'&amp;mdp='.htmlspecialchars($passhash,ENT_QUOTES).'</span><br/>
				Si toute fois vous avez du mal à valider votre compte, veuillez contacter l\'administrateur du site pour plus d\'informations.<br/>		
				Nous vous rappellons que ces identifiants sont personnels, et ne doivent être communiqué à personne.<br/>
				--------------------------------------------------------------------<br/>
				Ceci est un message automatique, merci de ne pas y répondre.<br/>
			</div>
			<center>
				<p>En vous remerciant,
				Le Wembaster de '.TITRESITE.'.<br/>
				<a href="'.ROOTPATH.'">Dragon Ball Universe</a> © 2014 | Tous droits réservées.</p>
			</center>
		</body>
	</html>';
	//headers principaux.
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	//headers supplémentaires
	$headers .= 'From: "Inscription '.TITRESITE.'" <no-reply@dbuniverse.fr>' . "\r\n";
	$headers .= 'Cc: "" <duplicata@dbuniverse.fr>' . "\r\n";
	$mail = mail($to, $subject, $message, $headers); //marche
	if($mail) return true;
	return false;
}

function DeleteMember_MemberPerso($id_membre)
{
	global $bdd;

	$sqlDeleteMemberPerso = $bdd->prepare("DELETE FROM
												jeu_liste_membre_perso
											WHERE
											  	id_membre = :id_membre
												  ");
	$sqlDeleteMemberPerso->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlDeleteMemberPerso->execute();

	if ($sqlDeleteMemberPerso->rowCount() > 0)
	{
		$sqlDeleteMember = $bdd->prepare("DELETE FROM
											site_membres
										  WHERE
										  	id = :id_membre
										");
		$sqlDeleteMember->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
		$sqlDeleteMember->execute();
	}
}

function DeleteMember($id_membre)
{
	global $bdd;

	$sqlDeleteMember = $bdd->prepare("DELETE FROM
										site_membres
									  WHERE
									  	id_membre = :id_membre
									  ");
	$sqlDeleteMember->bindValue('id_membre', $id_membre, PDO::PARAM_INT);
	$sqlDeleteMember->execute();
}
?>