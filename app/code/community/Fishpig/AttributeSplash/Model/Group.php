<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Group extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/group');
	}
	
	/**
	 * Retrieve the name of the splash page
	 * If display name isn't set, option value label will be returned
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getDisplayName() ? $this->getDisplayName() : $this->getFrontendLabel();
	}
	
	/**
	 * Retrieve the URL for the splash group
	 * If cannot find rewrite, return system URL
	 *
	 * @return string
	 */
	public function getUrl()
	{
		if ($this->getUrlPath()) {
			return Mage::getUrl('', array(
				'_direct' => $this->getUrlPath(),
				'_secure' 	=> false,
				'_nosid' 	=> true,
				'_store' => $this->getStoreId() ? $this->getStoreId() : Mage::helper('attributeSplash')->getCurrentFrontendStore()->getId(),
			));
		}
		
		return Mage::getUrl($this->getResource()->getTargetPath($this));
	}
	
	/**
	 * Retrieve the URL path for the splash group
	 *
	 * @return string
	 */
	public function getUrlPath()
	{
		if (!$this->hasUrlPath()) {
			$this->setUrlPath($this->getResource()->getRequestPath($this));
		}
		
		return $this->getData('url_path');
	}
	
	/**
	 * Retrieve the page title
	 * If empty, use display_name
	 *
	 * @return string
	 */
	public function getPageTitle()
	{
		return $this->getData('page_title') ? $this->getData('page_title') : $this->getName();
	}
	
	/**
	 * Retrieve the Meta description.
	 * If empty, use the short description
	 *
	 * @return string
	 */
	public function getMetaDescription()
	{
		return $this->getData('meta_description') ? $this->getData('meta_description') : strip_tags($this->getShortDescription());
	}

	/**
	 * Determine whether the splash page can be displayed
	 *
	 * @return bool
	 */
	public function canDisplay()
	{
		return Mage::helper('attributeSplash')->splashGroupPagesEnabled()
			&& $this->getId() && $this->getIsEnabled() && $this->hasSplashPages();
	}
	
	/**
	 * Retrieve the attribute model for the page
	 *
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	public function getAttributeModel()
	{
		if (!$this->hasAttributeModel()) {
			$this->setAttributeModel($this->getResource()->getAttributeModel($this));
		}
		
		return $this->getData('attribute_model');
	}

	/**
	 * Retrieve the store model associated with the splash page
	 *
	 * @return Mage_Core_Model_Store
	 */
	public function getStore()
	{
		if (!$this->hasStore()) {
			$this->setStore(Mage::getModel('core/store')->load($this->getStoreId()));
		}
		
		return $this->getData('store');
	}
	
	/**
	 * Retrieve a collection of products associated with the splash page
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	public function getSplashPages()
	{
		if (!$this->hasData('splash_pages')) {
			$this->setSplashPages($this->getResource()->getSplashPages($this));
		}
		
		return $this->getData('splash_pages');
	}
	
	/**
	 * Determine whether this group has any splash pages
	 *
	 * @return bool
	 */
	public function hasSplashPages()
	{
		return count($this->getSplashPages()) > 0;
	}
	
	/**
	 * Retrieve the date/time the item was updated
	 *
	 * @param bool $includeTime = true
	 * @return string
	 */
	public function getUpdatedAt($includeTime = true)
	{
		if ($str = $this->_getData('updated_at')) {
			return $includeTime ? $str : trim(substr($str, 0, strpos($str, ' ')));
		}
		
		return '';
	}

	/**
	 * Retrieve the date/time the item was created
	 *
	 * @param bool $includeTime = true
	 * @return string
	 */
	public function getCreatedAt($includeTime = true)
	{
		if ($str = $this->_getData('created_at')) {
			return $includeTime ? $str : trim(substr($str, 0, strpos($str, ' ')));
		}
		
		return '';
	}
}
