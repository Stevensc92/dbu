<?php
$titre = (isset($titre)) ? $titre.' : '.TITRESITE : TITRESITE;
if (isset($_POST['login']))
{
	require('p/login.php');
	if (isset($_SESSION['membre_id']))
		$_SESSION['current_perso'] = getCurrentPerso($_SESSION['membre_id']);
}

if (isset($dir_forum) && $dir_forum == true)
{
	$backdir = BACK_DIR;
	$css_forum = '<link rel="stylesheet" type="text/css" media="screen" href="'.ROOTPATH.'css/forum_css.css" />';
	$js_forum = '<script type="text/javascript" src="'.JSDIR.'/function.forum.js"></script>';
}
else
{
	$backdir = "";
	$css_forum = "";
	$js_forum = "";
}

if (is_co())
{
	include_once($backdir.FUNCTION_DIR.'/function.personnage.php');
	$InfosPerso = getInfoPersonnage($_SESSION['membre_id'], $_SESSION['current_perso']);
	$AvatarPerso = getAvatarPerso($_SESSION['membre_id'], $_SESSION['current_perso']);
}

$sqlGetNbOnline = $bdd->query("SELECT
									COUNT(connectes_id) as nbr
								FROM
									site_connectes
							");
$NbOnline = $sqlGetNbOnline->fetch();

$nb_joueur = $NbOnline['nbr'];
$s_joueur_co = ($nb_joueur > 1) ? 's' : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if IE 7 ]>    <html class="ie7 oldie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr"> <!--<![endif]-->

	<head>
		<meta http-equiv="content-language" content="fr" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $titre; ?></title>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ROOTPATH; ?>css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ROOTPATH; ?>css/entypo.css" />
		<?php echo $css_forum; ?>
		<link rel="icon" href="<?php echo ROOTPATH; ?>images/favicon.png" type="image/png" />
		<script type="text/javascript" src="<?php echo JSDIR; ?>/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo JSDIR; ?>/function.notification.js"></script>
		<script type="text/javascript" src="<?php echo JSDIR; ?>/function.checkbox.js"></script>
		<script type="text/javascript" src="<?php echo JSDIR; ?>/function.bbcode.js"></script>
		<script type="text/javascript" src="<?php echo JSDIR; ?>/jquery.autocomplete.min.js"></script>
		<script type="text/javascript" src="<?php echo JSDIR; ?>/function.nextRound.js"></script>
		<?php echo $js_forum; ?>
		<!--<script type="text/javascript" src="<?php echo JSDIR; ?>/tooltip.js"></script>-->

		<!-- T O O L T I P -->
		<link rel="stylesheet" type="text/css" href="<?php echo TOOLTIP; ?>/css/tooltipster.css" />
		<script type="text/javascript" src="<?php echo TOOLTIP; ?>/doc/js/jquery.jgfeed.js"></script>
		<script type="text/javascript" src="<?php echo TOOLTIP; ?>/doc/js/prettify.js"></script>
		<script type="text/javascript" src="<?php echo TOOLTIP; ?>/doc/js/lang-css.js"></script>
		<script type="text/javascript" src="<?php echo TOOLTIP; ?>/doc/js/scripts.js"></script>
		<script type="text/javascript" src="<?php echo TOOLTIP; ?>/js/jquery.tooltipster.js"></script>
		
		<!--[if lt IE 9]>
			<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>
		<header>
			<a href="<?php echo ROOTPATH; ?>"><div id="logo"></div></a>
			<div class="stop-float"></div>
			<?php
			if (is_co())
			{
			?>
				<ul id="menu-top">
					<a href="<?php echo ROOTPATH; ?>?p=infosperso"><li class="lien lien-perso"></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=fight"><li class="lien lien-combat"></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=carte"><li class="lien lien-carte"></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=top"><li class="lien lin-top"></li></a>
				</ul>
				<a href="<?php echo ROOTPATH; ?>?p=logout&amp;token=<?php echo $_SESSION['__token']; ?>"><div class="deconnexion"></div></a>
			<?php
			}
			else
			{
			?>
				<span class="joueur-online-off">Joueur<?php echo $s_joueur_co; ?> connecté<?php echo $s_joueur_co; ?> : <span class="gras"><?php echo $nb_joueur; ?></span></span>
				<div id="form-connexion">
					<form method="post" action="#">
						<input type="email" name="email" id="email" placeholder="email@domain.com" required />
						<input type="password" name="password" id="password" placeholder="password" required />
						<input type="submit" name="login" id="login" value=""/>
					</form>
				</div>
			<?php
			}
			?>
		</header>
		<?php
		if (is_co())
		{
			$sqlGetNbMp = $bdd->prepare("SELECT
											COUNT(mp_id) as NbMP
										FROM
											site_mp
										WHERE
											mp_receveur = :id && mp_lu = '0'
										");
			$sqlGetNbMp->bindValue('id', $_SESSION['membre_id'], PDO::PARAM_INT);
			$sqlGetNbMp->execute();	

			$NbMp = $sqlGetNbMp->fetch();

			if ($NbMp['NbMP'] > 0)
			{

				$s = ($NbMp['NbMP'] > 1) ? "s" : "";
				$x = ($NbMp['NbMP'] > 1) ? "x" : "";

				$alt_title = 'title="'.$NbMp['NbMP'].' nouveau'.$x.' message'.$s.'"';
				$class = "nav-message-new";
			}
			else
			{
				$class = "nav-message";
				$alt_title = "";
			}
		?>
			<nav>
				<ul>
					<a href="<?php echo ROOTPATH; ?>?p=aventure"><li class="nav-aventure"></li></a>
					<a href="<?php echo ROOTPATH; ?>"><li class="lien-nav first-nav nav-accueil"></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=perso"><li class="lien-nav nav-personnage"></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=capsule"><li class="lien-nav nav-capsule"></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=mp"><li class="lien-nav <?php echo $class; ?>" <?php echo $alt_title; ?>></li></a>
					<a href="<?php echo ROOTPATH; ?>?p=option"><li class="lien-nav nav-option"></li></a>
					<a href="<?php echo ROOTPATH; ?>forum/"><li class="lien-nav nav-forum"></li></a>
					<a href="<?php echo ROOTPATH; ?>"><li class="lien-nav nav-team"></li></a>
					<li class="lien-nav joueur-online-on">Joueur<?php echo $s_joueur_co; ?> connecté<?php echo $s_joueur_co; ?> : <span class="gras"><?php echo $nb_joueur; ?></span> </li>
				</ul>
			</nav>
			<div class="stop_float"></div>

			<div id="perso_en_cours">
				<div class="avatar">
					<img 
						src="<?php echo ROOTPATH; ?>timthumb/timthumb.php?src=<?php echo $AvatarPerso; ?>&amp;w=133&amp;h=179&amp;cz=1" 
						style=""
					/>
				</div>

				<span class="nom_personnage">
					<?php
						echo isset($_SESSION['nom_personnage']) ? $_SESSION['nom_personnage'] : $InfosPerso['nom_personnage']; 
					?>
				</span>

				<div class="infos_personnage">
					<ul>
						<li class="level">Niveau : <span class="value"><?php echo $InfosPerso['level']; ?></span></li>
						<hr/>
						<li class="experience">
							<?php
								echo 'Exp : <span class="value">'.NumberFormat($InfosPerso['experience']).'</span>';
								$PourcentExp = getPourcentExp($InfosPerso['experience'], $InfosPerso['level']);
							?>
						</li>
						<li class="barre">
							<span class="barre-experience">
								<span class="progression" style="width: <?php echo $PourcentExp; ?>%">
									<span title="<?php echo $PourcentExp; ?>%" class="percent"></span>
								</span>
							</span>
						</li>
						<hr/>
						<li class="zenis">
							<?php
								$sqlGetZenis = $bdd->prepare("SELECT
																	zenis
																FROM
																	site_membres
																WHERE
																	id = :id_membre
																");
								$sqlGetZenis->bindValue('id_membre', $_SESSION['membre_id'], PDO::PARAM_INT);
								$sqlGetZenis->execute();

								$Zenis = $sqlGetZenis->fetch();
								echo 'Zénis : <span class="value">'.NumberFormat($Zenis['zenis']).'</span>';
							?>
						</li>
					</ul>
				</div>
			</div>
		<?php
		}

		if (isset($decal) && $decal == true)
			$class = ' class="decal"';
		else
			$class = '';
		?>

		<div id="content"<?php echo $class; ?>>
			<?php
			if (!is_co())
			{
			?>
				<div id="inscription">
					<div class="text-inscription">
						<p>
							Rejoignez l'aventure dès maintenant ! Cela vous prendra une minute !<br/><br/>
						</p>
						<form method="post" action="#">
							<ul>
								<li>
									<label for="pseudo">Pseudonyme : </label> <input type="text" name="pseudo" id="pseudo" required placeholder="Pseudonyme" size="23"/>
								</li>

								<li>
									<label for="password">Mot de passe : </label> <input type="password" name="password" id="password" required size="23" />
								</li>

								<li>
									<label for="password_verif">Retaper le mot de passe : </label> <input type="password" name="password_verif" id="password_verif" required size="23" />
								</li>

								<li>
									<label for="email">Adresse mail : </label> <input type="email" name="email" id="email" required placeholder="email@domain.com" size="23" />
								</li>

								<li>
									<input type="submit" name="register" value="Je m'inscris !" />
								</li>
							</ul>
						</form>
						<?php
						if (isset($_POST['register']))
						{
							echo '<p id="text-submit-form">';
								require_once('p/register.php');
								if (isset($erreur))
									echo '<span class="error">'.$erreur.'</span>';
								if (isset($success))
									echo '<span class="success">'.$success.'</span>';
							echo '</p>';
						}
						?>
					</div>
				</div>
			<?php
			}
			?>