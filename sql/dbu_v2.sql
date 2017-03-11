-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Sam 11 Mars 2017 à 00:59
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbu`
--

-- --------------------------------------------------------

--
-- Structure de la table `forum_categorie`
--

CREATE TABLE `forum_categorie` (
  `cat_id` int(11) NOT NULL,
  `cat_nom` varchar(65) NOT NULL,
  `cat_ordre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_categorie`
--

INSERT INTO `forum_categorie` (`cat_id`, `cat_nom`, `cat_ordre`) VALUES
(1, 'Général', 10),
(2, 'Évènement', 50),
(3, 'Autre', 20);

-- --------------------------------------------------------

--
-- Structure de la table `forum_config`
--

CREATE TABLE `forum_config` (
  `config_id` int(11) NOT NULL,
  `config_nom` varchar(65) NOT NULL,
  `config_value` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_config`
--

INSERT INTO `forum_config` (`config_id`, `config_nom`, `config_value`) VALUES
(1, 'post_par_page', '15'),
(2, 'topic_par_page', '30');

-- --------------------------------------------------------

--
-- Structure de la table `forum_forum`
--

CREATE TABLE `forum_forum` (
  `forum_id` int(11) NOT NULL,
  `forum_cat_id` int(11) NOT NULL,
  `forum_nom` varchar(65) NOT NULL,
  `forum_desc` text NOT NULL,
  `forum_ordre` int(11) NOT NULL,
  `forum_last_post_id` int(11) NOT NULL,
  `forum_nb_topic` int(11) NOT NULL DEFAULT '0',
  `forum_nb_post` int(11) NOT NULL DEFAULT '0',
  `forum_locked` enum('0','1') NOT NULL DEFAULT '0',
  `forum_auth_view` tinyint(7) NOT NULL DEFAULT '1' COMMENT 'Droit de lecture; Nécessite un rang égal ou supérieur à celui défini',
  `forum_auth_post` tinyint(7) NOT NULL DEFAULT '1' COMMENT 'Droit de répondre; Nécessite un rang égal ou supérieur à celui défini',
  `forum_auth_topic` tinyint(7) NOT NULL DEFAULT '1' COMMENT 'Droit de créer un sujet; Nécessite un rang égal ou supérieur à celui défini',
  `forum_auth_annonce` tinyint(7) NOT NULL DEFAULT '4' COMMENT 'Droit de créer un sujet d''annonce; Nécessite un rang égal ou supérieur à celui défini',
  `forum_auth_modo` tinyint(7) NOT NULL DEFAULT '4' COMMENT 'Droit de modération; Nécessite un rang égal ou supérieur à celui défini'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_forum`
--

INSERT INTO `forum_forum` (`forum_id`, `forum_cat_id`, `forum_nom`, `forum_desc`, `forum_ordre`, `forum_last_post_id`, `forum_nb_topic`, `forum_nb_post`, `forum_locked`, `forum_auth_view`, `forum_auth_post`, `forum_auth_topic`, `forum_auth_annonce`, `forum_auth_modo`) VALUES
(1, 1, 'Nouveauté', 'Toutes les news', 10, 0, 0, 0, '1', 1, 4, 4, 4, 4),
(2, 1, 'Bugs', 'Rapports de bugs ici', 40, 0, 0, 0, '0', 1, 1, 1, 4, 4),
(3, 1, 'Suggestions d\'idée', 'Une idée à faire partager ? C\'est ici ;)', 30, 0, 0, 0, '0', 1, 1, 1, 4, 4),
(4, 2, 'Tournois', 'rzrez', 0, 3, 2, 2, '0', 1, 1, 4, 4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `forum_post`
--

CREATE TABLE `forum_post` (
  `post_id` int(11) NOT NULL,
  `post_id_createur` int(11) NOT NULL,
  `post_texte` text NOT NULL,
  `post_time` int(11) NOT NULL,
  `post_topic_id` int(11) NOT NULL,
  `post_forum_id` int(11) NOT NULL,
  `post_edit` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => pas edité; 1 => édité',
  `post_edit_time` int(11) NOT NULL DEFAULT '0',
  `post_edit_id_membre` int(11) NOT NULL DEFAULT '0',
  `post_suppr` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => pas supprimé; 1 => supprimé'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `forum_post`
--

INSERT INTO `forum_post` (`post_id`, `post_id_createur`, `post_texte`, `post_time`, `post_topic_id`, `post_forum_id`, `post_edit`, `post_edit_time`, `post_edit_id_membre`, `post_suppr`) VALUES
(1, 3, 'Test\r\n\r\ntest 2\r\n\r\ntest3\r\n\r\ntest 4', 1420915429, 1, 4, '1', 1422650660, 3, '0'),
(2, 14, 're\r\n\r\nNooop ! :(', 1420918956, 1, 4, '1', 1422730414, 3, '1'),
(3, 3, 'Bah c\'est un fake, y\'a pas de tournois roh !', 1421191903, 2, 4, '0', 0, 0, '0'),
(4, 3, 'TEst', 1422745796, 1, 4, '0', 0, 0, '1'),
(5, 3, 'Prout', 1422745815, 2, 4, '0', 0, 0, '1'),
(6, 3, 'test', 1422919436, 1, 4, '0', 0, 0, '1'),
(7, 3, 'test rthtr', 1422919451, 1, 4, '0', 0, 0, '1'),
(8, 3, 'gfgfgf\r\n\r\n\r\n\r\n\r\n\r\ngf', 1423134281, 1, 4, '0', 0, 0, '1');

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic`
--

CREATE TABLE `forum_topic` (
  `topic_id` int(11) NOT NULL,
  `topic_forum_id` int(11) NOT NULL,
  `topic_id_createur` int(11) NOT NULL,
  `topic_titre` varchar(65) NOT NULL,
  `topic_vu` int(11) NOT NULL,
  `topic_genre` enum('1','2') NOT NULL COMMENT '1 => normal; 2 => annonce; 3 => vu all',
  `topic_time` int(11) NOT NULL,
  `topic_nb_message` int(11) NOT NULL,
  `topic_first_post_id` int(11) NOT NULL,
  `topic_last_post_id` int(11) NOT NULL,
  `topic_resolved` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => pas résolu; 1 => résolu;',
  `topic_locked` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => non; 1 => oui',
  `topic_suppr` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => non; 1 => oui;'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `forum_topic`
--

INSERT INTO `forum_topic` (`topic_id`, `topic_forum_id`, `topic_id_createur`, `topic_titre`, `topic_vu`, `topic_genre`, `topic_time`, `topic_nb_message`, `topic_first_post_id`, `topic_last_post_id`, `topic_resolved`, `topic_locked`, `topic_suppr`) VALUES
(1, 4, 3, 'test', 0, '2', 1420915429, 1, 1, 1, '1', '1', '0'),
(2, 4, 3, 'Tournois ? Fakee haha', 0, '1', 1421191903, 1, 3, 3, '1', '1', '0');

-- --------------------------------------------------------

--
-- Structure de la table `forum_topic_view`
--

CREATE TABLE `forum_topic_view` (
  `tv_id_membre` int(11) NOT NULL,
  `tv_topic_id` int(11) NOT NULL,
  `tv_forum_id` int(11) NOT NULL,
  `tv_post_id` int(11) NOT NULL,
  `tv_poste` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `jeu_capsule_corp`
--

CREATE TABLE `jeu_capsule_corp` (
  `id` int(11) NOT NULL,
  `id_capsule` int(11) NOT NULL,
  `capsule_type` int(11) NOT NULL,
  `restant` int(11) NOT NULL,
  `refresh` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `jeu_capsule_corp`
--

INSERT INTO `jeu_capsule_corp` (`id`, `id_capsule`, `capsule_type`, `restant`, `refresh`) VALUES
(7870, 45, 2, 20, 1417370045),
(7871, 48, 2, 20, 1417370045),
(7872, 49, 2, 20, 1417370046),
(7873, 20, 1, 60, 1417370046),
(7874, 16, 1, 60, 1417370046),
(7875, 29, 1, 60, 1417370046),
(7876, 18, 1, 60, 1417370046),
(7877, 26, 1, 60, 1417370046),
(7878, 12, 1, 60, 1417370046),
(7879, 11, 1, 60, 1417370046),
(7880, 4, 1, 60, 1417370046);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_level`
--

CREATE TABLE `jeu_level` (
  `level` int(11) NOT NULL,
  `exp_required` int(100) NOT NULL
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

CREATE TABLE `jeu_level_capsule` (
  `id` int(255) NOT NULL,
  `id_type_capsule` int(1) NOT NULL,
  `level` int(11) NOT NULL,
  `exp_require` int(255) NOT NULL,
  `bonus` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_level_capsule`
--

INSERT INTO `jeu_level_capsule` (`id`, `id_type_capsule`, `level`, `exp_require`, `bonus`) VALUES
(1, 1, 1, 0, 0),
(2, 1, 2, 500, 25),
(3, 1, 3, 1000, 50),
(4, 1, 4, 1500, 75),
(5, 1, 5, 2000, 100),
(6, 2, 1, 0, 0),
(7, 2, 2, 500, 25),
(8, 2, 3, 1000, 50),
(9, 2, 4, 1500, 75),
(10, 2, 5, 2000, 100),
(11, 3, 1, 0, 0),
(12, 3, 2, 1000, 25),
(13, 3, 3, 2000, 50),
(14, 3, 4, 3000, 75),
(15, 3, 5, 4000, 100);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_capsule`
--

CREATE TABLE `jeu_liste_capsule` (
  `id` int(11) NOT NULL,
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
  `prix` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_liste_capsule`
--

INSERT INTO `jeu_liste_capsule` (`id`, `type`, `id_perso_require`, `nom`, `degat`, `puissance`, `defense`, `magie`, `chance`, `vitesse`, `concentration`, `vie`, `energie`, `prix`) VALUES
(1, '1', 0, 'Chôshinsui', '0.0', '+10', '+10', '+10', '+10', '+10', '+10', '+500', '+250', 3000000),
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
(12, '1', 0, 'Fruit de l\'Enfer', '0.0', '+7', '+7', '-5', '+2', '+2', '-2', '+250', '+500', 10000),
(13, '1', 0, 'Sabre ensorcelé ', '0.0', '-5', '-2', '+5', '+7', '+2', '+2', '+250', '+125', 10000),
(14, '1', 0, 'Magie de Babidi', '0.0', '-2', '-5', '+7', '+2', '+2', '+7', '-250', '+300', 20000),
(15, '1', 0, 'Chance titanesque ', '0.0', '+5', '+5', '-7', '+7', '+5', '+5', '+300', '-125', 20000),
(16, '1', 0, 'Boite magique', '0.0', '-5', '0', '+7', '-5', '+7', '+5', '+250', '+125', 20000),
(17, '1', 0, 'Set de Saibaman', '0.0', '+7', '+5', '+7', '-2', '-7', '+5', '+300', '-125', 20000),
(18, '1', 0, 'Danse de l\'air', '0.0', '+5', '+7', '-7', '-5', '+7', '-2', '+250', '-125', 40000),
(19, '1', 0, 'Choseisui ', '0.0', '+7', '-5', '+7', '-5', '+7', '7', '+200', '-250', 40000),
(20, '1', 0, 'Tenue de Kaioh', '0.0', '+2', '+7', '-7', '+5', '+2', '-7', '+300', '+125', 40000),
(21, '1', 0, 'Colère suprème', '0.0', '+7', '+2', '-7', '+2', '+5', '-5', '+400', '+125', 40000),
(22, '1', 0, 'Bonbon de Buu', '0.0', '0', '+2', '+7', '0', '+7', '+7', '+400', '+250', 55000),
(23, '1', 0, 'Ceinture d\'Hercule', '0.0', '+7', '0', '0', '+7', '0', '+2', '+350', '+175', 55000),
(24, '1', 0, 'Tenue lourde', '0.0', '+2', '+7', '+2', '+5', '+2', '0', '+350', '+175', 55000),
(25, '1', 0, 'Z-Sword', '0.0', '5', '+5', '+5', '+2', '+5', '0', '+350', '0', 55000),
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

CREATE TABLE `jeu_liste_combat` (
  `id_combat` int(255) NOT NULL,
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
  `round6_attaquant` varchar(255) NOT NULL,
  `round6_attaquant_chiffre` int(255) NOT NULL,
  `round7_attaquant` varchar(255) NOT NULL,
  `round7_attaquant_chiffre` int(255) NOT NULL,
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
  `round6_defenseur` varchar(255) NOT NULL,
  `round6_defenseur_chiffre` int(255) NOT NULL,
  `round7_defenseur` varchar(255) NOT NULL,
  `round7_defenseur_chiffre` int(255) NOT NULL,
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
  `saved` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 : pas sauvegardé; 1: sauvegardé'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_liste_combat`
--

INSERT INTO `jeu_liste_combat` (`id_combat`, `id_membre_attaquant`, `id_perso_attaquant`, `round1_attaquant`, `round1_attaquant_chiffre`, `round2_attaquant`, `round2_attaquant_chiffre`, `round3_attaquant`, `round3_attaquant_chiffre`, `round4_attaquant`, `round4_attaquant_chiffre`, `round5_attaquant`, `round5_attaquant_chiffre`, `round6_attaquant`, `round6_attaquant_chiffre`, `round7_attaquant`, `round7_attaquant_chiffre`, `id_membre_defenseur`, `id_perso_defenseur`, `round1_defenseur`, `round1_defenseur_chiffre`, `round2_defenseur`, `round2_defenseur_chiffre`, `round3_defenseur`, `round3_defenseur_chiffre`, `round4_defenseur`, `round4_defenseur_chiffre`, `round5_defenseur`, `round5_defenseur_chiffre`, `round6_defenseur`, `round6_defenseur_chiffre`, `round7_defenseur`, `round7_defenseur_chiffre`, `terrain`, `etat_fight`, `victoire`, `defaite`, `match_nul`, `gain_exp_adv`, `gain_zenis_adv`, `gain_exp_def`, `gain_zenis_def`, `date`, `saved`) VALUES
(21, 3, 1, 'defense', 224, 'attaque', 705, 'attaque', 648, 'defense', 138, 'defense', 227, 'defense', 115, 'attaque', 588, 14, 1, 'attaque', 103, 'defense', 46, 'attaque', 127, 'defense', 38, 'attaque', 104, 'defense', 71, 'attaque', 119, '1', '2', 3, 14, 0, 4563, 1449, 1978, 752, 1423134148, '0'),
(22, 4, 1, 'defense', 46, 'attaque', 56, 'defense', 30, 'attaque', 49, 'defense', 29, 'attaque', 43, 'defense', 26, 15, 3, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '7', '0', 0, 0, 0, 0, 0, 0, 0, 1424157912, '0');

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_membre_capsule`
--

CREATE TABLE `jeu_liste_membre_capsule` (
  `id` int(11) NOT NULL,
  `id_capsule` int(11) NOT NULL,
  `level_capsule` int(11) NOT NULL DEFAULT '1',
  `experience` int(255) NOT NULL DEFAULT '0',
  `id_membre` int(11) NOT NULL,
  `id_perso_equipe` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_liste_membre_capsule`
--

INSERT INTO `jeu_liste_membre_capsule` (`id`, `id_capsule`, `level_capsule`, `experience`, `id_membre`, `id_perso_equipe`) VALUES
(1, 27, 1, 0, 3, 0),
(2, 25, 5, 55000, 3, 0),
(3, 34, 1, 0, 2, 1),
(4, 15, 1, 0, 2, 1),
(5, 18, 1, 0, 2, 1),
(6, 34, 1, 0, 2, 0),
(7, 11, 1, 0, 2, 1),
(8, 17, 1, 0, 2, 1),
(9, 11, 1, 0, 2, 1),
(10, 32, 1, 0, 2, 1),
(11, 34, 1, 0, 2, 0),
(12, 32, 1, 0, 2, 1),
(13, 18, 1, 0, 2, 0),
(14, 34, 1, 0, 2, 0),
(15, 34, 1, 0, 2, 0),
(16, 34, 1, 0, 2, 0),
(17, 34, 1, 0, 2, 0),
(18, 19, 1, 0, 2, 0),
(19, 32, 1, 0, 2, 1),
(20, 23, 1, 0, 2, 0),
(21, 2, 1, 0, 2, 0),
(22, 33, 1, 0, 2, 0),
(23, 29, 1, 10, 3, 0),
(24, 33, 1, 0, 3, 0),
(25, 30, 1, 0, 2, 0),
(26, 33, 1, 0, 2, 0),
(27, 25, 1, 0, 2, 0),
(28, 28, 1, 10, 3, 0),
(29, 28, 1, 0, 3, 0),
(30, 1, 1, 0, 12, 0),
(31, 23, 1, 0, 12, 0),
(32, 33, 1, 0, 12, 0),
(33, 28, 1, 0, 12, 0),
(34, 9, 1, 10, 3, 0),
(35, 32, 1, 0, 3, 0),
(36, 32, 1, 0, 3, 0),
(37, 1, 3, 1420, 3, 1),
(38, 1, 3, 1420, 3, 1),
(39, 1, 3, 1420, 3, 1),
(40, 1, 3, 1420, 3, 1),
(41, 1, 3, 1420, 3, 1),
(42, 48, 1, 0, 3, 0),
(43, 41, 4, 1550, 3, 1),
(44, 41, 4, 1550, 3, 1),
(45, 43, 4, 1540, 3, 1),
(46, 1, 2, 730, 14, 1);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_membre_perso`
--

CREATE TABLE `jeu_liste_membre_perso` (
  `id_list` int(255) NOT NULL,
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
  `match_nul` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_liste_membre_perso`
--

INSERT INTO `jeu_liste_membre_perso` (`id_list`, `id_membre`, `id_perso`, `level`, `experience`, `points_distrib`, `x`, `y`, `stats_puissance`, `stats_defense`, `stats_magie`, `stats_chance`, `stats_vitesse`, `stats_concentration`, `stats_vie`, `stats_energie`, `ki`, `caps_verte_1`, `caps_rouge_1`, `caps_rouge_2`, `caps_rouge_3`, `caps_rouge_4`, `caps_jaune_1`, `caps_jaune_2`, `caps_jaune_3`, `caps_jaune_4`, `caps_jaune_5`, `avatar_lien`, `match_victoire`, `match_defaite`, `match_tuer`, `match_mort`, `match_nul`) VALUES
(1, 2, 1, -9695, 1, 6, 7, 6, -999999724, 580, 180, 255, 355, 280, 29000, 11000, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 5934, 0, 0),
(2, 3, 1, 3, 79181, 0, 4, 5, 202, 102, 92, 98, 92, 102, 3925, 4562, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 23, 39, 2, 9, 8),
(3, 4, 1, 1, 0, 6, 7, 1, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(4, 5, 1, 1, 0, 6, 9, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(5, 6, 1, 1, 0, 6, 7, 1, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(6, 7, 1, 1, 0, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(10, 8, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(11, 9, 1, 1, 530, 6, 9, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 1, 0, 0, 0),
(12, 10, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(13, 11, 1, 1, 0, 6, 5, 4, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(14, 12, 1, 1, 0, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(15, 14, 1, 2, 42437, 0, 4, 5, 26, 20, 20, 20, 20, 20, 1500, 750, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 10, 16, 9, 2, 8),
(16, 3, 2, 1, 1452, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 1000, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 1, 1, 0, 0, 0),
(17, 3, 3, 1, 3143, 6, 4, 5, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 2, 0, 0, 0, 0),
(18, 14, 3, 1, 205, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 1, 0, 0, 0),
(19, 14, 2, 1, 473, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 2, 0, 0, 0),
(20, 15, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/1_2.jpg', 0, 0, 0, 0, 0),
(21, 15, 2, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/bulma/01_03.jpg', 0, 0, 0, 0, 0),
(22, 15, 3, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '/images/jeu_avatar/tortue_geniale/01_03.jpg', 0, 0, 0, 0, 0),
(23, 16, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0),
(24, 17, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0),
(25, 18, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0),
(26, 19, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0),
(27, 20, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0),
(28, 17, 1, 1, 0, 6, 7, 6, 10, 10, 10, 10, 10, 10, 1000, 500, 1150, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_liste_personnage`
--

CREATE TABLE `jeu_liste_personnage` (
  `id_perso` int(11) NOT NULL,
  `nom_personnage` varchar(64) NOT NULL,
  `short_name` varchar(64) NOT NULL,
  `icone` varchar(255) NOT NULL,
  `alternative` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Structure de la table `jeu_liste_perso_avatar`
--

CREATE TABLE `jeu_liste_perso_avatar` (
  `id` int(11) NOT NULL,
  `id_perso` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `chemin_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `jeu_liste_perso_avatar`
--

INSERT INTO `jeu_liste_perso_avatar` (`id`, `id_perso`, `level`, `chemin_image`) VALUES
(1, 1, 1, 'images/jeu_avatar/chibi_goku/01_03.jpg'),
(2, 1, 2, 'images/jeu_avatar/chibi_goku/01_03.jpg'),
(3, 1, 3, 'images/jeu_avatar/chibi_goku/01_03.jpg'),
(4, 1, 4, 'images/jeu_avatar/chibi_goku/04_06.jpg'),
(5, 1, 5, 'images/jeu_avatar/chibi_goku/04_06.jpg'),
(6, 1, 6, 'images/jeu_avatar/chibi_goku/04_06.jpg'),
(7, 1, 7, 'images/jeu_avatar/chibi_goku/07_09.jpg'),
(8, 1, 8, 'images/jeu_avatar/chibi_goku/07_09.jpg'),
(9, 1, 9, 'images/jeu_avatar/chibi_goku/07_09.jpg'),
(10, 1, 10, 'images/jeu_avatar/chibi_goku/10_12.jpg'),
(11, 1, 11, 'images/jeu_avatar/chibi_goku/10_12.jpg'),
(12, 1, 12, 'images/jeu_avatar/chibi_goku/10_12.jpg'),
(13, 1, 13, 'images/jeu_avatar/chibi_goku/13_15.jpg'),
(14, 1, 14, 'images/jeu_avatar/chibi_goku/13_15.jpg'),
(15, 1, 15, 'images/jeu_avatar/chibi_goku/13_15.jpg'),
(16, 1, 16, 'images/jeu_avatar/chibi_goku/16_18.jpg'),
(17, 1, 17, 'images/jeu_avatar/chibi_goku/16_18.jpg'),
(18, 1, 18, 'images/jeu_avatar/chibi_goku/16_18.jpg'),
(19, 1, 19, 'images/jeu_avatar/chibi_goku/19_21.jpg'),
(20, 1, 20, 'images/jeu_avatar/chibi_goku/19_21.jpg'),
(21, 2, 1, 'images/jeu_avatar/bulma/01_03.jpg'),
(22, 2, 2, 'images/jeu_avatar/bulma/01_03.jpg'),
(23, 2, 3, 'images/jeu_avatar/bulma/01_03.jpg'),
(24, 2, 4, 'images/jeu_avatar/bulma/04_06.jpg'),
(25, 2, 5, 'images/jeu_avatar/bulma/04_06.jpg'),
(26, 2, 6, 'images/jeu_avatar/bulma/04_06.jpg'),
(27, 2, 7, 'images/jeu_avatar/bulma/07_09.jpg'),
(28, 2, 8, 'images/jeu_avatar/bulma/07_09.jpg'),
(29, 2, 9, 'images/jeu_avatar/bulma/07_09.jpg'),
(30, 2, 10, 'images/jeu_avatar/bulma/10_12.jpg'),
(31, 2, 11, 'images/jeu_avatar/bulma/10_12.jpg'),
(32, 2, 12, 'images/jeu_avatar/bulma/10_12.jpg'),
(33, 2, 13, 'images/jeu_avatar/bulma/13_15.jpg'),
(34, 2, 14, 'images/jeu_avatar/bulma/13_15.jpg'),
(35, 2, 15, 'images/jeu_avatar/bulma/13_15.jpg'),
(36, 2, 16, 'images/jeu_avatar/bulma/16_18.jpg'),
(37, 2, 17, 'images/jeu_avatar/bulma/16_18.jpg'),
(38, 2, 18, 'images/jeu_avatar/bulma/16_18.jpg'),
(39, 2, 19, 'images/jeu_avatar/bulma/19_21.jpg'),
(40, 2, 20, 'images/jeu_avatar/bulma/19_21.jpg'),
(41, 3, 1, 'images/jeu_avatar/tortue_geniale/01_03.jpg'),
(42, 3, 2, 'images/jeu_avatar/tortue_geniale/01_03.jpg'),
(43, 3, 3, 'images/jeu_avatar/tortue_geniale/01_03.jpg'),
(44, 3, 4, 'images/jeu_avatar/tortue_geniale/04_06.jpg'),
(45, 3, 5, 'images/jeu_avatar/tortue_geniale/04_06.jpg'),
(46, 3, 6, 'images/jeu_avatar/tortue_geniale/04_06.jpg'),
(47, 3, 7, 'images/jeu_avatar/tortue_geniale/07_09.jpg'),
(48, 3, 8, 'images/jeu_avatar/tortue_geniale/07_09.jpg'),
(49, 3, 9, 'images/jeu_avatar/tortue_geniale/07_09.jpg'),
(50, 3, 10, 'images/jeu_avatar/tortue_geniale/10_12.jpg'),
(51, 3, 11, 'images/jeu_avatar/tortue_geniale/10_12.jpg'),
(52, 3, 12, 'images/jeu_avatar/tortue_geniale/10_12.jpg'),
(53, 3, 13, 'images/jeu_avatar/tortue_geniale/13_15.jpg'),
(54, 3, 14, 'images/jeu_avatar/tortue_geniale/13_15.jpg'),
(55, 3, 15, 'images/jeu_avatar/tortue_geniale/13_15.jpg'),
(56, 3, 16, 'images/jeu_avatar/tortue_geniale/16_18.jpg'),
(57, 3, 17, 'images/jeu_avatar/tortue_geniale/16_18.jpg'),
(58, 3, 18, 'images/jeu_avatar/tortue_geniale/16_18.jpg'),
(59, 3, 19, 'images/jeu_avatar/tortue_geniale/19_21.jpg'),
(60, 3, 20, 'images/jeu_avatar/tortue_geniale/19_21.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `jeu_map_action`
--

CREATE TABLE `jeu_map_action` (
  `id` int(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL,
  `x` int(255) NOT NULL,
  `y` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `jeu_map_action`
--

INSERT INTO `jeu_map_action` (`id`, `titre`, `lien`, `x`, `y`) VALUES
(1, 'Capsule Corp', '&action=capsulecorp', 4, 5);

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE `log` (
  `id` int(20) NOT NULL,
  `log` text NOT NULL,
  `nom_personnage` varchar(255) NOT NULL,
  `nom_membre` varchar(255) NOT NULL,
  `file` varchar(200) NOT NULL,
  `ligne` int(200) NOT NULL,
  `date` bigint(20) NOT NULL,
  `vu` enum('0','1') NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `log_admin`
--

CREATE TABLE `log_admin` (
  `id` int(20) NOT NULL,
  `log` text NOT NULL,
  `nom_personnage` varchar(255) NOT NULL,
  `nom_membre` varchar(255) NOT NULL,
  `file` varchar(200) NOT NULL,
  `ligne` int(200) NOT NULL,
  `date` bigint(20) NOT NULL,
  `vu` enum('0','1') NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `preinscription`
--

CREATE TABLE `preinscription` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `preinscription`
--

INSERT INTO `preinscription` (`id`, `email`, `ip`) VALUES
(1, 'stevensc92@gmail.com', '89.3.183.145'),
(2, 'DirtyTwix@hotmail.fr', '82.229.102.210'),
(3, 'naruto0017@hotmail.com', '109.215.174.228'),
(4, 'prince-bejita@hotmail.fr', '109.215.174.228'),
(5, 'fanatique-dragon-ball-z@hotmail.fr', '109.215.174.228'),
(6, 'ottawan17@hotmail.fr', '109.215.174.228'),
(7, 'th4nbull3t@gmail.com', '90.46.196.206');

-- --------------------------------------------------------

--
-- Structure de la table `site_connectes`
--

CREATE TABLE `site_connectes` (
  `connectes_id` int(11) NOT NULL,
  `connectes_ip` varchar(16) NOT NULL,
  `connectes_membre` varchar(16) NOT NULL,
  `connectes_actualisation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `site_connectes`
--

INSERT INTO `site_connectes` (`connectes_id`, `connectes_ip`, `connectes_membre`, `connectes_actualisation`) VALUES
(3, '::1', '1', 1489193821),
(17, '81.65.198.159', '1', 1425397021);

-- --------------------------------------------------------

--
-- Structure de la table `site_membres`
--

CREATE TABLE `site_membres` (
  `id` int(11) NOT NULL,
  `id_current_perso` int(11) NOT NULL DEFAULT '1',
  `zenis` int(255) NOT NULL DEFAULT '5000',
  `fouille` int(1) NOT NULL DEFAULT '10',
  `last_refresh_fouille` bigint(20) NOT NULL,
  `pseudo` varchar(32) NOT NULL,
  `pseudo_changed` enum('0','1') NOT NULL DEFAULT '0',
  `mdp` varchar(40) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `inscription` bigint(20) NOT NULL,
  `naissance` varchar(20) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `signature` text NOT NULL,
  `derniere_visite` bigint(20) NOT NULL,
  `rang` enum('0','1','2','3','4','5','6') NOT NULL DEFAULT '0',
  `groupe` varchar(40) NOT NULL DEFAULT 'Non validé',
  `nb_post` int(11) NOT NULL DEFAULT '0',
  `bannis_raison` text NOT NULL,
  `valider` int(11) NOT NULL DEFAULT '0',
  `mp_bloqued` text NOT NULL,
  `ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `site_membres`
--

INSERT INTO `site_membres` (`id`, `id_current_perso`, `zenis`, `fouille`, `last_refresh_fouille`, `pseudo`, `pseudo_changed`, `mdp`, `mail`, `inscription`, `naissance`, `avatar`, `signature`, `derniere_visite`, `rang`, `groupe`, `nb_post`, `bannis_raison`, `valider`, `mp_bloqued`, `ip`) VALUES
(1, 1, 254, 10, 1403369157, 'L\'équipe DBU', '0', '', 't@t.g', 1386879809, '12/12/2013', '', 'test signature', 1386879809, '6', 'Robot', 0, '', 1, '', ''),
(2, 1, 30747, 0, 1405029297, 'Damien', '0', '326a9926e61ce197118582f373ba76c54eb15bfa', 'damien.renaud2@gmail.com', 1401835098, '', '', '', 1401835098, '3', 'Membre', 0, '', 1, '', ''),
(3, 1, 39993, 10, 1417469651, 'Stevens', '0', 'f93a574b4d87f537c8ccfa8356cc468c1f14c3c1', 'stevensc92@gmail.com', 1401835138, '', '', 'test signature', 1418165946, '5', 'Membre', 2, '', 1, '', ''),
(4, 1, 5000, 10, 1403369157, 'Akashi', '0', '9fb33d4197f5f5c70abe337bd4b1f0b4f97e4970', 'mathieu97300@hotmail.fr', 1401836407, '', '', '', 1401836407, '2', 'Membre', 0, '', 1, '', ''),
(5, 1, 5000, 10, 1403369157, 'Erza', '0', '2a0c2ff9c2611a0042cd18c03f3e0dc509d9a408', '2fight.erza@laposte.net', 1401900058, '', '', '', 1401900058, '1', 'Membre', 0, '', 1, '', ''),
(6, 1, 5000, 10, 1403369157, 'Ragusen', '0', '2b1d769ea9326498cbf0f99869939ed1678e4d59', 'chevrot.marc21@gmail.com', 1401990406, '', '', '', 1401990406, '1', 'Membre', 0, '', 1, '', ''),
(7, 1, 5000, 10, 1403369157, 'Immo', '0', '57e930f11acc7454a54b1e873c63f9d5a3332824', 'nurhak1905@gmail.com', 1401997064, '', '', '', 1401997064, '1', 'Membre', 0, '', 1, '', ''),
(8, 1, 5000, 10, 1403369157, 'NB75', '0', 'ef6f9f5d6d35417a97392a7ddf0e1d6d4d275c19', 'nb.75011@gmail.com', 1402178193, '', '', '', 1402178193, '1', 'Membre', 0, '', 1, '', ''),
(9, 1, 5516, 10, 1410256657, 'TestDam', '0', '326a9926e61ce197118582f373ba76c54eb15bfa', 'daams57@live.fr', 1402326303, '', '', '', 1402326303, '1', 'Membre', 0, '', 1, '', ''),
(10, 1, 5000, 10, 1403369157, 'Kalaan', '0', '9c7b70854201eb5ef9b97333b68b41f754cb3f1d', 'contact.kalaan@gmail.com', 1403360131, '', '', '', 1403360131, '4', 'Membre', 0, '', 1, '', ''),
(11, 1, 6571, 0, 1409064123, 'Phelecar', '0', 'fa48785e849ec7167a1383a61b2c1092b76ef163', 'alexbd-crayven@hotmail.fr', 1409063998, '', '', '', 1409063998, '1', 'Membre', 0, '', 1, '', ''),
(12, 1, 36552, 10, 1415740791, 'Sg4', '0', '94d085c4dfe8991b3410b8d9cc104d136cf613ac', 'DirtyTwix@hotmail.fr', 1411304563, '', '', '', 1415744448, '1', 'Membre', 0, '', 1, '', ''),
(14, 1, 19099, 10, 1416165236, 'Shimaro', '0', 'f93a574b4d87f537c8ccfa8356cc468c1f14c3c1', 'shimaro@live.fr', 1416165236, '', '', '', 1417525968, '1', 'Membre', 0, '', 1, '', ''),
(15, 3, 5000, 10, 1417117941, 'Jetest', '0', 'ff579a1f92c1e5f29f879d54f55d402996dcd337', 'jetest@gmail.com', 1417117941, '', '', '', 1417118063, '1', 'Membre', 0, '', 1, '', ''),
(16, 1, 5000, 10, 1417815932, 'ghrujai', '0', 'e3f5c87fb4952aa93d1e84a494394ddaa9d97082', 'zeubi@gmail.com', 1417815932, '', '', '', 1417815932, '0', 'Non validé', 0, '', 0, '', ''),
(17, 1, 5000, 10, 1425396858, 'masterchoc', '0', '2ef6d8c2ec477d35dbb7f04faa3ae69b18f2d834', 'mel.florance@gmail.com', 1425396858, '', '', '', 1425396858, '1', 'Membre', 0, '', 1, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `site_membres_config`
--

CREATE TABLE `site_membres_config` (
  `id_membre` int(11) NOT NULL,
  `mail_news` enum('0','1') NOT NULL DEFAULT '0',
  `mail_mp` enum('0','1') NOT NULL DEFAULT '0',
  `mail_forum_topic` enum('0','1') NOT NULL DEFAULT '0',
  `mp_kill` enum('0','1') NOT NULL DEFAULT '0',
  `mp_dead` enum('0','1') NOT NULL DEFAULT '0',
  `mp_caps_sell` enum('0','1') NOT NULL DEFAULT '0',
  `mp_objet_sell` enum('0','1') NOT NULL DEFAULT '0',
  `echange` enum('0','1') NOT NULL DEFAULT '0',
  `safe_connexion` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `site_membres_config`
--

INSERT INTO `site_membres_config` (`id_membre`, `mail_news`, `mail_mp`, `mail_forum_topic`, `mp_kill`, `mp_dead`, `mp_caps_sell`, `mp_objet_sell`, `echange`, `safe_connexion`) VALUES
(3, '1', '0', '0', '1', '1', '1', '1', '1', '1'),
(15, '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(16, '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(17, '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(18, '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(19, '0', '0', '0', '0', '0', '0', '0', '0', '0'),
(20, '0', '0', '0', '0', '0', '0', '0', '0', '0');

-- --------------------------------------------------------

--
-- Structure de la table `site_mp`
--

CREATE TABLE `site_mp` (
  `mp_id` int(11) NOT NULL,
  `mp_expediteur` int(11) NOT NULL,
  `supp_expediteur` enum('0','1') NOT NULL DEFAULT '0',
  `mp_receveur` int(11) NOT NULL,
  `supp_receveur` enum('0','1') NOT NULL DEFAULT '0',
  `mp_titre` varchar(100) NOT NULL,
  `mp_text` text NOT NULL,
  `mp_time` bigint(20) NOT NULL,
  `mp_lu` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `site_mp`
--

INSERT INTO `site_mp` (`mp_id`, `mp_expediteur`, `supp_expediteur`, `mp_receveur`, `supp_receveur`, `mp_titre`, `mp_text`, `mp_time`, `mp_lu`) VALUES
(1, 3, '1', 14, '1', 'test', 'test', 1416484731, '1'),
(2, 3, '1', 14, '1', 'test', 'test également calay', 1416484761, '1'),
(3, 14, '1', 3, '1', 'test', '[b][i]visualisation[/i][/b][quote nom=bggg][spoiler nom=gfdgfdgfdgfdgfdgfdg]fgfdgdf[/spoiler][/quote]', 1416484782, '1'),
(4, 14, '1', 3, '1', 'test', 'long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte long texte', 1416484799, '1'),
(5, 14, '1', 3, '1', 'test', 'tette', 1416530955, '1'),
(6, 14, '1', 3, '1', '<strong>test</strong>', 'prout', 1417525931, '1'),
(7, 14, '1', 3, '1', 'test', 'test', 1417525943, '1'),
(8, 14, '1', 3, '1', 'test', 'test', 1417525961, '1'),
(9, 14, '1', 3, '1', 'Re : <strong>test</strong>', 'je t\'envoi un mp connard', 1418765274, '1'),
(10, 14, '1', 3, '1', 'Re : Re : <strong>test</strong>', 'WHAT ?!?!?!? TOU MTRAITE DE CONNARD ?!?!?', 1418765342, '1'),
(11, 14, '1', 3, '1', 'Re : Re : Re : <strong>test</strong>', 'OUAIS TAS UN BLEMPRO ? XD', 1418765355, '1'),
(12, 14, '1', 3, '1', 'Re : Re : Re : Re : <strong>test</strong>', 'rez', 1418766120, '1'),
(13, 14, '1', 3, '1', 'Re : Re : Re : Re : Re : <strong>test</strong>', '[u][s][url=][/url][/s][/u]', 1418767114, '1');

-- --------------------------------------------------------

--
-- Structure de la table `site_news`
--

CREATE TABLE `site_news` (
  `id` int(11) NOT NULL,
  `membre_pseudo` varchar(64) NOT NULL,
  `titre` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `time` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `site_news`
--

INSERT INTO `site_news` (`id`, `membre_pseudo`, `titre`, `message`, `time`) VALUES
(1, 'Stevens', 'test news', 'testgfdgfgf :-P :p :P :-phgfgfgfgf', 1416484960);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `forum_categorie`
--
ALTER TABLE `forum_categorie`
  ADD PRIMARY KEY (`cat_id`);

--
-- Index pour la table `forum_config`
--
ALTER TABLE `forum_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Index pour la table `forum_forum`
--
ALTER TABLE `forum_forum`
  ADD PRIMARY KEY (`forum_id`);

--
-- Index pour la table `forum_post`
--
ALTER TABLE `forum_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Index pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
  ADD PRIMARY KEY (`topic_id`);

--
-- Index pour la table `forum_topic_view`
--
ALTER TABLE `forum_topic_view`
  ADD PRIMARY KEY (`tv_id_membre`,`tv_topic_id`);

--
-- Index pour la table `jeu_capsule_corp`
--
ALTER TABLE `jeu_capsule_corp`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jeu_level`
--
ALTER TABLE `jeu_level`
  ADD UNIQUE KEY `level` (`level`);

--
-- Index pour la table `jeu_level_capsule`
--
ALTER TABLE `jeu_level_capsule`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jeu_liste_capsule`
--
ALTER TABLE `jeu_liste_capsule`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jeu_liste_combat`
--
ALTER TABLE `jeu_liste_combat`
  ADD PRIMARY KEY (`id_combat`);

--
-- Index pour la table `jeu_liste_membre_capsule`
--
ALTER TABLE `jeu_liste_membre_capsule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `jeu_liste_membre_perso`
--
ALTER TABLE `jeu_liste_membre_perso`
  ADD PRIMARY KEY (`id_list`),
  ADD UNIQUE KEY `id_list` (`id_list`);

--
-- Index pour la table `jeu_liste_personnage`
--
ALTER TABLE `jeu_liste_personnage`
  ADD PRIMARY KEY (`id_perso`);

--
-- Index pour la table `jeu_liste_perso_avatar`
--
ALTER TABLE `jeu_liste_perso_avatar`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jeu_map_action`
--
ALTER TABLE `jeu_map_action`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `log_admin`
--
ALTER TABLE `log_admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `preinscription`
--
ALTER TABLE `preinscription`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `site_connectes`
--
ALTER TABLE `site_connectes`
  ADD UNIQUE KEY `membre_id` (`connectes_id`,`connectes_membre`);

--
-- Index pour la table `site_membres`
--
ALTER TABLE `site_membres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `membre_pseudo` (`pseudo`),
  ADD UNIQUE KEY `membre_mail` (`mail`);

--
-- Index pour la table `site_membres_config`
--
ALTER TABLE `site_membres_config`
  ADD UNIQUE KEY `id_membre` (`id_membre`);

--
-- Index pour la table `site_mp`
--
ALTER TABLE `site_mp`
  ADD PRIMARY KEY (`mp_id`),
  ADD UNIQUE KEY `mp_id` (`mp_id`);

--
-- Index pour la table `site_news`
--
ALTER TABLE `site_news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `forum_categorie`
--
ALTER TABLE `forum_categorie`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `forum_config`
--
ALTER TABLE `forum_config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `forum_forum`
--
ALTER TABLE `forum_forum`
  MODIFY `forum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `forum_post`
--
ALTER TABLE `forum_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `forum_topic`
--
ALTER TABLE `forum_topic`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `jeu_capsule_corp`
--
ALTER TABLE `jeu_capsule_corp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7881;
--
-- AUTO_INCREMENT pour la table `jeu_level_capsule`
--
ALTER TABLE `jeu_level_capsule`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `jeu_liste_capsule`
--
ALTER TABLE `jeu_liste_capsule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT pour la table `jeu_liste_combat`
--
ALTER TABLE `jeu_liste_combat`
  MODIFY `id_combat` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT pour la table `jeu_liste_membre_capsule`
--
ALTER TABLE `jeu_liste_membre_capsule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT pour la table `jeu_liste_membre_perso`
--
ALTER TABLE `jeu_liste_membre_perso`
  MODIFY `id_list` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT pour la table `jeu_liste_personnage`
--
ALTER TABLE `jeu_liste_personnage`
  MODIFY `id_perso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `jeu_liste_perso_avatar`
--
ALTER TABLE `jeu_liste_perso_avatar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT pour la table `jeu_map_action`
--
ALTER TABLE `jeu_map_action`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `log_admin`
--
ALTER TABLE `log_admin`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `preinscription`
--
ALTER TABLE `preinscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `site_membres`
--
ALTER TABLE `site_membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `site_mp`
--
ALTER TABLE `site_mp`
  MODIFY `mp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `site_news`
--
ALTER TABLE `site_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
