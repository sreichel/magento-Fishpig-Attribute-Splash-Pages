<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_System_Config_Source_Navigation_Container
{
	public function toOptionArray()
	{
		return array	(
			array('value' => 'left', 'label' => Mage::helper('attributeSplash')->__('Left')),
			array('value' => 'right', 'label' => Mage::helper('attributeSplash')->__('Right')),
		);
	}
}