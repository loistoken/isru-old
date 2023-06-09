<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');

$listOrder	= $this->escape($this->state->get('list.ordering','id'));
$listDirn	= $this->escape($this->state->get('list.direction','asc')); ?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=errorlinks');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10 j-main-container">
		
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		
		<table class="table table-striped">
			<thead>
				<th width="1%" align="center" class="hidden-phone center"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
				<th><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_ERROR_LINK_URL', 'url', $listDirn, $listOrder); ?></th>
				<th width="4%" align="center" class="center hidden-phone"><?php echo JText::_('COM_RSSEO_ERROR_LINK_REFERRALS'); ?></th>
				<th width="4%" align="center" class="center hidden-phone"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_ERROR_LINK_CODE', 'code', $listDirn, $listOrder); ?></th>
				<th width="3%" align="center" class="center hidden-phone"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_ERROR_LINK_URL_COUNT', 'count', $listDirn, $listOrder); ?></th>
				<th width="3%" align="center" class="center hidden-phone"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item) { ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone"><?php echo JHTML::_('grid.id', $i, $item->id); ?></td>
					<td class="nowrap has-context">
						<?php echo $this->escape($item->url); ?>
					</td>
					<td align="center" class="center hidden-phone">
						<?php if ($item->referer) { ?>
						<a href="javascript:void(0)" onclick="RSSeo.showModal('<?php echo JRoute::_('index.php?option=com_rsseo&view=errorlinks&layout=referrals&tmpl=component&id='.$item->id); ?>');">
							<?php echo JText::_('COM_RSSEO_ERROR_LINK_REFERRALS_VIEW'); ?>
						</a>
						<?php } else { ?>
						-
						<?php } ?>
					</td>
					<td align="center" class="center hidden-phone">
						<?php echo $item->code; ?>
					</td>
					<td align="center" class="center hidden-phone">
						<?php echo $item->count; ?>
					</td>
					<td align="center" class="center hidden-phone">
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
</form>

<?php echo JHtml::_('bootstrap.renderModal', 'rsseoModal', array('title' => JText::_('COM_RSSEO_ERROR_LINK_REFERRALS'), 'height' => 600, 'bodyHeight' => 70)); ?>