<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Attribute_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/attribute');
	}

	protected function _initSelect()
	{
		return $this->getSelect()
			->distinct()
			->from(array('main_table' => $this->getResource()->getMainTable()))
			->join(
				array('catalog_eav' => $this->getResource()->getTable('catalog/eav_attribute')),
				"`catalog_eav`.`attribute_id` = `main_table`.`attribute_id`",
				"position"
			)
			->join(array('entity_type' => $this->getTable('eav/entity_type')), "`main_table`.`entity_type_id` = `entity_type`.`entity_type_id`", array('entity_type_code', 'entity_table'))
			->join(array('option_table' => $this->getTable('eav/attribute_option')), "`main_table`.`attribute_id` = `option_table`.`attribute_id`", "")
			->where("`main_table`.`frontend_input` IN (?)", array('select', 'multiselect'))
			->order('position ASC')
			->order("main_table.attribute_code ASC");
	}
}
