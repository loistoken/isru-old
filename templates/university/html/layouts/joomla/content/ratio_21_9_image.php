<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
$params = $displayData->params;
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
    <?php $imgfloat = empty($images->float_intro) ? $params->get('float_intro') : $images->float_intro; ?>
    <div uk-slideshow="ratio: 21:9">
        <ul class="uk-slideshow-items">
            <li class="uk-border-rounded uk-overflow-hidden">
                <img <?php if ($images->image_intro_caption) : echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"'; endif; ?> src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" itemprop="image" uk-cover />
            </li>
        </ul>
    </div>
<?php endif; ?>