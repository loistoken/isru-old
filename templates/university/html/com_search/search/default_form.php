<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* JHtml::_('bootstrap.tooltip'); */

$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

?>
<div class="uk-margin-medium-bottom uk-border-rounded uk-alert-<?php echo $this->total == 0 ? 'danger' : 'success'; ?> searchintro<?php echo $this->params->get('pageclass_sfx'); ?>" uk-alert>
    <?php if (!empty($this->searchword)) : ?>
        <p  class="uk-text-center font"><?php echo $this->total == 0 ? JTEXT::_('SEARCHNORESULT') : JText::sprintf('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total); ?></p>
    <?php endif; ?>
</div>
<div class="<?php if ($this->total > 0) echo 'uk-margin-medium-bottom'; ?>">
    <form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search'); ?>" method="post">
        <div>
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-1 uk-width-2-3@m uk-width-3-4@l">
                    <input type="text" name="searchword" title="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" placeholder="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox uk-input uk-border-rounded uk-text-center uk-text-left@m" />
                </div>
                <div class="uk-width-1-1 uk-width-1-3@m uk-width-1-4@l">
                    <button name="Search" onclick="this.form.submit()" title="<?php echo JHtml::_('tooltipText', 'COM_SEARCH_SEARCH');?>" class="uk-button uk-button-secondary uk-width-1-1 uk-border-rounded"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                </div>
            </div>
        </div>
        <input type="hidden" name="task" value="search" />
    </form>
</div>