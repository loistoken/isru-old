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
		if (task == 'error.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=error&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<?php echo JHtml::_('rsfieldset.start', 'adminform'); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('published'), $this->form->getInput('published')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('name'), $this->form->getInput('name')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('error'), $this->form->getInput('error')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('type'), $this->form->getInput('type')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('url'), $this->form->getInput('url'), array('id' => 'errorUrl')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('itemid'), $this->form->getInput('itemid'), array('id' => 'errorItemid')); ?>
		<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('layout'), '<div class="rsseo_editor">'.$this->form->getInput('layout').'</div>', array('id' => 'errorMessage')); ?>
		<?php echo JHtml::_('rsfieldset.end'); ?>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>
<script type="text/javascript">RSSeo.errorType(<?php echo isset($this->item->type) ? $this->item->type : 1; ?>);</script>