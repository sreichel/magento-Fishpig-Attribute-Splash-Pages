<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Attribute_Option_Extra_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	/**
	 * Indicates whether Attribute table has been joined
	 * This stops the table being joined multiple times
	 *
	 * @var bool
	 */
	protected $_hasAttributeInfo = false;

	/**
	 * Indicates whether Option table has been joined
	 * This stops the table being joined multiple times
	 *
	 * @var bool
	 */	
	protected $_hasOptionInfo = false;
	
	public function _construct()
	{
		$this->_init('attributeSplash/attribute_option_extra');
	}

	/**
	 * Initialises the Select object
	 */
	protected function _initSelect()
	{
		return $this->getSelect()->distinct()->from(array('main_table' => $this->getResource()->getMainTable()));
	}
	
	/**
	 * Adds the store name to the collection
	 */
	public function addStoreName()
	{
		$this->getSelect()
			->joinLeft(
				array('store_store' => $this->getTable('core/store')),
				"`main_table`.`store_id` = `store_store`.`store_id`",
				""
			)
			->joinLeft(
				array('store_admin' => $this->getTable('core/store')),
				"`main_table`.`store_id` = 0",
				array('store_name' => 'IFNULL(store_store.name, store_admin.name)')
			);
		
		return $this;
	}
	
	/**
	 * Adds the attribute information to the collection
	 */
	public function addAttributeInfo()
	{
		if (!$this->_hasAttributeInfo) {
			$this->_hasAttributeInfo = true;

			$this->getSelect()
				->join(
					array('option_table' => $this->getTable('eav/attribute_option')),
					"`main_table`.`option_id` = `option_table`.`option_id`",
					""
				)
				->join(
					array('attribute_table' => $this->getTable('eav/attribute')),
					"`option_table`.`attribute_id` = `attribute_table`.`attribute_id`",
					array('attribute_code', 'frontend_label')
				);
		}
		
		return $this;
	}	
	
	/**
	 * Adds the option information to the collection
	 * This also adds the attribute information
	 */
	public function addAttributeOptionInfo()
	{
		if (!$this->_hasOptionInfo) {
			$this->_hasOptionInfo = true;
			$this->addAttributeInfo()
				->getSelect()
				->joinLeft(
					array('value_store' => $this->getTable('eav/attribute_option_value')),
					"`value_store`.`option_id` = `option_table`.`option_id` AND `value_store`.`store_id` = `main_table`.`store_id`",
					''
				)
				->joinLeft(
					array('value_admin' => $this->getTable('eav/attribute_option_value')),
					"`value_admin`.`option_id` = `option_table`.`option_id` AND `value_admin`.`store_id` = 0",
					array('option_value' => 'IFNULL(value_store.value, value_admin.value)')
				);
		}
		
		return $this;
	}
}
