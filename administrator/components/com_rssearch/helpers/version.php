<?php
/**
* @package RSSearch!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSSearchVersion
{
	public $version = '1.0.7';
	
	public function __toString() {
		return $this->version;
	}
}