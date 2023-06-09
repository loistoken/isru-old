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
$user = JFactory::getUser();

$user_id = "";
$user_username = "";
$user_email = "";
$firstname = "";
$lastname = "";
$company = "";
$returnpage = JFactory::getApplication()->input->get("returnpage", "");
$Itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
$image = "";
$is_student = false;

$document = JFactory::getDocument();
$document->setTitle(trim(JText::_('GURU_MY_ACCOUNT')));

if(isset($user)){
	$user_id = $user->id;
	$user_username = $user->username;
	$user_email = $user->email;
	
	$customer_profile = $this->getCustomerProfile();
	
	if(isset($customer_profile) && count($customer_profile) > 0){
		$firstname = $customer_profile["0"]["firstname"];
		$lastname = $customer_profile["0"]["lastname"];
		$company = $customer_profile["0"]["company"];
		$image = $customer_profile["0"]["image"];
		$is_student = true;
	}
	else{
		$is_student = false;
		$name = $user->name;
		$temp = explode(" ", $name);
		if(count($temp) == 1){
			$firstname = $name;
		}
		else{
			$firstname = $temp["0"];
			unset($temp["0"]);
			$lastname = implode(" ", $temp);
		}
	}
}

include_once(JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_guru".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."helper.php");
$helper = new guruHelper();
$div_menu = $helper->createStudentMenu();
$page_title_cart = $helper->createPageTitleAndCart();

?>

<script language="javascript" type="text/javascript">
	function validateForm(){
		var first_name = document.adminForm.firstname.value;
		var last_name = document.adminForm.lastname.value;
		if(first_name == ""){
			alert("Firs Name is mandatory!");
			return false;
		}
		else if(last_name == ""){
			alert("Last Name is mandatory!");
			return false;
		}		
        if(document.adminForm.password.value != document.adminForm.password_confirm.value){
			alert("<?php echo JText::_("DSCONFIRM_PASSWORD_MSG");?>");
            return false;
        }   				
		return true;
	}
	
	function deleteImage(){
		document.getElementById("view_imagelist23").src = "<?php echo JURI::root(); ?>components/com_guru/images/blank.png";
		document.getElementById("image").value = "";
	}
</script>

<div class="gru-myprofile">
    <form onsubmit="return validateForm();" id="adminForm" name="adminForm" method="post" action="index.php" class="uk-form uk-form-horizontal">

        <div class="" uk-grid>
            <div class="uk-width-1-1 uk-width-3-4@m">
                <div class="uk-alert uk-border-rounded uk-padding uk-margin-remove-top uk-margin-medium-bottom">
                    <div class="uk-text-center">
                        <?php if (file_exists('images/avatars/'.$user->username.'.jpg')) { ?>
                            <img class="uk-text-circle uk-border-circle" src="images/avatars/<?php echo $user->username; ?>.jpg" width="100" height="100" alt="<?php echo $firstname.' '.$lastname; ?>">
                        <?php } else { ?>
                            <img class="uk-text-circle uk-border-circle" src="images/avatars/avatar_placeholder.jpg" width="100" height="100" alt="<?php echo $firstname.' '.$lastname; ?>">
                        <?php } ?>
                    </div>
                    <p class="uk-text-small uk-text-black font uk-text-center uk-margin-small-bottom uk-text-capitalize uk-text-muted">Welcome to your dashboard</p>
                    <h3 class="uk-text-center font uk-text-uppercase uk-margin-remove-top uk-margin-small-bottom"><?php echo $firstname.' '.$lastname; ?></h3>
                    <p class="uk-text-small uk-text-muted font uk-text-center">
                        <?php echo '<i class="fas fa-user"></i> <span class="uk-margin-right">'.$user_username.'</span><span><i class="fas fa-envelope"></i> '.$user_email.'</span>'; ?>
                    </p>
                    <?php
                    $khUser = JFactory::getUser();
                    $groups = $khUser->groups;
                    ?>
                    <?php if (in_array(11, $groups)): ?>
                    <div class="uk-text-center">
                        <a href="<?php echo JURI::base().'profile-details'; ?>" class="uk-button uk-button-small uk-button-cyan">More Information</a>
                    </div>
                    <?php endif;  ?>
                </div>
                <div class="uk-grid-divider uk-child-width-1-1 uk-child-width-1-2@m" uk-grid>
                    <div>
                        <h3 class="font uk-text-center uk-text-left@l">Profile Settings</h3>
                        <div class="uk-grid-small uk-grid" uk-grid>
                            <div class="uk-width-1-1 uk-grid-margin uk-first-column">
                                <input placeholder="<?php echo JText::_("GURU_EMAIL");?>" type="text" class="inputbox uk-input uk-width-1-1 uk-border-rounded" size="30" id="email" name="email" disabled="disabled" value="<?php echo $user_email; ?>"/>
                            </div>
                            <div class="uk-width-1-1 uk-width-1-2@m">
                                <input placeholder="<?php echo JText::_("GURU_FIRS_NAME");?>" type="text" class="inputbox uk-input uk-width-1-1 uk-border-rounded" size="30" id="firstname" name="firstname" disabled="disabled" value="<?php echo $firstname; ?>" />
                            </div>
                            <div class="uk-width-1-1 uk-width-1-2@m">
                                <input placeholder="<?php echo JText::_("GURU_LAST_NAME");?>" type="text" class="inputbox uk-input uk-width-1-1 uk-border-rounded" size="30" id="lastname" name="lastname" disabled="disabled" value="<?php echo $lastname; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font uk-text-center uk-text-left@l">Login information</h3>
                        <div class="uk-grid-small uk-grid" uk-grid>
                            <div class="uk-width-1-1">
                                <input placeholder="<?php echo JText::_("GURU_PROFILE_USERNAME");?>" type="text" class="inputbox uk-input uk-width-1-1 uk-border-rounded" size="30" id="username" disabled="disabled" name="username"  value="<?php echo $user_username; ?>" />
                            </div>
                            <?php if($returnpage != "checkout") { ?>
                                <div class="uk-width-1-1 uk-width-1-2@m">
                                    <input placeholder="<?php echo JText::_("GURU_PROFILE_REG_PSW");?>" type="password" class="inputbox uk-input uk-width-1-1 uk-border-rounded" size="30" id="password" name="password" />
                                </div>
                                <div class="uk-width-1-1 uk-width-1-2@m uk-grid-margin">
                                    <input placeholder="<?php echo JText::_("GURU_PROFILE_REG_PSW2");?>" type="password" class="inputbox uk-input uk-width-1-1 uk-border-rounded" size="30" id="password_confirm" name="password_confirm"/>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="password" value=""/>
                                <input type="hidden" name="password_confirm" value="" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="uk-width-1-1">
                        <input type="submit" value="<?php echo JText::_("GURU_UPDATE_MY_PROFILE"); ?>" class="uk-button uk-button-secondary uk-width-1-1">
                    </div>
                </div>



                <?php /* ?>
        <div class="uk-form-row">
            <label class="uk-form-label" for="name">
                <?php echo JText::_("GURU_COMPANY");?>:
            </label>
            <div class="uk-form-controls">
                <input type="text" class="inputbox" size="30" id="company" name="company" value="<?php echo $company; ?>"/>
            </div>
        </div>
                <?php */ ?>
        
        <?php
            $config = $this->configs;
            
            $max_upload = (int)(ini_get('upload_max_filesize'));
            $max_post = (int)(ini_get('post_max_size'));
            $memory_limit = (int)(ini_get('memory_limit'));
            $upload_mb = min($max_upload, $max_post, $memory_limit);
            if($upload_mb == 0){
                $upload_mb = 10;
            }
            $upload_mb *= 1048576; //transform in bytes
            $doc = JFactory::getDocument();
            
            $config_author = json_decode($config["0"]["authorpage"]);
            $author_t_prop = $config_author->author_image_size_type == "0" ? "width" : "heigth";

            $doc->addScriptDeclaration('
                jQuery.noConflict();
                jQuery(function(){
                    function createUploader(){
                        var uploader = new qq.FileUploader({
                            element: document.getElementById(\'fileUploader\'),
                            action: \''.JURI::root().'index.php?option=com_guru&controller=guruLogin&tmpl=component&format=raw&task=upload_ajax_image\',
                            params:{
                                folder:\'customers\',
                                mediaType:\'image\',
                                size: '.$config_author->author_image_size.',
                                type: \''.$author_t_prop.'\'
                            },
                            onSubmit: function(id,fileName){
                                jQuery(\'.qq-upload-list li\').css(\'display\',\'none\');
                            },
                            onComplete: function(id,fileName,responseJSON){
                                //alert(\'id: \'+ id + \'; filename:\' + fileName);
                                if(responseJSON.success == true){						
                                    jQuery(\'.qq-upload-success\').append(\'- <span style="color:#387C44;">Upload successful</span>\');
                                    if(responseJSON.locate) {
                                        jQuery(\'#view_imagelist23\').attr("src", \''.JURI::root().'\'+responseJSON.locate +"/"+ fileName+"?timestamp=" + new Date().getTime());
                                        jQuery(\'#image\').val("/"+responseJSON.locate +"/"+ fileName);
                                    }
                                }
                            },
                            allowedExtensions: [\'jpg\', \'jpeg\', \'png\', \'gif\', \'JPG\', \'JPEG\', \'PNG\', \'GIF\', \'xls\', \'XLS\'],
                            sizeLimit: '.$upload_mb.',
                            multiple: false,
                            maxConnections: 1
                        });           
                    }
                    createUploader();
                });
            ');
            //$doc->addScript('components/com_guru/js/fileuploader.js');
            $doc->addStyleSheet('components/com_guru/css/fileuploader.css');
        ?>
        
        <div class="uk-form-row uk-hidden">
            <label class="uk-form-label" for="name">
                <?php echo JText::_("GURU_UPLOAD_IMAGE");?>:
            </label>
            <div class="uk-form-controls">
                <div id="fileUploader"></div>
                <input type="hidden" name="image" id="image" value="<?php echo $image; ?>" />
            </div>
        </div>

        <?php /* if(isset($image) && $image != ""){ 	?>
                <div class="uk-form-row">
                    <label class="uk-form-label" for="name">
                        <?php echo JText::_("GURU_SEL_IMAGE");?>:
                    </label>
                    <div class="uk-form-controls">
                        <div id='authorImageSelected'>
                            <img id="view_imagelist23" name="view_imagelist" src='<?php echo JURI::root().$image; ?>'/><br />
                        </div>
                        <br />
                        <input type="button" class="uk-button uk-button-danger" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="return deleteImage();"/>
                        <input type="hidden" value="<?php echo $image; ?>" name="img_name" id="img_name" />
                    </div>
                </div>
        <?php
            }
            else{
        ?>
                <div class="uk-form-row">
                    <label class="uk-form-label" for="name">
                    </label>
                    <div class="uk-form-controls">
                        <div id='authorImageSelected'>
                            <img id='view_imagelist23' name='view_imagelist' src="<?php echo JURI::root(); ?>components/com_guru/images/blank.png"/>
                        </div>
                    </div>
                </div>
        <?php } */ ?>


                <?php
                if($user->id == "0"){
                    echo JText::_("DSEMAILNOTE");
                }
                ?>

            </div>
            <div class="uk-width-1-1 uk-width-1-4@m">
                <?php
                /* if(!$is_student){
                    echo '<div class="uk-alert uk-alert-warning">'.JText::_("GURU_NOT_STUDENT_COMPLETE_PROFILE").'</div>';
                } */

                echo $div_menu;
                echo $page_title_cart;
                ?>

            </div>
        </div>
    
    <input type="hidden" value="0" name="Itemid" />
    <input type="hidden" value="com_guru" name="option" />
    <input type="hidden" value="<?php echo $user_id; ?>" name="id" />
    <input type="hidden" value="saveCustomer" name="task" />
    <input type="hidden" value="<?php echo $returnpage; ?>" name="returnpage" />
    <input type="hidden" value="guruProfile" name="controller" />
    <input type="hidden" value="<?php echo $user_username; ?>" name="username" />
    <input type="hidden" value="<?php echo $user_email; ?>" name="email" />
    </form>
</div>