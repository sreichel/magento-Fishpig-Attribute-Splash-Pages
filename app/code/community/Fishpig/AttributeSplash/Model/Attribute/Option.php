<?php

class Fishpig_AttributeSplash_Model_Attribute_Option extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('attributeSplash/attribute_option');
	}

	/**
	 * Retrieve the attribute model for this option
	 *
	 * @return Fishpig_AttributeSplash_Model_Attribute
	 */
	public function getAttributeModel()
	{
		if (!$this->hasData('attribute_model')) {
			$this->setData('attribute_model', Mage::getModel('attributeSplash/attribute')->load($this->getAttributeId()));
		}
		
		return $this->getData('attribute_model');
	}
	
}
