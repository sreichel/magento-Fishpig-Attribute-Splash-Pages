<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
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
}
