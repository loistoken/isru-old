<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewData extends JViewLegacy
{
	public function display($tpl = null) {
		$this->form = $this->get('form');
		
		JPluginHelper::importPlugin('rsseo');
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_STRUCTURED_DATA'),'rsseo');
		JToolBarHelper::apply('data.save');
		
		if (JFactory::getUser()->authorise('core.admin', 'com_rsseo'))
			JToolBarHelper::preferences('com_rsseo');
	}
}