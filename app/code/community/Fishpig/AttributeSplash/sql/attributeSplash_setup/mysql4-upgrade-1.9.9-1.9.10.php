<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

	$this->startSetup();
	
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
