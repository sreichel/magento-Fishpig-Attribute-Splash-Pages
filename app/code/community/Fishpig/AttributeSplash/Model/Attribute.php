<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Attribute extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('attributeSplash/attribute');
	}

	/**
	 * Returns a collection of the values this attribute has 
	 * Values will be for the current store
	 *
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Attribute_Option_Collection
	 */
	public function getValues()
	{
		if (!$this->hasData('values')) {
			$this->setData('values', $this->getResource()->getValues($this));
		}
		
		return $this->getData('values');
	}
	
	/**
	 * Returns a collection of splash pages attributed to this attribute
	 *
	 * @param int|null $storeId - If null, the current store ID is used
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Splash_Collection
	 */
	public function getSplashPages($storeId = null)
	{
		if (!$this->hasData('splash_pages')) {
			$this->setData('splash_pages', $this->getResource()->getSplashPages($this, $storeId));
		}
		
		return $this->getData('splash_pages');
	}

	/**
	 * Retrieves the Attribute label
	 *
	 * @return string
	 */
	public function getDisplayName()
	{
		return $this->getFrontendLabel();
	}
	
	/**
	 * Retrieves the Attribute label
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getDisplayName();
	}
	
	/**
	 * Retrieves the attribute code (used for URL key)
	 *
	 * @return string
	 */
	public function getUrlKey()
	{
		return Mage::helper('attributeSplash/rewrite')->formatUrlkey($this->getAttributeCode());
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
	 * Gets the URL to view the Splash page on the frontend
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return Mage::helper('attributeSplash')->getSplashUrl($this);
	}
	
	/**
	 * Retrieves the ID path used by the rewrites
	 *
	 * @return string
	 */
	public function getIdPath()
	{
		return 'splash/list/'.$this->getId();
	}
	
	/**
	 * Retrieves the target path used by the rewrites
	 *
	 * @return string
	 */
	public function getTargetPath()
	{
		return 'splash/list/index/id/'.$this->getId();
	}
	
}
