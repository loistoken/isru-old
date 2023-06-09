<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewError extends JViewLegacy
{
	protected $form;
	protected $item;
	
	public function display($tpl = null) {
		$this->form 		= $this->get('Form');
		$this->item			= $this->get('Item');
		
		$this->addToolBar();
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_RSSEO_ERROR_NEW_EDIT'),'rsseo');
		
		JToolBarHelper::apply('error.apply');
		JToolBarHelper::save('error.save');
		JToolBarHelper::cancel('error.cancel');
	}
}