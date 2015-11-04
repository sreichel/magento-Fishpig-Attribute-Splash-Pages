<?php

class Fishpig_AttributeSplash_Adminhtml_Splash_PageController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('catalog/products');
		$this->renderLayout();
	}
	
	public function newAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('catalog/products');
		$this->renderLayout();
	}
	
	public function createAction()
	{
		$storeId = $this->getRequest()->getPost('store_id');
		if (!is_null($storeId) && ($optionId = $this->getRequest()->getPost('option_id'))
				&& ($optionValue = $this->getRequest()->getPost('option_value'))) {
		
			$optionExtra = Mage::getModel('attributeSplash/attribute_option_extra')
				->setDisplayName($optionValue)
				->setOptionId($optionId)
				->setStoreId($storeId);
				
			try {
				$optionExtra->save();
				$this->_redirect('*/*/edit/', array('id' => $optionExtra->getId()));
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/new/');
			}
		}
		else {
			Mage::getSingleton('adminhtml/session')->addError($this->__('There was an error creating your splash page'));
			$this->_redirect('*/*/new/');
		}
	}
	
	public function editAction()
	{
		if ($optionExtra = $this->_initOptionExtra()) {
			$optionExtra	->addAttributeInfo()
				->addAttributeOptionInfo()
				->addImageNewForUploads();
				
			Mage::register('splash_option_extra', $optionExtra);
			
			$this->loadLayout();
			$this->_setActiveMenu('catalog/products');
			$this->renderLayout();
		}
		else {
			Mage::getSingleton('adminhtml/session')->addError($this->__('The requested Splash page cannot be loaded'));
			$this->_redirect('*/*/');
		}
	}
	
	public function saveAction()
	{
		if (($post = $this->getRequest()->getPost()) && ($id = $this->getRequest()->getParam('id'))) {
			foreach(array('image_new' => 'image', 'thumbnail_new' => 'thumbnail') as $newImageKey => $imageKey) {
				if (isset($post[$newImageKey]['delete']) && $post[$newImageKey]['delete'] == 1) {
					$post[$imageKey] = '';
					unset($post[$newImageKey]);
				}
				else {
					if ($uploadResult = $this->_uploadImage($newImageKey)) {
						$post[$imageKey] = $uploadResult;
					}
				}
			}

			$optionExtra = Mage::getModel('attributeSplash/attribute_option_extra')
				->setId($id)->setData($post)->setEntityId($id);

			try {
			
				$optionExtra->save();
				
				Mage::dispatchEvent('attributeSplash_option_extra_save_after', array('option_extra' => $optionExtra, 'action' => $this));
				
				Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Splash page was saved successfully'));
			}
			catch (Exception $e) {
				Mage::logException($e);
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}

			$this->_redirect('*/*/edit/', array('id' => $id));		
			return;
		}
		
		Mage::getSingleton('adminhtml/session')->addError($this->__('The requested Splash page cannot be loaded'));
		$this->_redirect('*/*');
	}
	
	protected function _uploadImage($imageKey)
	{
		if(isset($_FILES[$imageKey]['name']) && file_exists($_FILES[$imageKey]['tmp_name'])) {
			try {
				$uploader = new Varien_File_Uploader($imageKey);
				$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(false);
				
				$uploadResult = $uploader->save(Mage::getBaseDir('media') . DS . 'splash' . DS, $_FILES[$imageKey]['name']);
				
				return (isset($uploadResult['file'])) ? $uploadResult['file'] : $_FILES[$imageKey]['name'];
			}
			catch (Exception $e) {exit($e);
				Mage::logException($e);
			}
		}
		
		return null;
	}
	
	protected function _initOptionExtra()
	{
		if ($optionExtraId = $this->getRequest()->getParam('id')) {
			if ($optionExtra = Mage::getModel('attributeSplash/attribute_option_extra')->load($optionExtraId)) {
				return ($optionExtra->getId()) ? $optionExtra : false;
			}
		}
		
		return false;
	}
	
	public function deleteAction()
	{
		if ($id = $this->getRequest()->getParam('id')) {
			try {
				Mage::getModel('attributeSplash/attribute_option_extra')
					->load($id)->delete();
				
				Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The Splash Page has been deleted'));
			}
			catch (Exception $e) {
				Mage::logException($e);
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}

		}
	
		$this->_redirect('*/*');	
	}
}
