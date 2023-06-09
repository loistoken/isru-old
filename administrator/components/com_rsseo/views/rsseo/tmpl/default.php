<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); 
JText::script('COM_RSSEO_GKEYWORD_DATE');
JText::script('COM_RSSEO_GKEYWORD_POSITION'); ?>

<?php if ($this->keywords) { ?>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(function() {
	RSSeo.drawGoogleKeywordChartDashboard();
});

jQuery(document).ready(function() {
	jQuery(window).resize(function() {
		RSSeo.drawGoogleKeywordChartDashboard();
	});
});
</script>
<?php } ?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo'); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span9">
			<div class="row-fluid">
				<div class="rsseo-stats">
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="fa fa-<?php echo $this->info->missing_title ? 'exclamation-triangle rsseo-box-icon-color3' : 'check rsseo-box-icon-color1'; ?>"></i>
						</div>
						<div class="rsseo-box-content">
							<?php if ($this->info->missing_title) { ?>
							<strong class="rsseo-box-number">
								<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=pages&hash=title|'.md5(''), false); ?>" class="hasTooltip" title="<?php echo JText::sprintf('COM_RSSEO_MISSING_TITLES_INFO', $this->info->missing_title); ?>">
									<?php echo $this->info->missing_title; ?>
								</a>
							</strong>
							<?php } else { ?>
							<strong class="rsseo-box-number"><?php echo $this->info->missing_title; ?></strong>
							<?php } ?>
							<span><?php echo JText::_('COM_RSSEO_MISSING_TITLES'); ?></span>
						</div>
					</div>
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="fa fa-<?php echo $this->info->missing_keywords ? 'exclamation-triangle rsseo-box-icon-color3' : 'check rsseo-box-icon-color1'; ?>"></i>
						</div>
						<div class="rsseo-box-content">
							<?php if ($this->info->missing_keywords) { ?>
							<strong class="rsseo-box-number">
								<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=pages&hash=keywords|'.md5(''), false); ?>" class="hasTooltip" title="<?php echo JText::sprintf('COM_RSSEO_MISSING_KEYWORDS_INFO', $this->info->missing_keywords); ?>">
									<?php echo $this->info->missing_keywords; ?>
								</a>
							</strong>
							<?php } else { ?>
							<strong class="rsseo-box-number"><?php echo $this->info->missing_keywords; ?></strong>
							<?php } ?>
							<span><?php echo JText::_('COM_RSSEO_MISSING_KEYWORDS'); ?></span>
						</div>
					</div>
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="fa fa-<?php echo $this->info->missing_description ? 'exclamation-triangle rsseo-box-icon-color3' : 'check rsseo-box-icon-color1'; ?>"></i>
						</div>
						<div class="rsseo-box-content">
							<?php if ($this->info->missing_description) { ?>
							<strong class="rsseo-box-number">
								<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=pages&hash=description|'.md5(''), false); ?>" class="hasTooltip" title="<?php echo JText::sprintf('COM_RSSEO_MISSING_DESCRIPTION_INFO', $this->info->missing_description); ?>">
									<?php echo $this->info->missing_description; ?>
								</a>
							</strong>
							<?php } else { ?>
							<strong class="rsseo-box-number"><?php echo $this->info->missing_description; ?></strong>
							<?php } ?>
							<span><?php echo JText::_('COM_RSSEO_MISSING_DESCRIPTION'); ?></span>
						</div>
					</div>
					<div class="rsseo-box">
						<div class="rsseo-box-image">
							<i class="fa fa-check rsseo-box-icon-color2"></i>
						</div>
						<div class="rsseo-box-content">
							<strong class="rsseo-box-number"><?php echo $this->info->total_pages; ?></strong>
							<span><?php echo JText::_('COM_RSSEO_TOTAL_PAGES'); ?></span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12 well">
					<div class="pull-right rsseo-chart-filter">
						<?php echo JHTML::_('calendar', '', 'rsto', 'rsto', '%Y-%m-%d' , array('class' => 'input-small', 'onChange' => 'RSSeo.drawGoogleKeywordChartDashboard()', 'placeholder' => JText::_('COM_RSSEO_TO'))); ?>
					</div>
					<div class="pull-right rsseo-chart-filter">
						<?php echo JHTML::_('calendar', '', 'rsfrom', 'rsfrom', '%Y-%m-%d' , array('class' => 'input-small', 'onChange' => 'RSSeo.drawGoogleKeywordChartDashboard()', 'placeholder' => JText::_('COM_RSSEO_FROM'))); ?>
					</div>
					<div class="pull-right rsseo-chart-filter">
						<?php echo JHtml::_('select.groupedlist', $this->keywords, 'keyword', array('list.attr' => 'onchange="RSSeo.drawGoogleKeywordChartDashboard()"', 'id' => 'keyword', 'list.select' => '', 'group.items' => null, 'option.key.toHtml' => false, 'option.text.toHtml' => false)); ?>
					</div>
					
					<div class="clearfix clr"></div>
					<br />
					<div class="chart_container">
						<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'chart_keywords_loading', 'style' => 'display:none;'), true); ?>
						<div id="chart"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="well">
						<h4 class="center"><?php echo JText::sprintf('COM_RSSEO_STATISTICS_FOR',JURI::root()); ?></h4>
						<?php if (!empty($this->statistics)) { ?>
						<table class="table table-condensed adminlist">
						<?php foreach ($this->statistics as $type => $value) { ?>
						<?php if ($type == 'date') continue; ?>
							<tr>
								<td width="80%"><?php echo JText::_('COM_RSSEO_STATISTICS_'.strtoupper($type)); ?></td>
								<td class="center">
									<?php if ($type == 'age') {
										echo $value == -1 ? JText::_('COM_RSSEO_NA') : rsseoHelper::convertage($value);
									} else { ?>
									<span class="badge<?php if ($value != -1) echo ' badge-success'; ?>"><?php echo $value == -1 ? JText::_('COM_RSSEO_NA') : $value; ?></span>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</table>
						<?php if (isset($this->statistics['date'])) { ?>
						<small style="text-align: right; display: block;"><?php echo JText::sprintf('COM_RSSEO_STATISTICS_LAST_UPDATED',JHtml::_('date', $this->statistics['date'], rsseoHelper::getConfig('global_dateformat'))); ?></small>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
				
				<div class="span4">
					<?php if (!empty($this->pages)) { ?>
					<div class="well">
						<h4 class="center"><?php echo JText::_('COM_RSSEO_MOST_VISITED_PAGES'); ?></h4>				
						<table class="table table-condensed adminlist">
						<?php foreach ($this->pages as $page) { ?>
						<?php $url = rsseoHelper::showURL($page->url, $page->sef); ?>
						<?php $pageurl = ($page->id == 1) ? JURI::root() : $url; ?>
							<tr>
								<td width="90%" class="rstd">
									<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=page.edit&id='.$page->id); ?>">
										<?php echo $pageurl; ?>
									</a>
									&nbsp;
									<a href="<?php echo JURI::root().$this->escape($url); ?>" target="_blank">
										<i class="fa fa-external-link"></i>
									</a>
								</td>
								<td class="center"><span class="badge badge-secondary"><?php echo $page->hits; ?></span></td>
							</tr>
						<?php } ?>
						</table>
					</div>
					<?php } ?>
				</div>
				
				<div class="span4">
					<?php if (!empty($this->lastcrawled)) { ?>
					<div class="well">
						<h4 class="center"><?php echo JText::_('COM_RSSEO_LAST_CRAWLED_PAGES'); ?></h4>				
						<table class="table table-condensed adminlist">
						<?php foreach ($this->lastcrawled as $page) { ?>
						<?php $url = rsseoHelper::showURL($page->url, $page->sef); ?>
						<?php $pageurl = ($page->id == 1) ? JURI::root() : $url; ?>
							<tr>
								<td width="73%" class="rstd">
									<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=page.edit&id='.$page->id); ?>">
										<?php echo $pageurl; ?>
									</a>
									&nbsp;
									<a href="<?php echo JURI::root().$this->escape($url); ?>" target="_blank">
										<i class="fa fa-external-link"></i>
									</a>
								</td>
								<td class="center small"><?php echo JHtml::_('date', $page->date, rsseoHelper::getConfig('global_dateformat')); ?></td>
							</tr>
						<?php } ?>
						</table>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="span3">
			<ul class="nav nav-tabs nav-stacked">
				<li class="center active">
					<div class="dashboard-container">
						<div class="dashboard-info">
							<span>
								<?php echo JHtml::image('com_rsseo/rsseo.png', 'RSSeo!', array('align' => 'middle'), true); ?>
							</span>
							<table class="dashboard-table">
								<tr>
									<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_PRODUCT_VERSION') ?>: </strong></td>
									<td nowrap="nowrap"><b>RSSeo! <?php echo $this->version; ?></b></td>
								</tr>
								<tr>
									<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_COPYRIGHT_NAME') ?>: </strong></td>
									<td nowrap="nowrap">&copy; <?php echo gmdate('Y'); ?> <a href="http://www.rsjoomla.com" target="_blank">RSJoomla.com</a></td>
								</tr>
								<tr>
									<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_LICENSE_NAME') ?>: </strong></td>
									<td nowrap="nowrap">GPL Commercial License</a></td>
								</tr>
								<tr>
									<td nowrap="nowrap"><strong><?php echo JText::_('COM_RSSEO_CODE_FOR_UPDATE') ?>: </strong></td>
									<?php if (strlen($this->code) == 20) { ?>
									<td nowrap="nowrap" class="correct-code"><?php echo $this->escape($this->code); ?></td>
									<?php } elseif ($this->code) { ?>
									<td nowrap="nowrap" class="incorrect-code"><?php echo $this->escape($this->code); ?></td>
									<?php } else { ?>
									<td nowrap="nowrap" class="missing-code">
										<a href="<?php echo JRoute::_('index.php?option=com_config&view=component&component=com_rsseo&path=&return='.base64_encode(JURI::getInstance())); ?>">
											<?php echo JText::_('COM_RSSEO_PLEASE_ENTER_YOUR_CODE_IN_THE_CONFIGURATION'); ?>
										</a>
									</td>
									<?php } ?>
								</tr>
							</table>
						</div>
					</div>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=competitors'); ?>">
						<i class="fa fa-bar-chart"></i> <?php echo JText::_('COM_RSSEO_MENU_SEO_PERFORMANCE'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=pages'); ?>">
						<i class="fa fa-list-alt"></i> <?php echo JText::_('COM_RSSEO_MENU_PAGES'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=crawler'); ?>">
						<i class="fa fa-bug"></i> <?php echo JText::_('COM_RSSEO_MENU_CRAWLER'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=sitemap'); ?>">
						<i class="fa fa-sitemap"></i> <?php echo JText::_('COM_RSSEO_MENU_SITEMAP'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=robots'); ?>">
						<i class="fa fa-android"></i> <?php echo JText::_('COM_RSSEO_MENU_ROBOTS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=errors'); ?>">
						<i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('COM_RSSEO_MENU_ERRORS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=errorlinks'); ?>">
						<i class="fa fa-exclamation-circle"></i> <?php echo JText::_('COM_RSSEO_MENU_ERROR_LINKS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=redirects'); ?>">
						<i class="fa fa-repeat"></i> <?php echo JText::_('COM_RSSEO_MENU_REDIRECTS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=keywords'); ?>">
						<i class="fa fa-key"></i> <?php echo JText::_('COM_RSSEO_MENU_KEYWORDS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=gkeywords'); ?>">
						<i class="fa fa-key"></i> <?php echo JText::_('COM_RSSEO_MENU_GKEYWORDS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=backup'); ?>">
						<i class="fa fa-hdd-o"></i> <?php echo JText::_('COM_RSSEO_MENU_BACKUP_RESTORE'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=analytics'); ?>">
						<i class="fa fa-pie-chart"></i> <?php echo JText::_('COM_RSSEO_MENU_ANALYTICS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=data'); ?>">
						<i class="fa fa-google"></i> <?php echo JText::_('COM_RSSEO_MENU_STRUCTURED_DATA'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&view=statistics'); ?>">
						<i class="fa fa-eye"></i> <?php echo JText::_('COM_RSSEO_MENU_STATISTICS'); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_rsseo&task=connectivity'); ?>">
						<i class="fa fa-globe"></i> <?php echo JText::_('COM_RSSEO_CHECK_CONNECTIVITY'); ?>
					</a>
				</li>
			</ul>
			
		</div>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>