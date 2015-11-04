<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splash_grid');
//		$this->setDefaultSort();
//		$this->setDefaultDir();
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	
	/**
	 * Initialise and set the collection for the grid
	 *
	 */
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('attributeSplash/page_collection');

		$this->setCollection($collection);
	
		return parent::_prepareCollection();
	}
	
	/**
	 * Add the columns to the grid
	 *
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('page_id', array(
			'header'	=> $this->__('ID'),
			'align'		=> 'left',
			'width'		=> '60px',
			'index'		=> 'page_id',
		));

		$this->addColumn('attribute_id', array(
			'header'		=> $this->__('Attribute'),
			'align'			=> 'left',
			'index'			=> 'attribute_id',
			'filter_index' 	=> '_attribute_table.attribute_id',
			'type'			=> 'options',
			'options' 		=> $this->getSplashedAttributes(),
		));
		
		$this->addColumn('display_name', array(
			'header'	=> $this->__('Name'),
			'align'		=> 'left',
			'index'		=> 'display_name',
		));
		
		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'	=> $this->__('Store'),
				'align'		=> 'left',
				'index'		=> 'store_id',
				'type'		=> 'options',
				'options' 	=> $this->getStores(),
			));
		}
		
		$this->addColumn('is_enabled', array(
			'header'	=> $this->__('Enabled'),
			'width'		=> '90px',
			'index'		=> 'is_enabled',
			'type'		=> 'options',
			'options'	=> array(
				1 => $this->__('Enabled'),
				0 => $this->__('Disabled'),
			),
		));
	
		$this->addColumn('action',
			array(
				'width'     => '50px',
				'type'      => 'action',
				'getter'     => 'getId',
				'actions'   => array(
					array(
						'caption' => Mage::helper('catalog')->__('Edit'),
						'url'     => array(
						'base'=>'*/*/edit',
					),
					'field'   => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'align' 	=> 'center',
			));

		return parent::_prepareColumns();
	}

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('page_id');
		$this->getMassactionBlock()->setFormFieldName('page');
	
		$this->getMassactionBlock()->addItem('delete', array(
			'label'=> $this->__('Delete'),
			'url'  => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('catalog')->__('Are you sure?')
		));
	}
	
	/**
	 * Retrieve the URL used to modify the grid via AJAX
	 *
	 * @return string
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid');
	}
	
	/**
	 * Retrieve the URL for the row
	 *
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	
	/**
	 * Retrieve an array of splashed attributes for the attribute filter
	 *
	 * @return array
	 */
	protected function getSplashedAttributes()
	{
		$attributes = $this->helper('attributeSplash')->getSplashedAttributeCollection();
		$options = array();

		foreach($attributes as $attribute) {
			$options[$attribute->getId()] = $attribute->getFrontendLabel();
		}

		return $options;
	}
	
	/**
	 * Retrieve an array of all of the stores
	 *
	 * @return array
	 */
	protected function getStores()
	{
		$stores = Mage::getResourceModel('core/store_collection');
		$options = array(0 => $this->__('Global'));
		
		foreach($stores as $store) {
			$options[$store->getId()] = $store->getWebsite()->getName() . ': ' . $store->getName();
		}

		return $options;
	}
}
