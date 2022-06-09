-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Lun 06 Décembre 2021 à 16:43
-- Version du serveur: 5.1.73
-- Version de PHP: 5.3.3

USE laravel_tutorial;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

SET NAMES utf8;
-- SET time_zone = '+00:00';
SET foreign_key_checks = 0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Structure de la table `zaction`
--

CREATE TABLE IF NOT EXISTS `zaction` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `action` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `zcredential`
--

CREATE TABLE IF NOT EXISTS `zcredential` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `group` varchar(10) NOT NULL,
  `action` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Contenu de la table `zcredential`
--

INSERT INTO `zcredential` (`id`, `group`, `action`) VALUES
(1, 'user', 'etudiantsearch'),
(2, 'user', 'etudiantindex'),
(3, 'user', 'etudiantview'),
(4, 'user', 'etudiantajaxview'),
(5, 'admin', 'adminuser'),
(6, 'admin', 'adminadduser'),
(7, 'admin', 'admindeleteuser'),
(8, 'admin', 'adminadduseringroup'),
(9, 'user', 'etudianttestpassword'),
(10, 'user', 'etudiantreinit'),
(11, 'user', 'etudiantajaxreinit'),
(12, 'user', 'etudiantajaxtestpassword'),
(13, 'admin', 'etudiantinsiscol'),
(14, 'user', 'etudiantajaxalert'),
(15, 'admin', 'etudiantupdateall'),
(17, 'user', 'etudiantajouteremail'),
(18, 'user', 'etudiantldapetudiant'),
(19, 'user', 'etudiantlistldapetudiants'),
(22, 'user', 'etudianttestwssiscol'),
(24, 'user', 'etudiantlistsiscoletudiants'),
(25, 'user', 'etudiantsiscoletudiant'),
(26, 'superuser', 'etudiantsearch'),
(27, 'superuser', 'etudiantindex'),
(28, 'superuser', 'etudiantview'),
(29, 'superuser', 'etudiantajaxview'),
(30, 'superuser', 'etudianttestpassword'),
(31, 'superuser', 'etudiantreinit'),
(32, 'superuser', 'etudiantajaxreinit'),
(33, 'superuser', 'etudiantajaxtestpassword'),
(34, 'superuser', 'etudiantajaxalert'),
(35, 'superuser', 'etudiantajouteremail'),
(36, 'superuser', 'etudiantldapetudiant'),
(37, 'superuser', 'etudiantlistldapetudiants'),
(38, 'superuser', 'etudianttestwssiscol'),
(39, 'superuser', 'etudiantlistsiscoletudiants'),
(40, 'superuser', 'etudiantsiscoletudiant'),
(41, 'admin', 'admindeleteusergroup'),
(42, 'admin', 'etudiantchecksapid'),
(43, 'admin', 'etudiantreinitue');

-- --------------------------------------------------------

--
-- Structure de la table `zgroup`
--

CREATE TABLE IF NOT EXISTS `zgroup` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `group` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `zgroup`
--

INSERT INTO `zgroup` (`id`, `group`) VALUES
(1, 'user'),
(2, 'admin'),
(3, 'superuser');

-- --------------------------------------------------------

--
-- Structure de la table `zuser`
--

