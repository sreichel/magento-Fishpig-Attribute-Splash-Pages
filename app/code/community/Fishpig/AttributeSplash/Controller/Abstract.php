<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

abstract class Fishpig_AttributeSplash_Controller_Abstract extends Mage_Core_Controller_Front_Action
{
	/**
	 * Set the document template using by a layout code stored in a config key
	 *
	 * @param string $configkey
	 */
	protected function _setTemplateByConfigKey($configKey)
	{
		if ($layoutCode = Mage::getStoreConfig($configKey)) {
			if ($templateData = Mage::getSingleton('page/config')->getPageLayout($layoutCode)) {
				if (isset($templateData['template'])) {
					$this->getLayout()->getBlock('root')
						->setTemplate($templateData['template']);
				}		
			}
		}
		
		return $this;
	}
	
	/**
	 * Add breadcrumbs for the given entity
	 *
	 * @param Mage_Core_Model_Abstract $entity
	 */
	protected function _addBreadcrumbs($entity)
	{
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb('home', array('label' => $this->__('Home'), 'link' => Mage::getBaseUrl('web')))
				->addCrumb('splash', array('label' => $entity->getDisplayName(), 'link' => $entity->getUrl()));
		}
		
		return $this;
	}
}
