<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>
<?php if (is_array($this->browsers)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_BROWSERS'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_GA_BROWSERS_BROWSER'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_BROWSERS_VISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_BROWSERS_PAGEVISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_BROWSERS_BOUNCERATE'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_BROWSERS_AVGTIME'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->browsers)) { ?>
			<?php foreach ($this->browsers as $i => $result) { ?>
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
<?php } else echo $this->browsers; ?>