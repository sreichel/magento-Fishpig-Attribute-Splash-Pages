<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
	/**
	 * Get filter item url
	 *
	 * @return string
	*/
	public function getUrl()
	{
		if (Mage::getStoreConfig('attributeSplash/frontend/inject_links')) {
			if (!Mage::registry('splash_page') && !$this->isSearchPage()) {
				if (Mage::helper('attributeSplash')->splashPageExists($this->getValue(), Mage::app()->getStore()->getId())) {
	
					$splashPage = Mage::getModel('attributeSplash/page')->loadByOptionId($this->getValue());
	
					if ($splashPage->canDisplay()) {
						$query = array(
							$this->getFilter()->getRequestVar() => null,
							Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null,
						);
						
						if ($_category = Mage::registry('current_category')) {
							if ($_category->getId() !== $this->getSplashCategoryId()) {
								$query['cat'] = $_category->getId();
							}
						}
						
						return Mage::getUrl('', array('_direct' => $splashPage->getUrlPath(), '_current' => true, '_query' => $query));
					}
				}
			}
		}

		return parent::getUrl();
	}
	
	/**
	 * Determine whether the current page is the search page
	 * If so, do not inject links
	 *
	 * @return bool
	 */
	public function isSearchPage()
	{
		return in_array('catalogsearch_result_index', Mage::getSingleton('core/layout')->getUpdate()->getHandles());
	}
	
	/**
	 * Retrieve the ID of the splash category
	 *
	 * @return int
	 */
	public function getSplashCategoryId()
	{
		if (!$this->hasSplashCategory()) {
			$this->setSplashCategory(Mage::helper('attributeSplash')->getBaseSplashCategory());
		}
		
		return $this->getSplashCategory()->getId();
	}
}
