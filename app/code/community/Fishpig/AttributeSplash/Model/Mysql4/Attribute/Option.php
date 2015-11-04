<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Attribute_Option extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/attribute_option', 'option_id');
	}

	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()))
			->where('main_table.' . $field . '=?', $value)
			->join(array('value_table' => Mage::getSingleton('core/resource')->getTableName('eav_attribute_option_value')), "`main_table`.`option_id` = `value_table`.`option_id`", array('value', 'store_id'))
			->where("`value_table`.`store_id` =?", Mage::app()->getStore()->getStoreId())
			->limit(1);

		return $select;
	}

}