<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('splashGrid');
//		$this->setDefaultSort('store_name');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(false);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('attributeSplash/attribute_option_extra_collection')
			->addAttributeOptionInfo()
			->addStoreName();

		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('entity_id', array(
			'header'    => 'ID',
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'entity_id',
		));
	
	
		$this->addColumn('store_id', array(
			'header'    => $this->__('Store'),
			'align'     =>'left',
			'width' => '120px',
			'index'     => 'store_id',
			'options' => $this->_getStoreOptions(),
            'sortable'  => false,
            'type'      => 'options',
		));
		
		$this->addColumn('frontend_label', array(
			'header'    => $this->__('Attribute'),
			'align'     =>'left',
			'width' => '180px',
			'index'     => 'frontend_label',
		));
		
		$this->addColumn('display_name', array(
			'header'    => $this->__('Display Name'),
			'align'     =>'left',
			'index'     => 'display_name',
		));
		
		$this->addColumn('status', array(
			'header'    => $this->__('Status'),
			'width'     => '90px',
			'index'     => 'status',
			'type'      => 'options',
			'options'    => array(
				0 => $this->__('Disabled'),
				1 => $this->__('Enabled'),
			),
		));
        
            $this->setColumnFilter('display_name');
            
		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	/**
	 * Returns an array of the available stores
	 *
	 * @return array
	 */
	protected function _getStoreOptions()
	{
		$stores = Mage::getResourceModel('core/store_collection')->toOptionHash();
		array_unshift($stores, $this->__('All Stores'));
		return $stores;
		$stores = array('value' => 0, 'label' => $this->__('All Stores'));
		return array_merge(array($stores), Mage::getModel('adminhtml/system_config_source_store')->toOptionArray());
	}
	
	/**
	 * Applies the column filter for the store ID
	 *
	 */
	protected function _addColumnFilterToCollection($column)
	{
		if ($this->getCollection()) {
			if ($column->getId() == 'store_id') {
				$this->getCollection()->getSelect()->where("main_table.store_id=?", $column->getFilter()->getValue());
				return $this;
			}
		}

		return parent::_addColumnFilterToCollection($column);
	}
}
