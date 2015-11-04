<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
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
			$collection = $this->getSplashPage()->getProductCollection();
			
			Mage::dispatchEvent(
				'attributeSplash_splash_page_product_collection', 
				array(
					'splash_page' => $this->getSplashPage(), 
					'collection' => $collection, 
					'layer' => $this
				)
			);

			$this->prepareProductCollection($collection);
			$this->_productCollections[0] = $collection;
		}
		
		return $collection;
	}

	/**
	 * Adds the store ID to the collection
	 * This ensures the price index functions correctly in 1.4.2.0
	 *
	 * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
	 *
	 */
	public function prepareProductCollection($collection)
	{
		$collection->addStoreFilter(Mage::app()->getStore()->getId());
	
		return parent::prepareProductCollection($collection);
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
		
		if ($splash = $this->getSplashPage()) {
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
     * Retrieves the current Splash model
     *
     * @return Fishpig_AttributeSplash_Model_Splash|null
     */
	public function getSplashPage()
	{
		if (!$this->hasSplashPage()) {
			if ($this->hasSplashPageId()) {
				$this->setSplashPage(Mage::getModel('attributeSplash/splash')->load($this->getSplashPageId()));
			}
			else {
				$this->setSplashPage(Mage::registry('splash_page'));
			}
		}
		
		return $this->getData('splash_page');
	}
}
