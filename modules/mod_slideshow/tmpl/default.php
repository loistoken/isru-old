<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$slides = json_decode( $params->get('slides'),true);
$total = count($slides['img']);
?>
<section class="<?php echo $moduleclass_sfx; ?>">
    <div class="uk-position-relative uk-visible-toggle uk-light" uk-slideshow="ratio: <?php echo $params->get('ratio'); ?>; animation: <?php echo $params->get('animation'); ?>; autoplay: <?php echo $params->get('autoplay'); ?>; autoplay-interval: <?php echo $params->get('interval'); ?>; min-height: 250">
        <div class="uk-slideshow-items">
            <?php for($i=0;$i<$total;$i++) { ?>
                <?php if ($slides['img'][$i] != '') { ?>
                    <div>
                        <img src="<?php echo $slides['img'][$i]; ?>" alt="<?php echo $slides['title'][$i]; ?>" uk-cover width="1170" height="390">
                        <div class="uk-position-<?php echo $slides['pos'][$i]; if ($slides['overlaymargin'][$i]) echo ' uk-position-large'; if ($slides['overlay'][$i]) echo ' uk-overlay uk-overlay-primary'; ?> uk-text-center uk-light">
                            <h2 class="uk-margin-remove font"><?php echo $slides['title'][$i]; ?></h2>
                            <p class="uk-margin-remove font"><?php echo $slides['text'][$i]; ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <?php if ($params->get('sidenav')) { ?>
        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
        <?php } if ($params->get('dotnav')) { ?>
        <div class="uk-position-bottom-center uk-position-small">
            <ul class="uk-dotnav">
                <?php for($j=0;$j<$total;$j++) { ?>
                <li uk-slideshow-item="<?php echo $j; ?>"><a href="#">...</a></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>
</section>