<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Retrieve Additional Element Types
	 *
	 * @return array
	*/
	protected function _getAdditionalElementTypes()
	{
		return array(
			'image' => Mage::getConfig()->getBlockClassName('attributeSplash/adminhtml_page_helper_image')
		);
	}
	
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('splash_');
        $form->setFieldNameSuffix('splash');
        
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_general', array('legend'=> $this->__('General Information')));

		$this->_addElementTypes($fieldset);
		
		$fieldset->addField('display_name', 'text', array(
			'name' 		=> 'display_name',
			'label' 	=> $this->__('Display Name'),
			'title' 	=> $this->__('Display Name'),
			'note' 		=> $this->__('If left empty, the option value will be used'),
			'required'	=> true,
			'class'		=> 'required-entry',
		));
		
		$fieldset->addField('image', 'image', array(
			'name' 	=> 'image',
			'label' => $this->__('Image'),
			'title' => $this->__('Image'),
			'note' 	=> $this->__('Upload a large image as this can be dynamically resized'),
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
		if ($page = Mage::registry('splash_page')) {
			return $page->getData();
		}
	
		if ($optionModel = Mage::registry('splash_page_option')) {
			return array('display_name' => $optionModel->getStoreValue());
		}
		
		return array();
	}
}
