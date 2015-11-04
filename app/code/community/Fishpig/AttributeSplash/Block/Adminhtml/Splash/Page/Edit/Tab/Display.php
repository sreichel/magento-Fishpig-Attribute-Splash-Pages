<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit_Tab_Display extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
	
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_display', array('legend'=> $this->__('Display Settings')));

		$fieldset->addField('display_mode', 'select', array(
			'name' => 'display_mode',
			'label' => $this->__('Display Mode'),
			'title' => $this->__('Display Mode'),
			'values' => Mage::getModel('catalog/category_attribute_source_mode')->getAllOptions(),
		));
		
		$fieldset->addField('cms_block', 'select', array(
			'name' => 'cms_block',
			'label' => $this->__('CMS Block'),
			'title' => $this->__('CMS Block'),
			'values' => Mage::getModel('catalog/category_attribute_source_page')->getAllOptions(),
		));

		$fieldset->addField('layout_update_xml', 'editor', array(
			'name' => 'layout_update_xml',
			'label' => $this->__('Layout Update XML'),
			'title' => $this->__('Layout Update XML'),
			'style' => 'width:600px;',
		));

		if ($optionExtra = Mage::registry('splash_option_extra')) {
			$form->setValues($optionExtra->getData());
		}

		return parent::_prepareForm();
	}
}
