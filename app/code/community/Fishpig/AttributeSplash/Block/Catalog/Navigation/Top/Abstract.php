<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

/**
 * This is a horrible hack but the only way I can see to select the right block for each Magento version
 * Sorry!
 *
 */
 
if (version_compare(Mage::getVersion(), '1.7.0.0', '<')) {
	abstract class Fishpig_AttributeSplash_Block_Catalog_Navigation_Top_Abstract extends Mage_Catalog_Block_Navigation
	{
	}
}
else {
	abstract class Fishpig_AttributeSplash_Block_Catalog_Navigation_Top_Abstract extends Mage_Page_Block_Html_Topmenu
	{
	}
}
