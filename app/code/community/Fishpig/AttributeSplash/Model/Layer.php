<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Layer extends Mage_Catalog_Model_Layer 
{
	/**
	 * Retrieve the current category for use when generating product collections
	 * As we are using splash pages and not categories, this returns the splash page
	 *
	 * @return false|Fishpig_AttributeSplashPro_Model_Page
	 */
	public function getCurrentCategory()
	{
		return $this->getSplashPage();
	}

	/**
	 * Retrieve the splash page
	 * We add an array to children_categories so that it can act as a category
	 *
	 * @return false|Fishpig_AttributeSplashPro_Model_Page
	 */
	public function getSplashPage()
	{
		if (($page = Mage::registry('splash_page')) !== null) {
			$page->setChildrenCategories(array());

			return $page;
		}
		
		return false;
	}

	/**
	 * Stop the splash page attribute from dsplaying in the filter options
	 *
	 * @param   Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection $collection
	 * @return  Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection
     */
	protected function _prepareAttributeCollection($collection)
	{
		parent::_prepareAttributeCollection($collection);
		
		if ($splash = $this->getSplashPage()) {
			$collection->addFieldToFilter('attribute_code', array('neq' => $splash->getAttributeCode()));
		}
		
		return $collection;
	}
}
