<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');

$listOrder	= $this->escape($this->state->get('list.ordering', 'id'));
$listDirn	= $this->escape($this->state->get('list.direction', 'ASC'));
$parent		= $this->escape($this->state->get('filter.parent')); ?>

<script type="text/javascript">	
jQuery(document).ready(function() {
	<?php if (!$this->config->enable_age) { ?>jQuery('#list_fullordering option[value="age ASC"], #list_fullordering option[value="age DESC"]').remove();<?php } ?>
	<?php if (!$this->config->enable_googlep) { ?>jQuery('#list_fullordering option[value="googlep ASC"], #list_fullordering option[value="googlep DESC"]').remove();<?php } ?>
	<?php if (!$this->config->enable_googleb) { ?>jQuery('#list_fullordering option[value="googleb ASC"], #list_fullordering option[value="googleb DESC"]').remove();<?php } ?>
	<?php if (!$this->config->enable_googler) { ?>jQuery('#list_fullordering option[value="googler ASC"], #list_fullordering option[value="googler DESC"]').remove();<?php } ?>
	<?php if (!$this->config->enable_bingp) { ?>jQuery('#list_fullordering option[value="bingp ASC"], #list_fullordering option[value="bingp DESC"]').remove();<?php } ?>
	<?php if (!$this->config->enable_bingb) { ?>jQuery('#list_fullordering option[value="bingb ASC"], #list_fullordering option[value="bingb DESC"]').remove();<?php } ?>
	<?php if (!$this->config->enable_alexa) { ?>jQuery('#list_fullordering option[value="alexa ASC"], #list_fullordering option[value="alexa DESC"]').remove();<?php } ?>
	
	<?php if (!$this->config->enable_moz) { ?>
	jQuery('#list_fullordering option[value="mozpagerank ASC"]').remove();
	jQuery('#list_fullordering option[value="mozpagerank DESC"]').remove();
	jQuery('#list_fullordering option[value="mozda ASC"]').remove();
	jQuery('#list_fullordering option[value="mozda DESC"]').remove();
	jQuery('#list_fullordering option[value="mozpa ASC"]').remove();
	jQuery('#list_fullordering option[value="mozpa DESC"]').remove();
	<?php } ?>

	jQuery('#list_fullordering').trigger('liszt:updated');
});

Joomla.submitbutton = function(task) {
	if (task == 'back') {
		jQuery('#filter_parent').val(0);
		Joomla.submitform();
		return false;
	} else {
		Joomla.submitform(task);
	}
}
</script>

