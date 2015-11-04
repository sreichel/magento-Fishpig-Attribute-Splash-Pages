<?php
/**
 * Fishpig's Attribute Splash 
 *
 * @category    Fishpig
 * @package    Fishpig_AttributeSplash
 * @author      Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplash_Helper_Image extends Fishpig_AttributeSplash_Helper_Abstract
{
	public function getImageUrl()
	{
		return Mage::getBaseUrl('media') . 'splash/';
	}
	
	public function getImagePath()
	{
		return Mage::getBaseDir('media') . DS . 'splash' . DS;
	}
	
	public function getImageCacheUrl()
	{
		return $this->getImageUrl() . 'cache/';
	}
	
	public function getImageCachePath()
	{
		return $this->getImagePath() . 'cache' . DS;
	}
	
	public function getImageUrlIfExists($file)
	{
		if ($this->imageExists($file)) {
			return $this->getImageUrl() . $file;
		}
	}

	/**
	 * Determine whether the given image exists
	 *
	 */
	public function imageExists($filename)
	{
		return $filename && file_exists($this->getImagePath() . $filename) && !is_dir($this->getImagePath() . $filename);
	}
	
	/**
	 * Resize an image based on the URL. The URL must be on the current domain
	 *
	 * @param string $imageUrl
	 * @param int $width
	 * @param int $height
	 * @return string
	 */
	public function resize($imageUrl, $width, $height)
	{
		if ($imageUrl) {
			if(!file_exists($this->getImageCachePath())) {
				@mkdir($this->getImageCachePath(), 0777);
			}
	
			$filename = trim(substr($imageUrl, strrpos($imageUrl, DS)), DS);
			
			if ($filename) {
				$ext = substr($filename, strrpos($filename, '.'));
				$filename = "{$width}x{$height}__" . substr($filename, 0, -(strlen($ext))) . $ext;
		
				$new = $this->getImageCachePath() . $filename;
				$original = str_replace(Mage::getBaseUrl(), Mage::getBaseDir() . DS, $imageUrl);
				
				try {
					if (!file_exists($new) && file_exists($original)) {
						$image = new Varien_Image($original);
						$image->constrainOnly(true);
						$image->keepAspectRatio(false);
						$image->keepFrame(false);
						$image->resize($width, $height);
						$image->save($new);
					}
	
					return $this->getImageCacheUrl() . $filename;
				}
				catch (Exception $e) {
					Mage::log($this->__('Error with image (%s -- %s)', $imageUrl, $filename));
					Mage::logException($e);
				}
			}
		}
	}
}
