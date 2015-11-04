<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Mysql4_Page extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('attributeSplash/page', 'page_id');
	}

	/**
	 * Retrieve the attribute model for the page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	public function getAttributeModel(Fishpig_AttributeSplash_Model_Page $page)
	{
		if ($page->getOptionId()) {
			return Mage::helper('attributeSplash')->getAttributeByOptionId($page->getOptionId());
		}
		
		return false;
	}

	/**
	 * Retrieve the option model for the page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return Mage_Eav_Model_Entity_Attribute_Option
	 */
	public function getOptionModel(Fishpig_AttributeSplash_Model_Page $page)
	{
		return Mage::helper('attributeSplash')->getOptionById($page->getOptionId(), $page->getStoreId());
	}
	
	/**
	 * Retrieve the attribute ID for the splash page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return int
	 */
	public function getAttributeId(Fishpig_AttributeSplash_Model_Page $page)
	{
		if ($attributeModel = $page->getAttributeModel()) {
			return $attributeModel->getId();
		}
		
		return 0;
	}
	
	/**
	 * Retrieve the attribute code for the splash page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @return string
	 */
	public function getAttributeCode(Fishpig_AttributeSplash_Model_Page $page)
	{
		if ($attributeModel = $page->getAttributeModel()) {
			return $attributeModel->getAttributeCode();
		}
	}
	 
	/**
	 * Retrieve a collection of products associated with the splash page
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */	
	public function getProductCollection(Fishpig_AttributeSplash_Model_Page $page)
	{
		$collection = Mage::getResourceModel('catalog/product_collection')
			->setStoreId($page->getStoreId());

		/**
		 * Adds the splash page filter
		 * This uses the EAV index so ensure indexes are always up to date
		 */
		$alias = $page->getAttributeCode().'_idx';
		$read = Mage::getSingleton('core/resource')->getConnection('read');
		$storeId = ($page->getStoreId() == 0) ? Mage::app()->getStore()->getId() : $page->getStoreId();
		$collection->getSelect()
			->join(
				array($alias => $this->getTable('catalog/product_index_eav')),
				"`{$alias}`.`entity_id` = `e`.`entity_id`"
				. $read->quoteInto(" AND `{$alias}`.`attribute_id` = ? ", $page->getAttributeId())
				. $read->quoteInto(" AND `{$alias}`.`store_id` = ? ", $storeId)
				. $read->quoteInto(" AND `{$alias}`.`value` = ?", $page->getOptionId()),
				''
			);
			
		return $collection;
	}
	
	/**
	 * Retrieve the URL for the splash page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page
	 * @return string
	 */
	public function getUrl(Fishpig_AttributeSplash_Model_Page $page)
	{
		$urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath($this->getIdPath($page));
		
		if ($urlRewrite->getId()) {
			return Mage::getUrl('', array('_direct' => $urlRewrite->getRequestPath()));
		}
		
		return '';
	}
	
	/**
	 * Before/After Load/Save functions
	 *
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
		if (!$object->getDisplayName()) {
			if (!$object->getFrontendLabel()) {
				throw new Exception(Mage::helper('attributeSplash')->__('Splash page must have a name'));
			}
			else {
				$object->setDisplayName($object->getFrontendLabel());
			}
		}
		
		if (!$object->getUrlKey()) {
			$object->setUrlKey($object->getname());
		}
		
		$object->setUrlKey(Mage::getSingleton('catalog/product_url')->formatUrlKey($object->getUrlKey()));
		
		return parent::_beforeSave();
	}
	
	protected function _afterSave(Mage_Core_Model_Abstract $object)
	{	
		$this->updateUrlRewrite($object);
		
		return parent::_afterSave($object);
	}
	
	/**
	 * Delete the URL rewrite after object has been deleted
	 *
	 * @param Mage_Core_Model_Abstract $object
	 */
	protected function _afterDelete(Mage_Core_Model_Abstract $object)
	{
		$this->_getWriteAdapter()
			->delete(
				$this->getTable('core/url_rewrite'),
				$this->_getWriteAdapter()->quoteInto('id_path=?', $this->getIdPath($object))
			);
	
		return $this;
	}

	/**
	 * Update/refresh the rewrites for every splash page
	 *
	 */
	public function updateAllUrlRewrites()
	{
		$splashPages = Mage::getResourceModel('attributeSplash/page_collection');
		
		foreach($splashPages as $splashPage) {
			$this->updateUrlRewrite($splashPage);
		}	
	}
	
	/**
	 * Update the URL rewrite for the splash page
	 * This generates a URL rewrite using the value in the URL Key field
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 */
	public function updateUrlRewrite(Fishpig_AttributeSplash_Model_Page $page)
	{
		if ($page->getId()) {
			if ($page->getStoreId() == '0') {
				if ($storeIds = $this->getAllStoreIds()) {
					foreach($storeIds as $storeId) {
						$this->_updateUrlRewrite($page, $storeId);			
					}
				}
			
			}
			else {
				$this->_updateUrlRewrite($page, $page->getStoreId());
			}
		}
	}
	
	/**
	 * Update the URL rewrite for the splash page
	 * This generates a URL rewrite using the value in the URL Key field
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 */
	protected function _updateUrlRewrite(Fishpig_AttributeSplash_Model_Page $page, $storeId)
	{
		if ($requestPath = $this->getValidRequestPath($page, $storeId)) {
			$table = $this->getTable('core/url_rewrite');
	
			$select = $this->_getReadAdapter()
				->select()
				->from($table, 'url_rewrite_id')
				->where('id_path=?', $this->getIdPath($page))
				->where('store_id=?', $storeId)
				->limit(1);
				
			if ($urlRewriteId = $this->_getReadAdapter()->fetchOne($select)) {
				$this->_getWriteAdapter()
					->update(
						$table, 
						array('request_path' => $requestPath), 
						$this->_getWriteAdapter()->quoteInto('url_rewrite_id=?', $urlRewriteId)
					);
			}
			else {
				$this->_getWriteAdapter()
					->insert(
						$table,
						array(
							'store_id' => $storeId,
							'id_path' => $this->getIdPath($page),
							'request_path' => $requestPath,
							'target_path' => $this->getTargetPath($page),
							'is_system' => 1,
						)
					);
			}
		}
	}
	
	/**
	 * Retrieve a valid request path for the page
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @param int $storeId
	 * @return null|string
	 */
	public function getValidRequestPath(Fishpig_AttributeSplash_Model_Page $page, $storeId)
	{
		$urlKey = $page->getUrlKey();
		$postFix = trim(Mage::getStoreConfig('attributeSplash/seo/url_suffix', $page->getStoreId()));

		if ($this->_requestPathIsValid($urlKey . $postFix, $this->getIdPath($page), $storeId)) {
			return $urlKey . $postFix;
		}
		
		$it = 0;
		while(++$it && $it <= 20) {
			$requestPath = $urlKey . '-' . $it . $postFix;
			
			if ($this->_requestPathIsValid($requestPath, $this->getIdPath($page), $storeId)) {
				return $requestPath;
			}
		}

		return null;
	}
	
	/**
	 * Determine whether the request path is valid
	 * Valid if either request_path and id_path already exist together
	 * or if request_path is not used
	 *
	 * @param string $requestPath
	 * @param string $idPath
	 * @param int $storeId
	 * @return bool
	 */
	protected function _requestPathIsValid($requestPath, $idPath, $storeId)
	{
		$select = $this->_getReadAdapter()
			->select()
			->from($this->getTable('core/url_rewrite'), 'id_path')
			->where('request_path=?', $requestPath)
			->where('store_id=?', $storeId)
			->limit(1);

		if ($bIdPath = $this->_getReadAdapter()->fetchOne($select)) {
			return $idPath == $bIdPath;
		}

		return true;
	}
	
	/**
	 * Retrieve all non-admin store ID's
	 *
	 * @return array
	 */
	public function getAllStoreIds($adminStoreId = 0)
	{
		$select = $this->_getReadAdapter()
			->select()
			->from($this->getTable('core/store'), 'store_id')
			->where('store_id <> ?', $adminStoreId);
			
		return $this->_getReadAdapter()->fetchCol($select);
	}

	/**
	 * Retrieve the target path of the splash page
	 * This is used for the URL rewrite
	 *
	 * @return string|null
	 */	
	public function getTargetPath(Fishpig_AttributeSplash_Model_Page $page)
	{
		return $page->getId() ? 'splash/page/view/id/' . $page->getId() : null;
	}
	
	/**
	 * Retrieve the ID path of the splash page
	 * This is used for loading the URL rewrite
	 *
	 * @return string|null
	 */
	public function getIdPath(Fishpig_AttributeSplash_Model_Page $page)
	{
		return $page->getId() ? 'splash/page/' . $page->getId() : null;
	}
}
