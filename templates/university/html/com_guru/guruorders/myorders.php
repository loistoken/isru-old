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
    $document = JFactory::getDocument();
    $document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
    $document->setTitle(trim(JText::_('GURU_MYORDERS_MYORDERS')));
    $guruModelguruOrder = new guruModelguruOrder();
    JHTML::_('behavior.tooltip');
    
    $data_post = JFactory::getApplication()->input->post->getArray();
    $k = 0;
    $myorders = $this->myorders;
    $Itemid = JFactory::getApplication()->input->get("Itemid", "0");
    $config = $this->getConfigSettings();
    $datetype = $this->datetype;
    $db = JFactory::getDbo();
    $user = JFactory::getUser();
    
    $helper = new guruHelper();
    $itemid_seo = $helper->getSeoItemid();
    $itemid_seo = @$itemid_seo["guruorders"];
    
    if(intval($itemid_seo) > 0){
        $Itemid = intval($itemid_seo);
    }
    
    $return_url = base64_encode("index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".intval($Itemid));
    
    if($config->gurujomsocialprofilestudent == 1){
        $link = "index.php?option=com_community&view=profile&task=edit&Itemid=".$Itemid;
    }
    else{
        $helper = new guruHelper();
        $itemid_seo = $helper->getSeoItemid();
        $itemid_seo = @$itemid_seo["guruprofile"];
        
        if(intval($itemid_seo) > 0){
            $Itemid = intval($itemid_seo);
            
            $Itemid = intval($itemid_seo);
            
            $sql = "select `access` from #__menu where `id`=".intval($Itemid);
            $db->setQuery($sql);
            $db->execute();
            $access = $db->loadColumn();
            $access = @$access["0"];
            
            if(intval($access) == 3){
                // special
                $user_groups = $user->get("groups");
                if(!in_array(8, $user_groups)){
                    $Itemid = JFactory::getApplication()->input->get("Itemid", "0");
                }
            }
        }
    
        $link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
    }

    $all_plans = $this->getPlans();
    
    include_once(JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_guru".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."helper.php");
    $helper = new guruHelper();
    $div_menu = $helper->createStudentMenu();
    $page_title_cart = $helper->createPageTitleAndCart();
    
    //$document->addScript('components/com_guru/js/guru_modal.js');
    $document->addStyleSheet('components/com_guru/css/tabs.css');
?>

<script type="text/javascript" language="javascript">
    document.body.className = document.body.className.replace("modal", "");
</script>

<div class="gru-myorders">
    <form action="index.php" name="adminForm" method="post">
        <div class="" uk-grid>
            <div class="uk-width-1-1 uk-width-3-4@m">

        <?php /* ?>
        <div class="gru-page-filters">
            <div class="gru-filter-item">
                <input type="text" class="form-control"  placeholder="<?php echo JText::_("GURU_SEARCH"); ?>" style="margin:0px;" name="search" value="<?php if(isset($data_post['search'])) echo $data_post['search'];?>" >
                <button class="uk-button uk-button-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
            </div>
        </div>
        <?php */ ?>
        
        <table class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables">
            <thead>
            <tr>
                <th class="font uk-text-nowrap uk-width-expand uk-text-left g_cell_1"><?php echo JText::_("GURU_COURSES_DETAILS"); ?></th>
                <th class="font uk-text-nowrap uk-width-1-1 uk-width-1-6@m uk-text-center hidden-phone"><?php echo JText::_("GURU_STATUS"); ?></th>
                <th class="font uk-text-nowrap uk-width-1-1 uk-width-1-6@m uk-text-center g_cell_3"><?php echo JText::_("GURU_INVOICE"); ?></th>
            </tr>
            </thead>
                <?php
                    foreach($myorders as $key=>$order){
                        $class = "odd";
                        if($k%2 != 0){
                            $class = "even";
                        }
                        $id = $order["id"];                             
                        $rec_link = JRoute::_("index.php?option=com_guru&view=guruOrders&task=showrec&orderid=".$id."&Itemid=".$Itemid);
                ?>
                        
                                <?php
                                    $courses = $order["courses"];
                                    $courses = explode("|", $courses);
                                    $date = $order["order_date"];
                                    
                                    foreach($courses as $course){
                                        $course_id_array = explode("-", $course);
                                        $course_id = $course_id_array["0"];
                                        $course_name = $guruModelguruOrder->getCourseName($course_id);
                                        if($course_name != NULL){
                                            $alias = JFilterOutput::stringURLSafe($course_name);
                                            $course_link = JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".intval($course_id)."-".$alias."&Itemid=".$Itemid);
                                            $course_link = '<a class="font uk-text-small mainTitle" href="'.$course_link.'">'.$course_name.'</a>';
                                            $plan = "";
                                            
                                            if(isset($course_id_array["1"]) && trim($course_id_array["2"]) != ""){
                                                if(isset($all_plans[trim(@$course_id_array["2"])]["term"]) && @$all_plans[trim(@$course_id["2"])]["term"] != "Unlimited"){
                                                    $period = $all_plans[trim(@$course_id_array["2"])]["name"];
                                                    if($all_plans[trim($course_id_array["2"])]["term"] <= 1 && (substr($period, -1) == "s")){
                                                        $period = substr($period, 0, -1);
                                                    }                                                           
                                                    if($all_plans[trim($course_id_array["2"])]["term"] == "0"){
                                                        $plan = " - ".JText::_("GURU_UNLIMITED");
                                                    }
                                                    else{
                                                        //$plan = " - ".$all_plans[trim($course_id_array["2"])]["term"]." ".$period;
                                                        $plan = " - ".$period;
                                                    }
                                                }
                                                else{
                                                    $plan = " - ".JText::_("GURU_UNLIMITED");
                                                }
                                            }
                                        
                                            ?>
                                            <tr>
                                                <td class="guru_product_name g_cell_1 uk-text-small uk-text-black uk-text-nowrap"><?php echo $course_link.$plan ;?>
                                            <?php   
                                             $currency = $order["currency"];
                                            $simbol = JText::_("GURU_CURRENCY_".$currency);
                                            $payed = $order["amount"];
                                            if(isset($order["amount_paid"]) && trim($order["amount_paid"]) != "" && trim($order["amount_paid"]) != "0"){
                                                $payed = $order["amount_paid"];
                                            }
                                            $edit_date = "";
                                            if($config->hour_format == 24){
                                                //$edit_date = date('m-d-Y | H:i' , strtotime($date));
                                                
                                                $format = "m-d-Y";
                                                switch($datetype){
                                                    case "d-m-Y H:i:s": $format = "d-m-Y H:i";
                                                          break;
                                                    case "d/m/Y H:i:s": $format = "d/m/Y H:i"; 
                                                          break;
                                                    case "m-d-Y H:i:s": $format = "m-d-Y H:i"; 
                                                          break;
                                                    case "m/d/Y H:i:s": $format = "m/d/Y H:i"; 
                                                          break;
                                                    case "Y-m-d H:i:s": $format = "Y-m-d H:i"; 
                                                          break;
                                                    case "Y/m/d H:i:s": $format = "Y/m/d H:i"; 
                                                          break;
                                                    case "d-m-Y": $format = "d-m-Y"; 
                                                          break;
                                                    case "d/m/Y": $format = "d/m/Y"; 
                                                          break;
                                                    case "m-d-Y": $format = "m-d-Y"; 
                                                          break;
                                                    case "m/d/Y": $format = "m/d/Y"; 
                                                          break;
                                                    case "Y-m-d": $format = "Y-m-d"; 
                                                          break;
                                                    case "Y/m/d": $format = "Y/m/d";        
                                                          break;                                      
                                                }
                                                $edit_date = JHTML::_('date', strtotime($date), $format);                                               
                                            }
                                            elseif($config->hour_format == 12){
                                                //$edit_date = date('m-d-Y | h:i A' , strtotime($date));
                                                $format = " m-d-Y ";
                                                switch($datetype){
                                                    case "d-m-Y H:i:s": $format = "d-m-Y h:i A";
                                                          break;
                                                    case "d/m/Y H:i:s": $format = "d/m/Y h:i A"; 
                                                          break;
                                                    case "m-d-Y H:i:s": $format = "m-d-Y h:i A"; 
                                                          break;
                                                    case "m/d/Y H:i:s": $format = "m/d/Y h:i A"; 
                                                          break;
                                                    case "Y-m-d H:i:s": $format = "Y-m-d h:i A"; 
                                                          break;
                                                    case "Y/m/d H:i:s": $format = "Y/m/d h:i A"; 
                                                          break;
                                                    case "d-m-Y": $format = "d-m-Y A"; 
                                                          break;
                                                    case "d/m/Y": $format = "d/m/Y A"; 
                                                          break;
                                                    case "m-d-Y": $format = "m-d-Y A"; 
                                                          break;
                                                    case "m/d/Y": $format = "m/d/Y A"; 
                                                          break;
                                                    case "Y-m-d": $format = "Y-m-d A"; 
                                                          break;
                                                    case "Y/m/d": $format = "Y/m/d A";  
                                                          break;                                          
                                                }
                                                $edit_date = JHTML::_('date', strtotime($date), $format);                                               
                                            }   
                                            ?>
                                             <br/><span class="hidden-phone uk-text-tiny uk-text-muted font"><?php echo JText::_("GURU_PURCHASED_ON"); ?> : <?php echo $edit_date; ?></span>
                                             
                                            </td>
                                           <td class="uk-text-center uk-text-nowrap uk-text-tiny uk-text-bold uk-text-<?php echo $order["status"] == 'Pending' ? 'warning' : 'success'; ?>">
                                            <?php 
                                                if($order["status"] == 'Pending'){
                                                    echo '<i class="fas fa-exclamation-triangle"></i> '.JText::_("GURU_PENDING");
                                                }
                                                else{
                                                    echo '<i class="fas fa-check"></i> '.JText::_("GURU_O_PAID");
                                                }
                                            ?>
                                           </td>
                                             
                                             <td class="g_cell_3 uk-text-nowrap">
                                                <a class="uk-button uk-width-1-1 uk-button-small uk-button-cyan uk-border-rounded uk-text-capitalize font uk-text-tiny" href="#" onclick="openMyModal(0, 0, '<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruOrders&task=showrec&orderid=<?php echo $id;?>&Itemid=<?php echo $Itemid; ?>&tmpl=component'); return false;"><?php echo JText::_("GURU_VIEW_ORDER");?></a>
                                            </td>
                                        </tr>
                                    <?php
                                        }   
                                    }
                                   
                                ?>                                          
                           
                           
                <?php   
                        $k++;
                    }
                ?>
                </table>

            </div>
            <div class="uk-width-1-1 uk-width-1-4@m">
                <?php echo $div_menu;echo $page_title_cart; ?>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_guru" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="controller" value="guruOrders" />
        <input type="hidden" name="view" value="guruOrders" />
        <input type="hidden" name="task" value="myorders" />
    </form>
</div>