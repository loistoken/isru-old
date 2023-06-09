<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<div class="container-fluid">
	<div class="row-fluid form-horizontal">
		<div class="span6">
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSSEO_GENERAL')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->batch->getLabel('keywords'), $this->batch->getInput('keywords')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->batch->getLabel('description'), $this->batch->getInput('description')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->batch->getLabel('frequency'), $this->batch->getInput('frequency')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->batch->getLabel('priority'), $this->batch->getInput('priority')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
		<div class="span6">
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSSEO_PAGE_ROBOTS')); ?>
			<?php foreach($this->batch->getGroup('robots') as $field) { ?>
			<?php echo JHtml::_('rsfieldset.element', $field->label, $field->input); ?>
			<?php } ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
	</div>
</div>