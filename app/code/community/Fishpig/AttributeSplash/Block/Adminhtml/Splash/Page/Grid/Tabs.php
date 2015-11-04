<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Grid_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_tabs');
		$this->setDestElementId('splash_grid_wrapper');
		$this->setTitle($this->__('<a href="http://fishpig.co.uk/" target="_gofishing" style="text-decoration:none;"><strong>Fishpig</strong></a><span style="font-weight:normal;">\'</span><a href="http://fishpig.co.uk/" target="_gofishing" style="text-decoration:none;"><strong>s</strong></a> Attribute Splash'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('splash_pages',
			array(
				'label' => $this->__('Splash Pages'),
				'title' => $this->__('Splash Pages'),
				'content' => $this->getLayout()->createBlock('attributeSplash/adminhtml_splash_page_grid')->toHtml(),
			)
		);

		$this->addTab('splash_lists',
			array(
				'label' => $this->__('Splash Lists'),
				'title' => $this->__('Splash Lists'),
				'content' => '<div style="color:#888; font-size:2em; padding:10px 20px;">Coming soon!</div>',
			)
		);

		$this->addTab('settings',
			array(
				'label' => $this->__('Configuration'),
				'title' => $this->__('Configuration'),
				'content' => "<script type=\"text/javascript\">Event.observe(window, 'load', function() { $('splash_tabs_settings').observe('click', function(e) { $('loading-mask').show(); location.href = '".$this->getUrl('adminhtml/system_config/edit/', array('section' => 'attributeSplash'))."'; e.stop(); });});</script>",
			)
		);

		return parent::_beforeToHtml();
	}
}
