<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Splash Page Information'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('misc',
			array(
				'label' => $this->__('Attribute, Option &amp; Store'),
				'title' => $this->__('Attribute, Option &amp; Store'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_page_edit_tab_option')->toHtml(),
			)
		);
		
		$this->addTab('general',
			array(
				'label' => $this->__('General'),
				'title' => $this->__('General'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_page_edit_tab_general')->toHtml(),
			)
		);
		
		$this->addTab('seo',
			array(
				'label' => $this->__('SEO'),
				'title' => $this->__('SEO'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_page_edit_tab_seo')->toHtml(),
			)
		);
		
		$this->addTab('display',
			array(
				'label' => $this->__('Display Settings'),
				'title' => $this->__('Display Settings'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_page_edit_tab_display')->toHtml(),
			)
		);
		
		if (Mage::registry('splash_page')) {
			$this->addTab('products',
				array(
					'label' => $this->__('Products'),
					'title' => $this->__('Products'),
					'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_page_edit_tab_products')->toHtml(),
				)
			);
		}
		
		$this->_activeTab = 'general';
		
		return parent::_beforeToHtml();
	}
}
