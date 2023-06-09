<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewCrawler extends JViewLegacy
{
	protected $config;
	
	public function display($tpl = null) {
		
		$this->config  = rsseoHelper::getConfig();
		$this->offline = JFactory::getConfig()->get('offline');
		
		if ($this->offline) {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSSEO_CRAWLER_SITE_OFFLINE'), 'error');
		}
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_CRAWLER'),'rsseo');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
	}
}