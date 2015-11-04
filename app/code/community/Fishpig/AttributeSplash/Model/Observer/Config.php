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
			if ($this->_urlRewriteSettingHasChanged('seo', 'url_suffix') || $this->_urlRewriteSettingHasChanged('frontend', 'include_group_url_key')) {
				Mage::getResourceModel('attributeSplash/group')->updateAllUrlRewrites();
			}
		}
		catch (Exception $e) {
			Mage::helper('attributeSplash')->log($e->getMessage());
		}
	}

	/**
	 * Determine whether a config setting has changed
	 *
	 * @param string $group
	 * @param string $field
	 * @param string $fieldSuffix = '_old'
	 * @return bool
	 */
	protected function _urlRewriteSettingHasChanged($group, $field, $fieldSuffix = '_old')
	{
		if ($post = Mage::app()->getRequest()->getPost()) {
			if (isset($post['groups'][$group]['fields'][$field]['value']) && isset($post['groups'][$group]['fields'][$field . $fieldSuffix]['value'])) {
				return $post['groups'][$group]['fields'][$field . $fieldSuffix]['value'] != $post['groups'][$group]['fields'][$field]['value'];
			}
		}
		
		return false;
	}
}
