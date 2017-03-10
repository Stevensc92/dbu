-- phpMyAdmin SQL Dump
-- version 4.1.14.8
-- http://www.phpmyadmin.net
--
-- Client :  db506532762.db.1and1.com
-- Généré le :  Ven 10 Mars 2017 à 17:46
-- Version du serveur :  5.1.73-log
-- Version de PHP :  5.4.45-0+deb7u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db506532762`
--

-- --------------------------------------------------------

--
-- Structure de la table `black_list`
--

CREATE TABLE IF NOT EXISTS `black_list` (
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cpt_visite`
--

CREATE TABLE IF NOT EXISTS `cpt_visite` (
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `visite_time` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- --------------------------------------------------------

--
-- Structure de la table `forum_categorie`
--

CREATE TABLE IF NOT EXISTS `forum_categorie` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nom` varchar(30) NOT NULL,
  `cat_ordre` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `forum_categorie`
--

INSERT INTO `forum_categorie` (`cat_id`, `cat_nom`, `cat_ordre`) VALUES
(1, 'Général', 0),
(4, 'Autre', 20),
(7, 'DBU', 10);

-- --------------------------------------------------------

--
-- Structure de la table `forum_config`
--

CREATE TABLE IF NOT EXISTS `forum_config` (
  `config_nom` varchar(200) NOT NULL,
  `config_valeur` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_config`
--

INSERT INTO `forum_config` (`config_nom`, `config_valeur`) VALUES
('avatar_maxsize', '10 000'),
('avatar_maxh', '100 '),
('avatar_maxl', '100 '),
('sign_maxl', '200'),
('auth_bbcode_sign', 'oui'),
('pseudo_maxsize', '15'),
('pseudo_minsize', '3'),
('topic_par_page', '20'),
('post_par_page', '15');

-- --------------------------------------------------------

--
-- Structure de la table `forum_forum`
--

CREATE TABLE IF NOT EXISTS `forum_forum` (
  `forum_id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_cat_id` mediumint(8) NOT NULL,
  `forum_name` varchar(30) NOT NULL,
  `forum_desc` text NOT NULL,
  `forum_ordre` mediumint(8) NOT NULL,
  `forum_last_post_id` int(11) NOT NULL,
  `forum_topic` mediumint(8) NOT NULL,
  `forum_post` mediumint(8) NOT NULL,
  `forum_locked` enum('0','1') NOT NULL,
  `auth_view` tinyint(4) NOT NULL DEFAULT '1',
  `auth_post` tinyint(4) NOT NULL DEFAULT '1',
  `auth_topic` tinyint(4) NOT NULL DEFAULT '1',
  `auth_annonce` tinyint(4) NOT NULL DEFAULT '4',
  `auth_modo` tinyint(4) NOT NULL DEFAULT '4',
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `forum_forum`
--

INSERT INTO `forum_forum` (`forum_id`, `forum_cat_id`, `forum_name`, `forum_desc`, `forum_ordre`, `forum_last_post_id`, `forum_topic`, `forum_post`, `forum_locked`, `auth_view`, `auth_post`, `auth_topic`, `auth_annonce`, `auth_modo`) VALUES
(1, 1, 'Nouveautés', 'Toutes les nouveautés concernant le développement du jeu seront postées ici.', 90, 85, 4, 2, '0', 0, 4, 4, 4, 4),
(2, 4, 'Jeux vidéos', 'Vous avez découvert un nouveau jeu ? Vous voulez savoir l''avis d''autre joueur ?\r\nVenez ici pour parler en rapport aux jeux vidéos :)', 1, 75, 1, 2, '0', 1, 1, 1, 4, 4),
(3, 1, 'Présentation', 'Vous êtes nouveau ? Venez vous présenter ;)', 80, 28, 2, 0, '0', 1, 1, 1, 4, 4),
(4, 1, 'Bugs', 'Vous êtes victimes d''un bug ? D''une erreur de frappe ? Venez nous le signaler ;)', 30, 22, 5, 0, '0', 1, 1, 1, 4, 4),
(5, 1, 'Idées', 'Vous voulez nous faire partager vos idées ? Faites-le ici ;)', 40, 79, 3, -1, '0', 1, 1, 1, 4, 4),
(7, 7, 'Aide', 'Vous êtes bloquer dans le mode histoire ? Vous ne savez pas comment monter votre personnage ? Venez poser toutes vos questions ici !', 0, 0, 0, 0, '0', 1, 1, 1, 4, 4),
(8, 1, 'Discussions nouveautés', 'Ce forum aura pour but de discuter sur toutes les nouveautés présentés dans le forum adéquat.', 90, 0, 0, 0, '0', 1, 1, 1, 4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `forum_post`
--

CREATE TABLE IF NOT EXISTS `forum_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_createur` int(11) NOT NULL,
  `post_texte` text NOT NULL,
  `post_time` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_forum_id` int(11) NOT NULL,
  `post_edit` enum('0','1') NOT NULL,
  `post_edit_time` int(11) NOT NULL,
  `post_edit_pseudo` int(11) NOT NULL,
  `post_edit_affich` enum('0','1') NOT NULL DEFAULT '0',
  `post_edit_raison` varchar(64) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=86 ;

--
-- Contenu de la table `forum_post`
--

INSERT INTO `forum_post` (`post_id`, `post_createur`, `post_texte`, `post_time`, `topic_id`, `post_forum_id`, `post_edit`, `post_edit_time`, `post_edit_pseudo`, `post_edit_affich`, `post_edit_raison`) VALUES
(2, 2, ' ', 1417123619, 2, 3, '1', 1420887550, 3, '0', ''),
(3, 2, 'Bon voila l''un de mes premiers bugs, impossible de cocher les personnages d''un joueurs pour le combattre.', 1417123752, 3, 4, '0', 0, 0, '0', ''),
(4, 3, 'Salut à toi Snake et merci de t''être présenté ! :)\r\n\r\nPour les quelques bugs, pourrais-tu faire un rapport dans le forum adéquat ? Si c''est pas rapport à l''affichage, je pense que ça touche tout le monde. J''essaie de voir ça plus en profondeur', 1417124112, 2, 3, '0', 0, 0, '0', ''),
(5, 3, 'Salut ! Aussi oui ! Dû au problème du menu qui est complètement décalé, la sélection se rend donc impossible, pour résoudre le problème le seul moyen que j''ai trouvé actuellement (en attendant que je répare le soucis) est de sélectionner avec la touche "Tab".', 1417124198, 3, 4, '0', 0, 0, '0', ''),
(6, 2, 'Ah d''accord, je comprend mieux du coup.\r\nEn effet, faire "Tab" puis "Espace" une fois sur le carré permet de la cocher, merci !', 1417124420, 3, 4, '0', 0, 0, '0', ''),
(7, 3, 'Le soucis est réglé ! Tu peux dorénavant sélectionner les fight avec ta souris :)', 1417124529, 3, 4, '0', 0, 0, '0', ''),
(8, 2, 'J''ai remarquer merci bien pour ta rapidité ! tu peux Bloquer le sujet je présume, si j''en vois d''autres, je te préviens :) \r\nEn espérant qu''il n''y en ai plus :P\r\n\r\nPetit Bug d''affichage une fois un combat valider pour voir le combat.', 1417124999, 3, 4, '0', 0, 0, '0', ''),
(9, 5, 'Salut,\r\n\r\nBug lors de l’ajout des pts en magie, j''ai même pas les 6 pts en +\r\n\r\n[img]http://i59.servimg.com/u/f59/11/92/98/97/tortu10.jpg[/img]', 1417125068, 4, 4, '0', 0, 0, '0', ''),
(10, 3, 'Exacte, un oubli de ma part lors de l''insertion, qui comme par hasard était uniquement pour les magies ! Je t''ai ajouté tes points ;)\r\n\r\nPS : La magie ne sert pas trop à grand chose actuellement, le coté "magique" des combats n''a pas encore été traité.', 1417125529, 4, 4, '1', 1417125750, 3, '0', ''),
(11, 5, 'Oui, c''est bon. Merci :)', 1417125716, 4, 4, '0', 0, 0, '0', ''),
(12, 5, ' J''ai 2 magies (je précise que j''ai capsule de magie) sur tortue géniale. Je ne fait aucun dégâts magie.', 1417126849, 5, 4, '0', 0, 0, '0', ''),
(13, 4, 'Juste un petit point je sais pas si c''est normal\r\nles Capsule ne gagne pas en expérience ', 1417126900, 6, 4, '0', 0, 0, '0', ''),
(14, 3, 'Actuellement je n''ai pas encore fait ce système, c''est dans la todo liste, ça devrait sûrement arriver demain si j''ai le temps, sinon il faudra attendre à la fin du week-end :/\r\n\r\n[i][b]Edit[/b][/i] : L''expérience des capsules viendra dimanche, par manque de temps je ne peux pas créer tout le système et y apporté toutes les modifications nécessaire aux bon fonctionnement des changements de statistiques.', 1417126957, 6, 4, '1', 1417180016, 3, '1', ''),
(15, 3, 'Yep, j''avais édité mon message juste après que tu ai répondu sur ton autre sujet : "La magie ne sert pas trop à grand chose actuellement, le coté "magique" des combats n''a pas encore été traité."\r\n\r\nLes attaques magiques seront faites durant la semaine prochaine, étant tout seul, les gifs sont parfois long à faire ^^', 1417127041, 5, 4, '0', 0, 0, '0', ''),
(16, 3, 'Si le bug d''affichage était par rapport aux deux images qui n''était pas affiché, ça a été résolu :)', 1417127306, 3, 4, '0', 0, 0, '0', ''),
(17, 2, 'Ma question, dans l''infos Persos les "Fight(s) en attente" il s''agit de nos fight reçus ou ceux qu''on a envoyer ? Si c''est ceux qu''on a reçus bien que j''en ai reçus je reste à 0 sur chaque personnages dans l''infos perso', 1417127474, 7, 4, '0', 0, 0, '0', ''),
(19, 3, 'Exact merci, je corrige ça une fois disponible\r\n\r\nEdit : C''est réparé ! :)', 1417127867, 7, 4, '1', 1417129117, 3, '1', ''),
(20, 4, '  ', 1417128439, 9, 3, '1', 1420887558, 3, '0', ''),
(21, 2, 'Bienvenue mon coco ! :D', 1417129132, 9, 3, '0', 0, 0, '0', ''),
(22, 2, 'J''ai vue ça, merci ! :)', 1417129184, 7, 4, '0', 0, 0, '0', ''),
(23, 3, 'Bienvenue à toi Johnny ! :)\r\n\r\nPourrais-tu me dire comment tu as connu mon jeu ? :)', 1417129188, 9, 3, '0', 0, 0, '0', ''),
(24, 4, 'Bah c''est damien qui m''en a parler. \r\net d''ailleur il n''est plus dans le projet ?', 1417129713, 9, 3, '0', 0, 0, '0', ''),
(25, 3, 'Disons que je n''ai pas de nouvelles de lui, excepté il y a quelques jours lorsque j''ai créé la page Facebook.', 1417129859, 9, 3, '0', 0, 0, '0', ''),
(26, 4, 'Ah d''accord , moi ma dernier news de lui c''est quand jlui est demander un conseil sur skype pour du php, jl''ai plus jamais revu x) \r\nmaintenant je men sort plutot bien sauf quelque diffilculté comme pour tout le monde :)', 1417129930, 9, 3, '0', 0, 0, '0', ''),
(27, 3, 'Ça doit dater alors ^^\r\nTout le monde a des moments de difficultés, comme moi ;)', 1417130045, 9, 3, '0', 0, 0, '0', ''),
(28, 4, 'Si ta besoin d''aide, hesite pas ^^\r\njuste que je code pas en PDO\r\nj''utilise du procedural , le code est plus beau comme ca ^^', 1417130261, 9, 3, '0', 0, 0, '0', ''),
(30, 2, 'Bon voilà une petite suggestions,  il serais bien que dans la liste des combats on n''y vois qu''uniquement les joueurs étant en ligne, pour éviter de fight de inutilement Xfois le même joueurs pour rien.', 1417173104, 11, 5, '0', 0, 0, '0', ''),
(31, 3, 'J''y ai pensé oui, ça viendra en même temps que l''expérience des capsules :)', 1417173220, 11, 5, '0', 0, 0, '0', ''),
(32, 2, 'Parfait,  bonne nouvelle alors ! :)', 1417173401, 11, 5, '0', 0, 0, '0', ''),
(33, 3, 'C''est ajouté ;), j''ai également ajouté le nombre de joueurs en ligne en bas du menu :)', 1417179928, 11, 5, '0', 0, 0, '0', ''),
(43, 3, 'Bonsoir à tous ! Hier a eu le lancement de la bêta test, c''est bien mais, il faudrait néanmoins apporter des informations sur ses fonctionnalités non ? :)\r\n\r\nVoici donc l''ensemble des fonctionnalités ainsi que des informations sur les statistiques et autre présente dans cette bêta et celle qui arriveront au fur et à mesure.\r\n\r\nSommaire :\r\n[url=http://www.dbuniverse.fr/forum/?view=topic&t=13#p_44]Les caractéristiques[/url]\r\n[url=http://www.dbuniverse.fr/forum/?view=topic&t=13#p_45]Les combats[/url]\r\n[url=http://www.dbuniverse.fr/forum/?view=topic&t=13#p_46]Les capsules[/url]\r\n[url=http://www.dbuniverse.fr/forum/?view=topic&t=13#p_47]Fonctionnalités à venir[/url]', 1417193777, 13, 1, '1', 1417195719, 3, '0', ''),
(49, 10, 'Bonsoir, c''est juste une idée pour que ce soit plus clair, mais je trouve el cadre "perso en cours" trop à gauche, il faudrait je pense le mettre plus à droite bien collé car là ça empiète un peu sur le forum ou la page Persos, donc c''est simplement par soucis de lisibilité^^', 1417195877, 14, 5, '0', 0, 0, '0', ''),
(50, 3, 'Oui, vu que j''ai travaillé sur ma résolution qui est relativement grande ([url=http://dbuniverse.fr/img/impr.png]http://dbuniverse.fr/img/impr.png[/url])\r\n\r\nJe bosserais sur les diverses résolutions afin d''avoir un meilleur rendu sur plusieurs résolutions.', 1417196031, 14, 5, '1', 1417196073, 3, '0', ''),
(52, 20, 'Pourquoi on peut combattre des  personnages adversaire qui sont sur d''autre case alors que nous y somme pas\r\n\r\nD''apres l''anime la maison Kame House n''est pas accoté de Satan city.\r\nalors expliquer moi comment ils font pour deviner qu''il y a un adversaire a cette distance\r\nc''est pas des super heroes non plus \r\nou peut etre que ils peuvent sentir le ki des personnage faible comme des level 1 \r\nou ils ont des radars à Ki ultra performant\r\n\r\nMais maintenant expliqué moi comment ils font pour combattre a une si grande distance en étant au corps à corps (je suis ok pour les mages)\r\nSANS SE DEPLACER !\r\nils pourraient au moins faire l''effort de se déplacer ...\r\n', 1417224921, 16, 5, '0', 0, 0, '0', ''),
(53, 3, 'Effectivement ça peut être une bonne idée d''avoir seulement les joueurs présents sur les cases actuelles, mais pour le moment je laisse comme c''est étant donné qu''il y a pas énormément de joueur inscrit.\r\nCependant tu m''as donné une autre idée à ajouter avec la tienne ;)', 1417259115, 16, 5, '0', 0, 0, '0', ''),
(54, 2, 'Pour moi le système tels qu''il l''est actuellement est bien mieux, devoir changer de case pour défier des joueurs risquerais d''être assez "chiant et lassant" si j''ai bien compris ta proposition ^^', 1417259430, 16, 5, '0', 0, 0, '0', ''),
(74, 11, 'Hello tous le monde!\r\nQuelqu''un ici à la PS4 ? Votre ID ?\r\nA quel jeux jouez-vous en ce moment ? \r\n\r\nMon ID est x_Fuzion-D, en ce moment je joue beaucoup à FIFA 15 & AC Unity. ', 1417347647, 21, 2, '0', 0, 0, '0', ''),
(75, 4, 'Moi j''ai la ps4\r\npsn : Johnny_jok\r\n\r\nje joue a call of duty: Advanced Warfare et GTA V ', 1417348959, 21, 2, '0', 0, 0, '0', ''),
(76, 5, 'Perso, sa va poser quand même un problème, tes sur une casse si il a personne comment tu fait ? Tu va passer ton temps à chercher chaque casse à chaque fois que ta plus de combat ?\r\n\r\nC''est aussi bien comme sa.', 1417352033, 16, 5, '0', 0, 0, '0', ''),
(77, 10, 'entièrement d''accord, on est déjà peu, si nous sommes une centaines de joueurs je dirai pas non, mais quand on est a peine 10 c''est bien trop peu avec 3personnages seulement ', 1417352356, 16, 5, '0', 0, 0, '0', ''),
(78, 3, 'Alors, en soit actuellement l''idée est prise en compte, mais dans ce contexte là ne sera pas mise en place tout de suite, peu de joueurs présent donc peu de personnage à combattre. Mais j''ai pensé à une alternative, avoir un nombre approximatif de personnage présent sur la case au survol de celle-ci.', 1417353191, 16, 5, '0', 0, 0, '0', ''),
(79, 11, 'Voir par "zone" plutôt non ? A voir quand on sera plus nombreux mais à la case ça serait beaucoup trop chiant, mais pourquoi pas "découpé" la carte en 4 à 8 partie. A voir :)', 1417353276, 16, 5, '0', 0, 0, '0', ''),
(80, 3, 'Salut à tous :)\r\nJe suis actuellement en train de bosser calmement et proprement sur un nouveau design, en espérant que celui-ci ne présentera pas de problème quelconque d''affichage (pour l''instant il n''y en a aucun).\r\n\r\nCelui-ci viendra avec tout plein de nouvelles chose, et forcément, ça ne viendra pas tout de suite !\r\n\r\nJe vous laisserai découvrir les nouvelles choses lorsque ce sera finalisé :)\r\n', 1417522008, 22, 1, '0', 0, 0, '0', ''),
(82, 40, 'Cherche jeune pamplemousse à élever. \r\n\r\n[b]gras[/b]\r\n[i]italique[/i]\r\n[u]souliner[/u]\r\n[s]barre[/s]\r\n[url=sex.com]je cherche jeune femme de 23cm entre les jambes[/url]\r\n[url=www.sex.com]je cherche jeune femme de 23cm entre les jambes[/url]\r\n[url=http://www.sex.com]je cherche jeune femme de 23cm entre les jambes[/url]\r\n[img]KOUKOU C PA 1 IMG[/img]\r\n[img]http://dbuniverse.fr/includes/timthumb.php?src=http://dbuniverse.fr/images/jeu_avatar/bulma/01_03.jpg&w=104&h=154&cz=1[/img]\r\n[quote nom=Jean-Paul ; camionneur à temps perdu]Je baise violamment contre le mur de la salle de bain [/quote]\r\n[spoiler nom=Moi]Choqué par une telle révélation, Abraham s''est défoulé sur l''homme au mulet, lui offrant 3 énormes coups de poing au visage, avant de le laisser s''écraser sur le sol.[/spoiler]', 1417560042, 24, 80, '0', 0, 0, '0', ''),
(85, 3, 'Bonsoir à tous ! Les vacances ayant commencés pour certain et vont commencer pour d''autre demain, moi je suis encore en vacance.. Mais cependant, je ne pourrais pas trop trop travailler sur DBU. Au vu du travail que j''ai à faire, les nouveautés n''arriveront pas aujourd''hui, moi qui voulait déployer la MàJ avant les vacances, c''est donc loupé !\r\n\r\nJe tiens à rajouter que je pense vraiment sortir les nouveautés toutes d''un coup, du moins celle que j''ai en tête, donc il faudra attendre sagement après les vacances (je ne sais pas combien de temps après) la sortie de celle-ci, à savoir que la partie pour le mode histoire (écrit) avance petit à petit, et n''arrivera pas d''aussi tôt.\r\n\r\nLors de la sortie des nouveautés, je ne ferais pas de reset, cependant, il y aura un reset quand la version officielle sortira (ça va de soit).\r\n\r\nEn attendant, je vais vous demander d''être patient ;) (c''est dur pour moi aussi car j''aimerais bien sortir la V officielle d''un claquement de doigt :)), si vous avez des idées à partager que vous aimeriez bien voir apparaître, rendez-vous dans la section des idées, je suis ouvert à tout ;)', 1418941309, 23, 1, '0', 0, 0, '0', '');

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic`
--

CREATE TABLE IF NOT EXISTS `forum_topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) NOT NULL,
  `topic_titre` char(60) NOT NULL,
  `topic_createur` int(11) NOT NULL,
  `topic_vu` mediumint(8) NOT NULL,
  `topic_time` int(11) NOT NULL,
  `topic_genre` varchar(30) NOT NULL,
  `topic_last_post` int(11) NOT NULL,
  `topic_first_post` int(11) NOT NULL,
  `topic_post` mediumint(8) NOT NULL,
  `topic_locked` enum('0','1') NOT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `topic_last_post` (`topic_last_post`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Contenu de la table `forum_topic`
--

INSERT INTO `forum_topic` (`topic_id`, `forum_id`, `topic_titre`, `topic_createur`, `topic_vu`, `topic_time`, `topic_genre`, `topic_last_post`, `topic_first_post`, `topic_post`, `topic_locked`) VALUES
(1, 1, 'Sortie de la bêta test', 3, 86, 1417090777, 'Message', 0, 3, -1, '0'),
(2, 3, 'Présentation Snake_Eater', 2, 64, 1417123619, 'Message', 4, 2, 1, '0'),
(3, 4, 'Sélection combat impossible ?', 2, 34, 1417123752, 'Message', 16, 0, 5, '0'),
(4, 4, 'Pts Magie', 5, 32, 1417125068, 'Message', 11, 9, 2, '0'),
(5, 4, 'Dégâts Magi', 5, 21, 1417126849, 'Message', 15, 12, 1, '0'),
(6, 4, 'exp caps', 4, 19, 1417126900, 'Message', 14, 13, 1, '0'),
(7, 4, 'Fight(s) en attente ?', 2, 31, 1417127474, 'Message', 22, 17, 2, '0'),
(9, 3, 'Présentation Johnny_jok', 4, 40, 1417128439, 'Message', 28, 20, 7, '0'),
(11, 5, 'combat uniquement joueurde en ligne', 2, 37, 1417173104, 'Message', 33, 30, 3, '0'),
(13, 1, 'Informations sur la bêta test', 3, 89, 1417193777, 'Annonce', 43, 43, 0, '1'),
(14, 5, 'cadre "perso en cours"', 10, 23, 1417195877, 'Message', 50, 49, 1, '0'),
(16, 5, 'Ilogisme', 20, 44, 1417224921, 'Message', 79, 52, 6, '0'),
(21, 2, 'PS4', 11, 17, 1417347647, 'Message', 75, 74, 1, '0'),
(22, 1, 'Nouveautés à venir', 3, 66, 1417522008, 'Annonce', 80, 80, 0, '0'),
(23, 1, 'Vacances ? :O', 3, 28, 1418941309, 'Annonce', 85, 85, 0, '0');

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic_view`
--

CREATE TABLE IF NOT EXISTS `forum_topic_view` (
  `tv_id` int(11) NOT NULL,
  `tv_topic_id` int(11) NOT NULL,
  `tv_forum_id` int(11) NOT NULL,
  `tv_post_id` int(11) NOT NULL,
  `tv_poste` enum('0','1') NOT NULL,
  PRIMARY KEY (`tv_id`,`tv_topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_topic_view`
--

INSERT INTO `forum_topic_view` (`tv_id`, `tv_topic_id`, `tv_forum_id`, `tv_post_id`, `tv_poste`) VALUES
(3, 1, 1, 0, '0'),
(2, 2, 3, 4, '1'),
(5, 1, 1, 48, '0'),
(2, 1, 1, 48, '0'),
(2, 3, 4, 16, '1'),
(3, 2, 3, 4, '1'),
(3, 3, 4, 16, '1'),
(5, 3, 4, 16, '0'),
(8, 1, 1, 3, '0'),
(5, 4, 4, 11, '1'),
(2, 4, 4, 11, '0'),
(3, 4, 4, 11, '1'),
(5, 2, 3, 4, '0'),
(5, 5, 4, 15, '1'),
(2, 5, 4, 15, '0'),
(4, 6, 4, 14, '1'),
(3, 5, 4, 15, '1'),
(3, 6, 4, 14, '1'),
(4, 5, 4, 15, '0'),
(4, 4, 4, 11, '0'),
(4, 3, 4, 16, '0'),
(5, 6, 4, 14, '0'),
(4, 1, 1, 48, '1'),
(2, 6, 4, 14, '0'),
(2, 7, 4, 22, '1'),
(2, 8, 4, 18, '1'),
(3, 7, 4, 22, '1'),
(3, 8, 4, 18, '0'),
(4, 7, 4, 22, '0'),
(4, 2, 3, 4, '0'),
(4, 9, 3, 28, '1'),
(3, 9, 3, 28, '1'),
(2, 9, 3, 28, '1'),
(10, 1, 1, 48, '0'),
(10, 5, 4, 15, '0'),
(10, 6, 4, 14, '0'),
(10, 4, 4, 11, '0'),
(10, 3, 4, 16, '0'),
(10, 7, 4, 22, '0'),
(10, 9, 3, 28, '0'),
(10, 2, 3, 4, '0'),
(11, 1, 1, 48, '0'),
(11, 9, 3, 28, '0'),
(11, 10, 3, 58, '1'),
(11, 7, 4, 22, '0'),
(11, 6, 4, 14, '0'),
(11, 2, 3, 4, '0'),
(3, 10, 3, 58, '1'),
(5, 7, 4, 22, '0'),
(5, 9, 3, 28, '0'),
(5, 10, 3, 58, '0'),
(2, 10, 3, 58, '1'),
(4, 10, 3, 58, '0'),
(14, 1, 1, 3, '0'),
(2, 11, 5, 37, '1'),
(3, 11, 5, 37, '1'),
(5, 11, 5, 37, '0'),
(4, 11, 5, 37, '0'),
(11, 11, 5, 37, '0'),
(10, 11, 5, 37, '0'),
(10, 10, 3, 58, '0'),
(9, 1, 1, 48, '0'),
(9, 10, 3, 29, '0'),
(9, 9, 3, 28, '0'),
(9, 2, 3, 4, '0'),
(9, 4, 4, 11, '0'),
(9, 6, 4, 14, '0'),
(9, 5, 4, 15, '0'),
(9, 3, 4, 16, '0'),
(9, 7, 4, 22, '0'),
(10, 12, 3, 41, '1'),
(3, 12, 3, 41, '1'),
(2, 12, 3, 41, '1'),
(15, 11, 5, 37, '0'),
(15, 2, 3, 4, '0'),
(15, 9, 3, 28, '0'),
(15, 1, 1, 42, '1'),
(3, 13, 1, 43, '1'),
(10, 13, 1, 47, '0'),
(15, 7, 4, 22, '0'),
(4, 13, 1, 47, '0'),
(10, 14, 5, 50, '1'),
(3, 14, 5, 50, '1'),
(17, 13, 1, 47, '0'),
(2, 13, 1, 47, '0'),
(4, 14, 5, 50, '0'),
(2, 14, 5, 50, '0'),
(11, 12, 3, 41, '0'),
(19, 13, 1, 47, '0'),
(4, 12, 3, 41, '0'),
(21, 1, 1, 48, '0'),
(21, 12, 3, 41, '0'),
(21, 9, 3, 28, '0'),
(21, 2, 3, 4, '0'),
(21, 10, 3, 58, '0'),
(21, 14, 5, 50, '0'),
(21, 4, 4, 11, '0'),
(5, 13, 1, 47, '0'),
(5, 12, 3, 41, '0'),
(5, 14, 5, 50, '0'),
(25, 1, 1, 48, '0'),
(20, 10, 3, 36, '0'),
(20, 12, 3, 41, '0'),
(20, 2, 3, 4, '0'),
(20, 15, 3, 56, '1'),
(20, 14, 5, 50, '0'),
(20, 6, 4, 14, '0'),
(20, 4, 4, 11, '0'),
(20, 5, 4, 15, '0'),
(20, 3, 4, 16, '0'),
(20, 7, 4, 22, '0'),
(20, 1, 1, 48, '0'),
(20, 16, 5, 54, '1'),
(3, 15, 3, 60, '1'),
(3, 16, 5, 79, '1'),
(5, 16, 5, 79, '1'),
(5, 15, 3, 60, '0'),
(2, 15, 3, 60, '1'),
(2, 16, 5, 79, '1'),
(21, 15, 3, 60, '0'),
(21, 6, 4, 14, '0'),
(21, 5, 4, 15, '0'),
(21, 3, 4, 16, '0'),
(21, 16, 5, 79, '0'),
(21, 13, 1, 47, '0'),
(4, 16, 5, 79, '0'),
(14, 15, 3, 51, '0'),
(14, 12, 3, 41, '0'),
(26, 13, 1, 47, '0'),
(26, 1, 1, 48, '0'),
(10, 16, 5, 79, '1'),
(10, 15, 3, 60, '1'),
(28, 1, 1, 48, '0'),
(10, 17, 4, 57, '1'),
(2, 17, 4, 57, '0'),
(28, 7, 4, 22, '0'),
(3, 17, 4, 57, '0'),
(4, 15, 3, 60, '0'),
(4, 17, 4, 57, '0'),
(11, 15, 3, 60, '1'),
(11, 16, 5, 79, '1'),
(11, 14, 5, 50, '0'),
(11, 17, 4, 57, '0'),
(11, 3, 4, 16, '0'),
(11, 13, 1, 47, '0'),
(15, 13, 1, 47, '0'),
(29, 1, 1, 62, '0'),
(30, 16, 5, 54, '0'),
(29, 11, 5, 37, '0'),
(29, 12, 3, 41, '0'),
(30, 18, 5, 71, '1'),
(29, 18, 5, 71, '0'),
(30, 1, 1, 48, '1'),
(30, 19, 8, 72, '1'),
(10, 18, 5, 73, '1'),
(10, 19, 8, 72, '0'),
(30, 17, 4, 57, '0'),
(30, 20, 4, 65, '1'),
(30, 2, 3, 4, '1'),
(29, 20, 4, 65, '0'),
(30, 15, 3, 60, '0'),
(29, 2, 3, 69, '0'),
(33, 2, 3, 70, '1'),
(10, 20, 4, 65, '0'),
(3, 19, 8, 72, '1'),
(3, 18, 5, 73, '0'),
(3, 20, 4, 65, '0'),
(4, 19, 8, 72, '0'),
(4, 18, 5, 73, '0'),
(4, 20, 4, 65, '0'),
(29, 19, 8, 72, '0'),
(30, 11, 5, 37, '0'),
(30, 13, 1, 47, '0'),
(2, 18, 5, 73, '1'),
(2, 19, 8, 72, '0'),
(11, 19, 8, 72, '0'),
(11, 18, 5, 73, '0'),
(11, 21, 2, 75, '1'),
(2, 21, 2, 75, '0'),
(3, 21, 2, 75, '0'),
(4, 21, 2, 75, '1'),
(10, 21, 2, 75, '0'),
(5, 19, 8, 72, '0'),
(5, 17, 4, 57, '0'),
(5, 18, 5, 73, '0'),
(5, 21, 2, 75, '0'),
(11, 5, 4, 15, '0'),
(39, 1, 1, 48, '0'),
(39, 13, 1, 47, '0'),
(39, 2, 3, 4, '0'),
(39, 9, 3, 28, '0'),
(39, 12, 3, 41, '0'),
(39, 10, 3, 58, '0'),
(39, 15, 3, 60, '0'),
(39, 17, 4, 57, '0'),
(39, 16, 5, 79, '0'),
(21, 21, 2, 75, '0'),
(21, 11, 5, 37, '0'),
(21, 7, 4, 22, '0'),
(21, 17, 4, 57, '0'),
(19, 1, 1, 0, '0'),
(19, 17, 4, 57, '0'),
(19, 7, 4, 22, '0'),
(19, 3, 4, 16, '0'),
(19, 5, 4, 15, '0'),
(19, 6, 4, 14, '0'),
(19, 4, 4, 11, '0'),
(19, 14, 5, 50, '0'),
(19, 16, 5, 79, '0'),
(9, 16, 5, 79, '0'),
(9, 14, 5, 50, '0'),
(9, 17, 4, 57, '0'),
(3, 22, 1, 80, '1'),
(10, 22, 1, 84, '0'),
(19, 22, 1, 80, '1'),
(4, 22, 1, 84, '0'),
(5, 22, 1, 84, '0'),
(2, 22, 1, 84, '0'),
(40, 1, 1, 48, '0'),
(11, 22, 1, 83, '0'),
(40, 21, 2, 75, '0'),
(40, 13, 1, 47, '0'),
(40, 22, 1, 80, '0'),
(40, 10, 3, 58, '0'),
(40, 23, 80, 81, '1'),
(3, 23, 80, 85, '0'),
(4, 23, 80, 85, '0'),
(2, 23, 80, 85, '0'),
(40, 24, 80, 82, '1'),
(3, 24, 80, 82, '0'),
(2, 24, 80, 82, '0'),
(21, 22, 1, 84, '0'),
(9, 22, 1, 84, '0'),
(19, 2, 3, 4, '0'),
(9, 13, 1, 47, '0'),
(10, 23, 1, 85, '0'),
(21, 23, 1, 85, '0'),
(5, 23, 1, 85, '0'),
(19, 23, 1, 85, '0'),
(19, 12, 3, 41, '0'),
(19, 15, 3, 60, '1'),
(19, 10, 3, 58, '0'),
(19, 9, 3, 28, '0'),
(19, 11, 5, 37, '0'),
(44, 17, 4, 57, '0');

-- --------------------------------------------------------

--
-- Structure de la table `jeu_capsule_corp`
--

CREATE TABLE IF NOT EXISTS `jeu_capsule_corp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_capsule` int(11) NOT NULL,
  `capsule_type` int(11) NOT NULL,
  `restant` int(11) NOT NULL,
  `refresh` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3112 ;

--
-- Contenu de la table `jeu_capsule_corp`
--

INSERT INTO `jeu_capsule_corp` (`id`, `id_capsule`, `capsule_type`, `restant`, `refresh`) VALUES
(3101, 41, 2, 20, 1437827031),
(3102, 43, 2, 20, 1437827031),
(3103, 46, 2, 20, 1437827031),
(3104, 12, 1, 60, 1437827031),
(3105, 24, 1, 60, 1437827031),
(3106, 13, 1, 60, 1437827031),
(3107, 20, 1, 60, 1437827031),
(3108, 10, 1, 60, 1437827031),
(3109, 4, 1, 60, 1437827031),
(3110, 22, 1, 60, 1437827031),
(3111, 26, 1, 60, 1437827031);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_level`
--

CREATE TABLE IF NOT EXISTS `jeu_level` (
  `level` int(11) NOT NULL,
  `exp_required` int(100) NOT NULL,
  UNIQUE KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_level`
--

INSERT INTO `jeu_level` (`level`, `exp_required`) VALUES
(1, 0),
(2, 10000),
(3, 41000),
(4, 72000),
(5, 134000),
(6, 211500),
(7, 289000),
(8, 397500),
(9, 521500),
(10, 645500),
(11, 800500),
(12, 971000),
(13, 1141500),
(14, 1343000),
(15, 1560000),
(16, 1777000),
(17, 2025000),
(18, 2288500),
(19, 2552000),
(20, 2846500),
(21, 3156500),
(22, 3466500),
(23, 3807500),
(24, 4164000),
(25, 4520500),
(26, 4908000),
(27, 5311000),
(28, 5714000),
(29, 6148000),
(30, 6597500),
(31, 7047000),
(32, 7527500),
(33, 8023500),
(34, 8519500),
(35, 9046500),
(36, 9589000),
(37, 10131500),
(38, 10705000),
(39, 11294000),
(40, 11883000),
(41, 12503000),
(42, 13138500),
(43, 13774000),
(44, 14440500),
(45, 15122500),
(46, 15804500),
(47, 16517500),
(48, 17246000),
(49, 17974500),
(50, 18734000);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_level_capsule`
--

CREATE TABLE IF NOT EXISTS `jeu_level_capsule` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_type_capsule` int(1) NOT NULL,
  `level` int(11) NOT NULL,
  `exp_require` int(255) NOT NULL,
  `bonus` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `jeu_level_capsule`
--

INSERT INTO `jeu_level_capsule` (`id`, `id_type_capsule`, `level`, `exp_require`, `bonus`) VALUES
(1, 1, 1, 0, 0),
(2, 1, 2, 500, 25),
(3, 2, 1, 0, 0),
(4, 2, 2, 600, 25),
(5, 3, 1, 0, 0),
(6, 3, 2, 1000, 25);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_capsule`
--

CREATE TABLE IF NOT EXISTS `jeu_liste_capsule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('1','2','3') NOT NULL,
  `id_perso_require` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(255) NOT NULL,
  `degat` decimal(65,1) NOT NULL DEFAULT '0.0',
  `puissance` varchar(255) NOT NULL,
  `defense` varchar(255) NOT NULL,
  `magie` varchar(255) NOT NULL,
  `chance` varchar(255) NOT NULL,
  `vitesse` varchar(255) NOT NULL,
  `concentration` varchar(255) NOT NULL,
  `vie` varchar(255) NOT NULL,
  `energie` varchar(255) NOT NULL,
  `prix` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Contenu de la table `jeu_liste_capsule`
--

INSERT INTO `jeu_liste_capsule` (`id`, `type`, `id_perso_require`, `nom`, `degat`, `puissance`, `defense`, `magie`, `chance`, `vitesse`, `concentration`, `vie`, `energie`, `prix`) VALUES
(1, '1', 0, 'Chôshinsui', '0.0', '+10', '+10', '+10', '+10', '+10', '+10', '+500', '+250', 300000),
(2, '1', 0, 'Armure Sayen', '0.0', '+2', '+2', '+2', '0', '-2', '0', '+100', '+50', 2500),
(3, '1', 0, 'Concentration Namek', '0.0', '0', '-2', '+2', '0', '0', '+5', '+100', '+150', 2500),
(4, '1', 0, 'Vitesse accrue ', '0.0', '-2', '0', '0', '0', '+5', '+2', '+250', '0', 2500),
(5, '1', 0, 'Bashôsen', '0.0', '-2', '0', '+2', '+2', '0', '+2', '0', '+250', 2500),
(6, '1', 0, 'Nyo-ibô', '0.0', '+7', '+2', '-2', '+5', '0', '-2', '+250', '+250', 5000),
(7, '1', 0, 'Kinto-un', '0.0', '-2', '+2', '0', '+5', '+7', '0', '+500', '-250', 5000),
(8, '1', 0, 'Manipulation de chance', '0.0', '0', '0', '+5', '+7', '-2', '+2', '-250', '+500', 5000),
(9, '1', 0, 'Haine Sayen', '0.0', '+7', '+2', '-2', '-2', '+2', '+2', '+250', '+250', 5000),
(10, '1', 0, 'Brume démoniaque', '0.0', '-2', '+5', '+5', '+2', '+2', '+7', '-500', '+500', 10000),
(11, '1', 0, 'Carapace des Tortues', '0.0', '+5', '+7', '0', '0', '+2', '-2', '+300', '-250', 10000),
(12, '1', 0, 'Fruit de l''Enfer', '0.0', '+7', '+7', '-5', '+2', '+2', '-2', '+250', '+500', 10000),
(13, '1', 0, 'Sabre ensorcelé ', '0.0', '-5', '-2', '+5', '+7', '+2', '+2', '+250', '+125', 10000),
(14, '1', 0, 'Magie de Babidi', '0.0', '-2', '-5', '+7', '+2', '+2', '+7', '-250', '+300', 20000),
(15, '1', 0, 'Chance titanesque ', '0.0', '+5', '+5', '-7', '+7', '+5', '+5', '+300', '-125', 20000),
(16, '1', 0, 'Boite magique', '0.0', '-5', '0', '+7', '-5', '+7', '+5', '+250', '+125', 20000),
(17, '1', 0, 'Set de Saibaman', '0.0', '+7', '+5', '+7', '-2', '-7', '+5', '+300', '-125', 20000),
(18, '1', 0, 'Danse de l''air', '0.0', '+5', '+7', '-7', '-5', '+7', '-2', '+250', '-125', 40000),
(19, '1', 0, 'Choseisui ', '0.0', '+7', '-5', '+7', '-5', '+7', '7', '+200', '-250', 40000),
(20, '1', 0, 'Tenue de Kaioh', '0.0', '+2', '+7', '-7', '+5', '+2', '-7', '+300', '+125', 40000),
(21, '1', 0, 'Colère suprème', '0.0', '+7', '+2', '-7', '+2', '+5', '-5', '+400', '+125', 40000),
(22, '1', 0, 'Bonbon de Buu', '0.0', '0', '+2', '+7', '0', '+7', '+7', '+400', '+250', 55000),
(23, '1', 0, 'Ceinture d''Hercule', '0.0', '+7', '0', '0', '+7', '0', '+2', '+350', '+175', 55000),
(24, '1', 0, 'Tenue lourde', '0.0', '+2', '+7', '+2', '+5', '+2', '0', '+350', '+175', 55000),
(25, '1', 0, 'Z-Sword', '0.0', '+5', '+5', '+5', '+2', '+5', '0', '+350', '0', 55000),
(26, '1', 0, 'Vœux exaucé  ', '0.0', '+7', '+2', '+7', '+2', '+2', '+2', '+250', '+125', 75000),
(27, '1', 0, 'Épée de Trunks', '0.0', '+2', '+7', '+2', '+5', '+2', '+5', '+350', '+175', 75000),
(28, '1', 0, 'Armure divine', '0.0', '0', '+5', '0', '+5', '+7', '+7', '+500', '0', 75000),
(29, '1', 0, 'Gravité x 100', '0.0', '+7', '+2', '+2', '0', '+7', '+2', '+250', '+125', 75000),
(30, '1', 0, 'Dragon Shenron', '0.0', '+10', '+10', '0', '0', '+10', '+10', '+500', '+250', 100000),
(31, '1', 0, 'Dragon Porunga', '0.0', '+2', '+2', '+10', '+10', '+2', '+2', '+500', '+250', 100000),
(34, '3', 1, 'Chibi Gokû', '0.0', '+10', '+10', '+10', '+10', '+10', '+10', '+500', '+500', 0),
(41, '2', 1, 'KameHameHa', '1.5', '', '', '', '', '', '', '', '250', 1000),
(42, '2', 1, 'Nyoi Bô', '2.0', '', '', '', '', '', '', '', '500', 3000),
(43, '2', 1, 'Poing du Dragon', '3.0', '', '', '', '', '', '', '', '1000', 6000),
(44, '2', 3, 'KameHameHa', '1.5', '', '', '', '', '', '', '', '250', 1000),
(45, '2', 3, 'Namamidabutsu', '2.0', '', '', '', '', '', '', '', '500', 3000),
(46, '2', 3, 'Super KameHameHa', '3.0', '', '', '', '', '', '', '', '1000', 6000),
(47, '2', 2, 'Mitraillette', '1.5', '', '', '', '', '', '', '', '250', 1000),
(48, '2', 2, 'Son Gokû à la rescousse', '2.0', '', '', '', '', '', '', '', '500', 3000),
(49, '2', 2, 'Charme', '3.0', '', '', '', '', '', '', '', '1000', 6000);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_combat`
--

CREATE TABLE IF NOT EXISTS `jeu_liste_combat` (
  `id_combat` int(255) NOT NULL AUTO_INCREMENT,
  `id_membre_attaquant` int(255) NOT NULL,
  `id_perso_attaquant` int(255) NOT NULL,
  `round1_attaquant` varchar(255) NOT NULL,
  `round1_attaquant_chiffre` int(255) NOT NULL,
  `round2_attaquant` varchar(255) NOT NULL,
  `round2_attaquant_chiffre` int(255) NOT NULL,
  `round3_attaquant` varchar(255) NOT NULL,
  `round3_attaquant_chiffre` int(255) NOT NULL,
  `round4_attaquant` varchar(255) NOT NULL,
  `round4_attaquant_chiffre` int(255) NOT NULL,
  `round5_attaquant` varchar(255) NOT NULL,
  `round5_attaquant_chiffre` int(255) NOT NULL,
  `id_membre_defenseur` int(255) NOT NULL,
  `id_perso_defenseur` int(255) NOT NULL,
  `round1_defenseur` varchar(255) NOT NULL,
  `round1_defenseur_chiffre` int(255) NOT NULL,
  `round2_defenseur` varchar(255) NOT NULL,
  `round2_defenseur_chiffre` int(255) NOT NULL,
  `round3_defenseur` varchar(255) NOT NULL,
  `round3_defenseur_chiffre` int(255) NOT NULL,
  `round4_defenseur` varchar(255) NOT NULL,
  `round4_defenseur_chiffre` int(255) NOT NULL,
  `round5_defenseur` varchar(255) NOT NULL,
  `round5_defenseur_chiffre` int(255) NOT NULL,
  `terrain` varchar(10) NOT NULL,
  `etat_fight` enum('0','1','2') NOT NULL COMMENT '0 : en attente ; 1 : refusé ; 2 : accepté',
  `victoire` int(255) NOT NULL DEFAULT '0' COMMENT 'id du membre puis perso vainqueur (id_membre.id_perso)',
  `defaite` int(255) NOT NULL DEFAULT '0' COMMENT 'id du membre puis perso perdant (id_membre.id_perso)',
  `match_nul` int(11) NOT NULL,
  `gain_exp_adv` int(11) NOT NULL DEFAULT '0',
  `gain_zenis_adv` int(11) NOT NULL DEFAULT '0',
  `gain_exp_def` int(11) NOT NULL DEFAULT '0',
  `gain_zenis_def` int(11) NOT NULL DEFAULT '0',
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`id_combat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_membre_capsule`
--

CREATE TABLE IF NOT EXISTS `jeu_liste_membre_capsule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_capsule` int(11) NOT NULL,
  `level_capsule` int(11) NOT NULL,
  `experience` int(255) NOT NULL DEFAULT '0',
  `id_membre` int(11) NOT NULL,
  `id_perso_equipe` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=210 ;

--
-- Contenu de la table `jeu_liste_membre_capsule`
--

INSERT INTO `jeu_liste_membre_capsule` (`id`, `id_capsule`, `level_capsule`, `experience`, `id_membre`, `id_perso_equipe`) VALUES
(1, 2, 0, 0, 2, 0),
(2, 5, 0, 0, 3, 0),
(3, 2, 0, 0, 3, 0),
(4, 2, 0, 0, 3, 0),
(5, 45, 0, 0, 5, 3),
(6, 46, 0, 0, 5, 3),
(7, 42, 0, 0, 2, 0),
(8, 13, 0, 0, 5, 3),
(9, 2, 0, 0, 4, 0),
(10, 2, 0, 0, 4, 0),
(11, 2, 0, 0, 4, 0),
(12, 2, 0, 0, 4, 0),
(13, 2, 0, 0, 4, 0),
(14, 2, 0, 0, 4, 0),
(15, 17, 0, 0, 3, 0),
(16, 2, 0, 0, 2, 0),
(17, 2, 0, 0, 2, 0),
(18, 2, 0, 0, 2, 0),
(19, 2, 0, 0, 2, 0),
(20, 41, 0, 0, 10, 1),
(21, 41, 0, 0, 10, 1),
(22, 17, 0, 0, 10, 0),
(23, 17, 0, 0, 3, 0),
(24, 30, 0, 0, 2, 0),
(25, 30, 0, 0, 4, 1),
(26, 30, 0, 0, 2, 0),
(27, 15, 0, 0, 10, 0),
(28, 9, 0, 0, 10, 0),
(29, 6, 0, 0, 10, 0),
(30, 6, 0, 0, 10, 0),
(31, 46, 0, 0, 13, 0),
(32, 44, 0, 0, 5, 3),
(33, 6, 0, 0, 10, 0),
(34, 6, 0, 0, 9, 0),
(35, 6, 0, 0, 9, 1),
(36, 19, 0, 0, 3, 0),
(37, 19, 0, 0, 3, 0),
(38, 6, 0, 0, 3, 0),
(39, 42, 0, 0, 3, 0),
(40, 17, 0, 0, 3, 0),
(41, 17, 0, 0, 3, 0),
(42, 17, 0, 0, 9, 2),
(43, 42, 0, 0, 15, 0),
(44, 2, 0, 0, 10, 0),
(45, 2, 0, 0, 10, 0),
(46, 2, 0, 0, 10, 0),
(47, 2, 0, 0, 10, 0),
(48, 2, 0, 0, 10, 0),
(49, 2, 0, 0, 10, 0),
(50, 2, 0, 0, 10, 0),
(51, 2, 0, 0, 10, 0),
(52, 17, 0, 0, 10, 2),
(53, 7, 0, 0, 10, 0),
(54, 30, 0, 0, 4, 1),
(55, 30, 0, 0, 4, 0),
(56, 30, 0, 0, 3, 0),
(57, 30, 0, 0, 10, 2),
(58, 30, 0, 0, 2, 0),
(59, 30, 0, 0, 2, 0),
(60, 30, 0, 0, 3, 0),
(61, 30, 0, 0, 4, 1),
(62, 30, 0, 0, 10, 1),
(63, 30, 0, 0, 2, 0),
(64, 30, 0, 0, 4, 1),
(65, 30, 0, 0, 3, 0),
(66, 30, 0, 0, 2, 2),
(67, 30, 0, 0, 10, 1),
(68, 30, 0, 0, 4, 2),
(69, 30, 0, 0, 3, 0),
(70, 30, 0, 0, 3, 0),
(71, 30, 0, 0, 4, 2),
(72, 30, 0, 0, 10, 1),
(73, 30, 0, 0, 2, 0),
(74, 30, 0, 0, 4, 2),
(75, 30, 0, 0, 2, 2),
(76, 49, 0, 0, 19, 2),
(77, 1, 0, 0, 10, 1),
(78, 27, 0, 0, 22, 0),
(79, 6, 0, 0, 9, 2),
(80, 6, 0, 0, 9, 1),
(81, 7, 0, 0, 2, 0),
(82, 30, 0, 0, 4, 2),
(83, 30, 0, 0, 4, 3),
(84, 30, 0, 0, 4, 2),
(85, 30, 0, 0, 4, 3),
(86, 30, 0, 0, 4, 3),
(87, 30, 0, 0, 4, 3),
(88, 30, 0, 0, 4, 3),
(89, 30, 0, 0, 2, 3),
(90, 30, 0, 0, 2, 3),
(91, 30, 0, 0, 2, 2),
(92, 30, 0, 0, 2, 2),
(93, 30, 0, 0, 2, 3),
(94, 30, 0, 0, 2, 3),
(95, 30, 0, 0, 2, 3),
(96, 13, 0, 0, 2, 0),
(97, 43, 0, 0, 10, 1),
(98, 1, 0, 0, 10, 1),
(99, 26, 0, 0, 10, 0),
(100, 12, 0, 0, 9, 3),
(101, 12, 0, 0, 9, 3),
(102, 12, 0, 0, 9, 3),
(103, 12, 0, 0, 9, 3),
(104, 6, 0, 0, 11, 1),
(105, 6, 0, 0, 11, 1),
(106, 6, 0, 0, 11, 1),
(107, 3, 0, 0, 30, 1),
(108, 3, 0, 0, 9, 2),
(109, 17, 0, 0, 31, 0),
(110, 15, 0, 0, 4, 0),
(111, 14, 0, 0, 11, 0),
(112, 30, 0, 0, 9, 1),
(113, 30, 0, 0, 9, 1),
(114, 48, 0, 0, 10, 0),
(115, 45, 0, 0, 10, 0),
(116, 12, 0, 0, 11, 1),
(117, 12, 0, 0, 11, 1),
(118, 31, 0, 0, 10, 0),
(119, 31, 0, 0, 10, 0),
(120, 31, 0, 0, 10, 0),
(121, 31, 0, 0, 10, 0),
(122, 31, 0, 0, 10, 0),
(123, 45, 0, 0, 10, 0),
(124, 45, 0, 0, 10, 0),
(125, 15, 0, 0, 21, 1),
(126, 1, 0, 0, 2, 1),
(127, 1, 0, 0, 2, 2),
(128, 1, 0, 0, 2, 1),
(129, 1, 0, 0, 2, 1),
(130, 1, 0, 0, 2, 1),
(131, 1, 0, 0, 2, 1),
(132, 28, 0, 0, 10, 0),
(133, 45, 0, 0, 17, 3),
(134, 30, 0, 0, 9, 1),
(135, 46, 0, 0, 10, 3),
(136, 46, 0, 0, 10, 3),
(137, 46, 0, 0, 10, 3),
(138, 1, 0, 0, 10, 3),
(139, 1, 0, 0, 10, 3),
(140, 1, 0, 0, 10, 3),
(141, 1, 0, 0, 10, 3),
(142, 1, 0, 0, 10, 3),
(143, 34, 0, 0, 4, 1),
(144, 11, 0, 0, 10, 2),
(145, 8, 0, 0, 10, 0),
(146, 7, 0, 0, 10, 0),
(147, 48, 0, 0, 10, 0),
(148, 6, 0, 0, 11, 0),
(149, 6, 0, 0, 11, 2),
(150, 6, 0, 0, 11, 2),
(151, 6, 0, 0, 11, 2),
(152, 6, 0, 0, 11, 2),
(153, 6, 0, 0, 11, 2),
(154, 23, 0, 0, 10, 0),
(155, 1, 0, 0, 3, 2),
(156, 1, 0, 0, 3, 3),
(157, 1, 0, 0, 3, 1),
(158, 1, 0, 0, 3, 2),
(159, 1, 0, 0, 3, 1),
(160, 1, 0, 0, 3, 3),
(161, 1, 0, 0, 3, 2),
(162, 1, 0, 0, 3, 3),
(163, 1, 0, 0, 3, 1),
(164, 1, 0, 0, 3, 2),
(165, 1, 0, 0, 3, 3),
(166, 1, 0, 0, 3, 1),
(167, 1, 0, 0, 3, 1),
(168, 1, 0, 0, 3, 2),
(169, 1, 0, 0, 3, 3),
(170, 12, 0, 0, 3, 0),
(171, 34, 0, 0, 3, 1),
(172, 20, 0, 0, 10, 2),
(173, 27, 0, 0, 21, 0),
(174, 28, 0, 0, 21, 0),
(175, 18, 0, 0, 10, 2),
(176, 15, 0, 0, 5, 0),
(177, 21, 0, 0, 10, 0),
(178, 15, 0, 0, 10, 0),
(179, 26, 0, 0, 2, 0),
(180, 30, 0, 0, 4, 1),
(181, 15, 0, 0, 2, 0),
(182, 27, 0, 0, 9, 0),
(183, 13, 0, 0, 9, 0),
(184, 4, 0, 0, 10, 0),
(185, 34, 0, 0, 9, 0),
(186, 12, 0, 0, 10, 0),
(187, 15, 0, 0, 2, 0),
(188, 22, 0, 0, 2, 0),
(189, 30, 0, 0, 2, 0),
(190, 21, 0, 0, 4, 0),
(191, 12, 0, 0, 2, 0),
(192, 49, 0, 0, 2, 0),
(193, 18, 0, 0, 10, 0),
(194, 27, 0, 0, 9, 0),
(195, 49, 0, 0, 2, 0),
(196, 48, 0, 0, 2, 0),
(197, 12, 0, 0, 5, 0),
(198, 5, 0, 0, 19, 2),
(199, 2, 0, 0, 46, 1),
(200, 3, 0, 0, 46, 1),
(201, 8, 0, 0, 46, 1),
(202, 7, 0, 0, 46, 0),
(203, 43, 0, 0, 46, 1),
(204, 9, 0, 0, 46, 1),
(205, 6, 0, 0, 46, 1),
(206, 23, 0, 0, 3, 0),
(207, 13, 0, 0, 4, 0),
(208, 19, 0, 0, 5, 0),
(209, 15, 0, 0, 5, 0);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_membre_perso`
--

CREATE TABLE IF NOT EXISTS `jeu_liste_membre_perso` (
  `id_list` int(255) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `id_perso` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `experience` int(100) NOT NULL DEFAULT '0',
  `points_distrib` int(11) NOT NULL DEFAULT '6',
  `x` int(11) NOT NULL DEFAULT '7',
  `y` int(11) NOT NULL DEFAULT '6',
  `stats_puissance` int(11) NOT NULL DEFAULT '10',
  `stats_defense` int(11) NOT NULL DEFAULT '10',
  `stats_magie` int(11) NOT NULL DEFAULT '10',
  `stats_chance` int(11) NOT NULL DEFAULT '10',
  `stats_vitesse` int(11) NOT NULL DEFAULT '10',
  `stats_concentration` int(11) NOT NULL DEFAULT '10',
  `stats_vie` int(11) NOT NULL DEFAULT '1000',
  `stats_energie` int(11) NOT NULL DEFAULT '500',
  `ki` int(100) NOT NULL DEFAULT '1150',
  `caps_verte_1` int(11) NOT NULL,
  `caps_rouge_1` int(11) NOT NULL,
  `caps_rouge_2` int(11) NOT NULL,
  `caps_rouge_3` int(11) NOT NULL,
  `caps_rouge_4` int(11) NOT NULL,
  `caps_jaune_1` int(11) NOT NULL,
  `caps_jaune_2` int(11) NOT NULL,
  `caps_jaune_3` int(11) NOT NULL,
  `caps_jaune_4` int(11) NOT NULL,
  `caps_jaune_5` int(11) NOT NULL,
  `avatar_lien` varchar(255) NOT NULL,
  `match_victoire` int(11) NOT NULL,
  `match_defaite` int(11) NOT NULL,
  `match_tuer` int(11) NOT NULL,
  `match_mort` int(11) NOT NULL,
  `match_nul` int(11) NOT NULL,
  PRIMARY KEY (`id_list`),
  UNIQUE KEY `id_list` (`id_list`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=154 ;

--
-- Contenu de la table `jeu_liste_membre_perso`
--

INSERT INTO `jeu_liste_membre_perso` (`id_list`, `id_membre`, `id_perso`, `level`, `experience`, `points_distrib`, `x`, `y`, `stats_puissance`, `stats_defense`, `stats_magie`, `stats_chance`, `stats_vitesse`, `stats_concentration`, `stats_vie`, `stats_energie`, `ki`, `caps_verte_1`, `caps_rouge_1`, `caps_rouge_2`, `caps_rouge_3`, `caps_rouge_4`, `caps_jaune_1`, `caps_jaune_2`, `caps_jaune_3`, `caps_jaune_4`, `caps_jaune_5`, `avatar_lien`, `match_victoire`, `match_defaite`, `match_tuer`, `match_mort`, `match_nul`) VALUES
(1, 2, 1, 31, 7267041, 0, 4, 5, 396, 210, 210, 210, 210, 210, 18500, 9250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 486, 86, 18, 2, 3),
(2, 2, 2, 24, 4172097, 0, 5, 5, 319, 175, 135, 135, 175, 175, 15000, 7500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 257, 203, 6, 0, 1),
(3, 2, 3, 23, 3860904, 0, 4, 5, 308, 170, 120, 120, 170, 170, 14500, 7250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 225, 238, 5, 0, 2),
(4, 3, 1, 50, 2453845, 0, 5, 5, 1100017663, 1000000001, -999999997, -1000000818, -100014568, -999999568, 2147483647, 13250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 220, 62, 11, 0, 0),
(5, 3, 2, 3, 70895, 0, 4, 5, 88, 70, 70, 70, 70, 70, 4500, 2250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 8, 39, 0, 5, 0),
(6, 3, 3, 5, 142365, 0, 4, 5, 110, 80, 80, 80, 80, 80, 5500, 2750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 8, 59, 4, 4, 0),
(7, 4, 1, 28, 6066130, 1, 4, 5, 2147483647, -2147483441, -2147483491, 155, 205, 205, -2147483648, 2147483647, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 407, 99, 12, 1, 3),
(8, 4, 2, 23, 3975854, 0, 7, 4, 308, 170, 120, 120, 170, 170, 14500, 7250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 258, 180, 6, 0, 1),
(9, 4, 3, 18, 2519776, 0, 7, 7, 253, 145, 95, 95, 145, 145, 12000, 6000, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 112, 287, 1, 0, 2),
(10, 5, 1, 4, 87854, 0, 4, 5, 49, 25, 25, 25, 25, 25, 2500, 1250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 33, 14, 0, 0, 1),
(11, 5, 2, 2, 26523, 0, 4, 5, 27, 15, 15, 15, 15, 15, 1500, 750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 12, 8, 0, 0, 0),
(12, 5, 3, 1, 744, 0, 4, 5, 5, 8, 21, 17, 12, 12, 1250, 2375, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 4, 0, 0, 0),
(13, 6, 1, 1, 1739, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 3, 0, 2, 0),
(14, 6, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(15, 6, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(16, 7, 1, 1, 2834, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 1, 7, 0, 0, 0),
(17, 7, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(18, 7, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(19, 8, 1, 1, 2737, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 1, 7, 0, 0, 0),
(20, 8, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(21, 8, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(22, 9, 1, 10, 673785, 0, 4, 5, 157, 89, 51, 65, 85, 81, 7600, 4005, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 36, 176, 2, 0, 0),
(23, 9, 2, 7, 290794, 6, 7, 6, 90, 45, 47, 43, 33, 48, 4650, 2275, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 9, 101, 0, 12, 0),
(24, 9, 3, 6, 220751, 0, 4, 5, 63, 99, 15, 43, 43, 27, 4500, 3750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 4, 93, 0, 7, 0),
(25, 10, 1, 31, 7116562, 0, 4, 5, 396, 210, 180, 180, 210, 210, 18500, 10750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 424, 270, 27, 1, 0),
(26, 10, 2, 15, 1570308, 0, 7, 6, 136, 176, 73, 78, 94, 84, 9950, 3875, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 48, 272, 1, 0, 0),
(27, 10, 3, 16, 1882509, 0, 4, 5, 153, 135, 213, 135, 135, 135, 11000, 8500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 73, 266, 2, 2, 1),
(28, 11, 1, 5, 182914, 0, 2, 3, 95, 50, 14, 49, 34, 20, 4250, 3250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 13, 106, 0, 26, 0),
(29, 11, 2, 1, 3219, 0, 4, 5, 51, 20, 0, 35, 10, 0, 2250, 1750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 2, 3, 0, 2, 0),
(30, 11, 3, 1, 578, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 1, 0, 0, 0),
(31, 12, 1, 1, 0, 6, 7, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(32, 12, 2, 1, 0, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(33, 12, 3, 1, 0, 0, 7, 6, 10, 10, 10, 16, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(34, 13, 1, 1, 0, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(35, 13, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(36, 13, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(37, 14, 1, 1, 0, 0, 7, 1, 10, 15, 10, 10, 11, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(38, 14, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(39, 14, 3, 1, 0, 6, 2, 10, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(40, 15, 1, 1, 8281, 0, 7, 6, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 3, 15, 0, 1, 0),
(41, 15, 2, 1, 1328, 0, 7, 6, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 6, 0, 0, 0),
(42, 15, 3, 1, 0, 0, 7, 6, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(43, 16, 1, 1, 0, 0, 7, 6, 11, 11, 11, 11, 11, 11, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(44, 16, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(45, 16, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(46, 17, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(47, 17, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(48, 17, 3, 2, 10297, 0, 4, 5, 23, 17, 15, 15, 15, 15, 1700, 1250, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 11, 0, 6, 0),
(49, 18, 1, 1, 877, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 1, 0, 1, 0),
(50, 18, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(51, 18, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(52, 19, 1, 1, 1243, 0, 8, 1, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 1, 0, 1, 0),
(53, 19, 2, 1, 2984, 0, 4, 5, 8, 10, 12, 12, 10, 12, 1000, 1780, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 2, 0, 1, 0),
(54, 19, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(55, 20, 1, 1, 1455, 6, 2, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 2, 0, 1, 0),
(56, 20, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(57, 20, 3, 1, 0, 6, 5, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(58, 21, 1, 1, 484, 6, 7, 1, 15, 15, 3, 17, 15, 15, 1300, 375, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 1, 0, 1, 0),
(59, 21, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(60, 21, 3, 1, 0, 6, 6, 1, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(61, 22, 1, 1, 0, 0, 2, 6, 10, 14, 10, 10, 10, 10, 1200, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(62, 22, 2, 1, 0, 0, 7, 6, 14, 10, 10, 10, 10, 10, 1200, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(63, 22, 3, 1, 0, 0, 5, 4, 10, 10, 14, 10, 10, 10, 1200, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(64, 23, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(65, 23, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(66, 23, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(67, 24, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(68, 24, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(69, 24, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(70, 25, 1, 1, 0, 0, 4, 5, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(71, 25, 2, 1, 0, 6, 7, 7, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(72, 25, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(73, 26, 1, 1, 0, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(74, 26, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(75, 26, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(76, 27, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(77, 27, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(78, 27, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(79, 28, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(80, 28, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(81, 28, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(82, 29, 1, 1, 0, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(83, 29, 2, 1, 1245, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 2, 0, 1, 0),
(84, 29, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(85, 30, 1, 1, 1582, 0, 5, 4, 16, 8, 12, 10, 10, 15, 1100, 650, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 1, 1, 0, 1, 0),
(86, 30, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(87, 30, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(88, 31, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(89, 31, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(90, 31, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(91, 32, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(92, 32, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(93, 32, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(94, 33, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(95, 33, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(96, 33, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(97, 34, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(98, 34, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(99, 34, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(100, 35, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(101, 35, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(102, 35, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(103, 36, 1, 2, 13475, 0, 4, 8, 27, 15, 15, 15, 15, 15, 1500, 750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 2, 17, 0, 11, 0),
(104, 36, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(105, 36, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(106, 37, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(107, 37, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(108, 37, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(109, 38, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(110, 38, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(111, 38, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(112, 39, 1, 1, 1423, 0, 4, 5, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 2, 0, 0, 0),
(113, 39, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(114, 39, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(115, 40, 1, 1, 0, 6, 1, 4, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(116, 40, 2, 1, 6873, 0, 6, 4, 15, 11, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 11, 0, 5, 0),
(117, 40, 3, 1, 887, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 1, 0, 1, 0),
(118, 41, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(119, 41, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(120, 41, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(121, 42, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(122, 42, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(123, 42, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(124, 43, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(125, 43, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(126, 43, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(127, 44, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(128, 44, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(129, 44, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(130, 45, 1, 1, 0, 0, 4, 5, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(131, 45, 2, 1, 0, 0, 7, 6, 10, 10, 10, 10, 10, 10, 1600, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(132, 45, 3, 1, 0, 0, 7, 6, 10, 16, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(133, 46, 1, 1, 0, 0, 4, 5, 91, 73, 53, 52, 25, 47, 5900, 4650, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(134, 46, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(135, 46, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(136, 47, 1, 1, 0, 0, 2, 3, 16, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(137, 47, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(138, 47, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(139, 48, 1, 1, 0, 6, 7, 1, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(140, 48, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(141, 48, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(142, 49, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(143, 49, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(144, 49, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(145, 50, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(146, 50, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(147, 50, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(148, 51, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(149, 51, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(150, 51, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(151, 52, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/chibi_goku/01_03.jpg', 0, 0, 0, 0, 0),
(152, 52, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(153, 52, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_perso_avatar`
--

CREATE TABLE IF NOT EXISTS `jeu_liste_perso_avatar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perso` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `chemin_image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=152 ;

--
-- Contenu de la table `jeu_liste_perso_avatar`
--

INSERT INTO `jeu_liste_perso_avatar` (`id`, `id_perso`, `level`, `chemin_image`) VALUES
(1, 1, 1, '/images/jeu_avatar/chibi_goku/01_03.jpg'),
(2, 1, 2, '/images/jeu_avatar/chibi_goku/01_03.jpg'),
(3, 1, 3, '/images/jeu_avatar/chibi_goku/01_03.jpg'),
(4, 1, 4, '/images/jeu_avatar/chibi_goku/04_06.jpg'),
(5, 1, 5, '/images/jeu_avatar/chibi_goku/04_06.jpg'),
(6, 1, 6, '/images/jeu_avatar/chibi_goku/04_06.jpg'),
(7, 1, 7, '/images/jeu_avatar/chibi_goku/07_09.jpg'),
(8, 1, 8, '/images/jeu_avatar/chibi_goku/07_09.jpg'),
(9, 1, 9, '/images/jeu_avatar/chibi_goku/07_09.jpg'),
(10, 1, 10, '/images/jeu_avatar/chibi_goku/10_12.jpg'),
(11, 1, 11, '/images/jeu_avatar/chibi_goku/10_12.jpg'),
(12, 1, 12, '/images/jeu_avatar/chibi_goku/10_12.jpg'),
(13, 1, 13, '/images/jeu_avatar/chibi_goku/13_15.jpg'),
(14, 1, 14, '/images/jeu_avatar/chibi_goku/13_15.jpg'),
(15, 1, 15, '/images/jeu_avatar/chibi_goku/13_15.jpg'),
(16, 1, 16, '/images/jeu_avatar/chibi_goku/16_18.jpg'),
(17, 1, 17, '/images/jeu_avatar/chibi_goku/16_18.jpg'),
(18, 1, 18, '/images/jeu_avatar/chibi_goku/16_18.jpg'),
(19, 1, 19, '/images/jeu_avatar/chibi_goku/19_21.jpg'),
(20, 1, 20, '/images/jeu_avatar/chibi_goku/19_21.jpg'),
(21, 2, 1, '/images/jeu_avatar/bulma/01_03.jpg'),
(22, 2, 2, '/images/jeu_avatar/bulma/01_03.jpg'),
(23, 2, 3, '/images/jeu_avatar/bulma/01_03.jpg'),
(24, 2, 4, '/images/jeu_avatar/bulma/04_06.jpg'),
(25, 2, 5, '/images/jeu_avatar/bulma/04_06.jpg'),
(26, 2, 6, '/images/jeu_avatar/bulma/04_06.jpg'),
(27, 2, 7, '/images/jeu_avatar/bulma/07_09.jpg'),
(28, 2, 8, '/images/jeu_avatar/bulma/07_09.jpg'),
(29, 2, 9, '/images/jeu_avatar/bulma/07_09.jpg'),
(30, 2, 10, '/images/jeu_avatar/bulma/10_12.jpg'),
(31, 2, 11, '/images/jeu_avatar/bulma/10_12.jpg'),
(32, 2, 12, '/images/jeu_avatar/bulma/10_12.jpg'),
(33, 2, 13, '/images/jeu_avatar/bulma/13_15.jpg'),
(34, 2, 14, '/images/jeu_avatar/bulma/13_15.jpg'),
(35, 2, 15, '/images/jeu_avatar/bulma/13_15.jpg'),
(36, 2, 16, '/images/jeu_avatar/bulma/16_18.jpg'),
(37, 2, 17, '/images/jeu_avatar/bulma/16_18.jpg'),
(38, 2, 18, '/images/jeu_avatar/bulma/16_18.jpg'),
(39, 2, 19, '/images/jeu_avatar/bulma/19_21.jpg'),
(40, 2, 20, '/images/jeu_avatar/bulma/19_21.jpg'),
(41, 3, 1, '/images/jeu_avatar/tortue_geniale/01_03.jpg'),
(42, 3, 2, '/images/jeu_avatar/tortue_geniale/01_03.jpg'),
(43, 3, 3, '/images/jeu_avatar/tortue_geniale/01_03.jpg'),
(44, 3, 4, '/images/jeu_avatar/tortue_geniale/04_06.jpg'),
(45, 3, 5, '/images/jeu_avatar/tortue_geniale/04_06.jpg'),
(46, 3, 6, '/images/jeu_avatar/tortue_geniale/04_06.jpg'),
(47, 3, 7, '/images/jeu_avatar/tortue_geniale/07_09.jpg'),
(48, 3, 8, '/images/jeu_avatar/tortue_geniale/07_09.jpg'),
(49, 3, 9, '/images/jeu_avatar/tortue_geniale/07_09.jpg'),
(50, 3, 10, '/images/jeu_avatar/tortue_geniale/10_12.jpg'),
(51, 3, 11, '/images/jeu_avatar/tortue_geniale/10_12.jpg'),
(52, 3, 12, '/images/jeu_avatar/tortue_geniale/10_12.jpg'),
(53, 3, 13, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(54, 3, 14, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(55, 3, 15, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(56, 3, 16, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(57, 3, 17, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(58, 3, 18, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(59, 3, 19, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(60, 3, 20, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(61, 1, 21, '/images/jeu_avatar/chibi_goku/19_21.jpg'),
(62, 1, 22, '/images/jeu_avatar/chibi_goku/22_24.jpg'),
(63, 1, 23, '/images/jeu_avatar/chibi_goku/22_24.jpg'),
(64, 1, 24, '/images/jeu_avatar/chibi_goku/22_24.jpg'),
(65, 1, 25, '/images/jeu_avatar/chibi_goku/25_27.jpg'),
(66, 1, 26, '/images/jeu_avatar/chibi_goku/25_27.jpg'),
(67, 1, 27, '/images/jeu_avatar/chibi_goku/25_27.jpg'),
(68, 1, 28, '/images/jeu_avatar/chibi_goku/28_30.jpg'),
(69, 1, 29, '/images/jeu_avatar/chibi_goku/28_30.jpg'),
(70, 1, 30, '/images/jeu_avatar/chibi_goku/28_30.jpg'),
(71, 1, 31, '/images/jeu_avatar/chibi_goku/31_33.jpg'),
(72, 1, 32, '/images/jeu_avatar/chibi_goku/31_33.jpg'),
(73, 1, 33, '/images/jeu_avatar/chibi_goku/31_33.jpg'),
(74, 1, 34, '/images/jeu_avatar/chibi_goku/34_36.jpg'),
(75, 1, 35, '/images/jeu_avatar/chibi_goku/34_36.jpg'),
(76, 1, 36, '/images/jeu_avatar/chibi_goku/34_36.jpg'),
(77, 1, 37, '/images/jeu_avatar/chibi_goku/37_39.jpg'),
(78, 1, 38, '/images/jeu_avatar/chibi_goku/37_39.jpg'),
(79, 1, 39, '/images/jeu_avatar/chibi_goku/37_39.jpg'),
(80, 1, 40, '/images/jeu_avatar/chibi_goku/40_42.jpg'),
(81, 1, 41, '/images/jeu_avatar/chibi_goku/40_42.jpg'),
(82, 1, 42, '/images/jeu_avatar/chibi_goku/40_42.jpg'),
(83, 1, 43, '/images/jeu_avatar/chibi_goku/43_45.jpg'),
(84, 1, 44, '/images/jeu_avatar/chibi_goku/43_45.jpg'),
(85, 1, 45, '/images/jeu_avatar/chibi_goku/43_45.jpg'),
(86, 1, 46, '/images/jeu_avatar/chibi_goku/46_48.jpg'),
(87, 1, 47, '/images/jeu_avatar/chibi_goku/46_48.jpg'),
(88, 1, 48, '/images/jeu_avatar/chibi_goku/46_48.jpg'),
(89, 1, 49, '/images/jeu_avatar/chibi_goku/49_51.jpg'),
(90, 1, 50, '/images/jeu_avatar/chibi_goku/49_51.jpg'),
(92, 2, 21, '/images/jeu_avatar/bulma/19_21.jpg'),
(93, 2, 22, '/images/jeu_avatar/bulma/22_24.jpg'),
(94, 2, 23, '/images/jeu_avatar/bulma/22_24.jpg'),
(95, 2, 24, '/images/jeu_avatar/bulma/22_24.jpg'),
(96, 2, 25, '/images/jeu_avatar/bulma/25_27.jpg'),
(97, 2, 26, '/images/jeu_avatar/bulma/25_27.jpg'),
(98, 2, 27, '/images/jeu_avatar/bulma/25_27.jpg'),
(99, 2, 28, '/images/jeu_avatar/bulma/28_30.jpg'),
(100, 2, 29, '/images/jeu_avatar/bulma/28_30.jpg'),
(101, 2, 30, '/images/jeu_avatar/bulma/28_30.jpg'),
(102, 2, 31, '/images/jeu_avatar/bulma/31_33.jpg'),
(103, 2, 32, '/images/jeu_avatar/bulma/31_33.jpg'),
(104, 2, 33, '/images/jeu_avatar/bulma/31_33.jpg'),
(105, 2, 34, '/images/jeu_avatar/bulma/34_36.jpg'),
(106, 2, 35, '/images/jeu_avatar/bulma/34_36.jpg'),
(107, 2, 36, '/images/jeu_avatar/bulma/34_36.jpg'),
(108, 2, 37, '/images/jeu_avatar/bulma/37_39.jpg'),
(109, 2, 38, '/images/jeu_avatar/bulma/37_39.jpg'),
(110, 2, 39, '/images/jeu_avatar/bulma/37_39.jpg'),
(111, 2, 40, '/images/jeu_avatar/bulma/40_42.jpg'),
(112, 2, 41, '/images/jeu_avatar/bulma/40_42.jpg'),
(113, 2, 42, '/images/jeu_avatar/bulma/40_42.jpg'),
(114, 2, 43, '/images/jeu_avatar/bulma/43_45.jpg'),
(115, 2, 44, '/images/jeu_avatar/bulma/43_45.jpg'),
(116, 2, 45, '/images/jeu_avatar/bulma/43_45.jpg'),
(117, 2, 46, '/images/jeu_avatar/bulma/43_45.jpg'),
(118, 2, 47, '/images/jeu_avatar/bulma/43_45.jpg'),
(119, 2, 48, '/images/jeu_avatar/bulma/43_45.jpg'),
(120, 2, 49, '/images/jeu_avatar/bulma/43_45.jpg'),
(121, 2, 50, '/images/jeu_avatar/bulma/43_45.jpg'),
(122, 3, 21, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(123, 3, 22, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(124, 3, 23, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(125, 3, 24, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(126, 3, 25, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(127, 3, 26, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(128, 3, 27, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(129, 3, 28, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(130, 3, 29, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(131, 3, 30, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(132, 3, 31, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(133, 3, 32, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(134, 3, 33, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(135, 3, 34, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(136, 3, 35, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(137, 3, 36, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(138, 3, 37, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(139, 3, 38, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(140, 3, 39, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(141, 3, 40, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(142, 3, 41, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(143, 3, 42, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(144, 3, 43, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(145, 3, 44, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(146, 3, 45, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(147, 3, 46, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(148, 3, 47, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(149, 3, 48, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(150, 3, 49, '/images/jeu_avatar/tortue_geniale/13_15.jpg'),
(151, 3, 50, '/images/jeu_avatar/tortue_geniale/13_15.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_personnage`
--

CREATE TABLE IF NOT EXISTS `jeu_liste_personnage` (
  `id_perso` int(11) NOT NULL AUTO_INCREMENT,
  `nom_personnage` varchar(64) NOT NULL,
  `short_name` varchar(64) NOT NULL,
  `icone` varchar(255) NOT NULL,
  `alternative` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_perso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `jeu_liste_personnage`
--

INSERT INTO `jeu_liste_personnage` (`id_perso`, `nom_personnage`, `short_name`, `icone`, `alternative`) VALUES
(1, 'Chibi Gokû', 'chibi_goku', '/images/jeux_icone_perso/chibi_goku.gif', '0'),
(2, 'Bulma', 'bulma', '/images/jeux_icone_perso/bulma.gif', '0'),
(3, 'Tortue Géniale', 'tortue_geniale', '/images/jeux_icone_perso/tortue_geniale.gif', '0'),
(4, 'Yamcha', '', '', '0'),
(5, 'Plume', '', '', '0'),
(6, 'Chichi', '', '', '0'),
(7, 'Robot Pilaf', '', '', '0'),
(8, 'Robot Maï', '', '', '0'),
(9, 'Robot Shu', '', '', '0'),
(10, 'Robots unis', '', '', '0'),
(11, 'Oozaru Gokû', '', '', '0'),
(12, 'Krillin', '', '', '0'),
(13, 'Bactérie', '', '', '0'),
(14, 'Jacky Choun', '', '', '0'),
(15, 'Nam', '', '', '0'),
(16, 'Lanfan', '', '', '0'),
(17, 'Guilan', '', '', '0'),
(18, 'Sergent Métallique', '', '', '0'),
(19, 'Ninja Violet', '', '', '0'),
(20, 'Boum', '', '', '0'),
(21, 'Commandant Bleu', '', '', '0'),
(22, 'Arale', '', '', '0'),
(23, 'Taopaipai', '', '', '0'),
(24, 'Draculaman', '', '', '0'),
(25, 'Homme Invisible', '', '', '0');

-- --------------------------------------------------------

--
-- Structure de la table `jeu_map_action`
--

CREATE TABLE IF NOT EXISTS `jeu_map_action` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL,
  `x` int(255) NOT NULL,
  `y` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `jeu_map_action`
--

INSERT INTO `jeu_map_action` (`id`, `titre`, `lien`, `x`, `y`) VALUES
(1, 'Capsule Corp', '&action=capsulecorp', 4, 5);

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `log` text NOT NULL,
  `file` varchar(200) NOT NULL,
  `ligne` int(200) NOT NULL,
  `date` bigint(20) NOT NULL,
  `vu` enum('0','1') NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1169 ;

--
-- Contenu de la table `log`
--


-- --------------------------------------------------------

--
-- Structure de la table `log_admin`
--

CREATE TABLE IF NOT EXISTS `log_admin` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `log` text NOT NULL,
  `file` varchar(200) NOT NULL,
  `ligne` int(200) NOT NULL,
  `date` bigint(20) NOT NULL,
  `vu` enum('0','1') NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=694 ;

--
-- Contenu de la table `log_admin`
--

INSERT INTO `log_admin` (`id`, `log`, `file`, `ligne`, `date`, `vu`, `type`) VALUES
(663, 'Tentative de re-retirage de capsule.', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 1784, 1424026434, '1', 'error'),
(664, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424076950, '1', 'error'),
(665, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424076956, '1', 'error'),
(666, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424076965, '1', 'error'),
(667, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424076970, '1', 'error'),
(668, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424076974, '1', 'error'),
(669, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424076977, '1', 'error'),
(670, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424077018, '1', 'error'),
(671, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424077023, '1', 'error'),
(672, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424077027, '1', 'error'),
(673, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424077031, '1', 'error'),
(674, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/profil.php', 120, 1424077035, '1', 'error'),
(675, 'Le membre Daanzox vient de s''inscrire.', '/homepages/10/d371907532/htdocs/dbu/p/inscription.php', 113, 1426185374, '1', 'success'),
(676, 'Le membre Michel vient de s''inscrire.', '/homepages/10/d371907532/htdocs/dbu/p/inscription.php', 113, 1427328988, '1', 'success'),
(677, 'Le membre Rednka vient de s''inscrire.', '/homepages/10/d371907532/htdocs/dbu/p/inscription.php', 113, 1427903261, '1', 'success'),
(678, 'Le membre Doxiis vient de s''inscrire.', '/homepages/10/d371907532/htdocs/dbu/p/inscription.php', 113, 1428831777, '1', 'success'),
(679, 'Tentative de ré-équipage de capsule déjà équipée.', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2130, 1432932713, '1', 'error'),
(680, 'Tentative de ré-équipage de capsule déjà équipée.', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2130, 1432932714, '1', 'error'),
(681, 'Tentative de ré-équipage de capsule déjà équipée.', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2130, 1432932716, '1', 'error'),
(682, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2268, 1432933646, '1', 'error'),
(683, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2319, 1432933646, '1', 'error'),
(684, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/perso.php', 32, 1432933646, '1', 'error'),
(685, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2905, 1432933646, '1', 'error'),
(686, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2268, 1432933651, '1', 'error'),
(687, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2319, 1432933651, '1', 'error'),
(688, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/p/perso.php', 32, 1432933651, '1', 'error'),
(689, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2905, 1432933651, '1', 'error'),
(690, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 1629, 1432933651, '1', 'error'),
(691, 'Clé : 0 => 00000<br/>Clé : 1 => <br/>Clé : 2 => <br/>', '/homepages/10/d371907532/htdocs/dbu/includes/fonctions.php', 2905, 1432933652, '1', 'error'),
(692, 'Le membre Feezzy vient de s''inscrire.', '/homepages/10/d371907532/htdocs/dbu/p/inscription.php', 113, 1437688736, '1', 'success'),
(693, 'Le membre Kabenoob vient de s''inscrire.', '/homepages/10/d371907532/htdocs/dbu/p/inscription.php', 113, 1450578070, '0', 'success');

-- --------------------------------------------------------

--
-- Structure de la table `oie_archive`
--

CREATE TABLE IF NOT EXISTS `oie_archive` (
  `id_archive` int(65) NOT NULL AUTO_INCREMENT,
  `id_etape` varchar(65) NOT NULL,
  `id_team` int(65) NOT NULL,
  `type_etape` int(65) NOT NULL,
  `membre_pseudo` varchar(65) NOT NULL COMMENT 'Membre ayant fait l''action',
  `lien_utiliser` text NOT NULL COMMENT 'Lien utilisé qui ne sera plus utilisable',
  `donnee_acquis` text NOT NULL,
  `donnee_acquis_zenis` int(255) NOT NULL,
  `versus_perso` varchar(65) NOT NULL,
  `with_perso` varchar(65) NOT NULL,
  PRIMARY KEY (`id_archive`),
  UNIQUE KEY `id_archive` (`id_archive`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1112 ;

--
-- Contenu de la table `oie_archive`
--


-- --------------------------------------------------------

--
-- Structure de la table `oie_detail_etape`
--

CREATE TABLE IF NOT EXISTS `oie_detail_etape` (
  `id_etape` int(65) NOT NULL,
  `type_etape` int(65) NOT NULL,
  `rp` text NOT NULL,
  `mission_etape` text NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id_etape`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `oie_detail_etape`
--

INSERT INTO `oie_detail_etape` (`id_etape`, `type_etape`, `rp`, `mission_etape`, `img`) VALUES
(1, 1, 'Vous commencez l''aventure, mais ce n''est pas gratuit ! Les repas, l''hotel tout ça tout ça...\n', '<br/>Gagner <span class="italique">10 000</span> zénis par personnes.<br/>', 'dragon-ball-z-canned-soda.jpg'),
(2, 2, 'Trunks avait raison pour l''arrivée des Cyborgs, ils sont en ville et ont tout détruit sur leur passage.', '<br/><span class="italique">Gagner</span> <span class="italique">10</span> fights par personnes contre des <span class="italique">Cyborgs</span><br/>\n', 'dbz130-01.jpg'),
(3, 3, 'Vous rencontrez Lunch ! Bonne nouvelle elle n''a pas éternué ! Elle vous laisse donc progresser de 3 cases ! Dépêchez-vous avant qu''elle ne change d''avis !', '<br/>Vous faites un bond de <span class="italique">3</span> cases en avant.<br/>', 'Good-Launch-Screenshots-dragon-ball-females-31976746-720-540.jpg'),
(4, 3, 'Vous croisez Freezer... Je doute qu''avancer d''avantage soit la bonne solution, vous préférez donc faire demi-tour et reculer de 3 cases.', '<br/>Vous faites un bond de <span class="italique">3</span> cases en arrières.<br/>', 'Saga-Freezer.jpg'),
(5, 2, 'Oolon veut mesurer ses talents de transformation avec d''autres magiciens.', '<br/><span class="italique">Gagnez</span> <span class="italique">10</span> fights contre 10 <span class="italique">Mages</span> avec <span class="italique">Oolon</span><br/>', 'RobotOolong.png'),
(6, 2, 'Goku et Végéta décident d''entraîner leur fils. Les élèves vont-ils dépasser les maîtres ?', '<br/><span class="italique">Gagner 5</span> fights contre <span class="italique">Son Goten</span> et <span class="italique">5</span> fights contre <span class="italique">Trunks</span> avec <span class="italique">Son Gokû</span> et/ou <span class="italique">Vegeta</span><br/>\n<span class="italique">Gagner 5</span> fights contre <span class="italique">Son Gokû</span> et <span class="italique">5</span> fights contre <span class="italique">Vegeta</span> avec <span class="italique">Son Goten</span> et/ou <span class="italique">Trunks</span><br/>', 'images.jpg'),
(7, 2, 'Miraï Trunks veut sauver son futur', '<br/><span class="italique">Gagnez</span> <span class="italique">5</span> fights contre <span class="italique">Cell</span> avec <span class="italique">Miraï Trunks</span><br/>', 'dvd_miraitr_ep107_scrn60.jpg'),
(8, 2, 'Mûten Roshi vous a appris l''art du combat. Prouvez lui que son entraînement n''a pas été vain.', '<br/><span class="italique">Gagnez</span> <span class="italique">10</span> fights avec au moins <span class="italique">5</span> niveaux de différences.<br/>', '1124312450.jpg'),
(9, 2, 'Mister Popo décide de prendre en charge votre entraînement.', '<br/><span class="italique">Gagnez</span> <span class="italique">5</span> fights avec <span class="italique">20</span> niveaux de moins.<br/>', 'Mrpopo.png'),
(10, 3, 'Pour vous féliciter de votre investissement en Warzone, votre très adorable, merveilleux, puissant, courageux, [...] chef vous donne le droit de relancer le dé !', '<br/>Vous avez gagné un <span class="italique">second lancé</span> ! Mais celui-ci compte double !<br/>', 'extra128-Kaio-sama.jpg'),
(11, 2, 'Gotenks veut montrer qui est le vrai rebelle.', '<br/><span class="italique">Gagnez</span> <span class="italique">15</span> fights contre des <span class="italique">FR</span> par personnes avec <span class="italique">Gotenks</span><br/>', 'images_gotenks.jpg'),
(12, 2, 'Les femmes veulent se venger des pervers.', '<br/><span class="italique">Gagnez</span> <span class="italique">10</span> fghts contre <span class="italique">Muten Roshi</span> niveau <span class="italique">30 ou plus</span> avec une <span class="italique">femme</span><br/>', '569610960.jpg'),
(13, 2, 'Piccolo se décide à entraîner le fils de Gokû afin de révéler son pouvoir.', '<br/><span class="italique">Gagnez</span> <span class="italique">10</span> fights par personnes contre <span class="italique">Piccolo</span> avec <span class="italique">San Gohan</span><br/>', 'images_gohan_piccolo.jpg'),
(14, 2, 'Buu est devenu gentil grâce à son ami Satan, mais il reste toujours sa partie démoniaque.', '<br/><span class="italique">Gagnez</span> <span class="italique">40</span> fights contre <span class="italique">Buu</span> avec <span class="italique">Buu</span><br/>', 'Good-Buu-Vs-Evil-Buu-majin-buu-12975679-500-350.jpg'),
(15, 2, 'Baddack se laisse emporter par la colère de voir la destruction de la Planète Végéta.<br/>La magie de la boule du néan de Freezer va bientôt anihiler les Saiyens.<br/>Baddack veut l''en empêcher, mais il est trop faible.', '<br/><span class="italique">Perdez</span> <span class="italique">10</span> fights contre des <span class="italique">mages</span> avec <span class="italique">Baddack</span><br/>', 'Bardock_mit_einigen_Saiyajin.jpg'),
(16, 2, 'Cell est en ville et absorbe l''énergie vitale des citoyens, mais Goku et ses amis sont là ... Ou pas !', '<br/><span class="italique">Perdez</span> <span class="italique">5</span> fights contre <span class="italique">Cell</span> avec un personnage de la <span class="italique">Z-Team</span><br/>', 'Dbz-cell-derp-face_c_150041.jpg'),
(17, 3, 'Mûten Roshi est de très mauvaise humeur ! Heureusement, vous êtes venus avec votre chère cousine, 95C, tout ça tout ça... Il vous laisse progresser de 4 cases !', '<br/>Vous faites un bond de <span class="italique">4</span> cases en avant.<br/>', 'Muten_Roshi_pafu_pafu.jpg'),
(18, 2, 'Tout le monde sait qu''on ne lève pas la main sur une femme.', '<br/><span class="italique">Perdez</span> contre <span class="italique">Chichi</span>, <span class="italique">Bulma</span>, <span class="italique">Videl</span> avec respectivement <span class="italique">Goku</span>, <span class="italique">Vegeta</span> et <span class="italique">Gohan</span> sans <span class="italique">infliger</span> de dégât.<br/>', 'DBZ%20les%20filles.jpg'),
(19, 2, 'Vous arrivez à la capitale du Nord. Comme vous n''avez pas envie d''avoir de problèmes avec L''inspecteur Jenny, vous ignorez les humains qui vous cherchent des noises.', '<br/>Faites <span class="italique">20 matchs nuls</span> contre des <span class="italique">humains</span>.<br/>', '956208ManWolfHuman.png'),
(20, 3, 'Vous rencontrez Bactérie ! Manque de bol, il vous crache dessus ! Vous restez donc bloqués pendant 24 heures !', '<br/>Pas de chance, vous êtes bloqués pendant <span class="italique">24</span> heures.<br/>', 'Bacterie.jpg'),
(21, 2, 'Piccolo reste le fils du grand Piccolo Daimaô ! Il est sans pitié et n''hésite pas à exterminer les humains sur son passage !', '<br/><span class="italique">Tuez 10 humains</span> avec <span class="italique">Piccolo</span>.<br/>', 'piccolo-jr.jpg'),
(22, 2, 'Les Saiyens sont arrivés sur Terre ! Mais... il n''y a pas que Végéta et Nappa ?! Ils sont plusieurs, ils sont mêmes nombreux ! Tuez-en !!', '<br/><span class="italique">Tuez 10 saiyens</span> par personnes.<br/>', 'jol_saiyens.jpg'),
(23, 2, 'Votre capsule spatiale vient d''atterir. Vous arrivez sur la Terre, une planète habitée par des faibles, les humains. Vous décidez donc de commencer votre travail : les tuer.', '<br/><span class="italique">Tuez 5 humains</span> par personnes avec des <span class="italique">Saiyens</span>.<br/>', 'saiyens.png'),
(24, 2, 'Ils arrivent ! Les monstrueux Saiyens sont là ! Sortez vos fusils, vos épées, braves humains, et partez au combat !', '<br/><span class="italique">Tuez 5 Saiyens</span> par personnes avec des <span class="italique">humains</span>.<br/>', 'dbz5-34.jpg'),
(25, 2, 'Vous arrivez au Grand Nord. Manque de bol, c''est le jour où tous les cyborgs viennent recharger leur duracell. Vous allez donc devoir vous battre !', '<br/><span class="italique">Tuez</span> un <span class="italique">Cyrborg</span> par personnes.<br/>', 'dbz135-01.jpg'),
(26, 2, 'Cell a absorbé C-17 et C-18 et est maintenant sous sa forme parfaite... Vous vous lancez désespérément dans le combat avec l''espoir de le tuer, mais il est trop fort... Un petit oiseau meurt et un Téléport raté, et tout le monde est tué.', '<br/><span class="italique">Mourez</span> <span class="italique">une</span> fois contre <span class="italique">Cell</span> avec <span class="italique">Son Gokû</span> et <span class="italique">C-16</span>.<br/>', 'dbz151-01.jpg'),
(27, 2, 'Buu est sorti de son cocon 1 place ultra-confort sorti tout droit d''Ikea (pourquoi il en est sorti ce con, hein ?). Vous décidez de vous suicider pour le faire disparaître... Ou tout simplement parce que les nouvelles auréoles "swag" sont sorties à carrefour...', '<br/><span class="italique">Mourez</span> contre <span class="italique">Buu</span> avec <span class="italique">Végéta</span>.<br/>', '222-95.jpg'),
(28, 2, 'Vous avez attrapé Radditz ! Piccolo concentre son énergie dans ses deux doigts. Quelques secondes plus tard, il crie "MAKENKOSENPPO !!!!!!"', '<br/><span class="italique">Mourez</span> contre <span class="italique">Piccolo</span> avec <span class="italique">San Goku</span>.<br/>', 'dbz4-24.jpg'),
(29, 2, 'Vous rentrez trop tard à la maison... Chichi vous regarde, un rouleau de patisserie dans la main. Derrière-vous, Gohan Et végéta. À côté de Chichi, Videl et Bulma... Préparez-vous à passer un mauvais quart d''heure !', '<br/><span class="italique">Mourez 10</span> fois par personnes contre des <span class="italique">femmes</span>.<br/>', 'Dbz_ChiChi_005.jpg'),
(30, 2, 'San Goku, aidé par son Kaiohken vous a bien niqué votre race ! Vous suppliez Végéta, mais ce dernier répond : "Crève, j''touche pas les mains d''un Feeder !"', '<br/><span class="italique">Mourez 5</span> fois par personnes contre <span class="italique">Végéta</span> avec <span class="italique">Nappa</span>.<br/>', 'mqdefault.jpg'),
(31, 2, 'Végéta et Trunks sont dans la salle d''entraînement conçue par Bulma. Cette dernière a mis en place un testeur de force. Il faut cumuler un certain nombre de frappe pour que l''entraînement soit utile !', '<br/><span class="italique">Cumulez 60 000</span> de <span class="italique">dégâts</span> avec <span class="italique">Végéta</span> et <span class="italique">Trunks</span>.<br/>', 'dbz207-29.jpg'),
(32, 2, 'C''est les soldes à la Capitale du Sud ! Vos chères femmes vous harcellent pour y aller. Comme vous êtes sympas, vous leu donnez vos économies ! Mais faites attentions, elles mordent.. Remettez-les en place avant !', '<br/><span class="italique">Gagnez 10</span> fights contre des <span class="italique">femmes</span> par personnes et économisez <span class="italique">10 000</span> zénis par personnes.<br/>', 'dbzm8-003.jpg'),
(33, 3, 'Chichi vous félicité pour avoir rangé votre chambre ! Relancez le dé !', '<br/>Vous avez gagné un <span class="italique">second lancé</span> ! <br/>', 'Chichi_hearts.jpg'),
(34, 3, 'Il fait nuit, vous ne pouvez pas continuer, Bulma a peur de la nuit ! Montez donc la tente, et dormez !', '<br/>Vous devez attendre <span class="italique">8 heures</span> avant de pouvoir relancer le dé.<br/>', '546531-dragon_ball_2_2.png'),
(35, 2, 'Le Dr.Gero veut tester la nouvelle force de ses Cyborgs.', '<br/><span class="italique">Gagner</span> <span class="italique">10</span> fights par personnes contre des <span class="italique">Humains</span> avec des <span class="italique">Cybogrs</span>', 'Geroirl.PNG'),
(36, 2, 'Après que Goku ait réduit Taopaipai en cendres, ce dernier revient plus fort, sous forme de cyborg.', '<br/><span class="italique">Perdez</span> contre <span class="italique">Taopaipai</span> en lui infligeant au moins <span class="italique">40.000</span> dégâts.<br/>', 'Tao_Pai_Pai_robot.jpg'),
(37, 3, 'Pour vous féliciter de votre investissement en Warzone, votre très adorable, merveilleux, puissant, courageux, [...] chef vous donne le droit de relancer le dé !', '<br/>Vous avez gagné un <span class="italique">second lancé</span> ! Mais celui-ci compte double !<br/>', 'extra128-Kaio-sama.jpg'),
(38, 1, 'Un passage secret ? Arf il est payant ! Vous réfléchissez longuement et décidez de quand même de le prendre.', '<br/>Réunissez <span class="italique">35 000</span> zénis et prenez le passage secret qui vous permettra peut-être d''atteindre la dernière case ! (Ou pas hein, faut pas rêver !)<br/>', ''),
(39, 2, 'Toma, étant au courant des plans maléfiques de Freeza, essaye de les contre carrer en lançant une attaque contre les hommes et la famille de Freeza.', '<br/><span class="italique">Gagner</span> contre <span class="italique">10</span> hommes et parents de freezer avec <span class="italique">Toma</span><br/>', '1230492755035_f.jpg'),
(40, 3, 'QUOI ?! Vous refusez de faire la Wz ?! Pour la peine, retournez-donc à la case départ !', '<br/>Malheureusement vous <span class="italique">recommencez</span> le jeu.<br/>', '00000yajirobe1.jpg'),
(41, 3, 'Tiens ? C''est le passage que Chibi Goku avait pris avec Bulma et Krillin quand ils cherchaient les Dragons Balls. Il n''y a plus de pirates iic, vous décidez donc de prendre ce passage qui vous mènera vers un autre endroit.', '<br/>Surprise, vous atterrissez à la case <span class="italique">53</span>.<br/>', 'PirateCave1.jpg'),
(42, 2, 'Après avoir utilisé votre détecteur pour trouver San Goku, vous arrivez dans un village agricole. Des humains vous attaquent, mais vous n''avez pas le temps, il faut trouver San Goku ! Esquivez-les !', '<br/>Faites <span class="italique">20 matchs nuls</span> contre des <span class="italique">humains</span> avec des <span class="italique">cyborgs</span>.<br/>\r\n', '6634.jpg'),
(43, 2, 'Vous rencontrez un vieux sage qui vous raconte son secret : "Pour emporter ton combat, esquiver toutes les frappes de ton ennemi tu devras..."', '<br/><span class="italique">Gagnez 20</span> fights avec plus de <span class="italique">300 000</span> de vie.</br>', 'Karin.jpg'),
(44, 1, 'Les études de Gohan coûtent cher ! En plus, Chichi a encore dépensée cinq fois trop en courses ! Dépêchez-vous de gagner de l''argent avant que l''huissier arrive !', '<br/>Récoltez <span class="italique">10 000</span> zénis.<br/>', '419788042_small.jpg'),
(45, 2, 'Les gorilles sont en colère, leurs noms ont été pris sans leur approbation.', '<br/><span class="italique">Gagnez</span> <span class="italique">20</span> fights contre les <span class="italique">GOU</span> par personnes avec <span class="italique">Toma</span>, <span class="italique">Vasha</span>, <span class="italique">Baddack</span>, <span class="italique">Oozaru</span> ou <span class="italique">Paragus</span><br/>\r\nLe choix du/des personnage(s) est optionnel, vous pouvez utiliser Toma, comme Vasha et Baddack, ou seulement Toma par exemple.<br/>', 'x240-ZPV.jpg'),
(46, 3, 'Un bon Saiyen est un Saiyen patient, comme disait toujours mon grand-père... ou ma grand-mère ? Bref, attendez 2 heures !', '<br/>Vous devez attendre <span class="italique">2 heures</span> avant de pouvoir relancer le dé.<br/>', 'extra139-Unhappy+Vegeta.jpg'),
(47, 2, 'Pour achever votre entraînement, vous décidez de partir à la recherche de tous les guerriers du monde !', '<br/><span class="italique">Gagnez</span> contre les <span class="italique">63</span> personnages disponibles.<br/>', 'index_z4.jpg'),
(48, 1, 'Le roi Gyumao a encore dû quitter sa maison... Qu''elle idée d''habiter une montage qui s''enflamme ! Aidez-le à acheter des extincteurs !', '<br/>Donnez <span class="italique">20 000</span> zénis à Gyumao.<br/>', 'gyumaoperso.jpg'),
(49, 4, 'Pour atteindre le stade de Super Saiyen, il vous faut gagner un certain nombre de combat en un temps limité !', '<br/><span class="italique">Gagnez 20</span> fights en <span class="italique">1</span> minute.<br/>', 'saiyens.png'),
(50, 4, 'La dernière capsule top-tendance est en promotion jusqu''à demain ! Dépêchez-vous d''économiser !', '<br/>Gagnez <span class="italique">20 000</span> zénis en <span class="italique">5</span> minutes.<br/>', 'capsules.png'),
(51, 2, 'Certains disent que le Kaméhaméha peut-être appris en surpassant ses limites, et en se rapprochant de la mort.', '<br/><span class="italique">Gagnez 20</span> fights avec moins de <span class="italique">30 000</span> de vie.<br/>', 'Goku_sad_it_wasen''t_as_much_as_Master_Roshi''s.jpg'),
(52, 3, 'Vous rencontrez Satan, le vaillant Champion ! Relancez donc le dé !', '<br/>Vous avez gagné un <span class="italique">second lancé</span> !<br/>', 'Mr._Satan.jpg'),
(53, 2, 'Akkuman est bien l''incarnation du Diable ! Il cherche à représenter le fameux 66 dans ses combats ! Aidez-le !\r\nFaites un combat avec un dégat final se finissant par "66" avec Akkuman.', '<br/>Faites un combat avec <span class="italique">Akkuman</span> avec un dégât final se finissant par <span class="italique">"66"</span>.<br/>', 'db74-12.jpg'),
(54, 5, 'Un peu de détente et de culture général !', '<br/>Vous devez remplir ce questionnaire, attention, pour chaque mauvaises réponses, vous devez attendre <span class="italique">X</span> heures, où X est égal au nombre d''erreur !<br/>', 'questionnaire-3d.jpg'),
(55, 3, 'Vous achetez des fleurs à Bukma pour la remercier d''avoir réparé le Dragon Radar, vous progressez de 1 case !', '<br/>Vous faites un bond de <span class="italique">1</span> case en avant.<br/>', '11194055_m.jpg'),
(56, 3, 'Steel''s Spell Mistakes', '<br/>Faites creuser vos méninges ! Vous devez faire une phrase avec le plus de fautes possibles !<br/>', ''),
(57, 3, 'Vous arrivez chez Mûten Roshi, et il décide de vous raconter sa jeunesse...', '<br/>Vous devez attendre <span class="italique">4 heures</span> avant de pouvoir relancer le dé.<br/>', 'Muten_Roshi_und_eine_Meerjungfrau.jpg'),
(58, 2, 'Pour achever votre entraînement, vous décidez de partir à la recherche de tous les guerriers du monde !', '<br/><span class="italique">Gagnez</span> contre les <span class="italique">63</span> personnages disponibles.<br/>', 'index_z4.jpg'),
(59, 2, 'Votre chef vous l''a souvent répété, la warzone est l''évènement majeur du jeu. Pour lui montrer vos progrès, vous décidez d''affronter les 10 meilleurs joueurs en warzone et les battre ! (gare à vos fesses si vous perdez volontairement !)', '<br/><span class="italique">Gagnez 10</span> fights par personnes contre les <span class="italique">10 meilleurs joueurs en WZ</span>.<br/>', 'hqdefault.jpg'),
(60, 2, 'Coola, le frère de Freezer déteste lui aussi les Saiyens. Il décide donc de partir à la recherche des derniers Saiyens survivants.', '<br/><span class="italique">Gagnez 5</span> fights par personnes contre les <span class="italique">Saiyens</span> avec <span class="italique">Coola</span>.<br/>', 'Coola.jpg'),
(61, 2, 'C''est à cause de Radditz que Nappa est chauve ! Il décide de le tuer !', '<br/><span class="italique">Tuez 5</span> fois <span class="italique">Radditz</span> avec <span class="italique">Nappa</span>.<br/>', '13295-vlcsnap_523638.png'),
(62, 3, 'Chichi sait que vous ne faites pas vos devoirs ! Pour la peine, elle vous oblige à reculer de 6 cases !', '<br/>Vous faites un bond de <span class="italique">6</span> cases en arrières.<br/>', 'images_chichi.jpg'),
(63, 2, 'Une épreuve obligatoire pour l''aventure de Son Gokû, vous devez impérativement faire cette épreuve pour pouvoir poursuivre l''entraînement de Son Gokû !', '<br/><span class="italique">Gagnez 500</span> fights</br>', 'url.png');

-- --------------------------------------------------------

--
-- Structure de la table `oie_equipe`
--

CREATE TABLE IF NOT EXISTS `oie_equipe` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `nom_team` varchar(64) NOT NULL,
  `password` varchar(40) NOT NULL,
  `liste_membre` varchar(255) NOT NULL,
  `position` int(2) NOT NULL DEFAULT '1',
  `time_arrived_position` int(255) NOT NULL,
  `nb_lancer` int(65) NOT NULL DEFAULT '0',
  `date_last_lancer` int(65) NOT NULL,
  `statut` varchar(65) NOT NULL DEFAULT 'en cours',
  `avancement` int(11) NOT NULL DEFAULT '0' COMMENT 'Avancement de letape en int',
  `time_statut_bloqued` int(65) NOT NULL DEFAULT '0',
  `fight_started_type_4` int(255) NOT NULL DEFAULT '0',
  `date_last_refresh` int(255) NOT NULL,
  `time_finish` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `oie_equipe`
--

INSERT INTO `oie_equipe` (`id`, `nom_team`, `password`, `liste_membre`, `position`, `time_arrived_position`, `nb_lancer`, `date_last_lancer`, `statut`, `avancement`, `time_statut_bloqued`, `fight_started_type_4`, `date_last_refresh`, `time_finish`) VALUES
(2, 'Owned By Us', 'a4d75c97c7ef7aa618e3a613aeeb7fef9a7f9698', ',Sb Rescuer of Myst°,Sg4', 63, 1416241676, 0, 1416241676, 'en cours', 79, 0, 0, 1454804752, 0),
(3, 'Test', '443152f93bbf73cd46c0101fcba2759012b80568', ',Natsuro,misuta popo', 63, 1416723611, 0, 1416723611, 'finish', 49, 0, 0, 1454804752, 1421361173),
(4, 'Attantion au Staff Kami', '3cde56e947631c62839b52cc6ccd6ec3916183dd', ',°MR°Mystogan,Attantion,Romulus', 63, 1416730716, 0, 1416730716, 'en cours', 0, 0, 0, 1454804752, 0),
(5, 'Kami no senshi', '485271e8bd7be3e1f2eea12a67a369d6164befe3', ',Lightdragons,NavyZack,iZnoGouD', 63, 1416087966, 0, 1415983185, 'finish', 500, 0, 0, 1454804752, 1416905305),
(6, '12ème Division', 'ce547d660e692520686f267f87aea1cf0b0a2c79', ',Misaki-chan,Unowen', 23, 1416078708, 0, 1416078708, 'en cours', 0, 0, 0, 1454804752, 0);

-- --------------------------------------------------------

--
-- Structure de la table `oie_etape_type_1`
--

CREATE TABLE IF NOT EXISTS `oie_etape_type_1` (
  `id_etape` int(65) NOT NULL,
  `detail` enum('toto','personne') NOT NULL COMMENT 'toto / par joueur',
  `nb` int(65) NOT NULL COMMENT 'zenis',
  PRIMARY KEY (`id_etape`),
  UNIQUE KEY `id_etape` (`id_etape`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `oie_etape_type_1`
--

INSERT INTO `oie_etape_type_1` (`id_etape`, `detail`, `nb`) VALUES
(1, 'personne', 10000),
(32, 'personne', 10000),
(38, 'toto', 35000),
(44, 'toto', 10000),
(48, 'toto', 20000);

-- --------------------------------------------------------

--
-- Structure de la table `oie_etape_type_2`
--

CREATE TABLE IF NOT EXISTS `oie_etape_type_2` (
  `id_etape` int(65) NOT NULL,
  `detail_fight` varchar(255) NOT NULL COMMENT ' Win / Defaite / Tue / Mourir / Match Nul',
  `detail_nb_fight` varchar(255) NOT NULL COMMENT 'toto / par personne',
  `nb_fight` int(255) NOT NULL COMMENT 'nb_fight',
  `with_perso` varchar(255) NOT NULL COMMENT 'groupe_perso (humains, cyborg etc. (array >= 1)) / 		liste_perso (array >= 1)',
  `with_detail_perso` varchar(255) NOT NULL COMMENT 'type_perso',
  `with_level_perso` int(255) NOT NULL COMMENT 'level_perso',
  `versus_perso` varchar(255) NOT NULL COMMENT 'groupe_perso (humains, cyrborg etc. (array >= 1)) / 		liste_perso (array >= 1)',
  `versus_name_player` varchar(255) NOT NULL COMMENT 'name_player (array >= 1)',
  `versus_detail_perso` varchar(255) NOT NULL COMMENT 'type_perso',
  `versus_level_perso` int(255) NOT NULL COMMENT 'level_perso',
  `versus_clan` varchar(255) NOT NULL COMMENT 'clan',
  `level_diff` int(255) NOT NULL COMMENT 'nb_level / null',
  `damage_detail` varchar(255) NOT NULL COMMENT 'cumul / need',
  `damage_detail_nb` int(255) NOT NULL COMMENT 'cumul->nb / need->nb',
  `life_detail` varchar(255) NOT NULL COMMENT '+/-',
  `life_detail_fight` int(255) NOT NULL COMMENT 'nb_life',
  PRIMARY KEY (`id_etape`),
  UNIQUE KEY `id_etape` (`id_etape`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `oie_etape_type_2`
--

INSERT INTO `oie_etape_type_2` (`id_etape`, `detail_fight`, `detail_nb_fight`, `nb_fight`, `with_perso`, `with_detail_perso`, `with_level_perso`, `versus_perso`, `versus_name_player`, `versus_detail_perso`, `versus_level_perso`, `versus_clan`, `level_diff`, `damage_detail`, `damage_detail_nb`, `life_detail`, `life_detail_fight`) VALUES
(2, 'Gagner', 'personne', 10, '', '', 0, 'Cyborgs', '', '', 0, '', 0, '', 0, '', 0),
(5, 'Gagner', 'toto', 10, 'Oolon', '', 0, 'all', '', 'Mage', 0, '', 0, '', 0, '', 0),
(6, 'Gagner', 'toto', 5, '(Son Gokû||Vegeta),(Son Goten||Trunks)', '', 0, '(Son Goten&&Trunks),(Son Gokû&&Vegeta)', '', '', 0, '', 0, '', 0, '', 0),
(7, 'Gagner', 'toto', 5, 'Mirai Trunks', '', 0, 'Cell', '', '', 0, '', 0, '', 0, '', 0),
(8, 'Gagner', 'toto', 10, 'all', '', 0, 'all', '', '', 0, '', 5, '', 0, '', 0),
(9, 'Gagner', 'toto', 5, 'all', '', 0, 'all', '', '', 0, '', -20, '', 0, '', 0),
(11, 'Gagner', 'personne', 15, 'Gotenks', '', 0, 'all', '', '', 0, 'FR', 0, '', 0, '', 0),
(12, 'Gagner', 'toto', 10, 'Femmes', '', 0, 'Muten Rôshi', '', '', 30, '', 0, '', 0, '', 0),
(13, 'Gagner', 'personne', 10, 'Son Gohan', '', 0, 'Piccolo', '', '', 0, '', 0, '', 0, '', 0),
(14, 'Gagner', 'toto', 40, 'Majin Buu', '', 0, 'Majin Buu', '', '', 0, '', 0, '', 0, '', 0),
(15, 'Perdre', 'toto', 10, 'Baddack', '', 0, 'all', '', 'Mage', 0, '', 0, '', 0, '', 0),
(16, 'Perdre', 'toto', 5, 'Z-Team', '', 0, 'Cell', '', '', 0, '', 0, '', 0, '', 0),
(18, 'Perdre', 'toto', 1, '(Son Gokû&&Vegeta&&Son Gohan)', '', 0, '(Chichi&&Bulma&&Videl)', '', '', 0, '', 0, 'need', 0, '', 0),
(19, 'Match Nul', 'personne', 20, 'all', '', 0, 'Humains', '', '', 0, '', 0, '', 0, '', 0),
(21, 'Tuer', 'toto', 10, 'Piccolo', '', 0, 'Humains', '', '', 0, '', 0, '', 0, '', 0),
(22, 'Tuer', 'personne', 10, 'All', '', 0, 'Saiyens', '', '', 0, '', 0, '', 0, '', 0),
(23, 'Tuer', 'personne', 5, 'Saiyens', '', 0, 'Humains', '', '', 0, '', 0, '', 0, '', 0),
(24, 'Tuer', 'personne', 5, 'Humains', '', 0, 'Saiyens', '', '', 0, '', 0, '', 0, '', 0),
(25, 'Tuer', 'personne', 1, 'all', '', 0, '(Aralé&&C-8&&C-17&&C-18&&C-19&&C-20)', '', '', 0, '', 0, '', 0, '', 0),
(26, 'Mourir', 'toto', 1, '(C-16&&Son Gokû)', '', 0, 'Cell', '', '', 0, '', 0, '', 0, '', 0),
(27, 'Mourir', 'toto', 1, 'Vegeta', '', 0, 'Buu', '', '', 0, '', 0, '', 0, '', 0),
(28, 'Mourir', 'toto', 1, 'Son Gokû', '', 0, 'Piccolo', '', '', 0, '', 0, '', 0, '', 0),
(29, 'Mourir', 'personne', 10, 'all', '', 0, 'Femmes', '', '', 0, '', 0, '', 0, '', 0),
(30, 'Mourir', 'personne', 5, 'Nappa', '', 0, 'Vegeta', '', '', 0, '', 0, '', 0, '', 0),
(31, 'Gagner', 'toto', 1, '(Vegeta&&Trunks)', '', 0, 'all', '', '', 0, '', 0, 'cumul', 60000, '', 0),
(32, 'Gagner', 'personne', 10, 'all', '', 0, 'Femmes', '', '', 0, '', 0, '', 0, '', 0),
(35, 'Gagner', 'personne', 10, 'Cyborgs', '', 0, 'Humains', '', '', 0, '', 0, '', 0, '', 0),
(36, 'Perdre', 'toto', 1, 'all', '', 0, 'Taopaipai', '', '', 0, '', 0, 'cumul', 40000, '', 0),
(39, 'Gagner', 'toto', 10, 'Toma', '', 0, 'Famille_Freezer', '', '', 0, '', 0, '', 0, '', 0),
(42, 'Match Nul', 'personne', 20, 'Cyborgs', '', 0, 'Humains', '', '', 0, '', 0, '', 0, '', 0),
(43, 'Gagner', 'toto', 20, 'all', '', 0, 'all', '', '', 0, '', 0, '', 0, '+', 300000),
(45, 'Gagner', 'personne', 20, '(Toma||Vasha||Baddack||Oozaru||Paragus)', '', 0, 'all', '', '', 0, 'GOU', 0, '', 0, '', 0),
(47, 'Gagner', 'toto', 63, 'all', '', 0, '(all)', '', '', 0, '', 0, '', 0, '', 0),
(51, 'Gagner', 'toto', 20, 'all', '', 0, 'all', '', '', 0, '', 0, '', 0, '-', 30000),
(53, 'Gagner', 'toto', 1, 'Akkuman', '', 0, 'all', '', '', 0, '', 0, 'need', 66, '', 0),
(58, 'Gagner', 'toto', 63, 'all', '', 0, '(all)', '', '', 0, '', 0, '', 0, '', 0),
(59, 'Gagner', 'toto', 10, 'all', '', 0, 'all', 'WZ', '', 0, '', 0, '', 0, '', 0),
(60, 'Gagner', 'personne', 5, 'Coola', '', 0, 'Saiyens', '', '', 0, '', 0, '', 0, '', 0),
(61, 'Tuer', 'toto', 5, 'Nappa', '', 0, 'Radditz', '', '', 0, '', 0, '', 0, '', 0),
(63, 'Gagner', 'toto', 500, 'Son Gokû', '', 0, 'all', '', '', 0, '', 0, '', 0, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `oie_etape_type_3`
--

CREATE TABLE IF NOT EXISTS `oie_etape_type_3` (
  `id_etape` int(65) NOT NULL,
  `action` varchar(65) NOT NULL COMMENT 'down / up / reset / secret / relance / waiting / 		nothing / dubble / steel',
  `nb_case` int(65) NOT NULL COMMENT 'case',
  PRIMARY KEY (`id_etape`),
  UNIQUE KEY `id_etape` (`id_etape`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `oie_etape_type_3`
--

INSERT INTO `oie_etape_type_3` (`id_etape`, `action`, `nb_case`) VALUES
(3, 'up', 3),
(4, 'down', 3),
(10, 'dubble', 0),
(17, 'up', 4),
(20, 'waiting', 24),
(33, 'relance', 0),
(34, 'waiting', 8),
(37, 'dubble', 0),
(40, 'reset', 0),
(41, 'secret', 53),
(46, 'waiting', 2),
(52, 'relance', 0),
(55, 'up', 1),
(56, 'steel', 0),
(57, 'waiting', 4),
(62, 'down', 6);

-- --------------------------------------------------------

--
-- Structure de la table `oie_etape_type_4`
--

CREATE TABLE IF NOT EXISTS `oie_etape_type_4` (
  `id_etape` int(65) NOT NULL,
  `action` varchar(65) NOT NULL COMMENT 'fight / zenis',
  `detail` int(255) NOT NULL COMMENT 'nb->fight / nb->zenis',
  `time` int(255) NOT NULL COMMENT 'x mins',
  PRIMARY KEY (`id_etape`),
  UNIQUE KEY `id_etape` (`id_etape`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `oie_etape_type_4`
--

INSERT INTO `oie_etape_type_4` (`id_etape`, `action`, `detail`, `time`) VALUES
(49, 'fight', 20, 1),
(50, 'zenis', 20000, 5);

-- --------------------------------------------------------

--
-- Structure de la table `oie_etape_type_5`
--

CREATE TABLE IF NOT EXISTS `oie_etape_type_5` (
  `id_question` int(255) NOT NULL AUTO_INCREMENT,
  `id_questionnaire` varchar(65) NOT NULL,
  `id_etape` int(11) NOT NULL,
  `question` text NOT NULL,
  `choix1` varchar(65) NOT NULL,
  `choix2` varchar(65) NOT NULL,
  `choix3` varchar(65) NOT NULL,
  `reponse` varchar(65) NOT NULL,
  PRIMARY KEY (`id_question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Contenu de la table `oie_etape_type_5`
--

INSERT INTO `oie_etape_type_5` (`id_question`, `id_questionnaire`, `id_etape`, `question`, `choix1`, `choix2`, `choix3`, `reponse`) VALUES
(1, '1', 54, 'Quelle est la date de sortie de League of Legends ?', '27 octobre 2009', '27 septembre 2008', '27 octobre 2008', '27 octobre 2009'),
(2, '1', 54, 'Combien de champions sont sortis le 21 février 2009 ?', '1', '9', '17', '17'),
(3, '1', 54, 'Quel a été le 100ème champion ?', 'Kha\Zix', 'Syndra', 'Jayce', 'Jayce'),
(4, '1', 54, 'Deux personnages sont sortis le 17 janvier, étaient-ce ...', '...Varus et Mordekaiser', '...Sejuani et Renekton', '...Jarvan IV et Poppy', '...Sejuani et Renekton'),
(5, '1', 54, 'Sg4 se plaint très souvent sur ce jeu, à quoi est-ce du ?', 'Rage', 'Bugs/Lags', 'Freezes', 'Freezes'),
(6, '1', 54, 'Le nom d''un champion est tiré d''une loi, quel est ce champion ?', 'Veigar', 'Amumu', 'Udyr', 'Veigar'),
(7, '1', 54, 'Il existe plusieurs champions dits "du chaos", ils ont une particularité, laquelle ?', 'Ils ont un apostrophe dans leur nom', 'Ils ont tous un skin de base sombre', 'Leur nom compte 6 lettres', 'Leur nom compte 6 lettres'),
(8, '1', 54, 'Le nom des champions du chaos compte 6 lettres, néanmoins, un champion du chaos n''a pas 6 lettres, lequel ?', 'ChoGath', 'Malzahar', 'Kassadin', 'ChoGath'),
(9, '1', 54, 'Quel a été le premier perso joué par notre bien-aimé chef ?', 'Jinx', 'Ashe', 'Garen', 'Garen'),
(10, '1', 54, 'Dans le didactitiel, entre combien de champions peut-on choisir de jouer ?', '2', '3', '4', '3'),
(11, '1', 54, 'Qui est le développeur du jeu ?', 'Riot Games', 'Rito Gaming', 'Riot Gaming', 'Riot Games'),
(12, '1', 54, 'Heimerdinger optient un "Buff Eureka" s''il ...', 'Tue quelqu''un avec ses tourelles en étant mort', 'Meurt contre une tourelle d''Heimerdinger adverse', 'Fait un pentakill', 'Fait un pentakill'),
(13, '1', 54, 'Quelle est la relation entre Ashe et Trydamère ?', 'Ils sont meilleurs amis', 'Ils sont ennemis mortels', 'Ils sont mariés', 'Ils sont mariés'),
(14, '1', 54, 'Quel âge a Gnar ?', '4 ans', '8ans', '12 ans', '4 ans'),
(15, '1', 54, 'Qui est le plus petit champion ?', 'Tristana', 'Teemo', 'Amumu', 'Teemo'),
(16, '1', 54, 'Quel champion a une animation de rire infinie ?', 'Vi', 'Jinx', 'Elise', 'Jinx'),
(17, '1', 54, 'Combien Vel''Koz gagne de points d''attaque par niveau ?', '¶', '§', 'µ', '¶'),
(18, '1', 54, 'Combien existe-t-il de skins "plage" ?', '2', '5', '7', '5'),
(19, '1', 54, 'Quel a été le premier skin "plage" ?', 'Leona', 'Graves', 'Ziggs', 'Ziggs'),
(20, '1', 54, 'Qui est le coach des Fnatic ?', 'Toyz', 'Dyrus', 'xPeke', 'Toyz'),
(21, '1', 54, 'Qui est le maitre de Wukong pour lui enseigner l''art Wuju ?', 'Jax', 'Master Yi', 'Nashor', 'Master Yi'),
(22, '1', 54, 'Quel est le champion avec le moins de points de vie de base au niveau 1 ?', 'Lux', 'Diana', 'Elise', 'Lux'),
(23, '1', 54, 'Quel lien uni Draven et Darius ?', 'Leur prénom se ressemblent', 'Ils sont frères', 'Ils sont meilleurs amis', 'Ils sont frères'),
(24, '1', 54, 'On peut forcer le login sur l''écran d''accueil en tappant une phrase, laquelle ?', 'thereisnourflevel', 'thereisourlevel', 'fkurlifeiwanttoplaybastard', 'thereisnourflevel'),
(25, '1', 54, 'Quels champions ont la plus grande vitesse de déplacement de base ?', 'Jarvan et Jax', 'Master Yi et Wukong', 'Master Yi et Panthéon', 'Master Yi et Panthéon'),
(26, '1', 54, 'Qui est le joueur ayant le plus joué à LoL ? (8722h)', 'DragonNC', 'DrakeEC', 'DragonES', 'DragonNC'),
(27, '1', 54, 'Comment se nomme le corbeau de Swain ?', 'Bénédicte', 'Béatrice', 'Belatrix', 'Béatrice'),
(28, '1', 54, 'A quelle série TV fait référence le skin Heimerdinger Hazmat ?', 'The Walking Dead', 'Breaking Bad', 'Dexter', 'Breaking Bad'),
(29, '1', 54, 'A quelle chanson fait référence la dance de Malzahar ?', 'Gangnam Style', 'Crystallize', 'U Can''t Touch This', 'U Can''t Touch This'),
(30, '1', 54, 'Quelle est la particularité de Lee Sin ? (Autre qu''il soit une fusée)', 'Il est aveugle', 'Il est sourd', 'Il est muet', 'Il est aveugle');

-- --------------------------------------------------------

--
-- Structure de la table `oie_type_etape`
--

CREATE TABLE IF NOT EXISTS `oie_type_etape` (
  `id_etape` int(65) NOT NULL AUTO_INCREMENT,
  `type_etape` int(65) NOT NULL,
  `nom_etape` varchar(64) NOT NULL,
  PRIMARY KEY (`id_etape`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `oie_type_etape`
--

INSERT INTO `oie_type_etape` (`id_etape`, `type_etape`, `nom_etape`) VALUES
(1, 1, 'Zenis'),
(2, 2, 'Fight'),
(3, 3, 'Spéciale'),
(4, 4, 'Time'),
(5, 5, 'Questions');

-- --------------------------------------------------------

--
-- Structure de la table `preinscription`
--

CREATE TABLE IF NOT EXISTS `preinscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `ip` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;



-- --------------------------------------------------------

--
-- Structure de la table `site_connectes`
--

CREATE TABLE IF NOT EXISTS `site_connectes` (
  `connectes_id` int(11) NOT NULL,
  `connectes_ip` varchar(16) NOT NULL,
  `connectes_membre` varchar(16) NOT NULL,
  `connectes_actualisation` int(11) NOT NULL,
  UNIQUE KEY `membre_id` (`connectes_id`,`connectes_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `site_membres`
--

CREATE TABLE IF NOT EXISTS `site_membres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_current_perso` int(11) NOT NULL DEFAULT '1',
  `zenis` int(255) NOT NULL DEFAULT '5000',
  `fouille` int(1) NOT NULL DEFAULT '10',
  `last_refresh_fouille` bigint(20) NOT NULL,
  `pseudo` varchar(32) NOT NULL,
  `mdp` varchar(40) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `inscription` bigint(20) NOT NULL,
  `naissance` varchar(20) NOT NULL,
  `sexe` varchar(20) NOT NULL,
  `siteweb` varchar(64) NOT NULL,
  `localisation` varchar(255) NOT NULL,
  `profession` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `biographie` text NOT NULL,
  `signature` text NOT NULL,
  `derniere_visite` bigint(20) NOT NULL,
  `jeu_rang` tinyint(4) NOT NULL DEFAULT '0',
  `forum_rang` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0',
  `groupe` varchar(40) NOT NULL DEFAULT 'Non validé',
  `nb_post` int(11) NOT NULL DEFAULT '0',
  `bannis_raison` text NOT NULL,
  `valider` int(11) NOT NULL DEFAULT '0',
  `mp_bloqued` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membre_pseudo` (`pseudo`),
  UNIQUE KEY `membre_mail` (`mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Structure de la table `site_membres_config`
--

CREATE TABLE IF NOT EXISTS `site_membres_config` (
  `id_membre` int(11) NOT NULL,
  `mail_news` enum('0','1') NOT NULL DEFAULT '0',
  `mail_mp` enum('0','1') NOT NULL DEFAULT '0',
  `mail_forum_topic` enum('0','1') NOT NULL DEFAULT '0',
  `mp_kill` enum('0','1') NOT NULL DEFAULT '0',
  `mp_dead` enum('0','1') NOT NULL DEFAULT '0',
  `mp_caps_sell` enum('0','1') NOT NULL DEFAULT '0',
  `mp_objet_sell` enum('0','1') NOT NULL DEFAULT '0',
  `echange` enum('0','1') NOT NULL DEFAULT '0',
  `safe_connexion` enum('0','1') NOT NULL DEFAULT '0',
  UNIQUE KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `site_mp`
--

CREATE TABLE IF NOT EXISTS `site_mp` (
  `conversation_id` int(20) NOT NULL,
  `mp_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_expediteur` int(11) NOT NULL,
  `mp_receveur` int(11) NOT NULL,
  `conversation_titre` varchar(100) NOT NULL,
  `mp_text` text NOT NULL,
  `mp_time` bigint(20) NOT NULL,
  `mp_lu` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`mp_id`),
  UNIQUE KEY `mp_id` (`mp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Contenu de la table `site_mp`
--


-- --------------------------------------------------------

--
-- Structure de la table `site_news`
--

CREATE TABLE IF NOT EXISTS `site_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membre_pseudo` varchar(64) NOT NULL,
  `titre` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `site_news`
--

INSERT INTO `site_news` (`id`, `membre_pseudo`, `titre`, `message`, `time`) VALUES
(1, 'Stevens', 'Sortie de la bêta test', 'Bonjour et bienvenue à la bêta-test de Dragon Ball Universe !\r\n\r\nCette bêta-test aura une durée indéterminée.\r\n\r\nPour plus d''informations concernant les fonctionnalités de cette bêta-test, rendez-vous [url=http://dbuniverse.fr/forum/?view=topic&t=1]ici[/url]', 1417090723),
(2, 'Stevens', 'Points à distribuer', 'Je passe ce petit règlement de bug en news afin que tout le monde puisse le voir facilement.\r\n\r\nActuellement, les personnages avait 6 points à distribuer dès leur niveau 1, et normalement, à chaque monté de niveau, il y a à nouveau 6 points à distribuer, mais ceux-ci n''étaient pas ajouté. J''ai donc rectifié le soucis, et ai ajouté les points à distribuer en fonction du niveau des personnages qui étaient supérieur au niveau 1.', 1417126351),
(3, 'Stevens', 'Informations sur la bêta test', 'Bonsoir ! Actuellement je viens de postés un message sur le forum concernant les informations relatives à la bêta-test ! Je vous invite donc à aller lire ce sujet [url=http://www.dbuniverse.fr/forum/?view=topic&t=13&page=1]ici[/url]\r\n\r\n\r\nEdit : L''expérience et zénis gagné pour le perdant ont été augmentés.\r\n[b]Stevens.[/b]', 1417195436),
(4, 'Stevens', 'Vacances ?', 'Une nouvelle news a été posté, rendez-vous [url=http://dbuniverse.fr/forum/?view=topic&t=23]ici[/url] pour plus d''informations.', 1418941655),
(5, 'Stevens', 'Noël & nouvel an', 'Je vous souhaite à tous un bon Noël (en retard :o) et une bonne nouvelle année à tous ! En espérant que vous aviez tous de bonnes résolutions pour cette année 2015 ! \r\n\r\nPour nous (équipe DBU) ce sera de sortir la version 1 officielle ;)\r\n\r\nEn espérant vous revoir parmi nous sur DBU lors de sa sortie officielle !\r\n\r\nPlusieurs fonctionnalités seront instauré lors la prochaine mise à jours afin de diversifier la bêta, et pour plus tard, je lancerais une première version du mode histoire une fois celle-ci commencée et fini (développement), après la mise à jours.\r\n\r\nPour le moment, je refais (comme déjà dit) la refonte globale du jeu. Celle-ci avance doucement mais sûrement.', 1420073800),
(6, 'Stevens', 'Faille Forum', 'Bonjour à tous, une faille du forum a été découvert et abusé par Trunks8, ce membre a été bannis.\n\nCependant, étant donné que ce ne soit qu''une simple bêta test, aucune sauvegarde ni rien de ce genre est fait.\n\nN''ayez crainte, la faille se situe seulement dans le forum, et pas ailleurs. Je pourrais donc me protéger contre cette faille sur la nouvelle version.\n\nJ''avance petit à petit, mais ai été freiné à cause d''un problème personnel.\n\nÀ très vite :)\n\n[b]\nEdit : [/b] J''ai désactivé le forum, je ferais partager les nouveautés par le biais de la news ;)', 1420812659);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
