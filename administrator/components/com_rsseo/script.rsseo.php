<?php
/**
* @version 1.0.0
* @package RSSeo! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class com_rsseoInstallerScript 
{
	public function install($parent) {}
	
	public function preflight($type, $parent) {
		$app		= JFactory::getApplication();
		$jversion	= new JVersion();
		
		if (!$jversion->isCompatible('3.8.0')) {
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.8.0 before continuing!', 'error');
			return false;
		}
		
		return true;
	}

	public function postflight($type, $parent) {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->clear();
		$query->select($db->qn('extension_id'))->from($db->qn('#__extensions'))->where($db->qn('element').' = '.$db->quote('com_rsseo'))->where($db->qn('type').' = '.$db->quote('component'));
		$db->setQuery($query);
		$extension_id = $db->loadResult();
		
		if ($type == 'install') {
			// Add default configuration when installing the first time RSSeo!
			if ($extension_id) {
				$default = '{"global_register_code":"","global_dateformat":"d M y H:i","load_jquery":"1","log_errors":"1","custom_errors":"0","track_visitors":"0","obfuscate_visitor_ip":"0","robots_permissions":"644","title_length":"50","keywords_length":"10","description_length":"150","google_domain":"google.com","enable_age":"1","enable_googlep":"1","enable_googleb":"1","enable_googler":"1","enable_bingp":"1","enable_bingb":"1","enable_alexa":"1","enable_moz":"1","moz_access_id":"","moz_secret":"","analytics_enable":"0","analytics_client_id":"","analytics_secret":"","ga_tracking":"0","ga_type":"0","ga_code":"","request_timeout":"0","crawler_type":"loopback","crawler_enable_auto":"0","crawler_save":"1","crawler_nofollow":"0","crawler_level":"2","site_name_in_title":"0","site_name_separator":"|","crawler_sef":"1","crawler_title_duplicate":"1","crawler_title_length":"1","crawler_description_duplicate":"1","crawler_description_length":"1","crawler_keywords":"1","crawler_headings":"1","crawler_images":"1","crawler_images_alt":"1","crawler_images_hw":"1","crawler_intext_links":"1","crawler_ignore":"{*}tmpl=component{*}\r\n{*}format=pdf{*}\r\n{*}format=feed{*}\r\n{*}output=pdf{*}\r\n{*}?gclid={*}","exclude_noindex":"0","sitemap_permissions":"644","enable_sitemap_cron":"0","sitemap_cron_type":"0","sitemap_cron_security":"707cb49519002c0b36d6ae726aa02589","sitemap_autocrawled":"2","sitemap_autocrawled_rule":"","enable_keyword_replace":"1","approved_chars":",;:.?!$%*&()[]{} ><","keyword_density_enable":"1","copykeywords":"0","overwritekeywords":"0","eanble_k_cron":"0","k_cron_run":"weekly","subdomains":"","proxy_enable":"0","proxy_server":"","proxy_port":"","proxy_username":"","proxy_password":"","img_auto_alt":"0","img_auto_alt_rule":"{name} {title}","img_auto_title":"0","img_auto_title_rule":"{name} image","sitemapauto":"0","sitemapprotocol":"0","ga_account":"","ga_start":"","ga_end":"","ga_token":"","sitemap_menus":"YToxOntpOjA7czo4OiJtYWlubWVudSI7fQ==","sitemap_excludes":"YTowOnt9","sitemap_timestamp":"0","enable_sef":"1","ga_options":""}';
				
				$query->clear();
				$query->update($db->qn('#__extensions'))->set($db->qn('params').' = '.$db->quote($default))->where($db->qn('extension_id').' = '.(int) $extension_id);
				$db->setQuery($query);
				$db->execute();
			}
		}
		
		if ($type == 'update' || $type == 'install') {
			// Get a new installer
			$installer = new JInstaller();
			
			// Install the system plugin
			$installer->install($parent->getParent()->getPath('source').'/extra/plugins/rsseo');
			// Install the installer plugin
			$installer->install($parent->getParent()->getPath('source').'/extra/plugins/installer');
			
			$query->clear();
			$query->select($db->qn('ordering'))->from($db->qn('#__extensions'))->where('`element` = '.$db->q('redirect'))->where('`type` = '.$db->q('plugin'))->where('`folder` = '.$db->quote('system'));
			$db->setQuery($query);
			$ordering = (int) $db->loadResult();
			
			$query->clear();
			$query->update('`#__extensions`')->set('`enabled` = 1')->where('`element` = '.$db->quote('rsseo'))->where('`type` = '.$db->quote('plugin'))->where('`folder` = '.$db->quote('system'));
			
			if ($ordering)
				$query->set($db->qn('ordering').' = '.$db->q($ordering + 1));
			
			$db->setQuery($query);
			$db->execute();
			
			$query->clear();
			$query->update('`#__extensions`')->set('`enabled` = 1')->where('`element` = '.$db->quote('rsseo'))->where('`type` = '.$db->quote('plugin'))->where('`folder` = '.$db->quote('installer'));
			
			$db->setQuery($query);
			$db->execute();
			
			$this->runSql(JPATH_ADMINISTRATOR.'/components/com_rsseo/install.mysql.utf8.sql');
		}
		
		if ($type == 'update') {
			// We only need to run this update query on Joomla! 2.5
			if (!version_compare(JVERSION, '3.0', '>=')) {
				
				// ======================================
				// =========== START OLD DATA ===========
				// ======================================
				
				$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordLink KeywordLink TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
				$db->execute();
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageModified')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD PageModified INT( 2 ) NOT NULL");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageKeywordsDensity')."");
				if (!$db->loadResult())
				{
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD PageKeywordsDensity TEXT NOT NULL AFTER PageKeywords");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageInSitemap')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD PageInSitemap INT ( 2 ) NOT NULL AFTER PageSitemap");
					$db->execute();
					$db->setQuery("UPDATE #__rsseo_pages SET PageInSitemap = 1 ");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('densityparams')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD densityparams TEXT NOT NULL AFTER params");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('canonical')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD canonical VARCHAR (500) NOT NULL AFTER densityparams");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('robots')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD robots VARCHAR (255) NOT NULL AFTER canonical");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('frequency')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD frequency VARCHAR (255) NOT NULL AFTER robots");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('priority')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD ".$db->qn('priority')." VARCHAR (255) NOT NULL AFTER frequency");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastTehnoratiRank')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors ADD LastTehnoratiRank INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Dmoz')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors ADD Dmoz INT( 1 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordAttributes')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords ADD KeywordAttributes TEXT NOT NULL");
					$db->execute();
				}

				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordLimit')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords ADD KeywordLimit INT( 3 ) NOT NULL");
					$db->execute();
				}
				
				// ======================================
				// ============ END OLD DATA ============
				// ======================================
				
				// ========= COMPETITORS TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('ordering')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP ".$db->qn('ordering')."");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastYahooPages')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP ".$db->qn('LastYahooPages')."");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastYahooBacklinks')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP ".$db->qn('LastYahooBacklinks')."");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastDateRefreshed')."");
				if ($dateref = $db->loadObject()) {				
					if ($dateref->Type == 'int(11)') {
						$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE `LastDateRefreshed` `LastDateRefreshed` VARCHAR(255) NOT NULL");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_competitors SET `LastDateRefreshed` = FROM_UNIXTIME(`LastDateRefreshed`)");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_competitors SET `LastDateRefreshed` = '0000-00-00 00:00:00' WHERE `LastDateRefreshed` = '1970-01-01 02:00:00'");
						$db->execute();					
						$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastDateRefreshed ".$db->qn('date')." DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
						$db->execute();
					}
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('IdCompetitor')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE IdCompetitor id INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Competitor')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE Competitor name VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('parent_id')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors ADD ".$db->qn('parent_id')." INT( 11 ) NOT NULL AFTER `name`");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastPageRank')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastPageRank pagerank INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
					
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastAlexaRank')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastAlexaRank alexa INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastTehnoratiRank')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastTehnoratiRank technorati INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastGooglePages')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastGooglePages googlep INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastBingPages')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastBingPages bingp INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastGoogleBacklinks')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastGoogleBacklinks googleb INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('LastBingBacklinks')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE LastBingBacklinks bingb INT( 11 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Dmoz')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE Dmoz dmoz INT( 1 ) NOT NULL DEFAULT '-1'");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('Tags')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors CHANGE Tags tags TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW INDEX FROM #__rsseo_competitors WHERE Key_name = 'Competitor'");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_competitors DROP INDEX Competitor");
					$db->execute();
				}
				
				
				// ========= REDIRECTS TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('IdRedirect')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE IdRedirect id INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('RedirectFrom')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE RedirectFrom ".$db->qn('from')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('RedirectTo')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE RedirectTo ".$db->qn('to')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_redirects WHERE Field = ".$db->q('RedirectType')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_redirects CHANGE RedirectType ".$db->qn('type')." ENUM( '301', '302' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				// ========= KEYWORDS TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('IdKeyword')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE IdKeyword id INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('Keyword')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE Keyword ".$db->qn('keyword')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordImportance')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordImportance ".$db->qn('importance')." ENUM( 'low', 'relevant', 'important', 'critical' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('ActualKeywordPosition')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE ActualKeywordPosition ".$db->qn('position')." INT( 11 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('LastKeywordPosition')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE LastKeywordPosition ".$db->qn('lastposition')." INT( 11 ) NOT NULL");
					$db->execute();
				}
				
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('DateRefreshed')."");
				if ($daterefkey = $db->loadObject()) {
					if ($daterefkey->Type == 'int(11)') {
						$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE `DateRefreshed` `DateRefreshed` VARCHAR(255) NOT NULL");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_keywords SET `DateRefreshed` = FROM_UNIXTIME(`DateRefreshed`)");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_keywords SET `DateRefreshed` = '0000-00-00 00:00:00' WHERE `DateRefreshed` = '1970-01-01 02:00:00'");
						$db->execute();					
						$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE DateRefreshed ".$db->qn('date')." DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
						$db->execute();
					}
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordBold')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordBold ".$db->qn('bold')." INT( 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordUnderline')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordUnderline ".$db->qn('underline')." INT( 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordLimit')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordLimit ".$db->qn('limit')." INT( 3 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordAttributes')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordAttributes ".$db->qn('attributes')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('KeywordLink')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_keywords CHANGE KeywordLink ".$db->qn('link')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				// ========= PAGES TABLE =========
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('IdPage')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE IdPage ".$db->qn('id')." INT( 11 ) NOT NULL AUTO_INCREMENT");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageURL')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageURL ".$db->qn('url')." VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageTitle')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageTitle ".$db->qn('title')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageKeywords')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageKeywords ".$db->qn('keywords')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageKeywordsDensity')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageKeywordsDensity keywordsdensity TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageDescription')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageDescription ".$db->qn('description')." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageSitemap')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageSitemap ".$db->qn('sitemap')." TINYINT( 1 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageInSitemap')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageInSitemap insitemap INT( 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageCrawled')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageCrawled ".$db->qn('crawled')." TINYINT( 1 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('DatePageCrawled')."");
				if ($daterefpag = $db->loadObject()) {
					if ($daterefpag->Type == 'int(11)') {
						$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE `DatePageCrawled` `DatePageCrawled` VARCHAR(255) NOT NULL");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_pages SET `DatePageCrawled` = FROM_UNIXTIME(`DatePageCrawled`)");
						$db->execute();
						$db->setQuery("UPDATE #__rsseo_pages SET `DatePageCrawled` = '0000-00-00 00:00:00' WHERE `DatePageCrawled` = '1970-01-01 02:00:00'");
						$db->execute();					
						$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE DatePageCrawled ".$db->qn('date')." DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
						$db->execute();
					}
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageModified')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageModified ".$db->qn('modified')." INT( 3 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageLevel')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageLevel ".$db->qn('level')." TINYINT( 4 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('PageGrade')."");
				if ($db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages CHANGE PageGrade ".$db->qn('grade')." FLOAT( 10, 2 ) NOT NULL");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('imagesnoalt')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD imagesnoalt TEXT NOT NULL AFTER priority");
					$db->execute();
				}
				
				$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('imagesnowh')."");
				if (!$db->loadResult()) {
					$db->setQuery("ALTER TABLE #__rsseo_pages ADD imagesnowh TEXT NOT NULL AFTER imagesnoalt");
					$db->execute();
				}
				
				// ========= COMPETITORS HISTORY TABLE =========
				$db->setQuery("SHOW TABLES FROM ".$db->qn(JFactory::getConfig()->get('db'))." LIKE ".$db->q('%'.JFactory::getConfig()->get('dbprefix').'rsseo_competitors_history%')."");
				if ($db->loadResult()) {
					$db->setQuery("SELECT * FROM #__rsseo_competitors_history");
					if ($history = $db->loadObjectList()) {
						foreach ($history as $item) {
							$db->setQuery("INSERT INTO #__rsseo_competitors SET `parent_id` = ".$db->q($item->IdCompetitor).", alexa = ".$db->q($item->AlexaRank).", technorati = ".$db->q($item->TehnoratiRank).", googlep = ".$db->q($item->GooglePages).", bingp = ".$db->q($item->BingPages).", googleb = ".$db->q($item->GoogleBacklinks).", bingb = ".$db->q($item->BingBacklinks).", date = FROM_UNIXTIME(".$db->q($item->DateRefreshed).") ");
							$db->execute();
						}
					}
					
					$db->setQuery("DROP TABLE #__rsseo_competitors_history");
					$db->execute();
				}
				
				// ========= CONFIGURATION TABLE =========
				$db->setQuery("SHOW TABLES FROM ".$db->qn(JFactory::getConfig()->get('db'))." LIKE ".$db->q('%'.JFactory::getConfig()->get('dbprefix').'rsseo_config%')."");
				if ($db->loadResult()) {
					$db->setQuery("SELECT ConfigName, ConfigValue FROM #__rsseo_config");
					if ($configuration = $db->loadObjectList()) {
						$config = array();
						foreach ($configuration as $conf) {
							if ($conf->ConfigName == 'enable.debug' || $conf->ConfigName == 'enable.yahoop' || $conf->ConfigName == 'enable.yahoob' || $conf->ConfigName == 'component.heading' || $conf->ConfigName == 'content.heading' || $conf->ConfigName == 'php.folder' || $conf->ConfigName == 'enable.php')
								continue;
							
							if ($conf->ConfigName == 'sitemap_no_autolinks') $conf->ConfigName = 'sitemapauto';
							if ($conf->ConfigName == 'search.dmoz') $conf->ConfigName = 'enable_dmoz';
							$conf->ConfigName = str_replace('.','_',$conf->ConfigName);
							
							$config[$conf->ConfigName] = $conf->ConfigValue;
						}
						$config['copykeywords'] = 0;
						$config['overwritekeywords'] = 0;
						
						
						$reg = new JRegistry();
						$reg->loadArray($config);
						$confdata = $reg->toString();
						
						$query->clear();
						$query->update('`#__extensions`')->set('`params` = '.$db->quote($confdata))->where('`extension_id` = '.(int) $extension_id);
						$db->setQuery($query);
						$db->execute();
					}
					
					$db->setQuery("DROP TABLE #__rsseo_config");
					$db->execute();
				}
			}
		
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_pages WHERE Field = ".$db->q('url'));
			if ($pagesField = $db->loadObject()) {
				if ($pagesField->Type == 'varchar(255)') {
					$db->setQuery("ALTER TABLE `#__rsseo_pages` CHANGE `url` `url` VARCHAR( 333 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
					$db->execute();
				}
			}
			
			$query->clear();
			$query->select($db->qn('params'))->from($db->qn('#__extensions'))->where($db->qn('extension_id').' = '.(int) $extension_id);
			$db->setQuery($query);
			$componentParams = $db->loadResult();
			$registry = new JRegistry;
			$registry->loadString($componentParams);
			$sitemap_menus		= $registry->get('sitemap_menus');
			$sitemap_excludes	= $registry->get('sitemap_excludes');
			$update				= false;
			
			if (is_array($sitemap_menus)) {
				$registry->set('sitemap_menus', base64_encode(serialize($sitemap_menus)));
				$update = true;
			}
			
			if (is_array($sitemap_excludes)) {
				$registry->set('sitemap_excludes', base64_encode(serialize($sitemap_excludes)));
				$update = true;
			}
			
			if ($update) {
				$query->clear();
				$query->update($db->qn('#__extensions'))->set($db->qn('params').' = '.$db->quote($registry->toString()))->where($db->qn('extension_id').' = '.(int) $extension_id);
				$db->setQuery($query);
				$db->execute();
			}
			
			// VERSION 1.18.0
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_competitors` WHERE Field = 'googler'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_competitors` ADD `googler` INT(11) NOT NULL AFTER `bingb`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_competitors` WHERE Field = 'age'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_competitors` ADD `age` VARCHAR( 255 ) NOT NULL AFTER `parent_id`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_error_links` WHERE Field = 'code'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_error_links` ADD `code` INT NOT NULL AFTER `url`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'hits'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `hits` INT NOT NULL AFTER `imagesnowh`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'custom'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `custom` TEXT NOT NULL AFTER `hits`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_redirects` WHERE Field = 'hits'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_redirects` ADD `hits` INT NOT NULL AFTER `type`");
				$db->execute();
			}
			
			$db->setQuery("SELECT `params` FROM `#__extensions` WHERE `extension_id` = ".(int) $extension_id);
			$componentParams2 = $db->loadResult();
			$registry = new JRegistry;
			$registry->loadString($componentParams2);
			
			if (!$registry->exists('enable_age')) $registry->set('enable_age','1');
			if (!$registry->exists('enable_googler')) $registry->set('enable_googler','1');
			if (!$registry->exists('enable_sitemap_cron')) $registry->set('enable_sitemap_cron','0');
			if (!$registry->exists('sitemap_cron_type')) $registry->set('sitemap_cron_type','0');
			if (!$registry->exists('sitemap_cron_security')) $registry->set('sitemap_cron_security','707cb49519002c0b36d6ae726aa02589');
			if (!$registry->exists('sitemapprotocol')) $registry->set('sitemapprotocol','0');
			if (!$registry->exists('sitemap_timestamp')) $registry->set('sitemap_timestamp','0');
			if (!$registry->exists('ga_type')) $registry->set('ga_type','0');
			if (!$registry->exists('track_visitors')) $registry->set('track_visitors','1');
			if (!$registry->exists('sitemap_permissions')) $registry->set('sitemap_permissions','644');
			if (!$registry->exists('obfuscate_visitor_ip')) $registry->set('obfuscate_visitor_ip','0');
			if (!$registry->exists('robots_permissions')) $registry->set('robots_permissions','644');
			if (!$registry->exists('title_length')) $registry->set('title_length','50');
			if (!$registry->exists('keywords_length')) $registry->set('keywords_length','10');
			if (!$registry->exists('description_length')) $registry->set('description_length','150');
			if (!$registry->exists('request_timeout')) $registry->set('request_timeout','0');
			if (!$registry->exists('crawler_type')) $registry->set('crawler_type','loopback');
			if (!$registry->exists('exclude_noindex')) $registry->set('exclude_noindex','0');
			if (!$registry->exists('sitemap_autocrawled')) $registry->set('sitemap_autocrawled','2');
			if (!$registry->exists('sitemap_autocrawled_rule')) $registry->set('sitemap_autocrawled_rule','');
			if (!$registry->exists('eanble_k_cron')) $registry->set('eanble_k_cron','0');
			if (!$registry->exists('k_cron_run')) $registry->set('k_cron_run','weekly');
			if (!$registry->exists('img_auto_alt')) $registry->set('img_auto_alt','0');
			if (!$registry->exists('img_auto_alt_rule')) $registry->set('img_auto_alt_rule','{name} {title}');
			if (!$registry->exists('img_auto_title')) $registry->set('img_auto_title','0');
			if (!$registry->exists('img_auto_title_rule')) $registry->set('img_auto_title_rule','{name} image');
			if (!$registry->exists('enable_moz')) $registry->set('enable_moz','1');
			if (!$registry->exists('moz_access_id')) $registry->set('moz_access_id','');
			if (!$registry->exists('moz_secret')) $registry->set('moz_secret','');
			if (!$registry->exists('enable_sef')) $registry->set('enable_sef','1');
			if (!$registry->exists('ga_options')) $registry->set('ga_options','');
			
			$query->clear();
			$query->update($db->qn('#__extensions'))->set($db->qn('params').' = '.$db->quote($registry->toString()))->where($db->qn('extension_id').' = '.(int) $extension_id);
			$db->setQuery($query);
			$db->execute();
			
			// VERSION 1.18.14
			$db->setQuery("SHOW INDEX FROM #__rsseo_keywords WHERE Key_name = 'keyword'");
			if ($db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_keywords DROP INDEX `keyword`");
				$db->execute();
			}
			
			// VERSION 1.19.0
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_errors` WHERE Field = 'itemid'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_errors` ADD `itemid` INT NOT NULL AFTER `layout`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'parent'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `parent` VARCHAR(333) NOT NULL AFTER `custom`");
				$db->execute();
			}
			
			$keywords = false;
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('position')."");
			if ($db->loadResult()) {
				$db->setQuery("SELECT `id`, `position`, `date` FROM `#__rsseo_keywords`");
				$keywords = $db->loadObjectList();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'external'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `external` INT NOT NULL AFTER `parent`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'internal'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `internal` INT NOT NULL AFTER `external`");
				$db->execute();
			}
			
			// VERSION 1.19.0
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('position')."");
			if ($db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_keywords DROP ".$db->qn('position')."");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('lastposition')."");
			if ($db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_keywords DROP ".$db->qn('lastposition')."");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_keywords WHERE Field = ".$db->q('date')."");
			if ($db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_keywords DROP ".$db->qn('date')."");
				$db->execute();
			}
			
			if ($keywords) {
				foreach ($keywords as $keyword) {
					$db->setQuery('INSERT INTO `#__rsseo_keyword_position` SET `idk` = '.$db->q($keyword->id).', `position` = '.$db->q($keyword->position).', `date` = '.$db->q($keyword->date).' ');
					$db->execute();
				}
			}
			
			// VERSION 1.19.9
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_statistics WHERE Field = ".$db->q('twitter')."");
			if ($db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_statistics DROP ".$db->qn('twitter')."");
				$db->execute();
			}
			
			// VERSION 1.20.0
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_competitors` WHERE Field = 'mozpagerank'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_competitors` ADD `mozpagerank` INT NOT NULL AFTER `tags`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_competitors` WHERE Field = 'mozpa'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_competitors` ADD `mozpa` INT NOT NULL AFTER `mozpagerank`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_competitors` WHERE Field = 'mozda'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_competitors` ADD `mozda` INT NOT NULL AFTER `mozpa`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_statistics` WHERE Field = 'mozpagerank'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_statistics` ADD `mozpagerank` INT NOT NULL AFTER `linkedin`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_statistics` WHERE Field = 'mozpa'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_statistics` ADD `mozpa` INT NOT NULL AFTER `mozpagerank`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_statistics` WHERE Field = 'mozda'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_statistics` ADD `mozda` INT NOT NULL AFTER `mozpa`");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_keywords` WHERE Field = 'lastcheck'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_keywords` ADD `lastcheck` DATETIME NOT NULL AFTER `link`");
				$db->execute();
			}
			
			// VERSION 1.20.6
			$db->setQuery("SHOW INDEX FROM #__rsseo_pages WHERE Key_name = 'PageURL'");
			if ($db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_pages DROP INDEX PageURL");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'url'");
			if ($pageObj = $db->loadObject()) {
				if (strpos(strtolower($pageObj->Type), 'varchar') !== false) {
					$db->setQuery("ALTER TABLE `#__rsseo_pages` CHANGE `url` `url` TEXT NOT NULL");
					$db->execute();
				}
			}
			
			// VERSION 1.20.8
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'sef'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `sef` VARCHAR(444) NOT NULL AFTER `url`");
				$db->execute();
			}
			
			// VERSION 1.20.10
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_pages` WHERE Field = 'hash'");
			if (!$db->loadResult()) {
				try {				
					$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD `hash` VARCHAR(32) NOT NULL AFTER `url`");
					$db->execute();
					$db->setQuery("UPDATE `#__rsseo_pages` SET `hash` = MD5(`url`)");
					$db->execute();
					
					$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD KEY `sef` (`sef`(333))");
					$db->execute();
					
					$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD KEY `hash` (`hash`)");
					$db->execute();
					
					$db->setQuery("ALTER TABLE `#__rsseo_pages` ADD KEY `hash_2` (`hash`,`published`)");
					$db->execute();
				} catch (Exception $e) {
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}
			
			// VERSION 1.20.11
			$db->setQuery("SHOW COLUMNS FROM `#__rsseo_visitors` WHERE Field = 'session_id'");
			if ($pageObj = $db->loadObject()) {
				if (strpos($pageObj->Type, '255') !== false) {
					$db->setQuery("ALTER TABLE `#__rsseo_visitors` CHANGE `session_id` `session_id` VARCHAR(50) NOT NULL");
					$db->execute();
				}
			}
			
			$db->setQuery("SHOW INDEX FROM #__rsseo_visitors WHERE Key_name = 'session_id'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_visitors ADD KEY `session_id` (`session_id`)");
				$db->execute();
			}
			
			$db->setQuery("SHOW INDEX FROM #__rsseo_visitors WHERE Key_name = 'id-session_id'");
			if (!$db->loadResult()) {
				$db->setQuery("ALTER TABLE #__rsseo_visitors ADD KEY `id-session_id` (`id`,`session_id`)");
				$db->execute();
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__rsseo_competitors WHERE Field = ".$db->q('tags')."");
			if ($competitorsRef = $db->loadObject()) {				
				if (strpos(strtolower($competitorsRef->Type), 'text') !== false) {
					$db->setQuery("ALTER TABLE `#__rsseo_competitors` CHANGE `tags` `tags` VARCHAR(500) NOT NULL DEFAULT ''");
					$db->execute();
				}
			}
			
			// Set default values on database fields
			if ($tables = $db->getTableList()) {
				foreach ($tables as $table) {
					if (strpos($table, $db->getPrefix().'rsseo') !== false) {
						if ($fields = $db->getTableColumns($table, false)) {
							foreach ($fields as $field) {
								$fieldType = strtolower($field->Type);
								$fieldKey = strtolower($field->Key);
								
								if (strpos($fieldType, 'int') !== false || strpos($fieldType, 'float') !== false|| strpos($fieldType, 'decimal') !== false) {
									if ($fieldKey != 'pri') {
										$db->setQuery('ALTER TABLE '.$db->qn($table).' ALTER '.$db->qn($field->Field).' SET DEFAULT '.$db->q(0));
										$db->execute();
									}
								} elseif (strpos($fieldType, 'varchar') !== false) {
									$db->setQuery('ALTER TABLE '.$db->qn($table).' ALTER '.$db->qn($field->Field).' SET DEFAULT '.$db->q(''));
									$db->execute();
								} elseif (strpos($fieldType, 'datetime') !== false) {
									$db->setQuery('ALTER TABLE '.$db->qn($table).' ALTER '.$db->qn($field->Field).' SET DEFAULT '.$db->q($db->getNullDate()));
									$db->execute();
								}
							}
						}
					}
				}
			}
		}
		
		$this->showInstall();
	}
	
	protected function runSql($sqlfile) {
		$buffer = file_get_contents($sqlfile);
		if ($buffer === false) {
			throw new Exception(JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'), 1);
			return false;
		}
		
		$db = JFactory::getDbo();
		$queries = $db->splitSql($buffer);
		if (count($queries) == 0) {
			// No queries to process
			return 0;
		}
		
		// Process each query in the $queries array (split out of sql file).
		foreach ($queries as $query)
		{
			$query = trim($query);
			if ($query != '' && $query{0} != '#') {
				$db->setQuery($query);
				if (!$db->execute()) {
					return false;
				}
			}
		}
	}
	
	public function uninstall($parent)  {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$installer	= new JInstaller();

		// Remove the rsseo system plugin
		$query->clear();
		$query->select('`extension_id`')->from('`#__extensions`')->where('`element` = '.$db->quote('rsseo'))->where('`type` = '.$db->quote('plugin'))->where('`folder` = '.$db->quote('system'));
		$db->setQuery($query,0,1);
		$plugin = $db->loadResult();
		if ($plugin) $installer->uninstall('plugin', $plugin);
		
		// Remove the rsseo installer plugin
		$query->clear();
		$query->select('`extension_id`')->from('`#__extensions`')->where('`element` = '.$db->quote('rsseo'))->where('`type` = '.$db->quote('plugin'))->where('`folder` = '.$db->quote('installer'));
		$db->setQuery($query,0,1);
		$iplugin = $db->loadResult();
		if ($iplugin) $installer->uninstall('plugin', $iplugin);
		
		$this->showUninstall();
	}
	
	protected function showInstall() {
?>
<style type="text/css">
.version-history {
	margin: 0 0 2em 0;
	padding: 0;
	list-style-type: none;
}
.version-history > li {
	margin: 0 0 0.5em 0;
	padding: 0 0 0 4em;
}
.version-new,
.version-fixed,
.version-upgraded {
	float: left;
	font-size: 0.8em;
	margin-left: -4.9em;
	width: 4.5em;
	color: white;
	text-align: center;
	font-weight: bold;
	text-transform: uppercase;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
.version-new { background: #7dc35b; }
.version-fixed { background: #e9a130; }
.version-upgraded { background: #61b3de; }
#installer-left {
	float: left;
	width: 230px;
	padding: 5px;
}
#installer-right {
	float: left;
}
.com-rsseo-button {
	display: inline-block;
	background: #459300 none repeat scroll 0 0;
	color: #fff !important;
	cursor: pointer;
	margin-bottom: 10px;
    padding: 7px;
	text-decoration: none !important;
}
</style>
<div id="installer-left">
	<img src="<?php echo JUri::root(); ?>media/com_rsseo/images/rsseo-logo.png" alt="RSSeo!" />
</div>
<div id="installer-right">
	<h2>Changelog v1.20.17</h2>
	<ul class="version-history">
		<li><span class="version-fixed">Fix</span> Custom SEF for some components caused a redirect loop.</li>
	</ul>
	<a class="com-rsseo-button" href="index.php?option=com_rsseo">Start using RSSeo!</a>
	<a class="com-rsseo-button" href="http://www.rsjoomla.com/support/documentation/view-knowledgebase/67-rsseo.html" target="_blank">Read the RSSeo! User Guide</a>
	<a class="com-rsseo-button" href="http://www.rsjoomla.com/customer-support/tickets.html" target="_blank">Get Support!</a>
</div>
<div style="clear: both;"></div>
	
<?php	
	}
	
	protected function showUninstall() {
		echo 'RSSeo! component has been successfully uninstaled!';
	}
}