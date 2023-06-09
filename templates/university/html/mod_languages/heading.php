<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('stylesheet', 'mod_languages/template.css', array('version' => 'auto', 'relative' => true));

if ($params->get('dropdown', 1) && !$params->get('dropdownimage', 0))
{
	JHtml::_('formbehavior.chosen');
}

$lang = JFactory::getLanguage();
$languages = JLanguageHelper::getLanguages('lang_code');
$languageCode = $languages[ $lang->getTag() ]->sef;
?>
<?php if ($headerText) : ?>
	<div class="pretext"><p><?php echo $headerText; ?></p></div>
<?php endif; ?>

<?php if ($params->get('dropdown', 1) && !$params->get('dropdownimage', 0)) : ?>
	<form name="lang" method="post" action="<?php echo htmlspecialchars(JUri::current(), ENT_COMPAT, 'UTF-8'); ?>">
	<select class="inputbox advancedSelect" onchange="document.location.replace(this.value);" >
	<?php foreach ($list as $language) : ?>
		<option dir=<?php echo $language->rtl ? '"rtl"' : '"ltr"'; ?> value="<?php echo $language->link; ?>" <?php echo $language->active ? 'selected="selected"' : ''; ?>>
		<?php echo $language->title_native; ?></option>
	<?php endforeach; ?>
	</select>
	</form>
<?php elseif ($params->get('dropdown', 1) && $params->get('dropdownimage', 0)) : ?>
	<div class="btn-group">
		<?php foreach ($list as $language) : ?>
			<?php if ($language->active) : ?>
				<a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
					<span class="caret"></span>
					<?php if ($language->image) : ?>
						&nbsp;<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.png', '', null, true); ?>
					<?php endif; ?>
					<?php echo $language->title_native; ?>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
		<ul class="uk-padding-remove uk-margin-remove <?php echo $params->get('lineheight', 1) ? 'lang-block' : 'lang-inline'; ?> dropdown-menu" dir="<?php echo JFactory::getLanguage()->isRtl() ? 'rtl' : 'ltr'; ?>">
		<?php foreach ($list as $language) : ?>
			<?php if (!$language->active || $params->get('show_active', 0)) : ?>
				<li<?php echo $language->active ? ' class="lang-active"' : ''; ?>>
				<a href="<?php echo $language->link; ?>">
					<?php if ($language->image) : ?>
						<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.gif', '', null, true); ?>
					<?php endif; ?>
					<?php echo $language->title_native; ?>
				</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
<?php else : ?>
	<?php foreach ($list as $language) : ?>
		<?php if (!$language->active || $params->get('show_active', 0)) : ?>
			<a href="<?php echo $language->link; ?>" class="uk-display-block" title="<?php echo JTEXT::_('SWITCHTO').' '.JTEXT::_($language->title_native); ?>" uk-tooltip="offset: <?php echo $languageCode == 'en' ? 0 : 5; ?>; cls: uk-active uk-text-tiny; pos: <?php echo $languageCode == 'en' ? 'left' : 'right'; ?>;">
			<?php if ($params->get('image', 1)) : ?>
				<?php if ($language->image) : ?>
					<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.png', $language->title_native, array('class' => 'uk-display-block'), true); ?>
				<?php else : ?>
					<span class="label"><?php echo strtoupper($language->sef); ?></span>
				<?php endif; ?>
			<?php else : ?>
				<?php echo $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef); ?>
			<?php endif; ?>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if ($footerText) : ?>
	<div class="posttext"><p><?php echo $footerText; ?></p></div>
<?php endif; ?>