<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Create_Options extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splash_options_grid');
//		$this->setDefaultSort('frontend_label');
//		$this->setDefaultDir('asc');
		$this->setSaveParametersInSession(false);
		$this->setUseAjax(true);

		if (!Mage::app()->isSingleStoreMode()) {
		

			$this->setRowClickCallback('getStoreChooserGrid');
		}
	}
	
	/**
	 * Initialise and set the collection for the grid
	 *
	 */
	protected function _prepareCollection()
	{
		$collection = $this->helper('attributeSplash')
			->getOptionCollectionByAttributeId($this->getRequest()->getParam('attribute_id'));

		$this->setCollection($collection);

		return parent::_prepareCollection();
	}
	
	/**
	 * Add the columns to the grid
	 *
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('option_id', array(
			'header'		=> $this->__('ID'),
			'align'			=> 'left',
			'width'			=> '60px',
			'index'			=> 'option_id',
		));
	
		$this->addColumn('default_value', array(
			'header'		=> $this->__('Default Value'),
			'align'			=> 'left',
			'index'			=> 'default_value',
			'filter_index'	=> 'tdv.value',
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
		return $this->getUrl('*/*/optionGrid', array('attribute_id' => $this->getRequest()->getParam('attribute_id')));
	}
	
	/**
	 * Retrieve the URL for each row
	 * The URL is used to request the options grid via AJAX
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		if (!Mage::app()->isSingleStoreMode()) {
			return $this->getUrl('*/*/storeGrid', array('attribute_id' => $this->getRequest()->getParam('attribute_id'), 'option_id' => $row->getId()));
		}
		
		return $this->getUrl('*/*/new', array('option_id' => $row->getId(), 'attribute_id' => $this->getRequest()->getParam('attribute_id'), 'store_id' => 0));
	}
}
