<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Helper_Product extends Fishpig_AttributeSplash_Helper_Abstract
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
		$collection = Mage::getResourceModel('attributeSplash/splash_collection')
			->addAttributeCodeFilter($attributeCode)
			->addProductFilter($product);
			
		if ($collection->count() >= 1) {
			$splash = $collection->getFirstItem();
			
			if ($splash->getId()) {
				$product->setData($attributeCode . '_splash_page', $splash);
				return $splash;
			}
		}
	
		return false;
	}
}
