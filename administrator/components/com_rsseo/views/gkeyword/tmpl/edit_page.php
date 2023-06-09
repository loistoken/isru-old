<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); 
$avg = $clicks = $impressions = 0; ?>

<div class="container-fluid">
	<div class="row-fluid">
		<table class="table table-striped table-hover rsseo_import_table">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_GKEYWORD_PAGE'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_IMPRESSIONS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_CLICKS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_AVG_POSITION'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_CTR'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->items as $data) { ?>
				<?php $impressions += (int) $data->impressions;?>
				<?php $clicks += (int) $data->clicks;?>
				<?php $avg += $data->avgposition;?>
				<tr>
					<td><?php echo $data->page; ?></td>
					<td align="center" class="center"><?php echo $data->impressions; ?></td>
					<td align="center" class="center"><?php echo $data->clicks; ?></td>
					<td align="center" class="center"><?php echo number_format($data->avgposition, 2); ?></td>
					<td align="center" class="center"><?php echo number_format(($data->clicks / $data->impressions) * 100, 2); ?>%</td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td><?php echo JText::_('COM_RSSEO_GKEYWORD_TOTALS'); ?></td>
					<td align="center" class="center"><?php echo $impressions; ?></td>
					<td align="center" class="center"><?php echo $clicks; ?></td>
					<td align="center" class="center"><?php echo number_format($avg / count($this->items), 2); ?></td>
					<td align="center" class="center"><?php echo number_format(($clicks / $impressions) * 100, 2); ?>%</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<?php die; ?>