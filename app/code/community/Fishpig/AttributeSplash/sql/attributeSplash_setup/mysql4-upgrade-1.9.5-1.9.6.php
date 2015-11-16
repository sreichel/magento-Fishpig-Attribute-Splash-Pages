<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();

	/**
	 * Create new splash page table
	 *
	 */
	$this->run("

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
	
	/**
	 * Add the layout update XML field
	 *
	 */
	$this->getConnection()->addColumn($this->getTable('attributesplash_page'), 'layout_update_xml', " TEXT NOT NULL default ''");

	/**
	 * Delete all of the old URL rewrites
	 *
	 */
	$this->getConnection('core_write')->delete($this->getTable('core_url_rewrite'), $this->getConnection('core_write')->quoteInto('id_path LIKE (?)', 'splash/%'));
		
	$select = $this->getConnection('core_read')
		->select()
		->from($this->getTable('attributesplash_attribute_option_extra'), '*');
			
			
	if ($results = $this->getConnection('core_read')->fetchAll($select)) {
		foreach($results as $result) {
			/**
			 * Switch status to new is_enabled column
			 *
			 */
			if (isset($result['status'])) {
				$result['is_enabled'] = $result['status'];
				unset($result['status']);
			}
		
			/**
			 * Remove entity_id field
			 *
			 */
			unset($result['entity_id']);

			/**
			 * Load splash page from option_id
			 *
			 */
			$splashPage = Mage::getModel('attributeSplash/page')
				->load($result['option_id'], 'option_id');
			
			/**
			 * Store id splash page ID
			 */
			$id = $splashPage->getId();
			
			/**
			 * Set the data and ID
			 *
			 */
			$splashPage->setData($result)->setId($id);;
			
			
			try {
				
				/**
				 * Save the new splash page
				 *
				 */
				$splashPage->save();
			}
			catch (Exception $e) {
				Mage::log($e->getMessage(), false, 'attributeSplash.log', true);			
			}
		}
	}
	
	try {
		/**
		 * Delete old splash pages
		 *
		 */
		$splashPages = Mage::getResourceModel('attributeSplash/page_collection');
		
		foreach($splashPages as $splashPage) {
			if (!$splashPage->getAttributeId()) {
				try {
	//				$splashPage->delete();
				}
				catch (Exception $e) {
					Mage::log($e->getMessage(), false, 'attributeSplash.log', true);
				}
			}
		}
	}
	catch (Exception $e) {
		Mage::logException($e);
	}

	/**
	 * Migrate images
	 *
	 */
	$old = Mage::getBaseDir('media') . DS . 'splash';
	$new = Mage::getBaseDir('media') . DS . 'attributesplash';
	
	try {
		if (is_dir($old)) {
			rename($old, $new);
		}
	}
	catch (Exception $e) {
		Mage::log($e->getMessage(), false, 'attributeSplash.log', true);
	}

	$this->endSetup();
