<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewAnalytics extends JViewLegacy
{
	protected $config;
	protected $accounts;
	protected $acc;
	protected $rsstart;
	protected $rsend;
	protected $tabs;
	protected $visits;
	protected $sources;
	
	public function display($tpl = null) {
		$this->config	= rsseoHelper::getConfig();
		
		if (JFactory::getApplication()->input->getInt('ajax',0)) {
			$layout = $this->getLayout();
			$this->{$layout} = $this->get('GA'.ucfirst($layout));
			
		} else {
			// Check if we can show the analytics form
			$this->check();
			
			// Check if the user is authentified and the token is valid
			$this->valid = $this->get('IsValid');
			
			// Get the authorization URL
			$this->auth = $this->get('AuthUrl');
			
			// Get user profiles
			$this->profiles = $this->get('Profiles');
			$this->selected = $this->get('Selected');
			
			$now			= JFactory::getDate()->toUnix(); 
			$this->rsstart	= JHtml::_('date', ($now - 604800), 'Y-m-d');
			$this->rsend	= JHtml::_('date', ($now - 86400), 'Y-m-d');
			$this->tabs		= $this->get('Tabs');
			
			$this->addToolBar();
		}
		
		parent::display($tpl);
		if (JFactory::getApplication()->input->getInt('ajax')) {
			JFactory::getApplication()->close();
		}
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_GOOGLE_ANALYTICS'),'rsseo');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
		
		JFactory::getDocument()->addScript('https://www.google.com/jsapi');
	}
	
	protected function check() {
		$app = JFactory::getApplication();
		
		if (!extension_loaded('curl')) {
			$app->enqueueMessage(JText::_('COM_RSSEO_NO_CURL'));
			$app->redirect('index.php?option=com_rsseo');
		}
		
		if (trim($this->config->analytics_client_id) == '' || trim($this->config->analytics_secret) == '' || $this->config->analytics_enable == 0) {
			$app->enqueueMessage(JText::_('COM_RSSEO_GA_ERROR'));
			$app->redirect('index.php?option=com_rsseo');
		}
	}
}