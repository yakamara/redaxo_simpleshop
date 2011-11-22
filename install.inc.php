<?php

// INSTALL DB

$installsql = new rex_sql();
$installsql->debugsql = true;
// $installsql->query("DROP TABLE IF EXISTS `".$REX[ADDON][tbl][art]["simple_shop"]."`");
$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_order` (
	`id` int(11) NOT NULL  auto_increment, 
	`session_id` varchar(255) NOT NULL DEFAULT '0', 
	`price_overall` float NOT NULL DEFAULT '0', 
	`status` int(11) NOT NULL DEFAULT '0', 
	`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', 
	`name` varchar(255) NOT NULL, 
	`mail_to` varchar(255) NOT NULL, 
	`mail_subject` varchar(255) NOT NULL, 
	`mail_text` text NOT NULL, 
	`ip` varchar(255) NOT NULL, 
  PRIMARY KEY  (`id`)
)");


$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_order_product` (
	`id` int(11) NOT NULL  auto_increment, 
	`product_name` varchar(255) NOT NULL  , 
	`order_id` int(11) NOT NULL DEFAULT '0' , 
	`product_id` int(11) NOT NULL DEFAULT '0' , 
	`amount` int(11) NOT NULL DEFAULT '0' , 
	`price` float NOT NULL DEFAULT '0' , 
  PRIMARY KEY  (`id`)
)");


$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_product` (
  `id` int(11) NOT NULL  auto_increment, 
	`online_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,
	`offline_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' , 
	`gsid` int(11) NOT NULL DEFAULT '0' , 
	`clang` int(11) NOT NULL DEFAULT '0' , 
	`path` varchar(255) NOT NULL, 
	`name` varchar(255) NOT NULL, 
	`categories` varchar(255) NOT NULL DEFAULT '0', 
	`description_short` text NOT NULL, 
	`description_long` text NOT NULL, 
	`description_format` text NOT NULL, 
	`description_amount` text NOT NULL, 
	`article_number` varchar(255) NOT NULL, 
	`vat` float NOT NULL DEFAULT '0' , 
	`price` float NOT NULL DEFAULT '0' , 
	`prices` text NOT NULL  , 
	`prices_graduated` text NOT NULL  , 
	`price_old` float NOT NULL DEFAULT '0' , 
	`order_min` int(11) NOT NULL DEFAULT '0', 
	`order_max` int(11) NOT NULL DEFAULT '0', 
	`order_amounts` text NOT NULL, 
	`stock_in` tinyint(1) NOT NULL DEFAULT '0', 
	`ve` varchar(255) NOT NULL, 
	`stock_info` text NOT NULL, 
	`image` varchar(255) NOT NULL, 
	`images` text NOT NULL, 
	`productrelations` text NOT NULL, 
	`prio` int(11) NOT NULL DEFAULT '0', 
	`status` int(11) NOT NULL DEFAULT '0', 
	`keywords` varchar(255) NOT NULL, 
	`amount_group_id` int(11) NOT NULL, 
	`vt` text NOT NULL,
  PRIMARY KEY  (`id`)
)");

$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_product_amount_group` (
	`id` int(11) NOT NULL auto_increment,
	`status` int(11) NOT NULL, 
	`name` varchar(255) NOT NULL, 
	`description` text NOT NULL, 
	`amount` int(11) NOT NULL, 
	PRIMARY KEY  (`id`)
)");


$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_product_discount_group` (
	`id` int(11) NOT NULL  auto_increment, 
	`status` int(11) NOT NULL DEFAULT '0', 
	`name` varchar(255) NOT NULL, 
	`amount` int(11) NOT NULL DEFAULT '0', 
	`price` float NOT NULL DEFAULT '0', 
	`description` text NOT NULL, 
	`discount_percent` float NOT NULL DEFAULT '0', 
	`discount_value` float NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
)");

 
$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_product_six_prices` (
	`id` int(11) NOT NULL DEFAULT '0', 
	`price` float NOT NULL DEFAULT '0', 
	`anzahl` int(11) NOT NULL DEFAULT '0', 
	`gsid` int(11) NOT NULL DEFAULT '0', 
  PRIMARY KEY  (`id`)
)");


$installsql->setQuery("CREATE TABLE IF NOT EXISTS `rex_shop_rel_product_discountgroup` (
	`id` int(11) NOT NULL  auto_increment, 
	`product_id` int(11) NOT NULL DEFAULT '0', 
	`group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
)");


$REX["ADDON"]["install"]["simple_shop"] = 1;
// ERRMSG IN CASE: $REX[ADDON][installmsg]["simple_shop"] = "Leider konnte nichts installiert werden da.";

?>