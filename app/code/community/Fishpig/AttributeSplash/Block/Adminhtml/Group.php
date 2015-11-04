<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Group extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->addButton('test', array(
			'label'		=> $this->__('Manage Splash Pages'),
			'onclick'	=> "setLocation('" .  $this->getUrl('*/adminhtml_page') . "');",
		));

		parent::__construct();
		
		$this->_controller = 'adminhtml_group';
		$this->_blockGroup = 'attributeSplash';
		$this->_headerText = $this->__('Attribute Splash Page Groups');
		
		$this->_removeButton('add');
	}
}