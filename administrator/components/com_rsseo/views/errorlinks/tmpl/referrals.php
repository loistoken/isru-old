<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive'); ?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<table class="table table-striped adminlist">
				<thead>
					<th><?php echo JText::_('COM_RSSEO_ERROR_LINK_REFERAL'); ?></th>
					<th width="10%" align="center" class="center hidden-phone"><?php echo JText::_('COM_RSSEO_ERROR_LINK_REFERAL_DATE'); ?></th>
				</thead>
				<tbody>
					<?php foreach ($this->referrals as $i => $item) { ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="nowrap has-context">
							<?php echo $this->escape($item->referer); ?>
						</td>
						<td align="center" class="center hidden-phone">
							<?php echo JHtml::_('date', $item->date, rsseoHelper::getConfig('global_dateformat')); ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>