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
$doc =JFactory::getDocument();
$k = 0;
$n = count ($this->plans);

?>


 <div id="myModal" class="modal-small modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
    </div>
</div>
 <div class="container-fluid">
          <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999586&tmpl=component')" class="pull-right guru_video" href="#">
                    <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                <?php echo JText::_("GURU_PLANS_VIDEO"); ?>                  
          </a>
	</div>	
	<div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_PLANS_SETTINGS_DESCRIPTION"); ?>
	</div>
<form action="index.php" id="adminForm" name="adminForm" method="post">    
 <div class="clearfix"> </div>
 <div class="clearfix"> </div>
    <div id="editcell" >
        <table class="table table-striped table-bordered  adminlist">
            <thead>
                <tr>
                    <th width="5">
                        <input type="checkbox" onclick="Joomla.checkAll(this)" name="toggle" value="" />
                        <span class="lbl"></span>
                    </th>
                    <th width="20">
                        <?php echo JText::_('GURU_ID');?>
                    </th>
                    <th>
                        <?php echo JText::_('GURU_NAME');?>
                    </th>
                    <th>
                        <?php echo JText::_('VIEWPACKAGETERMS');?>
                    </th>
                    <th>
                        <?php echo JText::_('VIEWPLUGPUBLISH');?>
                    </th>
                </tr>
            </thead>
            <tbody>
<?php 
	for ($i = 0; $i < $n; $i++):
	$plan = $this->plans[$i];
	$id = $plan->id;
	$checked = JHTML::_('grid.id', $i, $id);
	$link = JRoute::_("index.php?option=com_guru&controller=guruSubplan&task=edit&cid[]=" . $id);
	$published = guruAdminViewguruSubplan::approve($plan, $i );
?>
	<tr class="row<?php echo $k;?>"> 
        <td>
            <?php echo $checked;?>
            <span class="lbl"></span>
		</td>
        <td>
            <?php echo $id;?>
		</td>		
        <td>
            <a class="a_guru" href="<?php echo $link;?>" ><?php echo $plan->name;?></a>
		</td>		
		<td>
            <?php 
                if($plan->term == '0') {
                    $plan->term = JText::_('GURU_UNLIMPROMO');
                }
                switch($plan->period) {
                    case 'downloads':
                        $plan->period = strtolower(JText::_('GURU_DOWNLOADS'));
                        break;
                    case 'hours':
                        $plan->period = strtolower(JText::_('GURU_EHOURS'));
                        break;
                    case 'days':
                        $plan->period = strtolower(JText::_('GURU_REAL_DAYS'));
                        break;
					case 'weeks':
                        $plan->period = strtolower(JText::_('GURU_REAL_WEEKS'));
                        break;
                    case 'months':
                        $plan->period = strtolower(JText::_('GURU_EMONTH'));
                        break;
                    case 'years':
                        $plan->period = strtolower(JText::_('GURU_EYEAR'));
                        break;
                    case 'unlimited':
                        $plan->period = NULL;
                        break;
                }
				if(trim($plan->term) == "Unlimited"){
					echo $plan->term;
				}
				else{
                	echo $plan->term.' '.$plan->period; 
				}
            ?>
		</td>	
       
		<td align="center">
            <?php echo $published; ?>
		</td>	
	</tr>
<?php 
		$k = 1 - $k;
	endfor;
?>            
                </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                    	<div id="filter-bar" class="btn-toolbar">
                            <div class="btn-group pull-left hidden-phone">
                                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                                <?php echo $this->pagination->getLimitBox(); ?>
                            </div>
                         </div>
                    	<?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <input type="hidden" name="option" value="com_guru" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="guruSubplan" />
    <input type="hidden" name="old_limit" value="<?php echo JFactory::getApplication()->input->get("limitstart"); ?>" />
</form>
<script language="javascript">
	var first = false;
	function showContentVideo(href){
	first = true;
	jQuery.ajax({
      url: href,
      success: function(response){
       jQuery( '#myModal .modal-body').html(response);
      }
    });
}

	jQuery('#myModal').on('hide', function () {
	 jQuery('div.modal-body').html('');
	});
	jQuery('body').click(function () {
		if(!first){
			jQuery('#myModal .modal-body iframe').attr('src', '');
		}
		else{
			first = false;
		}
	});
</script>