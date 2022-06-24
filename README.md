CREATE DATABASE IF NOT EXISTS `posta` /*!40100 DEFAULT CHARACTER SET utf8 */;

-----table posta.envelope (конверты)
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
  PRIMARY KEY (`id`)) 
  ENGINE=MyISAM AUTO_INCREMENT=13028 DEFAULT CHARSET=utf8;

----- table posta.resvalue (склад)
CREATE TABLE IF NOT EXISTS `resvalue` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_res` int(2) DEFAULT '1',
  `id_name` varchar(255) DEFAULT '',
  `labeled` int(2) DEFAULT '0',
  `cut_name` varchar(255) DEFAULT '',
  `full_name` varchar(255) DEFAULT '',
  `value` int(255) DEFAULT '0',
  `value_k` int(255) DEFAULT '0',
  `one_sum` float(10,2) DEFAULT '0.00',
  `sum_k` float(10,2) DEFAULT '0.00',
  `datestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dates` date DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
 
---- table posta.resvalue_log (логи действий)
CREATE TABLE IF NOT EXISTS `resvalue_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_res` int(2) DEFAULT '1',
  `id_name` varchar(255) DEFAULT '',
  `labeled` int(2) DEFAULT '0',
  `cut_name` varchar(255) DEFAULT '',
  `full_name` varchar(255) DEFAULT '',
  `value` int(255) DEFAULT '0',
  `value_k` int(255) DEFAULT '0',
  `one_sum` float(10,2) DEFAULT '0.00',
  `sum_k` float(10,2) DEFAULT '0.00',
  `datestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dates` date DEFAULT NULL,
  `name_proc` varchar(255) DEFAULT NULL,
  `operation_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)) 
  ENGINE=MyISAM AUTO_INCREMENT=5242 DEFAULT CHARSET=utf8;
  
---- table posta.stamp (марки)
CREATE TABLE IF NOT EXISTS `stamp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_envelope` int(10) DEFAULT NULL,
  `id_resval` int(10) DEFAULT NULL,
  `value` int(10) DEFAULT NULL,
  `nominal` float(10,2) DEFAULT NULL,
  `res_clv` int(10) DEFAULT NULL,
  `dates` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)) 
ENGINE=MyISAM AUTO_INCREMENT=3077 DEFAULT CHARSET=utf8;

-----table posta.users (пользователи)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `fio` varchar(255) NOT NULL,
  `dr` date NOT NULL DEFAULT '0000-00-00',
  `pass` varchar(40) NOT NULL,
  `adm` int(1) NOT NULL DEFAULT '0',
  `priem` int(1) NOT NULL DEFAULT '0',
  `actualy` int(1) NOT NULL DEFAULT '1',
  `control` int(1) NOT NULL DEFAULT '0',
  `utif` varchar(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--- view posta.envelope_clv (представления, конверты количество, суммы)

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `envelope_clv` AS select date_format(`envelope`.`d_isx`,'%m') AS `m`,date_format(`envelope`.`d_isx`,'%Y') AS `y`,`envelope`.`id_resval` AS `id_resval`,`resvalue`.`cut_name` AS `cut_name`,`resvalue`.`full_name` AS `full_name`,sum(`envelope`.`one`) AS `clv`,(sum(`envelope`.`one`) * `resvalue`.`one_sum`) AS `s` from (`envelope` left join `resvalue` on((`envelope`.`id_resval` = `resvalue`.`id`))) group by date_format(`envelope`.`d_isx`,'%Y-%m'),`resvalue`.`cut_name`,`envelope`.`id_resval`,`resvalue`.`full_name` order by date_format(`envelope`.`d_isx`,'%Y-%m');


-----view posta.envelope_sum (представления, конверты суммированные данные)
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `envelope_sum` AS select `envelope`.`id_resval` AS `id_resval`,`resvalue`.`cut_name` AS `cut_name`,`resvalue`.`full_name` AS `full_name`,sum(`envelope`.`one`) AS `clv`,`resvalue`.`value_k` AS `value_k`,(`resvalue`.`value_k` - sum(`envelope`.`one`)) AS `sk`,`resvalue`.`value` AS `value` from (`envelope` left join `resvalue` on((`envelope`.`id_resval` = `resvalue`.`id`))) group by `resvalue`.`cut_name`,`envelope`.`id_resval`,`resvalue`.`full_name` order by 1;

----- view posta.stamp_e (представления, марки количество, суммы)
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `stamp_e` AS select `stamp`.`id_envelope` AS `id_envelope`,sum(`stamp`.`value`) AS `clv`,sum((`stamp`.`value` * `stamp`.`nominal`)) AS `sumitog`,ifnull(group_concat('(',`stamp`.`value`,' - ',`stamp`.`nominal`,'p)' separator ' '),'') AS `concats` from (`stamp` left join `resvalue` on((`stamp`.`id_resval` = `resvalue`.`id`))) group by `stamp`.`id_envelope`;

-----view posta.summ_e (представления, марки суммированные данные)
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summ_e` AS select date_format(`envelope`.`dates`,_utf8'%Y') AS `y`,date_format(`envelope`.`dates`,_utf8'%m') AS `m`,sum(`envelope`.`one`) AS `clven`,sum(`envelope`.`nsum`) AS `sume`,sum(`stamp_e`.`clv`) AS `clvm`,sum(`stamp_e`.`sumitog`) AS `summ` from (`envelope` left join `stamp_e` on((`envelope`.`id` = `stamp_e`.`id_envelope`))) group by date_format(`envelope`.`dates`,_utf8'%Y-%m');
