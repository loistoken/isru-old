<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$msgList = $displayData['msgList'];

?>
<div id="system-message-container">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<div id="system-message">
			<?php foreach ($msgList as $type => $msgs) : ?>
				<div class="alert alert-<?php echo $type; ?> uk-alert-<?php echo $type; ?> uk-margin-medium-bottom" uk-alert>
					<?php // This requires JS so we should add it through JS. Progressive enhancement and stuff. ?>
                    <a class="uk-alert-close close" uk-close data-dismiss="alert"></a>
					<?php if (!empty($msgs)) : ?>
                    <?php /* ?>
						<h3 class="alert-heading font"><?php echo JText::_($type); ?></h3>
                    <?php */ ?>
                        <?php foreach ($msgs as $msg) : ?>
                            <p class="alert-message font uk-text-small"><?php echo $msg; ?></p>
                        <?php endforeach; ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>