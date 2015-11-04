<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Group extends Fishpig_AttributeSplash_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/group', 'group_id');
	}

	/**
	 * Retrieve select object for load object data
	 * This gets the default select, plus the attribute id and code
	 *
	 * @param   string $field
	 * @param   mixed $value
	 * @return  Zend_Db_Select
	*/
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()))
			->where("`main_table`.`{$field}` = ?", $value)
			->limit(1);
		
		$select->join(
				array('_attribute_table' => $this->getTable('eav/attribute')),
				'`_attribute_table`.`attribute_id` = `main_table`.`attribute_id`',
				array('attribute_code', 'frontend_label')
			);
		
		return $select;
	}
	
	/**
	 * Retrieve the attribute model for the group
	 *
	 * @param Fishpig_AttributeSplash_Model_Group $group
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	public function getAttributeModel(Fishpig_AttributeSplash_Model_Group $group)
	{
		if ($group->getAttributeId()) {
			return Mage::getModel('eav/entity_attribute')->load($group->getAttributeId());
		}
		
		return false;
	}

	public function getSplashPages(Fishpig_AttributeSplash_Model_Group $group)
	{
		$splashPages = Mage::getResourceModel('attributeSplash/page_collection')
			->addIsEnabledFilter();
			
		if ($group->getStoreId() > 0) {
			$splashPages->addStoreIdFilter($group->getStoreId());
		}
		else {
			if (($storeId = Mage::app()->getStore()->getId()) > 0) {
				$splashPages->addStoreIdFilter($storeId);
			}
		}
			
		$splashPages->addAttributeIdFilter($group->getAttributeId());

		return $splashPages;
	}

	/**
	 * Update/refresh the rewrites for every splash object
	 *
	 */
	public function updateAllUrlRewrites()
	{
		$objects = Mage::getResourceModel('attributeSplash/group_collection');
		
		foreach($objects as $object) {
			try {
				$this->updateUrlRewrite($object);
			}
			catch (Exception $e) {
				Mage::helper('attributeSplash')->log($e->getMessage());
			}
		}
		
		
	}
	
	public function updateUrlRewrite(Mage_Core_Model_Abstract $object)
	{
		parent::updateUrlRewrite($object);

		$splashPages = Mage::getResourceModel('attributeSplash/page_collection')
//			->addStoreIdFilter($object->getStoreId())
			->addAttributeIdFilter($object->getAttributeId());

		foreach($splashPages as $page) {
			try {
				$page->getResource()->updateUrlRewrite($page);
			}
			catch (Exception $e) {
				Mage::helper('attributeSplash')->log($e->getMessage());
			}
		}

		return $this;
	}
	
	/**
	 * Retrieve the target path of the splash group
	 * This is used for the URL rewrite
	 *
	 * @return string|null
	 */	
	public function getTargetPath(Fishpig_AttributeSplash_Model_Group $group)
	{
		return $group->getId() ? 'splash/group/view/id/' . $group->getId() : null;
	}
	
	/**
	 * Retrieve the ID path of the splash group
	 * This is used for loading the URL rewrite
	 *
	 * @return string|null
	 */
	public function getIdPath(Fishpig_AttributeSplash_Model_Group $group)
	{
		return $group->getId() ? 'splash/group/' . $group->getId() : null;
	}
}
