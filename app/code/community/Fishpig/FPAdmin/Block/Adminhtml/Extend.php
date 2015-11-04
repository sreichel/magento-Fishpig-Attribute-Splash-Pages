<?php
/**
 * @category    Fishpig
 * @package     Fishpig_FPAdmin
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_FPAdmin_Block_Adminhtml_Extend extends Mage_Adminhtml_Block_Template
{
	/**
	 * Tracking string for GA
	 *
	 * @var const string
	 */
	const TRACKING_STRING = '?utm_source=%s&utm_medium=%s&utm_term=%s&utm_campaign=%s';
	
	/**
	 * Base URL for links
	 *
	 * @var const string
	 */
	 const BASE_URL = 'http://fishpig.co.uk/';

	/**
	 * Cache for all available extensions
	 *
	 * @var array
	 */
	static protected $_extensions = null;
	
	/**
	 * Retrieve the available extensions taking into account $count and $pref
	 *
	 * @param int $count = 0
	 * @param array $pref = array()
	 * @return false|array
	 */
	public function getExtensions($count = 0, array $pref = array())
	{
		if (($pool = $this->_getAllExtensions()) !== false) {
			$winners = array();
	
			foreach($pref as $code) {
				if (isset($pool[$code])) {
					$winners[$code] = $pool[$code];
					unset($pool[$code]);
				}
				
				if ($count > 0 && count($winners) >= $count) {
					break;
				}
			}
			
			while(count($winners) < $count && count($pool) > 0) {
				$code = key($pool);
				
				$winners[$code] = $pool[$code];
				unset($pool[$code]);
			}
					
			end($winners);
			
			$winners[key($winners)]['last'] = true;
	
			return $winners;
		}
		
		return false;
	}
	
	/**
	 * Retrieve all of the available extensions
	 *
	 * @return array
	 */
	protected function _getAllExtensions()
	{
		if (!is_null(self::$_extensions)) {
			return self::$_extensions;
		}
		
		$installedModules = array_keys((array)$this->_getConfig()->getNode('modules'));
		$config = (array)$this->_getConfig()->getNode('fishpig/extend');
		self::$_extensions = array();

		foreach($config as $code => $extension) {
			$extension->module = $code;
			$reqMultistore = isset($extension->require_multistore) ? (int)$extension->require_multistore : null;

			if (!isset($_SERVER['IS_FISHPIG']) && in_array($code, $installedModules)) {
				continue;
			}
			else if (!is_null($reqMultistore) && $reqMultistore === (int)Mage::app()->isSingleStoreMode()) {
				continue;
			}
			else if (isset($extension->depends)) {
				$depends = array_keys((array)$extension->depends);

				if (count(array_diff($depends, $installedModules)) > 0) {
					continue;
				}
			}

			self::$_extensions[$code] = (array)$extension;
		}
		
		if (count(self::$_extensions) === 0) {
			self::$_extensions = false;
		}

		return self::$_extensions;
	}

	/**
	 * Retrieve the title of the extension
	 *
	 * @param array $e
	 * @return string
	 */
	public function getTitle(array $e)
	{
		return $this->_getField($e, 'title');
	}
	
	/**
	 * Retrieve the subtitle of the extension
	 *
	 * @param array $e
	 * @return string
	 */
	public function getSubTitle(array $e)
	{
		return $this->_getField($e, 'subtitle');
	}

	/**
	 * Rertrieve the URL for $e with the tracking code
	 *
	 * @param array $e
	 * @param string $campaign
	 * @param string $source
	 * @param string $medium
	 * @return string
	 */
	public function getTrackedUrl(array $e, $source, $content = null, $medium = 'Extend')
	{
		$campaign = $this->_getField($e, 'module');		
		$trackedUrl = sprintf(self::BASE_URL . $this->_getField($e, 'url') . self::TRACKING_STRING, $source, $medium, $campaign, $campaign);
		
		if (!is_null($content)) {
			$trackedUrl .= '&utm_content=' . $content;
		}
		
		return $trackedUrl;
	}
	
	/**
	 * Retrieve the short description of the extension
	 *
	 * @param array $e
	 * @return string
	 */
	public function getShortDescription(array $e)
	{
		return $this->_getField($e, 'short_description');
	}
	
	/**
	 * Retrieve the image URL of the extension
	 *
	 * @param array $e
	 * @return string
	 */
	public function getImageUrl(array $e)
	{
		return $this->getUrl('adminhtml/fishpig_extend/image', array('id' => $this->_getField($e, 'image')));
	}
	
	/**
	 * Retrieve a field from the extension
	 *
	 * @param array $e
	 * @param string $field
	 * @return string
	 */
	protected function _getField(array $e, $field)
	{
		return $e && is_array($e) && isset($e[$field]) ? $e[$field] : '';
	}
	
	/**
	 * Determine wether $e is the last $e in the array
	 *
	 * @param array $e
	 * @return bool
	 */
	public function isLast(array $e)
	{
		return $this->_getField($e, 'last') === true;
	}

	/**
	 * Retrieve the Magento config model
	 *
	 * @return Mage_Core_Model_Config
	 */
	protected function _getConfig()
	{
		return Mage::app()->getConfig();
	}
}