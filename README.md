CREATE DATABASE IF NOT EXISTS `posta` /*!40100 DEFAULT CHARACTER SET utf8 */;

# table posta.envelope (конверты)
CREATE TABLE IF NOT EXISTS `envelope` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_resval` int(10) NOT NULL DEFAULT '0',
  `n_isx` varchar(255) DEFAULT NULL,
  `d_isx` date DEFAULT NULL,
  `orders` int(2) DEFAULT '0',
  `labeled` int(2) DEFAULT '0',
  `dates` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nclv` int(2) DEFAULT '1',
  `nsum` float(10,2) DEFAULT '0.00',
  `one` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13028 DEFAULT CHARSET=utf8;


