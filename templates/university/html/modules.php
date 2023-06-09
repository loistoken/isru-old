<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>

<?php
/*
 * xhtml (divs and font header tags)
 * With the new advanced parameter it does the same as the html5 chrome
 */
function modChrome_aside($module, &$params, &$attribs)
{
    $moduleTag      = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
    $headerTag      = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
    $bootstrapSize  = (int) $params->get('bootstrap_size', 0);
    $moduleClass    = $bootstrapSize !== 0 ? ' span' . $bootstrapSize : '';

    // Temporarily store header class in variable
    $headerClass    = $params->get('header_class');
    $headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

    if (!empty ($module->content)) : ?>
        <<?php echo $moduleTag; ?> class="moduletable">
        <div class="<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') . $moduleClass; ?>">
        <?php if ((bool) $module->showtitle) : ?>
            <<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
        <?php endif; ?>
        <?php echo $module->content; ?>
        </div>
        </<?php echo $moduleTag; ?>>
    <?php endif;
}
?>