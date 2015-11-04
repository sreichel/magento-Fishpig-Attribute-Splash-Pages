<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_System_Config_Source_Attribute_Mode
{
	public function toOptionArray()
	{
		return array(
			array(
				'value' => Fishpig_AttributeSplash_Model_Page::ATTRIBUTE_MODE_GRID, 
				'label' => Mage::helper('attributeSplash')->__('Grid')
			),
			array(
				'value' => Fishpig_AttributeSplash_Model_Page::ATTRIBUTE_MODE_LIST, 
				'label' => Mage::helper('attributeSplash')->__('List')
			),
			array(
				'value' => Fishpig_AttributeSplash_Model_Page::ATTRIBUTE_MODE_SIMPLE, 
				'label' => Mage::helper('attributeSplash')->__('Simple')
			),
		);
	}
}
