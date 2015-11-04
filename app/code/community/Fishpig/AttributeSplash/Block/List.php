<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_List extends Mage_Core_Block_Template
{
	/**
	 * Adds the META information to the resulting page
	 */
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		
		if ($headBlock = $this->getLayout()->getBlock('head')) {
			$attribute = $this->getAttribute();

			if ($title = $attribute->getDisplayName()) {
				$headBlock->setTitle($title);
			}

			if (Mage::getStoreConfig('attributeSplash/seo/use_canonical')) {
				$headBlock->addLinkRel('canonical', $attribute->getUrl());
			}
		}
		
		if (!$this->hasData('column_count') && $this->isGridMode()) {
			$this->setColumnCount(Mage::getStoreConfig('attributeSplash/list_page/grid_column_count'));
		}

        return $this;
    }
    
    /**
     * Retrieves a collection of  splash pages associated with the blocks attribute
     */
    public function getSplashPages()
    {
    	if (!$this->hasData('splash_pages')) {
	    	if ($attribute = $this->getAttribute()) {
	    		$this->setData('splash_pages', $attribute->getSplashPages());
	    	}
    	}
    	
    	return $this->getData('splash_pages');
    }
    
    /**
     * Retrives the HTML for the pager
     *
     * @return string
     */
    public function getPagerHtml()
    {
    	if ($block = $this->getPagerBlock()) {
    		$block->setAvailableLimit(array($this->getLimit() => $this->getLimit()));
    		$block->setLimit($this->getLimit());
    		return $block->setCollection($this->getSplashPages())->toHtml();
    	}
    }
    
    /**
     * Returns the block for the pager
     * If no block is set via the XML, one will NOT be created
     *
     * @return null|Mage_Page_Block_Html_Pager
     */
    public function getPagerBlock()
    {
    	if (!$this->hasData('pager_block')) {
	    	if (!$this->hasData('pager_block_name')) {
    			$this->setPagerBlockName('pager');
    		}

	  		$this->setData('pager_block', $this->getChild($this->getData('pager_block_name')));
    	}

		return $this->getData('pager_block');
    }

    /**
     * Retrieves the current Attribute model
     *
     * @return Fishpig_AttributeSplash_Model_Attribute|null
     */
	public function getAttribute()
	{
		if (!$this->hasData('attribute')) {
			if ($this->hasData('attribute_id')) {
				$this->setData('attribute', Mage::getModel('attributeSplash/attribute')->load($this->getAttributeId()));
			}
			else {
				$this->setData('attribute', Mage::registry('splash_attribute'));
			}
		}
		
		return $this->getData('attribute');
	}

	
	public function isListMode()
	{
		return $this->getMode() == Fishpig_AttributeSplash_Model_Splash::ATTRIBUTE_MODE_LIST;
	}
	
	public function isGridMode()
	{
		return $this->getMode() == Fishpig_AttributeSplash_Model_Splash::ATTRIBUTE_MODE_GRID;	
	}
	
	public function isSimpleMode()
	{
		return $this->getMode() == Fishpig_AttributeSplash_Model_Splash::ATTRIBUTE_MODE_SIMPLE;	
	}
	
	public function getMode()
	{
		return Mage::getStoreConfig('attributeSplash/list_page/display_mode');
	}
	
	public function isEnabled()
	{
		return Mage::getStoreConfig('attributeSplash/list_page/enabled');
	}
	
	/**
	 * Retrieves the amount of items to display on one page
	 *
	 * @return int
	 */
	protected function getLimit()
	{
		if (!$this->hasData('limit')) {
			if ($this->isListMode()) {
				$key = 'list';
			}
			elseif ($this->isGridMode()) {
				$key ='grid';
			}
			else {
				$key = 'simple';
			}
			
			$this->setLimit(Mage::getStoreConfig('attributeSplash/list_page/' . $key . '_per_page'));
		}
		
		return $this->getData('limit');
	}

}
