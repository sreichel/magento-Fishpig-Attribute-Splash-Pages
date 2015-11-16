<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Addon_QuickCreate_Block_Adminhtml_Create extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setFormActionUrl($this->getUrl('*/*/quick'));
		
		$this->_controller = 'adminhtml_page';
		$this->_blockGroup = 'attributeSplash';
		$this->_headerText = Mage::helper('attributeSplash')->__('Quick Create');
		
		$this->_removeButton('back');
		$this->_removeButton('reset');
		$this->_removeButton('save');
		
		$this->_addButton('create', array(
			'label' => $this->__('Create'),
			'class' => 'add',
			'onclick' => "editForm.submit();",
		));
	}

	protected function _prepareLayout()
	{
		$this->setChild('form',
			$this->getLayout()->createBlock('attributeSplash_addon_quickcreate/adminhtml_create_form')
		);
		
		return $this;
	}
}
