<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Page_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	/**
	 * Flag to determine whether attribute data added
	 *
	 * @var bool
	 */
	protected $_attributeDataAdded = false;
	
	public function _construct()
	{
		$this->_init('attributeSplash/page');
	}
	
	/**
	 * Add attribute data to the collection
	 *
	 */
	public function addAttributeOptionData()
	{
		if (!$this->_attributeDataAdded) {
			$this->_attributeDataAdded = true;
			$this->getSelect()
				->join(
					array('_option_table' => $this->getTable('eav/attribute_option')),
					"`_option_table`.`option_id` = `main_table`.`option_id`",
					''
				)
				->join(
					array('_attribute_table' => $this->getTable('eav/attribute')),
					"`_attribute_table`.`attribute_id` = `_option_table`.`attribute_id`",
					array('attribute_label' => 'frontend_label', 'attribute_id', 'attribute_code')
				);
		}
		
		return $this;
	}
	
	/**
	 * Add the store name to the collection
	 *
	 */
	public function addStoreName()
	{
		$this->getSelect()
			->join(
				array('_store_table' => $this->getTable('core/store')),
				"`_store_table`.`store_id` = `main_table`.`store_id`",
				array('store_name' => 'name')
			);
			
		return $this;
	}
	
	/**
	 * Filter the collection by attribute ID
	 *
	 */
	public function addAttributeCodeFilter($attributeCode)
	{
		$this->addAttributeOptionData();
		$this->getSelect()->where('`_attribute_table`.`attribute_code` = ?', $attributeCode);

		return $this;
	}
}
