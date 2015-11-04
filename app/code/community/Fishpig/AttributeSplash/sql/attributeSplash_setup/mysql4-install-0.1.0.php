<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

	$this->startSetup();
	
	$this->run("
		CREATE TABLE IF NOT EXISTS {$this->getTable('attributesplash_attribute_option_extra')} (
			`entity_id` int(11) unsigned NOT NULL auto_increment,
			`option_id` int (11) unsigned NOT NULL default 0,
			`store_id` int(11) unsigned NOT NULL default 0,
			`display_name` varchar(255) NOT NULL default '',
			`page_title` varchar(255) NOT NULL default '',
			`url_key` varchar(180) NOT NULL default '',
			`image` varchar(255) NOT NULL default '',
			`thumbnail` varchar(255) NOT NULL default '',
			`short_description` varchar(255) NOT NULL default '',
			`description` TEXT NOT NULL default '',
			`meta_description` varchar(255) NOT NULL default '',
			`meta_keywords` varchar(255) NOT NULL default '',
			`display_mode` varchar(40) NOT NULL default 'PRODUCTS',
			`cms_block` int(11) unsigned NOT NULL default 0,
			`status` int(1) unsigned NOT NULL default 1,
			PRIMARY KEY (entity_id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
		ALTER TABLE {$this->getTable('attributesplash_attribute_option_extra')} ADD UNIQUE (option_id,store_id);
	");
	
	$this->endSetup();
