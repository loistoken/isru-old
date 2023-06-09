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

$class  = ' class="first"';
$lang   = JFactory::getLanguage();
$user   = JFactory::getUser();
$groups = $user->getAuthorisedViewLevels();
?>

<?php if (count($this->children[$this->category->id]) > 0) : ?>

	<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
		<?php // Check whether category access level allows access to subcategories. ?>
		<?php if (in_array($child->access, $groups)) : ?>
			<?php
			if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren())) :
				if (!isset($this->children[$this->category->id][$id + 1])) :
					$class = ' class="last"';
				endif;
			?>

			<div<?php echo $class; ?>>
				<?php $class = ''; ?>
				<?php if ($lang->isRtl()) : ?>
				<h3 class="page-header item-title ggggg">
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_CONTENT_NUM_ITEMS_TIP'); ?>">
							<?php echo $child->getNumItems(true); ?>
						</span>
					<?php endif; ?>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>">
					<?php echo $this->escape($child->title); ?></a>

					<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
						<a href="#category-<?php echo $child->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right" aria-label="<?php echo JText::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
					<?php endif; ?>
				</h3>
				<?php else : ?>
				<h3 class="page-header item-title font oooooooo">
                    <?php /* ?>
                    <a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>"><?php echo $this->escape($child->title); ?></a>
                    <?php */ ?>
                    <?php echo $this->escape($child->title); ?>
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_CONTENT_NUM_ITEMS_TIP'); ?>">
							<?php echo $child->getNumItems(true); ?>
						</span>
					<?php endif; ?>

					<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
						kkkk<a href="#category-<?php echo $child->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right" aria-label="<?php echo JText::_('JGLOBAL_EXPAND_CATEGORIES'); ?>"><span class="icon-plus" aria-hidden="true"></span></a>
					<?php endif; ?>

				</h3>


                    <?php
                    $db = JFactory::getDbo();
                    $id = $child->id;
                    $query = $db->getQuery(true);
                    $query->select('*');
                    $query->from('#__content');
                    $query->where('catid="'.$id.'"');
                    $db->setQuery((string)$query);
                    $res = $db->loadObjectList();
                    echo '<ul>';
                    foreach($res as $r){
                       echo '<li><a class="font" href="'.JURI::current().'/'.$child->alias.'/'.$r->alias.'" class="" title="">'.$r->title.'</a></li>';
                    }
                    echo '</ul>';
                    ?>



				<?php endif; ?>

				<?php if ($this->params->get('show_subcat_desc') == 1) : ?>
					<?php if ($child->description) : ?>
						<div class="category-desc">
							<?php echo JHtml::_('content.prepare', $child->description, '', 'com_content.category'); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if (count($child->getChildren()) > 0 && $this->maxLevel > 1) : ?>
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

<?php endif; ?>