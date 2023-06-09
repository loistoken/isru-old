<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive'); ?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'redirect.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=redirect&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<?php $info = '<i class="fa fa-info-circle hasTooltip" title="'.JText::_('COM_RSSEO_REDIRECT_INFO').'"></i>'; ?>
		<?php echo JHtml::_('rsfieldset.start', 'adminform'); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('from'), '<span id="rsroot">'.JURI::root().'</span> '.$this->form->getInput('from').' '.$info.'<div class="clr"></div><div id="rss_results"><ul id="rsResultsUl"></ul></div>'); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('to'), $this->form->getInput('to')); ?>
		<?php if ($this->item->hits) echo JHtml::_('rsfieldset.element', '<label>'.JText::_('COM_RSSEO_HITS').'</label>', '<span class="pull-left badge badge-success">'.$this->item->hits.'</span>'); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('type'), $this->form->getInput('type')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('published'), $this->form->getInput('published')); ?>
		<?php echo JHtml::_('rsfieldset.end'); ?>
	</div>
	
	<?php if (!empty($this->referrers)) { ?>
	<table class="table table-striped adminlist">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_RSSEO_REFERER'); ?></th>
				<th class="center" align="center" width="15%"><?php echo JText::_('COM_RSSEO_REFERER_DATE'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->referrers as $referer) { ?>
			<tr>
				<td><b><?php echo $referer->referer ? $referer->referer : JText::_('COM_RSSEO_DIRECT_LINK'); ?></b> <?php if ($referer->url) echo '<small>('.JText::_('COM_RSSEO_LINK').': '.$referer->url.')</small>'; ?></td>
				<td class="center" align="center"><?php echo JHtml::_('date',$referer->date, rsseoHelper::getConfig('global_dateformat')); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } ?>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
</form>

<script type="text/javascript">
jQuery('#jform_from').on('keyup', function() {
	RSSeo.generateRSResults(1);
});
</script>