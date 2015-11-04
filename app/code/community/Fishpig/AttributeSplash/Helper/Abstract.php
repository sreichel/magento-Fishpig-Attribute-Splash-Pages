<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

abstract class Fishpig_AttributeSplash_Helper_Abstract extends Mage_Core_Helper_Abstract
{
	public function getUrlSuffix()
	{
		return Mage::getStoreConfig('attributeSplash/seo/url_suffix');
	}
}
