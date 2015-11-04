<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Addons extends Mage_Adminhtml_Block_Template
{
	/**
	 * Cache for ads
	 *
	 * @var array
	 */
	protected $_addons = null;
	
	/**
	 * Determine whether ads are available
	 *
	 * @return bool
	 */
	public function hasAvailableAddons()
	{
		return !is_null($this->_addons);
	}
	
	/**
	 * Retrieve the available ads
	 *
	 * @return false|array
	 */
	public function getAvailableAddons()
	{
		return $this->hasAvailableAddons()
			? $this->_addons
			: false;
	}
	
	/**
	 * Prepare the ads
	 *
	 * @return $this
	 */
	protected function _beforeToHtml()
	{
		$this->_prepareAddons();
		
		$this->setTemplate('attribute-splash/addons.phtml');

		return parent::_beforeToHtml();
	}
	
	/**
	 * Prepare the ads
	 *
	 * @return $this
	 */
	protected function _prepareAddons()
	{		
		$addons = array();
		$config = (array)Mage::app()->getConfig()->getNode('attributeSplash/ads');

		foreach($config as $code => $ad) {
			if (isset($ad->is_multistore) && Mage::app()->isSingleStoreMode) {
				continue;
			}
			else if (isset($ad->is_singlestore) && !Mage::app()->isSingleStoreMode) {
				continue;
			}

			$addon = new Varien_Object((array)$ad);

			$addon->setTrackedUrl(
				sprintf('%s?utm_source=mage-admin&utm_medium=addon&utm_term=%s&utm_campaign=attributeSplash', $addon->getUrl(), $code)
			);
			
			$addon->setImage($this->getSkinUrl($addon->getImage()));
			
			$addons[$code] = $addon;
		}
		
		if (count($addons) > 0) {
			$this->_addons = $addons;
		}
		
		return $this;
	}
}
