<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_ListController extends Fishpig_AttributeSplash_Controller_Abstract
{
	/**
	 * Initialise and save the Splash page model to the registry
	 *
	 * @return Fishpig_AttributeSplash_Model_Splash
	 */
	protected function _initAttribute()
	{
		if ($this->isEnabled() && ($attributeId = $this->getRequest()->getParam('id'))) {
			$attribute = Mage::getModel('attributeSplash/attribute')->load($attributeId);
			
			if ($attribute->getId()) {
				Mage::register('splash_attribute', $attribute, true);
				return $attribute;
			}
		}
		
		return false;
	}

	public function indexAction()
	{
		if ($attribute = $this->_initAttribute()) {
			$this->loadLayout()
				->_setTemplateByConfigKey('attributeSplash/list_page/template')
				->_addBreadcrumbs($attribute)
				->renderLayout();
		}
		else {
			$this->_forward('noRoute');
		}
	}


	
	/*
	 * Returns true if list splash pages is enabled in the config
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return Mage::getStoreConfigFlag('attributeSplash/list_page/enabled');
	}
}