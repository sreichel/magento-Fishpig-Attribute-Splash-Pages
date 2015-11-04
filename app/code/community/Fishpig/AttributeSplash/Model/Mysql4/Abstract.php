<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

abstract class Fishpig_AttributeSplash_Model_Mysql4_Abstract extends Mage_Core_Model_Mysql4_Abstract
{
	/**
	 * Retrieve the URL for the splash object
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return string
	 */
	public function getRequestPath(Mage_Core_Model_Abstract $object)
	{
		$urlRewrite = Mage::getModel('core/url_rewrite')->loadByIdPath($this->getIdPath($object));
		
		if ($urlRewrite->getId()) {
			return $urlRewrite->getRequestPath();
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
				throw new Exception(Mage::helper('attributeSplash')->__('Object must have a name'));
			}
			else {
				$object->setDisplayName($object->getFrontendLabel());
			}
		}
		
		if (!$object->getUrlKey()) {
			$object->setUrlKey($object->getname());
		}
		
		$object->setUrlKey($this->formatUrlKey($object->getUrlKey()));
		
		$object->setUpdatedAt(now());
		
		if (!$object->getCreatedAt()) {
			$object->setCreatedAt(now());
		}
		
		return parent::_beforeSave($object);
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
	 * Update the URL rewrite for the splash object
	 * This generates a URL rewrite using the value in the URL Key field
	 *
	 * @param Mage_Core_Model_Abstract $object
	 */
	public function updateUrlRewrite(Mage_Core_Model_Abstract $object)
	{
		if ($object->getId()) {
			if ($object->getStoreId() == '0') {
				if ($storeIds = $this->getAllStoreIds()) {
					foreach($storeIds as $storeId) {
						$this->_updateUrlRewrite($object, $storeId);			
					}
				}
			
			}
			else {
				$this->_updateUrlRewrite($object, $object->getStoreId());
			}
		}
	}
	
	/**
	 * Update the URL rewrite for the splash object
	 * This generates a URL rewrite using the value in the URL Key field
	 *
	 * @param Mage_Core_Model_Abstract $object
	 */
	protected function _updateUrlRewrite(Mage_Core_Model_Abstract $object, $storeId)
	{
		if ($requestPath = $this->getValidRequestPath($object, $storeId)) {
			$table = $this->getTable('core/url_rewrite');
	
			$select = $this->_getReadAdapter()
				->select()
				->from($table, 'url_rewrite_id')
				->where('id_path=?', $this->getIdPath($object))
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
							'id_path' => $this->getIdPath($object),
							'request_path' => $requestPath,
							'target_path' => $this->getTargetPath($object),
							'is_system' => 1,
						)
					);
			}
		}
	}
	
	/**
	 * Retrieve a valid request path for the object
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @param int $storeId
	 * @return null|string
	 */
	public function getValidRequestPath(Mage_Core_Model_Abstract $object, $storeId)
	{
		$urlKey = $object->getUrlKey();
		
		
		if ($object instanceof Fishpig_AttributeSplash_Model_Page) {
			if (Mage::getStoreConfigFlag('attributeSplash/frontend/include_group_url_key', $storeId)) {
				if ($object->getSplashGroup()) {
					$urlKey = ltrim($object->getSplashGroup()->getUrlKey() . '/' . $urlKey, '/');
				}
			}
		}
		
		$postFix = trim(Mage::getStoreConfig('attributeSplash/seo/url_suffix', $object->getStoreId()));

		if ($this->_requestPathIsValid($urlKey . $postFix, $this->getIdPath($object), $storeId)) {
			return $urlKey . $postFix;
		}
		
		$it = 0;
		while(++$it && $it <= 20) {
			$requestPath = $urlKey . '-' . $it . $postFix;
			
			if ($this->_requestPathIsValid($requestPath, $this->getIdPath($object), $storeId)) {
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
	 * Format a string to a valid URL key
	 * Allow a-zA-Z0-9, hyphen and /
	 *
	 * @param string $str
	 * @return string
	 */
	public function formatUrlKey($str)
	{
		$urlKey = str_replace("'", '', $str);
		$urlKey = preg_replace('#[^0-9a-z\/]+#i', '-', Mage::helper('catalog/product_url')->format($urlKey));
		$urlKey = strtolower($urlKey);
		$urlKey = trim($urlKey, '-');
		
		return $urlKey;
	}
}
