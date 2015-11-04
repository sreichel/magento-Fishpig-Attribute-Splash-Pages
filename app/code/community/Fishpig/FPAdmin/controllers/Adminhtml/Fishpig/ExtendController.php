<?php
/**
 * @category    Fishpig
 * @package     Fishpig_FPAdmin
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_FPAdmin_Adminhtml_Fishpig_ExtendController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Display a grid of splash groups
	 *
	 */
	public function imageAction()
	{
		$image = preg_replace('/([^a-z0-9\-\.]{1})/', '', trim($this->getRequest()->getParam('id')));
		$file = dirname(Mage::getModuleDir('etc', 'Fishpig_FPAdmin')) . DS . 'image' . DS . $image;

		if (!is_file($file) || !is_readable($file)) {
			$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
		}
		else {
			$this->getResponse()
				->setHeader('Content-Type', 'image/png')
				->setBody(@file_get_contents($file));
		}
	}
}
