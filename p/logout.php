<?php
if (is_co())
{
	if (isset($_GET['token']) && check_Token($_GET['token']))
	{
		UpdateConnecte("delete");
		addLog("Le membre ".$_SESSION['membre_pseudo']."[".$_SESSION['membre_id']."] vient de se déconnecter.",
				"", "", __FILE__,__LINE__, "log", "info");
		$_SESSION = array();
		session_destroy();
		unset($_SESSION);
		header("Location: index.php");
	}
	else
	{
		addLog(ERR_LOG_CSRF, "", "", __FILE__, __LINE__, "admin", "error");
		$Session->setFlash(ERR_CSRF);
		up_Token(0);
		header("Location: ".ROOTPATH);
		exit();
	}
}
else
{
	header("Location: ".ROOTPATH);
}
?>