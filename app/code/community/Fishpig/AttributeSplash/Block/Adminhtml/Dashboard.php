<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('splash_dashboard_tabs');
        $this->setDestElementId('splash_tab_content');
		$this->setTitle($this->__('Attribute Splash Pages'));
		$this->setTemplate('widget/tabshoriz.phtml');
	}
	
	protected function _prepareLayout()
	{
		$tabs = array(
			'group' => 'Groups',
			'page' => 'Pages',
		);
		
		foreach($tabs as $alias => $label) {
			$this->addTab($alias, array(
				'label'     => Mage::helper('catalog')->__($label),
				'content'   => $this->getLayout()->createBlock('attributeSplash/adminhtml_' . $alias)->toHtml(),
				'active'    => $alias === 'page',
			));
		}

		if ($extend = $this->getLayout()->createBlock('fpadmin/adminhtml_extend')) {
			$html = trim($extend->setTemplate('attribute-splash/extend.phtml')->toHtml());
			
			if ($html !== '') {
				$this->addTab('extend', array(
					'label'     => Mage::helper('catalog')->__('Add-Ons'),
					'content'   => $html,
					'active'    => false,
				));
			}
		}
	}
}
