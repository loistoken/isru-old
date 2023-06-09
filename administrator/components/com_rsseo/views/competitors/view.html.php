<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewCompetitors extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $config;
	
	public function display($tpl = null) {
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		$this->filterForm	= $this->get('FilterForm');
		$this->config 		= rsseoHelper::getConfig();
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		$parent = $this->state->get('filter.parent');
		
		if (!$parent) {
			JToolBarHelper::title(JText::_('COM_RSSEO_LIST_COMPETITORS'),'rsseo');	
			JToolBarHelper::addNew('competitor.add');
			JToolBarHelper::editList('competitor.edit');
		} else {
			JToolBarHelper::title(JText::sprintf('COM_RSSEO_LIST_COMPETITORS_FOR', $this->get('competitor')),'rsseo');
			JToolBarHelper::custom('back','arrow-left','arrow-left',JText::_('COM_RSSEO_GLOBAL_BACK'),false);
		}
		
		JToolBarHelper::deleteList('COM_RSSEO_GLOBAL_CONFIRM_DELETE','competitors.delete');
		
		if (!$parent) {
			JToolBarHelper::custom('competitors.export','upload','upload_f2',JText::_('COM_RSSEO_GLOBAL_EXPORT'),false);
		}
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
	}
}