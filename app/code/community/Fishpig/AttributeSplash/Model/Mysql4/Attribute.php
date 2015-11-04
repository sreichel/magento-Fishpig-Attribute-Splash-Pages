<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Attribute extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/attribute', 'attribute_id');
	}

	/**
	 * Custom load SQL
	 * Ensures that only select attributes can be loaded
	 * Applied entity_type_id if set
	 *
	 * @param string $field - field to match $value to
	 * @param string|int $value - $value to load record based on
	 * @param Mage_Core_Model_Abstract $object - object we're trying to load to
	 */
	protected function _getLoadSelect($field, $value, $object)
	{
		if (!is_numeric($value)) {
			$field = 'attribute_code';
		}
		
		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable())
			->where($this->getMainTable() . '.' . $field . '=?', $value)
			->where($this->getMainTable() . '.frontend_input IN (?)', array('select', 'multiselect'))
			->join(array('entity_type' => $this->getTable('eav/entity_type')), $this->getMainTable().'.entity_type_id = entity_type.entity_type_id', array('entity_type_code', 'entity_table'));
			
		if ($object->getEntityTypeId()) {
			$select->where($this->getMainTable() . '.entity_type_id=?', $object->getEntityTypeId());
		}
		
		$select->limit(1);

		return $select;
	}

	/**
	 * Returns a collections of attribute values
	 *
	 * @param Fishpig_AttributeSplash_Model_Attribute $attribute
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Attribute_Value_Collection
	 */
	public function getValues(Fishpig_AttributeSplash_Model_Attribute $attribute)
	{
		return Mage::getResourceModel('attributeSplash/attribute_option_collection')
			->addAttributeIdFilter($attribute->getId())
			->load();
	}
	
	/**
	 * Returns a collections of attribute values
	 *
	 * @param Fishpig_AttributeSplash_Model_Attribute $attribute
	 * @return Fishpig_AttributeSplash_Model_Mysql4_Attribute_Value_Collection
	 */
	public function getSplashPages(Fishpig_AttributeSplash_Model_Attribute $attribute, $storeId = null)
	{
		return Mage::getResourceModel('attributeSplash/splash_collection')
			->addAttributeIdFilter($attribute->getAttributeId())
			->addStoreIdFilter($storeId)
			->addStatusFilter(true);
	}

}