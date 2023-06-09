<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
$listOrder	= $this->escape($this->state->get('list.ordering', 'level'));
$listDirn	= $this->escape($this->state->get('list.direction', 'ASC')); ?>

<table class="table table-striped adminlist">
	<thead>
		<th width="1%" align="center" class="small hidden-phone"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
		<th class="small hidden-phone"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_URL', 'url', $listDirn, $listOrder); ?></th>
		<th class="center small" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_TITLE', 'title', $listDirn, $listOrder); ?></th>
		<th class="center small" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_KEYWORDS', 'keywords', $listDirn, $listOrder); ?></th>
		<th class="center small" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_DESCRIPTION', 'description', $listDirn, $listOrder); ?></th>
		<th width="6%" class="center small hidden-phone" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_LEVEL', 'level', $listDirn, $listOrder); ?></th>
		<th width="6%" class="center small" align="center"><?php echo JHtml::_('searchtools.sort', 'COM_RSSEO_PAGES_GRADE', 'grade', $listDirn, $listOrder); ?></th>
		<th width="1%" align="center" class="center small hidden-phone"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
	</thead>
	<tbody>
		<?php foreach ($this->items as $i => $item) { ?>
		<?php $url = rsseoHelper::showURL($item->url, $item->sef); ?>
		<?php if ($this->config->crawler_type == 'ajax') { ?>
		<?php $onchange = 'RSSeo.simpleCrawl(this, \''.addslashes(JUri::root().$item->url).'\', \''.$item->id.'\');'; ?>
		<?php } else { ?>
		<?php $onchange = 'RSSeo.saveSimpleCrawl(this);'; ?>
		<?php } ?>
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
			<td align="center" class="center has-context rstd">
				<textarea name="title[<?php echo $item->id; ?>]" onchange="<?php echo $onchange; ?>"><?php echo $item->title; ?></textarea>
			</td>
			
			<td align="center" class="center has-context rstd">
				<textarea name="keywords[<?php echo $item->id; ?>]" onchange="<?php echo $onchange; ?>"><?php echo $item->keywords; ?></textarea>
			</td>
			
			<td align="center" class="center has-context rstd">
				<textarea name="description[<?php echo $item->id; ?>]" onchange="<?php echo $onchange; ?>"><?php echo $item->description; ?></textarea>
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
				<?php echo $item->id; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
</table>