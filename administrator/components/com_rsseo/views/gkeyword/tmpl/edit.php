<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive'); 
JText::script('COM_RSSEO_GKEYWORD_RUN_ALL');
JText::script('COM_RSSEO_GKEYWORD_RUN_ALL_PAUSE');
JText::script('COM_RSSEO_GKEYWORD_DATE');
JText::script('COM_RSSEO_GKEYWORD_POSITION');
$pages = $impressions = $clicks = $avg = 0; ?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'gkeyword.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	
	<?php if ($this->item->id && $this->data) { ?>
	RSSeo.jsonPositionChartData = <?php echo $this->json; ?>;
	google.charts.load('current', {packages: ['corechart', 'line']});
	google.charts.setOnLoadCallback(function() {
		RSSeo.drawGoogleKeywordChart();
	});
	
	jQuery(document).ready(function() {
		jQuery(window).resize(function() {
			RSSeo.drawGoogleKeywordChart();
		});
	});
	<?php } ?>
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=gkeyword&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<?php echo JHtml::_('rsfieldset.start', 'adminform'); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('name'), $this->form->getInput('name')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('site'), $this->form->getInput('site')); ?>
		<?php echo JHtml::_('rsfieldset.end'); ?>
	</div>
	
	<?php if (!$this->total) { ?>	
		<?php if ($this->item->id) { ?>
		<br />
		<div class="alert alert-info">
			<i class="fa fa-info fa-fw fa-2x" style="vertical-align: sub;"></i> 
			<span id=""><?php echo JText::_('COM_RSSEO_GKEYWORD_NO_DATA'); ?></span>
		</div>
		<?php } ?>
	<?php } else { ?>
	
		<div class="row-fluid">
			<div class="span12">
				<div class="pull-right rsseo-chart-filter">
					<?php echo JHTML::_('calendar', $this->to, 'filter_to', 'filter_to', '%Y-%m-%d' , array('class' => 'input-small', 'onChange' => 'document.adminForm.submit();', 'placeholder' => JText::_('COM_RSSEO_TO'))); ?>
				</div>
				
				<div class="pull-right rsseo-chart-filter">
					<?php echo JHTML::_('calendar', $this->from, 'filter_from', 'filter_from', '%Y-%m-%d' , array('class' => 'input-small', 'onChange' => 'document.adminForm.submit();', 'placeholder' => JText::_('COM_RSSEO_FROM'))); ?>
				</div>
				
				<div class="pull-right rsseo-chart-filter">
					<select name="filter_device" id="filter_device" onchange="document.adminForm.submit();">
						<?php echo JHtml::_('select.options', $this->devices, 'value', 'text', $this->device); ?>
					</select>
				</div>
				
				<div class="pull-right rsseo-chart-filter">
					<select name="filter_country" id="filter_country" onchange="document.adminForm.submit();">
						<?php echo JHtml::_('select.options', $this->countries, 'value', 'text', $this->country); ?>
					</select>
				</div>
			</div>
		</div>
		
		<div class="clearfix"></div>
	
		<?php if ($this->data) { ?>
		
		<div class="row-fluid">
			<div class="rsseo_chart_container">
				<div id="chart"></div>
			</div>
		</div>
	
		<div class="row-fluid">
			<table class="table table-striped table-hover rsseo_import_table">
				<thead>
					<tr>
						<th><?php echo JText::_('COM_RSSEO_GKEYWORD_DATE'); ?></th>
						<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_PAGES'); ?></th>
						<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_IMPRESSIONS'); ?></th>
						<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_CLICKS'); ?></th>
						<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_AVG_POSITION'); ?></th>
						<th align="center" class="center"><?php echo JText::_('COM_RSSEO_GKEYWORD_CTR'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->data as $data) { ?>
					<?php $pages += (int) $data->pages;?>
					<?php $impressions += (int) $data->impressions;?>
					<?php $clicks += (int) $data->clicks;?>
					<?php $avg += $data->avgposition;?>
					<tr>
						<td><?php echo JFactory::getDate($data->date)->format(rsseoHelper::getConfig('g_date_format','d M Y')); ?></td>
						<td align="center" class="center">
							<?php if ($data->pages) { ?><a href="javascript:void(0)" onclick="RSSeo.showPages('<?php echo $this->item->id; ?>','<?php echo $data->date; ?>')"><?php } ?>
							<?php echo $data->pages; ?>
							<?php if ($data->pages) { ?></a><?php } ?>
						</td>
						<td align="center" class="center"><?php echo $data->impressions; ?></td>
						<td align="center" class="center"><?php echo $data->clicks; ?></td>
						<td align="center" class="center"><?php echo number_format($data->avgposition, 2); ?></td>
						<td align="center" class="center"><?php echo number_format(($data->clicks / $data->impressions) * 100, 2); ?>%</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td><?php echo JText::_('COM_RSSEO_GKEYWORD_TOTALS'); ?></td>
						<td align="center" class="center"><?php echo $pages; ?></td>
						<td align="center" class="center"><?php echo $impressions; ?></td>
						<td align="center" class="center"><?php echo $clicks; ?></td>
						<td align="center" class="center"><?php echo number_format($avg / count($this->data), 2); ?></td>
						<td align="center" class="center"><?php echo number_format(($clicks / $impressions) * 100, 2); ?>%</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php } else { ?>
		<br />
		<div class="alert alert-info">
			<i class="fa fa-info fa-fw fa-2x" style="vertical-align: sub;"></i> 
			<span id=""><?php echo JText::_('COM_RSSEO_GKEYWORD_NO_DATA_WITH_FILTER'); ?></span>
		</div>
		<?php } ?>
	<?php } ?>
	
	<?php echo JHtml::_('bootstrap.renderModal', 'process-data', array('title' => JText::_('COM_RSSEO_GKEYWORD_IMPORT'), 'footer' => $this->loadTemplate('footer'), 'bodyHeight' => 70), $this->loadTemplate('modal')); ?>
	<?php echo JHtml::_('bootstrap.renderModal', 'rsseo-pages', array('title' => JText::_('COM_RSSEO_GKEYWORD_PAGES_REPORT'), 'bodyHeight' => 70)); ?>
	
	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>