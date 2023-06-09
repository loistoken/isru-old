<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoViewPage extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $config;
	protected $layout;
	protected $details;
	
	public function display($tpl = null) {
		$this->layout		= $this->getLayout();
		$this->item			= $this->get('Item');
		$this->config 		= rsseoHelper::getConfig();
		$this->html			= JFactory::getConfig()->get('sef_suffix') ? '.html' : '';
		$this->sef			= JFactory::getConfig()->get('sef');
		
		if ($this->layout == 'details') {
			$this->details 		 = $this->get('Details');
		} elseif($this->layout == 'links') {

		} else {
			$this->form 		 = $this->get('Form');
			$this->broken 		 = $this->get('Broken');
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		if ($this->layout == 'details') {
			JToolBarHelper::title(JText::_('COM_RSSEO_PAGE_SIZE_DETAILS'),'rsseo');
			
			$bar 		= JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'arrow-left', JText::_('COM_RSSEO_GLOBAL_BACK'), 'index.php?option=com_rsseo&view=page&layout=edit&id='.$this->item->id);
		} elseif($this->layout == 'links') {
			JToolBarHelper::title(JText::_('COM_RSSEO_PAGE_INT_EXT_LINKS'),'rsseo');
			
			$bar 		= JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'arrow-left', JText::_('COM_RSSEO_GLOBAL_BACK'), 'index.php?option=com_rsseo&view=page&layout=edit&id='.$this->item->id);
		} else {
			JToolBarHelper::title(JText::_('COM_RSSEO_PAGE_NEW_EDIT'),'rsseo');
		
			JToolBarHelper::apply('page.apply');
			JToolBarHelper::save('page.save');
			JToolBarHelper::cancel('page.cancel');
			
			JHtml::script('com_rsseo/jquery.tablednd.js', array('relative' => true, 'version' => 'auto'));
		}
	}
}