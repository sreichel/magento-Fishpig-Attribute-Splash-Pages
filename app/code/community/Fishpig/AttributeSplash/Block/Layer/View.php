<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
	/**
	 * Returns the layer object for the attributeSplash model
	 *
	 * @return Fishpig_AttributeSplash_Model_Layer
	 */
	public function getLayer()
	{
		return Mage::getSingleton('attributeSplash/layer');
	}
	
	/**
	 * Ensure the default Magento blocks are used
	 *
	 * @return $this
	 */
    protected function _initBlocks()
    {
    	parent::_initBlocks();
    	
        $this->_stateBlockName              = 'Mage_Catalog_Block_Layer_State';
        $this->_categoryBlockName           = 'Mage_Catalog_Block_Layer_Filter_Category';
        $this->_attributeFilterBlockName    = 'Mage_Catalog_Block_Layer_Filter_Attribute';
        $this->_priceFilterBlockName        = 'Mage_Catalog_Block_Layer_Filter_Price';
        $this->_decimalFilterBlockName      = 'Mage_Catalog_Block_Layer_Filter_Decimal';
        
        return $this;
    }
}
