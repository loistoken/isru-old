<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$class  = ' class="first uk-text-center"';
$lang   = JFactory::getLanguage();
$user   = JFactory::getUser();
$groups = $user->getAuthorisedViewLevels();

if ($this->maxLevel != 0 && count($this->children[$this->category->id]) > 0) : ?>

	<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
		<?php // Check whether category access level allows access to subcategories. ?>
		<?php if (in_array($child->access, $groups)) : ?>
			<?php if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
				if (!isset($this->children[$this->category->id][$id + 1])) :
					$class = ' class="last uk-text-center"';
				endif;
			?>
			<div<?php echo $class; ?>>
				<?php $class = ''; ?>
				<?php if ($lang->isRtl()) : ?>
				<h3 class="page-header item-title yyyyy font">
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_CONTENT_NUM_ITEMS_TIP'); ?>">
							<?php echo $child->getNumItems(true); ?>
						</span>
					<?php endif; ?>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>">
                        <?php echo $this->escape($child->title); ?>
                    </a>

					<?php if ($this->maxLevel > 1 && count($child->getChildren()) > 0) : ?>
						<a href="#category-<?php echo $child->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right" aria-label="<?php echo JText::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
					<?php endif; ?>
				</h3>
				<?php else : ?>
				<h3 class="page-header item-title font">
                    <a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>">
                        <i class="<?php echo $child->alias == 'video' ? 'fab fa-youtube' : 'fas fa-images'; ?> fa-4x"></i>
                        <span class="uk-display-inline-block uk-width-1-1 uk-margin-top"><?php echo $this->escape($child->title); ?></span>
                    </a>
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_CONTENT_NUM_ITEMS_TIP'); ?>">
							<?php echo JText::_('COM_CONTENT_NUM_ITEMS'); ?>&nbsp;
							<?php echo $child->getNumItems(true); ?>
						</span>
					<?php endif; ?>

					<?php if ($this->maxLevel > 1 && count($child->getChildren()) > 0) : ?>
						<a href="#category-<?php echo $child->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right" aria-label="<?php echo JText::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
					<?php endif; ?>
				</h3>
				<?php endif; ?>

				<?php if ($this->params->get('show_subcat_desc') == 1) : ?>
					<?php if ($child->description) : ?>
						<div class="category-desc">
							<?php echo JHtml::_('content.prepare', $child->description, '', 'com_content.category'); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ($this->maxLevel > 1 && count($child->getChildren()) > 0) : ?>
					<div class="collapse fade" id="category-<?php echo $child->id; ?>">
						<?php
						$this->children[$child->id] = $child->getChildren();
						$this->category = $child;
						$this->maxLevel--;
						echo $this->loadTemplate('children');
						$this->category = $child->getParent();
						$this->maxLevel++;
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

<?php endif;
