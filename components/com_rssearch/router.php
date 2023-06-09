<?php
/**
* @package RSSearch!
* @copyright (C) 2011-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-3.0.html
*/

defined('_JEXEC') or die('Restricted access');

function RSSearchBuildRoute(&$query)
{
	$lang = JFactory::getLanguage();
	$lang->load('com_rssearch', JPATH_SITE);

	$segments = array();

	$segments[] = JText::_('COM_RSSEARCH_SEF_VIEW_RESULTS');

	$segments[] = isset($query['module_id']) ? urlencode('module-'.$query['module_id']) : '';
	$segments[] = isset($query['search']) ? urlencode('searchterm-'.$query['search']) : '';

	unset($query['search'], $query['module_id']);
	unset($query['task'], $query['view'], $query['layout']);

	return $segments;
}

function RSSearchParseRoute($segments)
{
	
	$lang = JFactory::getLanguage();
	$lang->load('com_rssearch', JPATH_SITE);

	$query = array();

	$segments[0] = str_replace(':', '-', $segments[0]);
	if (isset($segments[1]))
		$segments[1] = str_replace(':', '-', $segments[1]);
	if (isset($segments[2]))
		$segments[2] = str_replace(':', '-', $segments[2]);
	
	switch ($segments[0])
	{
		case JText::_('COM_RSSEARCH_SEF_VIEW_RESULTS'):
			$query['view']   = 'results';
			$query['layout']   = 'default';
			$query['module_id'] = isset($segments[1]) ? str_replace('module-', '', $segments[1]) : '';
			$query['search'] = isset($segments[2]) ? str_replace('searchterm-', '', $segments[2]) : '';
		break;
	}

	return $query;
}