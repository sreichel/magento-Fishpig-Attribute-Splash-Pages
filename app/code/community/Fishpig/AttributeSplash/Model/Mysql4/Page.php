<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Page extends Fishpig_AttributeSplash_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/page', 'page_id');
	}

	/**
	 * Retrieve select object for load object data
	 * This gets the default select, plus the attribute id and code
	 *
	 * @param   string $field
	 * @param   mixed $value
	 * @return  Zend_Db_Select
	*/
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()))
			->where("`main_table`.`{$field}` = ?", $value)
			->order('store_id DESC')
			->limit(1);

		if ($object->getStoreId()) {
			$select->where('store_id = 0 OR store_id = ?', $object->getStoreId());
		}

		$select->join(
			array('_option_table' => $this->getTable('eav/attribute_option')),
			'`_option_table`.`option_id` = `main_table`.`option_id`',
			''
			)
			->join(
				array('_attribute_table' => $this->getTable('eav/attribute')),
				'`_attribute_table`.`attribute_id` = `_option_table`.`attribute_id`',
				array('attribute_id', 'attribute_code')
			);
		
		return $select;
	}
	
	/**
	 * Retrieve the attribute model for the page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	public function getAttributeModel(Fishpig_AttributeSplash_Model_Page $page)
	{
		if ($page->getAttributeId()) {
			return Mage::getModel('eav/entity_attribute')->load($page->getAttributeId());
		}
		else if ($page->getOptionId()) {
			return Mage::helper('attributeSplash')->getAttributeByOptionId($page->getOptionId());
		}
		
		return false;
	}

	/**
	 * Retrieve the option model for the page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return Mage_Eav_Model_Entity_Attribute_Option
	 */
	public function getOptionModel(Fishpig_AttributeSplash_Model_Page $page)
	{
		return Mage::helper('attributeSplash')->getOptionById($page->getOptionId(), $page->getStoreId());
	}
	 
	/**
	 * Retrieve a collection of products associated with the splash page
	 * @thanks Flat catalog fix:
	 *   http://www.xtreme-vision.net/magento/magento-fishpig-attribute-splash-pages-and-flat-catalog
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */	
	public function getProductCollection(Fishpig_AttributeSplash_Model_Page $page)
	{
		$collection = Mage::getResourceModel('catalog/product_collection')
			->addStoreFilter($page->getStoreId());

		/**
		 * Adds the splash page filter
		 * This uses the EAV index so ensure indexes are always up to date
		 */
		$alias = $page->getAttributeCode().'_idx';
		$read = Mage::getSingleton('core/resource')->getConnection('read');
		$storeId = ($page->getStoreId() == 0) ? Mage::app()->getStore()->getId() : $page->getStoreId();
		$collection->getSelect()
			->join(
				array($alias => $this->getTable('catalog/product_index_eav')),
				"`{$alias}`.`entity_id` = `e`.`entity_id`"
				. $read->quoteInto(" AND `{$alias}`.`attribute_id` = ? ", $page->getAttributeId())
				. $read->quoteInto(" AND `{$alias}`.`store_id` = ? ", $storeId)
				. $read->quoteInto(" AND `{$alias}`.`value` = ?", $page->getOptionId()),
				''
			);

		$visibilities = array(
			Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
			Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH
		);

		if (Mage::getStoreConfigFlag('catalog/frontend/flat_catalog_product')) {
			$flatTable = Mage::getResourceSingleton('catalog/product_flat')->getFlatTableName($storeId);

			$collection->getSelect()
				->joinLeft(array('cpl' => $flatTable), "e.entity_id = cpl.entity_id")
				->where("cpl.visibility IN (?)", $visibilities);
		}
		else {
			$collection->addAttributeToFilter('visibility', array('in' => $visibilities));
		}
						
		$collection->addAttributeToFilter('status', 1);
		
		if (Mage::getStoreConfigFlag('attributeSplash/product/hide_out_of_stock', $page->getStoreId())) {
			Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		}
		
		if (Mage::getStoreConfigFlag('attributeSplash/product/hide_no_image', $page->getStoreId())) {
			$imageAttributeCode = Mage::getStoreConfig('attributeSplash/product/image_attribute_code', $page->getStoreId());
			
			if ($imageAttributeCode) {
				$collection->addAttributeToFilter($imageAttributeCode, array(
					'notnull' => '',
					'nteq' => ''
				));
			}
		}
		
		return $collection;
	}
	
	
	protected function _afterSave(Mage_Core_Model_Abstract $object)
	{
		parent::_afterSave($object);
		
		$this->updateSplashGroup($object);
		
		return $this;
	}

	/**
	 * Update/refresh the rewrites for every splash object
	 *
	 */
	public function updateAllUrlRewrites()
	{
		$objects = Mage::getResourceModel('attributeSplash/page_collection');
		
		foreach($objects as $object) {
			$this->updateUrlRewrite($object);
		}	
	}
	
	/**
	 * Retrieve the target path of the splash page
	 * This is used for the URL rewrite
	 *
	 * @return string|null
	 */	
	public function getTargetPath(Fishpig_AttributeSplash_Model_Page $page)
	{
		return $page->getId() ? 'splash/page/view/id/' . $page->getId() : null;
	}
	
	/**
	 * Retrieve the ID path of the splash page
	 * This is used for loading the URL rewrite
	 *
	 * @return string|null
	 */
	public function getIdPath(Fishpig_AttributeSplash_Model_Page $page)
	{
		return $page->getId() ? 'splash/page/' . $page->getId() : null;
	}
	
	/**
	 * Check whether the attribute group exists
	 * If not, create the group
	 *
	 * @param Fishpig_AttributeSPlash_Model_Page $page
	 */
	public function updateSplashGroup(Fishpig_AttributeSplash_Model_Page $page)
	{
		if (!$page->getSplashGroup()) {
			$group = Mage::getModel('attributeSplash/group')
				->setAttributeId($page->getAttributeModel()->getId())
				->setDisplayName($page->getAttributeModel()->getFrontendLabel())
				->setStoreId((int)$page->getStoreId())
				->setIsEnabled(1);
				
			try {
				$group->save();
			}
			catch (Exception $e) {
				Mage::helper('attributeSplash')->log($e->getMessage());
			}
		}

		return $this;
	}

	/**
	 * Retrieve the group associated with the splash page
	 * This will retrieve the most related group
	 * If there isn't a group for the same store, the admin group will be returned
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return Fishpig_AttributeSplash_Model_Group|false
	 */
	public function getSplashGroup(Fishpig_AttributeSplash_Model_Page $page)
	{
		$groups = Mage::getResourceModel('attributeSplash/group_collection')
			->addAttributeIdFilter($page->getAttributeModel()->getAttributeId());
			
		$groups->getSelect()
			->where('store_id = 0 OR store_id = ?', $page->getStoreId())
			->order('store_id desc')
			->limit(1);

		$groups->load();
		
		if (count($groups) > 0) {
			return $groups->getFirstItem();
		}

		return false;
	}
}
