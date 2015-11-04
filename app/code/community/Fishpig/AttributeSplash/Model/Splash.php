<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Splash extends Mage_Core_Model_Abstract
{
	const ATTRIBUTE_MODE_GRID = 1;
	const ATTRIBUTE_MODE_LIST = 2;
	const ATTRIBUTE_MODE_SIMPLE = 3;
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('attributeSplash/splash');
	}
	
	public function getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$collection = Mage::getResourceModel('catalog/product_collection')
					->setStoreId($this->getStoreId());;

			/**
			 * Adds the splash page filter
			 * This uses the EAV index so ensure indexes are always up to date
			 */
				$alias = $this->getAttributeCode().'_idx';
				$write = Mage::getSingleton('core/resource')->getConnection('write');
				$storeId = ($this->getStoreId() == 0) ? Mage::app()->getStore()->getId() : $this->getStoreId();
				$collection->getSelect()
					->join(
						array($alias => Mage::getSingleton('core/resource')->getTableName('catalog/product_index_eav')),
						"`{$alias}`.`entity_id` = `e`.`entity_id`"
						. $write->quoteInto(" AND `{$alias}`.`attribute_id`=? ", $this->getAttributeId())
						. $write->quoteInto(" AND `{$alias}`.`store_id`=? ", $storeId)
						. $write->quoteInto(" AND `{$alias}`.`value`=?", $this->getOptionId()),
						''
					);
			
			$this->_productCollection = $collection;
		}
		
		return $this->_productCollection;
	}
	
	/**
	 * Retrieves the model name
	 * If the model isn't set, the option label is returned
	 *
	 * @return string
	 */
	public function getName()
	{
		return (trim($this->getDisplayName())) ? $this->getDisplayName() : $this->getFrontendLabel();
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
	
	public function getImageUrl()
	{
		return Mage::helper('attributeSplash/image')->getImageUrlIfExists($this->getImage());
	}
	
	public function getThumbnailUrl()
	{
		return Mage::helper('attributeSplash/image')->getImageUrlIfExists($this->getThumbnail());
	}
}
