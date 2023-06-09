<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="search-results <?php echo $this->pageclass_sfx; ?> uk-child-width-1-1 uk-grid-small" uk-grid>
<?php foreach ($this->results as $result) : ?>
<div>
    <div class="uk-grid-small font fnum uk-text-small" uk-grid>
        <div class="uk-width-expand" uk-leader>
            <?php /* echo $this->pagination->limitstart + $result->count . '. '; */ ?>
            <?php if ($result->href) : ?>
                <a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) : ?> target="_blank"<?php endif; ?>>
                    <?php // $result->title should not be escaped in this case, as it may ?>
                    <?php // contain span HTML tags wrapping the searched terms, if present ?>
                    <?php // in the title. ?>
                    <?php echo $result->title; ?>
                </a>
            <?php else : ?>
                <?php // see above comment: do not escape $result->title ?>
                <?php echo $result->title; ?>
            <?php endif; ?>
        </div>
        <?php if ($result->section) : ?>
            <div><?php echo $this->escape($result->section); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
</div>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>