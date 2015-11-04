<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_System_Config_Source_Navigation
{
	const USE_NO_NAVIGATION = 0;
	const USE_LAYERED_NAVIGATION = 1;
	const USE_CATALOG_NAVIGATION = 2;
	
	public function toOptionArray()
	{
		return 
			array(
				array('value' => self::USE_NO_NAVIGATION, 'label' => Mage::helper('attributeSplash')->__('No/Custom Navigation')),
				array('value' => self::USE_LAYERED_NAVIGATION, 'label' => Mage::helper('attributeSplash')->__('Layered Navigation')),
				array('value' => self::USE_CATALOG_NAVIGATION, 'label' => Mage::helper('attributeSplash')->__('Catalog Navigation')),
			);
	}

}
