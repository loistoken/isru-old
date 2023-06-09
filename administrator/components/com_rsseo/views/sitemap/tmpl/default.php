<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=sitemap');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10 j-main-container">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_RSSEO_XML_SITEMAP'); ?></legend>
			<?php if (!$this->sitemap || !$this->ror) { ?>
			<table class="adminform">
				<?php if (!$this->sitemap) { ?>
				<tr id="sitemap">
					<td>
						<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'sitemaploading', 'style' => 'display:none;'), true); ?>
						<span id="sitemapspan">
							<?php echo JText::sprintf('COM_RSSEO_CREATE_SITEMAP_ROR_XML','<span class="badge badge-info">sitemap.xml</span>','<a href="javascript:void(0)" class="btn" onclick="RSSeo.createFile(\'sitemap\');">'.JText::_('COM_RSSEO_GLOBAL_HERE').'</a>'); ?>
						</span>
					</td>
				</tr>
				<?php } ?>
				<?php if (!$this->ror) { ?>
				<tr id="ror">
					<td>
						<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'rorloading', 'style' => 'display:none;'), true); ?>
						<span id="rorspan">
							<?php echo JText::sprintf('COM_RSSEO_CREATE_SITEMAP_ROR_XML','<span class="badge badge-info">ror.xml</span>','<a href="javascript:void(0)" class="btn" onclick="RSSeo.createFile(\'ror\');">'.JText::_('COM_RSSEO_GLOBAL_HERE').'</a>'); ?>
						</span>
					</td>
				</tr>
				<?php } ?>
			</table>
			<br />
			<?php } ?>
			
			<?php $disabled = $this->sitemap || $this->ror ? '' : ' disabled="disabled"'; ?>
			<table class="adminform table table-striped">
				<tr>
					<td><?php echo $this->form->getLabel('protocol'); ?></td>
					<td><?php echo $this->form->getInput('protocol'); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->form->getLabel('modified'); ?></td>
					<td><?php echo $this->form->getInput('modified'); ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<a id="btnsitemap" <?php echo $this->sitemap ? '' : 'style="display:none;"'; ?> class="btn btn-info sitemapbutton" href="<?php echo JURI::root(); ?>sitemap.xml" target="_blank">sitemap.xml</a>
						<a id="btnror" <?php echo $this->ror ? '' : 'style="display:none;"'; ?> class="btn btn-info sitemapbutton" href="<?php echo JURI::root(); ?>ror.xml" target="_blank">ror.xml</a>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><button id="sitemapbtn" class="btn btn-primary button" type="button" onclick="RSSeo.createSitemap(1);" <?php echo $disabled; ?>><?php echo JText::_('COM_RSSEO_GENERATE_SITEMAP'); ?></button></td>
				</tr>
			</table>
			
			<div class="com-rsseo-progress" id="com-rsseo-import-progress">
				<div class="com-rsseo-bar hasTooltip" title="<?php echo JText::sprintf('COM_RSSEO_SITEMAP_INFO',$this->percent); ?>" id="com-rsseo-bar" style="width: <?php echo $this->percent; ?>%;"><?php echo $this->percent; ?>%</div>
			</div>
			
		</fieldset>
		<br />
		
		<fieldset>
			<legend><?php echo JText::_('COM_RSSEO_HTML_SITEMAP'); ?></legend>
			<table class="adminform table table-striped">
				<tr>
					<td>
						<label for="menus" class="rsnofloat"><?php echo JText::_('COM_RSSEO_SITEMAP_HTML_MENUS'); ?></label>
						<?php $menusSelected = unserialize(base64_decode(rsseoHelper::getConfig('sitemap_menus'))); ?>
						<select name="menus[]" id="menus" multiple="multiple" size="5">
							<?php echo JHtml::_('select.options', JHtml::_('rsseomenu.menus'), 'value', 'text',$menusSelected); ?>
						</select>
					</td>
					<td>
						<label class="rsnofloat"><?php echo JText::_('COM_RSSEO_SITEMAP_HTML_EXCLUDE_MENU_ITEMS'); ?></label>
						<?php $excludeSelected = unserialize(base64_decode(rsseoHelper::getConfig('sitemap_excludes'))); ?>
						<?php echo JHTML::_('rsseomenu.menuitemlist','exclude[]',$excludeSelected,'multiple="multiple" size="5"'); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button class="btn btn-primary button" type="submit" onclick="Joomla.submitbutton('sitemap.html');"><?php echo JText::_('COM_RSSEO_GENERATE_SITEMAP'); ?></button>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
</div>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
</form>