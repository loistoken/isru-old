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
	Joomla.submitbutton = function(task)  {
		if (task == 'refresh') {
			jQuery('input[name="cid[]"]:checked').each(function() {
				<?php if ($this->config->crawler_type == 'ajax') { ?>
				jQuery('#refresh' + jQuery(this).val()).click();
				<?php } else { ?>
				RSSeo.checkPage(jQuery(this).val(),0);
				<?php } ?>
			});
		} else if (task == 'restore') {
			jQuery('input[name="cid[]"]:checked').each(function() {
				<?php if ($this->config->crawler_type == 'ajax') { ?>
				jQuery('#restore' + jQuery(this).val()).click();
				<?php } else { ?>
				RSSeo.checkPage(jQuery(this).val(),1);
				<?php } ?>
			});
		} else Joomla.submitform(task);
		
		return false;
	}
</script>

<form action="<?php echo JURI::getInstance(); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div id="j-sidebar-container" class="span2">
			<?php echo JHtmlSidebar::render(); ?>
		</div>
		<div id="j-main-container" class="span10 j-main-container">
			
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			
			<?php echo $this->loadTemplate($this->simple ? 'simple' : 'standard'); ?>
		</div>
	</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hash" value="<?php echo $this->escape(JFactory::getApplication()->input->getString('hash')); ?>" />
</form>