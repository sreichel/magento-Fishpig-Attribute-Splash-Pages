<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Splash_Page_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_controller = 'adminhtml_splash';
		$this->_blockGroup = 'attributeSplash';
		$this->_headerText = $this->_getHeaderText();
		
		if ($extra = Mage::registry('splash_option_extra')) {
			$this->addButton('splash_view', array(
				'label' => $this->__('View'),
				'onclick' => "popWin('".$extra->getUrl()."', '_blank')",
			));
		}
	}

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    
	protected function _getHeaderText()
	{
		if ($optionExtra = Mage::registry('splash_option_extra')) {
			if ($displayName = $optionExtra->getDisplayName()) {
				return $displayName;
			}
		}
	
		return $this->__('Edit Attribute Splash Page');
	}
}
