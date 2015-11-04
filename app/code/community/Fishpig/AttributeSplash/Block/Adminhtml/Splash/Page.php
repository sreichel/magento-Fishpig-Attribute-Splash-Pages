<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{

		$this->_controller = 'adminhtml_splash_page';
		$this->_blockGroup = 'attributeSplash';
		$this->_headerText = $this->__('Attribute Splash Pages');
		$this->_addButtonLabel = $this->__('Create a new Splash Page');

		parent::__construct();
		
		$this->setTemplate('attribute-splash/grid/container.phtml');
	}

}
