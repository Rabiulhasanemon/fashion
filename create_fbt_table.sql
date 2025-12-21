-- Create Frequently Bought Together table
CREATE TABLE IF NOT EXISTS `oc_product_frequently_bought_together` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `fbt_product_id` int(11) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `fbt_product_id` (`fbt_product_id`),
  UNIQUE KEY `unique_product_fbt` (`product_id`, `fbt_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

