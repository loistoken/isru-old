<?php

/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com/forum/index/
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
include_once(JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_guru".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."helper.php");
$helper = new guruHelper();
$div_menu = $helper->createStudentMenu();
$page_title_cart = $helper->createPageTitleAndCart();
$document = JFactory::getDocument();
$guruHelper = new guruHelper;
$document->setTitle(trim(JText::_('GURU_MY_PROJECTS')));

JHTML::_('behavior.tooltip');

//$document->addScript('components/com_guru/js/guru_modal.js');
$document->addStyleSheet('components/com_guru/css/tabs.css');

$Itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");

$itemid_seo = $helper->getSeoItemid();
$itemid_seo = @$itemid_seo["guruorders"];

if(intval($itemid_seo) > 0){
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
            $Itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
        }
    }
}

?>

    <div class="g_sect">
        <div class="g_table_wrap">
            <form action="<?php echo JRoute::_('index.php?option=com_guru&view=guruorders&layout=myprojects')?>" class="form-horizontal" id="adminForm" method="get" name="adminForm" enctype="multipart/form-data">

                <div class="" uk-grid>
                    <div class="uk-width-1-1 uk-width-3-4@m">

                        <?php /* ?>
                <div class="gru-page-filters">
                    <div class="gru-filter-item">
                        <select class="uk-form-width-small" name="filter_course_id" id="filter_course_id">
                            <option value="0"><?php echo JText::_("GURU_SELECT_COURSE");?></option>
                            <?php
                                if(!empty($this->my_courses)){
                                    foreach($this->my_courses as $key=>$course){
                                        $selected = "";
                                        if($course["id"] == $this->filter_course_id){
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="'.$course["course_id"].'" '.$selected.'>'.$course["course_name"].'</option>';
                                    }
                                }
                            ?>                  
                        </select>
                    </div>
                    <div class="gru-filter-item">
                        <input type="text" class="form-control" value="<?php echo $this->filter_keyword?$this->filter_keyword:''?>" name="filter_keyword" placeholder="<?php echo JText::_('GURU_SEARCHTXT')?>">
                        <button class="uk-button uk-button-primary hidden-phone" type="submit"><?php echo JText::_('GURU_SEARCHTXT')?></button>
                    </div>
                </div>
                        <?php */ ?>


                <table id="g_authorcourse" class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables">
                    <thead>
                    <tr>
                        <th class="font uk-text-left g_cell_1"><?php echo JText::_('GURU_COURSE_NAMEF')?></th>
                        <th class="font uk-text-center g_cell_2"><?php echo JText::_('GURU_PROGRES_LESSON')?></th>
                        <th class="font uk-text-center g_cell_3"><?php echo JText::_('GURU_PROJECT')?></th>
                        <th class="font uk-text-center g_cell_4"><?php echo JText::_('GURU_FILE')?></th>
                        <th class="font uk-text-center g_cell_5"><?php echo JText::_('GURU_SCORE')?></th>
                    </tr>
                    </thead>
                        <tbody>
                        <?php if(!empty($this->my_projects)):?>
                        <?php foreach ($this->my_projects as $my_project) :?>
                            <tr class="guru_row">   
                                <td class="guru_product_name g_cell_1">
                                    <a class="font uk-text-small mainTitle" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".$my_project["course_id"]."-".$my_project["course_alias"]."&Itemid=".$Itemid); ?>">
                                    <?php
                                        echo $my_project["course_name"];
                                    ?>
                                    </a>
                                </td>

                                <td class="guru_product_name g_cell_1 uk-text-center uk-text-black font uk-text-small">
                                    <?php
                                        echo $my_project["lesson_name"];
                                    ?>
                                </td>

                                <td class="guru_product_name g_cell_1 uk-text-center uk-text-black font uk-text-small">
                                    <?php
                                        echo $my_project["project_name"];
                                    ?>
                                </td>

                                <td class="guru_product_name g_cell_1 uk-width-1-4">
                                    <a title="<?php echo $my_project["file"]; ?>" class="uk-text-capitalize uk-button uk-button-small uk-button-cyan uk-border-rounded uk-width-1-1" href="<?php echo JURI::root()."index.php?option=com_guru&view=guruProjects&task=download&cid=".intval($my_project["id"]); ?>">
                                        Download File
                                    </a>
                                </td>

                                <td class="guru_product_name g_cell_1 uk-text-center uk-text-black font uk-text-small">
                                    <?php
                                        echo $my_project["score"];
                                    ?>
                                </td>
                           </tr>
                        <?php endforeach?>
                    <?php endif?>
                    </tbody>
                    </table>

                    </div>
                    <div class="uk-width-1-1 uk-width-1-4@m">
                        <?php
                        echo $div_menu;
                        echo $page_title_cart;
                        ?>
                    </div>
                </div>

           <?php
           /* echo $this->pagination->getLimitBox(); */
           $pages = $this->pagination->getPagesLinks();
           //echo $this->pagination->getListFooter();
           include_once(JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_guru".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."helper.php");
           $helper = new guruHelper();
           $pages = $helper->transformPagination($pages);
           echo $pages;
            ?>
            <div class="pagination pagination-centered uk-hidden"><input type="hidden" value="0" name="limitstart"></div>
            <input type="hidden" name="task" id="layout" value="myprojects">
            <input type="hidden" name="option" value="com_guru">
            <input type="hidden" name="view" id="view" value="guruorders">
            <?php echo JHTML::_('form.token'); ?>
        </form>
        </div>
    </div>
