<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

class rsseoTablePage extends JTable
{
	/**
	 * @param	JDatabase	A database connector object
	 */
	public function __construct($db) {
		parent::__construct('#__rsseo_pages', 'id', $db);
	}
	
	/**
	 * Overloaded bind function
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties
	 * to ignore while binding.
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
	 *
	 * @see     JTable::bind
	 * @since   11.1
	 */
	public function bind($array, $ignore = '') {
		if (isset($array['robots']) && is_array($array['robots'])) {
			$registry = new JRegistry;
			$registry->loadArray($array['robots']);
			$array['robots'] = (string) $registry;
		}
		
		if (isset($array['custom']) && is_array($array['custom'])) {
			$custom		= array();
			$metaname	= $array['custom']['name'];
			
			if (isset($metaname)) {
				foreach ($metaname as $i => $name) {
					if (empty($name)) continue;
					
					$custom[] = array(
							'type'		=> (isset($array['custom']['type'][$i]) ? $array['custom']['type'][$i] : 'name'),
							'name' 		=> $name,
							'content' 	=> (isset($array['custom']['content'][$i]) ? $array['custom']['content'][$i] : '')
						);
				}
			}
			
			$registry = new JRegistry;
			$registry->loadArray($custom);
			$array['custom'] = (string) $registry;
		}
		
		if (!isset($array['custom'])) {
			$array['custom'] = '';
		}
		
		if (isset($array['sef'])) {
			$sef = str_replace('.html', '', $array['sef']);
			$array['sef'] =  JFactory::getConfig()->get('unicodeslugs') ? JFilterOutput::stringUrlUnicodeSlug($sef) : JFilterOutput::stringUrlSafe($sef);
		}
		
		return parent::bind($array, $ignore);
	}
	
	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     JTable::check
	 * @since   11.1
	 */
	public function check() {
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$jinput = JFactory::getApplication()->input->get('jform',array(),'array');
		
		$this->url		= str_replace(array('&amp;','&apos;','&quot;','&gt;','&lt;'),array("&","'",'"',">","<"),$this->url);
		$this->url		= str_replace(array("&","'",'"',">","<"),array('&amp;','&apos;','&quot;','&gt;','&lt;'),$this->url);
		$this->url		= trim($this->url);
		$this->hash		= md5($this->url);
		$this->modified	= 1;
		
		// Check URL for http, www
		if (strpos($this->url,'http') !== false || strpos($this->url,'www') !== false) {
			$this->published = 0;
			$this->setError(JText::_('COM_RSSEO_INVALID_PAGE'));
			return false;
		}
		
		if (isset($jinput['original']) && $jinput['original'] == 1) {
			$this->modified = 0;
			$this->crawled = 0;
		}
		
		// Check for sef URL
		if ($this->sef) {
			$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__rsseo_pages'))
				->where('('.$db->qn('sef').' = '.$db->q($this->sef).' OR '.$db->qn('url').' = '.$db->q($this->sef).' OR '.$db->qn('url').' = '.$db->q($this->sef.'.html').')')
				->where($db->qn('id').' <> '.$db->q($this->id));
			$db->setQuery($query);
			if ($db->loadResult()) {
				$this->setError(JText::_('COM_RSSEO_PAGE_SEF_EXISTS'));
				return false;
			}
		}
		
		// Check for page duplicate
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__rsseo_pages'))
			->where($db->qn('url').' = '.$db->q($this->url))
			->where($db->qn('id').' <> '.$db->q($this->id));
		$db->setQuery($query);
		if ($db->loadResult()) {
			$this->setError(JText::_('COM_RSSEO_PAGE_EXISTS'));
			return false;
		}
		
		return true;
	}
	
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 * @param   integer  $pk        The primary key of the node to delete.
	 * @param   boolean  $children  True to delete child nodes, false to move them up a level.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     http://docs.joomla.org/JTable/delete
	 * @since   2.5
	 */
	public function delete($pk = null, $children = false) {
		if ($pk == 1) {
			$this->setError(JText::_('COM_RSSEO_CANNOT_DELETE_HOME_PAGE'));
			return false;
		}
		
		if (parent::delete($pk, $children)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)->delete($db->qn('#__rsseo_broken_links'))->where($db->qn('pid').' = '.(int) $pk);
			$db->setQuery($query);
			$db->execute();
			return true;
		} else {
			return false;
		}
	}
}