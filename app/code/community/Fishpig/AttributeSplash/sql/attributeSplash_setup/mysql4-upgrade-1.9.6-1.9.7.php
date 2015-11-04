<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();

	$splashPages = Mage::getResourceModel('attributeSplash/page_collection')
		->addAttributeOptionData();
	
	foreach($splashPages as $splashPage) {
		if (!$splashPage->getAttributeId()) {
			try {
				$splashPage->delete();
			}
			catch (Exception $e) {
				Mage::log($e->getMessage(), false, 'attributeSplash.log', true);
			}
		}
	}

	$this->endSetup();
