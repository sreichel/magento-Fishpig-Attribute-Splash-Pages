<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Addon_XmlSitemap_Block_Sitemap extends Mage_Core_Block_Text
{
	/**
	 * Set the XML content as the block's text
	 *
	 * @return $this
	 */
	protected function _beforeToHtml()
	{
		if (($xmlSitemap = $this->getXmlSitemap()) === false) {
			throw new Exception('No Splash pages or groups to create an XML sitemap for.');
		}
		
		$this->setText($xmlSitemap);

		return parent::_beforeToHtml();
	}
	
	/**
	 * Generate the XML sitemap content
	 *
	 * @return string
	 */
	public function getXmlSitemap()
	{
		$headers = array(
			'<?xml version="1.0" encoding="UTF-8"?>',
			'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
		);
		
		$footers = array(
			'</urlset>',
		);
		
		$methods = array(
			'page' => '_getSplashPages',
			'group' => '_getSplashGroups',
		);
		
		$items = array();

		foreach($methods as $type => $method) {
			if (($objects = $this->$method()) !== false) {

				$changeFreq = Mage::getStoreConfig('attributeSplash/' . $type . '_xml_sitemap/frequency');
				$priority = (float)Mage::getStoreConfig('attributeSplash/' . $type . '_xml_sitemap/priority');
				
				foreach($objects as $object) {
					$items[] = sprintf('<url><loc>%s</loc><changefreq>%s</changefreq><priority>%s</priority></url>', $object->getUrl(), $changeFreq, $priority);
				}
			}
		}
		
		if (count($items) > 0) {
			return implode("\n", $headers) . "\n"
				. implode("\n", $items) . "\n"
				. implode("\n", $footers);
		}
		
		return false;
	}
	
	/**
	 * Retrieve a collection of Splash Pages
	 *
	 * @return Fishpig_AttributeSplash_Model_Resource_Page_Collection
	 */
	protected function _getSplashPages()
	{
		if (!Mage::getStoreConfigFlag('attributeSplash/page_xml_sitemap/enabled')) {
			return false;
		}

		$pages = Mage::getResourceModel('attributeSplash/page_collection')
			->addStoreIdFilter(Mage::app()->getStore())
			->addIsEnabledFilter()
			->load();

		if (count($pages) === 0) {
			return false;
		}
		
		return $pages;
	}

	/**
	 * Retrieve a collection of Splash Groups
	 *
	 * @return Fishpig_AttributeSplash_Model_Resource_Group_Collection
	 */
	protected function _getSplashGroups()
	{
		if (!Mage::getStoreConfigFlag('attributeSplash/group_xml_sitemap/enabled')) {
			return false;
		}

		$pages = Mage::getResourceModel('attributeSplash/group_collection')
			->addStoreIdFilter(Mage::app()->getStore())
			->addIsEnabledFilter()
			->load();

		if (count($pages) === 0) {
			return false;
		}
		
		return $pages;
	}
}
