<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class rsseoController extends JControllerLegacy
{
	/**
	 *	Main constructor
	 *
	 * @return void
	 */
	public function __construct() {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/rsseo.php';
		
		parent::__construct();
	}
	
	public function keywords() {
		$config = rsseoHelper::getConfig();
		
		// Can we run the cron ?
		if (!$config->eanble_k_cron) {
			return;
		}
		
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$limit		= 3;
		$cron_run	= $config->k_cron_run;
		$secret		= JFactory::getConfig()->get('secret');
		
		// Get the time period
		if ($cron_run == 'daily') {
			$start = JFactory::getDate();
			$start->setTime(0,0,0);
			$end = JFactory::getDate();
			$end->setTime(23,59,59);
		} elseif ($cron_run == 'weekly') {
			$start = JFactory::getDate();
			$start->modify('this monday');
			$start->setTime(0,0,0);
			$end = JFactory::getDate();
			$end->modify('this sunday');
			$end->setTime(23,59,59);
			
			if ($start >= $end) {
				$start = JFactory::getDate('now');
				$start->modify('previous monday');
				$start->setTime(0,0,0);
			}
		} elseif ($cron_run == 'monthly') {
			$start = JFactory::getDate();
			$start->modify('first day of this month');
			$start->setTime(0,0,0);
			$end = JFactory::getDate();
			$end->modify('last day of this month');
			$end->setTime(23,59,59);
		}
		
		$query->select('*')
			->from($db->qn('#__rsseo_gkeywords'))
			->where($db->qn('lastcheck').' NOT BETWEEN CAST('.$db->q($start->toSql()).' AS DATETIME) AND CAST('.$db->q($end->toSql()).' AS DATETIME)');
		$db->setQuery($query, 0, $limit);
		if ($keywords = $db->loadObjectList()) {
			
			try {
				require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/gapi.php';
				
				$options = array(
					'email'		=> $config->accountID,
					'scope'		=> 'https://www.googleapis.com/auth/webmasters.readonly',
					'key'		=> file_get_contents(JPATH_ADMINISTRATOR.'/components/com_rsseo/assets/keys/'.md5($secret.'private_key').'.p12')
				);
				
				$gapi = rsseoGoogleAPI::getInstance($options);
				
				foreach ($keywords as $keyword) {
					$query->clear()
						->select('DISTINCT '.$db->qn('date'))
						->from($db->qn('#__rsseo_gkeywords_data'))
						->where($db->qn('idk').' = '.$db->q($keyword->id));
					$db->setQuery($query);
					$datesWithData = $db->loadColumn();
					
					$dates	= array();
					$from	= JFactory::getDate()->modify('-90 days');
					$to		= JFactory::getDate()->modify('-4 days');
					
					while ($to >= $from) {
						$date	= $from->format('Y-m-d');
						if (!in_array($date, $datesWithData)) {
							$dates[] = $date;
						}
						$from->modify('+1 days');
					}
					
					if ($date = current($dates)) {
						try {						
							$options = array('keyword' => $keyword->name, 'site' => $keyword->site, 'start' => $date, 'end' => $date);
							if ($data = $gapi->getSearchData($options)) {
								foreach ($data as $object) {
									$query->clear()
										->insert($db->qn('#__rsseo_gkeywords_data'))
										->set($db->qn('idk').' = '.$db->q($keyword->id))
										->set($db->qn('date').' = '.$db->q($date))
										->set($db->qn('page').' = '.$db->q($object->keys[1]))
										->set($db->qn('device').' = '.$db->q($object->keys[2]))
										->set($db->qn('country').' = '.$db->q($object->keys[3]))
										->set($db->qn('clicks').' = '.$db->q($object->clicks))
										->set($db->qn('impressions').' = '.$db->q($object->impressions))
										->set($db->qn('ctr').' = '.$db->q($object->ctr))
										->set($db->qn('position').' = '.$db->q($object->position));
									
									$db->setQuery($query);
									$db->execute();
								}
							}
						} catch (Exception $e) {}
					}
					
					$query->clear()
						->update($db->qn('#__rsseo_gkeywords'))
						->set($db->qn('lastcheck').' = '.$db->q(JFactory::getDate()->toSql()))
						->where($db->qn('id').' = '.$db->q($keyword->id));
					$db->setQuery($query);
					$db->execute();
				}
			} catch (Exception $e) {
				JFactory::getLanguage()->load('com_rsseo',JPATH_ADMINISTRATOR);
				rsseoHelper::saveLog('gkeywords', JText::sprintf('COM_RSSEO_LOG_MESSAGE', $e->getMessage(), __FILE__, __LINE__));
			}
		}
	}
	
	public function save() {
		$app		= JFactory::getApplication();
		$plugin		= JPluginHelper::getPlugin('system', 'rsseo');
		$registry	= new JRegistry;
		$registry->loadString($plugin->params);
		
		if ($allowed = $registry->get('frontend_seo_groups','')) {
			$allowed = array_map('intval', $allowed);
			$groups  = JFactory::getUser()->getAuthorisedGroups();
			
			if (array_intersect($allowed, $groups)) {
				JFactory::getLanguage()->load('plg_system_rsseo',JPATH_ADMINISTRATOR);
				
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true);
				$data	= $app->input->get('jform', array(), 'array');
				$url	= $data['url'];
				$url	= str_replace(array('&amp;','&apos;','&quot;','&gt;','&lt;'),array("&","'",'"',">","<"),$url);
				$url	= str_replace(array("&","'",'"',">","<"),array('&amp;','&apos;','&quot;','&gt;','&lt;'),$url);
				$url	= trim($url);
				
				$registry = new JRegistry;
				$registry->loadArray($data['robots']);
				$robots = (string) $registry;
				
				$query->select($db->qn('id'))
					->from($db->qn('#__rsseo_pages'))
					->where($db->qn('url').' = '.$db->q($url));
				$db->setQuery($query);
				if ($pageID = (int) $db->loadResult()) {
					$query->clear()
						->update($db->qn('#__rsseo_pages'))
						->set($db->qn('title').' = '.$db->q($data['title']))
						->set($db->qn('keywords').' = '.$db->q($data['keywords']))
						->set($db->qn('description').' = '.$db->q($data['description']))
						->set($db->qn('robots').' = '.$db->q($robots))
						->set($db->qn('crawled').' = '.$db->q(1))
						->where($db->qn('id').' = '.$db->q($pageID));
					$db->setQuery($query);
					$db->execute();
				} else {
					$query->clear()
						->insert($db->qn('#__rsseo_pages'))
						->set($db->qn('url').' = '.$db->q($url))
						->set($db->qn('hash').' = '.$db->q(md5($url)))
						->set($db->qn('title').' = '.$db->q($data['title']))
						->set($db->qn('keywords').' = '.$db->q($data['keywords']))
						->set($db->qn('description').' = '.$db->q($data['description']))
						->set($db->qn('date').' = '.$db->q(JFactory::getDate()->toSql()))
						->set($db->qn('robots').' = '.$db->q($robots))
						->set($db->qn('crawled').' = '.$db->q(1))
						->set($db->qn('published').' = '.$db->q(1));
					$db->setQuery($query);
					$db->execute();
				}
				
				echo JText::_('RSSEO_EDIT_PAGE_SAVED');
			}
		}

		$app->close();
	}
}