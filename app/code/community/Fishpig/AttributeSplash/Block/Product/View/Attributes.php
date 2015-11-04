<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Product_View_Attributes extends Mage_Catalog_Block_Product_View_Attributes
{
	/**
	 * Retrieve a product's additional information
	 * If allowed, add splash page link to data array
	 *
	 * @param $excludeAttr
	 * @return array
	 */
	public function getAdditionalData(array $excludeAttr = array())
	{
		$additionalData = parent::getAdditionalData($excludeAttr);

		if (Mage::getStoreConfigFlag('attributeSplash/product/inject_links')) {
			$product = $this->getProduct();
			
			foreach($additionalData as $attributeCode => $data) {
				if ((int)$product->getData($attributeCode) > 0) {
					$splash = Mage::getModel('attributeSplash/page')->loadByOptionId($product->getData($attributeCode));
					
					if ($splash->getId()) {
						$additionalData[$attributeCode]['link'] = $splash->getUrl();
					}
				}
			}
		}

		return $additionalData;
	}
}
