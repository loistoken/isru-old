<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$list = $displayData['list'];
?>
<ul class="uk-pagination font uk-text-12" uk-margin>
    <?php /* ?><li class="pagination-start"><?php echo $list['start']['data']; ?></li><?php */ ?>
    <?php /* ?><li class="pagination-end"><?php echo $list['end']['data']; ?></li><?php */ ?>
	<li class="pagination-prev"><?php echo $list['previous']['data']; ?></li>
	<?php foreach ($list['pages'] as $page) : ?>
		<?php echo '<li>' . $page['data'] . '</li>'; ?>
	<?php endforeach; ?>
	<li class="pagination-next"><?php echo $list['next']['data']; ?></li>
</ul>