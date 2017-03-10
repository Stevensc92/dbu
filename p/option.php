<?php
if (is_co())
{
	$titre = "Option";
	$decal = true;
	include('includes/header.php');
	$id = $_SESSION['membre_id'];
	$sqlRecOption = $bdd->prepare("SELECT
										*
									FROM
										site_membres
									LEFT JOIN
										site_membres_config
									ON
										site_membres_config.id_membre = site_membres.id
									WHERE
										id = :id
								");
	$sqlRecOption->bindValue('id', $id, PDO::PARAM_INT);
	$sqlRecOption->execute();
	$Option = $sqlRecOption->fetch();

	if (isset($_POST['change_gene']))
		echo 'gene';
	?>
	<div id="membre_profil">
		<fieldset>
			<legend>Informations Générales</legend>
			<form method="post" action="">
				<?php
				$disabled = ($Option['pseudo_changed'] == '1') ? "disabled" : "";

				if ($disabled != "") $alt_title = 'title="Vous avez déjà changé votre pseudo."';
				else $alt_title = "";
				?>
				<ul>
					<li><label for="pseudo">Changer de pseudo : </label><input type="text" name="pseudo" id="pseudo" <?php echo $disabled; ?> value="<?php echo $Option['pseudo']; ?>" <?php echo $alt_title; ?>/></li>
					<li><label for="last_pswd">Ancien mot de passe : </label><input type="password" name="last_pswd" id="last_pswd" /></li/>
					<li><label for="new_pswd">Nouveau mot de passe : </label><input type="password" name="new_pswd" id="new_pswd" /></li>
					<li><label for="new_pswd2">Confirmation nouveau mot de passe : </label><input type="password" name="new_pswd2" id="new_pswd2" /></li>
					<li><label for="email">Changer d'email : </label><input type="email" name="email" id="email" value="<?php echo $Option['mail']; ?>" /></li>
					<li><input type="submit" name="change_gene" value="Modifier" /></li>
				</ul>
			</form>
		</fieldset>
		
		<fieldset>
			<legend>Informations Profil</legend>
			<form enctype="multipart/form-data" method="post" action="">
				<ul>
					<li>
						<label for="birthday">Date d'anniversaire : </label><input type="text" name="birthday" id="datepicker" value="<?php echo $Option['naissance']; ?>" />
						<div class="tooltip"><span style="color:white">*</span><span class="t">La date de naissance ne peut être changée si elle est déjà définie.<br/>
						Format : DD/MM/YYYY</span></div>
					</li>
					<li><label>Changer d'avatar : <div class="tooltip"><span style="color:white">*</span><span class="t">Format acceptés : png, jpg, jpeg, gif</span></label></li>
					<li><label for="del_avatar"><input type="checkbox" name="del_avatar" id="del_avatar"/>Supprimer l'avatar actuel</label></li>
					<li><label for="avatar_lien">À partir d'une adresse web : </label><input type="text" name="avatar_lien" id="avatar_lien" /></li>
					<li><label for="avatar_pc">À partir de votre ordinateur : </label><input type="file" name="avatar_pc" id="avatar_pc" /></li>
					<li><input type="hidden" name="MAX_FILE_SIZE" value="76800" /></li>
					<li><input type="submit" name="change_profil" value="Modifier" /></li>
				</ul>
			</form>
		</fieldset>
		
		<fieldset>
			<legend>Informations Forum & Jeu</legend>
				<form method="post" action="">
					<ul>
						<?php
						$tabConfig = array('mail_news' => 'Recevoir un mail lorsqu\'il y a de nouvelles news',
											'mail_mp' => 'Recevoir un mail lorsqu\'un joueur m\'envoi un message privé',
											'mail_forum_topic' => 'Recevoir un mail lorsqu\'une personne répond à mon topic',
											'mp_kill' => 'Recevoir un message privé lorsque je tue une personne',
											'mp_dead' => 'Recevoir un message privé lorsqu\'un de mes personnages est tué',
											'mp_capsule_sell' => 'Recevoir un message privé lorsqu\'une de mes capsule est vendue au dépôt',
											'mp_objet_sell' => 'Recevoir un message privé lorsqu\'un de mes objet est vendu au dépôt',
											'echange' => 'Activer les échanges avec d\'autre joueur',
											'safe_connexion' => 'Activer la connexion sécurisée lorsqu\'un inconnu accède à ton compte'
										);
						
						foreach($Option as $key => $value)
						{
							if(array_key_exists($key, $tabConfig))
							{
								if($value == 1)
								{
									$checked = 'checked = "checked"';
								}
								else
								{
									$checked = '';
								}
								echo '<li>';
									echo '<label for="'.$key.'"><input type="checkbox" name="'.$key.'" id="'.$key.'" '.$checked.'/>';
										echo $tabConfig[$key];
								echo '</li>';
							}
						}
						?>
						<li>
							<label for="textarea">Signature : </label><br/>
							<?php include(BBCODE_DIR.'/header.php'); ?>
							<textarea name="textarea" id="textarea" cols="60" rows="10"><?php echo nl2br($Option['signature']); ?></textarea>
							<?php include(BBCODE_DIR.'/bottom.php'); ?>
						</li>
						<li><input type="submit" name="change_forum_jeu" value="Modifier" /></li> 
					</ul>
				</form>
		</fieldset>
	</div>
	<?php
}
else
{
	header("Location: ".ROOTPATH);
}
?>