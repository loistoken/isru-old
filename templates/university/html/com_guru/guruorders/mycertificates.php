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
defined ('_JEXEC') or die ("Go away.");
include(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_guru'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'gurutask.php');
JHTML::_('behavior.tooltip');
	
$document = JFactory::getDocument();
//$document->addScript("components/com_guru/js/programs.js");
$db = JFactory::getDBO();
$user = JFactory::getUser();
$user_id = $user->id;
$Itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
$search = JFactory::getApplication()->input->get("search_course", "");
$config = $this->getConfigSettings();
$guruModelguruOrder = new guruModelguruOrder();
$guruModelguruTask = new guruModelguruTask();

$my_courses = $this->my_courses;
$certcourseidlist = $guruModelguruOrder->getCourseidsList($user_id);
$certificates_general = $guruModelguruOrder->getCertificate(); 

$document->setTitle(trim(JText::_('GURU_MYCERTIFICATES')));

include_once(JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_guru".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."helper.php");
$helper = new guruHelper();
$div_menu = $helper->createStudentMenu();
$page_title_cart = $helper->createPageTitleAndCart();
$data_post = JFactory::getApplication()->input->post->getArray();
?>

<script type="text/javascript" language="javascript">
	document.body.className = document.body.className.replace("modal", "");
</script>

<script>
function openWinCertificate1(t1,t2,t3,t4,t5){
	t1 = encodeURIComponent(t1);
	t2 = encodeURIComponent(t2);
	t3 = encodeURIComponent(t3);
	t4 = encodeURIComponent(t4);
	t5 = encodeURIComponent(t5);
	
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=printcertificate&op=1&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=800,height=600, resizable = 0');
	myWindow.focus();
}

function openWinCertificate2(t1,t2,t3,t4,t5){
	t1 = encodeURIComponent(t1);
	t2 = encodeURIComponent(t2);
	t3 = encodeURIComponent(t3);
	t4 = encodeURIComponent(t4);
	t5 = encodeURIComponent(t5);
	
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=printcertificate&op=2&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=850,height=600, resizable = 0');
	myWindow.focus();
}

function openWinCertificate3(t1,t2,t3,t4,t5){
	t1 = encodeURIComponent(t1);
	t2 = encodeURIComponent(t2);
	t3 = encodeURIComponent(t3);
	t4 = encodeURIComponent(t4);
	t5 = encodeURIComponent(t5);
	
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=printcertificate&op=3&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=800,height=250, resizable = 0');
	myWindow.focus();
}

function openWinCertificate4(t1,t2,t3,t4,t5){
	t1 = encodeURIComponent(t1);
	t2 = encodeURIComponent(t2);
	t3 = encodeURIComponent(t3);
	t4 = encodeURIComponent(t4);
	t5 = encodeURIComponent(t5);
	
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=savepdfcertificate&op=9&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=800,height=600, resizable = 0');
	myWindow.focus();
}
</script>

<div class="gru-mycertificates">
    <form action="index.php" name="adminForm" method="post">
        <div class="" uk-grid>
            <div class="uk-width-1-1 uk-width-3-4@m">

        <?php /* ?>
        <div class="gru-page-filters">
        	<div class="gru-filter-item">
                <input type="text" class="form-control" style="margin:0px;" placeholder="<?php echo JText::_("GURU_SEARCH"); ?>" name="search_course" value="<?php if(isset($data_post['search_course'])) echo $data_post['search_course'];?>" >
                <button class="uk-button uk-button-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
			</div>
        </div>
        <?php */ ?>
        
        <table class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables">
            <thead>
                <tr>
                    <th class="font g_cell_1"><span class="g_hide_mobile"><?php echo JText::_("GURU_PROGRAM_PROGRAMS"); ?></span> <span><?php echo JText::_("GURU_NAME"); ?></span></th>
                    <?php /* ?>
                    <th class="font g_cell_2 g_hide_mobile"><?php echo JText::_("GURU_TERM"); ?></th>
                    <?php */ ?>
                    <th class="font uk-text-left g_cell_3 g_hide_mobile"><?php echo JText::_("GURU_LESSONS_COMPLETED"); ?></th>
                    <th class="font uk-text-center g_cell_4 g_hide_mobile"><?php echo JText::_("GURU_QUIZES_AVG_SCORE"); ?></th>
                    <th class="font uk-text-center g_cell_5 g_hide_mobile"><?php echo JText::_("GURU_FINAL_EXAM_SCORE"); ?></th>
                    <th class="font uk-text-center g_cell_6"><?php echo JText::_("GURU_OPTIONS"); ?></th>
                </tr>
            </thead>
                <tr>
                    <?php
                        $k = 0;
                        $hascertificate = false;
                        $already_edited = array();
                        $db		= JFactory::getDBO();
                        $datetype = "SELECT datetype from #__guru_config WHERE id=1";
                        $db->setQuery($datetype);
                        $db->execute();
                        $datetype = $db->loadResult();
                        
                        $s = 0;
                        $n = count($my_courses);
                        $scores_avg_quizzes = 0;
                        
                        foreach($my_courses as $key=>$course){
                            $class = "odd";
                            if($k%2 != 0){
                                $class = "even";
                            }
        
                            $id = $my_courses[$key]["course_id"];
                            $avg_quizzes_cert = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($id);
                            $db->setQuery($avg_quizzes_cert);
                            $db->execute();
                            $avg_quizzes_cert = $db->loadResult();
                            
                            $sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($id);
                            $db->setQuery($sql);
							$db->execute();
                            $resulthasq = $db->loadResult();
                        
                            // start calculate sum for all quizes from course------------------------------------
                            $sql = "select mr.`media_id` from #__guru_mediarel mr, #__guru_days d where mr.`type`='dtask' and mr.`type_id`=d.`id` and d.`pid`=".intval($course["course_id"]);
                            $db->setQuery($sql);
                            $db->execute();
                            $lessons = $db->loadColumn();
                            
                            if(!isset($lessons) || count($lessons) == 0){
                                $lessons = array("0");
                            }
                            
                            $sql = "select mr.`media_id` from #__guru_mediarel mr where mr.`layout`='12' and mr.`type`='scr_m' and mr.`type_id` in (".implode(", ", $lessons).")";
                            $db->setQuery($sql);
                            $db->execute();
                            $all_quizzes = $db->loadColumn();

                            $s = 0;
							
                            if(isset($all_quizzes) && count($all_quizzes) > 0){
                                foreach($all_quizzes as $key_quiz=>$quiz_id){
                                    $sql = "SELECT score_quiz FROM #__guru_quiz_question_taken_v3 WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and pid=".intval($id)." ORDER BY id DESC LIMIT 0,1";
                                    $db->setQuery($sql);
                                    $db->execute();
                                    $result_q = $db->loadColumn();
                                    $res = @$result_q["0"];
                                    $s += $res;
                                    
                                    $sql = "SELECT `failed` FROM #__guru_quiz_question_taken_v3 WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and pid=".intval($id)." ORDER BY id DESC LIMIT 0,1";
                                    $db->setQuery($sql);
                                    $db->execute();
                                    $failed = $db->loadColumn();
                                    $failed = @$failed["0"];
                                }
                            }
                            // stop calculate sum for all quizes from course------------------------------------
                            
                            /*$sql = "SELECT hasquiz  FROM #__guru_program
                            WHERE id =".intval($id);
                            $db->setQuery($sql);
                            $db->execute();
                            $nb_ofscores = $db->loadColumn();
                            $nb_ofscores = @$nb_ofscores[0];*/
							
							$nb_ofscores = 0;
							
							if(is_array($all_quizzes) && count($all_quizzes) > 0){
								$nb_ofscores = count($all_quizzes);
							}
                            
                            if($nb_ofscores != 0){
                                $scores_avg_quizzes = intval($s/$nb_ofscores);
                            }
                            $certterm = $my_courses[$key]["certerm"];
                            
                            if(!in_array($id, $already_edited)){
                                $already_edited[] = $id;
                            ?>
                                    <td class="g_cell_1"> 
                                        <?php
                                            $course_details = $guruModelguruOrder->getCourses($id);
                                            $certificateid =  $guruModelguruOrder->getCertificateId($user_id, $id);
                                            
											$sql = "SELECT `author_id` from #__guru_mycertificates where `course_id`=".intval($id)." and `user_id`=".intval($user_id);
											$db->setQuery($sql);
											$db->execute();
											$author_ids = $db->loadColumn();
											
											if(!is_array($author_ids) || count($author_ids) == 0){
												$author_ids = array("0");
											}
											
											$author_name = "SELECT name FROM #__users WHERE id IN (".implode(",", $author_ids).") ";		
                                            $db->setQuery($author_name);
											$db->execute();
                                            $author_name = $db->loadColumn();
                                            $author_name = implode(", ", $author_name);
                                            
                                            $date_completed = "SELECT datecertificate from #__guru_mycertificates where course_id=".intval($id)." and user_id=".intval($user_id);
                                            $db->setQuery($date_completed);
											$db->execute();
                                            $date_completed = $db->loadResult();

                                            if(!isset($date_completed)){
                                                $date_completed = "SELECT date_taken_quiz from #__guru_quiz_question_taken_v3 where pid=".intval($id)." and user_id=".intval($user_id);
                                                $db->setQuery($date_completed);
                                                $db->execute();
                                                $date_completed = $db->loadResult();

                                                if(!isset($date_completed)){
                                                    $date_completed = date("Y-m-d");
                                                }

                                                $db = JFactory::getDbo();
                                                $sql = "select `author` from #__guru_program where `id`=".intval($course["course_id"]);
                                                $db->setQuery($sql);
                                                $db->execute();
                                                $course_author = $db->loadColumn();
                                                $course_author = @$course_author["0"];
                                                $course_author = explode("|", $course_author);
                                                $certificate_course_author = 0;
                                                
                                                foreach($course_author as $key_author=>$value_author){
                                                    if(intval($value_author) != 0){
                                                        $certificate_course_author = intval($value_author);
                                                        break;
                                                    }
                                                }
                                                
                                                $joomla_user = JFactory::getUser();
                                                $jnow = new JDate('now');
                                                $current_date_cert = $jnow->toSQL();
                                                
                                                // check if certificate already added
                                                $sql = "select count(*) from #__guru_mycertificates where `course_id`=".intval($course["course_id"])." and `author_id`=".intval($certificate_course_author)." and `user_id`=".intval($joomla_user->id);
                                                $db->setQuery($sql);
                                                $db->execute();
                                                $count_certificates = $db->loadColumn();
                                                $count_certificates = @$count_certificates["0"];
                                                
                                                if(intval($count_certificates) == 0){
                                                    $sql = 'insert into #__guru_mycertificates (`course_id`, `author_id`, `user_id`, `emailcert`, `datecertificate`) values ("'.intval($course["course_id"]).'", "'.intval($certificate_course_author).'", "'.intval($joomla_user->id).'", \'0\', "'.$date_completed.'")';
                                                    $db->setQuery($sql);
                                                    $db->execute();
                                                }
                                            }
                                            
                                            $datetype = "SELECT datetype from #__guru_config WHERE id=1";
                                            $db->setQuery($datetype);
											$db->execute();
                                            $datetype = $db->loadResult();
                                            $date_completed =  date($datetype, strtotime($date_completed));
                                            
                                            $sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($id);
                                            $db->setQuery($sql);
											$db->execute();
                                            $result = $db->loadResult();
											$id_final_exam = $result;
                                            
                                            $sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
                                            $db->setQuery($sql);
											$db->execute();
                                            $result_maxs = $db->loadResult();
                                            
                                            if($certterm == 0){
                                                $details = JText::_("GURU_NO_CERT_GIVEN");
                                            }
                                            elseif($certterm == 1){
                                                $details = JText::_("GURU_NO_CERT_GIVEN");
                                            }
                                            elseif($certterm == 2){
                                                $details = JText::_("GURU_MUST_COLMP_ALL_LESS");
                                            }
                                            elseif($certterm == 3){
                                                $details = JText::_("GURU_MUST_PASS_FE")." ".$result_maxs."%";
                                            }
                                            elseif($certterm == 4){
                                                $details = JText::_("GURU_MUST_PASS_QAVG")." ".$avg_quizzes_cert."%";
                                            }
                                            elseif($certterm == 5){
                                                $details = JText::_("GURU_CERT_TERM_FALFE");
                                            }
                                            elseif($certterm == 6){
                                                $details = JText::_("GURU_CERT_TERM_FALPQAVG")." ".$avg_quizzes_cert."%";
                                            }
                                            elseif($certterm == 7){
                                                $sql = "select `record_hour`, `record_min` from #__guru_program where `id`=".intval($course["course_id"]);
                                                $db->setQuery($sql);
                                                $db->execute();
                                                $course_record_details = $db->loadAssocList();

                                                if($course_record_details["0"]["record_hour"] == ""){
                                                    $course_record_details["0"]["record_hour"] = "00";
                                                }

                                                if($course_record_details["0"]["record_min"] == ""){
                                                    $course_record_details["0"]["record_min"] = "00";
                                                }

                                                $details = JText::_("GURU_CERT_TERM_TIME_RECORDING")." ".$course_record_details["0"]["record_hour"].":".$course_record_details["0"]["record_min"].":00";
                                            }

                                            $completed_course = $guruModelguruOrder->courseCompleted($user_id, $id);
        
        ?>
                                            <a class="uk-text-small font mainTitle" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".$id."-".@$alias."&Itemid=".$Itemid); ?>"><?php echo $my_courses[$key]["course_name"]; ?></a>
                                        <br><span class="uk-text-muted font uk-text-tiny"><?php echo $details ;  ?></span>
                                        </td>
                                        <td class="g_cell_3 g_hide_mobile uk-text-center">
                                            <?php
                                                if($completed_course == true){
                                                    echo '<i class="uk-text-success fas fa-check"></i>';
                                                }
                                                else{
                                                    echo '<i class="uk-text-danger fas fa-times"></i>';
                                                }
                                            
                                             ?>
                                        </td>
                                        <td class="g_cell_4 g_hide_mobile font uk-text-tiny uk-text-black uk-text-center">
                                            <?php
                                            if($resulthasq == 0 && $scores_avg_quizzes == ""){
                                                echo JText::_("GURU_NO_QUIZZES");
                                            }
                                            elseif($resulthasq != 0 && $scores_avg_quizzes == ""){
                                                echo JText::_("GURU_NOT_TAKEN");
                                            }
                                            elseif($resulthasq != 0 && isset($scores_avg_quizzes)){
                                                if($scores_avg_quizzes >= intval($avg_quizzes_cert)){
                                                    echo $scores_avg_quizzes.'%'.' &bull; <span class="uk-text-tiny font" style="color:#66CC00;">'.JText::_("GURU_QUIZ_PASSED").'</span>';
                                                }
                                                else{
                                                    echo $scores_avg_quizzes.'%'.' &bull; <span class="uk-text-tiny font"  style="color:#FF0000;">'.JText::_("GURU_QUIZ_FAILED").'</span>';
                                                }
                                            } 
                                            ?>
                                        </td>
                                        <td class="g_cell_5 g_hide_mobile font uk-text-tiny uk-text-black uk-text-center">
                                            <?php
                                                if($result !=0 && $res !="" ){
                                                    if( $res >= $result_maxs){
                                                        echo $res.'%'.' &bull; <span class="uk-text-tiny font uk-text-success">'.JText::_("GURU_QUIZ_PASSED").'</span>';
                                                    }
                                                    elseif($res < $result_maxs){
                                                        echo $res.'%'.' &bull; <span class="uk-text-tiny font uk-text-danger"">'.JText::_("GURU_QUIZ_FAILED").'</span>';
                                                    }
                                                }
                                                elseif(($result !=0 && $result !="")){
                                                    echo JText::_("GURU_NOT_TAKEN");
                                                }
                                                elseif($result ==0 || $result ==""){
                                                    echo JText::_("GURU_NO_FINAL_EXAM");
                                                }
                                                
                                             ?>
                                        </td>
        
                                     <td class="g_cell_6 uk-text-center" nowrap="nowrap">
        
                                 <?php
                                    //--------------hascertificate calculation-------------------
                                    if($certterm == 1 || $certterm == 0){
                                        $hascertficate = false;
                                    }
									
                                    if($certterm == 2){
                                        if($completed_course == true){
                                            $hascertficate = true;
                                        }
                                        else{
                                            $hascertficate = false;
                                        }
                                    }
                                    elseif($certterm == 3){
                                        if( $res >= $result_maxs){
											$hascertficate = true;
                                        }
                                        else{
                                            $hascertficate = false;
                                        }
                                    }
                                    elseif($certterm == 4){
                                        if($scores_avg_quizzes >= intval($avg_quizzes_cert)){
                                            $hascertficate = true;
                                        }
                                        else{
                                            $hascertficate = false;
                                        }
                                    }
                                    elseif($certterm == 5){
                                        if($completed_course==true && isset($result_maxs) && $res >= intval($result_maxs)){
                                            $hascertficate = true;
                                        }
                                        else{
                                            $hascertficate = false;
                                        }
                                    }
                                    elseif($certterm == 6){
                                        if($completed_course==true && isset($scores_avg_quizzes) && ($scores_avg_quizzes >= intval($avg_quizzes_cert))){
                                            $hascertficate = true;
                                        }
                                        else{
                                            $hascertficate = false;
                                        }
                                    }
                                    elseif($certterm == 7){
                                        if($completed_course == true){
                                            $hascertficate = true;
                                        }
                                        else{
                                            $hascertficate = false;
                                        }
                                    }
                                    
                                    //-----------------------------------------------------------
									if(!isset($res)){
										$res = 0;
									}
									
									if( $hascertficate && !in_array($id_final_exam, $certcourseidlist) ){
										$db = JFactory::getDbo();
										
										$sql = "select `author` from #__guru_program where `id`=".intval($course["course_id"]);
										$db->setQuery($sql);
										$db->execute();
										$course_author = $db->loadColumn();
										$course_author = @$course_author["0"];
										$course_author = explode("|", $course_author);
										$certificate_course_author = 0;
										
										foreach($course_author as $key_author=>$value_author){
											if(intval($value_author) != 0){
												$certificate_course_author = intval($value_author);
												break;
											}
										}
										
										$joomla_user = JFactory::getUser();
										$jnow = new JDate('now');
										$current_date_cert = $jnow->toSQL();
										
										// check if certificate already added
										$sql = "select count(*) from #__guru_mycertificates where `course_id`=".intval($course["course_id"])." and `author_id`=".intval($certificate_course_author)." and `user_id`=".intval($joomla_user->id);
										$db->setQuery($sql);
										$db->execute();
										$count_certificates = $db->loadColumn();
										$count_certificates = @$count_certificates["0"];
										
										if(intval($count_certificates) == 0){
											$sql = 'insert into #__guru_mycertificates (`course_id`, `author_id`, `user_id`, `emailcert`, `datecertificate`) values ("'.intval($course["course_id"]).'", "'.intval($certificate_course_author).'", "'.intval($joomla_user->id).'", \'0\', "'.$current_date_cert.'")';
											$db->setQuery($sql);
											$db->execute();
										}
										
										$certcourseidlist[] = $id_final_exam;
										$certcourseidlist[] = $id;
									}
									
									if($hascertficate == false ){
										if($certterm == 0){
											$span = JText::_("GURU_NO_CERT_MYC");
										}
										elseif($certterm == 1){
											$span = JText::_("GURU_NO_CERT_MYC");
										}
										elseif($certterm == 2){
											$span = JText::_("GURU_ALLLESS_CERT_MYC");
										}
										elseif($certterm == 3){
											if($res == ""){
												$span =  JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%,".JText::_("GURU_YOUR_SCORE_IS2");
											}
											elseif(isset($result_maxs) && $res < intval($result_maxs) && $failed == "1"){
												$span =  JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%, ".JText::_("GURU_FINAL_FAILED");
											}
											elseif(isset($result_maxs) && $res < intval($result_maxs)){
												$span =  JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%,".JText::_("GURU_YOUR_SCORE_IS") ." ".$res."%";
											}
											else{
												$span = JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%,".JText::_("GURU_YOUR_SCORE_IS2");
											}
										}
										elseif($certterm == 4){
											if(isset($scores_avg_quizzes) && ($scores_avg_quizzes < intval($avg_quizzes_cert))){
												$span = JText::_("GURU_PASSAVG")." ".$avg_quizzes_cert."%,".JText::_("GURU_YOUR_SCORE_WAS")." ".$scores_avg_quizzes."%";
											}
											elseif($scores_avg_quizzes == null){
												$span = JText::_("GURU_PASSAVG")." ".$avg_quizzes_cert."%,".JText::_("GURU_YOUR_SCORE_WAS2");
											}
											
										}
										elseif($certterm == 5){
											if($completed_course==true && isset($result_maxs) && $res < intval($result_maxs)){
												$span =  JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE2") ." ".$res."%";
											}
											elseif($completed_course==false && isset($result_maxs) && $result_maxs < intval($res)){
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE4");
		
											}
											elseif($completed_course==false && isset($result_maxs) && $res < intval($result_maxs)){
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE3")." ".$res."%";
											}
											elseif($completed_course==false && $res == ""){
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE5")." ".$res."%";
											}
										}
										elseif($certterm == 6){
											if($completed_course==true && isset($scores_avg_quizzes) && ($scores_avg_quizzes < intval($avg_quizzes_cert))){
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG2")." ".$scores_avg_quizzes."%";
											}
											elseif($completed_course==false && isset($scores_avg_quizzes) && ($avg_quizzes_cert < intval($scores_avg_quizzes))){
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG4");
		
											}
											elseif($completed_course==false && $scores_avg_quizzes == ""){
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG5");
											}								
											else{
												$span = JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG3")." ".$scores_avg_quizzes."%";
											}
										}
                                        elseif($certterm == 7){
                                            $joomla_user = JFactory::getUser();
                                            $sql = "select `viewed_time` from #__guru_viewed_lesson where `user_id`=".intval($joomla_user->id)." and `pid`=".intval($course["course_id"]);
                                            $db->setQuery($sql);
                                            $db->execute();
                                            $viewed_time = $db->loadColumn();
                                            $viewed_time = @$viewed_time["0"];

                                            $span = JText::_("GURU_YOUR_TIME")." ";
                                            
                                            if(isset($viewed_time)){
                                                $saved_time = explode(":", $viewed_time);
                                                $saved_sec = $saved_time["2"];
                                                $saved_min = $saved_time["1"];
                                                $saved_hour = $saved_time["0"];

                                                $span .= $saved_hour.":".$saved_min.":".$saved_sec;
                                            }
                                        }
										?>
											<span title="<?php echo $span; ?>" uk-tooltip="pos: top-center; cls: uk-active uk-text-center uk-border-rounded uk-text-tiny;" class="uk-text-tiny font uk-text-danger"><?php echo  JText::_("GURU_NOT_ELIGIBLE");?></span><br/>


										<?php
									}
									elseif(in_array($id_final_exam, $certcourseidlist) && ($hascertficate == true || $hascertficate == 1)){
										$replace_text = array("'", '"');
										$replace_with = array("&acute;", "&quot;"); ?>
                                         <div class="uk-button-group">
                                             <a href="#" onclick="openWinCertificate1('<?php echo str_replace($replace_text, $replace_with,$course_details[0]["name"])?>','<?php echo str_replace($replace_text, $replace_with,$author_name); ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')" title="<?php echo JText::_("GURU_VIEW_TOOLTIP"); ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="pos: top-center; cls: uk-active font uk-text-center uk-border-rounded uk-text-tiny uk-text-capitalize;"><i class="fas fa-eye"></i></a>
                                             <a href="#" onclick="openWinCertificate4('<?php echo str_replace($replace_text, $replace_with,$course_details[0]["name"])?>','<?php echo str_replace($replace_text, $replace_with,$author_name); ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')" title="<?php echo JText::_("GURU_DLD_TOOLTIP"); ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="pos: top-center; cls: uk-active font uk-text-center uk-border-rounded uk-text-tiny uk-text-capitalize;"><i class="fas fa-file-pdf"></i></a>
                                             <a href="#" onclick="openWinCertificate3('<?php echo str_replace($replace_text, $replace_with,$course_details[0]["name"])?>','<?php echo str_replace($replace_text, $replace_with,$author_name); ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')" title="<?php echo JText::_("GURU_LINK_TOOLTIP"); ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="pos: top-center; cls: uk-active font uk-text-center uk-border-rounded uk-text-tiny uk-text-capitalize;"><i class="fas fa-link"></i></a>
                                             <?php /* ?><a href="#" onclick="openWinCertificate2('<?php echo str_replace($replace_text, $replace_with,$course_details[0]["name"])?>','<?php echo str_replace($replace_text, $replace_with,$author_name); ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')" title="<?php echo JText::_("GURU_EMAIL_TOOLTIP"); ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="pos: top-center; cls: uk-active font uk-text-center uk-border-rounded uk-text-tiny uk-text-capitalize;"><i class="fas fa-envelope"></i></a><?php */ ?>
                                         </div>
                                          <?php } ?>
                                     </td>
                                    </tr>
                    <?php	
                            }
                            $k++;
                        }
                    ?>
            </table>

            </div>
            <div class="uk-width-1-1 uk-width-1-4@m">
                <?php
                echo $div_menu;
                echo $page_title_cart;
                ?>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_guru" />
        <input type="hidden" name="controller" value="guruOrders" />
        <input type="hidden" name="task" value="mycertificates" />
        <input type="hidden" name="order_id" value="" />
        <input type="hidden" name="course_id" value="" />
    </form>
</div>