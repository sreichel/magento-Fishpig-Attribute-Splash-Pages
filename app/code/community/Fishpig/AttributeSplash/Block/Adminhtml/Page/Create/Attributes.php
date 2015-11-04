<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Create_Attributes extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splash_attributes_grid');
		$this->setDefaultSort('frontend_label');
		$this->setDefaultDir('asc');
		$this->setSaveParametersInSession(false);
		$this->setUseAjax(true);
		$this->setRowClickCallback('getAttributeValueGrid');
	}
	
	/**
	 * Initialise and set the collection for the grid
	 *
	 */
	protected function _prepareCollection()
	{
		$collection = $this->helper('attributeSplash')->getSplashableAttributeCollection();
		
		$this->setCollection($collection);
	
		return parent::_prepareCollection();
	}
	
	/**
	 * Add the columns to the grid
	 *
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('attribute_id', array(
			'header'	=> $this->__('ID'),
			'align'		=> 'left',
			'width'		=> '60px',
			'index'		=> 'attribute_id',
		));
	
		$this->addColumn('attribute_code', array(
			'header'	=> $this->__('Name'),
			'align'		=> 'left',
			'index'		=> 'attribute_code',
		));
		
		$this->addColumn('frontend_label', array(
			'header'	=> $this->__('Label'),
			'align'		=> 'left',
			'index'		=> 'frontend_label',
		));
	
		return parent::_prepareColumns();
	}
	
	/**
	 * Retrieve the URL used to modify the grid via AJAX
	 *
	 * @return string
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/*/attributeGrid');
	}
	
	/**
	 * Retrieve the URL for each row
	 * The URL is used to request the options grid via AJAX
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/optionGrid', array('attribute_id' => $row->getId()));
	}
}
