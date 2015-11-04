<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Catalog_Product_View_Attributes extends Mage_Catalog_Block_Product_View_Attributes
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
			$product 	= $this->getProduct();
			$toIgnore = array(Mage::helper('catalog')->__('N/A'), Mage::helper('catalog')->__('No'));
			$storeId = Mage::app()->getStore()->getId();

			foreach($additionalData as $attributeCode => $data) {
				if (!in_array($data['value'], $toIgnore)) {
					if ($optionIds = $this->_getOptionIds($product, $attributeCode) ) {
						$buffer = array();
	
						foreach($optionIds as $optionId) {
							$splash = Mage::getModel('attributeSplash/page')->setStoreId($storeId)->loadByOptionId($optionId);
						
							if ($splash->getId()) {
								$name = $splash->getOptionModel() ? $splash->getOptionModel()->getValue() : $splash->getName();
								$buffer[] = sprintf('<a href="%s" title="%s">%s</a>', $splash->getUrl(), $this->escapeHtml($splash->getName()), $this->escapeHtml($name));
							}
							else {
								$option = Mage::helper('attributeSplash')->getOptionById($optionId, Mage::app()->getStore()->getId());
								
								if ($option) {
									$value = $option->getStoreDefaultValue() ?  $option->getStoreDefaultValue() : $option->getValue();
									
									$buffer[] = $this->escapeHtml($value);
								}
							}
						}
						
						$additionalData[$attributeCode]['value'] = implode(', ', $buffer);
					}
				}
				else {
					unset($additionalData[$attributeCode]);
				}
			}
		}

		return $additionalData;
	}
	
	protected function _getOptionIds($product, $attributeCode)
	{
		$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);
		$optionIds = array();

		if ($attribute->getFrontendInput() == 'multiselect') {
			$optionIds = explode(',', $product->getData($attributeCode));
		}
		elseif ($attribute->getFrontendInput() == 'select') {
			$optionIds = array($product->getData($attributeCode));
		}
		
		return count($optionIds) > 0 ? $optionIds : false;
	}
}
