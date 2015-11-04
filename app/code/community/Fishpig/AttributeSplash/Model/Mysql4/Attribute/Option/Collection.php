<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Attribute_Option_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/attribute_option');
	}

	/**
	 * Initialises the Select object
	 */
	protected function _initSelect()
	{
		 $this->getSelect()
			->from(array('main_table' => $this->getResource()->getMainTable()))
			->joinLeft(
				array('value_table_1' => $this->getTable('eav/attribute_option_value')), 
				"`main_table`.`option_id` = `value_table_1`.`option_id` AND `value_table_1`.`store_id`='{Mage_Core_Model_App::ADMIN_STORE_ID}'",
				array())
			->joinLeft(
				array('value_table_2' => $this->getTable('eav/attribute_option_value')), 
				"`main_table`.`option_id` = `value_table_2`.`option_id` AND `value_table_2`.`store_id`='{Mage::app()->getStore()->getId()}'", 
				array('value' => "IFNULL(`value_table_1`.`value`, `value_table_2`.`value`)"))
			->order("main_table.sort_order ASC")
			->order("value ASC");

		return $this;
	}
	
	/**
	 * Filters the collection by an attribute ID
	 *
	 * @param int $attributeId
	 */
	public function addAttributeIdFilter($attributeId)
	{
		return $this->addFieldToFilter('attribute_id', $attributeId);
	}
}
