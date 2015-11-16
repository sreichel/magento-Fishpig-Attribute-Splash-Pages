<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
	
	$this->startSetup();
	
	try {
		$this->run("
			ALTER TABLE `{$this->getTable('attributesplash_group')}` CHANGE `short_description` `short_description` TEXT;
			ALTER TABLE `{$this->getTable('attributesplash_page')}` CHANGE `short_description` `short_description` TEXT;
		");
	}
	catch (Exception $e) {
		
	}

	$this->endSetup();
