<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Model_Splash extends Mage_Core_Model_Abstract
{
	const ATTRIBUTE_MODE_GRID = 1;
	const ATTRIBUTE_MODE_LIST = 2;
	const ATTRIBUTE_MODE_SIMPLE = 3;
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('attributeSplash/splash');
	}
	
	/**
	 * Retrieves the model name
	 * If the model isn't set, the option label is returned
	 *
	 * @return string
	 */
	public function getName()
	{
		return (trim($this->getDisplayName())) ? $this->getDisplayName() : $this->getFrontendLabel();
	}

	/**
	 * Gets the URL to view the Splash page on the frontend
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return Mage::helper('attributeSplash')->getSplashUrl($this);
	}
	
	public function getImageUrl()
	{
		return $this->_getImageUrl($this->getImage());
	}
	
	public function getThumbnailUrl()
	{
		return $this->_getImageUrl($this->getThumbnail());
	}
	
	protected function _getImageUrl($filename)
	{
		return Mage::getBaseUrl('media') . DS . 'splash' . DS . $filename;
	}
}
