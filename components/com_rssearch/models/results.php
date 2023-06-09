<?php
/**
* @version 1.2.0
* @package RSSearch! 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class RssearchModelResults extends JModelLegacy {
	
	protected $_total=null;

	public function __construct() {
		parent::__construct();
		
		$app		= JFactory::getApplication();
		$limit		= $app->getUserStateFromRequest('com_rssearch.results.limit', 'limit', $app->getCfg('list_limit'));
		$limitstart = $app->input->getInt('limitstart', 0);

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getResults() {
		jimport('joomla.plugin.helper');
		
		$search		= JFactory::getApplication()->input->getString('search','');
		$results	= array();

		$params = $this->getModuleParams();
		
		$registry = new JRegistry;
		$registry->loadString($params);
		
		$components = $registry->get('comps');
		
		// Get plugins
		JPluginHelper::importPlugin('rssearch');
		$dispatcher = JDispatcher::getInstance();

		if (!empty($components)) {
			
			if (!is_array($components)) {
				$components = (array) $components;
			}
			
			foreach ($components as $component) {
				if ($plugin = JPluginHelper::getPlugin('rssearch',$component)){
					$className = 'plgRSSearch'.$plugin->name;
					if(class_exists($className)) {
						$instance = new $className($dispatcher, (array)$plugin);
						$instance->getResults($results, $search, $registry);
					}
				}
			}
		}

		$this->_total = count($results);

		if($this->getState('limit') > 0){
			$results = array_slice($results, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $results;
	}
	
	public function getTotal() {
		return $this->_total;
	}
	
	public function getPagination() {
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}
	
	public function getWordsLimit() {
		$params = $this->getModuleParams();
		
		$registry = new JRegistry;
		$registry->loadString($params);
		
		return $registry->get('nr_words',80);
	}
	
	public function getType() {
		$params = $this->getModuleParams();

		$registry = new JRegistry;
		$registry->loadString($params);
		
		return $registry->get('show_type',1);
	}

	public function getModuleId() {
		$module_id = JFactory::getApplication()->input->getInt('module_id',0);
		if ( !$module_id )
			$module_id = $this->getPageParams()->get('module_id');

		return $module_id;
	}
	
	protected function getModuleParams() {
		static $params 	= array();
		$mid			= $this->getModuleId();
		
		if ( !isset($params[$mid]) ) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select($db->qn('params'))->from($db->qn('#__modules'))->where($db->qn('id').' = '.$db->q($mid));
			$db->setQuery($query);
			$params[$mid] = $db->loadResult();
		}

		return $params[$mid];
	}

	public static function getPageParams() {
		$app	= JFactory::getApplication();
		$itemid = $app->input->getInt('Itemid',0);
		$params = null;
		
		if ($app->isAdmin()) {
			return new JRegistry;
		}
		
		if ($itemid) {
			$menu = $app->getMenu();
			$active = $menu->getItem($itemid);
			if ($active) {
				$params = $active->params;
			}
		}
		
		if (empty($params)) {
			$params = $app->getParams();
		}
		
		return $params;
	}
	
}