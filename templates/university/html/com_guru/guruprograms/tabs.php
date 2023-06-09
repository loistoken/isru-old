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
include(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_guru'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'gurutask.php');
require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

require_once (JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
$guruHelper = new guruHelper();

$document = JFactory::getDocument();
$program= $this->program;
$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
$guruHelper = new guruHelper();
$document->addScriptDeclaration('
	document.onreadystatechange = function(){
		initPhoneTabs();
	}
');

//$document->addScriptDeclaration('juri_root = "' . JURI::root() . '";');
//$document->addScript('components/com_guru/js/guru.js');

?>
<input type="hidden" id="available_language" value="<?php echo JText::_("GURU_AVAILABLE_ON"); ?>" />

<script>

function reloadCourseLessonsBar(){
	reloadCourseLessonsBarJS();
}

function changeGuruTab(tab){
	tabs = new Array("tab1", "tab2", "tab3", "tab4", "tab5", "tab6");
	for(i=0; i<tabs.length; i++){
		if(tab == tabs[i]){
			if(eval(document.getElementById(tabs[i]))){
				var temp = "li-"+tabs[i];
				document.getElementById(tabs[i]).style.display = "block";
				if(eval(document.getElementById(temp))){
					document.getElementById(temp).classList.add("uk-active");
				}
			}
		}
		else{
			if(eval(document.getElementById(tabs[i]))){
				var temp = "li-"+tabs[i];
				document.getElementById(tabs[i]).style.display = "none";
				if(eval(document.getElementById(temp))){
					document.getElementById(temp).classList.remove("uk-active");
				}
			}
		}
	}
}

	var accordionItems = new Array();

    function initPhoneTabs() {
      // Grab the accordion items from the page
      var divs = document.getElementsByTagName( 'div' );
      for ( var i = 0; i < divs.length; i++ ) {
        if ( divs[i].className == 'accordionItem' ) accordionItems.push( divs[i] );
      }

      // Assign onclick events to the accordion item headings
      for ( var i = 0; i < accordionItems.length; i++ ) {
        var h3 = getFirstChildWithTagName( accordionItems[i], 'H3' );
        h3.onclick = toggleItem;
      }

      // Hide all accordion item bodies except the first
      for ( var i = 1; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hideTabs';
      }
    }

    function toggleItem() {
      var itemClass = this.parentNode.className;

      // Hide all items
      for ( var i = 0; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hideTabs';
      }

      // Show this item if it was previously hidden
      if ( itemClass == 'accordionItem hideTabs' ) {
        this.parentNode.className = 'accordionItem';
      }
    }

    function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
      }
    }

</script>

<?php
if($program->metatitle =="" || $program->metadesc =="" ){
    $document = JFactory::getDocument();
    $document->setTitle($program->name);
    $document->setMetaData('keywords', $program->name);
    //$document->setMetaData('description', strip_tags($program->description));
}


$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
?>
<?php
function get_time_difference($start, $end){
    $uts['start'] = $start;
    $uts['end'] = $end;
    if( $uts['start'] !== -1 && $uts['end'] !== -1){
        if($uts['end'] >= $uts['start']){
            $diff = $uts['end'] - $uts['start'];
            if($days=intval((floor($diff/86400)))){
                $diff = $diff % 86400;
            }
            if($hours=intval((floor($diff/3600)))){
                $diff = $diff % 3600;
            }
            if($minutes=intval((floor($diff/60)))){
                $diff = $diff % 60;
            }
            $diff = intval($diff);
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff));
        }
        else{
            return false;
        }
    }
    return false;
}
function isCustomer(){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
	$course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $sql = "select count(*) from #__guru_buy_courses bc, #__guru_order o, #__guru_customer c where bc.userid=".intval($user_id)." and o.id = bc.order_id and bc.course_id=".intval($course_id)." and o.userid=".intval($user_id)." and c.id=".intval($user_id);
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadColumn();
    $result = @$result["0"];
	
    if($result > 0){
        $sql = "select `block` from #__users where `id`=".intval($user_id);
        $db->setQuery($sql);
        $db->execute();
        $result = $db->loadColumn();
        $result = @$result["0"];
        if($result == 0){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function isActiveOrder(){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
	$course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $sql = "select count(*) from #__guru_buy_courses bc, #__guru_order o, #__guru_customer c where bc.userid=".intval($user_id)." and o.id = bc.order_id and bc.course_id=".intval($course_id)." and o.userid=".intval($user_id)." and c.id=".intval($user_id)." and o.`status`='Paid'";
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadColumn();
    $result = @$result["0"];
	
    if($result > 0){
        $sql = "select `block` from #__users where `id`=".intval($user_id);
        $db->setQuery($sql);
        $db->execute();
        $result = $db->loadColumn();
        $result = @$result["0"];
        if($result == 0){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function inCustomerTable(){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
    $sql = "select count(*) from  #__guru_customer where id=".intval($user_id);
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadColumn();
    $result = @$result["0"];
    
    if($result > 0){
        $sql = "select `block` from #__users where `id`=".intval($user_id);
        $db->setQuery($sql);
        $db->execute();
        $result = $db->loadColumn();
        $result = @$result["0"];
        
        if($result == 0){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}
function hasAtLeastOneCourse(){
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $user_id = $user->id;
    $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $sql = "SELECT count(*) FROM #__guru_buy_courses where `userid`=".intval($user_id)." and course_id <>".$course_id;
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadResult();
    if($result > 0){
        return true;
    }
    else{
        return false;
    }
}

function createButton($buy_background, $course_id, $buy_class, $program, $program_content){
    $course_id = intval($course_id);
	$return = "";
	$guruModelguruProgram = new guruModelguruProgram();
	$is_expired_true ="";
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
    $itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
    $expired = false;
    $sql = "select `expired_date` from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $expired_date_string = $db->loadColumn();
    $expired_date_string = @$expired_date_string["0"];
    
	$red_bar = false;
    $not_show = false;
    $current_date_string = "";
    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= now() or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadColumn();
	
    $result = @$result["0"];
	$span10 = "span10";
	
	$author = $program->author;
	$author = explode("|", $author);
	$author = array_filter($author);
	$course_authors = $author;
	
	if((isset($expired_date_string) && $expired_date_string != "0000-00-00 00:00:00") || (!isset($result) || intval($result) == 0)){
        $expired_date_int = strtotime($expired_date_string);
        $jnow = new JDate('now');
        $current_date_string = $jnow->toSQL();
        $current_date_int = strtotime($current_date_string);
        $renew = "false";
        if($current_date_int < $expired_date_int){
            $renew = "true";
        }
        $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($user_id)." and o.status='Paid'";
        $db->setQuery($sql);
        $db->execute();
        $my_courses = $db->loadColumn();
        
		if(in_array($course_id, $my_courses) && $renew){ // I bought this course
           	@$difference_int = get_time_difference($current_date_int, $expired_date_int);
            $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
            
			if($difference_int["days"] == 0){
                if($difference_int["hours"] == 0){
                    if($difference_int["minutes"] == 0){
                        $difference = "0";
                    }
                    else{
                        $difference = $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                    }
                }
                else{
                    $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS");
                }
            }
			
            if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                $difference_int = "1"; //default for unlimited
            }
            
            if($difference_int !== FALSE){// is not expired
                $not_show = true;
            }
            else{
                $return .= JURI::root()."index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".intval($itemid);
                $expired = true;
            }
        }
        else{
            $return .= JURI::root()."index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".intval($itemid);
        }
		
		$return = '<a href="'.$return.'"><i class="uk-icon-bullhorn"></i> '.JText::_("GURU_ACCESS_BUT_BUTTON").'</a>';
    }
    else{//not show the button
        $not_show = true;
    }
	
    $sql = "SELECT chb_free_courses, step_access_courses, selected_course, groups_access FROM `#__guru_program` where id = ".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $result= $db->loadAssocList();
    $chb_free_courses = $result["0"]["chb_free_courses"];
    $step_access_courses = $result["0"]["step_access_courses"];
    $selected_course = $result["0"]["selected_course"];
	$members_groups = $result["0"]["groups_access"];
    
	if($chb_free_courses == 1){// free for
		if($step_access_courses){// members
			$mandatory_login = false;
			$can_not_buy = false;
			
			$sql = "select `price` from #__guru_program_plans where `product_id`=".intval($course_id);
			$db->setQuery($sql);
			$db->execute();
			$prices = $db->loadAssocList();
			
			if(isset($members_groups) && trim($members_groups) != ""){
				// selected some groups
				$members_groups_array = explode(",", $members_groups);
				$user_logged = JFactory::getUser();
				
				if(intval($user_logged->id) == 0){
					$mandatory_login = true;
					$can_not_buy = true;
					
					if(isset($prices) && count($prices) > 0){
						foreach($prices as $key=>$value_price){
							if(intval($value_price["price"]) != 0){
								$can_not_buy = false;
							}
						}
					}
				}
				else{
					$intersect_groups = array_intersect($user_logged->groups, $members_groups_array);
					if(is_array($intersect_groups) && count($intersect_groups) <= 0){
						$mandatory_login = false;
						$can_not_buy = true;
						
						if(isset($prices) && count($prices) > 0){
							foreach($prices as $key=>$value_price){
								if(intval($value_price["price"]) != 0){
									$can_not_buy = false;
								}
							}
						}
					}
				}
			}
			else{
				// all groups
				$user_logged = JFactory::getUser();

				if(intval($user_logged->id) == 0){
					$mandatory_login = true;
					$can_not_buy = true;
					
					if(isset($prices) && count($prices) > 0){
						foreach($prices as $key=>$value_price){
							if(intval($value_price["price"]) != 0){
								$can_not_buy = false;
							}
						}
					}
				}
			}

			if($mandatory_login){
				$return = '<div class="uk-grid">
								<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1 uk-text-left padding-right-10">
									'.JText::_("GURU_FREE_FOR_MEMEBERS_PRICE").'
								</div>
								<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
									<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
								</div>
							</div>';
			}
			else{
				if($can_not_buy){
					$return = "";
				}
			}
		}
	}
	
	if($chb_free_courses == 1){
        $sql = "SELECT  count(*) FROM `#__guru_buy_courses` where `order_id` >='0' and `userid`=".intval($user_id)." and course_id=".intval($course_id);
        $db->setQuery($sql);
        $db->execute();
        $result = $db->loadColumn();
        $result = @$result["0"];
        
        if($result > 0 ){
            $sql = "select `block` from #__users where `id`=".intval($user_id);
            $db->setQuery($sql);
            $db->execute();
            $result = $db->loadColumn();
            $result = @$result["0"];
			
            if($result != 0 ||  $result == NULL ){
                $not_show = false;
            }
            else{
                $not_show = true;
            }
        }
        else{
            $not_show = false;
        }
    }
    
    if(@$difference_int == FALSE && @$expired_date_string != "0000-00-00 00:00:00"){
        $is_expired_true = true;
    }
    else{
        $is_expired_true = false;
    }
	
    if($not_show && ($chb_free_courses == 0 || ($chb_free_courses == 1 && $step_access_courses == 1) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course != -1 && isCustomer()) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course == -1 && hasAtLeastOneCourse()) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course != -1 && buySelectedCourse($selected_course)))){
		
        $return = array("0"=>"");
        
        if(isset($program_content) && count($program_content) > 0){
            $module_id = $program_content["0"]["id"];
            $lessons = $guruModelguruProgram->getSubCategory($module_id);
            $lesson_name = "";
            
			if(isset($lessons) && count($lessons) > 0){
                $lesson_name = $lessons["0"]["name"];
            }
			
			if($is_expired_true == false){
                $return["0"] = '<div><div><p>'.JText::_("GURU_WELCOME_TO").' "'.$program->name.'" '.JText::_("GURU_COURSE_FROM_PHRASE").'! '.JText::_("GURU_PLEASE_GET_STARTED").' "'.$lesson_name.'" '.JText::_("GURU_BELOW").'</p></div></div>';
            }
            else{
                $return["0"] = '<div><div>'.JText::_("GURU_EXPIRED_TEXT1")." ".'<a href="'.JRoute::_('index.php?option=com_guru&controller=guruOrders&task=renew&course_id='.$course_id).'">'.JText::_("GURU_EXPIRED_TEXT2").'</a>'." ".JText::_("GURU_EXPIRED_TEXT3").'</div></div>';
            }
        }
    }
    else{
        if($chb_free_courses == 1){//checked
            if($step_access_courses == 0 && !$expired){// Students
                if($selected_course == '-1'){// any course
                    if($user_id == 0){//not logged
                        $red_bar = true;
						
						$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
						
						$helper = new guruHelper();
						$itemid_seo = $helper->getSeoItemid();
						$itemid_seo = @$itemid_seo["gurulogin"];
						
						if(intval($itemid_seo) > 0){
							$itemid = intval($itemid_seo);
						}
						
						$return = ' <div class="uk-grid">
										<div class="uk-width-large-1-1 uk-width-medium-1-1 uk-width-small-1-1">
											<p>'.JText::_("GURU_FREE_ALL_STUDENTS_LOG_HERE").' <a class="inactive-lesson" href="#" onclick="openMyModal(\'0\', \'0\', \''.JURI::root().'index.php?option=com_guru&view=guruLogin&tmpl=component&Itemid='.intval($itemid).'&returnpage=open_lesson&lesson_id=0\'); return false;">'.JText::_("GURU_LOGIN_HERE").'</a></p>
										</div>
									</div>';
                    }
                    else{
                        if(hasAtLeastOneCourse()){
							$red_bar = true;
							$return = ' <div class="uk-grid">
											<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1">
												<p>'.JText::_("GURU_FREE_ALL_STUDENTS_LOGGIN").'</p>
											</div>
											<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
												<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
											</div>
										</div>';
                        }
						else{
							$red_bar = true;
							$return = ' <div class="uk-grid">
											<div class="uk-width-large-1-1 uk-width-medium-1-1 uk-width-small-1-1">
												<p>'.JText::_("GURU_FREE_FOR_OTHER_COURSES1").' <a href="'.JRoute::_("index.php?option=com_guru&view=gurupcategs").'">'.JText::_("GURU_OTHER_COURSES").'</a> '.JText::_("GURU_FREE_FOR_OTHER_COURSES2").' </p>
											</div>
										</div>';
						}
                    }
                }
                else{// selected courses
					if($user_id == 0){// not logged
                        $selected_course_final = explode('|', $selected_course);
                        foreach($selected_course_final as $key=>$value){
                            if(trim($value) == ""){
                                unset($selected_course_final[$key]);
                            }
                        }
                        $db = JFactory::getDBO();
                        $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                        $db->setQuery($sql);
                        $db->execute();
                        $result = $db->loadAssocList();
                        $all_title = array();
                        $itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
                        if(isset($result) && count($result) > 0){
                            foreach($result as $key=>$course){
                                $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".intval($itemid)).'">'.$course["name"].'</a>';
                            }
                        }
                        $all_title = implode(", ", $all_title);
                        $not_show = false;
                        $red_bar = true;
						$return = ' <div class="uk-grid">
                                        <div class="uk-width-large-1-1 uk-width-medium-1-1 uk-width-small-1-1 uk-text-left">
											'.JText::_("GURU_FREE_STUDENTS_SOME_COURSES").'<br/>'.$all_title.'
                                        </div>
									</div>
									
									<div class="uk-grid margin-2">
                                        <div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1  padding-right-10">
											'.JText::_("GURU_STUDENT_ANY_OF_COURSE").'
										</div>
										
										<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
											<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
										</div>
									</div>	
									
									<div class="uk-grid margin-2">
										<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1  padding-right-10">
											'.JText::_("GURU_NOT_A_STUDENT").'
										</div>
										
										<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1  padding-none">
											<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JURI::root()."index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".intval($itemid).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />
										</div>
									</div>';
                    }
                    else{
                        if(buySelectedCourse($selected_course)){
                            $selected_course_final = explode('|', $selected_course);
                            
							foreach($selected_course_final as $key=>$value){
                                if(trim($value) == ""){
									unset($selected_course_final[$key]);
                                }
                            }
							
                            $db = JFactory::getDBO();
                            $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                            $db->setQuery($sql);
                            $db->execute();
                            $result = $db->loadAssocList();
                            $all_title = array();
                            $itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
                            if(isset($result) && count($result) > 0){
                                foreach($result as $key=>$course){
                                    $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".intval($itemid)).'">'.$course["name"].'</a>';
                                }
                            }
                            $all_title = implode(", ", $all_title);
							$red_bar = true;
							
                            $return = ' <div class="uk-grid">
											<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1 uk-text-left padding-right-10">
												'.JText::_("GURU_STUDENT_SOME_COURSE").'
												'.$all_title.'
											</div>
											<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
												<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
											</div>
										</div>';
                        }
						else{
							$selected_course_final = explode('|', $selected_course);
                            
							foreach($selected_course_final as $key=>$value){
                                if(trim($value) == ""){
									unset($selected_course_final[$key]);
                                }
                            }
							
                            $db = JFactory::getDBO();
                            $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                            $db->setQuery($sql);
                            $db->execute();
                            $result = $db->loadAssocList();
                            $all_title = array();
                            $itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
                            if(isset($result) && count($result) > 0){
                                foreach($result as $key=>$course){
                                    $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".intval($itemid)).'">'.$course["name"].'</a>';
                                }
                            }
                            $all_title = implode(", ", $all_title);
							
							$red_bar = true;
							$return = JText::_("GURU_FREE_STUDENTS_SOME_COURSES")." ".$all_title;
						}
                    }
                }
            }
            elseif($step_access_courses == 1){// Members
                if(isset($program->groups_access) && trim($program->groups_access) != ""){
					$user = JFactory::getUser();
					$user_groups = JAccess::getGroupsByUser($user->id, $recursive = true);
					$course_access = explode(",", $program->groups_access);
					$access = FALSE;
					
					if(is_array($course_access)&& count($course_access) > 0){
						foreach($course_access as $key=>$value){
							if(in_array($value, $user_groups)){
								$access = TRUE;
								break;
							}
						}
					}
					
					if($access){
						$red_bar = true;
						$return = '	<div class="uk-grid">
										<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1  padding-right-10">
											'.JText::_("GURU_FREE_MEMBERS").'
										</div>
										<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
											<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
										</div>
									</div>';
					}
				}
				else{
					$red_bar = true;
					$return = ' <div class="uk-grid">
									<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1 uk-text-left padding-right-10">
										'.JText::_("GURU_FREE_FOR_MEMEBERS_PRICE").'
									</div>
									<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
										<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
									</div>
								</div>';
				}
            }
            elseif($step_access_courses == 2){// Guest
                $return = ' <div class="uk-grid">
                                <div class="uk-width-1-1">
									'.JText::_("GURU_FREE_GUEST").'
                                </div>
                            </div>';
            }
        }
    }
	
	$bar_class = "uk-alert";

	if($red_bar){
		$bar_class = "uk-alert uk-alert-danger";
	}
	
	$access_bar = '<div class="'.$bar_class.' uk-hidden-small hidden-phone uk-visible@m course-access-bar">';
	
	if(is_array($return)){
		$access_bar .= $return["0"];
	}
	else{
		$access_bar .= $return;
	}
	
	$access_bar .= '</div>';
	
    return $access_bar;
}

function buySelectedCourse($selected_course){
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $user_id = $user->id;
    $sql = "SELECT distinct(bc.`course_id`) FROM #__guru_buy_courses bc, #__guru_order o where bc.`userid`=".intval($user_id)." and o.`id`=bc.`order_id` and o.`status`='Paid'";
    $db->setQuery($sql);
    $db->execute();
    $all_courses = $db->loadColumn();
    $selected_course_final = explode('|', $selected_course);
    @$intersect = array_intersect($selected_course_final, @$all_courses);
    if(count($intersect)>0){
        return true;
    }
    else{
        return false;
    }
}

function chekIfNotLog($lesson){
    $db = JFactory::getDBO();
    $lesson_id = $lesson["id"];
    $user = JFactory::getUser();
    $user_id = $user->id;
    $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $sql = "select step_access from #__guru_task where id=".intval($lesson_id);
    $db->setQuery($sql);
    $db->execute();
    $lesson_acces = intval($db->loadResult());
    if($user_id == 0 && $lesson_acces == 2){
        return true;
    }
    elseif($user_id == 0 && $lesson_acces != 2){
        return false;
    }
    elseif($user_id == 0 && $lesson_acces == 0){
        return false;
    }
    elseif($user_id == 0 && $lesson_acces == 1){
        return false;
    }
    elseif($user_id != 0 && $lesson_acces == 1){
        return true;
    }
    elseif($user_id != 0 && $lesson_acces == 2){
        return true;
    }
    elseif($user_id != 0 && $lesson_acces == 0){
        $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id);
        $db->setQuery($sql);
        $db->execute();
        $result = $db->loadColumn();
        $result = @$result["0"];
        
        if($result > 0){
            $expired = false;
            $sql = "select expired_date from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
            $db->setQuery($sql);
            $db->execute();
            $expired_date_string = $db->loadResult();
            $current_date_string = "";
            $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= now() or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
            $db->setQuery($sql);
            $db->execute();
            $result = $db->loadColumn();
            $result = @$result["0"];
            
            if(($expired_date_string != "0000-00-00 00:00:00") || (isset($result) || intval($result) != 0)){
                $expired_date_int = strtotime($expired_date_string);
                $jnow = new JDate('now');
                $current_date_string = $jnow->toSQL();
                $current_date_int = strtotime($current_date_string);

                $renew = "false";
                if($current_date_int < $expired_date_int){
                    $renew = "true";
                }
                $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($user_id)." and o.status='Paid'";
                $db->setQuery($sql);
                $db->execute();
                $my_courses = $db->loadColumn();
				if(!is_array($my_courses)){
					$my_courses = array($my_courses);
				}
				
                if(in_array($course_id, $my_courses)){ // I bought this course
                    $difference_int = get_time_difference($current_date_int, $expired_date_int);
                    $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
                    if($difference_int["days"] == 0){
                        if($difference_int["hours"] == 0){
                            if($difference_int["minutes"] == 0){
                                $difference = "0";
                            }
                            else{
                                $difference = $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                            }
                        }
                        else{
                            $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS");
                        }
                    }
                    if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                        $difference_int = "1"; //default for unlimited
                    }
                    if($difference_int !== FALSE){// is not expired
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id)." and order_id=0";
                    $db->setQuery($sql);
                    $db->execute();
                    $result = $db->loadResult();
                    if($result > 0){
                        return true;
                    }
                }
            }
        }
        else{
            return false;
        }
    }
    $jnow = new JDate('now');
    $current_date_string = $jnow->toSQL();
    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= '".$current_date_string."' or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadResult();
    if(!isset($result) || intval($result) == 0){
        return false;
    }
    return true;
}
function accessToLesson($lesson){
	$lesson_id = $lesson["id"];
    $db = JFactory::getDBO();
    $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    if($lesson["chb_free_courses"] != 1){// not checked
        return chekIfNotLog($lesson);
    }
    else{
        $user = JFactory::getUser();
        if($user->id != 0){
            $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user->id)." and `course_id`=".intval($course_id);
            $db->setQuery($sql);
            $db->execute();
            $result = $db->loadResult();
            if($result == 0){// not bought course
                switch($lesson["step_access_courses"]){
                    case "0":{ //for students, if that user has at least one course
                    $user_id = $user->id;
                    $sql = "select distinct(`course_id`) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id);
                    $db->setQuery($sql);
                    $db->execute();
                    $all_student_courses = $db->loadResultArray();
                    if(isset($all_student_courses) && count($all_student_courses) > 0){
                        $sql = "select `selected_course` from #__guru_program where id=".intval($course_id);
                        $db->setQuery($sql);
                        $db->execute();
                        $selected_courses = $db->loadResult();
                        if(trim($selected_courses) != ""){
                            if(trim($selected_courses) == "-1"){// for any course bought
                                return true;
                            }
                            else{//only for selected courses
                                $ok = true;
                                $selected_courses = explode("|", trim($selected_courses));
                                foreach($selected_courses as $key => $select_course){
                                    if(trim($select_course) != "" && !in_array(trim($select_course), $all_student_courses)){
                                        $ok = false;
                                        break;
                                    }
                                }
                                return $ok;
                            }
                        }
                    }
                    else{
                        return false;
                    }
                    break;
                    }
                    case "1" : {// for members access
                    return true;
                    break;
                    }
                    case "2" : {// for guest access
                    return true;
                    break;
                    }
                }
            }
            else{// bought course
                return true;
            }
        }//log-in
        else{//log-out
            if($lesson["step_access_courses"] == 2){// guest access
                return true;
            }
            else{
                return chekIfNotLog($lesson);
            }
        }
    }
}
function getAction(){
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $user_id = $user->id;
    $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $jnow = new JDate('now');
    $current_date_string = $jnow->toSQL();
    $sql = "select count(*) from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadResult();
    if($result == 0){
        return false;
    }
    return true;
}

function tab1($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config, $device){
	$prev_id = 0;
	$diff_start = "";
	$diff_date = "";
	$start_date = "";
	$itemid = "";
	$action ="";
	$lesson_width = "";
	$lesson_height = "";
    $st_psgpage = json_decode($config->st_psgpage);
    $psgpage = json_decode($config->psgpage);
    $course_level = $psgpage->course_level;
    $buy_class = $st_psgpage->course_other_button;
    $buy_background = $st_psgpage->course_other_background;
    $my = JFactory::getUser();
    $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $show_buy_button =  $course_config->course_buy_button;
    $buy_button_location =  $course_config->course_buy_button_location;
    $user_id = $my->id;
    @$user->id = $my->id;
    $lesson_size = $config->lesson_window_size;
    $lesson_size = explode("x", $lesson_size);
    $lesson_height = $lesson_size["0"];
    $lesson_width = $lesson_size["1"];
    $style_grayout = "color:#999999;";
    $db = JFactory::getDBO();
    $sql = "select name, alias from #__guru_program where id=".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $result = $db->loadAssocList();
    $alias = $result["0"]["alias"] == "" ? JFilterOutput::stringURLSafe($result["0"]["name"]) : $result["0"]["alias"];
    $sql = "SELECT  count(*) FROM `#__guru_buy_courses` where `order_id` >='0' and `userid`=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $result= $db->loadResult();
	
    if($result > 0){
        $not_show = true;
    }
    else{
        $not_show = false;
    }
	
	$sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM `#__guru_program` where id = ".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $result= $db->loadAssocList();
    $chb_free_courses = $result["0"]["chb_free_courses"];
    $step_access_courses = $result["0"]["step_access_courses"];
    $selected_course = $result["0"]["selected_course"];
    
	$hascourse = false;
	if(buySelectedCourse($selected_course)){
        $hascourse = true;
    }
	
    $coursetype_details = guruModelguruProgram::getCourseTypeDetails($course_id);
    
	if($course_level==1){
        $display_levelimg = "none";
    }
    else{
        $display_levelimg = "inherit-inline";
    }
	
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	
	$authors = $program->author;
	$authors = explode("|", $authors);
	$authors = array_filter($authors);
	
	$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
	
    ?>
    
<div>

    <!-- start main div-->
    <div class="table-of-contents" uk-accordion="multiple: true">
        <?php
        // start calculation for one lesson per (option in admin)
        if($user_id > 0){
            $db = JFactory::getDBO();
            $sql = "select DATE_FORMAT(buy_date,'%Y-%m-%d %H:%i:%s') from #__guru_buy_courses where course_id=".intval($course_id)." and userid =".$user_id;
            $db->setQuery($sql);
            $db->execute();
            $date_enrolled = $db->loadResult();
            $date_enrolled = strtotime($date_enrolled);
        }
		// start changes for lessons per release
        $lessons_per_release = $program->lessons_per_release;

        if(isset($date_enrolled) && $date_enrolled !== FALSE && !is_null($date_enrolled)){
			$start_relaese_date1 = $coursetype_details[0]["start_release"];
            $start_relaese_date = strtotime($start_relaese_date1);
            $start_date = $date_enrolled;
            
            //$jnow = new JDate('now');
            //$date9 = $jnow->toSQL();

            $timezone = new DateTimeZone( JFactory::getConfig()->get('offset') );
            $jnow = new JDate('now');
            $jnow->setTimezone($timezone);
            $date9 = $jnow->toSQL();

            $date_9 = date("Y-m-d H:i:s",strtotime($date9));
            $date9 = strtotime($date9);
			
            $interval = abs($date9 - $start_date);
			
			$dif_hours = floor($interval/(60*60));
            $dif_days = floor($interval/(60*60*24));
            $dif_week = floor($interval/(60*60*24*7));
            $dif_month = floor($interval/(60*60*24*30));

		    if($coursetype_details[0]["course_type"] == 1){
                if($coursetype_details[0]["lesson_release"] == 1){
                    $diff_start = $dif_days + 1;
                    $diff_date = $dif_days + 1;
                }
                elseif($coursetype_details[0]["lesson_release"] == 2){
                    $diff_start = $dif_week + 1;
                    $diff_date = $dif_week + 1;
                }
                elseif($coursetype_details[0]["lesson_release"] == 3){
                    $diff_start = $dif_month + 1;
                    $diff_date = $dif_month + 1;
                }
                elseif($coursetype_details[0]["lesson_release"] == 4){
                   $diff_start = $dif_hours + $coursetype_details["0"]["after_hours"];
                   $diff_date = $dif_hours + $coursetype_details["0"]["after_hours"];
                }
                elseif($coursetype_details[0]["lesson_release"] == 5){
                    $diff_start = 1;
                    $diff_date = 1;
                }
            }
        }

        $guruModelguruTask = new guruModelguruTask();
        $step_less = @$diff_start;
        $nr_lesson = 1;

		$guruModelguruProgram = new guruModelguruProgram();
		
		$total_hours = 0;
		$total_minutes = 0;
		$total_seconds = 0;
		$lesson_details_for_quiz = array();
		
        foreach($program_content as $key=>$module){
            $lessons = $guruModelguruProgram->getSubCategory($module['id']);
        ?>

        	<div class="guru-tabs chapter_wrap t_row uk-open">

                    	<!-- start module -->
                        <a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black"><?php echo $module['title']; ?></a>
                        <!-- stop module -->

                        <?php
                        	// start lessons
							if(isset($lessons) && count($lessons) > 0){
						?>
                        		<div class="uk-accordion-content">
                                	<div id='td_<?php echo $device."_".$module['id']; ?>'>
                                    	<div id='table_<?php echo $device."_".$module['id'];?>'>


                                        	<table class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables uk-margin-remove">
                                        	<thead>
                                        	<tr>
                                        	<th class="uk-text-left font uk-table-expand"><?php echo JTEXT::_('LESSON'); ?></th>
                                        	<th class="uk-text-center font uk-width-1-6"><?php echo JTEXT::_('STATUS'); ?></th>
                                        	<th class="uk-text-center font uk-width-1-6"><?php echo JTEXT::_('VIEWED'); ?></th>
                                        	<th class="uk-text-center font uk-width-1-6"><?php echo JTEXT::_('DURATION'); ?></th>
                                        	<th class="uk-text-center font uk-width-1-6"><?php echo JTEXT::_('LEVEL'); ?></th>
</tr>
</thead>
												<?php
													$model = new guruModelguruProgram();
													
													foreach($lessons as $poz=>$lesson){
														$nr_columns = 2;
														
														if($course_level == 0){
															// show level icon
															$nr_columns = 3;
														}
														
														$lesson = $model->checkLessonQuiz($lesson, $program);
														$lesson_details_for_quiz[] = $lesson;
														
                                                        $lesson = $model->getLessonDetails($program, $author, $lesson, $diff_date, $diff_start, $step_less, $start_date, $config, $lesson_details_for_quiz, $poz, $nr_lesson, $lessons_per_release);
        // end changes for lessons per release
                                                        $nr_lesson ++;
														
                                                        if(isset($lesson["not_show_lesson"]) && trim($lesson["not_show_lesson"]) == "1"){
															continue;
														}
														
														$minutes = 0;
														$seconds = 0;
														
														if(isset($lesson['duration']) && trim($lesson['duration']) != ""){
															$temp_duration = explode("x", trim($lesson['duration']));
															@$total_minutes += $temp_duration["0"];
															@$total_seconds += $temp_duration["1"];
															
															$minutes = $temp_duration["0"];
															$seconds = $temp_duration["1"];
														}
														
														$imgLevel = "";
														
														switch($lesson['difficultylevel']){
															case "easy":
																$imgLevel = "beginner_level.png";
																break;
															case "medium":
																$imgLevel = "intermediate_level.png";
																break;
															case "hard":
																$imgLevel = "advanced_level.png";
																break;
														}
														
														$link = "";
														$onclick = "";
														$lesson_span = "span9";
														$available_date = "";
														$style = "";
														$inactive_lesson_class = "";
														
														if($lesson["can_open_lesson"] == "1"){ // access to this lesson
															if($config->open_target == 0 && FALSE){ // same window
                                                                $link = "index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$module["id"]."-".$module["alias"]."&cid=".$lesson['id']."-".$lesson["alias"]."&Itemid=".intval($itemid);
																$onclick = "javascript:setViewed('viewed-".$lesson['id']."', '".JURI::root()."components/com_guru/images/icons/viewed.gif')";

                                                                $lang = JFactory::getApplication()->input->get("lang", "", "raw");
                                                                $lang_url = "";

                                                                if(isset($lang) && trim($lang) != ""){
                                                                    $lang_url = "&lang=".trim($lang);
                                                                }

                                                                $link .= $lang_url;
															}
															elseif($config->open_target == 1 || TRUE){ // modal window
                                                                $link = "#";

                                                                $lang = JFactory::getApplication()->input->get("lang", "", "raw");
                                                                $lang_url = "";

                                                                if(isset($lang) && trim($lang) != ""){
                                                                    $lang = explode("-", $lang);
                                                                    $lang_url = "&lang=".trim($lang["0"]);
                                                                }

																$onclick = "openMyModalNew('0', '0', '".JURI::root()."index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$module["id"]."-".$module["alias"]."&cid=".$lesson['id']."-".$lesson["alias"]."&tmpl=component&Itemid=".intval($itemid).$lang_url. "', { uri: '" . JURI::root() . "', pid: '" . $program->id . "', cid: '" . $lesson['id'] . "', item_id: '" . intval($itemid) . "', current_lesson: '" . $lesson['id'] . "' }); return false; javascript:setViewed('viewed-".$lesson['id']."', '".JURI::root()."components/com_guru/images/icons/viewed.gif"."')";
															}
															
															if(isset($course_config->duration) && $course_config->duration == 0){
																$nr_columns ++;
															}
															
															if(isset($course_config->quiz_status) && $course_config->quiz_status == 0){
																$nr_columns ++;
															}
															
															if(isset($lesson["available_div"]) && trim($lesson["available_div"]) != ""){
																// add AVAILABLE language var
																$available_date = trim($lesson["available_div"]);
																$nr_columns ++;
															}
														}
														elseif($lesson["can_open_lesson"] == "0"){ // no access to this lesson
															$inactive_lesson_class = "inactive-lesson";
															
                                                            if(isset($lesson["finish_required_courses"]) && $lesson["finish_required_courses"] == 1){
																// start open alert modal with required courses message
																$link = "#";
																$onclick = "openMyModal('0','0','".JURI::root()."index.php?option=com_guru&view=guruProfile&task=required_courses_message&graybox=true&tmpl=component'); return false;";
																// stop open alert modal with required courses message
															}
															elseif(isset($lesson["lesson_quiz_student_go_on"]) && $lesson["lesson_quiz_student_go_on"] == '0'){
																// start exist quizzes that are not completed
																$link = "#";
																$onclick = "alert('".JText::_("GURU_NO_FINISHED_QUIZZES_BEFORE_LESSON")."')";
																// stop exist quizzes that are not completed
															}
															elseif(isset($lesson["enroll_to_course"]) && $lesson["enroll_to_course"] == 1){
																// start open enroll modal
																$link = "#";
																$onclick = "openMyModal('0','0','".JURI::root()."index.php?option=com_guru&view=guruProfile&task=loginform&course_id=".intval($course_id)."-".$alias.$action."&returnpage=guruprograms&graybox=true&tmpl=component'); return false;";
																// stop open enroll modal
															}
															elseif(isset($lesson["available_div"]) && trim($lesson["available_div"]) != ""){
																// do nothing, the lesson is not available
																$link = "#";
																$onclick = "return false";
																$available_date = trim($lesson["available_div"]);
																$style = "style='color:#999999;'";
																$nr_columns ++;
															}
															elseif(isset($lesson["need_enroll"]) && $lesson["need_enroll"] == "1"){
																$link = "#";
																$onclick = "openMyModal('0', '0', '".JURI::root()."index.php?option=com_guru&view=guruProfile&task=loginform&course_id=".$program->id."&tmpl=component&Itemid=".intval($itemid)."'); return false;";
															}
															elseif(isset($lesson["need_registration"]) && $lesson["need_registration"] == "1"){
																$link = "#";
																
																$helper = new guruHelper();
																$itemid_seo = $helper->getSeoItemid();
																$itemid_seo = @$itemid_seo["gurulogin"];
																
																if(intval($itemid_seo) > 0){
																	$itemid = intval($itemid_seo);
																}
																
																$onclick = "openMyModal('0', '0', '".JURI::root()."index.php?option=com_guru&view=guruLogin&tmpl=component&Itemid=".intval($itemid)."&returnpage=open_lesson&lesson_id=".intval($lesson["id"])."'); return false;";
																
															}
															else{
																if(isset($lesson["available_div"]) && trim($lesson["available_div"]) != ""){
																	// add AVAILABLE language var
																	$nr_columns ++;
																	$available_date = trim($lesson["available_div"]);
																}
																
																// start open course plans for buy
																$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
																
																$helper = new guruHelper();
																$itemid_seo = $helper->getSeoItemid();
																
																if(isset($itemid_seo["gurueditplans"])){
																	$itemid_seo = $itemid_seo["gurueditplans"];
																}
																
																if(intval($itemid_seo) > 0){
																	$itemid = intval($itemid_seo);
																}
																
																$link = "#";
																$onclick = "openMyModal('0','0','".JURI::root()."index.php?option=com_guru&view=guruEditplans&course_id=".intval($course_id)."-".$alias.@$action."&tmpl=component&Itemid=".intval($itemid)."'); return false;";
																// stop open course plans for buy
															}
															
															if(isset($course_config->duration) && $course_config->duration == 0){
																$nr_columns ++;
															}
															
															if(isset($course_config->quiz_status) && $course_config->quiz_status == 0){
																$nr_columns ++;
															}

                                                            $step_less++;
														}

														$is_quiz_lesson = $guruModelguruProgram->getCount($module["id"], $lesson['id']);
														
														if(isset($config->indicate_quiz) && intval($config->indicate_quiz) == 1){
															if($is_quiz_lesson > 0){
																$lesson['name'] .= ' ['.JText::_("GURU_QUIZ").']';
															}
														}

														if($diff_date != ""){
															@$diff_date--;
														}
												?>
                                                		<tr>
															<td class="uk-table-expand">
                                                            	<?php
                                                                	$sql = "SELECT chb_free_courses, step_access_courses, selected_course, groups_access FROM `#__guru_program` where id = ".intval($course_id);
																	$db->setQuery($sql);
																	$db->execute();
																	$result= $db->loadAssocList();
																	$chb_free_courses = $result["0"]["chb_free_courses"];
																	$step_access_courses = $result["0"]["step_access_courses"];
																	$selected_course = $result["0"]["selected_course"];
																	$members_groups = $result["0"]["groups_access"];
																	
																	$mandatory_login = false;
																	$can_not_buy = false;
																	
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
																						$mandatory_login = true;
																						$can_not_buy = true;
																					}
																					else{
																						$can_not_buy = true;
																						
																						if(isset($prices) && count($prices) > 0){
																							foreach($prices as $key=>$value_price){
																								if(intval($value_price["price"]) != 0){
																									$can_not_buy = false;
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
																						$mandatory_login = true;
																						$can_not_buy = true;
																					}
																					else{
																						$can_not_buy = true;
																						
																						if(isset($prices) && count($prices) > 0){
																							foreach($prices as $key=>$value_price){
																								if(intval($value_price["price"]) != 0){
																									$can_not_buy = false;
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
																				
																				if(intval($user_logged->id) == 0){
																					$mandatory_login = true;
																					$can_not_buy = true;
																					
																					if(isset($prices) && count($prices) > 0){
																						foreach($prices as $key=>$value_price){
																							if(intval($value_price["price"]) != 0){
																								$can_not_buy = false;
																							}
																						}
																					}
																				}
																				else{
																					$intersect_groups = array_intersect($user_logged->groups, $members_groups_array);
																					if(is_array($intersect_groups) && count($intersect_groups) <= 0){
																						$mandatory_login = false;
																						$can_not_buy = true;
																						
																						if(isset($prices) && count($prices) > 0){
																							foreach($prices as $key=>$value_price){
																								if(intval($value_price["price"]) != 0){
																									$can_not_buy = false;
																								}
																							}
																						}
																					}
																				}
																			}
																			else{
																				// all groups
																				$user_logged = JFactory::getUser();
																				
																				if(intval($user_logged->id) == 0){
																					$mandatory_login = true;
																					$can_not_buy = true;
																					
																					if(isset($prices) && count($prices) > 0){
																						foreach($prices as $key=>$value_price){
																							if(intval($value_price["price"]) != 0){
																								$can_not_buy = false;
																							}
																						}
																					}
																				}
																			}
																			
																			if($mandatory_login){
																				$return = '<div class="uk-grid">
																								<div class="uk-width-large-8-10 uk-width-medium-1-1 uk-width-small-1-1 uk-text-left padding-right-10">
																									'.JText::_("GURU_FREE_FOR_MEMEBERS_PRICE").'
																								</div>
																								<div class="uk-width-large-2-10 uk-width-medium-1-1 uk-width-small-1-1 ">
																									<input type="button" class="uk-button uk-button-success uk-button-small" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
																								</div>
																							</div>';
																			}
																			else{
																				if($can_not_buy){
																					$return = "";
																				}
																			}
																		}
																		
																		$sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= now() or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
																		$db->setQuery($sql);
																		$db->execute();
																		$result = $db->loadColumn();
																		$result = @$result["0"];
																		
																		if(intval($result) > 0){
																			$can_not_buy = false;
																		}
																	}
																?>
                                                            
                                                                <?php
                                                                	$lesson_html_id = $device.'-lesson-'.intval($lesson["id"]);
																	
																	if($is_quiz_lesson > 0){
																		$quiz = JFactory::getApplication()->input->get("quiz", "0");
																		if(intval($quiz) > 0 && intval($quiz) == intval($is_quiz_lesson)){
																			$lesson_html_id = $device.'-quiz-'.intval($quiz);
																		}
																	}
																?>
                                                                
                                                                <?php
																	if($can_not_buy && !$mandatory_login){
																?>
                                                                		<a id="<?php echo $lesson_html_id; ?>" href="#" <?php echo $style; ?> class="<?php echo $inactive_lesson_class; ?> mainTitle font uk-text-small">
																			<?php echo $lesson['name']; ?>
                                                                        </a>
                                                                <?php	
																	}
																	else{
																?>
                                                                        <a id="<?php echo $lesson_html_id; ?>" onclick="<?php echo $onclick; ?>" href="<?php echo $link; ?>" <?php echo $style; ?> class="mainTitle font uk-text-small <?php echo $inactive_lesson_class; ?>">
                                                                            <?php echo $lesson['name']; ?>
                                                                        </a>
																<?php
                                                                	}
																?>
                                                        	</td>
                                                            <?php
                                                            	$user_id = $my->id;
																$display = "hidden";

																if($user_id > 0){
																	$lesson_viewed = $guruModelguruTask->getViewLesson($lesson['id']);
																	
																	if(isset($lesson_viewed) && $lesson_viewed === TRUE){
																		$display = "inherit";
																	}
																}
															?>

                                                            	<?php
                                                            	if(isset($course_config->quiz_status) && $course_config->quiz_status == 0){
															?>
                                                            		<td class="font uk-text-small uk-text-black uk-text-nowrap uk-width-1-6 uk-text-center">
                                                                        <?php
                                                                        	if(isset($lesson["quiz_passed"]) && $lesson["quiz_passed"] == -1 && !$expired_license){
																				// pending
																				echo '<span class="uk-text-warning font uk-text-small">'.JText::_("GURU_PENDING").'</span>';
																			}
																			elseif(isset($lesson["quiz_passed"]) && $lesson["quiz_passed"] == 0 && !$expired_license){
																				// failed
																				echo '<span class="uk-text-danger font uk-text-small">'.JText::_("GURU_QUIZ_FAILED_STATUS").'</span>';
																			}
																			elseif(isset($lesson["quiz_passed"]) && $lesson["quiz_passed"] == 1 && !$expired_license){
																				// passed
																				echo '<span class="uk-text-success font uk-text-small">'.JText::_("GURU_QUIZ_PASSED_STATUS").'</span>';
																			}
																			elseif(isset($lesson["quiz_passed"]) && $lesson["quiz_passed"] == 2 && !$expired_license){
																				// pending assessment
																				echo '<span class="font uk-text-small">'.JText::_("GURU_PENDING_ASSESSMENT").'</span>';
																			}
																			else{
																				echo '&nbsp;';
																			}
																		?>
                                                                    </td>
                                                            <?php
																}
															?>
                                                            
                                                                <td class="uk-text-success font uk-text-small uk-text-black uk-text-nowrap uk-width-1-6 uk-text-center viewed viewed-<?php echo $lesson["id"]; ?>">
                                                                    <i class="fas fa-check" style="visibility:<?php echo $display; ?>;"></i>
                                                                </td>
                                                            
                                                            <?php
                                                            	if(isset($course_config->duration) && $course_config->duration == 0){
															?>
                                                            		<td class="font uk-text-small uk-text-black uk-text-nowrap uk-width-1-6 uk-text-center">
                                                                        <?php
                                                                        	if(intval($minutes) != 0){
																				echo intval($minutes)."m ";
																			}
																			
																			if(intval($seconds) != 0){
																				echo intval($seconds)."s ";
																			}
																			
																			if(intval($minutes) == 0 && intval($seconds) == 0){
																				echo '&nbsp;';
																			}
																		?>
                                                                    </td>
                                                            <?php
																}
															?>
                                                            
                                                                <td class="uk-width-1-6 uk-text-nowrap uk-text-center">
                                                                <?php
                                                                    if($course_level == 0){ // show course level
                                                                ?>
                                                                        <span class="uk-display-inline-block"><img width="17" height="14" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" /></span>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </td>
                                                                <?php
																	$available_date = str_replace("replace_class", 'uk-width-1-'.($nr_columns - 1).' ', $available_date);
																	echo $available_date;
																?>
														</tr>
                                                <?php
													}
												?>
                                            </table>
										</div>
									</div>
								</div>
                        <?php
							}
							// stop lessons
						?>
                        
						<?php
                        	$quiz = JFactory::getApplication()->input->get("quiz", "0");
							if(intval($quiz) > 0){
						?>
                        		<script type="text/javascript" language="javascript">
									window.onload = function() {
										document.getElementById("<?php echo $device.'-quiz-'.intval($quiz); ?>").click();
									};
								</script>
                        <?php
							}
						?>
            </div>
		<?php
        }
		
		$total_minutes += floor($total_seconds / 60);
		$total_seconds = $total_seconds % 60;
		
		if(intval($total_minutes) >= 60){
			$total_hours = floor($total_minutes / 60);
			$total_minutes = $total_minutes % 60;
		}
		
		$display_duration_1 = array();
		$display_duration_2 = array();
		
		if(intval($total_hours) > 0){
			if(intval($total_hours) > 1){
				$display_duration_1[] = $total_hours." ".JText::_("GURU_PROGRAM_DETAILS_HOURS");
			}
			else{
				$display_duration_1[] = $total_hours." ".JText::_("GURU_PROGRAM_DETAILS_HOUR");
			}
			
			$display_duration_2[] = $total_hours."h";
		}
		
		if(intval($total_minutes) > 0){
			if(intval($total_minutes) > 1){
				$display_duration_1[] = $total_minutes." ".JText::_("GURU_PROGRAM_DETAILS_MINUTES");
			}
			else{
				$display_duration_1[] = $total_minutes." ".JText::_("GURU_PROGRAM_DETAILS_MINUTE");
			}
			
			$display_duration_2[] = $total_minutes."m";
		}
		
		if(intval($total_seconds) > 0){
			if(intval($total_minutes) > 1){
				$display_duration_1[] = $total_seconds." ".JText::_("GURU_PROGRAM_DETAILS_SECONDS");
			}
			else{
				$display_duration_1[] = $total_seconds." ".JText::_("GURU_PROGRAM_DETAILS_SECOND");
			}
			
			$display_duration_2[] = $total_seconds."s";
		}
		
		if(isset($course_config->duration) && $course_config->duration == 0){
		?>
			<script type="text/javascript" language="javascript">
				if(eval(document.getElementById("total-duration-1"))){
					document.getElementById("total-duration-1").innerHTML = "<?php echo implode(", ", $display_duration_1); ?>";
				}
				
				if(eval(document.getElementById("total-duration-2"))){
					document.getElementById("total-duration-2").innerHTML = "<?php echo implode(" ", $display_duration_2); ?>";
				}
			</script>
		<?php
		}
		?>
        
        </div>


    <div class="uk-grid">
        <?php
        $show_all_cloase_all = isset($course_config->show_all_cloase_all) ? $course_config->show_all_cloase_all : "0";
        if($user_id > 0){
            $col_width=9;
        }else{
            $col_width=8;
        }

        $model = new guruModelguruProgram();
        $expired_license = $model->getExpiredLicense($program);

        if($user_id > 0 && $coursetype_details[0]["course_type"] != 0  && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE && /*!in_array($user_id, $authors) &&*/ !$expired_license){
            $col_width = 7;
        }

        if(isset($course_config->duration) && $course_config->duration == 0){
            $col_width = $col_width - 2;
        }

        if(isset($course_config->quiz_status) && $course_config->quiz_status == 0){
            $col_width = $col_width - 2;
        }

        ?>

        <div class="uk-hidden">
            <?php
            if($show_all_cloase_all != 1){
                ?>
                <input type="button" class="uk-button uk-button-default uk-button-small uk-border-rounded uk-text-tiny show_sub" value="+ <?php echo JText::_("GURU_SHOW_ALL_BUTTON"); ?>"/><!--show all button -->
                <input type="button" class="uk-button uk-button-default uk-button-small uk-border-rounded uk-text-tiny close_sub" value="- <?php echo JText::_("GURU_CLOSE_ALL_BUTTON"); ?>"/><!--close all button -->
                <?php
            }
            else{
                echo '';
            }
            ?>
        </div>

        <div class="uk-width-1-2 uk-hidden-small hidden-phone uk-hidden">
            <?php
            $nr_columns = 1;

            if(isset($course_config->duration) && $course_config->duration == 0){
                $nr_columns ++;
            }

            if(isset($course_config->quiz_status) && $course_config->quiz_status == 0){
                $nr_columns ++;
            }

            if($course_level == 0){
                $nr_columns ++;
            }

            if($user_id > 0 && $coursetype_details[0]["course_type"] != 0  && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE && /*!in_array($user_id, $authors) &&*/ !$expired_license){
                $nr_columns ++;
            }
            ?>

            <?php
            if(isset($course_config->quiz_status) && $course_config->quiz_status == 0 && $device == "d"){
                ?>
                <div class="col_title pull-left uk-width-1-<?php echo intval($nr_columns); ?> ">
                    <?php
                    echo JText::_("GURU_QUIZ_STATUS");
                    ?>
                </div>
                <?php
            }
            ?>

            <div class="col_title pull-left uk-width-1-<?php echo intval($nr_columns); ?> ">
                <?php
                echo JText::_("GURU_VIEWED");
                ?>
            </div>

            <?php
            if(isset($course_config->duration) && $course_config->duration == 0 && $device == "d"){
                ?>
                <div class="col_title pull-left uk-width-1-<?php echo intval($nr_columns); ?> ">
                    <?php
                    echo JText::_("GURU_DURATION");
                    ?>
                    <br />
                    <span id="total-duration-2"></span>
                </div>
                <?php
            }
            ?>


            <div class="col_title pull-left uk-width-1-<?php echo intval($nr_columns); ?> ">
                <?php
                if($course_level==0){
                    echo JText::_("GURU_LEVEL");
                }
                ?><!--Level -->
            </div>
            <?php

            if($user_id > 0 && $coursetype_details[0]["course_type"] != 0  && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] > 0 && $not_show === TRUE && /*!in_array($user_id, $authors) &&*/ !$expired_license){?>
                <div class="col_title pull-left uk-width-1-<?php echo intval($nr_columns); ?> ">
                    <?php echo JText::_("GURU_AVAILABILITY"); ?>
                </div>
                <?php
            } ?>
        </div>
    </div>



</div>



    <!-- end main div-->
    <?php
}

function tab2($program){
?>
    <div class="">
        <div class="uk-text-small font uk-text-justify">
            <?php
				$program->description = JHtml::_('content.prepare', $program->description);
                echo $program->description;
            ?>  
        </div>
    </div>    
<?php
}
function tab3($program, $config){
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
   <div class="clearfix">
    <div class="g_table_wrap"> 
        <table class="uk-table uk-table-striped">
             <tr>
                <th class="g_cell_1"><?php echo  JText::_("GURU_SUBS_PLAN_NAME");  ?></th>
                <th class="g_cell_2"><?php echo JText::_("GURU_PROGRAM_DETAILS_PRICE"); ?></th>
            </tr>
    <?php
				foreach($prices as $key=>$value){
					$class = "odd";
					if($k%2 != 0){
						$class = "even";
					}
					if(trim($value["name"]) != "" || trim($value["price"]) != ""){
						if($value["price"] > 0){
            ?>
                    <tr class="<?php echo $class; ?>"> 
                        <td class="g_cell_1"><b><?php echo $value["name"]; ?>:</b></td>
                        <td  class="g_cell_2">
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
                        </td>
                    </tr>
            <?php
						}
					}
					$k++;
				}
        ?>
        </table>
      </div>
    </div>    
    <?php  
		}
		else{
			echo JText::_("GURU_FREE_FOR_MEMEBERS_PRICE");
		}     
    }
}

function tab4($exercise,$config){
?>
    <ul>
<?php
    $db = JFactory::getDBO();
    $course_id = intval(JFactory::getApplication()->input->get("cid", 0));
    $my = JFactory::getUser();
	$my = JFactory::getUser();
    $user_id = $my->id;
	
	$sql = "select `expired_date` from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->execute();
    $expired_date_string = $db->loadColumn();
    $expired_date_string = @$expired_date_string["0"];
	
    foreach($exercise as $element){
        ?>
    <li class="g_row row-fluid">
        <div class="g_cell">
    <script type="text/javascript">
            <?php
            if($my->id >0 && $element->access != 2){
                $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($my->id)." and `course_id`=".intval($course_id);
                $db->setQuery($sql);
                $db->execute();
                $result = $db->loadResult();
                if($result > 0){
                    $expired = false;
                    $sql = "select expired_date from #__guru_buy_courses where userid=".intval($my->id)." and course_id=".intval($course_id);
                    $db->setQuery($sql);
                    $db->execute();
                    $expired_date_string = $db->loadResult();
                    $current_date_string = "";
                    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($my->id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= '".$current_date_string."' or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
                    $db->setQuery($sql);
                    $db->execute();
                    $result = $db->loadResult();
                    if(($expired_date_string != "0000-00-00 00:00:00") || (!isset($result) || intval($result) == 0)){
                        $expired_date_int = strtotime($expired_date_string);
                        $jnow = new JDate('now');
                        $current_date_string = $jnow->toSQL();
                        $current_date_int = strtotime($current_date_string);
                        if($current_date_int < $expired_date_int){
                            $expired = false;
                        }
                        $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($my->id)." and o.status='Paid'";
                        $db->setQuery($sql);
                        $db->execute();
                        $my_courses = $db->loadColumn();
                        if(in_array($course_id, $my_courses)){ // I bought this course
                            $difference_int = get_time_difference($current_date_int, $expired_date_int);
                            $difference = $difference_int["days"];
                            if($difference_int["days"] == 0){
                                if($difference_int["hours"] == 0){
                                    if($difference_int["minutes"] == 0){
                                        $difference = "0";
                                    }
                                    else{
                                        $difference = $difference_int["minutes"];
                                    }
                                }
                                else{
                                    $difference = $difference_int["hours"];
                                }
                            }
                            if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                                $difference_int = "1"; //default for unlimited
                            }
                            if($difference_int !== FALSE){// is not expired
                                $expired = true;
                            }
                            else{
                                $expired = false;
                            }
                        }
                        else{
                            $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($my->id)." and `course_id`=".intval($course_id)." and order_id=0";
                            $db->setQuery($sql);
                            $db->execute();
                            $result = $db->loadResult();
                            if($result > 0){
                                $expired = true;
                            }
                        }
                    }
                }
                else{
                    $expired = false;
					@$expired_date_int = -1;

                }
            }
            $access_exerc = 1;
            if($expired_date_string =='0000-00-00 00:00:00'){
                $access_exerc = 1;
            }
			elseif($expired_date_int == -1){
				$access_exerc = 0;
			}
            else{
                $expired_date_int = strtotime($expired_date_string);
                $jnow = new JDate('now');
                $current_date_string = $jnow->toSQL();
                $current_date_int = strtotime($current_date_string);
                if($current_date_int > $expired_date_int){
                    $access_exerc = 0;                  
                }
                else{
                    $access_exerc = 1;
                }
                if($expired_date_int ==0){
                    $access_exerc = 1;      
                }
            }
            ?>
            <?php
            if($element->access == 2 || ($element->access < 2 && $my->id > 0 && $access_exerc == 1 )){  
                if($element->type == "docs" && trim($element->local) != ""){
                    ?>
                document.write('<a target="_blank" href="<?php echo JURI::root().$config->docsin.'/'.$element->local; ?>"><img src="components/com_guru/images/doc.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->local; ?></a>');
                    <?php
                }
				elseif($element->type == "docs" && trim($element->url) != ""){
                    ?>
                	document.write('<a target="_blank" href="<?php echo JURI::root().$element->url; ?>"><img src="components/com_guru/images/doc.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->name; ?></a>');
                    <?php

                }
                elseif($element->type == "file" && trim($element->local) != ""){
                    ?>
                document.write('<a target="_blank" href="<?php echo JURI::root().$config->filesin.'/'.$element->local; ?>"><img src="components/com_guru/images/file.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->local; ?></a>');
                    <?php

                }
            }
            else{
                if(trim($element->local) != ""){
					$image = "";
					if($element->type == "docs"){
						$image = "doc";
					}
					elseif($element->type == "file"){
						$image = "file";
					}
					
					$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
					
					$helper = new guruHelper();
					$itemid_seo = $helper->getSeoItemid();
					$itemid_seo = @$itemid_seo["gurueditplans"];
					
					if(intval($itemid_seo) > 0){
						$itemid = intval($itemid_seo);
					}
			?>
                document.write('<a href="<?php echo JURI::root()."index.php?option=com_guru&view=guruEditplans&course_id=".$course_id."&tmpl=component&Itemid=".intval($itemid); ?>" onclick="openMyModal(\'\',\'\',\'<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo $course_id;?>&tmpl=component&Itemid=<?php echo intval($itemid); ?>\'); return false;" ><img src="components/com_guru/images/<?php echo $image; ?>.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->local; ?></a>');
                    <?php
                }
            }
            ?>
            
    </script>
        </div>
        </li>
        <?php
    }
?>
    </ul>
<?php
}
function tab5($authors, $course, $config, $course_config){
    $authors_config = json_decode($config->st_authorspage);
    $detect = new Mobile_Detect;
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
    if($deviceType == "phone"){
        $class_th_links = "class='teacher_links2'";
    }
    else{
        $class_th_links = "class='well teacher_links'";
    }
    ?>
<div class="course_view_teacher">
    <div id="teacherdetail" class="clearfix com-cont-wrap">
    <?php
    	if(isset($authors) && count($authors) > 0){
			foreach($authors as $key=>$author){
	?>

<div uk-accordion>
    <div class="uk-open">
        <a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black" href="#">Introduction</a>
        <div class="uk-accordion-content">
        <div class="" uk-grid>
        <div class="uk-width-1-1 uk-width-1-4@m">

        <?php
                                $config_author = json_decode($config->authorpage);
                                $img_align = $config_author->author_image_alignment; //0-left, 1-right
                                if($img_align == 0){
                                    $align = "left";
                                }
                                else{
                                    $align = "right";
                                }
                                $guruHelper = new guruHelper();

                                if(trim($author->images)!=""){
                                    $type = $course_config->course_image_size_type == "0" ? "w" : "h";
                                    $guruHelper->createThumb(@$author->imageName,$config->imagesin."/authors", $course_config->course_image_size, $type);
                                    ?>
                                    <?php /* ?>
                                    <a href="<?php echo JRoute::_('index.php?option=com_guru&view=guruauthor&layout=view&task=author&cid='.$author->id."-".JFilterOutput::stringURLSafe($author->name)); ?>">

                                    </a>
                                    <?php */ ?>
                                    <img class="uk-width-1-1 uk-border-rounded uk-margin-bottom" src='<?php echo JURI::root().$author->images; ?>' alt='author image' align='<?php echo $align;?>' />
        <?php   } ?>

                <?php
        if( (trim($author->show_email) == "") || ($author->show_email == 0) &&
		(trim($author->show_website) == "http://") || ($author->show_website == 0) &&
		(trim($author->show_blog) == "") || ($author->show_blog == 0) &&
		(trim($author->show_twitter) == "") || ($author->show_twitter == 0) &&
		(trim($author->show_facebook) == "http://") || ($author->show_facebook == 0)
		){
            // do nothing
		} else { ?>
		<div class="uk-text-center">
            <div class="uk-button-group">
                                        <?php
                                        if((trim($author->show_email)!="")&&($author->show_email==1)){ ?>
                                                    <a class="uk-button uk-button-default uk-button-small" uk-tooltip="" title="<?php echo JText::_('GURU_EMAIL');?>" href="mailto:<?php echo $author->email; ?>">
                                                        <i class="fa fa-envelope"></i>

                                                    </a>
                                            <?php
                                        }
                                        if((trim($author->show_website)!="http://")&&($author->show_website==1)){ ?>
                                                    <a class="uk-button uk-button-default uk-button-small" uk-tooltip="" title="<?php echo JText::_('GURU_SITE'); ?>" href="<?php echo $author->website; ?>" target="_blank">
                                                        <i class="fa fa-globe"></i>

                                                    </a>
                                            <?php
                                        }
                                        /*
                                        if((trim($author->show_blog)!="http://")&&($author->show_blog==1)){ ?>
                                                    <a class="uk-button uk-button-default uk-button-small" uk-tooltip="" title="<?php echo JText::_('GURU_BLOG'); ?>" href="<?php echo $author->blog; ?>" target="_blank">
                                                        <i class="fas fa-pencil-alt"></i>

                                                    </a>
                                        <?php
                                        } */
                                        if((trim($author->show_twitter)!="")&&($author->show_twitter==1)){ ?>
                                                    <a class="uk-button uk-button-default uk-button-small" uk-tooltip="" title="<?php echo JText::_('GURU_TWITTER'); ?>" href="http://www.twitter.com/<?php echo $author->twitter; ?>" target="_blank">
                                                        <i class="fab fa-twitter"></i>

                                                    </a>
                                        <?php
                                        }
                                        if((trim($author->show_facebook)!="http://")&&($author->show_facebook==1)){ ?>
                                                    <a class="uk-button uk-button-default uk-button-small" uk-tooltip="" title="<?php echo JText::_('GURU_FACEBOOK'); ?>" href="<?php echo $author->facebook; ?>" target="_blank">
                                                        <i class="fab fa-facebook-f"></i>

                                                    </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
        </div>
		<?php } ?>

</div>
        <div class="uk-width-1-1 uk-width-3-4@m">

        <!-- Author Name -->
            <div>
                <h3 class="font uk-margin-small-top uk-margin-small-bottom"><?php echo $author->name; ?></h3>
            </div>

        <?php
                                	$author->full_bio = JHtml::_('content.prepare', $author->full_bio);
									echo '<div class="uk-text-justify font uk-text-black uk-text-small">'.$author->full_bio.'</div>';
								?>

</div>
</div>

</div>
    </div>
    <div>
        <a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black" href="#"><?php echo JText::_("GURU_TAB_AUTHOR_COURSES"); ?></a>
        <div class="uk-accordion-content">
        <table class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables">
        <thead>
                                            <tr>
                                                <th class="font uk-text-nowrap uk-text-left uk-table-expnad" ><?php echo JText::_("GURU_TAB_AUTHOR_COURSES_NAME"); ?></th>
                                                <th class="font uk-text-nowrap uk-text-center uk-table-shrink uk-text-center"><?php echo JText::_("GURU_TAB_AUTHOR_COURSES_LEVEL"); ?></th>
                                                <th class="font uk-text-nowrap uk-text-center uk-table-shrink uk-text-center"><?php echo JText::_("GURU_TAB_AUTHOR_COURSES_RELEASE");?></th>
                                            </tr>
</thead>
                                            <?php
                                            $k = 0;

											$guruModelguruProgram = new guruModelguruProgram();
											$course = $guruModelguruProgram->getAuthorCoursesById($author);

                                            if(count($course)>0){
                                                $itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
                                                for($i=0; $i<count($course); $i++){
													$class = "odd";
                                                    if($k%2 != 0){
                                                        $class = "even";
                                                    }
                                            ?>
                                                <tr class="<?php echo $class; ?>">
                                                    <td>
                                                        <?php
                                                            /***$alias = trim($course[$i]->alias) == "" ? JFilterOutput::stringURLSafe($course[$i]->name) : trim($course[$i]->alias);
                                                            $courseLink = JRoute::_('index.php?option=com_guru&view=guruPrograms&layout=view&cid='.$course[$i]->id."-".$alias."&Itemid=".$itemid);***/
                                                            if(isset($course[$i]->alias)){
                                                                if(trim($course[$i]->alias) == ""){
                                                                    $alias = JFilterOutput::stringURLSafe($course[$i]->name);
                                                                }
                                                                else{
                                                                    $alis = trim($course[$i]->alias);
                                                                }
                                                                $courseLink = JRoute::_('index.php?option=com_guru&view=guruPrograms&layout=view&cid='.$course[$i]->id."-".$alias."&Itemid=".intval($itemid));
                                                            }
                                                            else{
                                                                $courseLink = JRoute::_('index.php?option=com_guru&view=guruPrograms&layout=view&cid='.$course[$i]->id."&Itemid=".intval($itemid));
                                                            }
                                                        ?>
                                                        <a href='<?php echo $courseLink; ?>' class="mainTitle">
                                                            <?php echo $course[$i]->name; ?>
                                                        </a>
                                                    </td>
                                                    <td class="uk-text-center uk-text-nowrap"><img src='<?php echo JURI::root()."components/com_guru/images/".$course[$i]->level.".png"; ?>'/></td>
                                                    <?php
                                                        $int_date    = strtotime($course[$i]->startpublish);
                                                        $date        = date($config->datetype, $int_date);
                                                    ?>
                                                    <td class="uk-text-center uk-text-nowrap uk-text-black font uk-text-small"><?php echo JHTML::date($date, 'm F Y'); ?></td>
                                                </tr>
                                            <?php
                                            $k++;
                                                }

                                            }
                                            ?>
                                    </table>
</div>
    </div>
</div>
	<?php
        	}
		}
	?>
</div>
</div>
<?php
}

function tab6($requirements, $program){
?>
<div>
    <div class="course_view_requirements" uk-accordion="multiple: true">
    <?php if(!empty($requirements)) { ?>
        <div>
        <a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black"><?php echo JText::_("GURU_TAB_REQUIREMENTS_COURSES"); ?></a>
        <div class="uk-accordion-content">
        <ul class="uk-grid-small uk-grid-divider uk-child-width-1-1 reqCourseLisr" uk-grid>
            <li>
                <?php
                $requirements = implode("</li><li>",$requirements);
                $requirements = JHtml::_('content.prepare', $requirements);
                echo $requirements;
                ?>
            </li>
        </ul>
        </div>
        </div>
    <?php } ?>
    
        <?php
    
        if(trim($program->pre_req) != ""){
    
		?>
        	<div><a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black"><?php echo JText::_("GURU_TAB_REQUIREMENTS_OTHERS");?></a>
		<?php
			$program->pre_req = JHtml::_('content.prepare', $program->pre_req);   
            echo '<div class="uk-text-justify font uk-text-small uk-text-black uk-accordion-content">'.$program->pre_req.'</div></div>';
        }
    
        if(trim($program->pre_req_books) != ""){
    

            ?>
    
        <div><a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black"><?php echo JText::_("GURU_TAB_REQUIREMENTS_BOOKS");?></a>
    
            <?php
    		$program->pre_req_books = JHtml::_('content.prepare', $program->pre_req_books);
            echo '<div class="uk-text-justify font uk-text-small uk-text-black uk-accordion-content">'.$program->pre_req_books.'</div></div>';
    
        }
    
    
    
        if(trim($program->reqmts) != ""){
    
            ?>
    
        <div><a class="font uk-accordion-title uk-border-rounded uk-card uk-card-default uk-padding-small uk-text-small uk-text-black"><?php echo JText::_("GURU_TAB_REQUIREMENTS_MISC");?></a>
    
            <?php
    		$program->reqmts = JHtml::_('content.prepare', $program->reqmts);
            echo '<div class="uk-text-justify font uk-text-small uk-text-black uk-accordion-content">'.$program->reqmts.'</div></div>';
    
        }
        ?>
     </div> 
 </div>      
    <?php
}

function createTabs($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config){
	jimport('joomla.html.pane');
    jimport('joomla.utilities.date');
    JHtml::_('behavior.framework');
    
    $document = JFactory::getDocument();
    $document->addStyleSheet("components/com_guru/css/tabs_css.css");
    $document->addStyleSheet("components/com_guru/css/tabs.css");
    
    //$document->addScript("components/com_guru/js/programs.js");
    
    $itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
    $user = JFactory::getUser();
    $user_id = $user->id;
    $lesson_size = $config->lesson_window_size;
    $lesson_size = explode("x", $lesson_size);
    $lesson_height = $lesson_size["0"];
    $lesson_width = $lesson_size["1"];
    
    if(trim($lesson_height) == "" || trim($lesson_height) == "0"){
        $lesson_height == 1000;
    }
    if(trim($lesson_width) == "" || trim($lesson_width) == "0"){
        $lesson_width == 600;
    }
    
    $action_bool = getAction();
    $action = "";
    if($action_bool === TRUE){
        $action = "&action=renew";
    }
	
	$st_psgpage = json_decode($config->st_psgpage);
	$buy_background = $st_psgpage->course_other_background;
	$course_id = intval(JFactory::getApplication()->input->get("cid", 0));
	$buy_class = $st_psgpage->course_other_button;
?>

    <div>
        <!-- start mobile version -->
        <div class="uk-hidden-large uk-hidden-medium uk-hidden@m">
                    <div class="uk-clearfix">
                        <div id="accordion" class="accordion uk-margin-top">
                            <?php
                                if(!empty($program_content) && $course_config->course_table_contents == "0"){ ?>
                                    <div class="accordionItem">
                                        <h3 class="guru-accordion-title g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_TABLE_CONTENT");?></h3>
                                        <div class="guru-accordion-content clearfix tab-body  g_accordion-group g_content_active">
                                            <?php
                                                tab1($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config, "p");
                                            ?>
                                        </div>
                                    </div>

                            <?php
                                }

                                if($program->description != "" && $course_config->course_description_show == "0"){ ?>
                                    <div class="accordionItem">
                                        <h3 class="guru-accordion-title g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_DESCRIPTION");?></h3>
                                        <div class="guru-accordion-content clearfix tab-body  g_accordion-group g_content_active">
                                            <?php
                                                tab2($program);
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }

                                if($course_config->course_tab_price == "0" && !is_array(@$button)){
                            ?>
                                    <div class="accordionItem">
                                        <h3 class="guru-accordion-title g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_BUY_PRICE");?></h3>
                                        <div class="guru-accordion-content clearfix tab-body  g_accordion-group g_content_active">
                                            <?php
                                                if(!isset($button)){
                                                    $button = "";
                                                }
                                                tab3($program, $config);
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }
                                if(!empty($exercise) && $config->course_exercises == "0"){
                            ?>
                                    <div class="accordionItem">
                                        <h3 class="guru-accordion-title g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_EXERCISE_FILES");?></h3>
                                        <div class="guru-accordion-content clearfix tab-body  g_accordion-group g_content_active">
                                            <?php
                                                tab4($exercise,$config);
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }

                                if($course_config->course_author == "0"){
                            ?>
                                    <div class="accordionItem">
                                        <h3 class="guru-accordion-title g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_AUTHOR");?></h3>
                                        <div class="guru-accordion-content clearfix tab-body  g_accordion-group g_content_active">
                                            <?php
                                                tab5($author,$course, $config, $course_config);
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }

                                if((!empty($requirements) || $program->pre_req != "" || $program->pre_req_books != "" || $program->reqmts != "") && ($course_config->course_requirements == "0") && !is_array(@$button)){
                            ?>
                                    <div class="accordionItem">
                                        <h3 class="guru-accordion-title g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_REQUIREMENTS");?></h3>
                                        <div class="guru-accordion-content clearfix tab-body  g_accordion-group g_content_active">
                                            <?php
                                                tab6($requirements, $program);
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
        <!-- end mobile version -->

        <!-- start computer/tablet version -->
        <div class="uk-hidden-small hidden-phone uk-visible@m">
            <div id="guru_tabs">
                <ul class="uk-subnav uk-subnav-pill" uk-switcher="animation: uk-animation-fade">
                    <?php  if($program->description != "" && $course_config->course_description_show == "0"){?>
                        <li id="li-tab2"><a class="font uk-text-small uk-text-capitalize uk-padding-small uk-border-rounded" href="#" onclick="javascript:changeGuruTab('tab2'); return false;"><?php echo JText::_("GURU_TAB_DESCRIPTION");?></a></li>
                    <?php }?>
                     <?php if(!empty($program_content) && $course_config->course_table_contents == "0"){?>
                            <li id="li-tab1"><a class="font uk-text-small uk-text-capitalize uk-padding-small uk-border-rounded" href="#" onclick="javascript:changeGuruTab('tab1'); return false;"><?php echo JText::_("GURU_TAB_TABLE_CONTENTS");?></a></li>
                     <?php }?>

                     <?php /* if($course_config->course_tab_price == "0" && !is_array(@$button)){?>
                                <li id="li-tab3"><a class="font uk-text-small uk-text-capitalize uk-padding-small uk-border-rounded" href="#" onclick="javascript:changeGuruTab('tab3'); return false;"><?php echo JText::_("GURU_BUY_PRICE");?></a></li>
                     <?php } */ ?>

                     <?php if(!empty($exercise) && $config->course_exercises == "0"){?>
                                <li id="li-tab4"><a class="font uk-text-small uk-text-capitalize uk-padding-small uk-border-rounded" href="#" onclick="javascript:changeGuruTab('tab4'); return false;"><?php echo JText::_("GURU_EXERCISE_FILES");?></a></li>
                     <?php }?>
                     <?php if($course_config->course_author == "0"){?>
                                <li id="li-tab5">
                                	<a class="font uk-text-small uk-text-capitalize uk-padding-small uk-border-rounded" href="#" onclick="javascript:changeGuruTab('tab5'); return false;">
										<?php
											if(count($author) == 1){
                                        		echo JText::_("GURU_TAB_AUTHOR");
											}
											else{
												echo JText::_("GURU_TAB_AUTHORS");
											}
                                        ?>
                                    </a>
								</li>
                     <?php } ?>
                     <?php if((!empty($requirements) || $program->pre_req!="" || $program->pre_req_books!="" || $program->reqmts!="") && ($course_config->course_requirements == "0") && !is_array(@$button)){?>
                                <li id="li-tab6"><a class="font uk-text-small uk-text-capitalize uk-padding-small uk-border-rounded" href="#" onclick="javascript:changeGuruTab('tab6'); return false;"><?php echo JText::_("GURU_TAB_REQUIREMENTS");?></a></li>
                     <?php }?>
            </ul>
             
             <div class="uk-switcher">
                 <div>
                     <?php
                     if($program->description != "" && $course_config->course_description_show == "0"){
                         tab2($program);
                     }
                     ?>
                 </div>
                 <div>
                     <?php
					 	if(!empty($program_content) && $course_config->course_table_contents == "0"){
							tab1($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config, 'd');
                        }
                     ?>
                 </div>
                 <?php /* ?>
                 <div id="tab3">
                     <?php
                     if(!isset($button)){
                         $button = "";
                     }
                     if($course_config->course_tab_price == "0" && !is_array($button)){
                         tab3($program, $config);
                     }
                     ?>
                 </div>
                 <?php */ ?>
                 <?php /* ?>
                 <div id="tab4"">
                     <?php
                     if(!empty($exercise) && $config->course_exercises == "0"){
                         tab4($exercise,$config);
                     }
                     ?>
                 </div>
                 <?php */ ?>
                 <div>
                     <?php
                     if($course_config->course_author == "0"){
                         tab5($author,$course, $config, $course_config);
                     }
                     ?>
                 </div>
                 <div>
                     <?php
                     if((!empty($requirements) || $program->pre_req!="" || $program->pre_req_books!="" || $program->reqmts!="") && ($course_config->course_requirements == "0") && !is_array($button)){
                         tab6($requirements, $program);
                     }
                     ?>
                 </div>
             </div>
         </div>
    </div>
    </div>
<?php } ?>