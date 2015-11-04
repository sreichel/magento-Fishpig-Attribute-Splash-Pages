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
			if (Mage::app()->getRequest()->getPost('reindex_urls', false)) {
				Mage::getResourceModel('attributeSplash/group')->updateAllUrlRewrites();
			}
		}
		catch (Exception $e) {
			Mage::helper('attributeSplash')->log($e->getMessage());
		}
	}
}
