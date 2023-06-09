<?php
/**
* @version 1.4.0
* @package RSform!Pro 1.4.0
* @copyright (C) 2007-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$lang = JFactory::getLanguage();
$languages = JLanguageHelper::getLanguages('lang_code');
$languageCode = $languages[ $lang->getTag() ]->sef;
?>

<div class=" rsform<?php echo ' '.$languageCode.'form '.$moduleclass_sfx; ?>">
	<?php echo RSFormProHelper::displayForm($formId, true); ?>
</div>