-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 05 Décembre 2021 à 13:09
-- Version du serveur :  5.5.42
-- Version de PHP :  5.4.42

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `genitrini`
--

-- --------------------------------------------------------

--
-- Structure de la table `ANDROIDE`
--

CREATE TABLE IF NOT EXISTS `ANDROIDE` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `BIM`
--

CREATE TABLE IF NOT EXISTS `BIM` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `DAC`
--

CREATE TABLE IF NOT EXISTS `DAC` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `edt_ideal`
--

CREATE TABLE IF NOT EXISTS `edt_ideal` (
  `numetu` text NOT NULL,
  `voeux` int(11) NOT NULL,
  `ue1` varchar(10) NOT NULL,
  `ue2` varchar(10) NOT NULL,
  `ue3` varchar(10) NOT NULL,
  `ue4` varchar(10) NOT NULL,
  `ue5` varchar(10) NOT NULL,
  `ue6` varchar(10) NOT NULL,
  `ue7` varchar(10) NOT NULL,
  `ue8` varchar(10) NOT NULL,
  `ue1gpe` int(11) NOT NULL,
  `ue2gpe` int(11) NOT NULL,
  `ue3gpe` int(11) NOT NULL,
  `ue4gpe` int(11) NOT NULL,
  `ue5gpe` int(11) NOT NULL,
  `ue6gpe` int(11) NOT NULL,
  `ue7gpe` int(11) NOT NULL,
  `ue8gpe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `IMA`
--

CREATE TABLE IF NOT EXISTS `IMA` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `IQ`
--

CREATE TABLE IF NOT EXISTS `IQ` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ListeEtudiants`
--

CREATE TABLE IF NOT EXISTS `ListeEtudiants` (
  `id` int(11) NOT NULL,
  `numero` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `mail` text NOT NULL,
  `spe` text NOT NULL,
  `voeux` int(11) NOT NULL DEFAULT '0',
  `ue1` text NOT NULL,
  `ue2` text NOT NULL,
  `ue3` text NOT NULL,
  `ue4` text NOT NULL,
  `ue5` text NOT NULL,
  `ue6` text NOT NULL,
  `ue7` text NOT NULL,
  `ue8` text NOT NULL,
  `ue9` text NOT NULL,
  `ue10` text NOT NULL,
  `ue11` text NOT NULL,
  `ue12` text NOT NULL,
  `ue13` text NOT NULL,
  `ue14` text NOT NULL,
  `ue15` text NOT NULL,
  `ue1gpe` int(11) NOT NULL,
  `ue2gpe` int(11) NOT NULL,
  `ue3gpe` int(11) NOT NULL,
  `ue4gpe` int(11) NOT NULL,
  `ue5gpe` int(11) NOT NULL,
  `ue6gpe` int(11) NOT NULL,
  `ue7gpe` int(11) NOT NULL,
  `ue8gpe` int(11) NOT NULL,
  `ue9gpe` int(11) NOT NULL,
  `ue10gpe` int(11) NOT NULL,
  `ue11gpe` int(11) NOT NULL,
  `ue12gpe` int(11) NOT NULL,
  `ue13gpe` int(11) NOT NULL,
  `ue14gpe` int(11) NOT NULL,
  `ue15gpe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Master`
--

CREATE TABLE IF NOT EXISTS `Master` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `NumTraduction`
--

CREATE TABLE IF NOT EXISTS `NumTraduction` (
  `numini` varchar(20) NOT NULL,
  `numvrai` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `RES`
--

CREATE TABLE IF NOT EXISTS `RES` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `SAR`
--

CREATE TABLE IF NOT EXISTS `SAR` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `SESI`
--

CREATE TABLE IF NOT EXISTS `SESI` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `CCA`
--

CREATE TABLE IF NOT EXISTS `CCA` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `STL`
--

CREATE TABLE IF NOT EXISTS `STL` (
  `numetu` text NOT NULL,
  `rang` int(11) NOT NULL,
  `rang_rouge` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `UEGroupes`
--

CREATE TABLE IF NOT EXISTS `UEGroupes` (
  `groupe` varchar(10) NOT NULL,
  `effectif` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- ------------------------- Ajout --------------------------------------

 CREATE TABLE IF NOT EXISTS csv_files (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        csv_content TEXT NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 );

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ANDROIDE`
--
ALTER TABLE `ANDROIDE`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `BIM`
--
ALTER TABLE `BIM`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `DAC`
--
ALTER TABLE `DAC`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `IMA`
--
ALTER TABLE `IMA`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `IQ`
--
ALTER TABLE `IQ`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `ListeEtudiants`
--
ALTER TABLE `ListeEtudiants`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Master`
--
ALTER TABLE `Master`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `RES`
--
ALTER TABLE `RES`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `SAR`
--
ALTER TABLE `SAR`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `SESI`
--
ALTER TABLE `SESI`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `CCA`
--
ALTER TABLE `CCA`
  ADD PRIMARY KEY (`rang`);

--
-- Index pour la table `STL`
--
ALTER TABLE `STL`
  ADD PRIMARY KEY (`rang`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `ANDROIDE`
--
ALTER TABLE `ANDROIDE`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `BIM`
--
ALTER TABLE `BIM`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `DAC`
--
ALTER TABLE `DAC`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `IMA`
--
ALTER TABLE `IMA`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `IQ`
--
ALTER TABLE `IQ`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ListeEtudiants`
--
ALTER TABLE `ListeEtudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Master`
--
ALTER TABLE `Master`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `RES`
--
ALTER TABLE `RES`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `SAR`
--
ALTER TABLE `SAR`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `SESI`
--
ALTER TABLE `SESI`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `CCA`
--
ALTER TABLE `CCA`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `STL`
--
ALTER TABLE `STL`
  MODIFY `rang` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
