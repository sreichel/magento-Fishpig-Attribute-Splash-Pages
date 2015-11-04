<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit_Tab_Images extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
	
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_images', array('legend'=> $this->__('Images')));

		$fieldset->addField('image_new', 'image', array(
			'label' => $this->__('Image'),
			'title' => $this->__('Image'),
			'name' => 'image_new',
			'required' => false,
		));
		
		$fieldset->addField('thumbnail_new', 'image', array(
			'label' => $this->__('Thumbnail'),
			'title' => $this->__('Thumbnail'),
			'name' => 'thumbnail_new',
			'required' => false,
		));


		if ($optionExtra = Mage::registry('splash_option_extra')) {
			$form->setValues($optionExtra->getData());
		}

		return parent::_prepareForm();
	}
}