CREATE TABLE IF NOT EXISTS `zuser` (
  `login` varchar(10) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(150) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `zuser`
--

INSERT INTO `zuser` (`login`, `nom`, `prenom`) VALUES
('lecoeurj', 'LE COEUR', 'Joseph'),
('villino', 'VILLIN', 'Olivier'),
('dumoulic', 'DUMOULIN', 'Christophe'),
('barrezj', 'BARREZ', 'Jean-Christophe'),
('nardoua', 'NARDOU', 'Annie'),
('pereirci', 'PEREIRA', 'Cindy'),
('zrekn', 'ZREK', 'Nahla'),
('jacquenf', 'JACQUENOD', 'Frédéric'),
('buissarb', 'BUISSART', 'Brigitte'),
('woznicas', 'WOZNICA', 'Stanislaw'),
('proudhoc', 'PROUDHOM', 'Christophe'),
('chicotx', 'CHICOT', 'Xavier'),
('bliahel', 'BLIAH', 'Elodie'),
('repirs', 'REPIR', 'Stéphanie'),
('nyatchac', 'NYA TCHABO', 'Cindy'),
('ferreica', 'FERREIRA', 'Carlos'),
('murcianc', 'MURCIANO', 'Carole'),
('rocquek', 'ROCQUE', 'Kevin'),
('laines', 'LAINE', 'Sandrine'),
('hassanir', 'AIT MEDRI', 'Roza'),
('lelongj', 'LELONG', 'Jérôme'),
('hammamvh', 'HAMMAM-VAÏANI', 'Hicheme'),
('takdjera', 'TAKDJERAD', 'Ahmed'),
('canesig', 'CANESI', 'Gérard'),
('jarrigel', 'JARRIGE', 'Léa'),
('dinnesp', 'DINNES', 'Pratheepa'),
('zebom', 'ZEBO', 'Michael'),
('monmonc', 'MONMON', 'Cyril'),
('lafontar', 'LAFONTAINE', 'Renée'),
('nacreons', 'NACREON', 'Sandrine'),
('elbouran', 'EL BOURAKADI', 'Noura'),
('leblancm', 'LEBLANC', 'Matthieu'),
('nabbachy', 'NABBACH', 'Yasmina'),
('fouquers', 'FOUQUERAY', 'Sylvain'),
('meyerowm', 'MEYEROWITCH', 'Marina'),
('pierrej', 'PIERRE', 'Juliara'),
('labonneo', 'LABONNE-JAYAT', 'Olivier'),
('boumapil', 'BOUMA-PIOT', 'Laëtitia'),
('masaouds', 'MASAOUDI', 'Said'),
('amrit', 'AMRI', 'Tarik'),
('richardn', 'RICHARD', 'Nadia'),
('lebrasp', 'LE BRAS', 'Philippe'),
('ghamraoi', 'GHAMRAOUI', 'Imane'),
('kartoutm', 'KARTOUT', 'Malika'),
('lipiece', 'JEDRASEK', 'Ewelina'),
('boukhliz', 'BOUKHLIFI', 'Zineb'),
('molinas', 'MOLINA', 'Sylvie'),
('nguyenb', 'NGUYEN', 'Ba-Hien'),
('nguyent', 'NGUYEN', 'Thi-Thu-Tra'),
('adjloutz', 'ADJLOUT', 'Zahra'),
('mileticn', 'MILETIC', 'Nicolas'),
('courtiag', 'COURTIAL', 'Geneviève'),
('haram', 'HARA', 'Meryem'),
('hernandn', 'HERNANDEZ', 'Nathalie'),
('mottao', 'MOTTA', 'Olivier'),
('oumezzaa', 'OUMEZZAOUCHE', 'Ali'),
('gondm', 'GOND', 'Manuela'),
('aminec', 'AMINE', 'Chantal'),
('tandinem', 'TANDINE', 'Masse'),
('ozkanlim', 'OZKANLI', 'Miyase'),
('diac', 'WANE', 'Coumba'),
('pacaudbm', 'PACAUD-BARISON', 'Magali'),
('bernarva', 'BERNIER', 'Valérie'),
('hassaoua', 'HASSAOUI', 'Akli'),
('palmierc', 'PALMIER', 'Chantal'),
('mepandyj', 'MEPANDY', 'Jeanida'),
('hervev', 'HERVE', 'Virginie'),
('macdonah', 'MAC DONALD', 'Hubert'),
('navarror', 'NAVARRO', 'Ramiro'),
('bonnetp', 'BONNET', 'Pascale'),
('leroin', 'LE ROI', 'Nathalie'),
('linaiss', 'LINAIS', 'Sandra'),
('lemassos', 'LEMASSON', 'Stessy'),
('barakea', 'BARAKÉ', 'Asta'),
('briendc', 'BRIEND', 'Carole'),
('paulics', 'PAULIC', 'Stéphanie'),
('thoness', 'THONES', 'Sophie'),
('cherifol', 'CHERIF OUAZANI', 'Louisa'),
('matouap', 'MATOUA', 'Priscilla'),
('marroula', 'MARROULLE', 'Anne-Solenne'),
('bridaulm', 'BRIDAULT', 'Magali'),
('richarf2', 'RICHARD', 'Francine'),
('ntolaa', 'NTOLA', 'Anita'),
('perroym', 'PERROY', 'Marie-Thérèse'),
('rossie', 'ROSSI', 'Emilie'),
('tragerh', 'THOREAU-AOUBAID', 'Hélène'),
('veronf', 'VERON', 'Felicie'),
('zamorac', 'ZAMORA', 'Claudia'),
('constanl', 'CONSTANTIN', 'Laurent'),
('manzinu', 'MAN ZIN', 'Ulrich'),
('guilloun', 'GUILLOU', 'Nicolas'),
('victoira', 'VICTOIRE', 'Axel'),
('roseantl', 'ROSE-ANTOINETTE', 'Laurie'),
('carebal', 'CAREBA', 'Lydia'),
('agostonk', 'AGOSTON-THEURIER', 'Karel'),
('goyera', 'GOYER', 'Aurélie'),
('fayecorl', 'FAYE COROUGE', 'Liliane'),
('aidrir', 'AIDRI', 'Rabah'),
('nedromik', 'NEDROMI', 'Karim'),
('pfenderm', 'PFENDER', 'Magdaléna'),
('gunduzgm', 'GUNDUZ-GUVENTURK', 'Mahsum'),
('kazioul', 'KAZIOU', 'Lenny'),
('neynaudh', 'NEYNAUD', 'Hélène'),
('frogera', 'FROGER', 'Agathe'),
('taiaum', 'TAIAU', 'Mustafa'),
('moukasss', 'MOUKASSA', 'Splendeur'),
('cagniarh', 'CAGNIARD', 'Hélène'),
('rochec', 'ROCHE', 'Cindy'),
('bellancd', 'BELLANCE', 'Daisy-Rose'),
('catarinj', 'CATARINO', 'Joaninha'),
('elisabec', 'ELIZABETH', 'Charlotte'),
('mwandasj', 'MWANDA SUMUKI', 'Jean-Jacques'),
('toussaip', 'TOUSSAINT', 'Pascale'),
('kokolohl', 'KOKOLO-HUBERDEAU', 'Lara'),
('duboste', 'DUBOST', 'Elodie'),
('carassef', 'CARRASSE', 'Françoise'),
('estacem', 'ESTACE', 'Mickaël'),
('strohmel', 'STROHMEYER', 'Laurence'),
('duvala', 'DUVAL', 'Aurélia'),
('ntumbat', 'NTUMBA', 'Tryphenne'),
('lihairem', 'LIHAIRE', 'Marie-Laure'),
('mendest', 'MENDES', 'Tania'),
('karlene', 'KARLEN', 'Etienne'),
('ftouhik', 'FTOUHI', 'Kawthar'),
('careli', 'CAREL', 'Isabelle'),
('davidn', 'DAVID', 'Nadine'),
('aubruna', 'AUBRUN', 'Aurélie'),
('allairga', 'ALLAIRE', 'Gauthier'),
('gustaven', 'GUSTAVE', 'Nathalie'),
('duvalca', 'DUVAL', 'Caroline'),
('alarmanh', 'AL ARMANI', 'Hovik'),
('diah', 'DIA', 'Hapsatoue'),
('cozici', 'COZIC', 'Isabelle'),
('thobork', 'THOBOR', 'Katia'),
('dormoyj', 'DORMOY', 'Julie'),
('vitalisf', 'VITALIS', 'Florence');

-- --------------------------------------------------------

--
-- Structure de la table `zusergroup`
--

CREATE TABLE IF NOT EXISTS `zusergroup` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user` varchar(10) NOT NULL,
  `group` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`user`,`group`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=381 ;

--
-- Contenu de la table `zusergroup`
--

INSERT INTO `zusergroup` (`id`, `user`, `group`) VALUES
(342, 'nardoua', 'superuser'),
(46, 'hammamvh', 'user'),
(256, 'lebrasp', 'superuser'),
(88, 'barrezj', 'user'),
(135, 'dumoulic', 'admin'),
(286, 'boumapil', 'admin'),
(110, 'villino', 'admin'),
(23, 'dumoulic', 'user'),
(25, 'zrekn', 'user'),
(26, 'jacquenf', 'user'),
(130, 'jacquenf', 'superuser'),
(28, 'buissarb', 'user'),
(29, 'woznicas', 'user'),
(30, 'woznicas', 'admin'),
(380, 'richarf2', 'superuser'),
(160, 'proudhoc', 'user'),
(34, 'chicotx', 'user'),
(91, 'bliahel', 'user'),
(276, 'molinas', 'user'),
(371, 'manzinu', 'superuser'),
(39, 'murcianc', 'user'),
(40, 'ferreica', 'user'),
(195, 'lafontar', 'user'),
(73, 'hassanir', 'user'),
(44, 'lelongj', 'user'),
(45, 'lelongj', 'admin'),
(273, 'amrit', 'user'),
(292, 'dinnesp', 'superuser'),
(291, 'dinnesp', 'user'),
(54, 'zebom', 'user'),
(55, 'zebom', 'admin'),
(56, 'monmonc', 'user'),
(372, 'nyatchac', 'user'),
(61, 'leblancm', 'user'),
(159, 'nabbachy', 'user'),
(66, 'fouquers', 'user'),
(67, 'meyerowm', 'user'),
(272, 'amrit', 'admin'),
(70, 'labonneo', 'admin'),
(71, 'labonneo', 'user'),
(377, 'macdonah', 'superuser'),
(75, 'masaouds', 'user'),
(157, 'richardn', 'user'),
(255, 'lebrasp', 'user'),
(82, 'ghamraoi', 'user'),
(198, 'murcianc', 'admin'),
(109, 'villino', 'user'),
(217, 'rocquek', 'admin'),
(293, 'lecoeurj', 'user'),
(95, 'hammamvh', 'superuser'),
(200, 'kartoutm', 'superuser'),
(97, 'ferreica', 'superuser'),
(98, 'labonneo', 'superuser'),
(99, 'woznicas', 'superuser'),
(100, 'zrekn', 'superuser'),
(102, 'murcianc', 'superuser'),
(162, 'laines', 'superuser'),
(104, 'monmonc', 'superuser'),
(105, 'pereirci', 'user'),
(106, 'pereirci', 'superuser'),
(140, 'lipiece', 'user'),
(112, 'boukhliz', 'user'),
(271, 'briendc', 'user'),
(264, 'elbouran', 'superuser'),
(116, 'nguyenb', 'user'),
(117, 'nguyent', 'user'),
(119, 'zrekn', 'admin'),
(120, 'barrezj', 'admin'),
(122, 'adjloutz', 'user'),
(123, 'masaouds', 'superuser'),
(196, 'lafontar', 'superuser'),
(290, 'nacreons', 'user'),
(163, 'mileticn', 'user'),
(127, 'hassanir', 'superuser'),
(376, 'pierrej', 'superuser'),
(129, 'bliahel', 'superuser'),
(131, 'jacquenf', 'admin'),
(378, 'repirs', 'superuser'),
(134, 'lelongj', 'superuser'),
(141, 'lipiece', 'superuser'),
(164, 'mileticn', 'superuser'),
(375, 'pierrej', 'user'),
(199, 'kartoutm', 'user'),
(145, 'meyerowm', 'superuser'),
(146, 'leblancm', 'admin'),
(147, 'courtiag', 'user'),
(274, 'leroin', 'user'),
(261, 'hernandn', 'superuser'),
(287, 'lecoeurj', 'superuser'),
(168, 'canesig', 'user'),
(169, 'chicotx', 'admin'),
(170, 'mottao', 'user'),
(171, 'oumezzaa', 'user'),
(265, 'elbouran', 'user'),
(173, 'gondm', 'superuser'),
(174, 'aminec', 'user'),
(279, 'haram', 'superuser'),
(176, 'tandinem', 'user'),
(178, 'diac', 'user'),
(179, 'pacaudbm', 'superuser'),
(201, 'leroin', 'superuser'),
(252, 'bernarva', 'user'),
(183, 'hassaoua', 'user'),
(184, 'hassaoua', 'superuser'),
(307, 'taiaum', 'user'),
(188, 'nabbachy', 'superuser'),
(266, 'hervev', 'user'),
(190, 'proudhoc', 'superuser'),
(367, 'navarror', 'user'),
(373, 'jarrigel', 'user'),
(260, 'hernandn', 'user'),
(202, 'linaiss', 'superuser'),
(275, 'molinas', 'superuser'),
(314, 'nedromik', 'superuser'),
(269, 'ozkanlim', 'superuser'),
(209, 'paulics', 'user'),
(210, 'thoness', 'user'),
(211, 'cherifol', 'superuser'),
(214, 'matouap', 'superuser'),
(374, 'jarrigel', 'superuser'),
(218, 'rocquek', 'user'),
(219, 'rocquek', 'superuser'),
(220, 'marroula', 'user'),
(221, 'marroula', 'superuser'),
(224, 'fouquers', 'superuser'),
(225, 'bonnetp', 'user'),
(226, 'bridaulm', 'user'),
(379, 'nyatchac', 'superuser'),
(228, 'ntolaa', 'user'),
(229, 'perroym', 'user'),
(230, 'rossie', 'user'),
(231, 'tragerh', 'user'),
(232, 'veronf', 'superuser'),
(233, 'zamorac', 'superuser'),
(267, 'hervev', 'superuser'),
(235, 'constanl', 'user'),
(236, 'constanl', 'superuser'),
(237, 'manzinu', 'user'),
(343, 'guilloun', 'user'),
(240, 'victoira', 'user'),
(241, 'roseantl', 'user'),
(242, 'roseantl', 'superuser'),
(243, 'victoira', 'superuser'),
(246, 'carebal', 'superuser'),
(247, 'carebal', 'user'),
(248, 'agostonk', 'superuser'),
(249, 'agostonk', 'user'),
(250, 'goyera', 'superuser'),
(251, 'goyera', 'user'),
(253, 'fayecorl', 'superuser'),
(254, 'fayecorl', 'user'),
(280, 'haram', 'user'),
(281, 'lemassos', 'user'),
(282, 'lemassos', 'superuser'),
(283, 'boumapil', 'user'),
(284, 'palmierc', 'user'),
(285, 'aidrir', 'user'),
(294, 'takdjera', 'user'),
(295, 'nedromik', 'user'),
(296, 'palmierc', 'superuser'),
(297, 'pfenderm', 'user'),
(298, 'pfenderm', 'superuser'),
(299, 'gunduzgm', 'user'),
(300, 'gunduzgm', 'superuser'),
(301, 'kazioul', 'user'),
(302, 'kazioul', 'admin'),
(303, 'neynaudh', 'user'),
(304, 'neynaudh', 'superuser'),
(305, 'frogera', 'superuser'),
(306, 'frogera', 'user'),
(308, 'ozkanlim', 'user'),
(309, 'thoness', 'superuser'),
(310, 'mepandyj', 'user'),
(311, 'moukasss', 'user'),
(312, 'cagniarh', 'user'),
(313, 'rochec', 'user'),
(315, 'takdjera', 'admin'),
(316, 'barakea', 'user'),
(317, 'barakea', 'superuser'),
(318, 'bellancd', 'user'),
(319, 'bellancd', 'superuser'),
(322, 'catarinj', 'user'),
(323, 'catarinj', 'superuser'),
(324, 'elisabec', 'superuser'),
(325, 'mwandasj', 'user'),
(326, 'mwandasj', 'superuser'),
(327, 'toussaip', 'superuser'),
(328, 'kokolohl', 'superuser'),
(329, 'kokolohl', 'user'),
(330, 'zamorac', 'user'),
(331, 'duboste', 'superuser'),
(332, 'carassef', 'superuser'),
(333, 'estacem', 'superuser'),
(334, 'strohmel', 'superuser'),
(335, 'duvala', 'superuser'),
(336, 'ntumbat', 'superuser'),
(337, 'lihairem', 'superuser'),
(338, 'mendest', 'superuser'),
(339, 'karlene', 'user'),
(340, 'karlene', 'superuser'),
(341, 'ftouhik', 'superuser'),
(344, 'careli', 'user'),
(345, 'careli', 'superuser'),
(346, 'guilloun', 'superuser'),
(347, 'davidn', 'superuser'),
(348, 'aubruna', 'superuser'),
(349, 'allairga', 'superuser'),
(350, 'gustaven', 'superuser'),
(351, 'duvalca', 'superuser'),
(352, 'alarmanh', 'superuser'),
(353, 'diah', 'superuser'),
(354, 'diah', 'user'),
(355, 'lafontar', 'admin'),
(356, 'cozici', 'user'),
(357, 'cozici', 'superuser'),
(358, 'alarmanh', 'admin'),
(359, 'thobork', 'superuser'),
(360, 'dormoyj', 'superuser'),
(362, 'ghamraoi', 'superuser'),
(364, 'vitalisf', 'superuser'),
(365, 'vitalisf', 'admin'),
(366, 'vitalisf', 'user');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
