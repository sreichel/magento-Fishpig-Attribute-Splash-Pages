<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Create_Stores extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splash_stores_grid');
		$this->setSaveParametersInSession(false);
		$this->setUseAjax(true);
	}
	
	protected function _prepareLayout()
	{
		parent::_prepareLayout();

		$this->setChild('make_global_button',
			$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
					'label'     => Mage::helper('adminhtml')->__('Make Global'),
					'onclick'   => "setLocation('" . $this->getMakeGlobalUrl(). "')",
					'class'   => 'add'
				))
		);
		
		return $this;
	}
	
	public function getMainButtonsHtml()
	{
		$html = parent::getMainButtonsHtml();
		
		$html .= $this->getChildHtml('make_global_button');
		
		return $html;
		
	
	
	
	}
	
	public function getMakeGlobalUrl()
	{
		return $this->getUrl(
			'*/*/new',
			array(
				'attribute_id' => $this->getRequest()->getParam('attribute_id'), 
				'option_id' => $this->getRequest()->getParam('option_id'), 
				'store_id' => 0,
			)
		);
	}
	
	/**
	 * Initialise and set the collection for the grid
	 *
	 */
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('core/store_collection');
		
		$collection->getSelect()
			->join(
				array('_website_table' => $collection->getTable('core/website')),
				"_website_table.website_id = main_table.website_id",
				array('website_name' => 'name')
			);

		$this->setCollection($collection);
	
		return parent::_prepareCollection();
	}
	
	/**
	 * Add the columns to the grid
	 *
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('store_id', array(
			'header'		=> $this->__('ID'),
			'align'			=> 'left',
			'width'			=> '60px',
			'index'			=> 'store_id',
		));
	
		$this->addColumn('website_name', array(
			'header'		=> $this->__('Website'),
			'align'			=> 'left',
			'index'			=> 'website_name',
			'filter_index'	=> '_website_table.name',
		));
		
		$this->addColumn('name', array(
			'header'		=> $this->__('Store'),
			'align'			=> 'left',
			'index'			=> 'name',
			'filter_index' => 'main_table.name',
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
		return $this->getUrl(
			'*/*/storeGrid', 
			array(
				'attribute_id' => $this->getRequest()->getParam('attribute_id'),
				'option_id' => $this->getRequest()->getParam('option_id')
			)
		);
	}
	
	/**
	 * Retrieve the URL for each row
	 * The URL is used to request the options grid via AJAX
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/new', array(
			'attribute_id' => $this->getRequest()->getParam('attribute_id'), 
			'option_id' => $this->getRequest()->getParam('option_id'), 
			'store_id' => $row->getId()));
	}
}
