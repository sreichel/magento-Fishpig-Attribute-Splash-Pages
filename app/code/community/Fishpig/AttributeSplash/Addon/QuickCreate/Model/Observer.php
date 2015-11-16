<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Addon_QuickCreate_Model_Observer extends Varien_Object
{
	/**
	 * Inject the tab for QC into the Splash dashboard
	 *
	 * @param Varien_Event_Observer $observer
	 * @return $this
	 */
	public function injectQuickCreateTabObserver(Varien_Event_Observer $observer)
	{
		$layout = Mage::getSingleton('core/layout');

		$observer->getEvent()
			->getTabs()
				->addTab('quickcreate', array(
					'label'     => Mage::helper('catalog')->__('Quick Create'),
					'content'   => $layout->createBlock('attributeSplash_addon_quickcreate/adminhtml_create')->toHtml(),
				));

		return $this;
	}
}
