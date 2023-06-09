<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">RSSeo.seconds = <?php echo (int) $this->config->request_timeout; ?>;</script>
<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=crawler');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10 j-main-container">
		<?php if ($this->config->crawler_enable_auto) { ?>
			<div class="rssmessage" id="rssmessage" style="display:none;"><i class="fa fa-info-circle"></i> <?php echo JText::_('COM_RSSEO_CRAWLER_MESSAGE'); ?></div>
		<?php } ?>
		<table class="table table-striped adminform">
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_URL'); ?></td>
				<td><span id="url"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_LEVEL'); ?></td>
				<td><span id="level"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_PAGES_SCANED'); ?></td>
				<td><span id="scaned"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_PAGES_LEFT'); ?></td>
				<td><span id="remaining"></span></td>
			</tr>
			<tr>
				<td width="300"><?php echo JText::_('COM_RSSEO_CRAWLER_PAGES_TOTAL'); ?></td>
				<td><span id="total"></span></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php if (!$this->offline) { ?>
					<?php if ($this->config->crawler_type == 'loopback') { ?>
					<button type="button" class="btn btn-primary button_start" onclick="RSSeo.doCrawl(1,0);"><?php echo JText::_('COM_RSSEO_CRAWLER_START'); ?></button>
					<button type="button" style="display:none;" class="btn btn-primary button_pause" onclick="RSSeo.pauseCrawl();"><?php echo JText::_('COM_RSSEO_CRAWLER_PAUSE'); ?></button>
					<button type="button" style="display:none;" class="btn btn-primary button_continue" onclick="RSSeo.continueCrawl();"><?php echo JText::_('COM_RSSEO_CRAWLER_CONTINUE'); ?></button>
					<?php } else { ?>
					<button type="button" class="btn btn-primary button_start" onclick="RSSeo.initCrawler('<?php echo JUri::root(); ?>',1);"><?php echo JText::_('COM_RSSEO_CRAWLER_START'); ?></button>
					<button type="button" style="display:none;" class="btn btn-primary button_pause" onclick="RSSeo.pause();"><?php echo JText::_('COM_RSSEO_CRAWLER_PAUSE'); ?></button>
					<button type="button" style="display:none;" class="btn btn-primary button_continue" onclick="RSSeo.continue();"><?php echo JText::_('COM_RSSEO_CRAWLER_CONTINUE'); ?></button>
					<?php } ?>
					<?php } ?>
					
					<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'rsseoCrawling', 'style' => 'display:none;'), true); ?>
				</td>
			</tr>
		</table>
	</div>
</div>


	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="pause" id="pause" value="0" />
	<input type="hidden" name="auto" id="auto" value="<?php echo $this->config->crawler_enable_auto; ?>" />
	<input type="hidden" name="pageid" id="pageid" value="" />
	<input type="hidden" name="pageurl" id="pageurl" value="" />
	<input type="hidden" name="task" value="" />
</form>