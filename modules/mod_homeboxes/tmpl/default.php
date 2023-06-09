<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$boxes = json_decode($params->get('boxes'),true);
$total = count($boxes['title']);
?>
<section class="<?php echo $moduleclass_sfx; ?>">
    <div class="uk-grid-small" uk-grid>
        <?php for($i=0;$i<$total;$i++) { ?>
        <div class="uk-width-1-1 uk-width-<?php echo $boxes['width'][$i]; ?>@m">
            <div class="uk-border-rounded uk-overflow-hidden uk-display-block uk-position-relative boxBG <?php if ($boxes['overlay'][$i] == 1) echo 'darkOverlay'; ?>" style="background-color: <?php echo $boxes['bgcolor'][$i]; ?>; background-image: url(<?php echo $boxes['background'][$i]; ?>);">
                <a href="<?php echo $boxes['url'][$i] ?>" title="<?php echo $boxes['title'][$i]; ?>" class="uk-position-relative uk-position-z-index uk-background-cover uk-panel uk-flex uk-flex-<?php echo $boxes['halign'][$i]; ?> uk-flex-<?php echo $boxes['valign'][$i]; ?> uk-padding-homeTabBoxes">
                    <span class="font uk-text-tiny" style="color: <?php echo $boxes['textcolor'][$i]; ?>"><?php echo $boxes['title'][$i]; ?></span>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</section>