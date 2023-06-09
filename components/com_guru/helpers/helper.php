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

//require_once (JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');

class guruHelper {
	function createBreacrumbs(){
		$bradcrumbs = "";
		$db = JFactory::getDBO();
		$sql = "select show_bradcrumbs from #__guru_config";
		$db->setQuery($sql);
		$db->execute();
		$result = $db->loadResult();
		$show_bradcrumbs = $result;
		$Home_link = "index.php?option=com_guru";
		$task = JFactory::getApplication()->input->get("task", "");
		
		if(trim($task) == ""){
			$task = JFactory::getApplication()->input->get("layout", "");
		}
		
		$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
				
		$helper = new guruHelper();
		$itemid_seo = $helper->getSeoItemid();
		$itemid_seo = @$itemid_seo["gurupcategs"];
		
		if(intval($itemid_seo) > 0){
			$itemid = intval($itemid_seo);
		}
		
		if($show_bradcrumbs == "1"){
			$controller = JFactory::getApplication()->input->get("controller", "");
			$position = JFactory::getApplication()->input->get("position", "");
			
			if(trim($controller) == ""){
				$controller = JFactory::getApplication()->input->get("view", "");
			}

			if($controller == "guruPcategs" && trim($position) == ""){
				$cid = JFactory::getApplication()->input->get("cid", "0");

				$catid = $cid;
				
				$sql = "select name from #__guru_category where id=".intval($cid);
				$db->setQuery($sql);
				$db->execute();
				$categ_name = $db->loadResult();
				
				$bradcrumbs .= '<ul id="g_breadcrumb" class="uk-breadcrumb breadcrumb">';
				$bradcrumbs .= 		'<li>';
				$bradcrumbs .= 			'<a class="pathway g_breadcrumb_link" href="'.JRoute::_($Home_link).'">Home</a>';
				$bradcrumbs .= 		'</li>';
				
				//start - check if this is subcategory
				$sql = "select parent_id from #__guru_categoryrel where child_id=".intval($cid);
				$db->setQuery($sql);
				$db->execute();
				$parent_id = $db->loadResult();
				$array_bradcrumbs = array();
				
				while($parent_id != ""){
					if(intval($parent_id) != "0"){
						$sql = "select name from #__guru_category where id=".intval($parent_id);
						$db->setQuery($sql);
						$db->execute();
						$parent_cat_name = $db->loadResult();

						$helper = new guruHelper();
	                    $itemid_menu = $helper->getCategMenuItem(intval($parent_id));
	                    $item_id_categ = $itemid;

	                    if(intval($itemid_menu) > 0){
	                        $item_id_categ = intval($itemid_menu);
	                    }

						$array_bradcrumbs[] = '<li><a class="pathway g_breadcrumb_link" href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$parent_id."&Itemid=".$item_id_categ).'">'.$parent_cat_name.'</a></li>';
					}

					$sql = "select parent_id from #__guru_categoryrel where child_id=".intval($parent_id);
					$db->setQuery($sql);
					$db->execute();
					$parent_id = $db->loadResult();
				}
				
				if(isset($array_bradcrumbs) && count($array_bradcrumbs) > 0){
					for($i=count($array_bradcrumbs)-1; $i>=0; $i--){
						$bradcrumbs .= $array_bradcrumbs[$i];
					}
				}
				//stop - check if this is subcategory
				
				if($task == ""){
					$bradcrumbs .= '<li>'.$categ_name.'</li>';
				}
				else{
					$pid = JFactory::getApplication()->input->get("cid", "0");
					$sql = "select name from #__guru_program where id=".intval($pid);
					$db->setQuery($sql);
					$db->execute();
					$product_name = $db->loadResult();

					$helper = new guruHelper();
                    $itemid_menu = $helper->getCategMenuItem(intval($catid));
                    $item_id_categ = $itemid;

                    if(intval($itemid_menu) > 0){
                        $item_id_categ = intval($itemid_menu);
                    }

					$bradcrumbs .= 		'<li><a class="pathway g_breadcrumb_link " href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$catid."&Itemid=".@$item_id_categ).'">'.$categ_name.'</a></li>';
				}	
				$bradcrumbs .= '</ul>';
			}
			
			if(($controller == "guruprograms" || $controller == "guruPrograms") && trim($position) == ""){
				$cid = JFactory::getApplication()->input->get("cid", "0");
				
				$sql = "select catid from #__guru_program where id=".intval($cid);
				$db->setQuery($sql);
				$db->execute();
				$catid = $db->loadResult();
				
				$itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
				$sql = "select name from #__guru_category where id=".intval($catid);
				$db->setQuery($sql);
				$db->execute();
				$categ_name = $db->loadResult();
				
				if($task != "enroll"){
					$bradcrumbs .= '<ul id="g_breadcrumb" class="uk-breadcrumb breadcrumb">';
					$bradcrumbs .= 		'<li class="breadcrumbs pathway">';
					$bradcrumbs .= 			'<a class="pathway g_breadcrumb_link" href="'.JRoute::_($Home_link).'">Home</a>';
					$bradcrumbs .= 		'</li>';
				
					//start - check if this is subcategory
					$sql = "select parent_id from #__guru_categoryrel where child_id=".intval($catid);
					$db->setQuery($sql);
					$db->execute();
					$parent_id = $db->loadResult();
					$array_bradcrumbs = array();
	
					while($parent_id != ""){
						if(intval($parent_id) != "0"){
							$sql = "select name from #__guru_category where id=".intval($parent_id);
							$db->setQuery($sql);
							$db->execute();
							$parent_cat_name = $db->loadResult();

							$helper = new guruHelper();
		                    $itemid_menu = $helper->getCategMenuItem(intval($parent_id));
		                    $item_id_categ = $itemid;

		                    if(intval($itemid_menu) > 0){
		                        $item_id_categ = intval($itemid_menu);
		                    }

							$array_bradcrumbs[] = '<li><a class="pathway g_breadcrumb_link" href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$parent_id."&Itemid=".$item_id_categ).'">'.$parent_cat_name.'</a></li>';
						}

						$sql = "select parent_id from #__guru_categoryrel where child_id=".intval($parent_id);
						$db->setQuery($sql);
						$db->execute();
						$parent_id = $db->loadResult();
					}
					
					if(isset($array_bradcrumbs) && count($array_bradcrumbs) > 0){
						for($i=count($array_bradcrumbs)-1; $i>=0; $i--){
							$bradcrumbs .= $array_bradcrumbs[$i];
						}
					}
					//stop - check if this is subcategory
					
					if($task == ""){
						$bradcrumbs .= '<li>'.$categ_name.'<li>';
					}
					else{
						$pid = JFactory::getApplication()->input->get("cid", "0");
						$sql = "select name from #__guru_program where id=".intval($pid);
						$db->setQuery($sql);
						$db->execute();
						$product_name = $db->loadResult();

						$helper = new guruHelper();
	                    $itemid_menu = $helper->getCategMenuItem(intval($catid));
	                    $item_id_categ = $itemid;

	                    if(intval($itemid_menu) > 0){
	                        $item_id_categ = intval($itemid_menu);
	                    }
						
						$bradcrumbs .= 		'<li><a class="pathway g_breadcrumb_link" href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$catid."&Itemid=".@$item_id_categ).'">'.$categ_name.'</a></li>';
						$bradcrumbs .= 		'<li>'.$product_name.'</li>';
					}
					
					$bradcrumbs .= '</ul>';
				}
			}
		}
		echo $bradcrumbs;
	}


	function getDate($date){
		$db = JFactory::getDBO();
		$sql = "select datetype from #__guru_config where id=1";
		$db->setQuery($sql);
		$db->execute();
		$format = $db->loadResult();
		
		if(trim($date) == "0000-00-00 00:00:00"){
			return $date;
		}

		$result = date($format, strtotime($date));
		return $result;
	}

