<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();
	
	$this->run("

		DROP TABLE IF EXISTS {$this->getTable('attributesplash_page')};

		CREATE TABLE IF NOT EXISTS {$this->getTable('attributesplash_page')} (
			`page_id` int(11) unsigned NOT NULL auto_increment,
			`option_id` int (11) unsigned NOT NULL default 0,
			`store_id` smallint(5) unsigned NOT NULL default 0,
			`display_name` varchar(255) NOT NULL default '',
			`image` varchar(255) NOT NULL default '',
			`short_description` varchar(255) NOT NULL default '',
			`description` TEXT NOT NULL default '',
			`url_key` varchar(180) NOT NULL default '',
			`page_title` varchar(255) NOT NULL default '',
			`meta_description` varchar(255) NOT NULL default '',
			`meta_keywords` varchar(255) NOT NULL default '',
			`display_mode` varchar(40) NOT NULL default 'PRODUCTS',
			`cms_block` int(11) unsigned NOT NULL default 0,
			`is_enabled` int(1) unsigned NOT NULL default 1,
			PRIMARY KEY (`page_id`),
			KEY `FK_OPTION_ID_SPLASH_PAGE` (`option_id`),
			CONSTRAINT `FK_OPTION_ID_SPLASH_PAGE` FOREIGN KEY (`option_id`) REFERENCES `{$this->getTable('eav_attribute_option')}` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			KEY `FK_STORE_ID_SPLASH_PAGE` (`store_id`),
			CONSTRAINT `FK_STORE_ID_SPLASH_PAGE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='AttributeSplash: Page';
		
		ALTER TABLE {$this->getTable('attributesplash_page')} ADD UNIQUE (option_id, store_id);

	");
	
	$this->endSetup();
	
	
	
