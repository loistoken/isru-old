<?php
/**
* @package RSSeo!
* @copyright (C) 2016 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<a href="javascript:void(0)" id="rsseo-frontend-edit" class="rsseo-frontend-edit" onclick="rsseo_show_panel();">
	<?php echo JHtml::image('com_rsseo/logo.png', 'RSSeo!', array(), true); ?>
</a>

<form method="POST" action="javascript:void(0)" id="rsseo-frontend-edit-form" name="rsseo-frontend-edit-form">
	<div id="rsseo-frontend-window" class="rsseo-frontend-window">
		<a class="rsseo-frontend-window-close" href="javascript:void(0)" onclick="rsseo_hide_panel()">&times;</a>
		
		<div class="control-group">
			<div class="control-label">
				<label for="jform_title"><?php echo JText::_('RSSEO_EDIT_PAGE_TITLE'); ?></label>
			</div>
			<div class="controls">
				<input type="text" size="30" value="<?php echo $this->escape($this->page->title); ?>" id="jform_title" name="jform[title]">
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<label for="jform_keywords"><?php echo JText::_('RSSEO_EDIT_PAGE_KEYWORDS'); ?></label>
			</div>
			<div class="controls">
				<input type="text" size="30" value="<?php echo $this->escape($this->page->keywords); ?>" id="jform_keywords" name="jform[keywords]">
			</div>
		</div>
		
		<div class="control-group">
			<div class="control-label">
				<label for="jform_description"><?php echo JText::_('RSSEO_EDIT_PAGE_DESCRIPTION'); ?></label>
			</div>
			<div class="controls">
				<textarea id="jform_description" name="jform[description]"><?php echo $this->escape($this->page->description); ?></textarea>
			</div>
		</div>
		
		<h3><?php echo JText::_('RSSEO_EDIT_ROBOTS'); ?></h3>
		
		<div class="row-fluid">
			<div class="span6">
				<div class="control-group">
					<div class="control-label">
						<label for="jform_robots_index"><?php echo JText::_('RSSEO_EDIT_ROBOTS_INDEX'); ?></label>
					</div>
					<div class="controls">
						<select id="jform_robots_index" name="jform[robots][index]" class="input-small" size="1">
							<?php echo JHtml::_('select.options', $this->robotsOptions, 'value', 'text', $this->page->robots['index']); ?>
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label for="jform_robots_archive"><?php echo JText::_('RSSEO_EDIT_ROBOTS_ARCHIVE'); ?></label>
					</div>
					<div class="controls">
						<select id="jform_robots_archive" name="jform[robots][archive]" class="input-small" size="1">
							<?php echo JHtml::_('select.options', $this->robotsOptions, 'value', 'text', $this->page->robots['archive']); ?>
						</select>
					</div>
				</div>
			</div>
			
			<div class="span6">
				<div class="control-group">
					<div class="control-label">
						<label for="jform_robots_follow"><?php echo JText::_('RSSEO_EDIT_ROBOTS_FOLLOW'); ?></label>
					</div>
					<div class="controls">
						<select id="jform_robots_follow" name="jform[robots][follow]" class="input-small" size="1">
							<?php echo JHtml::_('select.options', $this->robotsOptions, 'value', 'text', $this->page->robots['follow']); ?>
						</select>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label">
						<label for="jform_robots_snippet"><?php echo JText::_('RSSEO_EDIT_ROBOTS_SNIPPET'); ?></label>
					</div>
					<div class="controls">
						<select id="jform_robots_snippet" name="jform[robots][snippet]" class="input-small" size="1">
							<?php echo JHtml::_('select.options', $this->robotsOptions, 'value', 'text', $this->page->robots['snippet']); ?>
						</select>
					</div>
				</div>
			</div>
		</div>
		
		<div id="rsseo-frontend-edit-message" class="alert alert-success" style="display: none;"></div>
		
		<div class="control-group">
			<div class="controls">
				<button type="button" class="btn btn-primary" onclick="rsseo_save_page('<?php echo addslashes(JUri::root()); ?>');">
					<?php echo JHtml::image('com_rsseo/loader.gif', '', array('id' => 'rsseo-frontend-edit-loader', 'style' => 'display:none;'), true); ?> <?php echo JText::_('RSSEO_EDIT_SAVE'); ?>
				</button> 
				<button type="button" class="btn" onclick="rsseo_hide_panel();"><?php echo JText::_('RSSEO_EDIT_CLOSE'); ?></button>
			</div>
		</div>
		
	</div>
	
	<input type="hidden" name="jform[url]" value="<?php echo $this->page->url; ?>" />
</form>