<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
implements Mage_Adminhtml_Block_Widget_Tab_Interface 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splashPageProductGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('desc');
		$this->setSaveParametersInSession(false);
		$this->setUseAjax(true);
	}
	
	protected function _prepareCollection()
	{
		if ($this->getStoreId()) {
			$this->getSplashPage()->setStoreId($this->getStoreId());
		}
		
		$collection = $this->getSplashPage()->getProductCollection()
			->addAttributeToSelect(array('name'));

		if ($this->getStoreId()) {
			$collection->setStoreId($this->getStoreId());
		}
		
		$this->setCollection($collection);
	
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('entity_id', array(
			'header'=> Mage::helper('catalog')->__('ID'),
			'width' => '50px',
			'type'  => 'number',
			'index' => 'entity_id',
		));
		
		$this->addColumn('name', array(
			'header'=> Mage::helper('catalog')->__('Name'),
			'index' => 'name',
		));
	
		return parent::_prepareColumns();
	}
	
	/**
	 * Retrieve the current splash page
	 *
	 * @return Fishpig_AttributeSplash_Model_Page
	 */
	public function getSplashPage()
	{
		return Mage::registry('splash_page');
	}
	
	/**
	 * Retrieve the class name of the tab
	 *
	 * return string
	 */
	public function getTabClass()
	{
		return 'ajax';
	}

	/**
	 * Determine whether to show the tab
	 *
	 * @return true
	 */
	public function canShowTab()
	{
		return true;
	}
	
	/**
	 * Retrieve the URL used to load the tab content via AJAX
	 *
	 * @return string
	 */
	public function getTabUrl()
	{
		return $this->getUrl('splash_admin/adminhtml_page/productGrid', array('id' => $this->getSplashPage()->getId(), 'store_id' => $this->getStoreId()));
	}
	
	/**
	 * Retrieve the URL used to load the tab content via AJAX
	 *
	 * @return string
	 */
	public function getGridUrl()
	{
		return $this->getUrl('splash_admin/adminhtml_page/productGrid', array('id' => $this->getSplashPage()->getId(), 'store_id' => $this->getStoreId()));
	}
	
	/**
	 * Determine whether the tab is hidden
	 *
	 * @return false
	 */
	public function isHidden()
	{
		return false;
	}
	
	/**
	 * Determine whether to generate content on load or via AJAX
	 *
	 * @return bool
	 */
	public function getSkipGenerateContent()
	{
		return true;
	}
	
	/**
	  * Retrieve the tab label
	  *
	  * @return string
	  */
	public function getTabLabel()
	{
		if ($this->hasStoreLabel()) {
			return $this->__('Products (%s)', $this->getStoreLabel());
		}
		
		return $this->__('Products');
	}
	
	/**
	  * Retrieve the tab title
	  *
	  * @return string
	  */
	public function getTabTitle()
	{
		return $this->getTabLabel();
	}
	
}
