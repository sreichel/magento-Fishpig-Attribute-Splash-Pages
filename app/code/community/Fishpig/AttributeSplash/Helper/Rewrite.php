<?php

class Fishpig_AttributeSplash_Helper_Rewrite extends Fishpig_AttributeSplash_Helper_Abstract
{
	/**
	 * Generates the object's rewrite description
	 * This is used to update rewrites when the URL suffix is changed
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return string
	 */
	public function getRewriteDescription(Mage_Core_Model_Abstract $object)
	{
		return serialize(array('url_key' => $this->_getUrlKey($object), 'resource' => $object->getResourceName()));
	}

	/**
	 * Generates the object's request path
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return string
	 */
	public function getRequestPath(Mage_Core_Model_Abstract $object)
	{
		return $this->_getUrlKey($object) . $this->getUrlSuffix();
	}
	
	/**
	 * Retrieves the real URL key by checking the interal data array before generating
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return string
	 */
	protected function _getUrlKey(Mage_Core_Model_Abstract $object)
	{
		return (($urlKey = trim($object->getData('url_key'))) ? $urlKey : $object->getUrlKey());
	}
	
	/**
	 * Converts a string to formatted URL key
	 *
	 * @param $string - the string to format
	 * @return string
	 */
	public function formatUrlKey($string)
	{
		$string = preg_replace('#[^0-9a-z]+#i', '-', $string);
		$string = strtolower($string);
		$string = trim($string, '-');
		
		return $string;
	}
}
