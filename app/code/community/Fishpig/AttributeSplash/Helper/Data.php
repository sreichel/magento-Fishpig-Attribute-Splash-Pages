<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Helper_Data extends Fishpig_AttributeSplash_Helper_Abstract
{
	/**
	 * Returns a full URL for a splash page
	 *
	 * @param Mage_Core_Model_Abstract|string $splash
	 * @return string
	 */
	public function getSplashUrl($splash)
	{
		$urlKey = $splash;
		
		if ($splash instanceof Mage_Core_Model_Abstract) {
			$urlKey = $splash->getUrlKey();
		}
		
		if ($urlKey) {
			return Mage::getBaseUrl('web', false) . $urlKey . $this->getUrlSuffix();
		}
	}
}
