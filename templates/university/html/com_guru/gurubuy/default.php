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
$document->addScript(JURI::base()."components/com_guru/js/buy.js");
JHTML::_('behavior.modal');
require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

require_once (JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
$guruHelper = new guruHelper();

$total = "";

$session = JFactory::getSession();
$registry = $session->get('registry');
$order_id_session = $registry->get('order_id', NULL);
$promo_code_session = $registry->get('promo_code', NULL);

$order_id = isset($order_id_session) ? intval($order_id_session) : "";
$promocode = "";

if(isset($promo_code_session)){
	$promocode = $promo_code_session;
}
$guruModelguruBuy = new guruModelguruBuy();
$configs = $guruModelguruBuy->getConfigs();
$currency = $configs["0"]["currency"];

$currencypos = $configs["0"]["currencypos"];
$character = "GURU_CURRENCY_".$currency;
$action = JFactory::getApplication()->input->get("action", "", "raw");

$all_product = array();

$courses_from_cart = $registry->get('courses_from_cart', NULL);
$renew_courses_from_cart = $registry->get('renew_courses_from_cart', NULL);

if($action == ""){
	if(isset($courses_from_cart)){
		$all_product = $courses_from_cart;
	}
}
else{
	$all_product = $renew_courses_from_cart;
}

$user = JFactory::getUser();
$user_id = $user->id;
if($user_id != "0" && $action == ""){
	$all_product = $this->refreshCoursesFromCart($all_product);
}

$action2 = JFactory::getApplication()->input->get("action2", "");
if($action != "renew"){
	foreach($all_product as $key=>$value){
		$course_details = $guruModelguruBuy->getCourseDetails(@$value["course_id"]);
		
		if(is_array($course_details) && count($course_details) == 0){
			$courses_from_cart = $registry->get('courses_from_cart', array());
			if(isset($value["course_id"]) && isset($courses_from_cart[$value["course_id"]])){
				unset($courses_from_cart[$value["course_id"]]);
				$registry->set('courses_from_cart', $courses_from_cart);
			}
		}
	}
	$courses_from_cart = $registry->get('courses_from_cart', array());
	$all_product = $courses_from_cart;
}
$document->setTitle(JText::_("GURU_MY_CART"));

$db = JFactory::getDBO();
$sql = "select courses_ids from #__guru_promos where code="."'".$promocode."'";
$db->setQuery($sql);
$db->execute();
$courses_ids_list = $db->loadColumn();
$courses_ids_list2 = implode(",",$courses_ids_list);
$courses_ids_list3 = explode("||",$courses_ids_list2);
$counter = 0;
if(trim($action2) != ""){
	$order_id = JFactory::getApplication()->input->get("order_id", "0");
	$db = JFactory::getDBO();
	$sql = "select form from #__guru_order where id=".intval($order_id);
	$db->setQuery($sql);
	$db->execute();
	$form = $db->loadResult();
	echo $form;	
}
elseif(isset($all_product) && count($all_product) > 0){
?>

<!-- <script type="text/javascript" src="<?php //echo JURI::root(); ?>media/system/js/mootools-core.js"></script>
<script type="text/javascript" src="<?php //echo JURI::root(); ?>media/system/js/core.js"></script>
<script type="text/javascript" src="<?php //echo JURI::root(); ?>media/system/js/mootools-more.js"></script> -->

<div id="guru_cart" class="gru-cart">
    <form action="<?php echo JURI::root()."index.php?option=com_guru&view=gurubuy"; ?>" id="adminForm" name="adminForm" method="post">


        <div uk-grid>
            <div class="uk-width-1-1 uk-width-3-4@m">
                <div>
                    <table id="g_table_cart" class="uk-table uk-table-divider uk-table-hover uk-table-small uk-table-middle uk-table-responsive profileTables">
                        <thead>
                        <th class="font uk-text-center uk-table-shrink">&nbsp;</th>
                        <th class="font uk-text-left uk-table-expand"><?php echo JText::_("GURU_COURSE_NAME"); ?></th>
                        <th class="font uk-text-center hidden-phone uk-table-shrink"><?php echo JText::_("GURU_SELECT_PLAN"); ?></th>
                        <th class="font uk-text-center hidden-phone uk-width-1-6"><?php echo JText::_("GURU_MYORDERS_AMOUNT"); ?></th>
                        <th class="font uk-text-center hidden-phone uk-hidden"><?php echo JText::_("GURU_TOTAL"); ?></th>
                        </thead>

                        <?php
                        if(isset($all_product) && is_array($all_product) && count($all_product) > 0){
                            $j = 1;
                            $all_ids = array();

                            foreach($all_product as $key=>$value){
                                $all_ids[] = $key;
                            }

                            $all_ids = implode(",", $all_ids);
                            $price = 0;
                            $total_price = 0;
                            $poz = 0;

                            foreach($all_product as $key=>$value){
                                $course_details = $guruModelguruBuy->getCourseDetails(@$value["course_id"]);
                                $course_plans = $guruModelguruBuy->getCoursePlans(@$value["course_id"], @$value["plan"]);

                                if(isset($course_details["0"]["name"]) || @$course_details["0"]["name"] !=""){
                                    ?>
                                    <tr id="row_<?php echo intval(@$value["course_id"]); ?>" class="tr2">
                                        <td class="g_cell_3 uk-table-shrink uk-text-center uk-text-left@m">
                                            <?php
                                            $action_for_request = "buy";
                                            if(trim($action) == "renew"){
                                                $action_for_request = "renew";
                                            }
                                            ?>
                                            <a class="uk-button uk-button-danger uk-button-small uk-border-rounded" href="javascript:void(0)" name="remove" onclick="javascript:removeCourse(<?php echo intval($value["course_id"]); ?>, '<?php echo $all_ids; ?>', '<?php echo $action_for_request; ?>', '<?php echo addslashes(JText::_("GURU_CART_IS_EMPTY")); ?>', '<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs"); ?>', '<?php echo addslashes(JText::_("GURU_CLICK_HERE_TO_PURCHASE")); ?>', '<?php echo trim(JText::_($character)); ?>');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>

                                        <td class="g_cell_1 font uk-text-small uk-text-black uk-text-center uk-text-left@m uk-table-expand">
                                            <?php
                                            if(isset($course_details["0"]["name"])){
                                                echo $course_details["0"]["name"];
                                            }
                                            ?>
                                        </td>

                                        <td class="uk-table-shrink">
                                            <?php
                                            echo '<select class="uk-width-1-1 uk-width-auto@m uk-select uk-border-rounded uk-text-small" onchange="update_cart('.$value["course_id"].', this.value, \''.$all_ids.'\', \''.trim(JText::_($character)).'\')" id="plan_id'.$value["course_id"].'" name="plan_id['.$value["course_id"].']">';

                                            if(isset($course_plans) && count($course_plans) > 0){
                                                $find = FALSE;
                                                $poz = -1;

                                                foreach($course_plans as $key_plan=>$value_plan){
                                                    $selected = "";

                                                    if($value_plan["default"] == "1" && $value["value"] == "" && $value["plan"] == "buy" && !$find){
                                                        $price = $value_plan["price"];
                                                        $total_price = $price;
                                                        @$total += $total_price;
                                                        $selected = ' selected="selected "';
                                                        $find = TRUE;
                                                        $poz = $key_plan;
                                                    }
                                                    elseif($value_plan["default"] == "1" && $value["value"] == "" && $value["plan"] == "renew" && !$find){
                                                        $price = $value_plan["price"];
                                                        $total_price = $price;
                                                        @$total += $total_price;
                                                        $selected = ' selected="selected "';
                                                        $find = TRUE;
                                                        $poz = $key_plan;
                                                    }
                                                    elseif($value_plan["price"] == $value["value"] && !$find){
                                                        $price = $value_plan["price"];
                                                        $total_price = $price;
                                                        @$total += $total_price;
                                                        $selected = ' selected="selected "';
                                                        $find = TRUE;
                                                        $poz = $key_plan;
                                                    }

                                                    if($currencypos == 0){
                                                        echo '<option onclick="document.getElementById(\'plan_selected_'.$value["course_id"].'\').value=\''.md5('guru-poz-'.$key_plan).'\';" value="'.$value_plan["plan_id"].'" '.$selected.' >'.$value_plan["name"].' - '.JText::_($character).' '.$guruHelper->displayPrice($value_plan["price"]).'</option>';
                                                    }
                                                    else{
                                                        echo '<option onclick="document.getElementById(\'plan_selected_'.$value["course_id"].'\').value=\''.md5('guru-poz-'.$key_plan).'\';" value="'.$value_plan["plan_id"].'" '.$selected.' >'.$value_plan["name"].' - '.$guruHelper->displayPrice($value_plan["price"]).' '.JText::_($character).'</option>';
                                                    }
                                                }
                                            }
                                            echo '</select>';

                                            echo '<input type="hidden" id="plan_selected_'.$value["course_id"].'" name="plan_selected['.$value["course_id"].']" value="'.md5('guru-poz-'.$poz).'">';
                                            ?>
                                        </td>

                                        <td class="g_cell_2 hidden-phone font uk-text-small uk-text-black uk-text-center">
                                        <span class="guru_cart_amount" id="cart_item_price<?php echo $value["course_id"]; ?>" >
                                        <?php
                                        if($currencypos == 0){
                                            echo JText::_($character)." ".$guruHelper->displayPrice($price);
                                        }
                                        else{
                                            echo $guruHelper->displayPrice($price)." ".JText::_($character);
                                        }
                                        ?>
                                        </span>
                                        </td>

                                        <td class="g_cell_4 hidden-phone font uk-text-small uk-text-black uk-text-center uk-hidden">
                                            <ul>
                                                <li class="guru_cart_amount">
                                                <span id="cart_item_total<?php echo $value["course_id"]; ?>">
                                                <?php
                                                if($currencypos == 0){
                                                    echo JText::_($character)." ".$guruHelper->displayPrice($total_price);
                                                }
                                                else{
                                                    echo $guruHelper->displayPrice($total_price)." ".JText::_($character);
                                                }
                                                ?>
                                                </span>
                                                </li>
                                                <?php

                                                if(in_array($value["course_id"],$courses_ids_list3 )){
                                                    $counter +=1;
                                                    ?>
                                                    <li class="guru_cart_amount_discount">
                                                    <span id="guru_cart_amount_discount<?php echo $value["course_id"]; ?>">
                                                        <?php
                                                        echo JText::_("GURU_DISCOUNT").": ";
                                                        $promo_discount_percourse = $this->getPromoDiscountCourse($total_price);

                                                        $promo_discount_percourse = number_format((float)$promo_discount_percourse, 2, '.', '');

                                                        if($currencypos == 0){
                                                            echo JText::_($character)." ".$guruHelper->displayPrice($promo_discount_percourse);
                                                        }
                                                        else{
                                                            echo $guruHelper->displayPrice($promo_discount_percourse)." ".JText::_($character);
                                                        }
                                                        ?>
                                                    </span>
                                                    </li>
                                                    <li class="guru_cart_amount_discount">
                                                    <span id="guru_cart_amount_discount<?php echo $value["course_id"]; ?>">
                                                    <?php
                                                    echo JText::_("GURU_TOTAL").": ";
                                                    $total_final = $this->setPromoTest($total_price, $counter);

                                                    $total_final = number_format((float)$total_final, 2, '.', '');

                                                    if($currencypos == 0){
                                                        echo JText::_($character)." ".$guruHelper->displayPrice($total_final);
                                                    }
                                                    else{
                                                        echo $guruHelper->displayPrice($total_final)." ".JText::_($character);
                                                    }
                                                    ?>
                                                    </span>
                                                    </li>
                                                    <?php
                                                }
                                                else{
                                                    if(!isset($courses_ids_list3) || count($courses_ids_list3) == 0 || intval($courses_ids_list3["0"])== 0){
                                                        $counter +=1;
                                                        $promo_discount_percourse = $this->getPromoDiscountCourse($total_price);
                                                        $total_final = $this->setPromoTest($total_price, $counter);
                                                    }
                                                    else{
                                                        $total_final = $total_price;
                                                        $promo_discount_percourse = 0;
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                    </tr><!--end table row 2-->
                                    <?php
                                }

                                @$total_finish += $total_final;
                                @$totall_discount += $promo_discount_percourse;
                                $j = $j == 1 ? 2 : 1;

                                if(isset($course_plans) && count($course_plans) > 0){
                                    foreach($course_plans as $key_plan=>$value_plan){
                                        echo '<input type="hidden" id="plan_selected_value_'.$value_plan["plan_id"].'_'.$value["course_id"].'" value="'.$value_plan["price"].'">';
                                    }
                                }

                                if($value["plan"] == "renew"){
                                    echo '<input type="hidden" name="plan_selected_renew_'.$value["course_id"].'" value="renew">';
                                }
                            }// foreach
                        }// all products
                        ?>
                    </table>
                </div>
                <hr class="uk-margin-large uk-margin-remove-horizontal">
                <div class="uk-alert uk-padding-small uk-border-rounded">
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-1 uk-width-3-4@m"><input placeholder="<?php echo JText::_("GURU_BUY_PROMO"); ?>" type="text" class="uk-input uk-border-rounded" value="<?php echo $promocode; ?>" name="promocode" /></div>
                        <div class="uk-width-1-1 uk-width-1-4@m"><input type="submit" class="uk-button uk-button-cyan uk-width-1-1 uk-border-rounded" value="<?php echo JText::_("GURU_RECALCULATE"); ?>" name="Submit"  onclick="document.adminForm.task.value='updatecart'" /></div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1 uk-width-1-4@m">

                <div uk-sticky="offset: 81">
                    <aside>
                        <div class="uk-card uk-card-body uk-card-default uk-border-rounded uk-card-small">
                            <h4 class="title_guru font">Summery :</h4>
                            <ul class="category-module uk-child-width-1-1 uk-padding-remove uk-grid-small uk-grid-divider" uk-grid>
                                <?php if($counter > 0){?>
                                    <li class="uk-text-12 font"><span class="uk-text-muted"><?php echo JText::_("GURU_DISCOUNT"); ?> : </span>
                                        <span class="uk-text-black">
                                        <?php
                                        $totall_discount = number_format((float)$totall_discount, 2, '.', '');

                                        if($currencypos == 0){
                                            echo JText::_($character)." ".$guruHelper->displayPrice($totall_discount);
                                        }
                                        else{
                                            echo $guruHelper->displayPrice($totall_discount)." ".JText::_($character);
                                        }

                                        $session = JFactory::getSession();
                                        $registry = $session->get('registry');
                                        $registry->set('discount_value', $totall_discount);
                                        ?>
                                            </span>
                                    </li>
                                <?php } ?>
                                <li class="uk-text-12 font">
                                    <span class="uk-text-muted"><?php echo JText::_("GURU_TOTAL"); ?> : </span>
                                    <span id="max_total" class="uk-text-black">
                                    <?php
                                    if($counter >0){
                                        $total_finish = number_format((float)$total_finish, 2, '.', '');

                                        if($currencypos == 0){
                                            echo JText::_($character)." ".$guruHelper->displayPrice($total_finish);
                                        }
                                        else{
                                            echo $guruHelper->displayPrice($total_finish)." ".JText::_($character);
                                        }
                                    }
                                    else{
                                        if(trim($total) != ""){
                                            $total = number_format((float)$total, 2, '.', '');

                                            $session = JFactory::getSession();
                                            $registry = $session->get('registry');
                                            $max_total = $registry->get('max_total', NULL);

                                            if(!isset($max_total)){
                                                if($currencypos == 0){
                                                    echo JText::_($character)." ".$guruHelper->displayPrice($total);
                                                }
                                                else{
                                                    echo $guruHelper->displayPrice($total)." ".JText::_($character);
                                                }
                                            }
                                            elseif($max_total != $total){
                                                $registry->set('max_total', $total);

                                                $total = number_format((float)$total, 2, '.', '');

                                                if($currencypos == 0){
                                                    echo JText::_($character)." ".$guruHelper->displayPrice($total);
                                                }
                                                else{
                                                    echo $guruHelper->displayPrice($total)." ".JText::_($character);
                                                }
                                            }
                                            else{
                                                $registry->set('max_total', number_format((float)$max_total, 2, '.', ''));

                                                if($currencypos == 0){
                                                    echo JText::_($character)." ".$guruHelper->displayPrice($max_total);
                                                }
                                                else{
                                                    echo $guruHelper->displayPrice($max_total)." ".JText::_($character);
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </span>
                                </li>
                            </ul>
                            <div id="g_myCart_payment" class="guru-payment-method">
                                <div class="uk-alert uk-border-rounded uk-padding-small uk-margin-top uk-margin-remove-bottom uk-text-tiny uk-text-capitalize uk-text-muted font"><?php echo $this->getPlugins(); ?></div>
                            </div>
                            <div>
                                <input type="button" class="uk-button uk-button-secondary uk-width-1-1 uk-margin-top uk-border-rounded" value="<?php echo JText::_("GURU_CHECKOUT"); ?>" name="checkout" onclick="document.adminForm.submit();"/>
                                <input type="button" class="uk-hidden" onclick="window.location='<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs"); ?>';" value="<?php echo JText::_("GURU_CONTINUE_SHOPPING"); ?>" name="continue"/>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
	
    	<input type="hidden" name="option" value="com_guru" />
		<input type="hidden" name="controller" value="guruBuy" />
		<input type="hidden" name="task" value="checkout" />
		<input type="hidden" name="view" value="test" />
		<input type="hidden" name="order_id" id="order_id" value="<?php echo intval($order_id); ?>"/>
		<input type="hidden" value="<?php echo $action; ?>" id="action" name="action" />
        
        <input type="hidden" id="thousands-separator" value="<?php echo $configs["0"]["thousands_separator"]; ?>" />
        <input type="hidden" id="decimals-separator" value="<?php echo $configs["0"]["decimals_separator"]; ?>" />
	</form>
</div>

<?php
}
else{
    echo '<div class="uk-text-center uk-margin-large-top uk-margin-large-bottom"><div class="uk-margin-bottom"><i class="fas fa-shopping-basket fa-5x uk-text-muted"></i></div>';
	echo '<p class="font uk-text-small uk-text-capitalize">'.JText::_("GURU_CART_IS_EMPTY").".".'</p><p><a class="uk-button uk-button-secondary uk-border-rounded" href="'.JRoute::_('index.php?option=com_guru&view=gurupcategs').'">'.JText::_("GURU_DISCOVER_COURSES").'</a></p>';
    echo '</div>';
}
?>

<?php

JPluginHelper::importPlugin('gurupayment');

?>