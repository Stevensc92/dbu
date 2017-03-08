<?php
if($_SERVER['HTTP_HOST'] == 'localhost')
{
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'dbu');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}
else
{
    define('DB_HOST', '***');
    define('DB_NAME', '***');
    define('DB_USER', '***');
    define('DB_PASS', '***');
}

date_default_timezone_set('Europe/Paris');

// Define globale du site
define('ROOTPATH', "http://".$_SERVER['SERVER_NAME']."/dbu_v2/");
define('FORUM', ROOTPATH.'forum/');
define('TITRESITE', "Dragon Ball Universe");
define('LAST_URL', "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

// Define de dossier pour les includes etc
define('TIMTHUMB', ROOTPATH.'timthumb/timthumb.php?src=');
define('JSDIR', ROOTPATH.'includes/js');
define('TOOLTIP', ROOTPATH.'includes/tooltip');
define('FUNCTION_DIR', './includes/function');
define('ARRAY_DIR', './includes/array');
define('BBCODE_DIR', './includes/bbcode/');
define('BACK_DIR', '../');

// Define des capsules
define('CAPS_J', ROOTPATH.'images/capsule/jaune(2).png');
define('CAPS_R', ROOTPATH.'images/capsule/rouge(2).png');
define('CAPS_V', ROOTPATH.'images/capsule/verte(2).png');

// Define page fight limite
define('LIMIT_FIGHT', 5);
define('LIMIT_ROUND_ATQ', 4);
define('LIMIT_ROUND_DEF', 4);
define('LIMIT_ROUND_MAG', 3);
define('LIMIT_ROUND_TOTO_ATQ', 4);

// Define erreur
define('ERR_INTERNE', "Une erreur interne est survenue. Un rapport a été envoyé à l'administrateur.");
define('ERR_IS_NOT_CO', "Vous devez être connecté pour pouvoir accéder à cette page.");
define('ERR_AUTH_VIEW', "Vous ne pouvez accéder à ce topic.");
define('ERR_AUTH_TOPIC', "Vous ne pouvez ajouter de nouveau topic");
define('ERR_CSRF', "Le jeton CSRF est invalide, veuillez recharger la page.");
define('ERR_FORM', "Une erreur s'est produite lors de la soumission du formulaire, veuillez re-essayer.");

// Define erreur log
define('ERR_LOG_CSRF', "Erreur jeton CSRF.");
?>
