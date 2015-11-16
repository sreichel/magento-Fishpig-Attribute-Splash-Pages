<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Addon_XmlSitemap_Model_Observer
{
	/**
	 * Determine whether the current request is for the Splash Pages XML sitemap
	 *
	 * @param Varien_Event_Observer $observer
	 * @return $this
	 */
	public function matchXmlSitemapRouteObserver(Varien_Event_Observer $observer)
	{
		$requestUri = $observer->getEvent()->getRequestUri();
		$xmlSitemapFile = $this->_getXmlSitemapFile();
		
		if (!$xmlSitemapFile || $xmlSitemapFile !== $requestUri) {
			return $this;
		}

		$observer->getEvent()
			->getRouter()
				->getRequest()
					->setModuleName('splash')
					->setControllerName('sitemap')
					->setActionName('view');
		
		return $this;
	}
	
	/**
	 * Get the name of the XML Sitemap file
	 *
	 * @return string
	 */
	protected function _getXmlSitemapFile()
	{
		return trim(Mage::getStoreConfig('attributeSplash/seo/xml_sitemap_file'), '/');
	}
}
