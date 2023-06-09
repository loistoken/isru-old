<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
JText::script('COM_RSSEO_ANALYTICS_SELECT_ACCOUNT'); ?>

<?php if ($this->valid) { ?>
<script type="text/javascript">
jQuery(document).ready(function () {
	if (jQuery('#profile').val() != '') {
		RSSeo.updateAnalytics();
	}
});
</script>
<?php } ?>

<script type="text/javascript">google.load('visualization', '1', {packages: ['corechart','corechart']});</script>
<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=analytics');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10 j-main-container">
		
		<?php if ($this->valid) { ?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="btn-group pull-right">
				<button class="btn btn-danger button" type="button" onclick="Joomla.submitbutton('analytics.logout');"><?php echo JText::_('COM_RSSEO_GLOBAL_LOGOUT'); ?></button>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<button class="btn btn-info button" type="button" onclick="RSSeo.updateAnalytics();"><?php echo JText::_('COM_RSSEO_GLOBAL_UPDATE'); ?></button>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<?php echo JHTML::_('calendar', $this->rsend, 'rsend', 'rsend', '%Y-%m-%d' , array('class' => 'input-small')); ?>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<?php echo JHTML::_('calendar', $this->rsstart, 'rsstart', 'rsstart', '%Y-%m-%d' , array('class' => 'input-small')); ?>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<select id="profile" class="input-large" size="1" name="profile">
					<?php echo JHtml::_('select.options', $this->profiles, 'value', 'text', $this->selected); ?>
				</select>
			</div>
			
			<div class="clearfix"> </div>
		</div>
		<div class="clr"></div>
		
		<div id="rsseo-analytics">
			<?php $this->tabs->title('COM_RSSEO_GA_VISITORS_LBL','ga-visitors'); ?>
			<?php $this->tabs->title('COM_RSSEO_GA_TRAFFIC_LBL','ga-traffic'); ?>
			<?php $this->tabs->title('COM_RSSEO_GA_CONTENT_LBL','ga-content'); ?>
			<?php $this->tabs->content($this->loadTemplate('gavisitors')); ?>
			<?php $this->tabs->content($this->loadTemplate('gatraffic')); ?>
			<?php $this->tabs->content($this->loadTemplate('gacontent')); ?>
			<?php echo $this->tabs->render(); ?>
		</div>
		<?php } else { ?>
		<div class="ga_no_account">
			<h3>
				<i class="fa fa-info-circle"></i> 
				<?php echo JText::_('COM_RSSEO_PLEASE_AUTH_APPLICATION'); ?> <a href="<?php echo $this->auth; ?>" class="btn btn-success"><?php echo JText::_('COM_RSSEO_AUTHENTIFICATE'); ?></a>
			</h3>
		</div>
		<?php } ?>
	</div>
</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
</form>