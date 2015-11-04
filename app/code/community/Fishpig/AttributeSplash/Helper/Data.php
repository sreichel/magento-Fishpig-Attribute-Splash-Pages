<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
 
class Fishpig_AttributeSplash_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Retrieve a collection of attributes that can be splashed
	 *
	 * @return Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
	 */
	public function getSplashableAttributeCollection()
	{
		$collection = Mage::getResourceModel('eav/entity_attribute_collection')
		->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
		->addFieldToFilter('frontend_input', array('in' => array('select', 'multiselect')));
		
		$collection->getSelect()
			->where('`main_table`.`source_model` IS NULL OR `main_table`.`source_model` IN (?)', array('', 'eav/entity_attribute_source_table'));
		
		return $collection;
	}

	public function getSplashedAttributeCollection()
	{
		$attributes = $this->getSplashableAttributeCollection();

		$attributes->getSelect()
			->distinct(true)
			->join(
				array('_option_table' => $attributes->getResource()->getTable('eav/attribute_option')),
				"`_option_table`.`attribute_id` = `main_table`.`attribute_id`",
				''
			)
			->join(
				array('_splash_table' => $attributes->getResource()->getTable('attributeSplash/page')),
				"`_splash_table`.`option_id` = `_option_table`.`option_id`",
				''
			);
		
		return $attributes;
	}
	
	/**
	 * Retrieve an attribute model based on a option ID
	 *
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	public function getAttributeByOptionId($optionId)
	{
		if ($option = $this->getOptionById($optionId)) {
			$attribute = Mage::getModel('eav/entity_attribute')->load($option->getAttributeId());
			
			if ($attribute->getId()) {
				return $attribute;
			}
		}
		
		return false;
	}
	
	/**
	 * Retrieve a collection of options related to an attribute ID
	 *
	 * @param int $attributeId
	 * @param $storeId = 0
	 * @return Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
	 */
	public function getOptionCollectionByAttributeId($attributeId, $storeId = 0)
	{
		return Mage::getResourceModel('eav/entity_attribute_option_collection')
			->setAttributeFilter($attributeId)
			->setStoreFilter($storeId);
	}
	
	/**
	 * Retrieve an option by it's ID
	 *
	 * @param int $optionId
	 * @param int $storeId = null
	 * @return false|Mage_Eav_Model_Entity_Attribute_Option
	 */
	public function getOptionById($optionId, $storeId = null)
	{
		$options = Mage::getResourceModel('eav/entity_attribute_option_collection')
			->setStoreFilter($storeId)
			->addFieldToFilter('main_table.option_id', $optionId)
			->setPageSize(1)
			->setCurPage(1)
			->load();

		if (count($options) > 0) {
			return $options->getFirstItem();
		}
		
		return false;
	}

	/**
	 * Retrieve a splash page for the product / attribute code combination
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @param $attributeCode
	 * @return Fishpig_AttributeSplash_Model_Splash|null
	 */
	public function getProductSplashPage(Mage_Catalog_Model_Product $product, $attributeCode)
	{
		$key = $attributeCode . '_splash_page';
		
		if (!$product->hasData($key)) {
			$product->setData($key, false);
			$collection = Mage::getResourceModel('attributeSplash/page_collection')
				->addAttributeCodeFilter($attributeCode)
				->addProductFilter($product);
			
			$collection->load();
			
			if ($collection->count() >= 1) {
				$splash = $collection->getFirstItem();
				
				if ($splash->getId()) {
					$product->setData($key, $splash);
				}
			}
		}
		
		return $product->getData($key);
	}

	/**
	 * Determine whether a splash page exists for the $optionId/$storeId combination
	 *
	 * @param int $optionId
	 * @param int $storeId = 0
	 * @return bool
	 */
	public function splashPageExists($optionId, $storeId = 0)
	{
		$select = $this->_getReadAdapter()
			->select()
			->from(Mage::getSingleton('core/resource')->getTableName('attributeSplash/page'), 'page_id')
			->where('option_id = ?', $optionId)
			->where('store_id = 0 OR store_id = ?', $storeId)
			->order('store_id DESC')
			->limit(1);

		return $this->_getReadAdapter()->fetchOne($select) !== false;
	}
	
	/**
	 * Determine whether to display canonical meta tag
	 *
	 * @return bool
	 */
	public function canUseCanonical()
	{
		return Mage::getStoreConfigFlag('attributeSplash/seo/use_canonical');
	}
	

	/**
	 * Retrieve the read adapter
	 *
	 */
	protected function _getReadAdapter()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}
	
	/**
	 * Determine whether group pages are enabled
	 *
	 * @return bool
	 */
	public function splashGroupPagesEnabled()
	{
		return Mage::getStoreConfigFlag('attributeSplash/list_page/enabled');
	}
	
	/**
	 * Log an error message
	 *
	 * @param string $msg
	 * @param mixed $status
	 * @param string $file
	 * @param bool $force
	 * @return Fishpig_AttributeSplash_Helper_Data
	 */
	public function log($msg, $status = false, $file = 'attributeSplash.log', $force = true)
	{
		Mage::log($msg, $status, $file, $force);
		return $this;
	}
	
	/**
	 * Retrieve the current store code
	 *
	 * @return Mage_Core_Model_Store
	 */
	public function getCurrentFrontendStore()
	{
		if (!Mage::registry('current_frontend_store')) {
			$store = Mage::app()->getStore();
			
			if (!$store->getId() || $store->getCode() == 'admin') {
				$resource = Mage::getSingleton('core/resource');
				$connection = $resource->getConnection('core_read');
				$select = $connection->select()
					->from($resource->getTableName('core/store'), 'store_id')
					->where('store_id > ?', 0)
					->where('code != ?', 'admin')
					->limit(1)
					->order('sort_order ASC');
				
				$store = Mage::getModel('core/store')->load($connection->fetchOne($select));

				Mage::register('current_frontend_store', $store, true);
			}
			else {
				Mage::register('current_frontend_store', $store, true);
			}
		}
		
		return Mage::registry('current_frontend_store');
	}
}
