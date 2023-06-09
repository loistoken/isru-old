<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoControllerSitemap extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	rsseoControllerSitemap
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Sitemap', $prefix = 'rsseoModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 *	Attempt to create the sitemap.xml and ror.xml
	 *
	 */
	public function create() {
		JFactory::getDocument()->setMimeEncoding('application/json');

		try {
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.path');

			$type = JFactory::getApplication()->input->getCmd('file');

			// Verify if it's a valid sitemap file
			if (!in_array($type, array('sitemap', 'ror'))) {
				throw new Exception(JText::sprintf('COM_RSSEO_SITEMAP_CANNOT_CREATE_WRONG_NAME', $type));
			}

			$file	= JPATH_SITE.'/'.$type.'.xml';
			$empty	= '';

			// Attempt to write the file on disk
			if (!JFile::write($file, $empty)) {
				throw new Exception(JText::sprintf('COM_RSSEO_SITEMAP_CANNOT_WRITE', $type, JPATH_SITE));
			}

			// Apply permissions
			if (JPath::canChmod($file)) {
				$permission	= rsseoHelper::validatePermission(JComponentHelper::getParams('com_rsseo')->get('sitemap_permissions', 644));
				JPath::setPermissions($file,'0'.$permission);
			}

			echo json_encode(array(
				'status'  => 1,
				'message' => JText::sprintf('COM_RSSEO_SITEMAP_CREATED_SUCCESSFULLY', $type)
			));
		} catch (Exception $e) {
			echo json_encode(array(
				'status'  => 0,
				'message' => $e->getMessage()
			));
		}

		JFactory::getApplication()->close();
	}
	
	/**
	 *	Generate the XML sitemap
	 *
	 */
	public function generate() {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsseo/helpers/sitemap.php';
		
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$jinput 	= JFactory::getApplication()->input;
		$new		= $jinput->getInt('new',0);
		$protocol	= $jinput->getInt('protocol',0);
		$modified	= $jinput->getCmd('modified','');
		$config		= rsseoHelper::getConfig();
		$suffix		= JFactory::getConfig()->get('sef_suffix');
		$sef 		= JFactory::getConfig()->get('sef');

		JFactory::getDocument()->setMimeEncoding('application/json');

		try {
			// Get a new instance of the Sitemap class
			$sitemap = sitemapHelper::getInstance($new, $protocol, $modified, 1);

			$query->clear()
				->select($db->qn('id'))->select($db->qn('url'))->select($db->qn('sef'))->select($db->qn('title'))
				->select($db->qn('level'))->select($db->qn('priority'))->select($db->qn('frequency'))
				->from($db->qn('#__rsseo_pages'))
				->where($db->qn('sitemap') . ' = 0')
				->where($db->qn('insitemap') . ' = 1')
				->where($db->qn('published') . ' != -1')
				->where($db->qn('canonical') . ' = ' . $db->q(''));
				
			if ($config->exclude_noindex) {
				$query->where($db->qn('robots').' NOT LIKE '.$db->q('%"index":"0"%'));
			}
			
			$query->order($db->qn('level'));
			$db->setQuery($query, 0, 500);

			$sitemap->setHeader();

			if ($pages = $db->loadObjectList()) {
				foreach ($pages as $page) {
					$page->url = rsseoHelper::showURL($page->url, $page->sef);
					
					$sitemap->add($page);

					$query->clear()
						->update($db->qn('#__rsseo_pages'))
						->set($db->qn('sitemap') . ' = 1')
						->where($db->qn('id') . ' = ' . $db->q($page->id));
					$db->setQuery($query);
					$db->execute();
				}
			} else {
				$sitemap->close();

				echo json_encode(array(
					'status' 	=> 1,
					'progress' 	=> 100,
					'finished'	=> 1,
					'message'	=> JText::_('COM_RSSEO_SITEMAP_GENERATED_SUCCESSFULLY')
				));

				JFactory::getApplication()->close();
			}

			$query->clear()
				->select('COUNT(id)')
				->from($db->qn('#__rsseo_pages'))
				->where($db->qn('insitemap') . ' = 1')
				->where($db->qn('published') . ' != -1')
				->where($db->qn('canonical') . ' = ' . $db->q(''));
				
			if ($config->exclude_noindex) {
				$query->where($db->qn('robots').' NOT LIKE '.$db->q('%"index":"0"%'));
			}
			
			$db->setQuery($query);
			$total = (int)$db->loadResult();

			$query->clear()
				->select('COUNT(id)')
				->from($db->qn('#__rsseo_pages'))
				->where($db->qn('sitemap') . ' = 1')
				->where($db->qn('insitemap') . ' = 1')
				->where($db->qn('published') . ' != -1')
				->where($db->qn('canonical') . ' = ' . $db->q(''));
			
			if ($config->exclude_noindex) {
				$query->where($db->qn('robots').' NOT LIKE '.$db->q('%"index":"0"%'));
			}
			
			$db->setQuery($query);
			$processed = (int)$db->loadResult();

			echo json_encode(array(
				'status' 	=> 1,
				'finished'	=> 0,
				'progress' 	=> ceil($processed * 100 / $total)
			));
		} catch (Exception $e) {
			echo json_encode(array(
				'status'  => 0,
				'message' => $e->getMessage()
			));
		}

		JFactory::getApplication()->close();
	}
	
	/**
	 *	Create the HTML sitemap
	 *
	 */
	public function html() {
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$jinput		= JFactory::getApplication()->input;
		$menus		= $jinput->get('menus',array(),'array');
		$exclude	= $jinput->get('exclude',array(),'array');
		
		$component	= JComponentHelper::getComponent('com_rsseo');
		$cparams	= $component->params;
		
		if ($cparams instanceof JRegistry) {
			$cparams->set('sitemap_menus', base64_encode(serialize($menus)));
			$cparams->set('sitemap_excludes', base64_encode(serialize($exclude)));
			$query->clear();
			$query->update($db->qn('#__extensions'));
			$query->set($db->qn('params'). ' = '.$db->q((string) $cparams));
			$query->where($db->qn('extension_id'). ' = '. $db->q($component->id));
			
			$db->setQuery($query);
			$db->execute();
		}
		
		$this->setMessage(JText::_('COM_RSSEO_HTML_SITEMAP_CREATED'));
		return $this->setRedirect('index.php?option=com_rsseo&view=sitemap');
	}
}