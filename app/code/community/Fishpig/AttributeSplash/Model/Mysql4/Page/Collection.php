<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Page_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/page');
	}

	/**
	 * Init collection select
	 *
	 * @return Mage_Core_Model_Mysql4_Collection_Abstract
	*/
	protected function _initSelect()
	{
		$this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()));
		
		$this->getSelect()->join(
			array('_option_table' => $this->getTable('eav/attribute_option')),
			'`_option_table`.`option_id` = `main_table`.`option_id`',
			''
			)
			->join(
				array('_attribute_table' => $this->getTable('eav/attribute')),
				'`_attribute_table`.`attribute_id` = `_option_table`.`attribute_id`',
				array('attribute_id', 'attribute_code', 'frontend_label')
			);

		$this->getSelect()->order('_option_table.sort_order ASC');
		$this->getSelect()->order('main_table.display_name ASC');

		return $this;
	}
	
	/**
	 * Filter the collection by attribute Code
	 *
	 */
	public function addAttributeCodeFilter($attributeCode)
	{
		$this->getSelect()->where('`_attribute_table`.`attribute_code` = ?', $attributeCode);

		return $this;
	}
	
	/**
	 * Filter the collection by attribute ID
	 *
	 */
	public function addAttributeIdFilter($attributeId)
	{
		$this->getSelect()->where('`_attribute_table`.`attribute_id` = ?', $attributeId);

		return $this;
	}
	
	/**
	 * Filter the collection so only enabled pages are returned
	 *
	 */
	public function addIsEnabledFilter($value = 1)
	{
		return $this->addFieldToFilter('is_enabled', $value);
	}

	/**
	 * Filter the collection so that only featured items are returned
	 *
	 * @param int $isFeatured
	 */
	public function addIsFeaturedFilter($isFeatured = 1)
	{
		return $this->addFieldToFilter('is_featured', $isFeatured ? 1 : 0);
	}
	
	/**
	 * Filter the collection so that only featured items are returned
	 *
	 * @param int $isFeatured
	 */
	public function addIncludeInMenuFilter($include = 1)
	{
		return $this->addFieldToFilter('include_in_menu', $include ? 1 : 0);
	}
	
	/**
	 * Add a store ID filter to the collection
	 * If $includeAdmin is true, global pages will be returned also
	 *
	 * @param int $storeId
	 * @param bool $includeAdmin
	 */
	public function addStoreIdFilter($storeId, $includeAdmin = true)
	{
		$storeIds = $includeAdmin ? array(0, $storeId) : array($storeId);
		
		$this->getSelect()->where('`main_table`.`store_id` IN (?)', $storeIds);
		return $this;
	}
	
	/**
	 * Filter the collection by a product ID
	 *
	 * @param Mage_Catalog_Model_Product $product
	 */
	public function addProductFilter(Mage_Catalog_Model_Product $product, $storeId = null)
	{
		if (is_null($storeId)) {
			$storeId = Mage::app()->getStore()->getId();
		}

		$this->getSelect()
			->join(
				array('_product_filter' => $this->getTable('catalog/product_index_eav')),
				"`_product_filter`.`attribute_id`= `_attribute_table`.`attribute_id` AND `_product_filter`.`value` = `main_table`.`option_id`"
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`entity_id` = ?", $product->getId())
				. $this->getConnection()->quoteInto(" AND `_product_filter`.`store_id`=? ", $storeId),
				''
			);

		return $this;
	}
}
