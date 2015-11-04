<?php

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