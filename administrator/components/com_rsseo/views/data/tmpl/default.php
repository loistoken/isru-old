<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
jQuery(document).ready(function () {
	if (typeof(Storage) !== 'undefined') {
		if (sessionStorage.rsseoSelectedTab) {
			jQuery('#structuredDataTabs > li a[href="#' + sessionStorage.rsseoSelectedTab + '"]').click();
		} else {
			jQuery('#structuredDataTabs > li a:first').click();
		}
		
		jQuery('#structuredDataTabs > li > a').click(function() {
			sessionStorage.rsseoSelectedTab = jQuery(this).attr('href').replace('#','');
		});
	}
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=data');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<div class="row-fluid">
		<div id="j-sidebar-container" class="span2">
			<?php echo JHtmlSidebar::render(); ?>
		</div>
		<div id="j-main-container" class="span10 j-main-container">
			<?php echo JHtml::_('bootstrap.startTabSet', 'structuredData', array('active' => 'site')); ?>
			<?php foreach ($this->form->getFieldsets() as $fieldset) { ?>
			<?php echo JHtml::_('bootstrap.addTab', 'structuredData', $fieldset->name, JText::_('COM_RSSEO_STRUCTURED_FIELDSET_'.strtoupper($fieldset->name))); ?>
			<div class="row-fluid">
				<?php foreach ($this->form->getFieldset($fieldset->name) as $field) { ?>
				<?php echo $field->renderField(); ?>
				<?php } ?>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php } ?>
			<?php JFactory::getApplication()->triggerEvent('rsseo_structuredTabs'); ?>
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>	
		</div>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
</form>