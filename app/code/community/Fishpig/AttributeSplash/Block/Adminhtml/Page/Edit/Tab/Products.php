<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splashPageProductGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('desc');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
//		$this->setVarNameFilter('product_filter');
	}
	
	protected function _prepareCollection()
	{
		$collection = $this->getSplashPage()->getProductCollection()
			->addAttributeToSelect(array('name'));

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
	
	public function getSplashPage()
	{
		return Mage::registry('splash_page');
	}
}
