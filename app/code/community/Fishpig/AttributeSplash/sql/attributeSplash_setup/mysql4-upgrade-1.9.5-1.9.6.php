<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();

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
	
	$this->endSetup();
