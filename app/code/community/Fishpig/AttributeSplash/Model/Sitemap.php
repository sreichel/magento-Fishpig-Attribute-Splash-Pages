<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Sitemap extends Mage_Sitemap_Model_Sitemap
{
	/**
	 * Generate the normal sitemap and then add the splash pages/groups
	 *
	 * @return $this
	 */
	public function generateXml()
	{
		parent::generateXml();
		
		if (!Mage::getStoreConfig('attributeSplash/sitemap/enabled')) {
			return $this;
		}

		if (is_file($this->getPreparedFilename()) && is_writeable($this->getPreparedFilename())) {
			$xml = file_get_contents($this->getPreparedFilename());
			
			if (strpos($xml, '</urlset>') !== false) {
				$xml = substr($xml, 0, strpos($xml, '</urlset>'));
				
				$splashPages = $this->_getSplashPages();
				
				if (count($splashPages) > 0) {
					foreach($splashPages as $page) {
						$xml .= sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
							htmlspecialchars($page->getUrl()), $page->getUpdatedAt(false), $this->_getPageChangeFrequency(), $this->_getPagePriority());
					}
				}
				
				if (Mage::helper('attributeSplash')->splashGroupPagesEnabled()) {
					$splashGroups = $this->_getSplashGroups();
					
					if (count($splashGroups) > 0) {
						foreach($splashGroups as $group) {
							if ($group->canDisplay()) {
								$xml .= sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
											htmlspecialchars($group->getUrl()), $group->getUpdatedAt(false), $this->_getGroupChangeFrequency(), $this->_getGroupPriority());
							}
						}
					}
				}
			}
			
			$xml .= '</urlset>';

			$this->_saveSitemapContent($xml);
		}
		
		return $this;
	}

	/**
	 * Save the sitemap content
	 *
	 * @param string $xml
	 * @return bool
	 */
	protected function _saveSitemapContent($xml)
	{
		$f = fopen($this->getPreparedFilename(), 'w');
		
		if ($f) {
			fwrite($f, $xml);
			fclose($f);
		
			return true;
		}
		
		return false;
	}
	
	/**
	 * Retrieve the page change frequency
	 *
	 * @return string
	 */	
	protected function _getPageChangeFrequency()
	{
		return Mage::getStoreConfig('attributeSplash/sitemap/page_change_frequency');
	}
	
	/**
	 * Retrieve the group change frequency
	 *
	 * @return string
	 */
	protected function _getGroupChangeFrequency()
	{
		return Mage::getStoreConfig('attributeSplash/sitemap/group_change_frequency');
	}
	
	/**
	 * Retrieve the page priority
	 *
	 * @return float
	 */	
	protected function _getPagePriority()
	{
		return Mage::getStoreConfig('attributeSplash/sitemap/page_priority');
	}
	
	/**
	 * Retrieve the group priority
	 *
	 * @return float
	 */
	protected function _getGroupPriority()
	{
		return Mage::getStoreConfig('attributeSplash/sitemap/group_priority');
	}
	
	/**
	 * Retrieve a collection of splash pages for the sitemap
	 *
	 * @return Fishpig_AttributeSplash_Model_Mysl4_Page_Collection
	 */
	protected function _getSplashPages()
	{
		$pages = Mage::getResourceModel('attributeSplash/page_collection')
			->addIsEnabledFilter()
			->addStoreIdFilter($this->getStoreId())
			->load();
	
		return $pages;
	}
	
	/**
	 * Retrieve a collection of splash groups for the sitemap
	 *
	 * @return Fishpig_AttributeSplash_Model_Mysl4_Page_Collection
	 */
	protected function _getSplashGroups()
	{
		$pages = Mage::getResourceModel('attributeSplash/group_collection')
			->addIsEnabledFilter()
			->load();
	
		return $pages;
	}
}
