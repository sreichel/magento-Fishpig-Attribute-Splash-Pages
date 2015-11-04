<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
	
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_general', array('legend'=> $this->__('General Information')));

		$fieldset->addField('display_name', 'text', array(
			'name' => 'display_name',
			'label' => $this->__('Display Name'),
			'title' => $this->__('Display Name'),
		));
		
		$fieldset->addField('url_key', 'text', array(
			'name' => 'url_key',
			'label' => $this->__('URL Key'),
			'title' => $this->__('URL Key'),
		));
		
		$fieldset->addField('short_description', 'editor', array(
			'name' => 'short_description',
			'label' => $this->__('Short Description'),
			'title' => $this->__('Short Description'),
		));

		$fieldset->addField('description', 'editor', array(
			'name' => 'description',
			'label' => $this->__('Description'),
			'title' => $this->__('Description'),
			'style' => 'width:98%; height:300px;',
			'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('add_widgets' => false, 'add_variables' => false, 'add_image' => false))
		));

		$fieldset->addField('status', 'select', array(
			'name' => 'status',
			'title' => $this->__('Enabled'),
			'label' => $this->__('Enabled'),
			'required' => true,
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));
		
		if ($optionExtra = Mage::registry('splash_option_extra')) {
			$form->setValues($optionExtra->getData());
		}

		return parent::_prepareForm();
	}
}
