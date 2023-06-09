<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JText::script('COM_RSSEO_LOAD_MORE');
JText::script('COM_RSSEO_LOADING');  ?>

<script type="text/javascript">google.load('visualization', '1', {packages: ['corechart', 'line']});</script>
<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=statistics');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10 j-main-container">
		<div class="row-fluid">
			<?php if (!$this->config->track_visitors) { ?><div class="alert alert-danger"><?php echo JText::_('COM_RSSEO_TRACK_VISITORS_DISABLED'); ?></div><?php } ?>
			<div class="well">
				<div id="rsseo-filter">
					<div class="btn-group pull-right">
						<button class="btn btn-info button" type="button" onclick="RSSeo.updateVisitors();"><?php echo JText::_('COM_RSSEO_GLOBAL_UPDATE'); ?></button>
					</div>
					<div class="btn-group pull-right">&nbsp;</div>
					<div class="btn-group pull-right">
						<?php echo JHTML::_('calendar', $this->to, 'rsto', 'rsto', '%Y-%m-%d' , array('class' => 'input-small')); ?>
					</div>
					<div class="btn-group pull-right">&nbsp;</div>
					<div class="btn-group pull-right">
						<?php echo JHTML::_('calendar', $this->from, 'rsfrom', 'rsfrom', '%Y-%m-%d' , array('class' => 'input-small')); ?>
					</div>
					<div class="clearfix clr"> </div>
				</div>
				
				<div class="rsseo-stats">
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="icon-user rsseo-box-icon-visitors"></i>
						</div>
						<div class="rsseo-box-content">
							<strong id="total-visitors"><?php echo $this->totalvisitors; ?></strong>
							<span><?php echo JText::_('COM_RSSEO_TOTAL_VISITORS'); ?></span>
						</div>
					</div>
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="icon-eye rsseo-box-icon-pageviews"></i>
						</div>
						<div class="rsseo-box-content">
							<strong id="total-pageviews"><?php echo $this->totalpageviews; ?></strong>
							<span><?php echo JText::_('COM_RSSEO_TOTAL_PAGEVIEWS'); ?></span>
						</div>
					</div>
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="icon-user rsseo-box-icon-visitors"></i>
						</div>
						<div class="rsseo-box-content">
							<strong id="visitors-timeframe"><?php echo $this->totalvisitorst; ?></strong>
							<span><?php echo JText::_('COM_RSSEO_TOTAL_VISITORS_IN_PERIOD'); ?></span>
						</div>
					</div>
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="icon-eye rsseo-box-icon-pageviews"></i>
						</div>
						<div class="rsseo-box-content">
							<strong id="pageviews-timeframe"><?php echo $this->totalpageviewst; ?></strong>
							<span><?php echo JText::_('COM_RSSEO_TOTAL_PAGEVIEWS_IN_PERIOD'); ?></span>
						</div>
					</div>
				</div>
				
				<hr />
				
				<div class="visitors-container">
					<div class="row-fluid">
						<div class="chart_visitors_container span6">
							<h3 class="text-center"><?php echo JText::_('COM_RSSEO_CHART_VISITS_TITLE'); ?></h3>
							<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'chart_visitors_loading', 'style' => 'display:none;'), true); ?>
							<div id="chart_visitors"></div>
						</div>
						<div class="chart_pageviews_container span6">
							<h3 class="text-center"><?php echo JText::_('COM_RSSEO_CHART_PAGEVIEWS_TITLE'); ?></h3>
							<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'chart_pageviews_loading', 'style' => 'display:none;'), true); ?>
							<div id="chart_pageviews"></div>
						</div>
					</div>
					<div class="clr"></div>
					
					<hr />
					
					<div class="row-fluid" id="visitors-table">
						<table class="table adminlist">
							<thead>
								<tr>
									<th width="1%" align="center" class="small hidden-phone"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
									<th width="15%"><?php echo JText::_('COM_RSSEO_CHART_DATE'); ?></th>
									<th width="15%"><?php echo JText::_('COM_RSSEO_IP'); ?></th>
									<th><?php echo JText::_('COM_RSSEO_EXIT_PAGE'); ?></th>
									<th width="10%">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->visitors as $i => $visitor) { ?>
								<tr>
									<td class="center small hidden-phone"><?php echo JHTML::_('grid.id', $i, $visitor->session_id); ?></td>
									<td><?php echo JHtml::_('date', $visitor->date, $this->config->global_dateformat); ?></td>
									<td><?php echo rsseoHelper::obfuscateIP($visitor->ip); ?></td>
									<td><a href="<?php echo JUri::root().rsseoHelper::getSef($visitor->page); ?>" target="_blank"><?php echo empty($visitor->page) ? JUri::root() : rsseoHelper::getSef($visitor->page); ?></a></td>
									<td><a href="javascript:void(0);" onclick="RSSeo.showModal('<?php echo JRoute::_('index.php?option=com_rsseo&view=statistics&layout=pageviews&tmpl=component&id='.$visitor->id,false); ?>');"><?php echo JText::_('COM_RSSEO_VIEW_PAGEVIEWS'); ?></a></td>
								</tr>
							<?php } ?>
							</tbody>
							<tfoot>
								<tr id="visitors-pagination" <?php if ($this->total <= $this->count) { ?>style="display: none;<?php } ?>">
									<td colspan="5" class="center" align="center">
										<button type="button" class="rsseo-btn" onclick="RSSeo.loadVisitors()"><?php echo JText::_('COM_RSSEO_LOAD_MORE'); ?></button>
									</td>
								</tr>
							</tfoot>
						</table>
						<span id="visitors-total" style="display: none;"><?php echo $this->total; ?></span>
						<input type="hidden" name="boxchecked" value="0" />
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
</form>

<script type="text/javascript">
jQuery(document).ready(function() {
	RSSeo.updateCharts();
	
	jQuery(window).resize(function() {
		RSSeo.updateCharts();
	});
	
	jQuery('a[href="#stat-visitors"]').on('click', function() {
		RSSeo.updateCharts();
	});
	
	if (typeof jQuery.fn.tooltip != 'undefined') {
		jQuery('#rsfrom, #rsto').tooltip('destroy');
	}
});
</script>

<?php echo JHtml::_('bootstrap.renderModal', 'rsseoModal', array('title' => JText::_('COM_RSSEO_CHART_PAGEVIEWS_TITLE'), 'height' => 600, 'bodyHeight' => 70)); ?>