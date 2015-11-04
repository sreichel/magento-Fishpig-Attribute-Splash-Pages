<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

abstract class Fishpig_AttributeSplash_Model_Resource_Collection_Abstract extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/**
	 * Init the entity type
	 *
	 */
	public function _construct()
	{
		$this->_map['fields'][$this->getResource()->getIdFieldName()] = 'main_table.' . $this->getResource()->getIdFieldName();
		$this->_map['fields']['store'] = 'store_table.store_id';
	}
	
	/**
	 * Add a store filter
	 *
	 * @param int $store
	 * @return $this
	 */
	public function addStoreIdFilter($store)
	{
		return $this->addStoreFilter($store);
	}

	/**
	 * Add filter by store
	 *
	 * @param int|Mage_Core_Model_Store $store
	 * @param bool $withAdmin
	 * @return Mage_Cms_Model_Resource_Page_Collection
	*/
	public function addStoreFilter($store, $withAdmin = true)
	{
		if (!$this->getFlag('store_filter_added')) {
			if ($store instanceof Mage_Core_Model_Store) {
				$store = array($store->getId());
			}
	
			if (!is_array($store)) {
				$store = array($store);
			}
	
			if ($withAdmin) {
				$store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
			}
	
			$this->addFilter('store', array('in' => $store), 'public');
		}

		return $this;
	}

	/**
	 * Join store relation table if there is store filter
	 *
	 * @return $this
	*/
	protected function _renderFiltersBefore()
	{
		if ($this->getFilter('store')) {
			$idFieldName = $this->getResource()->getIdFieldName();
			
			$this->getSelect()->join(
				array('store_table' => $this->getResource()->getStoreTable()),
				'main_table.' . $idFieldName . ' = store_table.' . $idFieldName,
				array()
			)->group('main_table.' . $idFieldName);

			$filter = $this->getFilter('store');
		}


		return parent::_renderFiltersBefore();
	}
	
	protected function _afterLoad()
	{
		$uniques = array();
		$uniqueField = $this->getResource()->getUniqueFieldName();
		
		foreach($this->getItems() as $key => $item) {
			if ($value = $item->getData($uniqueField)) {
				if (!isset($uniques[$value])) {
					$uniques[$value] = $item;
				}
				else {
					if ($uniques[$value]->isGlobal()) {
						$this->removeItemByKey($uniques[$value]->getId());
						$uniques[$value] = $item;
					}
					else if ($item->isGlobal()) {
						$this->removeItemByKey($item->getId());
					}
				}
			}
		}
		
		return parent::_afterLoad();
	}
	
	/**
	 * Filter the collection by attribute Code
	 *
	 * @param string $attributeCode
	 * @return $this
	 */
	public function addAttributeCodeFilter($attributeCode)
	{
		return $this->addFieldToFilter('attribute_code', $attributeCode);
	}
	
	/**
	 * Filter the collection by attribute ID
	 *
	 * @param int $attributeId
	 * @return $this
	 */
	public function addAttributeIdFilter($attributeId)
	{
		return $this->addFieldToFilter('attribute_id', $attributeId);
	}
	
	/**
	 * Filter the collection so only enabled pages are returned
	 *
	 * @param int $value = 1
	 * @return $this
	 */
	public function addIsEnabledFilter($value = 1)
	{
		return $this->addFieldToFilter('is_enabled', $value);
	}
}