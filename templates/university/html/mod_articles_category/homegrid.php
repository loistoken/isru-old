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


<?php if ($grouped) : ?>
<div class="homeGrid <?php echo $moduleclass_sfx; ?>">
    <?php $kh = 1; foreach ($list as $group_name => $group): ?>
        <h3 class="font uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-black uk-text-center uk-margin-medium-bottom uk-text-left@m"><?php echo $group_name; ?></h3>
        <div class="<?php echo $kh == count($list) ? '' : 'uk-margin-large-bottom'; ?>" uk-grid>
            <?php foreach ($group as $item) : ?>
                <?php $db = JFactory::getDBO(); $sql = "select * from #__fields_values where `item_id` = $item->id"; $db->setQuery($sql); $fieldslist = $db->loadObjectList(); ?>
                <?php if ($fieldslist[2]->value == 1) { ?>
                    <div class="uk-width-1-1"><hr class="uk-margin-remove"></div>
                <?php } ?>
                <?php if ($fieldslist[1]->value == 'left') { ?>
                    <div class="uk-width-1-1">
                        <div uk-grid>
                            <div class="item uk-width-1-1 uk-width-1-3@m">
                                <div class="imgWrapper">
                                    <img src="<?php echo JURI::base(); ?>images/homeGrid/pic4.jpg" width="640" height="360" alt="Connect with us through podcasts and webinars" class="uk-display-inline-block uk-border-rounded">
                                </div>
                            </div>
                            <div class="item uk-width-1-1 uk-width-2-3@m">
                                <div class="contentWrapper">
                                    <?php if ($params->get('link_titles') == 1) : ?>
                                        <a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
                                    <?php else : ?>
                                        <h4 class="font uk-margin-remove"><?php echo $item->title; ?></h4>
                                    <?php endif; ?>
                                    <?php if ($params->get('show_introtext')) : ?>
                                        <p class="uk-margin-small-top uk-margin-remove-bottom"><?php echo $item->displayIntrotext; ?></p>
                                    <?php endif; ?>
                                    <?php if ($params->get('show_readmore') && $item->fulltext != '') : ?>
                                        <a href="<?php echo $item->link; ?>" class="uk-button uk-button-small uk-button-cyan uk-border-rounded uk-text-tiny uk-margin-top" title="<?php echo $item->title; ?>" target="_self">Read More</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } elseif ($fieldslist[1]->value == 'right') { ?>
                    <div class="uk-width-1-1">
                        <div uk-grid>
                            <div class="item uk-width-1-1 uk-width-2-3@m">
                                <div class="contentWrapper">
                                    <?php if ($params->get('link_titles') == 1) : ?>
                                        <a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
                                    <?php else : ?>
                                        <h4 class="font uk-margin-remove"><?php echo $item->title; ?></h4>
                                    <?php endif; ?>
                                    <?php if ($params->get('show_introtext')) : ?>
                                        <p class="uk-margin-small-top uk-margin-remove-bottom"><?php echo $item->displayIntrotext; ?></p>
                                    <?php endif; ?>
                                    <?php if ($params->get('show_readmore') && $item->fulltext != '') : ?>
                                        <a href="<?php echo $item->link; ?>" class="uk-button uk-button-small uk-button-cyan uk-border-rounded uk-text-tiny uk-margin-top" title="<?php echo $item->title; ?>" target="_self">Read More</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item uk-width-1-1 uk-width-1-3@m">
                                <div class="imgWrapper">
                                    <img src="<?php echo JURI::base(); ?>images/homeGrid/pic4.jpg" width="640" height="360" alt="Connect with us through podcasts and webinars" class="uk-display-inline-block uk-border-rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                <div class="item uk-width-1-1 uk-width-1-<?php echo $fieldslist[0]->value; ?>@m">
                    <?php if (json_decode($item->images)->image_intro != '') { ?>
                    <div class="imgWrapper uk-margin-bottom">
                        <img src="<?php echo json_decode($item->images)->image_intro; ?>" width="640" height="360" alt="<?php echo $item->title; ?>" class="uk-display-inline-block uk-border-rounded">
                    </div>
                    <?php } ?>
                    <div class="contentWrapper">
                        <?php if ($params->get('link_titles') == 1) : ?>
                            <a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
                        <?php else : ?>
                            <h4 class="font uk-margin-remove"><?php echo $item->title; ?></h4>
                        <?php endif; ?>
                        <?php if ($params->get('show_introtext')) : ?>
                            <p class="uk-margin-small-top uk-margin-remove-bottom"><?php echo $item->displayIntrotext; ?></p>
                        <?php endif; ?>
                        <?php if ($params->get('show_readmore') && $item->fulltext != '') : ?>
                            <a href="<?php echo $item->link; ?>" class="uk-button uk-button-small uk-button-cyan uk-border-rounded uk-text-tiny uk-margin-top" title="<?php echo $item->title; ?>" target="_self">Read More</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php } ?>
                <?php if ($fieldslist[3]->value == 1) { ?>
                    <div class="uk-width-1-1"><hr class="uk-margin-remove"></div>
                <?php } ?>
            <?php endforeach; ?>
        </div>
    <?php $kh++; endforeach; ?>
</div>
<?php else : ?>
<?php endif; ?>