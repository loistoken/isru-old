<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<div class="container-fluid rsseo_import_container">
	<div class="row-fluid">
		<?php if (!empty($this->dates)) { ?>
			<?php foreach ($this->dates as $month => $dates) { ?>
			<?php if ($dates) { ?>
			<div class="rsseo_month_container">
				<h3><?php echo JText::_('COM_RSSEO_MONTH_'.$month); ?></h3>
				<ul class="unstyled inline rsseo_import_dates">
					<?php foreach ($dates as $date) { ?>
					<li>
						<button type="button" class="btn" onclick="RSSeo.importKeywordData(this, '<?php echo $this->escape($date); ?>');">
							<i style="display:none;" class="fa fa-spinner fa-pulse fa-fw"></i> <?php echo JText::sprintf('COM_RSSEO_GKEYWORDS_IMPORT_DATE',JFactory::getDate($date)->format(rsseoHelper::getConfig('g_date_format','d M Y'))); ?>
						</button>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<?php echo JText::_('COM_RSSEO_GKEYWORDS_IMPORT_NO_DATES'); ?>
		<?php } ?>
	</div>
</div>
<input type="hidden" id="rsseoPause" name="rsseoPause" value="0" />