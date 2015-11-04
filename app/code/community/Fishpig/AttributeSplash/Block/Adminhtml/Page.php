<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{	
		parent::__construct();
		
		$this->_controller = 'adminhtml_page';
		$this->_blockGroup = 'attributeSplash';
		$this->_headerText = $this->__('Attribute Splash Pages');
		$this->_addButtonLabel = $this->__('Create a New Splash Page');
		
		$this->_addButton('reindex_urls', array(
			'label' => $this->__('Reindex URL\'s'),
			'onclick' => 'confirmSetLocation(\'Are you sure you want to reindex your Splash URL rewrites?\', \'' . $this->getUrl('*/*/reindex') . '\');',
		));
	}
}