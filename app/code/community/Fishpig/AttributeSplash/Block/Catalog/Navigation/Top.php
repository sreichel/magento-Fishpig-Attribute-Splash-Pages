<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Catalog_Navigation_Top extends Fishpig_AttributeSplash_Block_Catalog_Navigation_Top_Abstract
{
	/**
	 * Determine whether to inject links or not
	 *
	 * @return bool
	 */
	public function canDisplay()
	{
		return Mage::getStoreConfigFlag('attributeSplash/navigation/inject_together_title');
	}
	
	/**
	 * Determine whether to combine splash pages into 1 navigation column
	 *
	 * @return bool
	 */
	public function displaySplashPagesAfterCategories()
	{
		return Mage::getStoreConfigFlag('attributeSplash/navigation/inject_after');
	}
	
	/**
	 * Determine whether to combine splash pages into 1 navigation column
	 *
	 * @return bool
	 */
	public function displaySplashPagesTogether()
	{
		return $this->canDisplay() && Mage::getStoreConfigFlag('attributeSplash/navigation/inject_together');
	}
	
	/**
	 * Retrieve the navigation title used when splash pages are grouped
	 *
	 * @return string
	 */
	public function getGroupedTitle()
	{
		return Mage::getStoreConfig('attributeSplash/navigation/inject_together_title');
	}
	
	/**
	 * Retrieve a collection of all splash pages
	 *
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Page_Collection
	 */
	public function getAllSplashPages()
	{
		$pages = $this->_getSplashPageCollection();
		
		return $pages;
	}
	
	/**
	 * Retrieve a collection of splash pages
	 *
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Page_Collection
	 */
	protected function _getSplashPageCollection()
	{
		$pages = Mage::getResourceModel('attributeSplash/page_collection')
			->addIsEnabledFilter()
			->addIncludeInMenuFilter()
			->addStoreIdFilter(Mage::app()->getStore()->getId());

		return $pages;
	}
	
	/**
	 * Retrieve a collection of splash groups
	 *
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Group_Collection
	 */
	public function getSplashGroups()
	{
		$groups = Mage::getResourceModel('attributeSplash/group_collection')
			->addIsEnabledFilter()
			->addIncludeInMenuFilter();
			
		return $groups;
	}

	/**
	 * Retrieve a collection of splash pages for a splash group
	 *
	 * @param Fishpig_AttributeSplash_Model_Group $group
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Page_Collection
	 */
	public function getSplashGroupSplashPages(Fishpig_AttributeSplash_Model_Group $group)
	{
		return $group->getSplashPages()->addIncludeInMenuFilter();
	}
	
	/**
	 * Retrieve the number of top level categories
	 *
	 * @return int
	 */
	public function getCategoryIndexOffset()
	{
		if (!$this->hasCategoryIndexOffset()) {
			$categories = Mage::getResourceModel('catalog/category_collection');
			
			$categories->addAttributeToFilter('level', 2);
			$categories->addAttributeToFilter('include_in_menu', 1);
			$categories->setStoreId(Mage::app()->getStore()->getId());
			$categories->load();
			
			$this->setCategoryIndexOffset(count($categories));
		
		}
		
		return $this->getData('category_index_offset');
	}
}
