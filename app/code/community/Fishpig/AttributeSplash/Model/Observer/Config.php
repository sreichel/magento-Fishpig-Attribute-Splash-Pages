<?php

class Fishpig_AttributeSplash_Model_Observer_Config
{
	/**
	 * Updates the ReWrites with the new URL suffix
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function checkRewrites(Varien_Event_Observer $observer)
	{
		try {
			$this->_checkRewrites();
		}
		catch (Exception $e) {
			Mage::log($e->getMessage(), null, 'attributeSplash.log');
		}
	}
	
	protected function _checkRewrites()
	{
		if ($post = Mage::app()->getRequest()->getPost()) {
			if (isset($post['groups']['seo']['fields']['url_suffix']['value'])) {
			
				$helper = Mage::helper('attributeSplash/rewrite');
				$urlSuffix = trim($post['groups']['seo']['fields']['url_suffix']['value']);

				$select = $this->getReadAdapter()
					->select()
					->from($this->getResource()->getTableName('core/url_rewrite'), array('url_rewrite_id', 'request_path', 'description'))
					->where('target_path LIKE ?', "splash/%");

				if ($results = $this->getReadAdapter()->fetchAll($select)) {
					foreach($results as $result) {
						$data = new Varien_Object(unserialize($result['description']));

						if (!$data->getResource()) {
							$data->setResource('attributeSplash/attribute_option_extra');
						}
						
						$object = Mage::getModel($data->getResource())->setUrlKey($data->getUrlKey());
						$requestPath = $helper->getRequestPath($object);

						if ($requestPath != $result['request_path']) {
							$this->getWriteAdapter()
								->update(
									$this->getResource()->getTableName('core/url_rewrite'),
									array('request_path'=>$requestPath, 'description'=>$helper->getRewriteDescription($object)),
									$this->getWriteAdapter()->quoteInto('url_rewrite_id=?', $result['url_rewrite_id'])
								);
						}
					}
				}
			}
		}
	}

	/**
	 * Retrieves the core resource object
	 */
	protected function getResource()
	{
		return Mage::getSingleton('core/resource');
	}
	
	/**
	 * Shortcut for the read adapter
	 */
	protected function getReadAdapter()
	{
		return $this->getResource()->getConnection('core_read');
	}

	/**
	 * Shortcut for the write adapter
	 */	
	protected function getWriteAdapter()
	{
		return $this->getResource()->getConnection('core_write');
	}
}
