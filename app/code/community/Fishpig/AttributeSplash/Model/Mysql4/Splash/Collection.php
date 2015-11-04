<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Splash_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	protected $_storeId = null;

	public function _construct()
	{
		$this->_init('attributeSplash/splash');
	}
	
	protected function _initSelect()
	{
		return $this->getSelect()
			->from(array('main_table' => $this->getResource()->getMainTable()))
			->join(
				array('option_table'=>$this->_getTableName('eav_attribute_option')), 
				"main_table.option_id = option_table.option_id",
				""
			)
			->join(
				array('attribute_table' => $this->_getTableName('eav_attribute')),
				"option_table.attribute_id = attribute_table.attribute_id",
				array('attribute_id', 'attribute_code', 'attribute_name' => 'frontend_label')
			)
			->joinLeft(
				array('value_store' => $this->_getTableName('eav_attribute_option_value')),
				"main_table.option_id = value_store.option_id AND value_store.store_id = main_table.store_id",
				''
			)
			->joinLeft(
				array('value_admin' => $this->_getTableName('eav_attribute_option_value')),
				"main_table.option_id = value_admin.option_id AND value_admin.store_id = 0",
				array('option_value' => "IFNULL(value_store.value, value_admin.value)")
			)
			->join(
				array('store_table' => $this->getTable('core/store')), 
				"`main_table`.`store_id` = `store_table`.`store_id`", 
				array('store_name' => 'name')
			)
			->order('option_table.sort_order ASC')
			->order('option_value ASC');
	}
	
	/**
	 * Filters the collection by an attribute code
	 *
	 * @param string $attributeCode
	 */
	public function addAttributeCodeFilter($attributeCode)
	{
		$this->getSelect()->where('attribute_table.attribute_code=?', $attributeCode);
		return $this;
	}
	
	/**
	 * Filters the collection by an attribute id
	 *
	 * @param string $attributeId
	 */
	public function addAttributeIdFilter($attributeId)
	{
		$this->getSelect()->where('attribute_table.attribute_id=?', $attributeId);
		return $this;
	}
	
	/**
	 * Filters the collection by an option id
	 *
	 * @param string $attributeId
	 */
	public function addOptionIdFilter($optionId)
	{
		$this->getSelect()->where('main_table.option_id=?', $optionId);
		return $this;
	}
	
	/**
	 * Filters the collection by a store ID
	 *
	 * @param int|null $storeId - if NULL uses current store
	 */
	public function addStoreIdFilter($storeId = null)
	{
		if (!$storeId) {
			$storeId = Mage::app()->getStore()->getId();
		}
		
		$this->_storeId = $storeId;
		
		$this->getSelect()->where('main_table.store_id=0 OR main_table.store_id=?', $storeId);
		return $this;
	}
	
	/**
	 * Filter the collection by a product ID
	 *
	 * @param Mage_Catalog_Model_Product $product
	 */
	public function addProductFilter(Mage_Catalog_Model_Product $product)
	{
		$storeId = is_null($this->_storeId) ? Mage::app()->getStore()->getId() : $this->_storeId;

		$this->getSelect()
			->join(
				array('_product_filter' => $this->_getTableName('catalog/product_index_eav')),
				"`_product_filter`.`attribute_id`=attribute_table.attribute_id"
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`value` = main_table.option_id")
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`entity_id` = ?", $product->getId())
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`store_id`=? ", $storeId),
				''
			);

		return $this;
	}
	
	protected function _getTableName($entity)
	{
		return Mage::getSingleton('core/resource')->getTableName($entity);
	}
	
	public function addStatusFilter($enabled = true)
	{
		$this->getSelect()->where('status=?', $enabled ? 1 : 0);
		return $this;
	}
}