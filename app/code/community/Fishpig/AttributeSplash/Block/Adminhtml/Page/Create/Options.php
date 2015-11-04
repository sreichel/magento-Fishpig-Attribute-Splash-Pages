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
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
//		$this->setRowClickCallback('getAttributeValueGrid');
	}
	
	/**
	 * Initialise and set the collection for the grid
	 *
	 */
	protected function _prepareCollection()
	{
		$collection = $this->helper('attributeSplash')
			->getOptionCollectionByAttributeId($this->getRequest()->getParam('attribute_id'));
		
//		echo $collection->getSelect() . '<br/><br/>';
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
			'filter_index'	=> 'store_default_value.value',
		));
		
		$this->addColumn('store_value', array(
			'header'		=> $this->__('Default Value'),
			'align'			=> 'left',
			'index'			=> 'store_value',
			'filter_index'	=> 'store_value.value',
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
		return $this->getUrl('*/*/new', array('option_id' => $row->getId(), 'attribute_id' => $this->getRequest()->getParam('attribute_id')));
	}
	
	/**
	 * Add a custom filter for the in_product column
	 *
	 */
	 /*
	protected function _addColumnFilterToCollection($column)
	{
		echo '<pre>'; print_r($column->getData()); echo '</pre>'; exit;
		if ($column->getName() == 'store_value') {
			$this->getCollection()->addFieldToFilter('store_default_value.' . $column->getName(), $column->getValue());	
		}
	
		
		
		echo $this->getCollection()->getSelect();
		exit;

		
		return $this;
	}
*/	
}
