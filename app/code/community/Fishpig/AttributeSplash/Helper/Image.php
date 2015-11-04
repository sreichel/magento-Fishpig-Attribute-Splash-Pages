<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */
 
class Fishpig_AttributeSplash_Helper_Image extends Mage_Core_Helper_Abstract
{
	/**
	 * Storeage for image object, used for resizing images
	 *
	 * @var null/Varien_Image
	 */
	protected $_imageObject = null;
	
	/**
	 * Flag used to determine wether to recreate already cached image
	 *
	 * @var bool
	 */
	protected $_forceRecreate = false;
	
	/**
	 * Filename currently initialized in the image object
	 *
	 * @var null|string
	 */
	protected $_filename = '';

	/**
	 * The folder name used to store images
	 * This is relative to the media directory
	 *
	 * @var const string
	 */
	const IMAGE_FOLDER = 'attributesplash';

	/**
	 * Retrieve the image URL where images are stored
	 *
	 * @return string
	 */
	public function getBaseImageUrl()
	{
		return Mage::getBaseUrl('media') . self::IMAGE_FOLDER . '/';
	}
	
	/**
	 * Retrieve the directory/path where images are stored
	 *
	 * @return string
	 */
	public function getBaseImagePath()
	{
		return Mage::getBaseDir('media') . DS . self::IMAGE_FOLDER . DS;
	}
	
	/**
	 * Retrieve the full image URL
	 * Null returned if image does not exist
	 *
	 * @param string $image
	 * @return string|null
	 */
	public function getImageUrl($image)
	{
		if ($this->imageExists($image)) {
			return $this->getBaseImageUrl() . $image;
		}
		
		return null;
	}
	
	/**
	 * Retrieve the full image path
	 * Null returned if image does not exist
	 *
	 * @param string $image
	 * @return string|null
	 */
	public function getImagePath($image)
	{
		if ($this->imageExists($image)) {
			return $this->getBaseImagePath() . $image;
		}
		
		return null;
	}
	
	/**
	 * determine whether the image exists
	 *
	 * @param string $image
	 * @return bool
	 */
	public function imageExists($image)
	{
		return is_file($this->getBaseImagePath() . $image);
	}

	/**
	 * Converts a filename, width and height into it's resized uri path
	 * returned path does not include base path
	 *
	 * @param string $filename
	 * @param int $width = null
	 * @param int $height = null
	 * @return string
	 */
	public function getResizedImageUrl($filename, $width = null, $height = null)
	{
		return $this->getBaseImageUrl() . $this->_getRelativeResizedImagePath($filename, $width, $height);
	}
	
	/**
	 * Converts a filename, width and height into it's resized path
	 * returned path does not include base path
	 *
	 * @param string $filename
	 * @param int $width = null
	 * @param int $height = null
	 * @return string
	 */
	public function getResizedImagePath($filename, $width = null, $height = null)
	{
		return $this->getBaseImagePath() . $this->_getRelativeResizedImagePath($filename, $width, $height);
	}

	/**
	 * Converts a filename, width and height into it's resized path
	 * returned path does not include base path
	 *
	 * @param string $filename
	 * @param int $width = null
	 * @param int $height = null
	 * @return string
	 */	
	protected function _getRelativeResizedImagePath($filename, $width = null, $height = null)
	{
		if (!is_null($width) || !is_null($height)) {
			return 'cache' . DS . trim($width.'x'.$height, 'x') . DS . $filename;
		}
		
		return $filename;
	}

	/**
	 * Initialize the image object
	 * This sets up the image object for resizing and caching
	 *
	 * @param Fishpig_AttributeSplash_Model_Page $page
	 * @param string $attribute
	 * @return Fishpig_AttributeSplash_Helper_Image
	 */
	public function init(Fishpig_AttributeSplash_Model_Page $page, $attribute = 'image')
	{
		$this->_imageObject = null;
		$this->_forceRecreate = false;
		$this->_filename = null;
		
		if ($imagePath = $this->getImagePath($page->getData($attribute))) {
			$this->_imageObject = new Varien_Image($imagePath);
			$this->_filename = basename($imagePath);
			
			$this->keepAspectRatio(true);
		}
		
		return $this;
	}

	/**
	 * Resize the image loaded into the image object
	 *
	 * @param int $width = null
	 * @param int $height = null
	 * @return string
	 */
	public function resize($width = null, $height = null)
	{
		if ($this->isActive()) {
			$cachedFilename = $this->getResizedImagePath($this->_filename, $width, $height);
				
			if ($this->_forceRecreate || !is_file($cachedFilename)) {
				$this->_imageObject->resize($width, $height);
				$this->_imageObject->save($cachedFilename);
			}
			
			return $this->getResizedImageUrl($this->_filename, $width, $height);;
		}
	
		return '';
	}
	
	/**
	 * Keep the frame or add a white space
	 *
	 * @param bool $val
	 */
	public function keepFrame($val)
	{
		if ($this->isActive()) {
			$this->_imageObject->keepFrame($val);
		}
		
		return $this;
	}
	
	/**
	 * Keep the aspect ratio of an image
	 *
	 * @param bool $val
	 */
	public function keepAspectRatio($val)
	{
		if ($this->isActive()) {
			$this->_imageObject->keepAspectRatio($val);
		}
		
		return $this;
	}
	
	/**
	 * Don't increase the size of an image, only decrease
	 *
	 * @param bool $val
	 */
	public function constrainOnly($val)
	{
		if ($this->isActive()) {
			$this->_imageObject->constrainOnly($val);
		}
		
		return $this;
	}

	/**
	 * Determine whether to recreate image that already exists
	 *
	 * @param bool $val
	 */	
	public function forceRecreate($val)
	{
		if ($this->isActive()) {
			$this->_forceRecreate = $val;
		}
		
		return $this;
	}

	/**
	 * Set the image background colour
	 *
	 * @param array $rgb
	 */
	public function backgroundColor($rgb = null)
	{
		if ($this->isActive()) {
			$this->_imageObject->backgroundColor($rgb);
		}
		
		return $this;
	}

	/**
	 * Determine whether the image object has been initialised
	 *
	 * @return bool
	 */
	public function isActive()
	{
		return is_object($this->_imageObject);
	}
	
	/**
	 * Upload an image based on the $fileKey
	 *
	 * @param string $fileKey
	 * @param string|null $filename - set a custom filename
	 * @return null|string - returns saved filename
	 */
	public function uploadImage($fileKey, $filename = null)
	{
		try {
			$uploader = new Varien_File_Uploader($fileKey);
			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
			$uploader->setAllowRenameFiles(true);
			$result = $uploader->save($this->getBaseImagePath());
			
			return $result['file'];
		}
		catch (Exception $e) {
			if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
				throw $e;
			}
		}
		
		return null;
	}
}
