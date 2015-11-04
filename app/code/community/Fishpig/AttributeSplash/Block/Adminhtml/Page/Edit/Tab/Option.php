<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Option extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('splash_');
        $form->setFieldNameSuffix('splash');
        
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_option', array('legend'=> $this->__('Attribute, Option &amp; Store Information')));

		$fieldset->addField('frontend_label', 'text', array(
			'name' 		=> 'frontend_label',
			'label' 	=> $this->__('Attribute'),
			'title' 	=> $this->__('Attribute'),
			'disabled'	=> true,
		));
		
		$fieldset->addField('option_value', 'text', array(
			'name' 		=> 'option_value',
			'label' 	=> $this->__('Option'),
			'title' 	=> $this->__('Option'),
			'disabled'	=> true,
		));

		$fieldset->addField('option_id', 'hidden', array(
			'name'		=> 'option_id',
			'title'		=> $this->__('Option ID'),
		));
		
		$fieldset->addField('store_name', 'text', array(
			'name' 		=> 'store_name',
			'label' 	=> $this->__('Store'),
			'title' 	=> $this->__('Store'),
			'disabled'	=> true,
		));
		
		$fieldset->addField('store_id', 'hidden', array(
			'name'		=> 'store_id',
			'title'		=> $this->__('Store ID'),
		));
		
		$form->setValues($this->_getCombinedFormData());


		return parent::_prepareForm();
	}
	
	/**
	 * Retrieve the form data used to populate the form
	 *
	 * @return array
	 */
	protected function _getCombinedFormData()
	{
		return array_merge($this->_getAttributeData(), $this->_getOptionData(), $this->_getStoreData());
	}
	
	/**
	 * Retrieve the attribute data associated with the page
	 * If creating a page, load the attribute form the ID in the URL
	 *
	 * @return array
	 */
	protected function _getAttributeData()
	{
		if ($attributeModel = Mage::registry('splash_page_attribute')) {
			return $attributeModel->getData();
		}
		
		return array();
	}

	/**
	 * Retrieve the option data associated with the page
	 * If creating a page, load the option form the ID in the URL
	 *
	 * @return array
	 */	
	protected function _getOptionData()
	{
		if ($optionModel = Mage::registry('splash_page_option')) {
			return array(
				'option_id' => $optionModel->getId(),
				'option_value' => $optionModel->getStoreDefaultValue() ? $optionModel->getStoreDefaultValue() : $optionModel->getValue()
			);
		}
		
		return array();
	}
	
	protected function _getStoreData()
	{
		$storeModel = null;
		
		if ($page = Mage::registry('splash_page')) {
			$storeModel = $page->getStore();
		}
		elseif ($storeId = $this->getRequest()->getParam('store_id')) {
			$storeModel = Mage::getModel('core/store')->load($storeId);
		}
		else {
			$storeModel = Mage::app()->getStore();
		}
	
		if (!is_null($storeModel) && $storeModel->getId() !== false) {
			return array(
				'store_id' => $storeModel->getId(),
				'store_name' => $storeModel->getName() == 'Admin' ? $this->__('Global') : $storeModel->getName(),
			);
		}

		return array();
	}
}
