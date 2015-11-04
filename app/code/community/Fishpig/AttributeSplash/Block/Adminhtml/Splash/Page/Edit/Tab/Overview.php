<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit_Tab_Overview extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
	
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_overview', array('legend'=> $this->__('Attribute &amp; Option Information')));

		$fieldset->addField('store_id', 'hidden', array(
			'name' => 'store_id',
		));
		
		if (!Mage::app()->isSingleStoreMode()) {
			$fieldset->addField('store_name', 'text', array(
				'name'      => 'store_name',
				'label'     => $this->__('Store'),
				'title'     => $this->__('Store'),
				'required'  => true,
				'disabled' => true,
			));
		}

		$fieldset->addField('attribute_code', 'text', array(
			'name' => 'attribute_code',
			'label' => $this->__('Attribute Code'),
			'title' => $this->__('Attribute Code'),
			'disabled' => true,
		));
		
		$fieldset->addField('attribute_name', 'text', array(
			'name' => 'attribute_name',
			'label' => $this->__('Attribute Name'),
			'title' => $this->__('Attribute Name'),
			'disabled' => true,
		));
		
		$fieldset->addField('option_value', 'text', array(
			'name' => 'option_value',
			'label' => $this->__('Option Value'),
			'title' => $this->__('Option Value'),
			'disabled' => true,
		));
		
		$fieldset->addField('option_value_hidden', 'hidden', array(
			'name' => 'option_value_hidden',
			'title' => $this->__('Option Value'),
		));
		
		$fieldset->addField('option_id', 'hidden', array(
			'name' => 'option_id',
			'title' => $this->__('Option ID'),
		));

		if ($optionExtra = Mage::registry('splash_option_extra')) {
			$optionExtra->setOptionValueHidden($optionExtra->getOptionValue());
			$form->setValues($optionExtra->getData());
		}

		return parent::_prepareForm();
	}
}
