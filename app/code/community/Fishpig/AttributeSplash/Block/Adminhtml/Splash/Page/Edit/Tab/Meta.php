<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
	
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_meta', array('legend'=> $this->__('Page Title &amp; Meta Information')));

		$fieldset->addField('page_title', 'text', array(
			'name' => 'page_title',
			'label' => $this->__('Page Title'),
			'title' => $this->__('Page Title'),
		));

		$fieldset->addField('meta_description', 'editor', array(
			'name' => 'meta_description',
			'label' => $this->__('Meta Description'),
			'title' => $this->__('Meta Description'),
		));
		
		$fieldset->addField('meta_keywords', 'editor', array(
			'name' => 'meta_keywords',
			'label' => $this->__('Meta Keywords'),
			'title' => $this->__('Meta Keywords'),
		));

		if ($optionExtra = Mage::registry('splash_option_extra')) {
			$form->setValues($optionExtra->getData());
		}

		return parent::_prepareForm();
	}
}
