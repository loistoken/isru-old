<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewPages extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $config;
	
	public function display($tpl = null) {
		$this->simple		= JFactory::getSession()->get('com_rsseo.pages.simple',false);
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		$this->config 		= rsseoHelper::getConfig();
		$this->batch		= $this->get('BatchFields');
		$this->sef			= JFactory::getConfig()->get('sef');
		$this->filterForm	= $this->get('FilterForm');
		$this->activeFilters= $this->get('ActiveFilters');
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_LIST_PAGES'),'rsseo');

		JToolBarHelper::addNew('page.add');
		JToolBarHelper::editList('page.edit');
		JToolBarHelper::deleteList('COM_RSSEO_PAGE_CONFIRM_DELETE','pages.delete');
		JToolBar::getInstance('toolbar')->appendButton('Confirm',JText::_('COM_RSSEO_DELETE_ALL_PAGES_MESSAGE',true),'delete',JText::_('COM_RSSEO_DELETE_ALL_PAGES'),'pages.removeall',false);
		JToolBarHelper::publishList('pages.publish');
		JToolBarHelper::unpublishList('pages.unpublish');
		
		if (!$this->simple) {
			JToolBarHelper::custom('pages.addsitemap','new','new',JText::_('COM_RSSEO_PAGE_ADDTOSITEMAP'));
			JToolBarHelper::custom('pages.removesitemap','trash','trash',JText::_('COM_RSSEO_PAGE_REMOVEFROMSITEMAP'));
			JToolBarHelper::custom('restore','flag','flag',JText::_('COM_RSSEO_RESTORE_PAGES'));
			JToolBarHelper::custom('refresh','refresh','refresh',JText::_('COM_RSSEO_BULK_REFRESH'));
			JToolBarHelper::custom('pages.simple','contract','contract',JText::_('COM_RSSEO_SIMPLE_VIEW'),false);
			
			$layout = new JLayoutFile('joomla.toolbar.popup');
			$dhtml = $layout->render(array('text' => JText::_('COM_RSSEO_BATCH'), 'class' => 'icon-checkbox-partial', 'name' => 'batchpages', 'doTask' => ''));
			JToolbar::getInstance('toolbar')->appendButton('Custom', $dhtml, 'batch');
		} else {
			JToolBarHelper::custom('pages.standard','expand','expand',JText::_('COM_RSSEO_STANDARD_VIEW'),false);
		}
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
	}
}