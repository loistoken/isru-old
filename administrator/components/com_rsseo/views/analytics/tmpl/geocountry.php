<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>
<?php if (is_array($this->geocountry)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_GC_VISITS_BY_COUNTRY'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_GA_GC_COUNTRY'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_GC_VISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_GC_NEW_VISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_GC_NEW_VISITS_P'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_GC_BOUNCERATE'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_GC_PAGEVISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_GC_AVGTIME'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->geocountry)) { ?>
			<?php $i = 0; ?>
			<?php foreach ($this->geocountry as $result) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo $result->country; ?></td>
					<td align="center" class="center"><?php echo $result->visits; ?></td>
					<td align="center" class="center"><?php echo $result->newvisits; ?></td>
					<td align="center" class="center"><?php echo $result->newvisitsp; ?></td>
					<td align="center" class="center"><?php echo $result->bouncerate; ?></td>
					<td align="center" class="center"><?php echo $result->pagesvisits; ?></td>
					<td align="center" class="center"><?php echo $result->avgtimesite; ?></td>
				</tr>
			<?php $i++; ?>
			<?php } ?>
			<?php } ?>
			</tbody>
		</table>
	</fieldset>
<?php } else echo $this->geocountry; ?>