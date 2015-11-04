<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_New_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle('Splash Page Information');
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('settings',
			array(
				'label' => $this->__('Settings'),
				'title' => $this->__('Settings'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_new_tab_form')->toHtml(),
			)
		);
		
		return parent::_beforeToHtml();
	}
}
