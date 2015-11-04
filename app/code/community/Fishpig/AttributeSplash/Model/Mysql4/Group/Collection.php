<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Group_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/group');
	}

	/**
	 * Init collection select
	 *
	 * @return Mage_Core_Model_Mysql4_Collection_Abstract
	*/
	protected function _initSelect()
	{
		$this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()));
		
		$this->getSelect()->join(
				array('_attribute_table' => $this->getTable('eav/attribute')),
				'`_attribute_table`.`attribute_id` = `main_table`.`attribute_id`',
				array('attribute_code', 'frontend_label')
			);

		return $this;
	}
	
	/**
	 * Filter the collection by attribute Code
	 *
	 */
	public function addAttributeCodeFilter($attributeCode)
	{
		$this->getSelect()->where('`_attribute_table`.`attribute_code` = ?', $attributeCode);

		return $this;
	}
	
	/**
	 * Filter the collection by attribute ID
	 *
	 */
	public function addAttributeIdFilter($attributeId)
	{
		$this->getSelect()->where('`main_table`.`attribute_id` = ?', $attributeId);

		return $this;
	}

	/**
	 * Filter the collection so only enabled pages are returned
	 *
	 */
	public function addIsEnabledFilter($value = 1)
	{
		return $this->addFieldToFilter('is_enabled', $value);
	}

	/**
	 * Filter the collection so that only featured items are returned
	 *
	 * @param int $isFeatured
	 */
	public function addIncludeInMenuFilter($include = 1)
	{
		return $this->addFieldToFilter('include_in_menu', $include ? 1 : 0);
	}
}
