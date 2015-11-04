<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Splash Page Information'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('attribute',
			array(
				'label' => $this->__('Attribute, Option &amp; Store'),
				'title' => $this->__('Attribute, Option &amp; Store'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_edit_tab_overview')->toHtml(),
			)
		);
		
		$this->addTab('general',
			array(
				'label' => $this->__('General'),
				'title' => $this->__('General'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_edit_tab_general')->toHtml(),
			)
		);
		
		$this->addTab('images',
			array(
				'label' => $this->__('Images'),
				'title' => $this->__('Images'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_edit_tab_images')->toHtml(),
			)
		);
		
		$this->addTab('meta',
			array(
				'label' => $this->__('Page Title &amp; Meta'),
				'title' => $this->__('Page Title &amp; Meta'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_edit_tab_meta')->toHtml(),
			)
		);
		
		$this->addTab('display',
			array(
				'label' => $this->__('Display Settings'),
				'title' => $this->__('Display Settings'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_edit_tab_display')->toHtml(),
			)
		);
		
		$this->_activeTab = 'general';
		
		return parent::_beforeToHtml();
	}
}