<div class="row-fluid">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10 j-main-container">
		<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=competitors');?>" method="post" name="adminForm" id="adminForm">
			
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			
			<table class="table table-striped">
				<thead>
					<th width="1%" align="center" class="small hidden-phone center"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
					<?php if (!$parent) { ?>
					<th width="2%" class="small hidden-phone"><?php echo JText::_('COM_RSSEO_COMPETITORS_HISTORY'); ?></th>
					<th class="small"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_COMPETITOR', 'name', $listDirn, $listOrder); ?></th>
					<?php } ?>
					<?php if ($this->config->enable_age) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort','COM_RSSEO_COMPETITORS_DOMAIN_AGE', 'age', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_googlep) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort','COM_RSSEO_COMPETITORS_GOOGLE_PAGES', 'googlep', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_googleb) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort','COM_RSSEO_COMPETITORS_GOOGLE_BACKLINKS', 'googleb', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_googler) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort','COM_RSSEO_COMPETITORS_GOOGLE_RELATED', 'googler', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_bingp) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_BING_PAGES', 'bingp', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_bingb) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_BING_BACKLINKS', 'bingb', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_alexa) { ?><th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_ALEXA_RANK', 'alexa', $listDirn, $listOrder); ?></th><?php } ?>
					<?php if ($this->config->enable_moz) { ?>
					<th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_MOZ_RANK', 'mozpagerank', $listDirn, $listOrder); ?></th>
					<th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_MOZ_PA', 'mozpa', $listDirn, $listOrder); ?></th>
					<th class="center small hidden-phone" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_MOZ_DA', 'mozda', $listDirn, $listOrder); ?></th>
					<?php } ?>
					
					<th class="small center hidden-phone" align="center" width="5%"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_COMPETITORS_DATE', 'date', $listDirn, $listOrder); ?></th>
					<?php if (!$parent) { ?>
					<th class="small center hidden-phone" align="center" width="5%"><?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?></th>
					<?php } ?>
					<th width="1%" align="center" class="small center hidden-phone"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
				</thead>
				<tbody id="competitorsTable">
					<?php foreach ($this->items as $i => $item) { ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center small hidden-phone"><?php echo JHTML::_('grid.id', $i, $item->id); ?></td>
						<?php if (!$parent) { ?>
						<td align="center" class="center small hidden-phone">
							<a href="javascript:void(0)" onclick="RSSeo.competitorHistory(<?php echo $item->id; ?>)">
								<span class="icon-list"></span>
							</a>
						</td>
						<td class="nowrap small has-context">
							<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=competitor.edit&id='.$item->id); ?>" id="competitor<?php echo $item->id; ?>">
								<?php echo $this->escape($item->name); ?>
							</a>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_age) { ?>
						<td align="center" class="center small hidden-phone">
							<span id="age<?php echo $item->id; ?>">
								<?php echo (int) $item->age <= 0 ? '-' : rsseoHelper::convertage($item->age); ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_googlep) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->googlepbadge; ?>" id="googlep<?php echo $item->id; ?>">
								<?php echo $item->googlep; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_googleb) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->googlebbadge; ?>" id="googleb<?php echo $item->id; ?>">
								<?php echo $item->googleb; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_googler) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->googlerbadge; ?>" id="googler<?php echo $item->id; ?>">
								<?php echo $item->googler; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_bingp) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->bingpbadge; ?>" id="bingp<?php echo $item->id; ?>">
								<?php echo $item->bingp; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_bingb) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->bingbbadge; ?>" id="bingb<?php echo $item->id; ?>">
								<?php echo $item->bingb; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_alexa) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->alexabadge; ?>" id="alexa<?php echo $item->id; ?>">
								<?php echo $item->alexa; ?>
							</span>
						</td>
						<?php } ?>
						
						<?php if ($this->config->enable_moz) { ?>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->mozpagerankbadge; ?>" id="mozpagerank<?php echo $item->id; ?>">
								<?php echo $item->mozpagerank; ?>
							</span>
						</td>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->mozpabadge; ?>" id="mozpa<?php echo $item->id; ?>">
								<?php echo $item->mozpa; ?>
							</span>
						</td>
						<td align="center" class="center small hidden-phone">
							<span class="badge badge-<?php echo $item->mozdabadge; ?>" id="mozda<?php echo $item->id; ?>">
								<?php echo $item->mozda; ?>
							</span>
						</td>
						<?php } ?>
						
						<td align="center" class="center small hidden-phone">
							<span id="date<?php echo $item->id; ?>">
								<?php echo JHtml::_('date', $item->date, $this->config->global_dateformat); ?>
							</span>
						</td>
						
						<?php if (!$parent) { ?>
						<td align="center" class="center small hidden-phone">
							<a href="javascript:void(0)" onclick="RSSeo.competitor(<?php echo $item->id; ?>)" id="refresh<?php echo $item->id; ?>">
								<?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?>
							</a>
							<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'loading'.$item->id, 'style' => 'display:none;'), true); ?>
						</td>
						<?php } ?>
						
						<td align="center" class="center small hidden-phone">
							<?php echo $item->id; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="18">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
			</table>
			
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="filter[parent]" id="filter_parent" value="<?php echo $this->state->get('filter.parent'); ?>" />
		</form>
	</div>
</div>