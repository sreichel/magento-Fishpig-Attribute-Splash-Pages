<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Observer_Sitemap
{
	public function injectSplashPages(Varien_Event_Observer $observer)
	{
		$sitemap = $observer->getEvent()->getDataObject();
	
	
		echo '<pre>'; print_r($sitemap->getData()); echo '</pre>'; exit;
	
	
	}
}
