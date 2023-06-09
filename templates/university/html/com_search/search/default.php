<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');
?>
<div class="uk-flex-center" uk-grid>
    <div class="uk-width-1-1 uk-width-1-2@l">
        <div class="search <?php echo $this->pageclass_sfx; ?>">
            <?php echo $this->loadTemplate('form'); ?>
            <?php if ($this->error == null && count($this->results) > 0) : ?>
                <?php echo $this->loadTemplate('results'); ?>
            <?php else : ?>
                <?php echo $this->loadTemplate('error'); ?>
            <?php endif; ?>
        </div>
    </div>
</div>