<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Splash extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/splash', 'entity_id');
	}

	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('e' => $this->getMainTable()))
			->where('e.' . $field . '=?', $value)
			->join(
				array('option_table'=>$this->_getTableName('eav_attribute_option')), 
				"e.option_id = option_table.option_id",
				""
			)
			->join(
				array('attribute_table' => $this->_getTableName('eav_attribute')),
				"option_table.attribute_id = attribute_table.attribute_id",
				array('attribute_id', 'attribute_code', 'attribute_name' => 'frontend_label')
			)
			->joinLeft(
				array('value_store' => $this->_getTableName('eav_attribute_option_value')),
				"e.option_id = value_store.option_id AND value_store.store_id = e.store_id",
				''
			)
			->joinLeft(
				array('value_admin' => $this->_getTableName('eav_attribute_option_value')),
				"e.option_id = value_admin.option_id AND value_admin.store_id = 0",
				array('option_value' => "IFNULL(value_store.value, value_admin.value)")
			)
			->join(
				array('store_table' => $this->getTable('core/store')), 
				"`e`.`store_id` = `store_table`.`store_id`", 
				array('store_name' => 'name')
			)
			->limit(1);

		return $select;
	}

	protected function _getTableName($entity)
	{
		return Mage::getSingleton('core/resource')->getTableName($entity);
	}
	
}