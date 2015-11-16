<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Addon_QuickCreate_Block_Adminhtml_Create_Form extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Generate the form object
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		$module = 'quickcreate';
		
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getUrl('*/*/addon', array('module' => $module)),
			'method' => 'post',
		));
		
		$form->setUseContainer(true);
        $form->setHtmlIdPrefix('qc_');
        $form->setFieldNameSuffix($module);
 
 
		$this->setForm($form);
			
		$fieldset = $this->getForm()->addFieldset('qc', array(
			'legend'=> $this->helper('adminhtml')->__('Quick Create'),
			'class' => 'fieldset-wide',
		));

		$fieldset->addField('attribute_id', 'select', array(
			'name' => 'attribute_id',
			'label' => $this->__('Attribute'),
			'title' => $this->__('Attribute'),
			'values' => Mage::getSingleton('attributeSplash/system_config_source_attribute_splashable')->toOptionArray(true),
			'required' => true,
		));

		if (!Mage::app()->isSingleStoreMode()) {
			$field = $fieldset->addField('store_id', 'select', array(
				'name' => 'store_id',
				'label' => Mage::helper('cms')->__('Store View'),
				'title' => Mage::helper('cms')->__('Store View'),
				'required' => true,
				'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
			));

			$renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
			
			if ($renderer) {
				$field->setRenderer($renderer);
			}
		}
		else {
			$fieldset->addField('store_id', 'hidden', array(
				'name' => 'store_id',
				'value' => Mage::app()->getStore(true)->getId(),
			));
		}
		
		return parent::_prepareForm();
	}

}
