<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_View extends Mage_Core_Block_Template
{
	/**
	 * Adds the META information to the resulting page
	 */
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		
		if ($headBlock = $this->getLayout()->getBlock('head')) {
			$splash = $this->getSplash();

			if ($title = $splash->getPageTitle()) {
				$headBlock->setTitle($title);
			}
			else {
				$headBlock->setTitle($splash->getDisplayName());
			}

			if ($description = $splash->getMetaDescription()) {
				$headBlock->setDescription($description);
			}
			
			if ($keywords = $splash->getMetaKeywords()) {
				$headBlock->setKeywords($keywords);
			}
			
			if (Mage::getStoreConfig('attributeSplash/seo/use_canonical')) {
				$headBlock->addLinkRel('canonical', $splash->getUrl());
			}
		}

        return $this;
    }
    
    /**
     * Retrieves the current Splash model
     *
     * @return Fishpig_AttributeSplash_Model_Splash|null
     */
	public function getSplash()
	{
		if (!$this->hasData('splash')) {
			if ($this->hasData('splash_id')) {
				$this->setData('splash', Mage::getModel('attributeSplash/splash')->load($this->getSplashId()));
			}
			else {
				$this->setData('splash', Mage::registry('splash_page'));
			}
		}
		
		return $this->getData('splash');
	}

	/**
	 * Check if category display mode is "Products Only"
	 * @return bool
	*/
	public function isProductMode()
	{
		return $this->getSplash()->getDisplayMode()==Mage_Catalog_Model_Category::DM_PRODUCT;
	}
	
	/**
	 * Check if category display mode is "Static Block and Products"
	 * @return bool
	*/
	public function isMixedMode()
	{
		return $this->getSplash()->getDisplayMode()==Mage_Catalog_Model_Category::DM_MIXED;
	}

	public function isContentMode()
	{
		return $this->getSplash()->getDisplayMode()==Mage_Catalog_Model_Category::DM_PAGE;
	}
	
	/**
	 * Retrieves and renders the product list block
	 *
	 * @return string
	 */
	public function getProductListHtml()
	{
		return $this->getProductListBlock()->toHtml();
		//$this->getChildHtml('product_list');
	}
	
	public function getProductListBlock()
	{
		if ($block = $this->getChild('product_list')) {
			if (!$block->hasData('column_count')) {
				$block->setColumnCount(Mage::getStoreConfig('attributeSplash/frontend/grid_column_count'));
			}
			
			return $block;
		}
		
		return false;
	}
	
	/**
	 * Retrieves the HTML for the CMS block
	 *
	 * @return string
	 */
	public function getCmsBlockHtml()
	{
		if (!$this->getData('cms_block_html')) {
			$html = $this->getLayout()->createBlock('cms/block')
				->setBlockId($this->getSplash()->getCmsBlock())->toHtml();
			$this->setData('cms_block_html', $html);
		}
		
		return $this->getData('cms_block_html');
	}
}
