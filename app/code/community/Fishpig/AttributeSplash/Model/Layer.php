<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */
 
class Fishpig_AttributeSplash_Model_Layer extends Mage_Catalog_Model_Layer 
{
	/**
	 * Defines the product collection used
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	public function getProductCollection()
	{
		if (isset($this->_productCollections[0])) {
			$collection = $this->_productCollections[0];
		}
		else {
			$collection = $this->getSplash()->getProductCollection();
			
			Mage::dispatchEvent('attributeSplash_splash_page_product_collection', array('splash' => $this->getSplash(), 'collection' => $collection, 'layer' => $this));

			$this->prepareProductCollection($collection);
			$this->_productCollections[0] = $collection;
		}
		
		return $collection;
	}

	/**
	 * Stop the splash page attribute from dsplaying in the filter options
	 *
	 * @param   Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection $collection
	 * @return  Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection
     */
	protected function _prepareAttributeCollection($collection)
	{
		parent::_prepareAttributeCollection($collection);
		
		if ($splash = $this->getSplash()) {
			$collection->addFieldToFilter('attribute_code', array('neq' => $splash->getAttributeCode()));
		}
		
		return $collection;
	}
	
	/**
	 * Returns the ID of the current category being filtered by
	 *
	 * @return int|null
	 */
	public function getFilteredCategoryId()
	{
		return Mage::app()->getRequest()->getParam(Mage::getSingleton('catalog/layer_filter_category')->getRequestVar());
	}

	/**
	 * Retrieve the Splash Page model
	 *
	 * @return Fishpig_AttributeSplash_Model_Splash|null
	 */
	public function getSplash()
	{
		if (!$this->hasData('splash')) {
			if ($this->hasData('splash_id')) {
				$this->setSplash(Mage::getModel('attributeSplash/splash')->load($this->getSplashId()));
			}
			else {
				$this->setSplash(Mage::registry('splash_page'));
			}
		}
		
		return $this->getData('splash');
	}
}
