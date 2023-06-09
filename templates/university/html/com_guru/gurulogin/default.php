<?php
/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com.com/forum/index/
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_SITE . '/components/com_users/helpers/route.php';
JHtml::_('behavior.keepalive');

$lang = JFactory::getLanguage();
$extension = 'mod_login';
$base_dir = JPATH_SITE;
$language_tag = 'en-GB';
$lang->load($extension, $base_dir, '', true);

$Itemid = JFactory::getApplication()->input->get("Itemid", "0");
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");
$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );

require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');  
$document->setTitle(JText::_("GURU_ALREADY_MEMBER"));
$returnpageoR = JFactory::getApplication()->input->get("returnpage", "");

if($returnpageoR == 'authorprofile' || $returnpageoR == "authormymedia" || $returnpageoR == "authormymediacategories" || $returnpageoR == "mystudents" || $returnpageoR == "authormycourses"){
	$returnpageo = 'authorprofile';
}
else{
	$returnpageo = $returnpageoR;
}

?>

<script type="text/javascript" language="javascript">
	document.body.className = document.body.className.replace("modal", "");
</script>
<div class="gru-login gru-page">
    <div class="uk-flex-center" uk-grid>
        <div class="uk-width-1-1 uk-width-2-3@l">
            <div>
                <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-divider uk-grid-large" uk-grid>
                    <!-- Login box -->
                    <div>
                        <form name="loginForm" method="post" class="uk-form" action="index.php">
                            <div class="gru-login-box uk-panel uk-panel-box uk-panel-header">
                                <h3 class="font uk-text-center uk-text-left@l"><?php echo JText::_("GURU_HAVE_ACCOUNT"); ?></h3>
                                <div class="uk-child-width-1-1 uk-grid-small" uk-grid>
                                    <div><input class="uk-input uk-width-1-1 uk-text-small uk-border-rounded font" type="text" id="username" name="username" placeholder="<?php echo JText::_("GURU_USERNAME"); ?>" /></div>
                                    <div><input class="uk-input uk-width-1-1 uk-text-small uk-border-rounded font" type="password" class="form-control" id="passwd" name="passwd" placeholder="<?php echo JText::_("GURU_PASSWORD"); ?>" /></div>
                                    <div class="uk-width-1-1 uk-width-1-2@l"><input type="submit" onclick="document.loginForm.submit();" class="uk-button uk-button-secondary uk-width-1-1" name="submit_button" value="<?php echo JText::_("GURU_LOGIN_AND_CONTINUE"); ?>" /></div>
                                    <div class="uk-width-1-2 uk-hidden"><label for="remember"><input class="uk-checkbox" type="checkbox" name="remember" value="1" checked /><?php echo JText::_("GURU_PROFILE_REMEMBER_ME");?></label></div>
                                </div>
                            </div>
                            <input type="hidden" name="option" value="com_guru" />
                            <input type="hidden" name="controller" value="guruLogin" />
                            <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                            <input type="hidden" name="task" value="log_in_user" />
                            <input type="hidden" name="returnpage" value="<?php echo JFactory::getApplication()->input->get("returnpage", ""); ?>" />
                            <input type="hidden" name="lesson_id" value="<?php echo JFactory::getApplication()->input->get("lesson_id", "0"); ?>" />
                            <input type="hidden" name="cid" value="<?php echo JFactory::getApplication()->input->get("cid", "0"); ?>" />
                        </form>
                    </div>
                    <!-- Register box -->
                    <?php $params = JComponentHelper::getParams('com_users'); $allowUserRegistration = $params->get('allowUserRegistration'); if($allowUserRegistration) { ?>
                    <div>
                        <h3 class="font uk-text-center uk-text-left@l"><?php echo JText::_("GURU_CREATE_NEW_ACCOUNT"); ?></h3>
                        <?php if($returnpageo != "authorprofile"){ ?>
                            <form name="register" method="post" class="uk-form">
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruLogin" />
                                <input type="hidden" name="Itemid" value="<?php echo JFactory::getApplication()->input->get("Itemid", "0"); ?>" />
                                <input type="hidden" name="task" value="register" />
                                <input type="hidden" name="returnpage" value="<?php echo JFactory::getApplication()->input->get("returnpage", ""); ?>" />
                                <input type="hidden" name="lesson_id" value="<?php echo JFactory::getApplication()->input->get("lesson_id", "0"); ?>" />
                                <input type="hidden" name="cid" value="<?php echo JFactory::getApplication()->input->get("cid", "0"); ?>" />
                                <!--start case of student rgistration -->
                                <p class="font uk-text-small uk-text-center uk-text-left@l"><?php echo JText::_("GURU_REGISTRATION_EASY_STUDENT"); ?></p>
                                <?php /* ?>
                                <input type="submit" class="uk-button uk-button-default uk-width-1-1 uk-width-auto@l" value="<?php echo JText::_("GURU_REGISTER_AS_STUDENT");?>" />
                                <?php */ ?>
                                <a href="online-registration" class="uk-button uk-button-default uk-width-1-1 uk-width-auto@l"><?php echo JText::_("GURU_REGISTER_AS_STUDENT");?></a>
                                <!-- end of student registration -->
                            </form>
                        <?php }
                        elseif($returnpageo == "authorprofile"){?>
                            <form name="register" method="post" class="uk-form">
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruAuthor" />
                                <input type="hidden" name="Itemid" value="<?php echo JFactory::getApplication()->input->get("Itemid", "0"); ?>" />
                                <input type="hidden" name="task" value="authorregister" />
                                <input type="hidden" name="returnpage" value="<?php echo JFactory::getApplication()->input->get("returnpage", ""); ?>" />
                                <input type="hidden" name="lesson_id" value="<?php echo JFactory::getApplication()->input->get("lesson_id", "0"); ?>" />
                                <input type="hidden" name="cid" value="<?php echo JFactory::getApplication()->input->get("cid", "0"); ?>" />
                                <!-- start case of teacher registration -->
                                <p class="font uk-text-small uk-text-center uk-text-left@l"><?php echo JText::_("GURU_REGISTRATION_EASY_TEACHER"); ?></p>
                                <input type="submit" class="uk-button uk-button-default uk-width-1-1 uk-width-auto@l" value="<?php echo JText::_("GURU_REGISTER_AS_TEACHER");?>" />
                                <!-- end of teacher registration -->
                            </form>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <div class="uk-width-1-1">
                        <div class="uk-grid-small uk-grid-divider uk-child-width-auto uk-flex-center" uk-grid>
                            <div><a class="uk-text-tiny uk-text-muted" href="<?php echo JRoute::_('index.php?option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>"><i class="fas fa-user uk-margin-small-right"></i><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a></div>
                            <div><a class="uk-text-tiny uk-text-muted" href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>"><i class="fas fa-lock uk-margin-small-right"></i><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>