<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Adminhtml_PageController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Display a grid of splash pages
	 *
	 */
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('attributeSplash');
		$this->renderLayout();
	}
	
	/**
	 * Display the grid of splash pages without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function gridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_page_grid')->toHtml()
			);
	}
	
	/**
	 * Display the attribute grid without the container
	 * This is useful for modifying the grid via AJAX
	 *
	 */
	public function attributeGridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_page_create_attributes')->toHtml()
			);
	}
	
	/**
	 * Display the option grid without the container
	 * This is useful for modifying the grid via AJAX
	 *
	 */
	public function optionGridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_page_create_options')->toHtml()
			);
	}
	
	/**
	 * Display the store grid without the container
	 * This is useful for modifying the grid via AJAX
	 *
	 */
	public function storeGridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_page_create_stores')->toHtml()
			);
	}

	/**
	 * Create a new splash page
	 *
	 */
	public function newAction()
	{
		$optionId = $this->getRequest()->getParam('option_id');
		$storeId = $this->getRequest()->getParam('store_id', 0);
		
		if (!Mage::helper('attributeSplash')->splashPageExists($optionId, $storeId)) {
			$this->_forward('edit');
		}
		else {
			if ($storeId > 0) {
				$this->_getSession()->addError($this->__('You have already created a global splash page for that attribute option. To create a store specific page, delete the global page and then create individual store pages'));	
			}
			else {
				$this->_getSession()->addError($this->__('A splash page exists for that option/store combination'));
			}

			$this->_redirect('*/*');
		}
	}
	
	
	/**
	 * Display the add/edit form for the splash page
	 *
	 */
	public function editAction()
	{
		$splash = $this->_initSplashPage();
		
		$this->_initAttribute();
		$this->_initOption();
		
		$this->loadLayout();
		$this->_setActiveMenu('attributeSplash');
		
		if ($splash) {
			if ($headBlock = $this->getLayout()->getBlock('head')) {
				$headBlock->setTitle($splash->getName());
			}	
		}
		
		$this->renderLayout();
	}

	/**
	 * Display the grid of splash pages without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function productGridAction()
	{
		$this->_initSplashPage();
		
		if ($storeId = $this->getRequest()->getParam('store_id', false)) {
			$store = Mage::getModel('core/store')->load($storeId);
			
			if ($store->getId()) {
				$blockHtml = $this->getLayout()->createBlock('attributeSplash/adminhtml_page_edit_tab_products')
					->setStoreId($store->getId())
					->setStoreLabel($store->getName())
					->toHtml();
	
				$this->getResponse()->setBody($blockHtml);
			}
		}
	}
	
	/**
	 * Save the posted data
	 *
	 */
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost('splash')) {
			$page = Mage::getModel('attributeSplash/page')
				->setData($data)
				->setId($this->getRequest()->getParam('id'));
				
			try {
				$this->_handleImageUpload($page, 'image');
				$this->_handleImageUpload($page, 'thumbnail');
				
				$page->save();
				$this->_getSession()->addSuccess($this->__('Splash page was saved'));
			}
			catch (Exception $e) {
				$this->_getSession()->addError($this->__($e->getMessage()));
			}
				
			if ($page->getId() && $this->getRequest()->getParam('back', false)) {
				$this->_redirect('*/*/edit', array('id' => $page->getId()));
				return;
			}
		}
		else {
			$this->_getSession()->addError($this->__('There was no data to save.'));
		}

		$this->_redirect('*/*');
	}
	
	/**
	 * Delete a splash page
	 *
	 */
	public function deleteAction()
	{
		if ($pageId = $this->getRequest()->getParam('id')) {
			$splashPage = Mage::getModel('attributeSplash/page')->load($pageId);
			
			if ($splashPage->getId()) {
				try {
					$splashPage->delete();
					$this->_getSession()->addSuccess($this->__('The Splash Page was deleted.'));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}
		
		$this->_redirect('*/*');
	}
	
	public function massDeleteAction()
	{
		$pageIds = $this->getRequest()->getParam('page');

		if (!is_array($pageIds)) {
			$this->_getSession()->addError($this->__('Please select page(s).'));
		}
		else {
			if (!empty($pageIds)) {
				try {
					foreach ($pageIds as $pageId) {
						$page = Mage::getSingleton('attributeSplash/page')->load($pageId);
	
						Mage::dispatchEvent('attributeSplash_controller_page_delete', array('splash_page' => $page, 'page' => $page));
	
						$page->delete();
					}
					
					$this->_getSession()->addSuccess($this->__('Total of %d record(s) have been deleted.', count($pageIds)));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}
		
		$this->_redirect('*/*/index');
	}

	/**
	 * Reindex all Splash URL's
	 *
	 */
	public function reindexAction()
	{
		try {
			Mage::getResourceModel('attributeSplash/group')->updateAllUrlRewrites();
			Mage::getSingleton('adminhtml/session')->addSuccess($this->__('%s index was rebuilt.', 'Splash URL Rewrites'));
		}
		catch (Exception $e) {
			Mage::helper('attributeSplash')->log($e->getMessage());
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
		$this->_redirect('*/*');
	}
	
	/**
	 * Initialise the splash page model
	 *
	 * @return false|Fishpig_AttributeSplash_Model_Page
	 */
	protected function _initSplashPage()
	{
		if ($id = $this->getRequest()->getParam('id')) {
			$page = Mage::getModel('attributeSplash/page')->load($id);
			
			if ($page->getId()) {
				Mage::register('splash_page', $page);
				return $page;
			}
		}
		
		return false;
	}
	
	protected function _initAttribute()
	{
		$attributeModel = null;
		
		if ($page = Mage::registry('splash_page')) {
			$attributeModel = $page->getAttributeModel();
		}
		elseif ($attributeId = $this->getRequest()->getParam('attribute_id')) {
			$attributeModel = Mage::getModel('eav/entity_attribute')->load($attributeId);
		}
		
		if (!is_null($attributeModel) && $attributeModel->getId()) {
			Mage::register('splash_page_attribute', $attributeModel);
			return true;
		}
		
		return false;
	}
	
	protected function _initOption()
	{
		$optionModel = null;
		
		if ($page = Mage::registry('splash_page')) {
			$optionModel = $page->getOptionModel();
		}
		elseif ($optionId = $this->getRequest()->getParam('option_id')) {
			$optionModel = Mage::helper('attributeSplash')->getOptionById($optionId);
		}
		
		if (!is_null($optionModel) && $optionModel->getId()) {
			Mage::register('splash_page_option', $optionModel);
			return true;
		}
		
		return false;
	}
	
	protected function _handleImageUpload(Fishpig_AttributeSplash_Model_Page $page, $field)
	{
		$data = $page->getData($field);

		if (isset($data['value'])) {
			$page->setData($field, $data['value']);
		}

		if (isset($data['delete']) && $data['delete'] == '1') {
			$page->setData($field, '');
		}

		if ($filename = Mage::helper('attributeSplash/image')->uploadImage($field)) {
			$page->setData($field, $filename);
		}
	}
}
