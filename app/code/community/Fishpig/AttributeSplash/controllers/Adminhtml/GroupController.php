<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Adminhtml_GroupController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Display a grid of splash groups
	 *
	 */
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('attributeSplash');
		$this->renderLayout();
	}
	
	/**
	 * Display the grid of splash groups without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function gridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_group_grid')->toHtml()
			);
	}
	
	/**
	 * Display the add/edit form for the splash group
	 *
	 */
	public function editAction()
	{
		$splash = $this->_initSplashGroup();
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
	 * Save the posted data
	 *
	 */
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost('splash')) {
			$group = Mage::getModel('attributeSplash/group')
				->setData($data)
				->setId($this->getRequest()->getParam('id'));
				
			try {
				$group->save();
				$this->_getSession()->addSuccess($this->__('Splash group was saved'));
			}
			catch (Exception $e) {
				$this->_getSession()->addError($this->__($e->getMessage()));
			}
				
			if ($group->getId() && $this->getRequest()->getParam('back', false)) {
				$this->_redirect('*/*/edit', array('id' => $group->getId()));
				return;
			}
		}
		else {
			$this->_getSession()->addError($this->__('There was no data to save.'));
		}

		$this->_redirect('*/*');
	}

	/**
	 * Initialise the splash group model
	 *
	 * @return false|Fishpig_AttributeSplash_Model_Group
	 */
	protected function _initSplashGroup()
	{
		if ($id = $this->getRequest()->getParam('id')) {
			$group = Mage::getModel('attributeSplash/group')->load($id);
			
			if ($group->getId()) {
				Mage::register('splash_group', $group);
				return $group;
			}
		}
		
		return false;
	}
	
	/**
	 * Thank you Michael!
	 *
	 */
	public function deleteAction($id)
	{
		if ($id = $this->getRequest()->getParam('id')) {
			try {
				Mage::getModel('attributeSplash/group')
					->setId($id)
					->delete();

				$this->_getSession()->addSuccess($this->__('Splash group was deleted'));
			}
			catch (Exception $e) {
				$this->_getSession()->addError($this->__($e->getMessage()));
			}

			$this->_redirect('*/*');
		}

		$this->_redirect('*/*');
	}
}
