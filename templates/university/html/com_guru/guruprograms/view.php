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
$k = 0;
$guruModelguruProgram = new guruModelguruProgram();
// --------------------------- unload ijseo_plugin
jimport( 'joomla.plugin.helper' );
class iJoomlaPlugin extends JPluginHelper{
    function unloadFromPlugin($type, $name){
        $plugins = JPluginHelper::getPlugin("content");
        $plugins = JPluginHelper::$plugins;
        
        if(isset($plugins) && count($plugins) > 0){
            foreach($plugins as $key=>$value){
                if($value->name == $name && $value->type == $type){
                    unset($plugins[$key]);
                    JPluginHelper::$plugins = $plugins;
                    break;
                }
            }
        }
    }
}
$db = JFactory::getDBO();
$sql = "SELECT  guru_ignore_ijseo from #__guru_config where id =1";
$db->setQuery($sql);
$db->execute();
$res = $db->loadResult();

if($res == 0){
    $iJoomlaPlugin = new iJoomlaPlugin();
    $iJoomlaPlugin->unloadFromPlugin("content", "ijseo_plugin");
}
// --------------------------- unload ijseo_plugin


require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
    
$document = JFactory::getDocument();
$guruHelper = new guruHelper ();

$db = JFactory::getDBO();
$sql = "SELECT guru_turnoffjq  FROM  #__guru_config WHERE id=1";
$db->setQuery($sql);
$db->execute();
$guru_turnoffjq = $db->loadResult();

