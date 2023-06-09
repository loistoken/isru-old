<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>
<?php if (is_array($this->sources)) { ?>
	<fieldset>
		<legend><?php echo JText::_('COM_RSSEO_GA_SOURCES'); ?></legend>
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_GA_SOURCES_SOURCE'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_SOURCES_VISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_SOURCES_NEWVISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_SOURCES_PAGEVISITS'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_SOURCES_BOUNCERATE'); ?></th>
					<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GA_SOURCES_AVGTIME'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($this->sources)) { ?>
			<?php foreach ($this->sources as $i => $result) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo $result->source; ?></td>
					<td align="center" class="center"><?php echo $result->visits; ?></td>
					<td align="center" class="center"><?php echo $result->newvisits; ?></td>
					<td align="center" class="center"><?php echo $result->pagesvisits; ?></td>
					<td align="center" class="center"><?php echo $result->bouncerate; ?></td>
					<td align="center" class="center"><?php echo $result->avgtimesite; ?></td>
				</tr>
			<?php } ?>
			<?php } ?>
			</tbody>
		</table>
	</fieldset>
<?php } else echo $this->sources; ?>