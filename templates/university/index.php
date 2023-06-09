<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');
$hostiran = '<a href="http://design.hostiran.net" target="_blank">'.JTEXT::_("HDD").'</a>';

// Add Stylesheets
if ($this->direction === 'rtl') {
    JHtml::_('stylesheet', 'uikit-rtl.min.css', array('version' => 'auto', 'relative' => true));
} else {
    JHtml::_('stylesheet', 'uikit.min.css', array('version' => 'auto', 'relative' => true));
}
JHtml::_('stylesheet', 'university.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'fa-svg-width-js.css', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'custom.css', array('version' => 'auto', 'relative' => true));

// Add js
JHtml::_('script', 'uikit.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'fontawesome-all.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'uikit-icons.min.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

$socialsicons = json_decode( $params->get('socials'),true);
$total = count($socialsicons['icon']);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : ' ltr'); ?>">

<?php
$menu = $app->getMenu();
$active = $menu->getItem($itemid);
?>
<header id="top" class="uk-card uk-card-body uk-card-default uk-padding-remove uk-position-z-index">
    <div class="headerWrapper">
        <div class="uk-container">
            <nav uk-navbar="offset: 1;">
                <div class="uk-width-1-1">
                    <div class="uk-grid-medium uk-flex-center" uk-grid>
                        <div class="uk-width-expand uk-hidden@m">
                            <a class="uk-navbar-toggle uk-padding-remove uk-height-1-1" href="#offcanvas-usage" uk-toggle uk-icon="icon: menu; ratio: 1.6"></a>
                        </div>
                        <div class="nav-overlay uk-width-auto">
                            <div>
                                <a href="<?php echo JURI::root(); ?>" class="uk-padding-small uk-padding-remove-horizontal uk-display-inline-block" title="<?php echo $sitename; ?>"><img src="<?php echo $params->get('logo'); ?>" width="80" height="80" class="uk-display-inline-block" alt="<?php echo $sitename; ?>"></a>
                            </div>
                        </div>
                        <jdoc:include type="modules" name="heading" style="xhtml" />
                        <?php if ($this->countModules('lang')) : ?>
                        <div class="nav-overlay uk-width-auto uk-flex uk-flex-center uk-flex-middle uk-visible@m">
                            <jdoc:include type="modules" name="lang" style="xhtml" />
                        </div>
                        <?php endif; ?>
                        <?php if ($this->countModules('search')) : ?>
                        <div class="nav-overlay uk-width-expand searchWrapper" hidden>
                            <jdoc:include type="modules" name="search" style="xhtml" />
                        </div>
                        <div class="nav-overlay uk-width-auto uk-visible@m">
                            <a class="uk-navbar-toggle uk-padding-remove uk-height-1-1" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#" uk-icon="icon: search; ratio: 1"></a>
                        </div>
                        <div class="nav-overlay uk-width-auto uk-visible@m" hidden>
                            <a class="uk-navbar-toggle uk-padding-remove uk-height-1-1" uk-toggle="target: .nav-overlay; animation: uk-animation-fade" href="#" uk-icon="icon: close; ratio: 1.6"></a>
                        </div>
                        <div class="mobSearch uk-width-expand uk-hidden@m">
                            <a class="uk-navbar-toggle uk-padding-remove uk-height-1-1" href="#" uk-toggle="target: .mobSearch; animation: uk-animation-fade" uk-icon="icon: search; ratio: 1.6"></a>
                        </div>
                        <div class="mobSearch uk-width-expand uk-hidden@m" hidden>
                            <a class="uk-navbar-toggle uk-padding-remove uk-height-1-1" href="#" uk-toggle="target: .mobSearch; animation: uk-animation-fade" uk-icon="icon: close; ratio: 1.6"></a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<div class="uk-visible@m" uk-sticky="bottom: #slideshowWrapper; media: @m; cls-active: uk-active uk-card uk-card-body uk-card-default uk-padding-remove;">
    <nav class="mainNav">
        <div class="mainNavWrapper">
            <div class="uk-container"><jdoc:include type="modules" name="nav" style="xhtml" /></div>
        </div>
    </nav>
</div>
<?php if ($menu->getParams( $active->id )->get('pageclass_sfx') != 'home') { ?>
<section class="pageHeading">
    <div class="pageHeadingWrapper">
        <div class="uk-container">
            <div class="uk-grid-small" uk-grid>
                <div class="title uk-width-1-1 uk-width-auto@m"><h1 class="uk-text-center uk-text-left@m"><?php echo JFactory::getDocument()->getTitle(); ?></h1></div>
                <div class="breadcrumb uk-width-1-1 uk-width-expand@m uk-flex uk-flex-middle uk-flex-center uk-flex-right@m"><jdoc:include type="modules" name="breadcrumbs" style="xhtml" /></div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<div id="slideshowWrapper">
    <jdoc:include type="modules" name="slideshow" style="xhtml" />
</div>
<main  class="<?php echo $menu->getParams( $active->id )->get('pageclass_sfx').' '.$option . ' view-' . $view . ($layout ? ' layout-' . $layout : ' no-layout') . ($task ? ' task-' . $task : ' no-task') . ($itemid ? ' itemid-' . $itemid : '') . ($params->get('fluidContainer') ? ' fluid' : '') . ($this->direction === 'rtl' ? ' rtl' : ''); ?>">
    <div class="mainWrapper">
        <?php if ($menu->getParams( $active->id )->get('pageclass_sfx') == 'home') { ?>
            <section class="homeTabsBar uk-text-0" uk-sticky>
                <div class="htbWrapper uk-text-0">
                    <div class="uk-container">
                        <div uk-grid>
                            <div class="uk-width-1-1 uk-width-2-3@m">
                                <ul class="homeTabsHeading uk-padding-remove" uk-switcher="animation: uk-animation-fade; connect: .homeTabs">
                                    <li class="uk-display-inline-block uk-width-1-3 uk-width-small@m uk-text-center"><a href="#" class="font uk-position-relative uk-display-block">Online ISRU</a></li>
                                    <li class="uk-display-inline-block uk-width-1-3 uk-width-small@m uk-text-center"><a href="#" class="font uk-position-relative uk-display-block">Events</a></li>
                                    <li class="uk-display-inline-block uk-width-1-3 uk-width-small@m uk-text-center"><a href="#" class="font uk-position-relative uk-display-block">Latest News</a></li>
                                </ul>
                            </div>
                            <div class="uk-width-1-1 uk-width-1-3@m uk-flex uk-flex-right uk-flex-middle uk-visible@m">
                                <ul class="uk-padding-remove socials uk-text-center uk-text-right@l">
                                    <?php for($i=0;$i<$total;$i++) { ?>
                                        <?php if ($socialsicons['title'][$i] != '') { ?>
                                            <li class="uk-display-inline-block uk-margin-left"><a href="<?php echo $socialsicons['link'][$i]; ?>" target="_blank" title="<?php echo $socialsicons['title'][$i]; ?>" class="uk-display-inline-block uk-border-rounded"><i class="<?php echo $socialsicons['icon'][$i]; ?>"></i></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
        <div class="uk-container">
            <div uk-grid>
                <?php if ($this->countModules('asidestart')) : ?>
                    <div class="uk-width-1-1 uk-width-1-4@m">
                        <aside><jdoc:include type="modules" name="asidestart" style="xhtml" /></aside>
                    </div>
                <?php endif; ?>
                <div class="uk-width-1-1 uk-width-expand@m">
                    <article>
                        <jdoc:include type="message" />
                        <?php if ($menu->getParams( $active->id )->get('pageclass_sfx') != 'home') { ?>
                        <jdoc:include type="component" />
                        <?php } else { ?>
                            <div class="uk-padding uk-padding-remove-horizontal">
                                <div class="uk-grid-divider" uk-grid>
                                    <div class="uk-width-1-1 uk-width-2-3@m uk-width-3-4@l">
                                        <div class="uk-switcher uk-margin homeTabs">
                                            <jdoc:include type="modules" name="hometabs" style="xhtml" />
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1 uk-width-1-3@m uk-width-1-4@l">
                                        <div uk-sticky="offset: 107; bottom: true;"><jdoc:include type="modules" name="hometabside" style="xhmtml" /></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </article>
                </div>
                <?php if ($this->countModules('asideend')) : ?>
                    <div class="uk-width-1-1 uk-width-1-4@m">
                        <aside>
                            <div class="uk-child-width-1-1" uk-grid><jdoc:include type="modules" name="asideend" style="aside" /></div>
                        </aside>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<footer class="uk-position-relative">
    <div class="footerWrapper">
        <div class="uk-container">
            <div>
                <div class="uk-grid-small" uk-grid>
                    <?php if ($this->countModules('newsletter')) : ?>
                    <div class=" uk-width-1-1 uk-width-1-1@m uk-width-1-3@l moduleWrapper newsletterWrapper">
                        <h3 class="uk-text-center uk-text-left@m"><?php echo JTEXT::_('NEWSLETTER'); ?></h3>
                        <div><jdoc:include type="modules" name="newsletter" style="none" /></div>
                        <ul class="uk-padding-remove socials uk-text-center uk-text-left@l">
                            <?php for($i=0;$i<$total;$i++) { ?>
                                <?php if ($socialsicons['title'][$i] != '') { ?>
                                    <li class="uk-display-inline-block uk-margin-right"><a href="<?php echo $socialsicons['link'][$i]; ?>" target="_blank" title="<?php echo $socialsicons['title'][$i]; ?>" class="uk-display-inline-block"><i class="<?php echo $socialsicons['icon'][$i]; ?>"></i></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="uk-width-1-1 uk-width-1-2@m uk-width-1-3@l moduleWrapper">
                        <h3 class="uk-text-center uk-text-left@m"><?php echo JTEXT::_('CONTACTINFO'); ?></h3>
                        <div class="moduleBody">
                            <?php if ($params->get('phone') || $params->get('fax') || $params->get('email')) { ?>
                                <ul class="uk-padding-remove font uk-child-width-1-1 uk-grid-small" uk-grid>
                                    <?php if($params->get('address')) { echo '<li><i class="fas uk-text-muted fa-map-marker-alt uk-margin-small-right fa-fw"></i><span class="uk-margin-small-right uk-text-muted">'.JTEXT::_('address').'</span>'.$params->get('address').'</li>'; } ?>
                                    <?php if($params->get('phone')) { echo '<li><i class="fas uk-text-muted fa-phone uk-margin-small-right fa-fw"></i><span class="uk-margin-small-right uk-text-muted">'.JTEXT::_('phone').'</span>'.$params->get('phone').'</li>'; } ?>
                                    <?php if($params->get('fax')) { echo '<li><i class="fas uk-text-muted fa-fax uk-margin-small-right fa-fw"></i><span class="uk-margin-small-right uk-text-muted">'.JTEXT::_('fax').'</span>'.$params->get('fax').'</li>'; } ?>
                                    <?php if($params->get('email')) { echo '<li><i class="fas uk-text-muted fa-envelope uk-margin-small-right fa-fw"></i><span class="uk-margin-small-right uk-text-muted">'.JTEXT::_('email').'</span>'.$params->get('email').'</li>'; } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                    <jdoc:include type="modules" name="footer" style="xhtml" />
                    <div class="uk-width-1-1">
                        <hr class="uk-margin-bottom uk-margin-top">
                    </div>
                    <div class="copyright uk-width-1-1@s uk-width-1-2@m">
                        <p class="uk-text-center uk-text-left@m font uk-text-tiny"><?php echo '<span class="uk-margin-small-right"><i class="far fa-copyright"></i> 2006</span>'.JTEXT::sprintf('COPYRIGHT', $sitename); ?></p>
                    </div>
                    <?php if ($this->countModules('fmenu')) : ?>
                    <div class="footerMenu uk-width-1-1 uk-width-1-2@m uk-visible@l"><jdoc:include type="modules" name="fmenu" style="xhtml" /></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <a href="#top" uk-scroll class="backToTop uk-position-absolute uk-position-bottom-center uk-visible@l"><i class="fas fa-angle-up"></i></a>
</footer>

<!-- Off-Canvas -->
<div class="uk-offcanvas-content uk-light">
    <div class="uk-light" id="offcanvas-usage" uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar uk-light">
            <button class="uk-offcanvas-close" type="button" uk-close></button>
            <jdoc:include type="modules" name="mobside" style="xhtml" />
        </div>
    </div>
</div>

<div class="mobSearch mobSearchWrapper uk-card uk-card-body uk-card-default uk-width-1-1 uk-position-top" hidden><jdoc:include type="modules" name="mobsearch" style="xhtml" /></div>

</body>
</html>