<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator'); ?>

<div class="container-fluid">
	<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=statistics&layout=pageviews&tmpl=component&id='.JFactory::getApplication()->input->getInt('id',0));?>" method="post" name="adminForm" id="adminForm">
		<table class="table table-striped adminlist">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_RSSEO_CHART_DATE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_IP'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_USER'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_AGENT'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_REFERER'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_PAGE'); ?></th>
					<th><?php echo JText::_('COM_RSSEO_TIME_ON_PAGE'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->pageviews as $i => $item) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td><?php echo JHtml::_('date', $item->date, $this->config->global_dateformat); ?></td>
					<td><?php echo rsseoHelper::obfuscateIP($item->ip); ?></td>
					<td><?php echo $item->username ? $item->username : JText::_('COM_RSSEO_GUEST'); ?></td>
					<td><?php echo $item->agent; ?></td>
					<td><?php echo $item->referer; ?></td>
					<td><a href="<?php echo JUri::root().rsseoHelper::getSef($item->page); ?>" target="_blank"><?php echo empty($item->page) ? JUri::root() : rsseoHelper::getSef($item->page); ?></a></td>
					<td><?php echo $item->time; ?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7" class="center" align="center">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
		
		<?php echo JHTML::_( 'form.token' ); ?>
		<input type="hidden" name="task" value="" />
	</form>
</div>