<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

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
			Mage::log($e->getMessage(), null, 'attributeSplash.log', true);
		}
	}
	
	protected function _checkRewrites()
	{
		if ($post = Mage::app()->getRequest()->getPost()) {
			if (isset($post['groups']['seo']['fields']['url_suffix']['value']) && isset($post['groups']['seo']['fields']['url_suffix_old']['value'])) {
				$oldUrlSuffix = $post['groups']['seo']['fields']['url_suffix_old']['value'];
				$newUrlSuffix = $post['groups']['seo']['fields']['url_suffix']['value'];
				
				if ($oldUrlSuffix != $newUrlSuffix) {
					Mage::getResourceModel('attributeSplash/page')->updateAllUrlRewrites();
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
