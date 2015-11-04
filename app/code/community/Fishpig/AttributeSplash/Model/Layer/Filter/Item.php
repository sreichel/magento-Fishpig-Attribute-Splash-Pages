<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
	/**
	 * Get filter item url
	 *
	 * @return string
	*/
	public function getUrl()
	{
		if (!Mage::registry('splash_page')) {
	    	if (Mage::helper('attributeSplash')->splashPageExists($this->getValue(), Mage::app()->getStore()->getId())) {

    			$splashPage = Mage::getModel('attributeSplash/page')->loadByOptionId($this->getValue());

    			if ($splashPage->canDisplay()) {
    				$query = array(
    					$this->getFilter()->getRequestVar() => null,
    					Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null,
    				);
    				
					return Mage::getUrl('', array('_direct' => $splashPage->getUrlPath(), '_current' => true, '_query' => $query));
	    		}
			}
		}
		
		return parent::getUrl();
	}
}
