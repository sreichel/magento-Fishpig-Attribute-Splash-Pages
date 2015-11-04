<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Attribute_Option_Extra extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/attribute_option_extra', 'entity_id');
		
		if (Mage::app()->isSingleStoreMode()) {
			$uniqueMessage = 'An attribute splash page for that option';
		}
		else {
			$uniqueMessage = 'An attribute splash page using that option/store combination';
		}
		
		$this->_uniqueFields = array(
			array(
				'field' => array('option_id', 'store_id'),
				'title' => Mage::helper('attributeSplash')->__($uniqueMessage)
			)
		);
	}

	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('e' => $this->getMainTable()), '*')
			->where('e.' . $field . '=?', $value)
			->joinLeft(array('store_table' => $this->getTable('core/store')), "`e`.`store_id` = `store_table`.`store_id`", array('store_name' => 'name'))
			->limit(1);

		return $select;
	}
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
	{
		parent::_afterLoad($object);
		
		if ($object->getStoreId() == '0') {
			$object->setStoreName(Mage::helper('core')->__('All Stores'));
		}
		
		return $this;
	}
    
	public function prepareDataForSave(Mage_Core_Model_Abstract $object)
	{
		if (!$object->getData('display_name')) {
			$object->setData('display_name', $object->getOptionValue());
		}

		if (!$object->getData('url_key')) {
			$object->setData('url_key', $object->formatUrlKey($object->getData('display_name')));
		}
		
		return $this;
	}
	
	public function updateRewrite(Fishpig_AttributeSplash_Model_Attribute_Option_Extra $object)
	{
		return $this->_updateRewrite($object, $object->getStoreId());
	}
	
	public function updateAttributeRewrite(Fishpig_AttributeSplash_Model_Attribute_Option_Extra $object)
	{
		if ($attribute = $object->getAttributeModel()) {
			return $this->_updateRewrite($attribute, $object->getStoreId());
		}
		
		return $this;
	}
	
	protected function _updateRewrite(Mage_Core_Model_Abstract $object, $storeId)
	{
		$helper = Mage::helper('attributeSplash/rewrite');
		$select = $this->_getReadAdapter()
				->select()
				->from($this->_resources->getTableName('core/url_rewrite'), 'url_rewrite_id')
				->where('id_path=?', $object->getIdPath())
				->where('store_id=?', $storeId)
				->limit(1);
		
		if ($rewriteId = $this->_getReadAdapter()->fetchOne($select)) {
			$this->_getWriteAdapter()
				->update(
					$this->_resources->getTableName('core/url_rewrite'),
					array('request_path' => $helper->getRequestPath($object), 'description' => $helper->getRewriteDescription($object)),
					$this->_getWriteAdapter()->quoteInto("url_rewrite_id=?", $rewriteId)
				);
		}
		else {
			$this->_getWriteAdapter()
				->insert(
					$this->_resources->getTableName('core/url_rewrite'),
					array(
						'request_path' => $helper->getRequestPath($object), 
						'store_id' => $storeId, 
						'id_path' => $object->getIdPath(),
						'target_path' => $object->getTargetPath(),
						'is_system' => 1,
						'description' => $helper->getRewriteDescription($object),
					),
					$this->_getWriteAdapter()->quoteInto("url_rewrite_id=?", $rewriteId)
				);
		}
		
		return $this;
	}
	
	public function deleteRewrite(Fishpig_AttributeSplash_Model_Attribute_Option_Extra $object)
	{
		$this->_getWriteAdapter()
			->delete(
				$this->_resources->getTableName('core/url_rewrite'),
				$this->_getWriteAdapter()->quoteInto("id_path=?", $object->getIdPath())
				. " AND ". $this->_getWriteAdapter()->quoteInto("store_id=?", $object->getStoreId())
			);
		
		return $this;
	}
	
	public function deleteAttributeRewrite(Fishpig_AttributeSplash_Model_Attribute_Option_Extra $object)
	{
		if ($attribute = $object->getAttributeModel()) {
			if (count($attribute->getSplashPages($object->getStoreId())) <= 1) {
				$this->_getWriteAdapter()
					->delete(
						$this->_resources->getTableName('core/url_rewrite'),
						$this->_getWriteAdapter()->quoteInto("id_path=?", $attribute->getIdPath())
						. " AND ". $this->_getWriteAdapter()->quoteInto("store_id=?", $object->getStoreId())
					);
			}
		}
		
		return $this;
	}
	
}
