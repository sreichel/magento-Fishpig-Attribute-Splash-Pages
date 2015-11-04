<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Group_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_group_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Splash Group Information'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('general',
			array(
				'label' => $this->__('General'),
				'title' => $this->__('General'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_group_edit_tab_general')->toHtml(),
			)
		);
		
		$this->addTab('seo',
			array(
				'label' => $this->__('SEO'),
				'title' => $this->__('SEO'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_group_edit_tab_seo')->toHtml(),
			)
		);
		
		$this->addTab('display',
			array(
				'label' => $this->__('Display Settings'),
				'title' => $this->__('Display Settings'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_group_edit_tab_display')->toHtml(),
			)
		);
		
		return parent::_beforeToHtml();
	}
}
