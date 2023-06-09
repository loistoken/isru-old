<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>
<?php if (is_array($this->mobiles)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_MOBILES'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th width="15%"><?php echo JText::_('COM_RSSEO_GA_MOBILES_OS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_MOBILES_VISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_MOBILES_PAGEVISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_MOBILES_BOUNCERATE'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_MOBILES_AVGTIME'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->mobiles)) { ?>
			<?php foreach ($this->mobiles as $i => $result) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo $result->browser; ?></td>
					<td align="center" class="center"><?php echo $result->visits; ?></td>
					<td align="center" class="center"><?php echo $result->pagesvisits; ?></td>
					<td align="center" class="center"><?php echo $result->bouncerate; ?></td>
					<td align="center" class="center"><?php echo $result->avgtimesite; ?></td>
				</tr>
			<?php } ?>
			<?php } ?>
			</tbody>
		</table>
	</fieldset>
<?php } else echo $this->mobiles; ?>