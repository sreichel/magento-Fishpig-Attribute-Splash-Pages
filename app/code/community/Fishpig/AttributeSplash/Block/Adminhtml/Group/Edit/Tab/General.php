<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Group_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('splash_');
        $form->setFieldNameSuffix('splash');
        
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_general', array('legend'=> $this->__('General Information')));
		
		$fieldset->addField('store_id', 'hidden', array(
			'name'		=> 'store_id',
			'title'		=> $this->__('Store ID'),
		));
		
		$fieldset->addField('attribute_id', 'hidden', array(
			'name'		=> 'attribute_id',
			'title'		=> $this->__('Attribute ID'),
		));

		$fieldset->addField('display_name', 'text', array(
			'name' 		=> 'display_name',
			'label' 	=> $this->__('Display Name'),
			'title' 	=> $this->__('Display Name'),
			'note' 		=> $this->__('If left empty, the option value will be used'),
			'required'	=> true,
			'class'		=> 'required-entry',
		));
		
		$fieldset->addField('short_description', 'editor', array(
			'name' => 'short_description',
			'label' => $this->__('Short Description'),
			'title' => $this->__('Short Description'),
			'style' => 'width:98%; height:120px;',
		));

		$fieldset->addField('description', 'editor', array(
			'name' => 'description',
			'label' => $this->__('Description'),
			'title' => $this->__('Description'),
			'style' => 'width:98%; height:300px;',
			'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('add_widgets' => false, 'add_variables' => false, 'add_image' => false, 'files_browser_window_url' => $this->getUrl('adminhtml/cms_wysiwyg_images/index')))
		));

		$fieldset->addField('include_in_menu', 'select', array(
			'name' => 'include_in_menu',
			'title' => $this->__('Include In Menu'),
			'label' => $this->__('Include In Menu'),
			'required' => true,
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));
		
		$fieldset->addField('is_enabled', 'select', array(
			'name' => 'is_enabled',
			'title' => $this->__('Enabled'),
			'label' => $this->__('Enabled'),
			'required' => true,
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));

		$form->setValues($this->_getFormData());

		return parent::_prepareForm();
	}
	
	/**
	 * Retrieve the data used for the form
	 *
	 * @return array
	 */
	protected function _getFormData()
	{
		if ($group = Mage::registry('splash_group')) {
			return $group->getData();
		}
	
		if ($group = Mage::registry('splash_group')) {
			return array('display_name' => $group->AttributeModel()->getStoreValue());
		}
		
		return array();
	}
}
