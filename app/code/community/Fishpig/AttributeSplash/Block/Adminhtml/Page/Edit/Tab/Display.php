<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tab_Display extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('splash_');
        $form->setFieldNameSuffix('splash');
	
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

		if ($splashPage = Mage::registry('splash_page')) {
			$form->setValues($splashPage->getData());
		}

		return parent::_prepareForm();
	}

}
