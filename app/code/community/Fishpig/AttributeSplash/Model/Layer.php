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
			$attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
			$collection = Mage::getResourceModel('catalog/product_collection')
				->setStoreId(Mage::app()->getStore()->getId())
				->addAttributeToSelect($attributes)
				->addMinimalPrice()
				->addFinalPrice()
				->addTaxPercents();
			
			$collection->addUrlRewrite($this->getCurrentCategory()->getId());

			/**
			 * Adds the splash page filter
			 * This uses the EAV index so ensure indexes are always up to date
			 */
			if ($splash = $this->getSplash()) {
				$alias = $splash->getAttributeCode().'_idx';
				$write = Mage::getSingleton('core/resource')->getConnection('write');
				$storeId = ($splash->getStoreId() == 0) ? Mage::app()->getStore()->getId() : $splash->getStoreId();
				$collection->getSelect()
					->join(
						array($alias => Mage::getSingleton('core/resource')->getTableName('catalog/product_index_eav')),
						"`{$alias}`.`entity_id` = `e`.`entity_id`"
						. $write->quoteInto(" AND `{$alias}`.`attribute_id`=? ", $splash->getAttributeId())
						. $write->quoteInto(" AND `{$alias}`.`store_id`=? ", $storeId)
						. $write->quoteInto(" AND `{$alias}`.`value`=?", $splash->getOptionId()),
						''
					);
			}

			Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
			Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
			
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
