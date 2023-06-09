<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/version.php';

class rsseoHelper {
	
	// Get component configuration
	public static function getConfig($name = null, $default = null) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		static $config;
		
		if (empty($config)) {
			$query->clear();
			$query->select($db->qn('params'));
			$query->from($db->qn('#__extensions'));
			$query->where($query->qn('type') . ' = ' . $db->q('component'));
			$query->where($query->qn('element') . ' = ' . $db->q('com_rsseo'));
			$db->setQuery($query);
			$params = $db->loadResult();
			
			$registry = new JRegistry;
			$registry->loadString($params);
			$config = $registry->toObject();
		}
		
		if ($name != null) {
			if (isset($config->$name)) { 
				return $config->$name;
			} else {
				if (!is_null($default))
					return $default;
				else
					return false;
			}
		}
		else return $config;
	}
	
	// Update configuration
	public static function updateConfig($name, $value) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select($db->qn('extension_id'))->select($db->qn('params'))
			->from($db->qn('#__extensions'))
			->where($query->qn('type') . ' = ' . $db->q('component'))
			->where($query->qn('element') . ' = ' . $db->q('com_rsseo'));
		$db->setQuery($query);
		if ($extension = $db->loadObject()) {
			$registry = new JRegistry;
			$registry->loadString($extension->params);
			$registry->set($name, $value);
				
			$query->clear()
				->update($db->qn('#__extensions'))
				->set($db->qn('params'). ' = '.$db->q((string) $registry->toString()))
				->where($db->qn('extension_id'). ' = '. $db->q($extension->extension_id));
			
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	// Get key code for update
	public static function genKeyCode() {
		$code = rsseoHelper::getConfig('global_register_code');
		$version = new RSSeoVersion();
		return md5($code.$version->key);
	}
	
	// Check for Joomla! version
	public static function isJ3() {
		return version_compare(JVERSION, '3.0', '>=');
	}
	
	// Load jQuery
	public static function loadjQuery($noconflict = true) {
		$enabled = rsseoHelper::getConfig('load_jquery',1);
		
		if ($enabled) {
			JHtml::_('jquery.framework', $noconflict);
		}
	}
	
	// Add backend submenus
	public static function addSubmenu($vName) {
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_DASHBOARD'),		'index.php?option=com_rsseo',						$vName == '' || $vName == 'default');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_SEO_PERFORMANCE'),	'index.php?option=com_rsseo&view=competitors',		$vName == 'competitors');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_PAGES'),			'index.php?option=com_rsseo&view=pages',			$vName == 'pages');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_CRAWLER'),			'index.php?option=com_rsseo&view=crawler',			$vName == 'crawler');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_SITEMAP' ),			'index.php?option=com_rsseo&view=sitemap',			$vName == 'sitemap');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_ROBOTS' ),			'index.php?option=com_rsseo&view=robots',			$vName == 'robots');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_ERRORS'),			'index.php?option=com_rsseo&view=errors',			$vName == 'errors');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_ERROR_LINKS'),		'index.php?option=com_rsseo&view=errorlinks',		$vName == 'errorlinks');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_REDIRECTS'),		'index.php?option=com_rsseo&view=redirects',		$vName == 'redirects');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_KEYWORDS' ),		'index.php?option=com_rsseo&view=keywords',			$vName == 'keywords');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_GKEYWORDS'),		'index.php?option=com_rsseo&view=gkeywords',		$vName == 'gkeywords');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_BACKUP_RESTORE'),	'index.php?option=com_rsseo&view=backup',			$vName == 'backup');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_ANALYTICS'),		'index.php?option=com_rsseo&view=analytics',		$vName == 'analytics');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_STRUCTURED_DATA'),	'index.php?option=com_rsseo&view=data',				$vName == 'data');
		JHtmlSidebar::addEntry(JText::_('COM_RSSEO_MENU_STATISTICS'),		'index.php?option=com_rsseo&view=statistics',		$vName == 'statistics');
	}
	
	// Set scripts and stylesheets
	public static function setScripts($from) {
		$doc	= JFactory::getDocument();
		$tmpl	= JFactory::getApplication()->input->get('tmpl') == 'component';
		
		if ($from == 'administrator') {
			JHtml::_('bootstrap.tooltip');
			
			if (!file_exists(JPATH_SITE.'/components/com_rsseo/sef.php')) {
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_RSSEO_NO_ROUTER_FILE', realpath(JPATH_SITE.'/components/com_rsseo/sef.php')),'error');
			}
			
			if (!$tmpl) {
				JHtml::_('formbehavior.chosen', 'select');
			}
			
			// Load jQuery
			self::loadjQuery();
			
			JHtml::script('com_rsseo/admin.js', array('relative' => true, 'version' => 'auto'));
			JHtml::stylesheet('com_rsseo/admin.css', array('relative' => true, 'version' => 'auto'));
			JHtml::stylesheet('com_rsseo/font-awesome.min.css', array('relative' => true, 'version' => 'auto'));
		}
	}
	
	// Main function to get content
	public static function fopen($url, $headers = 1, $test = false, $onlyHeaders = false, $crawl = false) {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/http.php';
		
		$config	= rsseoHelper::getConfig();
		$proxy	= false;
		
		if ($config->proxy_enable) {
			$proxy = array();
			$proxy['proxy_server'] = $config->proxy_server;
			$proxy['proxy_port'] = $config->proxy_port;
			$proxy['proxy_usrpsw'] = $config->proxy_username.':'.$config->proxy_password;
		}
		
		$options	= array('test' => $test, 'url' => $url, 'proxy' => $proxy, 'crawl' => $crawl);
		$http		= rsseoHttp::getInstance($options);
		
		if ($onlyHeaders) {
			return $http->getStatus();
		}
		
		if ($test) {
			return $http->getErrors();
		}
		
		$response 	= $http->getResponse();
		
		if (empty($response)) {
			return 'RSSEOINVALID';
		}
		
		return $response;
	}
	
	// Convert time in a readable format
	public static function convertseconds($sec) {
		$text = '';

		$hours = intval(intval($sec) / 3600); 
		$text .= str_pad($hours, 2, "0", STR_PAD_LEFT). ":";

		$minutes = intval(($sec / 60) % 60); 
		$text .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

		$seconds = intval($sec % 60); 
		$text .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

		return $text;
	}
	
	// Convert a timestamp to a years-days format
	public static function convertage($time) {
		$years	= floor($time / 31556926);
		$days	= floor(($time % 31556926) / 86400);
		
		if ($years == '1') {
			$y = '1 '.JText::_('COM_RSSEO_YEAR');
		} else {
			$y = $years.' '.JText::_('COM_RSSEO_YEARS');
		}
		
		if ($days == '1') {
			$d = '1 '.JText::_('COM_RSSEO_DAY');
		} else {
			$d = $days.' '.JText::_('COM_RSSEO_DAYS');
		}
		
		return $y.', '.$d;
	}
	
	// Copy keywords to density keywords
	public static function keywords() {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$config = rsseoHelper::getConfig();
		
		if ($config->copykeywords) {
			$query->clear();
			$query->update($db->qn('#__rsseo_pages'))->set($db->qn('keywordsdensity').' = '.$db->qn('keywords'));
			
			if (!$config->overwritekeywords)
				$query->where($db->qn('keywordsdensity').' = '.$db->q(''));
			
			$db->setQuery($query);
			if ($db->execute()) {
				$component	= JComponentHelper::getComponent('com_rsseo');
				$cparams	= $component->params;
				
				if ($cparams instanceof JRegistry) {
					$cparams->set('copykeywords', 0);
					$cparams->set('overwritekeywords', 0);
					$query->clear();
					$query->update($db->qn('#__extensions'));
					$query->set($db->qn('params'). ' = '.$db->q((string) $cparams));
					$query->where($db->qn('extension_id'). ' = '. $db->q($component->id));
					
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
	
	// Check broken URLs
	public static function checkBroken($id, $pageId) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$response	= array('finished' => 1);
		
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/phpQuery.php';
		
		// Get all internal/external links
		if (!$pageId) {
			$query->select($db->qn('url'))
				->from($db->qn('#__rsseo_pages'))
				->where($db->qn('id').' = '.(int) $id);
			$db->setQuery($query);
			$url = $db->loadResult();
			
			$url		= JURI::root().$url;
			$url		= str_replace(' ','%20',$url);
			$contents	= rsseoHelper::fopen($url,1);
			
			if (strpos($contents,'<html') === false || (strpos($contents,'RSSEOINVALID') !== false && $url != '')) {
				return json_encode($response);
			}
			
			$contents	= preg_replace('#<script.*?>.*?</script>#is','',$contents);
			$dom		= phpQuery::newDocumentHTML($contents);
			
			$query->clear()
				->delete($db->qn('#__rsseo_broken_links'))
				->where($db->qn('pid').' = '.(int) $id);
			$db->setQuery($query);
			$db->execute();
			
			$brokenLinks = array();
			foreach ($dom->find('a') as $href) {
				$href = phpQuery::pq($href)->attr('href');
				if ($href = rsseoHelper::getUrl($href)) {
					$brokenLinks[]  = $href;
				}
			}
			
			if ($brokenLinks = array_unique($brokenLinks)) {
				foreach ($brokenLinks as $brokenLink) {
					$query->clear()
						->insert($db->qn('#__rsseo_broken_links'))
						->set($db->qn('pid').' = '.(int) $id)
						->set($db->qn('url').' = '.$db->q($brokenLink))
						->set($db->qn('published').' = 0');
					
					$db->setQuery($query);
					$db->execute();
				}
				
				$query->clear()
					->select($db->qn('id'))
					->from($db->qn('#__rsseo_broken_links'))
					->where($db->qn('published').' = 0')
					->where($db->qn('pid').' = '.(int) $id);
				
				$db->setQuery($query,0,1);
				$nextId = (int) $db->loadResult();
				
				if ($nextId) {
					$response['finished']	= 0;
					$response['id']			= $nextId;
					$response['percent']	= 0;
				}
				
				return json_encode($response);
			} else {
				return json_encode($response);
			}
		} else {
			// Check URL's
			$query->clear()
				->select($db->qn('url'))
				->from($db->qn('#__rsseo_broken_links'))
				->where($db->qn('id').' = '.(int) $pageId);
			$db->setQuery($query);
			$currentUrl = $db->loadResult();
			
			$code = rsseoHelper::fopen($currentUrl, 0, false, true);
			
			$query->clear()
				->update($db->qn('#__rsseo_broken_links'))
				->where($db->qn('id').' = '.(int) $pageId);
			
			if (intval($code) == 200) {
				$query->set($db->qn('published').' = '.$db->q('-1'));
			} else {
				$query->set($db->qn('code').' = '.$db->q($code));
				$query->set($db->qn('published').' = '.$db->q(1));
			}
			
			$db->setQuery($query);
			$db->execute();
			
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rsseo_broken_links'))
				->where($db->qn('pid').' = '.(int) $id);
			
			$db->setQuery($query);
			$total = (int) $db->loadResult();
			
			$query->clear()
				->select('COUNT('.$db->qn('id').')')
				->from($db->qn('#__rsseo_broken_links'))
				->where($db->qn('published').' = 0')
				->where($db->qn('pid').' = '.(int) $id);
			
			$db->setQuery($query);
			$remaining = (int) $db->loadResult();
			
			$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__rsseo_broken_links'))
				->where($db->qn('published').' = 0')
				->where($db->qn('pid').' = '.(int) $id);
			
			$db->setQuery($query,0,1);
			$nextId = (int) $db->loadResult();
			
			if ($nextId) {
				$response['finished']	= 0;
				$response['id']			= $nextId;
				$response['percent']	= ceil(($total - $remaining) * 100 / $total);
			}
			
			return json_encode($response);
		}
	}
	
	// Correctly build the URL
	public static function getUrl($url) {
		// Skip unwanted links
		if (strpos($url,'mailto:') !== FALSE) return false;
		if (strpos($url,'javascript:') !== FALSE) return false;
		if (strpos($url,'ymsgr:im') !== FALSE) return false;
		if (substr($url,0,1) == '#') return false;
		
		$uri	= JURI::getInstance();
		$root	= JURI::root();
		$base	= JURI::root(true);
		$site	= $uri->toString(array('scheme','host'));
		
		// Internal link
		if (substr($url,0,4) == 'http' && strpos($url,$root) !== false)
			return $url;
		
		// External link
		if (substr($url,0,4) == 'http' && strpos($url,$root) === false)
			return $url;
		
		// Internal link
		if (substr($url,0,strlen($base)) == $base)
			return $site.$url;
		
		// Internal link
		if (substr($url,0,9) == 'index.php')
			return $root.$url;
		
		return $url;
	}
	
	// Get error message
	public static function getError($code) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('*')
			->from($db->qn('#__rsseo_errors'))
			->where($db->qn('error').' = '.(int) $code)
			->where($db->qn('published').' = 1');
		
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	// Log errors
	public static function saveURL($code) {
		$db		 = JFactory::getDbo();
		$query	 = $db->getQuery(true);
		$url	 = (string) JURI::getInstance();
		$enable	 = rsseoHelper::getConfig('log_errors',1);
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$crawl	 = isset($_SERVER['HTTP_X_RSSEO_CRAWLER']) ? (int) $_SERVER['HTTP_X_RSSEO_CRAWLER'] : 0;
		
		if ($crawl || !$enable) {
			return false;
		}
		
		$query->select($db->qn('id'))
			->from($db->qn('#__rsseo_error_links'))
			->where($db->qn('url').' = '.$db->q($url));
		$db->setQuery($query);
		$id = (int) $db->loadResult();
		
		if ($id) {
			$query->clear()
				->update($db->qn('#__rsseo_error_links'))
				->set($db->qn('count').' = '.$db->qn('count').' + 1')
				->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			$db->execute();
		} else {
			$query->clear()
				->insert($db->qn('#__rsseo_error_links'))
				->set($db->qn('url').' = '.$db->q($url))
				->set($db->qn('code').' = '.$db->q($code))
				->set($db->qn('count').' = 1');
			$db->setQuery($query);
			$db->execute();
			$id = $db->insertid();
		}
		
		if ($referer) {
			$query->clear()
				->insert($db->qn('#__rsseo_error_links_referer'))
				->set($db->qn('idl').' = '.$db->q($id))
				->set($db->qn('referer').' = '.$db->q($referer))
				->set($db->qn('date').' = '.$db->q(JFactory::getDate()->toSql()));
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	// Get a list of response codes
	public static function getResponseMessage($code) {
		$http_status_codes = array(	100 => "Continue", 
									101 => "Switching Protocols", 
									102 => "Processing", 
									200 => "OK", 
									201 => "Created", 
									202 => "Accepted", 
									203 => "Non-Authoritative Information", 
									204 => "No Content", 
									205 => "Reset Content", 
									206 => "Partial Content", 
									207 => "Multi-Status", 
									300 => "Multiple Choices", 
									301 => "Moved Permanently", 
									302 => "Found", 
									303 => "See Other", 
									304 => "Not Modified", 
									305 => "Use Proxy", 
									306 => "(Unused)", 
									307 => "Temporary Redirect", 
									308 => "Permanent Redirect", 
									400 => "Bad Request", 
									401 => "Unauthorized", 
									402 => "Payment Required", 
									403 => "Forbidden", 
									404 => "Not Found", 
									405 => "Method Not Allowed", 
									406 => "Not Acceptable", 
									407 => "Proxy Authentication Required", 
									408 => "Request Timeout", 
									409 => "Conflict", 
									410 => "Gone", 
									411 => "Length Required", 
									412 => "Precondition Failed", 
									413 => "Request Entity Too Large", 
									414 => "Request-URI Too Long", 
									415 => "Unsupported Media Type", 
									416 => "Requested Range Not Satisfiable", 
									417 => "Expectation Failed", 
									418 => "I'm a teapot", 
									419 => "Authentication Timeout", 
									420 => "Enhance Your Calm", 
									422 => "Unprocessable Entity", 
									423 => "Locked", 
									424 => "Failed Dependency", 
									424 => "Method Failure", 
									425 => "Unordered Collection", 
									426 => "Upgrade Required", 
									428 => "Precondition Required", 
									429 => "Too Many Requests", 
									431 => "Request Header Fields Too Large", 
									444 => "No Response", 
									449 => "Retry With", 
									450 => "Blocked by Windows Parental Controls", 
									451 => "Unavailable For Legal Reasons", 
									494 => "Request Header Too Large", 
									495 => "Cert Error", 
									496 => "No Cert", 
									497 => "HTTP to HTTPS", 
									499 => "Client Closed Request", 
									500 => "Internal Server Error", 
									501 => "Not Implemented", 
									502 => "Bad Gateway", 
									503 => "Service Unavailable", 
									504 => "Gateway Timeout", 
									505 => "HTTP Version Not Supported", 
									506 => "Variant Also Negotiates", 
									507 => "Insufficient Storage", 
									508 => "Loop Detected", 
									509 => "Bandwidth Limit Exceeded", 
									510 => "Not Extended", 
									511 => "Network Authentication Required", 
									598 => "Network read timeout error", 
									599 => "Network connect timeout error",
									0 => "Unknown error"
								);
		return isset($http_status_codes[$code]) ? $http_status_codes[$code] : '';
	}
	
	// Get statistics
	public static function getStatistics() {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$url		= JURI::root();
		$statistics = array();
		$canrun		= false;
		
		$query->clear()
			->select('*')
			->from($db->qn('#__rsseo_statistics'));
		$db->setQuery($query);
		$statistics = (array) $db->loadObject();
		
		if ($statistics) {
			if (JFactory::getDate($statistics['date'])->toUnix() + 86400 < JFactory::getDate()->toUnix()) {
				$canrun = true;
			} else {
				unset($statistics['id']);
				return $statistics;
			}
		} else {
			$canrun = true;
		}
		
		if ($canrun) {
			require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/competitors.php';
			$competitors = competitorsHelper::getInstance(null, $url, true);
			$competitor = $competitors->check();
			return $competitor;
		}
	}
	
	// Get the most visited pages
	public static function getMostVisited() {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->select($db->qn('id'))->select($db->qn('url'))
			->select($db->qn('sef'))->select($db->qn('hits'))
			->from($db->qn('#__rsseo_pages'))
			->where($db->qn('hits').' > 0')
			->order($db->qn('hits').' DESC');
		
		$db->setQuery($query,0,10);
		return $db->loadObjectList();
	}
	
	// Set the sitemap cron function
	public static function cronSitemap() {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		$config		= rsseoHelper::getConfig();

		try {

			if (!file_exists(JPATH_SITE . '/sitemap.xml') && !file_exists(JPATH_SITE . '/ror.xml')) {
				throw new Exception(JText::_('COM_RSSEO_SITEMAP_MISSING_FILES'));
			}

			$query->clear()
				->select($db->qn('id'))->select($db->qn('url'))->select($db->qn('sef'))->select($db->qn('title'))
				->select($db->qn('level'))->select($db->qn('priority'))->select($db->qn('frequency'))
				->from($db->qn('#__rsseo_pages'))
				->where($db->qn('sitemap') . ' = 0')
				->where($db->qn('insitemap') . ' = 1')
				->where($db->qn('published') . ' != -1')
				->where($db->qn('canonical') . ' = ' . $db->q(''))
				->order($db->qn('level'));
			$db->setQuery($query, 0, 250);

			if ($pages = $db->loadObjectList()) {
				require_once JPATH_ADMINISTRATOR . '/components/com_rsseo/helpers/sitemap.php';
				$protocol = isset($config->sitemapprotocol) ? $config->sitemapprotocol : 0;
				$sitemap = sitemapHelper::getInstance(0, $protocol, JHtml::_('date', 'NOW', 'Y-m-d'), $config->sitemapauto);

				if ((file_exists(JPATH_SITE . '/sitemap.xml') && filesize(JPATH_SITE . '/sitemap.xml') < 99) || (file_exists(JPATH_SITE . '/ror.xml') && filesize(JPATH_SITE . '/ror.xml') < 103)) {
					$sitemap->clear();
					$sitemap->setHeader(true);
					$sitemap->close();
				}

				foreach ($pages as $page) {
					$page->url = rsseoHelper::showURL($page->url, $page->sef);
					
					$sitemap->add($page, true);

					$query->clear()
						->update($db->qn('#__rsseo_pages'))
						->set($db->qn('sitemap') . ' = 1')
						->where($db->qn('id') . ' = ' . $db->q($page->id));
					$db->setQuery($query);
					$db->execute();
				}
			}
		} catch (Exception $e) {
			//echo $e->getMessage(); Perhaps send an email to the admin in a future version?
		}
	}
	
	// Get IP address
	public static function getIP($check_for_proxy = false) {
		$ip = $_SERVER['REMOTE_ADDR'];

		if ($check_for_proxy) {
			$headers = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM');
			foreach ($headers as $header)
				if (!empty($_SERVER[$header]))
					$ip = $_SERVER[$header];
		}

		return $ip;
	}
	
	// Check if a url is internal or not
	public static function isInternal($url) {
		$uri = JUri::getInstance($url);
		$base = $uri->toString(array('scheme', 'host', 'port', 'path'));
		$host = $uri->toString(array('scheme', 'host', 'port'));

		if (stripos($base, JUri::base()) !== 0 && !empty($host)) {
			return false;
		}
		
		return true;
	}
	
	// Check for a valid chmod permission
	public static function validatePermission($string, $default = '644') {
		return preg_match('/^[0-7]{3}$/', $string) ? $string : $default;
	}
	
	// Check if the IP address is IPV4
	public static function isIPV4($ip) {
		if (defined('FILTER_VALIDATE_IP') && defined('FILTER_FLAG_IPV4')) {
			return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
		} else {
			return (strpos($ip, '.') !== false && strpos($ip, ':') === false);
		}
	}
	
	// Check if the IP address is IPV6
	public static function isIPV6($ip) {
		if (defined('FILTER_VALIDATE_IP') && defined('FILTER_FLAG_IPV6')) {
			return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
		} else {
			return (strpos($ip, ':') !== false);
		}
	}
	
	// Obfuscate IP address
	public static function obfuscateIP($ip) {
		if ($ip == '::1' || $ip == '127.0.0.1') {
			return 'localhost';
		}
		
		if (!rsseoHelper::getConfig('obfuscate_visitor_ip', 0)) {
			return $ip;
		}
		
		if (strpos($ip,'%') !== false) {
			$ip = substr_replace($ip, '', strpos($ip,'%'), strlen($ip));
		}
		
		if (rsseoHelper::isIPV4($ip)) {
			list($p1, $p2, $p3, $p4) = explode('.', $ip);
			$p4 = 'x';
			
			return $p1.'.'.$p2.'.'.$p3.'.'.$p4;
		} elseif (rsseoHelper::isIPV6($ip)) {
			$parts = explode(':', $ip);
			array_pop($parts);
			$parts[] = '---';
			
			return implode(':',$parts);
		} else {
			return $ip;
		}
	}
	
	// Show custom URL for page
	public static function showURL($url, $custom) {
		$db		= JFactory::getDbo();
		$config = JFactory::getConfig();
		$lang	= '';
		
		if (!rsseoHelper::getConfig('enable_sef')) {
			return $url;
		}
		
		$query = $db->getQuery(true)->select($db->qn('published'))->from($db->qn('#__rsseo_pages'))->where($db->qn('url').' = '.$db->q($url));
		$db->setQuery($query);
		if ($published = $db->loadResult()) {
			if ($config->get('sef')) {
				// If no custom SEF URL , then return the page URL
				if (empty($custom)) {
					return $url;
				}
				
				if (JPluginHelper::isEnabled('system','languagefilter')) {
					$lang_codes = JLanguageHelper::getContentLanguages();
					$parts 		= explode('/',$url);
					$codes	 	= array();
					
					foreach ($lang_codes as $code) {
						$codes[] = $code->sef;
					}
					
					if (in_array($parts[0], $codes)) {
						$lang = $parts[0];
					}
				}
				
				// Check for the .htaccess file
				if (!file_exists(JPATH_SITE.'/.htaccess')) {
					$custom = 'index.php/'.($lang ? $lang.'/' : '').$custom;
				} else {
					if ($lang) {
						$custom = $lang.'/'.$custom;
					}
				}
				
				// Append the SEF suffix
				if ($config->get('sef_suffix')) {
					$custom .= '.html';
				}
				
				return $custom;
			}
		}
		
		return $url;
	}
	
	// Get a page custom SEF URL
	public static function getSEF($url) {
		static $SEFurls = array();
		
		$db		= JFactory::getDbo();
		$config = JFactory::getConfig();
		$hash	= md5($url);
		$lang	= '';
		
		if (!rsseoHelper::getConfig('enable_sef')) {
			return $url;
		}
		
		if (!isset($SEFurls[$hash])) {
			$query = $db->getQuery(true)->select($db->qn('sef'))->from($db->qn('#__rsseo_pages'))->where($db->qn('hash').' = '.$db->q($hash))->where($db->qn('published').' = '.$db->q(1));
			$db->setQuery($query);
			if ($sef = $db->loadResult()) {
				if ($config->get('sef')) {
					
					if (JPluginHelper::isEnabled('system','languagefilter')) {
						$lang_codes = JLanguageHelper::getContentLanguages();
						$parts 		= explode('/',$url);
						$codes	 	= array();
						
						foreach ($lang_codes as $code) {
							$codes[] = $code->sef;
						}
						
						if (in_array($parts[0], $codes)) {
							$lang = $parts[0];
						}
					}
					
					// Check for the .htaccess file
					if (!file_exists(JPATH_SITE.'/.htaccess')) {
						$sef = 'index.php/'.($lang ? $lang.'/' : '').$sef;
					} else {
						if ($lang) {
							$sef = $lang.'/'.$sef;
						}
					}
					
					// Append the SEF suffix
					if ($config->get('sef_suffix')) {
						$sef .= '.html';
					}
					
					$SEFurls[$hash] = $sef;
				}
			} else {
				$SEFurls[$hash] = $url;
			}
		}
		
		return $SEFurls[$hash];
	}
	
	// Save log
	public static function saveLog($type, $message) {
		$db			= JFactory::getDbo();
		$query		= $db->getQuery(true);
		
		$query->insert($db->qn('#__rsseo_logs'))
			->set($db->qn('type').' = '.$db->q($type))
			->set($db->qn('date').' = '.$db->q(JFactory::getDate()->toSql()))
			->set($db->qn('message').' = '.$db->q($message));
			
		$db->setQuery($query);
		$db->execute();
	}
}