<?php
/**
* @version 1.0.0
* @package RSSearch! 1.0.0
* @copyright (C) 2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
	var moduleId = <?php echo $module->id;?>;
	var RSFSearch<?php echo $module->id;?>;
	var results_box = document.getElementById('search_suggest<?php echo $module->id;?>');
	var generateResultsTimer = 0;

function searchSuggest<?php echo $module->id;?>() {
	if (generateResultsTimer > 0) {
		clearTimeout(generateResultsTimer);
	}
	
	generateResultsTimer = window.setTimeout(function () {
		var str =  encodeURIComponent(document.getElementById('rsf_inp<?php echo $module->id;?>').value);
		var ss = document.getElementById('search_suggest<?php echo $module->id;?>');
	
		if (str.length == 0) {
			closeSearch<?php echo $module->id;?>();
			return;
		}
	
		var req = new Request.JSON({
			method: 'post',
			url: '<?php echo JURI::root(); ?>'+'index.php?option=com_rssearch',
			data: 'view=results&layout=ajax&module_id=<?php echo $module->id; ?>&search='+str,
			onSuccess: function(responseText, responseXML) {
				var json = responseText;
				ss.innerHTML = '';
				
				var suggest = '';
				var results = json.length;
				var limit	= <?php echo (int) $limit; ?>;
				var i		= 0;
				
				if (results > 0) {
					if (results < limit) {
						var max = results;
					} else {
						var max = limit;
					}
					
					suggest += '<div class="rssearch_close"><a href="javascript:void(0)" onclick="javascript:closeSearch<?php echo $module->id;?>();"><img src="<?php echo JURI::root();?>modules/mod_rssearch/assets/images/close.png" alt="" /></a></div>';
					
					json.each(function (el) {
						if (i < max) {
							suggest += '<div onmouseover="javascript:suggestOver<?php echo $module->id;?>(this);" onmouseout="javascript:suggestOut<?php echo $module->id;?>(this);" class="suggest_link">';
							suggest += '<a style="color: #FFFFFF" href="'+ el.link +'">'+el.title+'</a>';
							suggest += '</div>';
						}
						
						i++;
					});
					
					if (results > limit){
						suggest += '<div class="suggest_link suggest_link_all" onclick="javascript:setSearch<?php echo $module->id;?>(this.innerHTML);"><b><?php echo JText::_("RSF_MODULE_VIEW_ALL_RESULTS"); ?></b></a></div>';
					}
				}
				
				ss.set('html',suggest);
				RSFSearch<?php echo $module->id;?>.slideIn();
			}
		});
		req.send();
	}, 800);
}

function suggestOver<?php echo $module->id;?>(div_value){
	div_value.className = 'suggest_link_over';
}

function suggestOut<?php echo $module->id;?>(div_value){
	div_value.className = 'suggest_link';
}

function setSearch<?php echo $module->id;?>(value){
	document.getElementById('search_suggest<?php echo $module->id;?>').innerHTML = '';
	document.getElementById('frmSearch'+<?php echo $module->id;?>).submit();
}

function closeSearch<?php echo $module->id;?>(){
	RSFSearch<?php echo $module->id;?>.slideOut();
}

window.addEvent('domready', function() {
	$('search_suggest<?php echo $module->id;?>').setStyle('height','auto');
	RSFSearch<?php echo $module->id;?> = new Fx.Slide('search_suggest<?php echo $module->id;?>').hide();
	var parent = $('search_suggest<?php echo $module->id;?>').getParent('div').setStyle('top','100%').setAttribute('class','uk-position-z-index uk-width-1-1');
});
</script>

<div id="rssearch" class="<?php echo $class_suffix; ?>">
	<form id="frmSearch<?php echo $module->id;?>" action="<?php echo JRoute::_('index.php?option=com_rssearch'); ?>" method="post" accept-charset="utf-8" class="uk-search uk-search-navbar uk-width-1-1">
        <input placeholder="<?php echo JTEXT::_('SEARCHWORD').' ...' ?>" type="search" id="rsf_inp<?php echo $module->id;?>" name="search" class="font uk-search-input <?php if ($show_loop == 'yes') echo 'rss_loop'; ?>" onkeyup="searchSuggest<?php echo $module->id;?>();" autocomplete="off" autofocus />
        <?php if($show_btn == 'yes') { ?>
            <button type="submit" id="cmdSearch" class="btn btn uk-border-rounded uk-button uk-button-primary uk-margin-top uk-width-1-1 font"><?php echo JText::_('RSF_MODULE_SEARCH_BTN');?></button>
        <?php }?>
		<div id="search_suggest<?php echo $module->id;?>" class="uk-position-absolute uk-width-1-1 uk-card uk-card-body uk-card-secondary"></div>

		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="module_id" value="<?php echo $module->id;?>" />
		<?php if ($itemid) { ?>
		<input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />
		<?php } ?>
	</form>
</div>