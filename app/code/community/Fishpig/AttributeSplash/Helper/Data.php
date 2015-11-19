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
				->addStoreFilter(Mage::app()->getStore())
				->addAttributeCodeFilter($attributeCode)
				->addProductFilter($product)
				->setPageSize(1)
				->setCurPage(1)
				->load();

			if (count($collection) > 0) {
				$page = $collection->getFirstItem();
				
				if ($page->getId()) {
					$product->setData($key, $page);
				}
			}
		}
		
		return $product->getData($key);
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
