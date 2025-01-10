SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `genitrini`
--

-- --------------------------------------------------------

-- Structure de la table `csv_files`

CREATE TABLE IF NOT EXISTS `csv_files` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `csv_content` TEXT ,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
-- Structure de la table `ListeEtudiants`
--

CREATE TABLE IF NOT EXISTS `ListeEtudiants` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `numero` TEXT NOT NULL,
  `nom` TEXT NOT NULL,
  `prenom` TEXT NOT NULL,
  `mail` TEXT NOT NULL,
  `spe` TEXT NOT NULL,
  `voeux` INT(11) NOT NULL DEFAULT '0',
  `ue1` TEXT NOT NULL,
  `ue2` TEXT NOT NULL,
  `ue3` TEXT NOT NULL,
  `ue4` TEXT NOT NULL,
  `ue5` TEXT NOT NULL,
  `ue6` TEXT NOT NULL,
  `ue7` TEXT NOT NULL,
  `ue8` TEXT NOT NULL,
  `ue9` TEXT NOT NULL,
  `ue10` TEXT NOT NULL,
  `ue11` TEXT NOT NULL,
  `ue12` TEXT NOT NULL,
  `ue13` TEXT NOT NULL,
  `ue14` TEXT NOT NULL,
  `ue15` TEXT NOT NULL,
  `ue1gpe` INT(11) NOT NULL,
  `ue2gpe` INT(11) NOT NULL,
  `ue3gpe` INT(11) NOT NULL,
  `ue4gpe` INT(11) NOT NULL,
  `ue5gpe` INT(11) NOT NULL,
  `ue6gpe` INT(11) NOT NULL,
  `ue7gpe` INT(11) NOT NULL,
  `ue8gpe` INT(11) NOT NULL,
  `ue9gpe` INT(11) NOT NULL,
  `ue10gpe` INT(11) NOT NULL,
  `ue11gpe` INT(11) NOT NULL,
  `ue12gpe` INT(11) NOT NULL,
  `ue13gpe` INT(11) NOT NULL,
  `ue14gpe` INT(11) NOT NULL,
  `ue15gpe` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- (Rest of the script remains unchanged)
