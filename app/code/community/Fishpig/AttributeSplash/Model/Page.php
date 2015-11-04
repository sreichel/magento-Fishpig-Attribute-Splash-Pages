<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Page extends Mage_Core_Model_Abstract
{
	/**
	 * value used when attributes use grid mode
	 *
	 * @var int
	 */
	const ATTRIBUTE_MODE_GRID = 1;
	
	/**
	 * value used when attributes use list mode
	 *
	 * @var int
	 */
	const ATTRIBUTE_MODE_LIST = 2;

	/**
	 * value used when attributes use simple mode
	 *
	 * @var int
	 */
	const ATTRIBUTE_MODE_SIMPLE = 3;

	public function _construct()
	{
		$this->_init('attributeSplash/page');
	}

	/**
	 * Load a splash page based on an option ID
	 *
	 * @param int $optionId
	 * @return Fishpig_AttributeSplash_Model_Page
	 */
	public function loadByOptionId($optionId)
	{
		return $this->load($optionId, 'option_id');
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
	 * Retrieve the URL for the splash page
	 * If cannot find rewrite, return system URL
	 *
	 * @return string
	 */
	public function getUrl()
	{
		if ($this->getUrlPath()) {
			return Mage::getUrl('', array('_direct' => $this->getUrlPath()));
		}
		
		return Mage::getUrl($this->getResource()->getTargetPath($this));
	}
	
	/**
	 * Retrieve the URL path for the splash page
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
	 * Retrieve an attributeSplash/image helper object
	 * This allows you to resize the image dynamically or return the URL to the full image
	 *
	 * @return Fishpig_AttributeSplash_Helper_Image
	 */
	public function getImage()
	{
		return Mage::helper('attributeSplash/image')->getImageUrl($this->getData('image'));
	}
	
	/**
	 * Determine whether the splash page can be displayed
	 *
	 * @return bool
	 */
	public function canDisplay()
	{
		return $this->getId() && $this->getIsEnabled();
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
	 * Retrieve the option model for the page
	 *
	 * @return Mage_Eav_Model_Entity_Attribute_Option
	 */
	public function getOptionModel()
	{
		if (!$this->hasOptionModel()) {
			$this->setOptionModel($this->getResource()->getOptionModel($this));
		}
		
		return $this->getData('option_model');
	}
	
	/**
	 * Retrieve the option value for the spash page
	 *
	 * @return string
	 */
	public function getOptionValue()
	{
		return $this->getOptionModel()->getValue();
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
	public function getProductCollection()
	{
		if (!$this->hasProductCollection()) {
			$this->setProductCollection($this->getResource()->getProductCollection($this));
		}
		
		return $this->getData('product_collection');
	}
	
	/**
	 * Retrieve the group associated with the splash page
	 * This will retrieve the most related group
	 * If there isn't a group for the same store, the admin group will be returned
	 *
	 * @return Fishpig_AttributeSplash_Model_Group|false
	 */
	public function getSplashGroup()
	{
		if (!$this->hasSplashGroup()) {
			$this->setSplashGroup($this->getResource()->getSplashGroup($this));
		}
		
		return $this->getData('splash_group');
	}
}
