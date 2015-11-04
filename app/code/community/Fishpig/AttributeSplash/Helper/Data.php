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

	/**
	 * Retrieve a collection of attribtues that has already been splashed
	 *
	 * @return Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
	 */
	public function getSplashedAttributeCollection()
	{
		return $this->getSplashableAttributeCollection()->getSelect()
			->distinct(true)
			->join(array('_option_table' => $this->getTable('eav/attribute_option')),
				"`_option_table`.`attribute_id` = `main_table`.`attribute_id`",
				''
			)
			->join(array('_splash_table' => $this->getTable('attributeSplash/page')),
				"`_splash_table`.`option_id` = `_option_table`.`option_id`",
				''
			);
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
	 * Retrieve the read adapter
	 *
	 * @return 
	 */
	protected function _getReadAdapter()
	{
		return Mage::getSingleton('core/resource')->getConnection('core_read');
	}
	
	/**
	 * Retrieve a full Magento table name for $entity
	 *
	 * @param string $entity
	 * @return string
	 */
	public function getTable($entity)
	{
		return Mage::getSingleton('core/resource')->getTableName('entity');
	}
	
	/**
	 * Log an error message
	 *
	 * @param string $msg
	 * @return Fishpig_AttributeSplash_Helper_Data
	 */
	public function log($msg)
	{
		Mage::log($msg, false, 'attributeSplash.log', true);

		return $this;
	}
}
