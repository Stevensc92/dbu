<?php
/*
 *
 * Page login.php
 * Require sur la page header.php
 *
 */

if (!is_co())
{
	if (isset($_POST['login']))
	{
		if (isset($_SESSION['time_bloqued']) && (time() < $_SESSION['time_bloqued']))
		{
			$minute = ceil(($_SESSION['time_bloqued']/60)-(time()/60));
			$Session->setFlash('Suites aux nombreuses tentatives de connexion successives échouées, vous êtes bloqué pendant encore '.$minute.' minutes.', 'info');
		}
		else
		{
			if (!empty($_POST['email']) && !empty($_POST['password']))
			{
				if (isset($_SESSION['erreur']) && $_SESSION['erreur'] >= 5)
				{
					if (!isset($_SESSION['time_bloqued']))
					{
						$_SESSION['time_bloqued'] = time() + (60 * 15);
						addLog("L'ip : ".$_SERVER['REMOTE_ADDR']." est bloqué pendant 15 minutes suites à des connexions échoué répétitives
								sous l'email : ".$_POST['email'].".", "", "", __FILE__, __LINE__, "log", "error");
						$Session->setFlash('Suites aux nombreuses tentatives de connexion successives échouées, vous êtes bloqué pendant 15 minutes.');
					}
				}

				if (isset($_SESSION['time_bloqued']) && (time() > $_SESSION['time_bloqued']))
				{
					unset($_SESSION['time_bloqued']);
				}
				$email = $_POST['email'];
				$password = HashPassword($_POST['password']);
				// On commence à vérifier si les deux champs donne un retour à la BDD
				$sqlVerifInfoMember = $bdd->prepare("SELECT 
														COUNT(id) as NbMembre, id, pseudo, mdp, rang,
														 groupe, bannis_raison, valider, ip
													 FROM
													 	site_membres
													 WHERE
													 	mail = :mail && mdp = :mdp
													 ");
				$sqlVerifInfoMember->bindValue('mail', $email, PDO::PARAM_STR);
				$sqlVerifInfoMember->bindValue('mdp', $password, PDO::PARAM_STR);
				$sqlVerifInfoMember->execute();

				$InfoMember = $sqlVerifInfoMember->fetch();

				if ($InfoMember['NbMembre'] == 0)
				{
					$Session->setFlash("L'adresse email ou le mot de passe sont incorrect, veuillez ré-essayer");
					$_SESSION['erreur'] = isset($_SESSION['erreur']) ? $_SESSION['erreur'] + 1 : 1;
				}
				else
				{
					// On check chaque possibilité avant de connecter officiellement le membre
					if ($InfoMember['valider'] == 0)
						$Session->setFlash("Votre compte n'est pas validé. Veuillez vérifier votre boîte spam.", "info");
					else if ($InfoMember['rang'] == 0)
						$Session->setFlash("Votre compte a été bannis pour la raison suivante : <br/> ".$InfoMember['bannis_raison'], "info");
					else
					{
						$Session->setFlash('Connexion réussie !', 'success');
						if ($_SERVER['REMOTE_ADDR'] != $InfoMember['ip'])
							$IpDifferent = "L'ip utilisé pour se connecter (".$_SERVER['REMOTE_ADDR'].") est différente de celle utilisé pour l'inscription (".$InfoMember['ip']."). ";
						else
							$IpDifferent = "";
						addLog("Le membre ".$InfoMember['pseudo']."[".$InfoMember['id']."] vient de se connecter sous l'ip ".$_SERVER['REMOTE_ADDR'].". ".$IpDifferent,
								 "", "", __FILE__, __LINE__, "log", "info");

						unset($_SESSION['erreur']);
						include_once(ARRAY_DIR.'/array.personnage.php');
						$_SESSION['membre_id'] = $InfoMember['id'];
						$_SESSION['membre_pseudo'] = $InfoMember['pseudo'];
						$_SESSION['membre_mdp'] = $InfoMember['mdp'];
						$_SESSION['membre_rang'] = $InfoMember['rang'];
						$_SESSION['last_url'] = isset($_SESSION['last_url']) ? $_SESSION['last_url'] : ROOTPATH;
						$_SESSION['__token'] = generate_Token(35);
						$_SESSION['__token_time'] = time();

						UpdateConnecte("add");
						header("Location: ".ROOTPATH);
					}
				}
			}
			else
			{
				$_SESSION['erreur'] = isset($_SESSION['erreur']) ? $_SESSION['erreur'] + 1 : 1;
				$Session->setFlash('Les deux champs ne sont pas remplis');
			}
		}
	}
}
else
{
	header("Location: index.php");
}
?>