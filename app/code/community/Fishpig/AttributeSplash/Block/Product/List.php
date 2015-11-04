<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Product_List extends Mage_Catalog_Block_Product_List
{
	/**
	 * Retrieves the current layer product collection
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	protected function _getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$this->_productCollection = Mage::getSingleton('attributeSplash/layer')->getProductCollection();
		}
		
		return $this->_productCollection;
	}
}
