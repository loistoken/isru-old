<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="items-leading uk-grid-medium uk-child-width-1-1 uk-child-width-1-2@l" uk-grid>
    <?php foreach ($list as $item) : ?>
        <div class="leading-0" itemprop="blogPost" itemscope="" itemtype="https://schema.org/BlogPosting">
            <div class="listItem">
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-1 uk-width-1-2@m uk-first-column">
                        <div uk-slideshow="ratio: 4:3" class="uk-slideshow">
                            <ul class="uk-slideshow-items" style="height: 150px;">
                                <li class="uk-border-rounded uk-overflow-hidden uk-active uk-transition-active" style="transform: translateX(0px);">
                                    <img src="<?php echo json_decode($item->images)->image_intro; ?>" alt="<?php echo $item->title; ?>" itemprop="image" uk-cover="" class="uk-cover" width="400" height="300">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="uk-width-expand uk-text-0 uk-flex uk-flex-middle uk-flex-center uk-flex-left@m">
                        <div class="page-header">
                            <h2 itemprop="name" class="font uk-text-large uk-margin-remove">
                                <?php if ($params->get('link_titles') == 1) : ?>
                                    <a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
                                <?php else : ?>
                                    <?php echo $item->title; ?>
                                <?php endif; ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>