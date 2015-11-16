<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Addon_QuickCreate_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Process the quick create data
	 *
	 * @param array $data = array
	 * @return bool
	 */
	public function process(array $data = array())
	{
		$data = new Varien_Object($data);
		
		if (!$data->getAttributeId()) {
			throw new Exception('Invalid attribute ID or store ID set.');
		}
		
		$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $data->getAttributeId());
		
		if (!$attribute || !$attribute->getId()) {
			throw new Exception('Attribute does not exist');
		}
		
		if (!$attribute->getSource()) {
			throw new Exception('Cannot get attribute options.');
		}
		
		$count = 0;
		$options = $attribute->getSource()->getAllOptions(false);
		
		foreach($options as $option) {
			try {
				$page = Mage::getModel('attributeSplash/page')
					->setAttributeId($data->getAttributeId())
					->setStoreIds(array((int)$data->getStoreId()))
					->setOptionId($option['value'])
					->setDisplayName($option['label'])
					->setSkipReindex(true)
					->setSkipAutoCreateGroup(true)
					->setAttributeId($data->getAttributeId());
					
				$page->save();
				
				++$count;
			}
			catch (Exception $e) {}
		}
		
		$pages = Mage::getResourceModel('attributeSplash/page_collection')
			->addStoreFilter($data->getStoreId())
			->addAttributeIdFilter($data->getAttributeId())
			->setPageSize(1)
			->load();
		
		if (count($pages) > 0) {
			$page = $pages->getFirstItem();
			$page->getResource()->updateSplashGroup($page);
			$page->getResource()->reindexAll();
			$page->getSplashGroup()->getResource()->reindexAll();
		}
		
		return $count;
	}
}
