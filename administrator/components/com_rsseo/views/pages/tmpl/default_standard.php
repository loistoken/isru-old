<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
$listOrder	= $this->escape($this->state->get('list.ordering', 'level'));
$listDirn	= $this->escape($this->state->get('list.direction', 'ASC')); ?>

<style type="text/css">.tooltip { z-index: 1500; }</style>

<table class="table table-striped adminlist">
	<thead>
		<th width="1%" align="center" class="small hidden-phone"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
		<th class="small hidden-phone"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_URL', 'url', $listDirn, $listOrder); ?></th>
		<th class="center small" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_TITLE', 'title', $listDirn, $listOrder); ?></th>
		<th width="6%" class="center small hidden-phone" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_LEVEL', 'level', $listDirn, $listOrder); ?></th>
		<th width="6%" class="center small" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_GRADE', 'grade', $listDirn, $listOrder); ?></th>
		<th width="8%" class="center small hidden-phone" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_LAST_CRAWLED', 'date', $listDirn, $listOrder); ?></th>
		<th width="1%" class="center small hidden-phone" align="center"><?php echo JText::_('COM_RSSEO_PAGES_STATUS'); ?></th>
		<th width="7%" class="center small hidden-phone" align="center"><?php echo JText::_('COM_RSSEO_PAGES_PAGE_MODIFIED'); ?></th>
		<th width="7% "class="center small hidden-phone" align="center"><?php echo JText::_('COM_RSSEO_PAGES_ADD_TO_SITEMAP'); ?></th>
		<th width="5%" class="center small hidden-phone" align="center"><?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?></th>
		<th width="1%" align="center" class="center small hidden-phone"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_HITS', 'hits', $listDirn, $listOrder); ?></th>
		<th width="1%" align="center" class="center small hidden-phone"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
	</thead>
	<tbody>
		<?php foreach ($this->items as $i => $item) { ?>
		<?php $url = rsseoHelper::showURL($item->url, $item->sef); ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center small hidden-phone"><?php echo JHTML::_('grid.id', $i, $item->id); ?></td>
			<td class="small hidden-phone rstd">
				<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=page.edit&id='.$item->id); ?>">
					<?php echo $item->url; ?> 
				</a> 
				<a href="<?php echo JURI::root().$this->escape($url); ?>" target="_blank">
					<i class="fa fa-external-link"></i>
				</a>
				<?php if ($this->sef && $this->config->enable_sef) echo $item->sef ? '<br /><strong>'.$url.'</strong>' : ''; ?>
			</td>
			<td class="small has-context rstd">
				<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=page.edit&id='.$item->id); ?>">
					<span id="title<?php echo $item->id; ?>">
						<?php echo empty($item->title) ? JText::_('COM_RSSEO_GLOBAL_NO_TITLE') : $this->escape($item->title); ?>
					</span>
				</a>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php echo ($item->level >= 127) ? JText::_('COM_RSSEO_GLOBAL_UNDEFINED') : $item->level; ?>
			</td>
			
			<td align="center" class="center small">
				<?php $grade = ($item->grade <= 0) ? 0 : ceil($item->grade); ?>
				<div class="rsj-progress" style="width: 100%">
					<span id="page<?php echo $item->id; ?>" style="width: <?php echo $grade; ?>%;" class="<?php echo $item->color; ?>">
						<span><?php echo $grade; ?>%</span>
					</span>
				</div>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<span id="date<?php echo $item->id; ?>">
					<?php echo JHtml::_('date', $item->date, $this->config->global_dateformat); ?>
				</span>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php echo JHtml::_('jgrid.published', $item->published, $i, 'pages.'); ?>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php echo JHtml::_('icon.modified', $item->modified, $item->id); ?>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php echo JHtml::_('icon.insitemap', $item->insitemap, $i); ?>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php if ($this->config->crawler_type == 'ajax') { ?>
				<a href="javascript:void(0)" onclick="RSSeo.refresh('<?php echo JUri::root().$item->url; ?>',<?php echo $item->id; ?>, 1)" id="restore<?php echo $item->id; ?>" style="display:none;">&nbsp;</a>
				<a href="javascript:void(0)" onclick="RSSeo.refresh('<?php echo JUri::root().$item->url; ?>',<?php echo $item->id; ?>, 0)" id="refresh<?php echo $item->id; ?>">
				<?php } else { ?>
				<a href="javascript:void(0)" onclick="RSSeo.checkPage(<?php echo $item->id; ?>,0)" id="refresh<?php echo $item->id; ?>">
				<?php } ?>
					<?php echo JText::_('COM_RSSEO_GLOBAL_REFRESH'); ?>
				</a>
				
				<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'loading'.$item->id, 'style' => 'display:none;'), true); ?>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php echo $item->hits; ?>
			</td>
			
			<td align="center" class="center small hidden-phone">
				<?php echo $item->id; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="16">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
</table>

<?php $footer = '<a href="javascript:void(0)" onclick="Joomla.submitbutton(\'pages.batch\');" class="btn btn-primary">'.JText::_('COM_RSSEO_APPLY').'</a><a href="javascript:void(0)" data-dismiss="modal" class="btn">'.JText::_('COM_RSSEO_GLOBAL_CLOSE').'</a>'; ?>
<?php echo JHtml::_('bootstrap.renderModal', 'modal-batchpages', array('title' => JText::_('COM_RSSEO_BATCH_OPTIONS'), 'footer' => $footer, 'bodyHeight' => 70), $this->loadTemplate('batch')); ?>