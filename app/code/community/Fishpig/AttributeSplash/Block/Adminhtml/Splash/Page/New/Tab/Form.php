<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    	Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_New_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
	
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_form', array('legend'=> $this->__('Create Splash Page Settings')));
		
		if ($json = $this->_getAttributeJson()) {
			$fieldset->addField('splash_attribute_id', 'select', array(
				'label'     	=> $this->__('Attribute'),
				'class'     	=> 'required-entry',
				'required'  => true,
				'name'      => 'attribute_id',
				'values' => $this->_getAttributeOptions(),
			));
		
			$fieldset->addField('splash_option_id', 'select', array(
				'name'      => 'option_id',
				'label'     => $this->__('Attribute Option'),
				'title'     => $this->__('Attribute Option'),
				'required'  => true,
				'values' => array(),
			));
	
			$fieldset->addField('option_value', 'hidden', array(
				'name' => 'option_value',
				'title'     => $this->__('Option Value'),
				'value' => '',
			));
			
			if ($storeId = $this->_getSoleStoreId()) {
				$fieldset->addField('splash_store_id', 'hidden', array(
					'name' => 'store_id',
					'title'     => $this->__('Store'),
					'value' => $storeId,
				));
			}
			else {
				$fieldset->addField('splash_store_id', 'select', array(
					'name'      => 'store_id',
					'label'     => $this->__('Store'),
					'title'     => $this->__('Store'),
					'required'  => true,
					'values' => $this->_getStoreOptions(),
				));
			}
			
			$fieldset->addField('splash_continue', 'button',  array(
				'name' => 'continue',
				'label' => '&nbsp;',
				'value' => $this->__('Continue'),
				'class' => 'form-button scalable save',
			));
			
			$fieldset->addField('buttons', 'note', array(
				'text' => '<script type="text/javascript">var dJson = '.$json.';</script>',
			));
		}
		else {
			$fieldset->addField('no_splash_pages', 'note', array(
				'text' => "You have no splashable attributes. To start creating splash pages, create some splashable attributes and add option values to them."
			));		
		}
		
		return parent::_prepareForm();
	}
	
	/**
	 * Generates the JSON used to populate the dropdown boxes
	 *
	 * @return false|string - JSON encoded
	 */
	protected function _getAttributeJson()
	{
		$attributes = Mage::getResourceModel('attributeSplash/attribute_collection')->load();
		$json = array();
			
		foreach($attributes as $attribute) {
			
			if ($values = $attribute->getValues()) {
				
				$buffer = array();
				
				foreach($values as $value) {
					$buffer[$value->getId()] = $value->getValue();
				}
				
				if ($buffer) {
					$json[$attribute->getId()] = $buffer;
				}
			}
		}

		return count($json) > 0 ? json_encode($json) : false;
	}
	
	/**
	 * Retrieves an array of attribute option information
	 *
	 * @return array
	 */
	protected function _getAttributeOptions()
	{
		$attributes = Mage::getResourceModel('attributeSplash/attribute_collection')->load();
		$options = array();
		
		foreach($attributes as $attribute) {
			if ($attribute->getId() && $attribute->getAttributeCode()) {
				$options[] = array('value' => $attribute->getId(), 'label' => $attribute->getFrontendLabel());
			}
		}

		return $options;
	}

	/**
	 * Returns an array of the available stores
	 *
	 * @return array
	 */
	protected function _getStoreOptions()
	{
		$stores = array('value' => 0, 'label' => $this->__('All Stores'));
		return array_merge(array($stores), Mage::getModel('adminhtml/system_config_source_store')->toOptionArray());
	}
	
	/**
	 * Returns the ID of a store in single store mode
	 *
	 * @return int|false
	 */
	protected function _getSoleStoreId()
	{
		$stores = Mage::getResourceModel('core/store_collection')->load();
		return (count($stores) == 1) ? $stores->getFirstItem()->getId() : false;
	}
}
