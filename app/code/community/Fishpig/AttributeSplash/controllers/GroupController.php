<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_GroupController extends Mage_Core_Controller_Front_Action
{
	public function viewAction()
	{
		if ($splashGroup = $this->_initSplashGroup()) {
			$this->_applyCustomViewLayout($splashGroup);

			if ($rootBlock = $this->getLayout()->getBlock('root')) {
				$rootBlock->addBodyClass('splash-group-' . $splashGroup->getId());
			}

			$this->renderLayout();
		}
		else {
			$this->_forward('noRoute');
		}
	}
	
	/**
	 * Apply custom layout handles to the splash page
	 *
	 * @param Fishpig_AttribtueSplash_Model_Page $splashPage
	 * @return Fishpig_AttribtueSplash_PageController
	 */
	protected function _applyCustomViewLayout(Fishpig_AttributeSplash_Model_Group $splashGroup)
	{
		$update = $this->getLayout()->getUpdate();
		
		$update->addHandle('default');
		$this->addActionLayoutHandles();
		$update->addHandle('attributesplash_group_view_' . $splashGroup->getId());
		$update->addHandle('attributesplash_group_view_' . $splashGroup->getAttributeCode());

		$this->loadLayoutUpdates();
		
		$update->addUpdate($splashGroup->getLayoutUpdateXml());

		$this->generateLayoutXml()->generateLayoutBlocks();

		$this->_isLayoutLoaded = true;

		return $this;	
	}

	/**
	 * Initialise the Splash Gourp model
	 *
	 * @return false|Fishpig_AttributeSplash_Model_Gourp
	 */
	protected function _initSplashGroup()
	{
		Mage::dispatchEvent('attributeSplash_controller_group_init_before', array('controller_action' => $this));
		
		$splashGroupId = (int) $this->getRequest()->getParam('id', false);

		if (!$splashGroupId) {
			return false;
		}
		
		$splashGroup = Mage::getModel('attributeSplash/group')->load($splashGroupId);
		
		if (!$splashGroup->canDisplay()) {
			return false;
		}
		
		Mage::register('splash_group', $splashGroup);
		
		try {
			Mage::dispatchEvent('attributeSplash_controller_group_init_after', array('group' => $splashGroup, 'splash_group' => $splashGroup, 'controller_action' => $this));		
		}
		catch(Mage_Core_Exception $e) {
			Mage::helper('attributeSplash')->log($e->getMessage());
			return false;
		}

		return $splashGroup;
	}
}
