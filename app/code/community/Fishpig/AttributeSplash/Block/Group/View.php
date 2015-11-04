<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Group_View extends Mage_Core_Block_Template
{
	protected $_splashPages = null;
	
	/**
	 * Retrieve the splash group
	 *
	 * @return Fishpig_AttributeSplash_Model_Group
	 */
	public function getSplashGroup()
	{
		if (!$this->hasSplashGroup()) {
			$this->setSplashGroup(false);
			
			if ($this->hasSplashGroupId()) {
				$splashGroup = Mage::getModel('attributeSplash/group')->load($this->getSplashGroupId());

				if ($splashGroup->getId()) {
					$this->setSplashGroup($splashGroup);
				}
			}
			else {
				$this->setSplashGroup(Mage::registry('splash_group'));
			}
		}
		
		return $this->_getData('splash_group');
	}
	
	public function getSplashPages()
	{
		if (is_null($this->_splashPages)) {
			$this->_splashPages = $this->getSplashGroup()->getSplashPages();
		}
		
		return $this->_splashPages;
	}
	
	/**
	 * Adds the META information to the resulting page
	 */
	protected function _prepareLayout()
	{
		parent::_prepareLayout();

		if ($layoutCode = Mage::getStoreConfig('attributeSplash/list_page/template')) {
			if ($templateData = Mage::getSingleton('page/config')->getPageLayout($layoutCode)) {
				if (isset($templateData['template'])) {
					$this->getLayout()->getBlock('root')->setTemplate($templateData['template']);
				}		
			}
		}

        return $this;
    }
	
	/**
	 * Determine whether to use the list display mode
	 *
	 * @return bool
	 */
	public function isListMode()
	{
		return $this->getMode() == Fishpig_AttributeSplash_Model_Page::ATTRIBUTE_MODE_LIST;
	}
	
	/**
	 * Determine whether to use the grid display mode
	 *
	 * @return bool
	 */
	public function isGridMode()
	{
		return $this->getMode() == Fishpig_AttributeSplash_Model_Page::ATTRIBUTE_MODE_GRID;	
	}
	
	/**
	 * Determine whether to use the simple display mode
	 *
	 * @return bool
	 */
	public function isSimpleMode()
	{
		return $this->getMode() == Fishpig_AttributeSplash_Model_Page::ATTRIBUTE_MODE_SIMPLE;	
	}
	
	/**
	 * Get the user-defined display mode
	 *
	 * @return int
	 */
	public function getMode()
	{
		return Mage::getStoreConfig('attributeSplash/list_page/display_mode');
	}
	
	/**
	 * Retrieve the amount of columns for grid view
	 *
	 * @return int
	 */
	public function getColumnCount()
	{
		return $this->hasColumnCount() ? $this->getData('column_count') : Mage::getStoreConfig('attributeSplash/list_page/grid_column_count');
	}
	
    /**
     * Retrives the HTML for the pager
     *
     * @return string
     */
    public function getPagerHtml()
    {
    	if (!$this->hasPagerHtml()) {
	    	if ($block = $this->getPagerBlock()) {
	    		$limit = $this->getLimit();

    			$block->setDefaultLimit($limit);
    			$block->setLimit($limit);
    			$block->setAvailableLimit(array($limit => $limit));	    		

    			$block->setCollection($this->getSplashPages());


				$this->setPagerHtml($block->toHtml());
			}
    	}
    	
    	return $this->_getData('pager_html');
    }
    
	/**
	 * Retrieves the amount of items to display on one page
	 *
	 * @return int
	 */
	protected function getLimit()
	{
		if (!$this->hasLimit()) {
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
		
		return (int)$this->getData('limit');
	}
    
    /**
     * Returns the block for the pager
     * If no block is set via the XML, one will NOT be created
     *
     * @return false|Mage_Page_Block_Html_Pager
     */
    public function getPagerBlock()
    {
    	if ($blockName = $this->getPagerBlockName()) {
	    	return $this->getChild($blockName);
	    }
	    
	    return false;
    }
}