	function publishAndExpiryHelper(&$img, &$alt, &$times, &$status, $timestart, $timeend, $published, $configs) {
		$now = time();
		$nullDate = 0;

		if ( $now <= $timestart && $promo->publishing == "1" ) {
	                $img = "tick.png";
        	        $alt = JText::_('HELPERPUBLISHED');
	        } else if ( ( $now <= $timeend || $timeend == $nullDate ) && $published == "1" ) {
        	        $img = "tick.png";
                	$alt = JText::_('HELPERPUBLISHED');
	        } else if ( $now > $timeend && $published == "1" && $timeend != $nullDate) {
        	        $img = "publish_r.png";
                	$alt = JText::_('HELPEREXPIRED');
	        } elseif ( $published == "0" ) {
        	        $img = "publish_x.png";
                	$alt = JText::_('HELPERUNPUBLICHED');
	        }       
  	        $times = '';
          	if (isset( $timestart)) {
          		if ( $timestart == $nullDate) {
                		$times .= "<tr><td>".(JText::_("HELPERALWAWSPUB"))."</td></tr>";
	                } else {
        		        $times .= "<tr><td>".(JText::_("HELPERSTARTAT"))." ".date($configs->time_format, $timestart)."</td></tr>";
	                }
        	}
	        if ( isset( $timeend ) ) {
        	        if ( $timeend == $nullDate) {
                		$times .= "<tr><td>".(JText::_("HELPERNEVEREXP"))."</td></tr>";
	                } else {
        		        $times .= "<tr><td>".(JText::_("HELPEXPAT"))." ".date($configs->time_format, $timeend)."</td></tr>";
	                }
        	}


                $status = '';
		if (!isset ($promo->codelimit)) {
			$promo->codelimit = 0;
		}
		if (!isset ($promo->used)) {
			$promo->used = 0;
		}

		$remain = $promo->codelimit - $promo->used;
		if (($timeend > $now || $timeend == $nullDate )&& ($remain > 0 || $promo->codelimit == 0)) {
			$status = JText::_("HELPERACTIVE");
		} else if ($timeend != $nullDate && $timeend < $now && ($remain < 1 && $promo->codelimit > 0)) {
			$status = "<span style='color:red'>".(JText::_("HELPEREXPIRE"))." (".(JText::_("Date"))." ,".(JText::_("Amount")).")</span>";
		} else if ($remain < 1 && $promo->codelimit > 0) {
			$status = "<span style='color:red'>".(JText::_("HELPEREXPIRE"))." (".(JText::_("Amount")).")</span>";
		} else if ($timeend < $now && $timeend != $nullDate){
			$status = "<span style='color:red'>".(JText::_("HELPEREXPIRE"))." (".(JText::_("Date")).")</span>";
		} else {
			$status = "<span style='color:red'>".(JText::_("HELPERPROMOERROR"))."</span>";
		}

	}

function createThumb($images, $folder, $new_size, $type, $folder_thumbs="thumbs"){
	$mosConfig_absolute_path = JPATH_ROOT;
	$mosConfig_live_site = JURI :: base();
	$folder=str_replace("/",DS,$folder);
	
	if(intval($new_size)>0){
		if(file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images)){
			$old_size = @getimagesize(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images);
			$width_old = $old_size[0];
			$height_old = $old_size[1];
			if(intval($width_old)==0 || intval($height_old)==0){
				return "";
			}
			
			if($type=='w'){
				//get the correct height
				if($width_old < $new_size){
					$width_new=$width_old;
				}	
			 	else{
					$width_new=$new_size;
				}
 			 	$height_new =intval($width_new*$height_old/$width_old);
			}
			else{
				if($height_old<$new_size){
					$height_new=$height_old;
				}	
				else{
					$height_new=$new_size;
				}	
				$width_new =intval($height_new*$width_old/$height_old); 	
				
			}
		
			if(!is_dir(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$folder_thumbs)){
				mkdir(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$folder_thumbs, 0777);
			}
			
			$images = trim($images);
			//get dir name and file name
			$get_path = explode('/',$images);
			$nr = (count($get_path) - 1);
			//get photo name
			//last value from get_path array
			$photo_name = $get_path[$nr];
			
			unset($get_path[$nr]);
			//get dir name
			$path = implode("/",$get_path);
			//@chmod($mosConfig_absolute_path.'/images/stories'.$path.,0777);					
			//see if thumbnails is created and it have same size return his name
			if(file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$folder_thumbs.DIRECTORY_SEPARATOR.$photo_name)){
				$img_size_thumb = @getimagesize(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$folder_thumbs.DIRECTORY_SEPARATOR.$photo_name);
				$width_thumb = $img_size_thumb[0];
				$height_thumb = $img_size_thumb[1];
				
				if($width_thumb == intval($width_new) || $height_thumb == intval($height_new)){
					return true;
				}
			}
		 
			$name_array = explode('.',$photo_name);
			$extension = $name_array[count($name_array)-1];
			$extension = strtolower ($extension);

			
			switch($extension){
				case "jpg":				
					$gdimg = @imagecreatefromjpeg(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images);
					break;
				case "jpeg":				
					$gdimg = @imagecreatefromjpeg(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images);
					break;
				case "gif": 
					$gdimg = @imagecreatefromgif(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images);
					break;
				case "png":
					$gdimg = @imagecreatefrompng(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images);
					break;
			}
			
			if($extension == "png"){
				$image_p = @imagecreatetruecolor($width_new, $height_new);
				@imagealphablending($image_p, false);
				@imagesavealpha($image_p, true);
				$source = @imagecreatefrompng(JPATH_SITE.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$images);
				@imagealphablending($source, true);
				@imagecopyresampled($image_p, $source, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old);
			}
			elseif($extension != 'gif'){
				$image_p = @imagecreatetruecolor($width_new, $height_new);
				$trans = @imagecolorallocate($image_p, 0,0,0);
				@imagecolortransparent($image_p, $trans);
				@imagecopyresampled($image_p, $gdimg, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old);
			}
			else{ 	
				$image_p = @imagecreate($width_new, $height_new);
				$trans = @imagecolorallocate($image_p,0,0,0);
				@imagecolortransparent($image_p,$trans);
				@imagecopyresized($image_p, $gdimg, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old);				
			}
		
			if($extension == "jpg" || $extension == "JPG"){
				$upload_th = @imagejpeg($image_p, JPATH_ROOT.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR."thumbs".DIRECTORY_SEPARATOR.$photo_name, 100);
			}
			if($extension == "jpeg" || $extension == "JPEG"){
				$upload_th = @imagejpeg($image_p, JPATH_ROOT.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR."thumbs".DIRECTORY_SEPARATOR.$photo_name, 100);			
			}
			if($extension == "gif" || $extension == "GIF"){
				$upload_th = @imagegif($image_p, JPATH_ROOT.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR."thumbs".DIRECTORY_SEPARATOR.$photo_name, 100); 
			}	
			if($extension == "png" || $extension == "PNG"){
				$upload_th = @imagepng($image_p, JPATH_ROOT.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR."thumbs".DIRECTORY_SEPARATOR.$photo_name);
			}
			
			if($upload_th){
				return true;
			}	
			else{
				return false;
			}	
		}
	}	
}    
	
	
	function create_media_using_plugin($main_media, $configs, $aheight, $awidth, $vheight, $vwidth){
		$auto_play = "";
		$tag_end = "";

		if($main_media->auto_play == "1"){
			$auto_play = "&autoplay=1";
		}
		
		if($main_media->type=='video'){			
			if($main_media->source=='code'){
				$media = $main_media->code;				
			}
			if($main_media->source=='url'){
				if(substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == "https" || substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == "HTTPS"){
						$main_media->url = str_replace("http","https",$main_media->url);
				}
				
					//$main_media->url .= $auto_play;
					
					//$position_watch = strpos($main_media->url, 'www.youtube.com/watch');
					if (strpos($main_media->url, 'www.youtube.com/watch')!==false)
					{ // youtube link - begin
						$link_array = explode('=',$main_media->url);
						$link_ = $link_array[1].$auto_play; 	
						$media = '{youtube}'.$link_.'{/youtube}';
					} // youtube link - end
					elseif(strpos($main_media->url, 'youtu.be') !== false){
						$media = '{youtube}'.$main_media->url.'{/youtube}';
					}
					elseif (strpos($main_media->url, 'www.123video.nl')!==false)
					{ // 123video.nl link - begin
						$link_array = explode('=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{123video}'.$link_.'{/123video}';			
					} // 123video.nl link - end
					elseif (strpos($main_media->url, 'www.aniboom.com')!==false)
					{ // aniboom.com link - begin
						$begin_tag = strpos($main_media->url, 'video');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{aniboom}'.$link_.'{/aniboom}';	
					} // aniboom.com link - end
					elseif (strpos($main_media->url, 'www.badjojo.com')!==false)
					{ // badjojo.com [adult] link - begin
						$link_array = explode('=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{badjojo}'.$link_.'{/badjojo}';
						echo $media;			
					} // badjojo.com [adult] link - end
					elseif (strpos($main_media->url, 'www.brightcove.tv')!==false)
					{ // brightcove.tv link - begin
						$begin_tag = strpos($main_media->url, 'title=');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '&');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{brightcove}'.$link_.'{/brightcove}';	
					} // brightcove.tv link - end
					elseif (strpos($main_media->url, 'www.collegehumor.com')!==false)
					{ // collegehumor.com link - begin
						$link_array = explode(':',$main_media->url);
						$link_ = $link_array[2]; 	
						$media = '{collegehumor}'.$link_.'{/collegehumor}';
					} // collegehumor.com link - end
					elseif (strpos($main_media->url, 'current.com')!==false)
					{ // current.com link - begin
						$begin_tag = strpos($main_media->url, 'items/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '_');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{current}'.$link_.'{/current}';	
					} // current.com link - end
					elseif (strpos($main_media->url, 'dailymotion.com')!==false)
					{ // dailymotion.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '_');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{dailymotion}'.$link_.'{/dailymotion}';	
					} // dailymotion.com link - end
					elseif (strpos($main_media->url, 'espn')!==false)
					{ // video.espn.com link - begin
						$begin_tag = strpos($main_media->url, 'videoId=');
						$remaining_link = substr($main_media->url, $begin_tag + 8, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '&');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{espn}'.$link_.'{/espn}';	
					} // video.espn.com link - end
					elseif (strpos($main_media->url, 'eyespot.com')!==false)
					{ // eyespot.com link - begin
						$link_array = explode('r=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{eyespot}'.$link_.'{/eyespot}';
					} // eyespot.com link - end
					elseif (strpos($main_media->url, 'flurl.com')!==false)
					{ // flurl.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '_');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{flurl}'.$link_.'{/flurl}';	
					} // flurl.com link - end
					elseif (strpos($main_media->url, 'funnyordie.com')!==false)
					{ // funnyordie.com link - begin
						$link_array = explode('videos/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{funnyordie}'.$link_.'{/funnyordie}';
					} // funnyordie.com link - end
					elseif (strpos($main_media->url, 'gametrailers.com')!==false)
					{ // gametrailers.com link - begin
						$begin_tag = strpos($main_media->url, 'player/');
						$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{gametrailers}'.$link_.'{/gametrailers}';	
					} // gametrailers.com link - end
					elseif (strpos($main_media->url, 'godtube.com')!==false)
					{ // godtube.com link - begin
						$link_array = explode('viewkey=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{godtube}'.$link_.'{/godtube}';
					} // godtube.com link - end
					elseif (strpos($main_media->url, 'gofish.com')!==false)
					{ // gofish.com link - begin
						$link_array = explode('gfid=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{gofish}'.$link_.'{/gofish}';
					} // gofish.com link - end
					elseif (strpos($main_media->url, 'google.com')!==false)
					{ // Google Video link - begin
						$link_array = explode('docid=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{google}'.$link_.'{/google}';
					} // Google Video link - end
					elseif (strpos($main_media->url, 'guba.com')!==false)
					{ // guba.com link - begin
						$link_array = explode('watch/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{guba}'.$link_.'{/guba}';
					} // guba.com link - end
					elseif (strpos($main_media->url, 'hook.tv')!==false)
					{ // hook.tv link - begin
						$link_array = explode('key=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{hook}'.$link_.'{/hook}';
					} // hook.tv link - end
					elseif (strpos($main_media->url, 'jumpcut.com')!==false)
					{ // jumpcut.com link - begin
						$link_array = explode('id=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{jumpcut}'.$link_.'{/jumpcut}';
					} // jumpcut.com link - end
					elseif (strpos($main_media->url, 'kewego.com')!==false)
					{ // kewego.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{kewego}'.$link_.'{/kewego}';	
					} // kewego.com link - end
					elseif (strpos($main_media->url, 'krazyshow.com')!==false)
					{ // krazyshow.com [adult] link - begin
						$link_array = explode('cid=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{krazyshow}'.$link_.'{/krazyshow}';
					} // krazyshow.com [adult] link - end
					elseif (strpos($main_media->url, 'ku6.com')!==false)
					{ // ku6.com link - begin
						$begin_tag = strpos($main_media->url, 'show/');
						$remaining_link = substr($main_media->url, $begin_tag + 5, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{ku6}'.$link_.'{/ku6}';	
					} // ku6.com link - end
					elseif (strpos($main_media->url, 'liveleak.com')!==false)
					{ // liveleak.com link - begin
						$link_array = explode('i=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{liveleak}'.$link_.'{/liveleak}';
					} // liveleak.com link - end
					elseif (strpos($main_media->url, 'metacafe.com')!==false)
					{ // metacafe.com link - begin
						$begin_tag = strpos($main_media->url, 'watch/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{metacafe}'.$link_.'{/metacafe}';	
					} // metacafe.com link - end
					elseif (strpos($main_media->url, 'mofile.com')!==false)
					{ // mofile.com link - begin
						$begin_tag = strpos($main_media->url, 'com/');
						$remaining_link = substr($main_media->url, $begin_tag + 4, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{mofile}'.$link_.'{/mofile}';	
					} // mofile.com link - end
					elseif (strpos($main_media->url, 'myspace.com')!==false)
					{ // myspace.com link - begin
						$link_array = explode('VideoID=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{myspace}'.$link_.'{/myspace}';
					} // myspace.com link - end
					elseif (strpos($main_media->url, 'myvideo.de')!==false)
					{ // myvideo.de link - begin
						$begin_tag = strpos($main_media->url, 'watch/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{myvideo}'.$link_.'{/myvideo}';	
					} // myvideo.de link - end
					elseif (strpos($main_media->url, 'redtube.com')!==false)
					{ // redtube.com [adult] link - begin
						$link_array = explode('/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{redtube}'.$link_.'{/redtube}';
					} // redtube.com [adult] - end
					elseif (strpos($main_media->url, 'revver.com')!==false)
					{ // revver.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{revver}'.$link_.'{/revver}';	
					} // revver.com link - end
					elseif (strpos($main_media->url, 'sapo.pt')!==false)
					{ // sapo.pt link - begin
						$link_array = explode('pt/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{sapo}'.$link_.'{/sapo}';
					} // sapo.pt - end
					elseif (strpos($main_media->url, 'sevenload.com')!==false)
					{ // sevenload.com link - begin
						$begin_tag = strpos($main_media->url, 'videos/');
						$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '-');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{sevenload}'.$link_.'{/sevenload}';	
					} // sevenload.com link - end
					elseif (strpos($main_media->url, 'sohu.com')!==false)
					{ // sohu.com link - begin
						$link_array = explode('/',$main_media->url);
						$link_ = $link_array[count($link_array)-1]; 	
						$media = '{sohu}'.$link_.'{/sohu}';
					} // sohu.com - end
					elseif (strpos($main_media->url, 'southparkstudios.com')!==false)
					{ // southparkstudios.com link - begin
						$begin_tag = strpos($main_media->url, 'clips/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{southpark}'.$link_.'{/southpark}';	
					} // southparkstudios.com link - end
					elseif (strpos($main_media->url, 'spike.com')!==false)
					{ // spike.com link - begin
						$link_array = explode('video/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{spike}'.$link_.'{/spike}';
					} // spike.com - end
					elseif (strpos($main_media->url, 'stickam.com')!==false)
					{ // stickam.com link - begin
						$link_array = explode('mId=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{stickam}'.$link_.'{/stickam}';
					} // stickam.com - end
					elseif (strpos($main_media->url, 'stupidvideos.com')!==false)
					{ // stupidvideos.com link - begin
						$link_array = explode('#',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{stupidvideos}'.$link_.'{/stupidvideos}';
					} // stupidvideos.com - end
					elseif (strpos($main_media->url, 'tudou.com')!==false)
					{ // tudou.com link - begin
						$begin_tag = strpos($main_media->url, 'view/');
						$remaining_link = substr($main_media->url, $begin_tag + 5, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{tudou}'.$link_.'{/tudou}';	
					} // tudou.com link - end
					elseif (strpos($main_media->url, 'ustream.tv')!==false)
					{ // ustream.tv link - begin
						$link_array = explode('recorded/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{ustream}'.$link_.'{/ustream}';
					} // ustream.tv - end
					elseif (strpos($main_media->url, 'veoh.com')!==false)
					{ // veoh.com link - begin
						$link_array = explode('videos/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{veoh}'.$link_.'{/veoh}';
					} // veoh.com - end
					elseif (strpos($main_media->url, 'videotube.de')!==false)
					{ // videotube.de link - begin
						$link_array = explode('watch/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{videotube}'.$link_.'{/videotube}';
					} // videotube.de - end
					elseif (strpos($main_media->url, 'vidiac.com')!==false)
					{ // vidiac.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{vidiac}'.$link_.'{/vidiac}';	
					} // vidiac.com link - end
					elseif (strpos($main_media->url, 'vimeo.com')!==false)
					{ // vimeo.com link - begin
						$link_array = explode('.com/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{vimeo}'.$link_.'{/vimeo}';
					} // vimeo.com - end
					elseif (strpos($main_media->url, 'yahoo.com')!==false)
					{ // video.yahoo.com link - begin
						$link_array = explode('watch/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{yahoo}'.$link_.'{/yahoo}';			
					} // video.yahoo.com - end
					elseif (strpos($main_media->url, 'youare.tv')!==false)
					{ // youare.tv link - begin
						$link_array = explode('id=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{youare}'.$link_.'{/youare}';			
					} // youare.tv - end
					elseif (strpos($main_media->url, 'youku.com')!==false)
					{ // youku.com link - begin
						$begin_tag = strpos($main_media->url, 'v_show/');
						$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{youku}'.$link_.'{/youku}';	
					} // youku.com link - end
					elseif (strpos($main_media->url, 'youmaker.com')!==false)
					{ // youmaker.com  link - begin
						$link_array = explode('id=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{youmaker}'.$link_.'{/youmaker}';			
					} // youmaker.com  - end
					else
					{
						//----------- not special link - begin
						$extension_array=explode('.',$main_media->url);
						$extension = $extension_array[count($extension_array)-1];
					
						if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='mp4' || strtolower($extension)=='divx')
							{
								$tag_begin = '{'.strtolower($extension).'remote}';
								$tag_end = '{/'.strtolower($extension).'remote}';
							}	
						if(!isset($tag_begin)) {$tag_begin=NULL;}
						if(!isset($tag_end)) {$tag_end=NULL;}
						$media = $tag_begin.$main_media->url.$auto_play.$tag_end;
						//----------- not special link - begin										
					}

					$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, 0);
				}
				
				if($main_media->source=='local'){	
					if($main_media->auto_play == "1"){
						$autoplay = 'true';
					}
					else {
						$autoplay = 'false';
					}
					
					$extension_array=explode('.',$main_media->local);
					$extension = $extension_array[count($extension_array)-1];
					
					if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='divx'){
						$tag_begin = '{'.strtolower($extension).'remote}';
						$tag_end = '{/'.strtolower($extension).'remote}';
					}
					
					if(!isset($tag_begin)) {
						$tag_begin=NULL;
					}
					
					if(!isset($tag_end)) {
						$tag_end=NULL;
					}
					
					if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='divx'){
						$media = $tag_begin.str_replace("/administrator","",JURI::base()).$configs->videoin.'/'.$main_media->local.$tag_end;
						$guru_media_autoplay = "";
						$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, $autoplay);
					}
					elseif(strtolower($extension)=='mp4'){
						$media = str_replace("/administrator", "", JURI::base()).$configs->videoin.'/'.$main_media->local;
					
						if(isset($main_media->exception) && intval($main_media->exception) == "1"){
							$media = $main_media->local;
						}
						
						$autoplay_html = "";

						if($autoplay == 'true'){
							$autoplay_html = "autoplay";
						}
						
						$media = '
							<video '.$autoplay_html.' width="100%" controls="controls" preload="metadata">
								<source src="'.$media.'" type="video/mp4" />
							</video>
						';
					}
				}
		}	
		
		if($main_media->type=='audio')
		{				
			/*if($main_media->auto_play == "1"){	
				$guru_media_autoplay = TRUE;
			}
			else{
				$guru_media_autoplay = FALSE;	
			}*/
			
			$guru_media_autoplay = FALSE;
			
			if($main_media->source=='code'){
				$media = $main_media->code;
			}
			
			if($main_media->source=='url'){
				$extension_array=explode('.',$main_media->url);
				$extension = $extension_array[count($extension_array)-1];
				
				if(strtolower($extension)=='mp3' || strtolower($extension)=='wma' || strtolower($extension)=='m4a'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				
				$awidth = "100%";
				$aheight = "24px";
				
				$media = @$tag_begin.$main_media->url.$tag_end;
				$media = guruHelper::jwAllVideos( $media, $awidth, $aheight, $vwidth, $vheight, $guru_media_autoplay);
			}
			
			if($main_media->source=='local'){
				$extension_array=explode('.',$main_media->local);
				$extension = $extension_array[count($extension_array)-1];
				
				if(strtolower($extension)=='mp3' || strtolower($extension)=='m4a' || strtolower($extension)=='wma'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				
				$awidth = "100%";
				$aheight = "24px";
				
				$media = $tag_begin.str_replace("/administrator","",JURI::base()).$configs->audioin.'/'.$main_media->local.$tag_end;					
				$media = guruHelper::jwAllVideos( $media, $awidth, $aheight, $vwidth, $vheight, $guru_media_autoplay);
			}
			
			$media = preg_replace('/height="(.*)"/msU', 'height="24px"', $media);
		}
		
		if($main_media->type=='url')
		{
			$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';	
		}		
		
		if($main_media->type=='docs')
		{
			if($main_media->source=='url')
				$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';	
			if($main_media->source=='local')
				$media = '<a target="_blank" href="'.str_replace("/administrator","",JURI::base()).'/'.$configs->docsin.'/'.$main_media->local.'">'.$main_media->name.'</a>';
		}
	
		if($main_media->type=='image'){
			$img_size = @getimagesize(JPATH_SITE.DIRECTORY_SEPARATOR.$configs->imagesin.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'thumbs'.$main_media->local);
		if(isset($img_size[0]) && isset($img_size[1])){
			$img_width = $img_size[0];
			$img_height = $img_size[1];
			if($img_width>0 && $img_height>0){ 
				if($main_media->width > 0){
					$thumb_width = $main_media->width;
					$thumb_height = $img_height / ($img_width/$main_media->width);
				}
				elseif($main_media->height > 0)	{
					$thumb_height = $main_media->height;
					$thumb_width = $img_width / ($img_height/$main_media->height);		
				}
				else{
					$thumb_height = 200;
					$thumb_width = $img_width / ($img_height/200);									
				}
			}
			
			if(isset($thumb_width) && isset($thumb_height)) {
				$media = '<img width="'.$thumb_width.'" height="'.$thumb_height.'" src="';
			} else {
				$media = '<img src="';
			}
			$media .= JUri::root()."/".$configs->imagesin.'/media/thumbs'.$main_media->local.'" />';	
		}
	}									
	
	if($main_media->type == "file"){   
 		$media = '<a target="_blank" href="'.JURI::root().$configs->filesin.'/'.$main_media->local.'">'.$main_media->name.'</a><br/><br/>'.$main_media->instructions;
	}
	
	if(isset($media)){
		return $media;
	}
	else{
		return NULL;
	}	
}	

	public function create_media_using_plugin_for_quiz($main_media, $configs, $aheight, $awidth, $vheight, $vwidth){
		$auto_play = "";
		$tag_end = "";
		if(@$main_media->auto_play == "1"){
			$auto_play = "&autoplay=1";
		}
		
		if(@$main_media->type=='video'){			
			if($main_media->source=='code'){
				$media = $main_media->code;				
			}
			if($main_media->source=='url'){
				if(substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == "https" || substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == "HTTPS"){
					$main_media->url = str_replace("http","https",$main_media->url);
				}
				//$position_watch = strpos($main_media->url, 'www.youtube.com/watch');
				if (strpos($main_media->url, 'www.youtube.com/watch')!==false)
				{ // youtube link - begin
					$link_array = explode('=',$main_media->url);
					$link_ = $link_array[1].$auto_play; 	
					$media = '{youtube}'.$link_.'{/youtube}';
				} // youtube link - end
				elseif (strpos($main_media->url, 'www.123video.nl')!==false)
				{ // 123video.nl link - begin
					$link_array = explode('=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{123video}'.$link_.'{/123video}';			
				} // 123video.nl link - end
				elseif (strpos($main_media->url, 'www.aniboom.com')!==false)
				{ // aniboom.com link - begin
					$begin_tag = strpos($main_media->url, 'video');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '/');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{aniboom}'.$link_.'{/aniboom}';	
				} // aniboom.com link - end
				elseif (strpos($main_media->url, 'www.badjojo.com')!==false)
				{ // badjojo.com [adult] link - begin
					$link_array = explode('=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{badjojo}'.$link_.'{/badjojo}';
					echo $media;			
				} // badjojo.com [adult] link - end
				elseif (strpos($main_media->url, 'www.brightcove.tv')!==false)
				{ // brightcove.tv link - begin
					$begin_tag = strpos($main_media->url, 'title=');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '&');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{brightcove}'.$link_.'{/brightcove}';	
				} // brightcove.tv link - end
				elseif (strpos($main_media->url, 'www.collegehumor.com')!==false)
				{ // collegehumor.com link - begin
					$link_array = explode(':',$main_media->url);
					$link_ = $link_array[2]; 	
					$media = '{collegehumor}'.$link_.'{/collegehumor}';
				} // collegehumor.com link - end
				elseif (strpos($main_media->url, 'current.com')!==false)
				{ // current.com link - begin
					$begin_tag = strpos($main_media->url, 'items/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '_');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{current}'.$link_.'{/current}';	
				} // current.com link - end
				elseif (strpos($main_media->url, 'dailymotion.com')!==false)
				{ // dailymotion.com link - begin
					$begin_tag = strpos($main_media->url, 'video/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '_');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{dailymotion}'.$link_.'{/dailymotion}';	
				} // dailymotion.com link - end
				elseif (strpos($main_media->url, 'espn')!==false)
				{ // video.espn.com link - begin
					$begin_tag = strpos($main_media->url, 'videoId=');
					$remaining_link = substr($main_media->url, $begin_tag + 8, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '&');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{espn}'.$link_.'{/espn}';	
				} // video.espn.com link - end
				elseif (strpos($main_media->url, 'eyespot.com')!==false)
				{ // eyespot.com link - begin
					$link_array = explode('r=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{eyespot}'.$link_.'{/eyespot}';
				} // eyespot.com link - end
				elseif (strpos($main_media->url, 'flurl.com')!==false)
				{ // flurl.com link - begin
					$begin_tag = strpos($main_media->url, 'video/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '_');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{flurl}'.$link_.'{/flurl}';	
				} // flurl.com link - end
				elseif (strpos($main_media->url, 'funnyordie.com')!==false)
				{ // funnyordie.com link - begin
					$link_array = explode('videos/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{funnyordie}'.$link_.'{/funnyordie}';
				} // funnyordie.com link - end
				elseif (strpos($main_media->url, 'gametrailers.com')!==false)
				{ // gametrailers.com link - begin
					$begin_tag = strpos($main_media->url, 'player/');
					$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '.');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{gametrailers}'.$link_.'{/gametrailers}';	
				} // gametrailers.com link - end
				elseif (strpos($main_media->url, 'godtube.com')!==false)
				{ // godtube.com link - begin
					$link_array = explode('viewkey=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{godtube}'.$link_.'{/godtube}';
				} // godtube.com link - end
				elseif (strpos($main_media->url, 'gofish.com')!==false)
				{ // gofish.com link - begin
					$link_array = explode('gfid=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{gofish}'.$link_.'{/gofish}';
				} // gofish.com link - end
				elseif (strpos($main_media->url, 'google.com')!==false)
				{ // Google Video link - begin
					$link_array = explode('docid=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{google}'.$link_.'{/google}';
				} // Google Video link - end
				elseif (strpos($main_media->url, 'guba.com')!==false)
				{ // guba.com link - begin
					$link_array = explode('watch/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{guba}'.$link_.'{/guba}';
				} // guba.com link - end
				elseif (strpos($main_media->url, 'hook.tv')!==false)
				{ // hook.tv link - begin
					$link_array = explode('key=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{hook}'.$link_.'{/hook}';
				} // hook.tv link - end
				elseif (strpos($main_media->url, 'jumpcut.com')!==false)
				{ // jumpcut.com link - begin
					$link_array = explode('id=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{jumpcut}'.$link_.'{/jumpcut}';
				} // jumpcut.com link - end
				elseif (strpos($main_media->url, 'kewego.com')!==false)
				{ // kewego.com link - begin
					$begin_tag = strpos($main_media->url, 'video/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '.');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{kewego}'.$link_.'{/kewego}';	
				} // kewego.com link - end
				elseif (strpos($main_media->url, 'krazyshow.com')!==false)
				{ // krazyshow.com [adult] link - begin
					$link_array = explode('cid=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{krazyshow}'.$link_.'{/krazyshow}';
				} // krazyshow.com [adult] link - end
				elseif (strpos($main_media->url, 'ku6.com')!==false)
				{ // ku6.com link - begin
					$begin_tag = strpos($main_media->url, 'show/');
					$remaining_link = substr($main_media->url, $begin_tag + 5, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '.');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{ku6}'.$link_.'{/ku6}';	
				} // ku6.com link - end
				elseif (strpos($main_media->url, 'liveleak.com')!==false)
				{ // liveleak.com link - begin
					$link_array = explode('i=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{liveleak}'.$link_.'{/liveleak}';
				} // liveleak.com link - end
				elseif (strpos($main_media->url, 'metacafe.com')!==false)
				{ // metacafe.com link - begin
					$begin_tag = strpos($main_media->url, 'watch/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{metacafe}'.$link_.'{/metacafe}';	
				} // metacafe.com link - end
				elseif (strpos($main_media->url, 'mofile.com')!==false)
				{ // mofile.com link - begin
					$begin_tag = strpos($main_media->url, 'com/');
					$remaining_link = substr($main_media->url, $begin_tag + 4, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '/');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{mofile}'.$link_.'{/mofile}';	
				} // mofile.com link - end
				elseif (strpos($main_media->url, 'myspace.com')!==false)
				{ // myspace.com link - begin
					$link_array = explode('VideoID=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{myspace}'.$link_.'{/myspace}';
				} // myspace.com link - end
				elseif (strpos($main_media->url, 'myvideo.de')!==false)
				{ // myvideo.de link - begin
					$begin_tag = strpos($main_media->url, 'watch/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '/');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{myvideo}'.$link_.'{/myvideo}';	
				} // myvideo.de link - end
				elseif (strpos($main_media->url, 'redtube.com')!==false)
				{ // redtube.com [adult] link - begin
					$link_array = explode('/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{redtube}'.$link_.'{/redtube}';
				} // redtube.com [adult] - end
				elseif (strpos($main_media->url, 'revver.com')!==false)
				{ // revver.com link - begin
					$begin_tag = strpos($main_media->url, 'video/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '/');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{revver}'.$link_.'{/revver}';	
				} // revver.com link - end
				elseif (strpos($main_media->url, 'sapo.pt')!==false)
				{ // sapo.pt link - begin
					$link_array = explode('pt/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{sapo}'.$link_.'{/sapo}';
				} // sapo.pt - end
				elseif (strpos($main_media->url, 'sevenload.com')!==false)
				{ // sevenload.com link - begin
					$begin_tag = strpos($main_media->url, 'videos/');
					$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '-');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{sevenload}'.$link_.'{/sevenload}';	
				} // sevenload.com link - end
				elseif (strpos($main_media->url, 'sohu.com')!==false)
				{ // sohu.com link - begin
					$link_array = explode('/',$main_media->url);
					$link_ = $link_array[count($link_array)-1]; 	
					$media = '{sohu}'.$link_.'{/sohu}';
				} // sohu.com - end
				elseif (strpos($main_media->url, 'southparkstudios.com')!==false)
				{ // southparkstudios.com link - begin
					$begin_tag = strpos($main_media->url, 'clips/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '/');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{southpark}'.$link_.'{/southpark}';	
				} // southparkstudios.com link - end
				elseif (strpos($main_media->url, 'spike.com')!==false)
				{ // spike.com link - begin
					$link_array = explode('video/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{spike}'.$link_.'{/spike}';
				} // spike.com - end
				elseif (strpos($main_media->url, 'stickam.com')!==false)
				{ // stickam.com link - begin
					$link_array = explode('mId=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{stickam}'.$link_.'{/stickam}';
				} // stickam.com - end
				elseif (strpos($main_media->url, 'stupidvideos.com')!==false)
				{ // stupidvideos.com link - begin
					$link_array = explode('#',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{stupidvideos}'.$link_.'{/stupidvideos}';
				} // stupidvideos.com - end
				elseif (strpos($main_media->url, 'tudou.com')!==false)
				{ // tudou.com link - begin
					$begin_tag = strpos($main_media->url, 'view/');
					$remaining_link = substr($main_media->url, $begin_tag + 5, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '/');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{tudou}'.$link_.'{/tudou}';	
				} // tudou.com link - end
				elseif (strpos($main_media->url, 'ustream.tv')!==false)
				{ // ustream.tv link - begin
					$link_array = explode('recorded/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{ustream}'.$link_.'{/ustream}';
				} // ustream.tv - end
				elseif (strpos($main_media->url, 'veoh.com')!==false)
				{ // veoh.com link - begin
					$link_array = explode('videos/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{veoh}'.$link_.'{/veoh}';
				} // veoh.com - end
				elseif (strpos($main_media->url, 'videotube.de')!==false)
				{ // videotube.de link - begin
					$link_array = explode('watch/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{videotube}'.$link_.'{/videotube}';
				} // videotube.de - end
				elseif (strpos($main_media->url, 'vidiac.com')!==false)
				{ // vidiac.com link - begin
					$begin_tag = strpos($main_media->url, 'video/');
					$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '.');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{vidiac}'.$link_.'{/vidiac}';	
				} // vidiac.com link - end
				elseif (strpos($main_media->url, 'vimeo.com')!==false)
				{ // vimeo.com link - begin
					$link_array = explode('.com/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{vimeo}'.$link_.'{/vimeo}';
				} // vimeo.com - end
				elseif (strpos($main_media->url, 'yahoo.com')!==false)
				{ // video.yahoo.com link - begin
					$link_array = explode('watch/',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{yahoo}'.$link_.'{/yahoo}';			
				} // video.yahoo.com - end
				elseif (strpos($main_media->url, 'youare.tv')!==false)
				{ // youare.tv link - begin
					$link_array = explode('id=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{youare}'.$link_.'{/youare}';			
				} // youare.tv - end
				elseif (strpos($main_media->url, 'youku.com')!==false)
				{ // youku.com link - begin
					$begin_tag = strpos($main_media->url, 'v_show/');
					$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
					$end_tag = strpos($remaining_link, '.');
					if($end_tag===false) $end_tag = strlen($remaining_link);
					$link_ = substr($remaining_link, 0, $end_tag);	
					$media = '{youku}'.$link_.'{/youku}';	
				} // youku.com link - end
				elseif (strpos($main_media->url, 'youmaker.com')!==false)
				{ // youmaker.com  link - begin
					$link_array = explode('id=',$main_media->url);
					$link_ = $link_array[1]; 	
					$media = '{youmaker}'.$link_.'{/youmaker}';			
				} // youmaker.com  - end
				else
				{
					//----------- not special link - begin
					$extension_array=explode('.',$main_media->url);
					$extension = $extension_array[count($extension_array)-1];
				
					if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='mp4' || strtolower($extension)=='divx')
						{
							$tag_begin = '{'.strtolower($extension).'remote}';
							$tag_end = '{/'.strtolower($extension).'remote}';
						}	
					if(!isset($tag_begin)) {$tag_begin=NULL;}
					if(!isset($tag_end)) {$tag_end=NULL;}
					$media = $tag_begin.$main_media->url.$auto_play.$tag_end;
					//----------- not special link - begin										
				}

				//$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, 0);
				//aici
				$main_media->auto_play = 0;
				require_once(JPATH_ROOT .'/components/com_guru/helpers/videos/helper.php');
				$parsedVideoLink = parse_url($main_media->url);
				preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedVideoLink['host'], $matches);
				$domain	= $matches['domain'];

				if (!empty($domain)){
					$provider		= explode('.', $domain);
					$providerName	= strtolower($provider[0]);
					
					if($providerName == "youtu"){
						$providerName = "youtube";
					}
					
					$libraryPath = JPATH_ROOT .'/components/com_guru/helpers/videos' .'/'. $providerName . '.php';
					
					if(file_exists($libraryPath)){
						require_once($libraryPath);
						$className		= 'PTableVideo' . ucfirst($providerName);
						$videoObj		= new $className();
						$videoObj->init($main_media->url);
						$video_id		= $videoObj->getId();
						
						if($providerName == "youtube" || $providerName == "vimeo" || $providerName == "dailymotion"){
							$video_id = $video_id."?autoplay=".$main_media->auto_play;
						}

						$videoPlayer	= $videoObj->getViewHTML($video_id, $main_media->width, $main_media->height);
						$videoPlayer = preg_replace('/width="(.*)"/msU', 'width="100%"', $videoPlayer);
						
						$media = $videoPlayer;
					}
					else{
						//$media = $this->parse_media(intval($result[$i]->media_id), $attribs->layout);
					}
				}
			}
			
			if($main_media->source=='local'){	
				if($main_media->auto_play == "1"){
					$autoplay = 'true';
				}
				else {
					$autoplay = 'false';
				}
				
				$extension_array=explode('.',$main_media->local);
				$extension = $extension_array[count($extension_array)-1];
				
				if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='divx'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				
				if(!isset($tag_begin)) {
					$tag_begin=NULL;
				}
				
				if(!isset($tag_end)) {
					$tag_end=NULL;
				}
				
				if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='divx'){
					$media = $tag_begin.str_replace("/administrator","",JURI::base()).$configs->videoin.'/'.$main_media->local.$tag_end;
					$guru_media_autoplay = "";
					$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, $autoplay);
				}
				elseif(strtolower($extension)=='mp4'){
					$media = str_replace("/administrator", "", JURI::base()).$configs->videoin.'/'.$main_media->local;
				
					$media = '
						<video width="100%" controls>
							<source src="'.$media.'" type="video/mp4" />
						</video>
					';
				}
			}
		}
		
		if(@$main_media->type=='audio'){				
			if($main_media->auto_play == "1"){	
				$guru_media_autoplay = TRUE;
			}
			else{
				$guru_media_autoplay = FALSE;	
			}
			
			if($main_media->source=='code'){
				$media = $main_media->code;
			}
			
			if($main_media->source=='url'){
				$extension_array=explode('.',$main_media->url);
				$extension = $extension_array[count($extension_array)-1];
				if(strtolower($extension)=='mp3' || strtolower($extension)=='wma' || strtolower($extension)=='m4a'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				$media = @$tag_begin.$main_media->url.$tag_end;
				$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, $guru_media_autoplay);
			}
			
			if($main_media->source=='local'){
				$extension_array=explode('.',$main_media->local);
				$extension = $extension_array[count($extension_array)-1];
				
				if(strtolower($extension)=='mp3' || strtolower($extension)=='wma'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				$media = $tag_begin.str_replace("/administrator","",JURI::base()).$configs->audioin.'/'.$main_media->local.$tag_end;
				$media = guruHelper::jwAllVideos( $media, $awidth, $aheight, $vheight, $vwidth, $guru_media_autoplay);
			}
		}

		if(@$main_media->type=='url'){
			$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';	
		}
		
		if(@$main_media->type=='docs'){
			if($main_media->source=='url'){
				$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';
			}
			if($main_media->source=='local'){
				$media = '<a target="_blank" href="'.str_replace("/administrator","",JURI::base()).'/'.$configs->docsin.'/'.$main_media->local.'">'.$main_media->name.'</a>';
			}
		}
		
		if(@$main_media->type=='image'){
			$img_size = @getimagesize(JPATH_SITE.DIRECTORY_SEPARATOR.$configs->imagesin.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'thumbs'.$main_media->local);
			if(isset($img_size[0]) && isset($img_size[1])){
				$img_width = $img_size[0];
				$img_height = $img_size[1];
				if($img_width>0 && $img_height>0){ 
					if($vwidth > 0){
						$thumb_width = $vwidth;
						$thumb_height = $img_height / ($img_width/$vwidth);
					}
					elseif($vheight > 0)	{
						$thumb_height = $vheight;
						$thumb_width = $img_width / ($img_height/$vheight);		
					}
					else{
						$thumb_height = 200;
						$thumb_width = $img_width / ($img_height/200);									
					}
				}
				
				if(isset($thumb_width) && isset($thumb_height)) {
					$media = '<img width="'.$thumb_width.'" height="'.$thumb_height.'" src="';
				} else {
					$media = '<img src="';
				}
				$media .= JUri::root()."/".$configs->imagesin.'/media/thumbs'.$main_media->local.'" />';	
			}
		}
		
		if(@$main_media->type == "file"){   
			$media = '<a target="_blank" href="'.JURI::root().$configs->filesin.'/'.$main_media->local.'">'.$main_media->name.'</a><br/><br/>'.$main_media->instructions;
		}
		
		if(isset($media)){
			return $media;
		}
		else{
			return NULL;
		}
	}

	
	public static function jwAllVideos( &$row, $parawidth=300, $paraheight=20, $parvwidth=400, $parvheight=300, $auto_play){
		if($auto_play == 1){
			$final_autoplay2 = 'TRUE';
		}
		else{
			$final_autoplay2 = 'FALSE';
		}
		
		$app = JFactory::getApplication('administrator');
		$plg_name					= "jw_allvideos";
		$plg_tag					= "";
		$plg_copyrights_start		= "\n\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.5.0) starts here -->\n";
		$plg_copyrights_end			= "\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.5.0) ends here -->\n\n";
		$mosConfig_live_site = JURI::root();
		$document  = JFactory::getDocument();

		
    	if(substr($mosConfig_live_site, -1)=="/"){
			$mosConfig_live_site = substr($mosConfig_live_site, 0, -1);
		}
		jimport('joomla.filesystem.file');

		//if(JPluginHelper::isEnabled('content',$plg_name)==false) return;
		
		include(JPATH_SITE.DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."content".DIRECTORY_SEPARATOR."jw_allvideos".DIRECTORY_SEPARATOR."jw_allvideos".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."sources.php");

		$grabTags = str_replace("(","",str_replace(")","",implode(array_keys($tagReplace),"|")));
		
		$grabTags .= "|vimeo";
		$row = str_replace("{vimeo}", "{Vimeo}", $row);
		$row = str_replace("{/vimeo}", "{/Vimeo}", $row);

		if(preg_match("#{(".$grabTags.")}#s", $row)==false){
			return;
		}

		$av_template			= 'getault';
		$av_compressjs			= 0;
		// video
		$vfolder 				= 'images/stories/videos';
		$vwidth 				= $parvwidth;
		$vheight 				= $parvheight;
		// audio
		$afolder 				= '';
		$awidth 				= $parawidth;
		$aheight 				= $paraheight;
		// global
		$autoplay 				= $auto_play;
		$transparency 			= 'transparent';
		$background 			= '#FFFFFF';
		// FLV playback
		$av_flvcontroller 		= 'bottom';	
	
		if($av_flvcontroller == "over"){
			$av_flvcontroller = "&controlbar=over";
		} else {
			$av_flvcontroller = "";
		}

		// Variable cleanups for K2
		if(JFactory::getApplication()->input->get('format')=='raw'){
			$plg_copyrights_start = '';
			$plg_copyrights_end = '';
		}

		// ----------------------------------- Render the output -----------------------------------

		// Append head includes only when the document is in HTML mode
		if(JFactory::getApplication()->input->get('format')=='html' || JFactory::getApplication()->input->get('format')==''|| JFactory::getApplication()->input->get('format')=='raw'){
			// CSS
			//$avCSS = $AllVideosHelper->getTemplatePath($this->plg_name,'css/template.css',$playerTemplate);
			@$avCSS = $avCSS->http;
			$document->addStyleSheet(@$avCSS);

			// JS
			
		  JHtml::_('behavior.framework');
		
			if(0){
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/jwp.js.php');
			} 
			else{
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/behaviour.js');
				//$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/mediaplayer/jwplayer.min.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/wmvplayer/silverlight.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/wmvplayer/wmvplayer.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/quicktimeplayer/ac_quicktime.js');
			}
		}		

		// START ALLVIDEOS LOOP	

		$document  = JFactory::getDocument();
		$document->addScript('https://cdn.jsdelivr.net/gh/clappr/clappr@latest/dist/clappr.min.js');
		$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/jwp.js.php');
		$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/behaviour.js');

		foreach ($tagReplace as $plg_tag => $value) {
			// expression to search for
			$regex = "#{".$plg_tag."}.*?{/".$plg_tag."}#s";
			// process tags

			if(preg_match_all($regex, $row, $matches, PREG_PATTERN_ORDER)) {
				// start the replace loop
				foreach ($matches[0] as $key => $match) {

					$tagcontent 		= preg_replace("/{.+?}/", "", $match);
					$tagparams 			= explode('|',$tagcontent);
					$tagsource 			= trim(strip_tags($tagparams[0]));

					// Prepare the HTML
					$output = new JObject;

					// Width/height/source folder split per media type

					if(in_array($plg_tag, array(
						'mp3',
						'mp3remote',
						'aac',
						'aacremote',
						'm4a',
						'm4aremote',
						'ogg',
						'oggremote',
						'wma',
						'wmaremote',
						'soundcloud'
					))){
						$final_awidth 	= (@$tagparams[1]) ? $tagparams[1] : $awidth;
						$final_aheight 	= (@$tagparams[2]) ? $tagparams[2] : $aheight;

						$output->playerWidth = $final_awidth;
						$output->playerHeight = $final_aheight;
						$output->folder = $afolder;


						if($plg_tag=='soundcloud'){
							if(strpos($tagsource,'/sets/')!==false){
								$output->mediaTypeClass = ' avSoundCloudSet';
							} else {
								$output->mediaTypeClass = ' avSoundCloudSong';
							}
							$output->mediaType = '';
						} else {
							$output->mediaTypeClass = ' avAudio';
							$output->mediaType = 'audio';
						}

						if(in_array($plg_tag, array('mp3','aac','m4a','ogg','wma'))){
							$output->source = "$siteUrl/$afolder/$tagsource.$plg_tag";
						} elseif(in_array($plg_tag, array('mp3remote','aacremote','m4aremote','oggremote','wmaremote'))){
							$output->source = $tagsource;

						} else {
							$output->source = '';
						}
					} else {
						$final_vwidth 	= (@$tagparams[1]) ? $tagparams[1] : $vwidth;
						$final_vheight 	= (@$tagparams[2]) ? $tagparams[2] : $vheight;

						$output->playerWidth = $final_vwidth;
						$output->playerHeight = $final_vheight;
						$output->folder = $vfolder;
						$output->mediaType = 'video';
						$output->mediaTypeClass = ' avVideo';
					}

					// Autoplay
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
					$final_autoplay	= ($final_autoplay) ? 'true' : 'false';

					// Special treatment for specific video providers
					if($plg_tag=="dailymotion"){
						$tagsource = preg_replace("~(http|https):(.+?)dailymotion.com\/video\/~s","",$tagsource);
						$tagsourceDailymotion = explode('_',$tagsource);
						$tagsource = $tagsourceDailymotion[0];
						if($final_autoplay=='true'){
							if(strpos($tagsource,'?')!==false){
								$tagsource = $tagsource.'&amp;autoPlay=1';
							} else {
								$tagsource = $tagsource.'?autoPlay=1';
							}
						}
					}

					if($plg_tag=="ku6"){
						$tagsource = str_replace('.html','',$tagsource);
					}

					if($plg_tag=="metacafe" && substr($tagsource,-1,1)=='/'){
						$tagsource = substr($tagsource,0,-1);
					}

					if($plg_tag=="tnaondemand"){
						$tagsource = parse_url($tagsource);
						$tagsource = explode('&',$tagsource['query']);
						$tagsource = str_replace('vidid=','',$tagsource[0]);
					}

					if($plg_tag=="twitvid"){
						$tagsource = preg_replace("~(http|https):(.+?)twitvid.com\/~s","",$tagsource);
						if($final_autoplay=='true'){
							$tagsource = $tagsource.'&amp;autoplay=1';
						}
					}

					if($plg_tag=="vidiac"){
						$tagsourceVidiac = explode(';',$tagsource);
						$tagsource = $tagsourceVidiac[0];
					}

					if($plg_tag=="vimeo"){
						$tagsource = preg_replace("~(http|https):(.+?)vimeo.com\/~s","",$tagsource);
							
						if(strpos($tagsource,'?')!==false){
							$tagsource = $tagsource.'&amp;portrait=0&amp;autoplay='.$auto_play.'';
						} else {
							$tagsource = $tagsource.'?portrait=0&amp;autoplay='.$auto_play.'';
						}
						if($final_autoplay=='true'){
							$tagsource = $tagsource.'&amp;autoplay='.$auto_play.'';
						}
					}

					if($plg_tag=="yahoo"){
						$tagsourceYahoo = explode('-',str_replace('.html','',$tagsource));
						$tagsourceYahoo = array_reverse($tagsourceYahoo);
						$tagsource = $tagsourceYahoo[0];
					}

					if($plg_tag=="yfrog"){
						$tagsource = preg_replace("~(http|https):(.+?)yfrog.com\/~s","",$tagsource);
					}

					if($plg_tag=="youmaker"){
						$tagsourceYoumaker = explode('-',str_replace('.html','',$tagsource));
						$tagsource = $tagsourceYoumaker[1];
					}

					if($plg_tag=="youku"){
						$tagsource = str_replace('.html','',$tagsource);
						$tagsource = substr($tagsource,3);
					}

					if($plg_tag=="youtube"){
						$tagsource = preg_replace("~(http|https):(.+?)youtube.com\/watch\?v=~s","",$tagsource);
						$tagsourceYoutube = explode('&',$tagsource);
						$tagsource = $tagsourceYoutube[0];

						if(strpos($tagsource,'?')!==false){
							$tagsource = $tagsource.'&amp;rel=0&amp;fs=1&amp;wmode=transparent';
						} else {
							$tagsource = $tagsource.'?rel=0&amp;fs=1&amp;wmode=transparent';
						}
						if($final_autoplay=='true'){
							$tagsource = $tagsource.'&amp;autoplay=1';
						}
					}

					
				// Set a unique ID
				$output->playerID = 'AVPlayerID_'.substr(md5($tagsource),1,8).'_'.rand();

				if($auto_play != ""){
					// is audio	
					if($auto_play == 0){
						$auto_play = "false";
					}
					else{
						$auto_play = "true";
					}
					
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $auto_play;
					$final_autoplay1 = (@$tagparams[3]) ? $tagparams[3] : $auto_play;
				}
				else{
					// is video
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $auto_play;
					$final_autoplay1 = (@$tagparams[3]) ? $tagparams[3] : $auto_play;
				}

				if(trim($final_autoplay1) == ""){
					$final_autoplay1 = $final_autoplay;
				}

				// replacements
				$findAVparams = array(
					"{SITEURL}",
					"{SOURCE}",
					"{SOURCEID}",
					"{FOLDER}",
					"{WIDTH}",
					"{HEIGHT}",		
					"{AUTOPLAY}",
					"{PLAYER_AUTOPLAY}",
					"{PLAYER_AUTOPLAY1}",
					"{TRANSPARENCY}",
					"{PLAYER_BACKGROUND}",
					"{PLAYER_ABACKGROUND}",
					"{CONTROLBAR}",
					"{PLAYER_POSTER_FRAME_REMOTE}",
					"{PLAYER_POSTER_FRAME}",
					"{PLAYER_LOOP}"
				);
				
				// special treatment
				if($plg_tag=="yahoo"){
					$tagsourceyahoo = explode('/',$tagsource);
					$tagsource = 'id='.$tagsourceyahoo[1].'&amp;vid='.$tagsourceyahoo[0];
				}
				if($plg_tag=="youku"){
					$tagsource = substr($tagsource,3);
				}				
				
					if(trim($final_autoplay) == ""){
						$final_autoplay = "0";
					}

					if(trim($final_autoplay1) == ""){
						$final_autoplay1 = "0";
					}

					if(trim($final_autoplay2) == ""){
						$final_autoplay2 = "0";
					}

					$add_download_button = false;

				// replacement elements
					if(in_array($plg_tag, array("mp3", "mp3remote", "m4a", "m4aremote", "wma", "wmaremote"))){
						$plugin = JPluginHelper::getPlugin('content', 'jw_allvideos');
						$plugin_params = $plugin->params;
						$plugin_params = json_decode($plugin_params, true);

						if(isset($plugin_params["allowAudioDownloading"]) && $plugin_params["allowAudioDownloading"] == 1){
							$add_download_button = true;
						}

						if($awidth == 0){
							$awidth = "80%";
						}
						
						if($aheight == 0){
							$aheight = "20px";
						}

						$aheight = 50;

						$replaceAVparams = array(
							JURI::root(),
							$tagsource,
							substr(md5($tagsource),1,8),
							$afolder,
							$awidth,
							$aheight,
							$final_autoplay,
							$final_autoplay1,
							$final_autoplay2,
							$transparency,
							$background,
							$background,
							@$controlBarLocation,
							"",
							"",
							0
						);

						$output->playerWidth = $awidth;
						$output->playerHeight = $aheight;
						
					}
					else{
						require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
						$detect = new Mobile_Detect;
						$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

						if($deviceType == 'phone'){
							$final_vwidth = "100%";
							$final_vheight = "100%";
						}
						
						if($final_vwidth == 0){
							$final_vwidth = "80%";
						}
						
						if($final_vheight == 0){
							$final_vheight = "20px";
						}
						
						$replaceAVparams = array(
							JURI::root(),
							$tagsource,
							substr(md5($tagsource),1,8),
							$vfolder,
							$final_vwidth,
							$final_vheight,
							$final_autoplay,
							$final_autoplay1,
							$final_autoplay2,
							$transparency,
							$background,
							$background,
							@$controlBarLocation,
							"",
							"",
							0
						);
					}

					//$plg_html = JFilterOutput::ampReplace($wrapstart.str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]).$wrapend);
					$plg_html = str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]);				
					
					// Do the replace
					@$row = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $plg_html , $row);
					
					$pluginLivePath = JURI::root().'plugins/content/jw_allvideos/jw_allvideos';
					$row = str_replace("{PLUGIN_PATH}", $pluginLivePath, $row);

					if($add_download_button){
						$row .= "<a href='".trim($tagsource)."' target='_blank' class='btn btn-default btn-block' style='margin-top:20px;'>".JText::_("GURU_DOWNLOAD")."</a>";
					}
					
					$row = str_replace("http://www.youtube.com", "https://www.youtube.com", $row);
					$row = str_replace("http://player.vimeo", "https://player.vimeo", $row);
					
				} // end foreach
			} // end if
		} // END ALLVIDEOS LOOP
		
		$extension_array = explode(".", $tagsource);
		$extension = $extension_array[count($extension_array) - 1];
		if($extension == "mp4"){
			$auto_play_object = "";
			
			if($final_autoplay == "false"){
				$auto_play_object = 'autoplay="false"';
			}
			else{
				$auto_play_object = 'autoplay="true"';
			}

			$row = '<object height="'.$final_vheight.'" width="'.$final_vwidth.'" data="'.$tagsource.'" '.$auto_play_object.'></object>';
		}
		
		$row = str_replace("%px", "%", $row);	
		return $row;
	} // END FUNCTION
	
	function transformPagination($pages){
		if(strpos(" ".$pages, '<ul>') !== FALSE){
			$pages = str_replace("<ul>", '<ul class="uk-pagination">', $pages);
		}
		$pages = str_replace('<ul class="pagination-list">', '<ul class="uk-pagination">', $pages);

		preg_match_all('/<a(.*)>(.*)<\/a>/msU', $pages, $matches);
		
		if(isset($matches) && count($matches) > 0){
			foreach($matches["0"] as $key=>$link){
				if(strpos($link, "limitstart=") !== FALSE){
					preg_match_all('/limitstart=(.*)"/msU', $link, $limit);
					if(isset($limit["1"]["0"])){
						$limitstart = intval($limit["1"]["0"]);
						$url_text = $matches["2"][$key];
						$url_text = preg_replace("/title=(.*)>/msU", "", $url_text);
						
						$new_link = '<a onclick="document.adminForm.limitstart.value='.intval($limitstart).'; Joomla.submitform();return false;" href="#">'.trim($url_text).'</a>';
						$pages = str_replace($link, $new_link, $pages);
					}
				}
				elseif(strpos($link, "start=") !== FALSE){
					preg_match_all('/start=(.*)"/msU', $link, $limit);
					if(isset($limit["1"]["0"])){
						$limitstart = intval($limit["1"]["0"]);
						$url_text = $matches["2"][$key];
						$url_text = preg_replace("/title=(.*)>/msU", "", $url_text);
						
						$new_link = '<a onclick="document.adminForm.limitstart.value='.intval($limitstart).'; Joomla.submitform();return false;" href="#">'.trim($url_text).'</a>';
						$pages = str_replace($link, $new_link, $pages);
					}
				}
				else{
					if(trim($matches["1"][$key]) != ""){
						$url_text = $matches["2"][$key];
						$url_text = preg_replace("/title=(.*)>/msU", "", $url_text);
						
						$new_link = '<a onclick="document.adminForm.limitstart.value=0; Joomla.submitform();return false;" href="#">'.trim($url_text).'</a>';
						$pages = str_replace($link, $new_link, $pages);
					}
				}
			}
			$pages .= '<input type="hidden" value="0" name="limitstart">';
		}
		$pages = '<div class="pagination pagination-centered">'.$pages.'</div>';

		return $pages;
	}
	
	function createStudentMenu(){
		$tmpl = JFactory::getApplication()->input->get("tmpl", "");
		if($tmpl == "component"){
			return "";
		}
		
		$db = JFactory::getDbo();
		
		$g_my_profile = "";
		$g_my_courses = "";
		$g_my_orders = "";
		$g_my_quizzes = "";
		$g_my_certificates = "";
		$g_my_projects = "";
		
		$menu_g_my_profile = "";
		$menu_g_my_courses = "";
		$menu_g_my_orders = "";
		$menu_g_my_quizzes = "";
		$menu_g_my_certificates = "";
		$menu_g_my_projects = "";
		
		$controller = JFactory::getApplication()->input->get("controller", "");
		if(trim($controller) == ""){
			$controller = JFactory::getApplication()->input->get("view", "");
		}
		$layout = JFactory::getApplication()->input->get("layout", "");
		$task = JFactory::getApplication()->input->get("task", "");
		$Itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
		
		$helper = new guruHelper();
		$itemid_seo = $helper->getSeoItemid();
		$itemid_seo = @$itemid_seo["guruprofile"];
		
		if(intval($itemid_seo) > 0){
			$Itemid = intval($itemid_seo);
		}
		else{
			$user = JFactory::getUser();
			$user_id = $user->id;

        	$itemid_menu = $helper->getTeacherMenuItem(intval($user_id));

        	if(intval($itemid_menu) > 0){
                $Itemid = intval($itemid_menu);
            }
        }
		
		$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
	
		if(trim($layout) == ""){
			$layout = $task;
		}
		
		if(strtolower($controller) == "guruprofile" && ($layout == "edit" || $layout == "editform" || $layout == "")){
			$g_my_profile = 'class="uk-active"';
			$menu_g_my_profile = 'selected="selected"';
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "mycourses"){
			$g_my_courses = 'class="uk-active"';
			$menu_g_my_courses = 'selected="selected"';
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "myorders"){
			$g_my_orders = 'class="uk-active"';
			$menu_g_my_orders = 'selected="selected"';
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "myquizandfexam"){
			$g_my_quizzes = 'class="uk-active"';
			$menu_g_my_quizzes = 'selected="selected"';
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "mycertificates"){
			$g_my_certificates = 'class="uk-active"';
			$menu_g_my_certificates = 'selected="selected"';
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "myprojects"){
			$g_my_projects = 'class="uk-active"';
			$menu_g_my_projects = 'selected="selected"';
		}
		
		$log_out = "";
		$user = JFactory::getUser();
		
		$itemid_categs = JFactory::getApplication()->input->get("Itemid", "0", "raw");
		$Itemid_profile = JFactory::getApplication()->input->get("Itemid", "0", "raw");
		$Itemid_orders = JFactory::getApplication()->input->get("Itemid", "0", "raw");
		
		if($user->id > 0){
			$helper = new guruHelper();
			$itemid_seo = $helper->getSeoItemid();
			$itemid_seo = @$itemid_seo["guruorders"];
			
			if(intval($itemid_seo) > 0){
				$Itemid_orders = intval($itemid_seo);
			}
			
			$helper = new guruHelper();
			$itemid_seo = $helper->getSeoItemid();
			$itemid_seo = @$itemid_seo["gurupcategs"];
			
			if(intval($itemid_seo) > 0){
				$itemid_categs = intval($itemid_seo);
			}
			
			$helper = new guruHelper();
			$itemid_seo = $helper->getSeoItemid();
			$itemid_seo = @$itemid_seo["guruprofile"];
			
			if(intval($itemid_seo) > 0){
				$Itemid_profile = intval($itemid_seo);
				
				$sql = "select access from #__menu where id=".intval($Itemid_profile);
				$db->setQuery($sql);
				$db->execute();
				$access = $db->loadColumn();
				$access = @$access["0"];
				
				if(intval($access) == 3){
					// special
					$user_groups = $user->get("groups");
					if(!in_array(8, $user_groups)){
						$Itemid_profile = JFactory::getApplication()->input->get("Itemid", "0", "raw");
					}
				}
			}
			else{
				$user = JFactory::getUser();
				$user_id = $user->id;

            	$itemid_menu = $helper->getTeacherMenuItem(intval($user_id));

            	if(intval($itemid_menu) > 0){
                    $Itemid_profile = intval($itemid_menu);
                }
            }
			
			$layout_come = JFactory::getApplication()->input->get("layout", "authormycourses");
			
			$return_url = base64_encode("index.php?option=com_guru&view=gurupcategs&Itemid=".intval($itemid_categs));
			
			$log_out = '<li id="g_logout" class="logout-btn">
							<a href="index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&Itemid='.$Itemid.'&return='.$return_url.'">Log Out</a>
					   </li>';
		}
		
		$return = '
			<aside>
			<div class=" uk-card uk-card-body uk-card-default uk-border-rounded uk-card-small">
				<ul class="category-module uk-child-width-1-1 uk-grid-small uk-grid-divider" style="margin-left: -30px;" uk-grid>
					<li id="g_my_profile" '.$g_my_profile.'>
						<a href="'.JRoute::_("index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".intval($Itemid_profile), false).'">'.JText::_('GURU_MY_ACCOUNT').'</a>
					</li>
					
					<li id="g_my_courses_active" '.$g_my_courses.'>
						<a href="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".intval($Itemid_orders), false).'">'.JText::_('GURU_MYCOURSES').'</a>
					</li>
					
					<li id="g_my_students" '.$g_my_orders.'>
						<a href="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".intval($Itemid_orders), false).'">'.JText::_('GURU_MYORDERS_MYORDERS').'</a>
					</li>
					
					<li id="g_my_quizzes" '.$g_my_quizzes.'>
						<a href="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=".intval($Itemid_orders), false).'">'.JText::_('GURU_QUIZZ_FINAL_EXAM').'</a>
					</li>
					<li id="g_my_projects" '.$g_my_projects.'>
						<a href="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=myprojects&Itemid=".intval($Itemid_orders), false).'">'.JText::_('GURU_MY_PROJECTS').'</a>
					</li>
					<li id="g_my_quizzes" '.$g_my_certificates.'>
						<a href="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=".intval($Itemid_orders), false).'">'.JText::_('GURU_MYCERTIFICATES').'</a>
					</li>';
					$return .= ''.$log_out.'
				</ul>
				</div>
			</aside>';
			
			$return .= '<div id="guru_menubar_mobile" class="uk-hidden-large uk-hidden-medium uk-hidden@m">
							<select name="menu_bar" onchange="document.location.href = this.value">
								<option '.$menu_g_my_profile.' value="'.JRoute::_("index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".intval($Itemid_profile), false).'">'.JText::_("GURU_MY_ACCOUNT").'</option>
								<option '.$menu_g_my_courses.' value="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".intval($Itemid_orders), false).'">'.JText::_("GURU_MYCOURSES").'</option>
								<option '.$menu_g_my_orders.' value="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".intval($Itemid_orders), false).'">'.JText::_("GURU_MYORDERS_MYORDERS").'</option>
								<option '.$menu_g_my_quizzes.' value="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=".intval($Itemid_orders), false).'">'.JText::_("GURU_QUIZZ_FINAL_EXAM").'</option>
								<option '.$menu_g_my_projects.' value="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=myprojects&Itemid=".intval($Itemid_orders), false).'">'.JText::_("GURU_MY_PROJECTS").'</option>
								<option '.$menu_g_my_certificates.' value="'.JRoute::_("index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=".intval($Itemid_orders), false).'">'.JText::_("GURU_MYCERTIFICATES").'</option>
							</select>
						</div>';
		return $return;
	}
	
	function createPageTitleAndCart(){
		$tmpl = JFactory::getApplication()->input->get("tmpl", "");
		if($tmpl == "component"){
			return "";
		}
		
		$page_title = "";
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		
		$controller = JFactory::getApplication()->input->get("controller", "");
		if(trim($controller) == ""){
			$controller = JFactory::getApplication()->input->get("view", "");
		}
		$layout = JFactory::getApplication()->input->get("layout", "");
		$task = JFactory::getApplication()->input->get("task", "");
		$Itemid = JFactory::getApplication()->input->get("Itemid", "0", "raw");
		
		$helper = new guruHelper();
		$itemid_seo = $helper->getSeoItemid();
		$itemid_seo = @$itemid_seo["guruprofile"];
		
		if(intval($itemid_seo) > 0){
			$Itemid = intval($itemid_seo);
			
			$sql = "select access from #__menu where id=".intval($Itemid);
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
		
		$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
	
		if(trim($layout) == ""){
			$layout = $task;
		}
		
		if(strtolower($controller) == "guruprofile" && ($layout == "edit" || $layout == "")){
			$page_title = JText::_('GURU_PROFILE');
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "mycourses"){
			$page_title = JText::_("GURU_MYCOURSES");
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "myorders"){
			$page_title = JText::_("GURU_MYORDERS_MYORDERS");
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "myquizandfexam"){
			$page_title = JText::_("GURU_QUIZZ_FINAL_EXAM");
		}
		elseif(strtolower($controller) == "guruorders" && $layout == "mycertificates"){
			$page_title = JText::_("GURU_MYCERTIFICATES");
		}
		
		$helper = new guruHelper();
		$itemid_seo = $helper->getSeoItemid();
		$itemid_seo = @$itemid_seo["gurubuy"];
		
		if(intval($itemid_seo) > 0){
			$Itemid = intval($itemid_seo);
		}
		
		$return = '
			<div class="uk-float-left pagetitle-cartbutton uk-hidden">
				<div class="uk-float-left uk-text-left">
					<h2 class="gru-page-title">'.$page_title.'</h2>
				</div>
				<div class="uk-float-left uk-text-right" width="50%">
					<a class="uk-button uk-button-success" href="index.php?option=com_guru&view=guruBuy&Itemid='.intval($Itemid).'">
						<img src="components/com_guru/images/cart.gif" alt="'.JText::_("GURU_MY_CART").'"/>
						<u>'.JText::_("GURU_CART").'</u>
					</a>
				</div>
			</div>
			<div class="clearfix"></div>
		';
		
		return $return;
	}
	
	function getSeoItemid(){
		$db = JFactory::getDbo();
		$return = array();
		
		$sql = "select seo from #__guru_config";
		$db->setQuery($sql);
		$db->execute();
		$seo = $db->loadColumn();
		$seo = @$seo["0"];
		
		if(trim($seo) != ""){
			$seo_array = json_decode(trim($seo), true);
			if(isset($seo_array["itemid"])){
				$return = $seo_array["itemid"];
			}
		}
		
		return $return;
	}

	function getCourseMenuItem($id){
        $db = JFactory::getDbo();

        $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=guruprograms&layout=view\' and `published`=\'1\' and `params` like \'%"cid":"'.intval($id).'"%\' order by `id` desc limit 0, 1';
        $db->setQuery($sql);
        $db->execute();
        $course_menu_id = $db->loadColumn();
        $course_menu_id = @$course_menu_id["0"];

        if(intval($course_menu_id) == 0){
            $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=gurupcategs&layout=view\' and `published`=\'1\' order by `id` desc limit 0, 1';
            $db->setQuery($sql);
            $db->execute();
            $course_menu_id = $db->loadColumn();
            $course_menu_id = @$course_menu_id["0"];
        }

        return intval($course_menu_id);
    }

    function getTeacherMenuItem($id){
        $db = JFactory::getDbo();

        $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=guruauthor&layout=view\' and `published`=\'1\' and `params` like \'%"cid":"'.intval($id).'"%\' order by `id` desc limit 0, 1';

        $db->setQuery($sql);
        $db->execute();
        $teacher_menu_id = $db->loadColumn();
        $teacher_menu_id = @$teacher_menu_id["0"];

        if(intval($teacher_menu_id) == 0){
            $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=guruauthor\' and `published`=\'1\' order by `id` desc limit 0, 1';

            $db->setQuery($sql);
            $db->execute();
            $teacher_menu_id = $db->loadColumn();
            $teacher_menu_id = @$teacher_menu_id["0"];
        }

        return intval($teacher_menu_id);
    }

    function getCategMenuItem($id){
        $db = JFactory::getDbo();

        $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=gurupcategs&layout=view\' and `params` like \'%"cid":"'.intval($id).'"%\' and `published`=\'1\' order by `id` desc limit 0, 1';

        $db->setQuery($sql);
        $db->execute();
        $categ_menu_id = $db->loadColumn();
        $categ_menu_id = @$categ_menu_id["0"];

        if(intval($categ_menu_id) == 0){
            $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=gurupcategs\' and `published`=\'1\' order by `id` desc limit 0, 1';

            $db->setQuery($sql);
            $db->execute();
            $categ_menu_id = $db->loadColumn();
            $categ_menu_id = @$categ_menu_id["0"];
        }

        return intval($categ_menu_id);
    }

    function getStudentMenuItem($id){
        $db = JFactory::getDbo();

        $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=guruprofile&layout=editform\' and `published`=\'1\' order by `id` desc limit 0, 1';

        $db->setQuery($sql);
        $db->execute();
        $student_menu_id = $db->loadColumn();
        $student_menu_id = @$student_menu_id["0"];

        if(intval($student_menu_id) == 0){
            $sql = 'select `id` from #__menu where `link`=\'index.php?option=com_guru&view=guruauthor&layout=studentregistration\' and `published`=\'1\' order by `id` desc limit 0, 1';

            $db->setQuery($sql);
            $db->execute();
            $student_menu_id = $db->loadColumn();
            $student_menu_id = @$student_menu_id["0"];
        }

        return intval($student_menu_id);
    }

	public function displayPrice($price){
		if($price == ""){
			return "";
		}

		$original_price = $price;

		$db = JFactory::getDbo();
		$sql = "select `thousands_separator`, `decimals_separator` from #__guru_config";
		$db->setQuery($sql);
		$db->execute();
		$settings = $db->loadAssocList();

		$thousands_separator = 0;
		$decimals_separator = 1;

		if(!isset($settings)){
			return $price;
		}
		else{
			$thousands_separator = $settings["0"]["thousands_separator"];
			$decimals_separator = $settings["0"]["decimals_separator"];

			if($decimals_separator == "0"){
				$decimals_separator = ".";
			}
			elseif($decimals_separator == "1"){
				$decimals_separator = ",";
			}
			elseif($decimals_separator == "2"){
				$decimals_separator = " ";
			}

			if($thousands_separator == "0"){
				$thousands_separator = ".";
			}
			elseif($thousands_separator == "1"){
				$thousands_separator = ",";
			}
			elseif($thousands_separator == "2"){
				$thousands_separator = " ";
			}

			$price = number_format((float)$price, 2, $decimals_separator, $thousands_separator);
		}

		return $price;
	}

	public function savePrice($price){
		if($price == ""){
			return "";
		}

		$original_price = $price;

		$db = JFactory::getDbo();
		$sql = "select `thousands_separator`, `decimals_separator` from #__guru_config";
		$db->setQuery($sql);
		$db->execute();
		$settings = $db->loadAssocList();

		$thousands_separator = 0;
		$decimals_separator = 1;

		if(!isset($settings)){
			return $price;
		}
		else{
			$thousands_separator = $settings["0"]["thousands_separator"];
			$decimals_separator = $settings["0"]["decimals_separator"];

			if($decimals_separator == "0"){
				$decimals_separator = ".";
			}
			elseif($decimals_separator == "1"){
				$decimals_separator = ",";
			}
			elseif($decimals_separator == "2"){
				$decimals_separator = " ";
			}

			if($thousands_separator == "0"){
				$thousands_separator = ".";
			}
			elseif($thousands_separator == "1"){
				$thousands_separator = ",";
			}
			elseif($thousands_separator == "2"){
				$thousands_separator = " ";
			}
			
			if($thousands_separator == "." && $decimals_separator == ","){
				$price = str_replace(".", "", $price);
				$price = str_replace(",", ".", $price);
			}
			elseif($thousands_separator == "," && $decimals_separator == "."){
				$price = str_replace(",", "", $price);
			}
			elseif($thousands_separator == " " && $decimals_separator == "."){
				$price = str_replace(" ", "", $price);
			}
			elseif($thousands_separator == " " && $decimals_separator == ","){
				$price = str_replace(" ", "", $price);
				$price = str_replace(",", ".", $price);
			}
			elseif($thousands_separator == "." && $decimals_separator == " "){
				$price = str_replace(".", "", $price);
				$price = str_replace(" ", ".", $price);
			}
			elseif($thousands_separator == "," && $decimals_separator == " "){
				$price = str_replace(",", "", $price);
				$price = str_replace(" ", ".", $price);
			}

			$price = number_format((float)$price, 2, ".", "");
		}

		return $price;
	}
};
?>