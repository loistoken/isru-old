<?php
/**
* @version 1.0.0
* @package RSSearch! 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class RSsearchController extends JControllerLegacy
{
	public function __construct() {
		parent::__construct();
	}
	
	public function search()
	{
		$this->input = JFactory::getApplication()->input;

		// Slashes cause errors, <> get stripped anyway later on. # causes problems.
		$badchars = array('#', '>', '<', '\\');
		$search = trim(str_replace($badchars, '', $this->input->getString('search', null, 'post')));

		// If search enclosed in double quotes, strip quotes and do exact match
		if (substr($search, 0, 1) == '"' && substr($search, -1) == '"')
			$post['search'] = substr($search, 1, -1);
		else
			$post['search'] = $search;


		$post['limit']        = $this->input->getUInt('limit', null, 'post');

		if ($post['limit'] === null)
		{
			unset($post['limit']);
		}

		// The Itemid from the request, we will use this if it's a search page or if there is no search page available
		$post['Itemid'] = $this->input->getInt('Itemid');

		// Set Itemid id for links from menu
		$app  = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getItem($post['Itemid']);

		if ($item) {
			// The request Item is not a search page so we need to find one
			if ($item->component != 'com_rssearch' || $item->query['view'] != 'results')
			{
				// Get item based on component, not link. link is not reliable.
				$item = $menu->getItems('component', 'com_rssearch', true);

				// If we found a search page, use that.
				if (!empty($item))
				{
					$post['Itemid'] = $item->id;
				}
			}
		}
		$post['module_id'] = $this->input->getInt('module_id');
		$post['view'] 	   = 'results';
		unset($post['task']);
		unset($post['submit']);

		$uri = JUri::getInstance();
		$uri->setQuery($post);
		$uri->setVar('option', 'com_rssearch');

		$this->setRedirect(JRoute::_('index.php' . $uri->toString(array('query', 'fragment')), false));
	}
}