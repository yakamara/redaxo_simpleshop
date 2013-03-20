--
-- Tabellenstruktur für Tabelle `rex_shop_order`
--

CREATE TABLE `rex_shop_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `price_overall` float NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mail_to` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mail_subject` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mail_text` text CHARACTER SET utf8 NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rex_shop_order_product`
--

CREATE TABLE `rex_shop_order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rex_shop_product`
--

CREATE TABLE `rex_shop_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `online_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `offline_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gsid` int(11) NOT NULL DEFAULT '0',
  `clang` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `categories` varchar(255) NOT NULL DEFAULT '0',
  `description_short` text NOT NULL,
  `description_long` text NOT NULL,
  `description_format` text NOT NULL,
  `description_amount` text NOT NULL,
  `article_number` varchar(255) NOT NULL,
  `vat` float NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `prices` text NOT NULL,
  `prices_graduated` text NOT NULL,
  `price_old` float NOT NULL DEFAULT '0',
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
  `discount_group_ids` text NOT NULL,
  `vt` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rex_shop_product_amount_group`
--

CREATE TABLE `rex_shop_product_amount_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rex_shop_product_discount_group`
--

CREATE TABLE `rex_shop_product_discount_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `discount_percent` float NOT NULL DEFAULT '0',
  `discount_value` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rex_shop_product_six_prices`
--

CREATE TABLE `rex_shop_product_six_prices` (
  `id` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `anzahl` int(11) NOT NULL DEFAULT '0',
  `gsid` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rex_shop_rel_product_discountgroup`
--
/*
CREATE TABLE `rex_shop_rel_product_discountgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/