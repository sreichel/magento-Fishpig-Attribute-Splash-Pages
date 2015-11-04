<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_ViewController extends Fishpig_AttributeSplash_Controller_Abstract
{
	/**
	 * Initialise and save the Splash page model to the registry
	 *
	 * @return Fishpig_AttributeSplash_Model_Splash
	 */
	protected function _initSplashPage()
	{
		if ($splashId = $this->getRequest()->getParam('id')) {
			$splash = Mage::getModel('attributeSplash/splash')->load($splashId);
			
			if ($splash->getId() && $splash->getStatus() == 1) {
				Mage::register('splash_page', $splash, true);
				return $splash;
			}
		}
		
		return false;
	}
	
	/**
	 * Default action that displays a splash page
	 *
	 */
	public function indexAction()
	{
		if ($splash = $this->_initSplashPage()) {
			$this->loadLayout();
			$this	->_setTemplateByConfigKey('attributeSplash/frontend/template');
			$this->_addBreadcrumbs($splash);
			$this->_setCurrentCategory();
			$this->_injectNavigation();
			$this->renderLayout();
		}
		else {
			$this->_forward('noRoute');
		}
	}
	
	/**
	 * Custom loadLayout() allows the injection of the custom layout update XML
	 *
	 */
	public function loadLayout()
	{
		$update = $this->getLayout()->getUpdate();
		$update->addHandle('default');
		
		$this->addActionLayoutHandles();
		$this->loadLayoutUpdates();
		
		$update->addUpdate($this->_initSplashPage()->getLayoutUpdateXml());
		
		$this->generateLayoutXml()->generateLayoutBlocks();
		
		return $this;
	}
	
	/**
	 * Injects the navigation into the page
	 * The navigation should be selected in the module config and NOT via XML
	 */
	protected function _injectNavigation()
	{
		$navOption = (int) Mage::getStoreConfig('attributeSplash/frontend/navigation');

		if ($navOption == Fishpig_AttributeSplash_Model_System_Config_Source_Navigation::USE_LAYERED_NAVIGATION) {
			$blockType = 'attributeSplash/layer_view';
			$defaultTemplate = 'catalog/layer/view.phtml';
		}
		elseif ($navOption == Fishpig_AttributeSplash_Model_System_Config_Source_Navigation::USE_CATALOG_NAVIGATION) {
			$blockType = 'catalog/navigation';
			$defaultTemplate = 'catalog/navigation/left.phtml';
		}
		else {
			return $this;
		}

		if ($navContainer = $this->_getNavigationContainer()) {
			if ($navContainerBlock = $this->getLayout()->getBlock($navContainer)) {
				$template = ($configTemplate = Mage::getStoreConfig('attributeSplash/frontend/navigation_template')) ? $configTemplate : $defaultTemplate;
				$block = $this->getLayout()->createBlock($blockType, 'catalog.leftnav', array('template' => $template, 'before' => '-'))->setTemplate($template);

				if ($block) {
					$navContainerBlock->insert($block);
				}
			}
		}
		
		return $this;
	}

	/**
	 * Gets the block reference name for the navigation
	 * Uses the following hierarchy:
	 * Custom block name (config), auto detect from template code, 'left'
	 *
	 * @return string
	 */
	protected function _getNavigationContainer()
	{
		if ($container = trim(Mage::getStoreConfig('attributeSplash/frontend/navigation_container'))) {
			return $container;
		}
		
		$template = Mage::getStoreConfig('attributeSplash/frontend/template');
		
		foreach(array('left', 'right') as $side) {
			if (strpos($template, $side) !== false) {
				return $side;
			}
		}
		
		return 'left';
	}
	
	/**
	 * Sets the current category as the current stores default category
	 */
	protected function _setCurrentCategory()
	{
		if (!Mage::registry('current_category')) {
			Mage::register('current_category', $this->_getStoresDefaultCategory(), true);
		}
		
		return $this;
	}

	/**
	 * Retrieves the store's default category
	 *
	 * @return false|Mage_Catalog_Model_Category
	 */
	protected function _getStoresDefaultCategory()
	{
		$category = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId());
		
		if ($category->getId()) {
			if ($splash = Mage::registry('splash_page')) {
				$category->setName($splash->getDisplayName());
			}
			
			return $category;
		}
		
		return false;
	}
}
