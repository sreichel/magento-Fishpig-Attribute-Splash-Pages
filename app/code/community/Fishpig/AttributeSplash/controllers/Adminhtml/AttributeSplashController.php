<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Adminhtml_AttributeSplashController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Display a grid of splash groups
	 *
	 */
	public function indexAction()
	{
		$this->loadLayout();
		
		$this->_title('FishPig');
		$this->_title($this->__('Attribute Splash'));

		$this->_setActiveMenu('attributeSplash');
		$this->renderLayout();
	}
	
	/**
	 * Display the grid of splash groups without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function groupGridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_group_grid')->toHtml()
			);
	}

	/**
	 * Display the grid of splash pages without the container (header, footer etc)
	 * This is used to modify the grid via AJAX
	 *
	 */
	public function pageGridAction()
	{
		$this->getResponse()
			->setBody(
				$this->getLayout()->createBlock('attributeSplash/adminhtml_page_grid')->toHtml()
			);
	}
	
	/**
	 * Display the Extend tab
	 *
	 * @return void
	 */
	public function extendAction()
	{
		$block = $this->getLayout()
			->createBlock('fpadmin/adminhtml_extend')
			->setModule('Fishpig_AttributeSplash')
			->setTemplate('large.phtml')
			->setLimit(4)
			->setPreferred(array('Fishpig_CrossLink', 'Fishpig_AttributeSplashPro', 'Fishpig_NoBots'));
			
		$this->getResponse()
			->setBody(
				$block->toHtml()
			);
	}
}