$document->addScriptDeclaration('
    jQuery.noConflict();
    jQuery(function(){
        jQuery(".subcat").find("hr:last").css("display","block"); 
    });
');


$program            = $this->program;   
$config             = $this->getConfigSettings;
$pdays              = $this->pdays; 
$author             = $this->author;
$courses            = $this->courses;
$programContent     = $this->programContent;
$exercise           = $this->exercise;
$requirements       = $this->requirements;
$number_of_days_per_program = count($pdays);

// how many points and how much time has a program
//$getsum_points_and_time = $this->getsum_points_and_time;

$my = JFactory::getUser();

$document   = JFactory::getDocument();
$document->setTitle($program->metatitle);
$document->setMetaData('keywords', $program->metakwd); 
$document->setMetaData('description', $program->metadesc); 

$db = JFactory::getDBO();
$sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM #__guru_program where id = ".intval($program->id);
$db->setQuery($sql);
$db->execute();
$result= $db->loadAssocList();
$chb_free_courses = $result["0"]["chb_free_courses"];
$step_access_courses = $result["0"]["step_access_courses"];
$selected_course = $result["0"]["selected_course"];

$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
$selected_course_final = explode('|', $selected_course);

if(implode(", ", $selected_course_final) != ''){
    foreach($selected_course_final as $key=>$value){
        if(trim($value) == ""){
            unset($selected_course_final[$key]);
        }
    }
    
    
    $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
    
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadAssocList();
    $all_title = array();
    
    if(isset($result) && count($result) > 0){
        foreach($result as $key=>$course){
            $all_title[] = $course["name"];
        }
    }
    $all_title = implode(", ", $all_title);
}
$sql = "select certificate_term from #__guru_program  where id = ".intval($program->id);
$db->setQuery($sql);
$db->execute();
$certificate_term = $db->loadResult();

$sql = "select avg_certc from #__guru_program where id = ".intval($program->id);
$db->setQuery($sql);
$db->execute();
$avg_cert = $db->loadResult();

$sql = "SELECT max_score FROM #__guru_quiz WHERE is_final= 1 LIMIT 1";
$db->setQuery($sql);
$result_maxs = $db->loadResult();


if($config->display_media == 1){
    $the_media = $guruModelguruProgram->find_intro_media($program->id);
    $no_plugin_for_code = 0;
    $aheight = 0; 
    $awidth = 0; 
    $vheight = 0; 
    $vwidth = 0;
    if(isset($the_media)){
        $the_media->code = stripslashes($the_media->code);
        if($the_media->type == 'video'){
            if($the_media->source == 'url' || $the_media->source == 'local'){
                if ($the_media->width == 0 || $the_media->height == 0){
                    $vheight = 300; 
                    $vwidth = 400;
                }
                else{
                    $vheight=$the_media->height; $vwidth=$the_media->width;
                }       
            }
            elseif ($the_media->source=='code'){
                if ($the_media->width == 0 || $the_media->height == 0){
                    $begin_tag = strpos($the_media->code, 'width="');
                    if($begin_tag !== false){
                        $remaining_code = substr($the_media->code, $begin_tag+7, strlen($the_media->code));
                        $end_tag = strpos($remaining_code, '"');
                        $vwidth = substr($remaining_code, 0, $end_tag);
                                    
                        $begin_tag = strpos($the_media->code, 'height="');
                        if($begin_tag !== false){
                            $remaining_code = substr($the_media->code, $begin_tag+8, strlen($the_media->code));
                            $end_tag = strpos($remaining_code, '"');
                            $vheight = substr($remaining_code, 0, $end_tag);
                            $no_plugin_for_code = 1;
                        }
                        else{
                            $vheight = 300; 
                            $vwidth = 400;
                        }   
                    }
                    else{
                        $vheight = 300; 
                        $vwidth = 400;
                    }   
                }
                else{
                    $replace_with = 'width="'.$the_media->width.'"';
                    $the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
                    $replace_with = 'height="'.$the_media->height.'"';
                    $the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
                    $vheight=$the_media->height; $vwidth=$the_media->width;                     
                }
            }   
        }
        elseif($the_media->type == 'audio'){
            if ($the_media->source == 'url' || $the_media->source == 'local'){  
                if ($the_media->width == 0 || $the_media->height == 0){
                    $aheight=20;
                    $awidth=300;
                }
                else{
                    $aheight=$the_media->height; 
                    $awidth=$the_media->width;
                }
            }       
            elseif ($the_media->source=='code'){
                if ($the_media->width == 0 || $the_media->height == 0){
                    $begin_tag = strpos($the_media->code, 'width="');
                    if ($begin_tag !== false){
                        $remaining_code = substr($the_media->code, $begin_tag+7, strlen($the_media->code));
                        $end_tag = strpos($remaining_code, '"');
                        $awidth = substr($remaining_code, 0, $end_tag); 
                        $begin_tag = strpos($the_media->code, 'height="');
                        if ($begin_tag !== false){
                            $remaining_code = substr($the_media->code, $begin_tag+8, strlen($the_media->code));
                            $end_tag = strpos($remaining_code, '"');
                            $aheight = substr($remaining_code, 0, $end_tag);
                            $no_plugin_for_code = 1;
                        }
                        else{
                            $aheight = 20;
                            $awidth = 300;
                        }   
                    }
                    else{
                        $aheight = 20;
                        $awidth = 300;
                    }                           
                }
                else{                   
                    $replace_with = 'width="'.$the_media->width.'"';
                    $the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
                    $replace_with = 'height="'.$the_media->height.'"';
                    $the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
                    $aheight=$the_media->height; $awidth=$the_media->width;
                }
            }   
        }   
        if ($no_plugin_for_code == 0){
            $media = $guruHelper->create_media_using_plugin($the_media, $config, $aheight, $awidth, $vheight, $vwidth);
        }
        else{
            $media = $the_media->code;
        }
    }
    else{
        $media = '';
    }
}
elseif($config->display_media == 0){
    $media = '';
}   

$public_data = $program->startpublish;
$int_date    = strtotime($public_data);
$data        = date($config->datetype,$int_date);

$view = $this->view->getView("guruPrograms", "html");
$view->setLayout("tabs");
$view->show();

$course_config = json_decode($config->psgpage);
$course_style = json_decode($config->st_psgpage);

$wrap = $course_config->course_wrap_image; //0-yes, 1-no
$img_align = $course_config->course_image_alignment; //0-left, 1-right
$type = $course_config->course_image_size_type == "0" ? "w" : "h";

if(trim($program->image) != ""){
    $array = explode("/", $program->image);
    if(isset($array) && count($array) > 0){
        $program->imageN = $array[count($array)-1];
    }
}

if(trim($program->image) == "" || $program->image == NULL ){
    $program->image = "components/com_guru/images/no_image.gif";
    $guruHelper->createThumb($program->image, "components/com_guru/images", $course_config->course_image_size, $type);
}
else{
    $guruHelper->createThumb($program->imageN, $config->imagesin."/courses", $course_config->course_image_size, $type);
    $program->image = str_replace("thumbs/", "", $program->image);
}

$img_size = getimagesize(JPATH_SITE.DIRECTORY_SEPARATOR.$program->image);
$height = "auto";
if(isset($img_size) && isset($img_size["1"])){
    $height = $img_size["1"];
}
$db = JFactory::getDBO();
$sql = "SELECT currency, currencypos, course_lesson_release  from #__guru_config where id =1";
$db->setQuery($sql);
$db->execute();
$res = $db->loadAssoc();
$amount_students = $guruModelguruProgram->getStudentAmount($program->id);
$class_cover ='guru-cover-details';
$class_wrap = "";
$text = "";

$style = "";
$show_course_image = "0"; //isset($course_config->show_course_image) ? $course_config->show_course_image : "0";

if(trim($program->image != "") && $show_course_image == 0){
    $style = 'style="background-image:url(\''.JURI::root().$program->image.'\');"';
}

$show_course_price = "";
$show_course_release = "";
$show_course_cert = "";

if ($course_config->course_price == "0") {
    $curent_currency = "GURU_CURRENCY_".$res["currency"];

    if ($course_config->course_price_type == 0 ) {
        $prices = $guruModelguruProgram->getOnlyPricesR($program->id);
    }
    else{
        $prices = $guruModelguruProgram->getOnlyPrices($program->id);
    }

    if(isset($chb_free_courses) && $chb_free_courses == 1) {
		if ($step_access_courses == 2) {

			$text = JText::_("GURU_FREE_GUEST_PRICE");
		}
		elseif ($step_access_courses == 1) {
			if (isset($prices)) {
				if ($res["currencypos"] == '0') {
					if(strpos($prices, "-") !== false){
						$prices = str_replace("-", " - ".JText::_($curent_currency)." ", $prices);
						$text = JText::_($curent_currency)." ".$prices;
					}
					else{
						$text = JText::_($curent_currency)." ".$prices;
					}
				}
				else{
					if(strpos($prices, "-") !== false){
						$prices = str_replace("-", " ".JText::_($curent_currency)." - ", $prices);
						$text = $prices." ".JText::_($curent_currency);
					}
					else{
						$text = $prices." ".JText::_($curent_currency);
					}
				}
			}
		}
		elseif ($step_access_courses == 0 && $selected_course == -1) {
			if (isset($prices)) {
				if ($res["currencypos"] == '0') {
					if(strpos($prices, "-") !== false){
						$prices = str_replace("-", " - ".JText::_($curent_currency)." ", $prices);
						$text = JText::_($curent_currency)." ".$prices." ".JText::_("GURU_FREE_FOR_STUDENTS_AC_PRICE2");
					}
					else{
						$text = JText::_($curent_currency)." ".$prices." ".JText::_("GURU_FREE_FOR_STUDENTS_AC_PRICE2");
					}
				}
				else{
					if(strpos($prices, "-") !== false){
						$prices = str_replace("-", " ".JText::_($curent_currency)." - ", $prices);
						$text = $prices." ".JText::_($curent_currency)." ".JText::_("GURU_FREE_FOR_STUDENTS_AC_PRICE2");
					}
					else{
						$text = $prices." ".JText::_($curent_currency)." ".JText::_("GURU_FREE_FOR_STUDENTS_AC_PRICE2");
					}
				}
			}
		}
		elseif($step_access_courses == 0 && $selected_course != -1) {
			$text = JText::_("GURU_FREE_FOR_STUDENTS_SC_PRICE")." ".$all_title;
			
			if (isset($prices)) {
				if ($res["currencypos"] == '0') {
					if(strpos($prices, "-") !== false){
						$prices = str_replace("-", " - ".JText::_($curent_currency)." ", $prices);
						$text = JText::_($curent_currency)." ".$prices." (".$text.")";
					}
					else{
						$text = JText::_($curent_currency)." ".$prices." (".$text.")";
					}
				}
				else{
					if(strpos($prices, "-") !== false){
						$prices = str_replace("-", " ".JText::_($curent_currency)." - ", $prices);
						$text = $prices." ".JText::_($curent_currency)." (".$text.")";
					}
					else{
						$text = $prices." ".JText::_($curent_currency)." (".$text.")";
					}
				}
			}
			
		}

        $show_course_price .= '<li><i class="uk-icon-usd"></i> <span class="uk-text-bold">'.JText::_("GURU_BUY_PRICE").':</span> '.$text.'</li>';
    }
	else{
        if (isset($prices)) {
            if ($res["currencypos"] == '0') {
                if(strpos($prices, "-") !== false){
					$prices = JText::_($curent_currency)." ".str_replace("-", " - ".JText::_($curent_currency)." ", $prices);
				}
				else{
					$prices = JText::_($curent_currency)." ".$prices;
				}
				
				$show_course_price .= '<li><i class="uk-icon-usd"></i> <span class="uk-text-bold">'.JText::_("GURU_BUY_PRICE").':</span> '.$prices."</li>";
            }
            else{
				if(strpos($prices, "-") !== false){
					$prices = str_replace("-", " ".JText::_($curent_currency)." - ", $prices)." ".JText::_($curent_currency);
				}
				else{
					$prices = $prices." ".JText::_($curent_currency);
				}
			
                $show_course_price .= '<li><i class="uk-icon-usd"></i> <span class="uk-text-bold">'.JText::_("GURU_BUY_PRICE").':</span> '.$prices."</li>";
            }
        }
    }
}

$res_progr =$guruModelguruProgram->getLessonReleaseType($program->id);
    
if($res["course_lesson_release"] == '0'){
    if($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 0){
        $show_course_release .= '<li><i class="uk-icon-calendar"></i> <span class="uk-text-bold">'.JText::_("GURU_RELEASED_DATE").':</span> '.JText::_("GURU_ALL_AT_ONCE").'</li>';
    }
    elseif($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 1){
        $show_course_release .= '<li><i class="uk-icon-calendar"></i> <span class="uk-text-bold">'.JText::_("GURU_RELEASED_DATE").':</span> '.JText::_("GURU_ONE_PER_DAY").'</li>';
    }
    elseif($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 2){
        $show_course_release .= '<li><i class="uk-icon-calendar"></i> <span class="uk-text-bold">'.JText::_("GURU_RELEASED_DATE").':</span> '.JText::_("GURU_ONE_PER_W").'</li>';
    }
    elseif($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 3){
        $show_course_release .= '<li><i class="uk-icon-calendar"></i> <span class="uk-text-bold">'.JText::_("GURU_RELEASED_DATE").':</span> '.JText::_("GURU_ONE_PER_M").'</li>';
    }
}

if(isset($certificate_term) && $certificate_term != 0){
    if($certificate_term == 1){
        $show_course_cert .= '<p>'.JText::_("GURU_NO_CERT_GIVEN").'</p>';
    }
    elseif($certificate_term == 2){
        $show_course_cert .= '<p>'.JText::_("GURU_MUST_COLMP_ALL_LESS").'</p>';
    }
    elseif($certificate_term == 3){
        //$show_course_cert .= '<p>'.JText::_("GURU_MUST_PASS_FE")." ".$result_maxs."%</p>";

        $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
        $db = JFactory::getDbo();
        $sql = "select `max_score` from #__guru_quiz where `id`= (select `id_final_exam` from #__guru_program where `id`=".intval($course_id).")";
        $db->setQuery($sql);
        $db->execute();
        $finale_max_score = $db->loadColumn();
        $finale_max_score = @$finale_max_score["0"];

        $show_course_cert .= '<p>'.JText::_("GURU_MUST_PASS_FE")." ".$finale_max_score."%</p>";
    }
    elseif($certificate_term == 4){
        $show_course_cert .= '<p>'.JText::_("GURU_MUST_PASS_QAVG")." ".$avg_cert."%</p>";
    }
    elseif($certificate_term == 5){
        $show_course_cert .= '<p>'.JText::_("GURU_CERT_TERM_FALFE").'</p>';
    }
    elseif($certificate_term == 6){
        $show_course_cert .= '<p>'.JText::_("GURU_CERT_TERM_FALPQAVG")." ".$avg_cert."%</p>";
    }
}
?>

<?php
	$session = JFactory::getSession();
	$registry = $session->get('registry');
	$joomlamessage = $registry->get('joomlamessage', NULL);

    if (isset($joomlamessage)) {
?>
        <div class="uk-alert uk-alert-danger" id="joomlamessage">
            <?php echo $joomlamessage; ?>
        </div>
<?php 
    }
	
	$registry->set('joomlamessage', NULL);
?>

<?php
if(isset($course_config->duration) && $course_config->duration == 0){
    echo '<span id="total-duration-1" class="uk-hidden"></span>';
}
?>

<script>
    jQuery(document).ready(function () {
        jQuery('#khDur').text(jQuery('#total-duration-1').text());
    })
</script>

<!-- Course cover area -->
<div uk-grid>
    <div id="sidePuller" class="uk-width-1-1 uk-width-3-4@m">
        <div uk-slideshow="ratio: 21:9" class="uk-border-rounded uk-card uk-card-body uk-card-default uk-card-small uk-padding-remove uk-slideshow">
            <ul class="uk-slideshow-items">
                <li class="uk-border-rounded uk-overflow-hidden">
                    <img src="<?php echo JURI::root().$program->image; ?>" alt="" itemprop="image" uk-cover>
                </li>
            </ul>
        </div>
        <h2 itemprop="headline" class="font"><?php echo $program->name; ?></h2>
        <form name="adminForm" id="adminForm" >

            <?php
            $show_buy_button =  $course_config->course_buy_button;
            $buy_button_location =  $course_config->course_buy_button_location;
            $st_psgpage = json_decode($config->st_psgpage);
            $buy_background = $st_psgpage->course_other_background;
            $course_id = JFactory::getApplication()->input->get("cid", "0");
            $buy_class = $st_psgpage->course_other_button;


            /*
            if($show_buy_button == "0" && ($buy_button_location == "0" || $buy_button_location == "2")){
                $button = createButton($buy_background, $course_id, $buy_class, $program, $programContent);

                if(is_array($button)){
                    echo $button["0"];
                }
                else{
                    echo $button;
                }
            }

            */

            ?>

            <?php
            ob_start();

            createTabs($program, $author, $programContent, $exercise, $requirements, $courses, $config, $course_config);
            $tabsContent = ob_get_contents();

            ob_end_clean();

            // Show tabs content
            echo $tabsContent;
            ?>

            <?php
            /*
            if($show_buy_button == "0" && ($buy_button_location == "1" || $buy_button_location == "2")){
                $button = createButton($buy_background, $course_id, $buy_class, $program, $programContent);

                if(is_array($button)){
                    echo $button["0"];
                }
                else{
                    echo $button;
                }
            }
            */

            $lang = JFactory::getLanguage()->getTag();
            $lang = explode("-", $lang);
            $lang = @$lang["0"];
            ?>

            <input type="hidden" id="course_id" name="course_id" value="<?php echo intval($course_id); ?>" />
            <input type="hidden" name="option" value="com_guru" />
            <input type="hidden" name="controller" value="guruPrograms" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" id="modal-lang" value="<?php echo $lang; ?>" />
        </form>
    </div>
    <div class="uk-width-1-1 uk-width-1-4@m">
        <div uk-sticky="offset: 81; bottom: #sidePuller; cls-active: uk-position-z-index;">
            <aside>

                <?php

                function khz($program, $config){
                    $guruModelguruProgram = new guruModelguruProgram();
                    $k = 0;
                    $prices = $guruModelguruProgram->getPrices($program->id);

                    $chb_free_courses = $program->chb_free_courses;
                    $step_access_courses = $program->step_access_courses;
                    $selected_course = $program->selected_course;

                    if($chb_free_courses == 1 && $step_access_courses != "1" && $step_access_courses != "0"){
                        if($step_access_courses == "2"){
                            echo JText::_("GURU_FREE_GUESTS");
                        }
                    }
                    elseif(isset($prices) && $prices != NULL){
                        if(isset($prices["0"]) && $prices["0"]["name"] != null){
                            ?>
                            <ul class="category-module uk-child-width-1-1 uk-padding-remove uk-grid-small uk-grid-divider" uk-grid>
                                <?php
                                foreach($prices as $key=>$value){
                                    $class = "odd";
                                    if($k%2 != 0){
                                        $class = "even";
                                    }
                                    if(trim($value["name"]) != "" || trim($value["price"]) != ""){
                                        if($value["price"] > 0){
                                            ?>
                                            <li class="uk-text-12 font">
                                                <span class="uk-text-muted"><?php echo $value["name"]; ?> : </span>
                                                <span class="uk-text-black">
                                                    <?php
                                                    $currency = $config->currency;
                                                    $currencypos = $config->currencypos;
                                                    $guruHelper = new guruHelper();

                                                    if($currencypos == 0){
                                                        echo JText::_("GURU_CURRENCY_".$currency)." ".$guruHelper->displayPrice($value["price"]);
                                                    }
                                                    else{
                                                        echo $guruHelper->displayPrice($value["price"])." ".JText::_("GURU_CURRENCY_".$currency);
                                                    }
                                                    ?>
                                                </span>
                                            </li>
                                            <?php
                                        }
                                    }
                                    $k++;
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        else{
                            echo JText::_("GURU_FREE_FOR_MEMEBERS_PRICE");
                        }
                    }
                }

                ?>


                <div class="uk-card uk-card-body uk-card-default uk-border-rounded uk-card-small uk-margin-medium-bottom">
                    <h4 class="<?php echo $course_style->course_name; ?> font">Course Details :</h4>
                    <ul class="category-module uk-child-width-1-1 uk-padding-remove uk-grid-small uk-grid-divider" uk-grid>
                        <?php
                        if ($course_config->show_course_studentamount == "0") {
                            echo '<li class="uk-text-12 font"><span class="uk-text-muted">'.JText::_("GURU_AMOUNT_STUDENTS").' : </span>'.$amount_students.'</li>';
                        }

                        if ($course_config->course_author_name_show == "0") {
                            $list_author_name = array();

                            if (isset($author) && count($author) > 0) {
                                foreach ($author as $key=>$value) {
                                    $list_author_name[] = $value->name;
                                }
                            }

                            if (isset($list_author_name) && count($list_author_name) > 0) {
                                echo '<li class="uk-text-12 font"><span class="uk-text-muted">'.JText::_("GURU_AUTHOR").' : </span>'.implode(", ", $list_author_name).'</li>';
                            }
                        }

                        if($course_config->course_released_date == "0"){
                            echo '<li class="uk-text-12 font"><span class="uk-text-muted">'.JText::_("GURU_RELEASED").' : </span>'.JHTML::date($data, 'j F Y').'</li>';
                        }

                        if($course_config->course_level == "0"){
                            echo '<li class="uk-text-12 font"><span class="uk-text-muted">'.JText::_("GURU_LEVEL").' : </span>'.$program->level."</li>";
                        }

                        if(isset($course_config->duration) && $course_config->duration == 0){
                            echo '<li class="uk-text-12 font"><span class="uk-text-muted">'.JText::_("GURU_DURATION").' : </span> <span id="khDur"></span></li>';
                        }

                        // Show course price
                        echo $show_course_price;

                        // Show course release
                        echo $show_course_release;
                        ?>
                    </ul>
                </div>

                <div class="uk-card uk-card-body uk-card-default uk-border-rounded uk-card-small">
                    <h4 class="<?php echo $course_style->course_name; ?> font">Course Price :</h4>
                    <?php
                    if(!isset($button)){
                        $button = "";
                    }
                    if($course_config->course_tab_price == "0" && !is_array($button)){
                        khz($program, $config);
                    }
                    ?>
                    <div>
                        <?php
                        if ($config->course_certificate == "0") {
                            ?>
                            <i class="uk-icon-file-text"></i>
                            <span class="uk-text-bold"><?php echo JText::_("GURU_CERTIFICATE_COL"); ?>:</span>
                            <div class="uk-clearfix"><?php echo $show_course_cert; ?></div>
                            <?php
                        }
                        ?>

                        <?php
                        $user = JFactory::getUser();
                        $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
                        $need_enroll = false;
                        $need_buy = true;
                        $need_login = false;
                        $not_show_button = false;

                        $sql = "select chb_free_courses, step_access_courses, groups_access, selected_course from #__guru_program where id=".intval($course_id);
                        $db->setQuery($sql);
                        $db->execute();
                        $course_access_details = $db->loadAssocList();

                        if($course_access_details["0"]["chb_free_courses"] == "1"){
                            if($course_access_details["0"]["step_access_courses"] == "1"){
                                // members
                                if($user->id == 0){
                                    // not logged
                                    $need_enroll = false;
                                    $need_buy = false;
                                    $need_login = true;
                                    $not_show_button = false;
                                }
                                else{
                                    $groups_access = $course_access_details["0"]["groups_access"];
                                    $in_groups = $guruModelguruProgram->userInGroups($groups_access);

                                    if(trim($groups_access) == ""){
                                        $need_enroll = true;
                                        $need_buy = false;
                                        $need_login = false;
                                        $not_show_button = false;
                                    }
                                    else{
                                        if($in_groups){
                                            $need_enroll = true;
                                            $need_buy = false;
                                            $need_login = false;
                                            $not_show_button = false;
                                        }
                                    }
                                }
                            }
                            elseif($course_access_details["0"]["step_access_courses"] == "0"){
                                // students
                                if($course_access_details["0"]["selected_course"] == "-1"){
                                    // any course
                                    if($user->id == 0){
                                        // not logged
                                        $need_enroll = false;
                                        $need_buy = false;
                                        $need_login = true;
                                        $not_show_button = false;
                                    }
                                    else{
                                        // user logged
                                        $is_customer = $guruModelguruProgram->isCustomer();
                                        $course_temp = array("id"=>$course_id);
                                        $is_customerfor_course = $guruModelguruProgram->isCustomerForCourse($course_temp);

                                        if($is_customer && !$is_customerfor_course){
                                            $need_enroll = true;
                                            $need_buy = false;
                                            $need_login = false;
                                            $not_show_button = false;
                                        }
                                        elseif($is_customer && $is_customerfor_course){
                                            $need_enroll = false;
                                            $need_buy = true;
                                            $need_login = false;
                                            $not_show_button = false;
                                        }
                                    }
                                }
                                else{
                                    // selected courses
                                    if($user->id == 0){
                                        // not logged
                                        $need_enroll = false;
                                        $need_buy = false;
                                        $need_login = true;
                                        $not_show_button = false;
                                    }
                                    else{
                                        $user_courses = $guruModelguruProgram->getUserCourses();
                                        $selected_course = $course_access_details["0"]["selected_course"];
                                        $selected_course = explode("|", $selected_course);
                                        $selected_course = array_filter($selected_course);

                                        if(isset($user_courses) && count($user_courses) > 0){
                                            $exist = false;
                                            foreach($user_courses as $key=>$value){
                                                if(in_array($key, $selected_course)){
                                                    $exist = true;
                                                    break;
                                                }
                                            }

                                            if($exist){
                                                $need_enroll = true;
                                                $need_buy = false;
                                                $need_login = false;
                                                $not_show_button = false;
                                            }
                                            else{
                                                $need_enroll = false;
                                                $need_buy = true;
                                                $need_login = false;
                                                $not_show_button = false;
                                            }
                                        }
                                        else{
                                            $need_enroll = false;
                                            $need_buy = true;
                                            $need_login = false;
                                            $not_show_button = false;
                                        }
                                    }
                                }
                            }
                            elseif($course_access_details["0"]["step_access_courses"] == "2"){
                                // free for gues
                                if($user->id == 0){
                                    // not logged
                                    $need_enroll = false;
                                    $need_buy = false;
                                    $need_login = false;
                                    $not_show_button = true;
                                }
                                else{
                                    // logged, need enrol
                                    $need_enroll = true;
                                    $need_buy = false;
                                    $need_login = false;
                                    $not_show_button = false;
                                }
                            }
                        }

                        $course_temp = array("id"=>$course_id);
                        $is_customerfor_course = $guruModelguruProgram->isCustomerForCourse($course_temp);

                        if($is_customerfor_course){
                            $need_enroll = false;
                            $need_buy = false;
                            $need_login = false;
                            $not_show_button = true;
                        }
                        ?>

                        <div rrr>
                            <?php
                            $sql = "SELECT chb_free_courses, step_access_courses, selected_course, groups_access FROM `#__guru_program` where id = ".intval($course_id);
                            $db->setQuery($sql);
                            $db->execute();
                            $result= $db->loadAssocList();
                            $chb_free_courses = $result["0"]["chb_free_courses"];
                            $step_access_courses = $result["0"]["step_access_courses"];
                            $selected_course = $result["0"]["selected_course"];
                            $members_groups = $result["0"]["groups_access"];

                            if($chb_free_courses == 1){// free for
                                if($step_access_courses == "0"){// students
                                    if($selected_course == "-1"){// any course
                                        $user_logged = JFactory::getUser();

                                        if(!hasAtLeastOneCourse()){
                                            $sql = "select `price` from #__guru_program_plans where `product_id`=".intval($course_id);
                                            $db->setQuery($sql);
                                            $db->execute();
                                            $prices = $db->loadAssocList();

                                            if(intval($user_logged->id) == 0){
                                                $need_enroll = false;
                                                $need_buy = false;
                                                $need_login = true;
                                            }
                                            else{
                                                $need_enroll = false;
                                                $need_buy = false;
                                                $need_login = false;

                                                if(isset($prices) && count($prices) > 0){
                                                    foreach($prices as $key=>$value_price){
                                                        if(intval($value_price["price"]) != 0){
                                                            $need_buy = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    elseif(!buySelectedCourse($selected_course)){
                                        $user_logged = JFactory::getUser();

                                        if(!hasAtLeastOneCourse()){
                                            $sql = "select `price` from #__guru_program_plans where `product_id`=".intval($course_id);
                                            $db->setQuery($sql);
                                            $db->execute();
                                            $prices = $db->loadAssocList();

                                            if(intval($user_logged->id) == 0){
                                                $need_enroll = false;
                                                $need_buy = false;
                                                $need_login = true;
                                            }
                                            else{
                                                $need_enroll = false;
                                                $need_buy = false;
                                                $need_login = false;

                                                if(isset($prices) && count($prices) > 0){
                                                    foreach($prices as $key=>$value_price){
                                                        if(intval($value_price["price"]) != 0){
                                                            $need_buy = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                elseif($step_access_courses){// members
                                    $sql = "select `price` from #__guru_program_plans where `product_id`=".intval($course_id);
                                    $db->setQuery($sql);
                                    $db->execute();
                                    $prices = $db->loadAssocList();

                                    if(isset($members_groups) && trim($members_groups) != ""){
                                        // selected some groups
                                        $members_groups_array = explode(",", $members_groups);
                                        $user_logged = JFactory::getUser();

                                        if(intval($user_logged->id) != 0){
                                            $intersect_groups = array_intersect($user_logged->groups, $members_groups_array);

                                            if(is_array($intersect_groups) && count($intersect_groups) <= 0){
                                                $need_enroll = false;
                                                $need_buy = false;
                                                $need_login = false;

                                                if(isset($prices) && count($prices) > 0){
                                                    foreach($prices as $key=>$value_price){
                                                        if(intval($value_price["price"]) != 0){
                                                            $need_buy = true;
                                                        }
                                                    }
                                                }

                                                if(buySelectedCourse($course_id."|")){
                                                    $need_buy = false;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            ?>

                            <?php
                            if($need_enroll){
                                ?>
                                <a class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top" href="#" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id); ?>'; return false;">
                                    <i class="uk-icon-check"></i>
                                    <?php echo JText::_("GURU_TAKE_COURSE"); ?>
                                </a>
                                <?php
                            }
                            elseif($need_buy){
                                ?>
                                <a class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top" href="<?php echo JURI::root()."index.php?option=com_guru&controller=guruPrograms&task=buy_action&course_id=".$course_id; ?>">
                                    <i class="uk-icon-check"></i>
                                    <?php echo JText::_("GURU_TAKE_COURSE"); ?>
                                </a>
                                <?php
                            }
                            elseif($need_login){
                                $course_lessons = $guruModelguruProgram->find_program_tasks(intval($course_id));
                                $lesson_id = 0;

                                if(isset($course_lessons) && count($course_lessons) > 0){
                                    $lesson_id = $course_lessons["0"]->id;
                                }
                                ?>
                                <a class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top" href="#" onclick="openMyModal('0', '0', '<?php echo JURI::root().'index.php?option=com_guru&view=guruLogin&tmpl=component&returnpage=open_lesson&lesson_id='.intval($lesson_id); ?>'); return false;">
                                    <i class="uk-icon-check"></i>
                                    <?php echo JText::_("GURU_TAKE_COURSE"); ?>
                                </a>
                                <?php
                            }
                            elseif($not_show_button){
                                // not show that button
                            }
                            ?>
                        </div>
                    </div>
                </div>



            </aside>
        </div>
    </div>
</div>