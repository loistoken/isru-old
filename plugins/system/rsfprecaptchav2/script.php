<?php
/**
* @package RSForm!Pro
* @copyright (C) 2007-2017 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemRSFPReCaptchav2InstallerScript
{
	public function preflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}

		try {
			$source = $parent->getParent()->getPath('source');

			$jversion = new JVersion();
			if (!$jversion->isCompatible('3.7.0')) {
				throw new Exception('Please upgrade to at least Joomla! 3.7.0 before continuing!');
			}

			if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php') || !file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/version.php')) {
				throw new Exception('Please install the RSForm! Pro component before continuing.');
			}

			if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/assets.php')) {
				throw new Exception('Please upgrade RSForm! Pro to at least version 1.52.10 before continuing!');
			}

			// Check version matches, we need 1.52.10 due to changes in the component.
			require_once JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/version.php';
			$version = new RSFormProVersion;
			if (version_compare((string) $version, '1.52.10', '<'))
			{
				throw new Exception('Please upgrade RSForm! Pro to at least version 1.52.10 before continuing!');
			}

			// All good.
			// Copy needed files
			$this->copyFiles($source);
			
			// Update? Run our SQL file
			if ($type == 'update') {
				$this->runSQL($source, 'install');
			}
		} catch (Exception $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			return false;
		}
		
		return true;
	}
	
	protected function copyFiles($source) {
		jimport('joomla.filesystem.folder');
		
		// Copy /admin files
		$src		= $source.'/admin';
		$dest 		= JPATH_ADMINISTRATOR.'/components/com_rsform';
		if (!JFolder::copy($src, $dest, '', true)) {
			throw new Exception('Could not copy to '.str_replace(JPATH_ADMINISTRATOR, '', $dest).', please make sure destination is writable!');
		}
	}
	
	protected function runSQL($source, $file) {
		$db 	= JFactory::getDbo();
		$driver = strtolower($db->name);
		if (strpos($driver, 'mysql') !== false) {
			$driver = 'mysql';
		}
		
		$sqlfile = $source.'/sql/'.$driver.'/'.$file.'.sql';
		
		if (file_exists($sqlfile)) {
			$buffer = file_get_contents($sqlfile);
			if ($buffer !== false) {
				$queries = $db->splitSql($buffer);
				foreach ($queries as $query) {
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						if (!$db->execute()) {
							throw new Exception(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
						}
					}
				}
			}
		}
	}
}