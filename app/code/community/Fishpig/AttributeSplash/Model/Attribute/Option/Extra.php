<?php

class Fishpig_AttributeSplash_Model_Attribute_Option_Extra extends Mage_Core_Model_Abstract
{
	/**
	 * Stores the attribute model for this option
	 *
	 * @var Fishpig_AttributeSplash_Model_Attribute
	 */
	protected $_attributeModel = null;
	
	/**
	 * Stores this attribute option for this option extra
	 *
	 * @var Fishpig_AttributeSplash_Model_Attribute_Option
	 */
	protected $_attributeOptionModel = null;
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('attributeSplash/attribute_option_extra');
	}
	
	/**
	 * Retrieves the attribute model
	 *
	 * @return Fishpig_AttributeSplash_Model_Attribute
	 */
	public function getAttributeModel()
	{
		if (is_null($this->_attributeModel)) {
			if ($option = $this->getAttributeOptionModel()) {
				$this->_attributeModel = $option->getAttributeModel();
			}
			else {
				$this->_attributeModel = false;
			}
		}
		
		return $this->_attributeModel;
	}

	/**
	 * Retrieves the attribute option model
	 *
	 * @return Fishpig_AttributeSplash_Model_Attribute_Option
	 */	
	public function getAttributeOptionModel()
	{
		if (is_null($this->_attributeOptionModel)) {
			$this->_attributeOptionModel = Mage::getModel('attributeSplash/attribute_option')->load($this->getOptionId());
		}
		
		return $this->_attributeOptionModel;
	}
	
	/**
	 * Adds attribute information to the data array
	 */
	public function addAttributeInfo()
	{
		if ($option = $this->getAttributeOptionModel()) {
			$this->setAttributeId($option->getAttributeId())
				->setOptionValue($option->getValue());
		}
		
		return $this;
	}
	
	/**
	 * Adds attribute option information to the data array
	 */
	public function addAttributeOptionInfo()
	{
		if ($attribute = $this->getAttributeModel()) {
			$this->setAttributeCode($attribute->getAttributeCode())
				->setAttributeName($attribute->getFrontendLabel());
		}
		
		return $this;
	}
	
	/**
	 * Gets the URL to view the Splash page on the frontend
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return Mage::helper('attributeSplash')->getSplashUrl($this);
	}

	/**
	 * If the display name isn't set, it is populated using the option_value
	 */
	protected function _beforeSave()
	{
		parent::_beforeSave();
		
		$this->getResource()->prepareDataForSave($this);
		return $this;
	}

	/**
	 * Updates the URL rewrite based on the newly saved URL Key
	 */
	protected function _afterSave()
	{
		parent::_afterSave();
		
		$this->getResource()->updateRewrite($this);
		$this->getResource()->updateAttributeRewrite($this);

		return $this;
	}
	
	/**
	 * Deletes the URL rewrite
	 */
	protected function _beforeDelete()
	{
		parent::_beforeDelete();
		$this->getResource()->deleteRewrite($this);
		$this->getResource()->deleteAttributeRewrite($this);
		
		return $this;
	}
	
	/**
	 * Returns the ID path used by the URL rewrite
	 *
	 * @return string
	 */
	public function getIdPath()
	{
		return 'splash/'.$this->getId();
	}
	
	/**
	 * Returns the target path used by the URL rewrite
	 *
	 * @return string
	 */
	public function getTargetPath()
	{
		return 'splash/view/index/id/'.$this->getId();
	}
	
	/**
	 * Converts a string to formatted URL key
	 *
	 * @param $string - the string to format
	 * @return string
	 */
	public function formatUrlKey($string)
	{
		return Mage::helper('attributeSplash/rewrite')->formatUrlkey($string);
	}

	/**
	 * Allows access to the resource name
	 *
	 * @return string
	 */
	public function getResourceName()
	{
		return $this->_resourceName;
	}
	
	/**
	 * Creates image_new and thumbail_new data elements
	 * These data elements are used in the Adminhtml to display/preview the currently uploaded image
	 */
	public function addImageNewForUploads()
	{
		foreach(array('image', 'thumbnail') as $key) {
			if ($image = trim($this->getData($key))) {
				$this->setData($key.'_new', 'splash' . DS . $image);
			}
		}
		
		return $this;
	}
}
