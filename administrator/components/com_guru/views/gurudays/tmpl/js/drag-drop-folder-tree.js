/************************************************************************************************************
Drag and drop folder tree
Copyright (C) 2006  DTHMLGoodies.com, Alf Magne Kalleland

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
written by Alf Magne Kalleland.

Alf Magne Kalleland, 2006
Owner of DHTMLgoodies.com


************************************************************************************************************/

	var JSTreeObj;
	var treeUlCounter = 0;
	var nodeId = 1;

	/* Constructor */
	function JSDragDropTree()
	{
		var idOfTree;
		var imageFolder;
		var folderImage;
		var plusImage;
		var minusImage;
		var maximumDepth;
		var dragNode_source;
		var dragNode_parent;
		var dragNode_sourceNextSib;
		var dragNode_noSiblings;
		var ajaxObjects;

		var dragNode_destination;
		var floatingContainer;
		var dragDropTimer;
		var dropTargetIndicator;
		var insertAsSub;
		var indicator_offsetX;
		var indicator_offsetX_sub;
		var indicator_offsetY;
		this.imageFolder = 'components/com_guru/views/gurudays/tmpl/images/';
		this.folderImage = ''; //'dhtmlgoodies_folder.gif';
		this.plusImage = 'dhtmlgoodies_plus.gif';
		this.minusImage = 'dhtmlgoodies_minus.gif';
		this.maximumDepth = 6;
		var messageMaximumDepthReached;
		var filePathRenameItem;
		var filePathDeleteItem;
		var additionalRenameRequestParameters = {};
		var additionalDeleteRequestParameters = {};

		var renameAllowed;
		var deleteAllowed;
		var currentlyActiveItem;
		var contextMenu;
		var currentItemToEdit;		// Reference to item currently being edited(example: renamed)
		var helpObj;
		this.contextMenu = false;

		this.floatingContainer = document.createElement('UL');
		this.floatingContainer.style.position = 'absolute';
		this.floatingContainer.style.display='none';
		this.floatingContainer.id = 'floatingContainer';

		document.body.appendChild(this.floatingContainer);

		this.insertAsSub = false;
		this.dragDropTimer = -1;
		this.dragNode_noSiblings = false;
		this.currentItemToEdit = false;



		if(document.all){
			this.indicator_offsetX = 2;	// Offset position of small black lines indicating where nodes would be dropped.
			this.indicator_offsetX_sub = 4;
			this.indicator_offsetY = 4;
		}else{
			this.indicator_offsetX = 1;	// Offset position of small black lines indicating where nodes would be dropped.
			this.indicator_offsetX_sub = 3;
			this.indicator_offsetY = 4;
		}
		if(navigator.userAgent.indexOf('Opera')>=0){
			this.indicator_offsetX = 2;	// Offset position of small black lines indicating where nodes would be dropped.
			this.indicator_offsetX_sub = 3;
			this.indicator_offsetY = -7;
		}

		this.messageMaximumDepthReached = ''; // Use '' if you don't want to display a message

		this.renameAllowed = true;
		this.deleteAllowed = true;
		this.currentlyActiveItem = false;
		this.filePathRenameItem = '';
		this.filePathDeleteItem = '';
		this.ajaxObjects = new Array();
		this.helpObj = false;

		this.RENAME_STATE_BEGIN = 1;
		this.RENAME_STATE_CANCELED = 2;
		this.RENAME_STATE_REQUEST_SENDED = 3;
		this.renameState = null;
	}




	/* JSDragDropTree class */
	JSDragDropTree.prototype = {
		// {{{ addEvent()
	    /**
	     *
	     *  This function adds an event listener to an element on the page.
	     *
	     *	@param Object whichObject = Reference to HTML element(Which object to assigne the event)
	     *	@param String eventType = Which type of event, example "mousemove" or "mouseup"
	     *	@param functionName = Name of function to execute.
	     *
	     * @public
	     */
		addEvent_tree : function(whichObject,eventType,functionName)
		{
		  if(whichObject.attachEvent){
		    whichObject['e'+eventType+functionName] = functionName;
		    whichObject[eventType+functionName] = function(){whichObject['e'+eventType+functionName]( window.event );}
		    whichObject.attachEvent( 'on'+eventType, whichObject[eventType+functionName] );
		  } else
		    whichObject.addEventListener(eventType,functionName,false);
		}
		// }}}
		,
		// {{{ removeEvent()
	    /**
	     *
	     *  This function removes an event listener from an element on the page.
	     *
	     *	@param Object whichObject = Reference to HTML element(Which object to assigne the event)
	     *	@param String eventType = Which type of event, example "mousemove" or "mouseup"
	     *	@param functionName = Name of function to execute.
	     *
	     * @public
	     */
		removeEvent_tree : function(whichObject,eventType,functionName)
		{
		  if(whichObject.detachEvent){
		    whichObject.detachEvent('on'+eventType, whichObject[eventType+functionName]);
		    whichObject[eventType+functionName] = null;
		  } else
		    whichObject.removeEventListener(eventType,functionName,false);
		}
		,
		Get_Cookie : function(name) {
		   var start = document.cookie.indexOf(name+"=");
		   var len = start+name.length+1;
		   if ((!start) && (name != document.cookie.substring(0,name.length))) return null;
		   if (start == -1) return null;
		   var end = document.cookie.indexOf(";",len);
		   if (end == -1) end = document.cookie.length;
		   return unescape(document.cookie.substring(len,end));
		}
		,
		// This function has been slightly modified
		Set_Cookie : function(name,value,expires,path,domain,secure) {
			expires = expires * 60*60*24*1000;
			var today = new Date();
			var expires_date = new Date( today.getTime() + (expires) );
		    var cookieString = name + "=" +escape(value) +
		       ( (expires) ? ";expires=" + expires_date.toGMTString() : "") +
		       ( (path) ? ";path=" + path : "") +
		       ( (domain) ? ";domain=" + domain : "") +
		       ( (secure) ? ";secure" : "");
		    document.cookie = cookieString;
		}
		,
		setFileNameRename : function(newFileName)
		{
			this.filePathRenameItem = newFileName;
		}
		,
		setFileNameDelete : function(newFileName)
		{
			this.filePathDeleteItem = newFileName;
		}
		,
		setAdditionalRenameRequestParameters : function(requestParameters)
		{
			this.additionalRenameRequestParameters = requestParameters;
		}
		,
		setAdditionalDeleteRequestParameters : function(requestParameters)
		{
			this.additionalDeleteRequestParameters = requestParameters;
		}
		,setRenameAllowed : function(renameAllowed)
		{
			this.renameAllowed = renameAllowed;
		}
		,
		setDeleteAllowed : function(deleteAllowed)
		{
			this.deleteAllowed = deleteAllowed;
		}
		,setMaximumDepth : function(maxDepth)
		{
			this.maximumDepth = maxDepth;
		}
		,setMessageMaximumDepthReached : function(newMessage)
		{
			this.messageMaximumDepthReached = newMessage;
		}
		,
		setImageFolder : function(path)
		{
			this.imageFolder = path;
		}
		,
		setFolderImage : function(imagePath)
		{
			this.folderImage = imagePath;
		}
		,
		setPlusImage : function(imagePath)
		{
			this.plusImage = imagePath;
		}
		,
		setMinusImage : function(imagePath)
		{
			this.minusImage = imagePath;
		}
		,
		setTreeId : function(idOfTree)
		{
			this.idOfTree = idOfTree;
		}
		,
		expandAll : function()
		{
			var menuItems = document.getElementById(this.idOfTree).getElementsByTagName('LI');
			for(var no=0;no<menuItems.length;no++){
				var subItems = menuItems[no].getElementsByTagName('UL');
				if(subItems.length>0 && subItems[0].style.display!='block'){
					JSTreeObj.showHideNode(false,menuItems[no].id);
				}
			}
		}
		,
		collapseAll : function()
		{
			var menuItems = document.getElementById(this.idOfTree).getElementsByTagName('LI');
			for(var no=0;no<menuItems.length;no++){
				var subItems = menuItems[no].getElementsByTagName('UL');
				if(subItems.length>0 && subItems[0].style.display=='block'){
					JSTreeObj.showHideNode(false,menuItems[no].id);
				}
			}
		}
		,
		/*
		Find top pos of a tree node
		*/
		getTopPos : function(obj){
			var top = obj.offsetTop/1;
			while((obj = obj.offsetParent) != null){
				if(obj.tagName!='HTML')top += obj.offsetTop;
			}
			if(document.all)top = top/1 + 13; else top = top/1 + 4;
			return top;
		}
		,
		/*
		Find left pos of a tree node
		*/
		getLeftPos : function(obj){
			var left = obj.offsetLeft/1 + 1;
			while((obj = obj.offsetParent) != null){
				if(obj.tagName!='HTML')left += obj.offsetLeft;
			}

			if(document.all)left = left/1 - 2;
			return left;
		}

		,
		showHideNode : function(e,inputId)
		{
			if(inputId){
				if(!document.getElementById(inputId))return;
				thisNode = document.getElementById(inputId).getElementsByTagName('IMG')[0];
			}else {
				thisNode = this;
				if(this.tagName=='A')thisNode = this.parentNode.getElementsByTagName('IMG')[0];

			}
			if(thisNode.style.visibility=='hidden')return;
			var parentNode = thisNode.parentNode;
			inputId = parentNode.id.replace(/[^0-9]/g,'');
			if(thisNode.src.indexOf(JSTreeObj.plusImage)>=0){
				thisNode.src = thisNode.src.replace(JSTreeObj.plusImage,JSTreeObj.minusImage);
				var ul = parentNode.getElementsByTagName('UL')[0];
				ul.style.display='block';
				if(!initExpandedNodes)initExpandedNodes = ',';
				if(initExpandedNodes.indexOf(',' + inputId + ',')<0) initExpandedNodes = initExpandedNodes + inputId + ',';
			}else{
				thisNode.src = thisNode.src.replace(JSTreeObj.minusImage,JSTreeObj.plusImage);
				parentNode.getElementsByTagName('UL')[0].style.display='none';
				initExpandedNodes = initExpandedNodes.replace(',' + inputId,'');
			}
			JSTreeObj.Set_Cookie('dhtmlgoodies_expandedNodes',initExpandedNodes,500);
			return false;
		}
		,
		/* Initialize drag */
		initDrag : function(e)
		{
			if(document.all)e = event;
			//alert(1);
			var subs = JSTreeObj.floatingContainer.getElementsByTagName('LI');
			//alert(2);
			if(subs.length>0){
				if(JSTreeObj.dragNode_sourceNextSib){
					JSTreeObj.dragNode_parent.insertBefore(JSTreeObj.dragNode_source,JSTreeObj.dragNode_sourceNextSib);
				}else{
					JSTreeObj.dragNode_parent.appendChild(JSTreeObj.dragNode_source);
				}
			}

			JSTreeObj.dragNode_source = this.parentNode;
			JSTreeObj.dragNode_parent = this.parentNode.parentNode;
			JSTreeObj.dragNode_sourceNextSib = false;


			if(JSTreeObj.dragNode_source.nextSibling)JSTreeObj.dragNode_sourceNextSib = JSTreeObj.dragNode_source.nextSibling;
			JSTreeObj.dragNode_destination = false;
			JSTreeObj.dragDropTimer = 0;
			JSTreeObj.timerDrag();
			return false;
		}
		,
		timerDrag : function()
		{
			if(this.dragDropTimer>=0 && this.dragDropTimer<10){
				this.dragDropTimer = this.dragDropTimer + 1;
				setTimeout('JSTreeObj.timerDrag()',20);
				return;
			}
			if(this.dragDropTimer==10)
			{
				JSTreeObj.floatingContainer.style.display='block';
				JSTreeObj.floatingContainer.appendChild(JSTreeObj.dragNode_source);
			}
		}
		,
		moveDragableNodes : function(e)
		{
			if(JSTreeObj.dragDropTimer<10)return;
			if(document.all)e = event;

			dragDrop_x = e.pageX;
			dragDrop_y = e.pageY;
			JSTreeObj.floatingContainer.style.left = dragDrop_x + 'px';
			JSTreeObj.floatingContainer.style.top = dragDrop_y + 'px';

			var thisObj = this;
			if(thisObj.tagName=='A' || thisObj.tagName=='IMG')thisObj = thisObj.parentNode;
			else
			thisObj = false;

			if(thisObj && thisObj.id)
			{

			JSTreeObj.dragNode_noSiblings = false;
			var tmpVar = thisObj.getAttribute('noSiblings');
			if(!tmpVar)tmpVar = thisObj.noSiblings;
			if(tmpVar=='true')JSTreeObj.dragNode_noSiblings=true;

				JSTreeObj.dragNode_destination = thisObj;
				var img = thisObj.getElementsByTagName('IMG')[1];
				var tmpObj= JSTreeObj.dropTargetIndicator;

				var drop_indicator = document.getElementById('cucu');
				drop_indicator.style.display='block';
				tmpObj.style.display='block';

				var eventSourceObj = this;
				if(JSTreeObj.dragNode_noSiblings && eventSourceObj.tagName=='IMG')eventSourceObj = eventSourceObj.nextSibling;

				var tmpImg = tmpObj.getElementsByTagName('IMG')[0];
				if(this.tagName=='A' || JSTreeObj.dragNode_noSiblings){
					tmpImg.src = tmpImg.src.replace('ind1','ind2');
					JSTreeObj.insertAsSub = true;
					tmpObj.style.left = (JSTreeObj.getLeftPos(eventSourceObj) + JSTreeObj.indicator_offsetX_sub) + 'px';
				}else{
					tmpImg.src = tmpImg.src.replace('ind2','ind1');
					JSTreeObj.insertAsSub = false;
					tmpObj.style.left = (JSTreeObj.getLeftPos(eventSourceObj) + JSTreeObj.indicator_offsetX) + 'px';
				}

				tmpObj.style.top = (JSTreeObj.getTopPos(thisObj) + JSTreeObj.indicator_offsetY) + 'px';
			}

			return false;

		}
		,
		dropDragableNodes:function()
		{
			if(JSTreeObj.dragDropTimer<10){
				JSTreeObj.dragDropTimer = -1;
				return;
			}
			var showMessage = false;

			var tmpVar = JSTreeObj.dragNode_source.getAttribute('isLeaf');
			var tmpVar1 = JSTreeObj.dragNode_destination.getAttribute('isLeaf');

			/* hack LMS today
			if( (JSTreeObj.dragNode_destination.id=='node0' && tmpVar=='true') || (tmpVar=='true' && tmpVar1=='true') || (tmpVar=='false' && tmpVar1=='false'))
				{
					JSTreeObj.dragNode_destination = false;
					showMessage = true; 	// Used later down in this function
				}
			*/

			if(JSTreeObj.dragNode_destination){	// Check depth

				var countUp = JSTreeObj.dragDropCountLevels(JSTreeObj.dragNode_destination,'up');
				var countDown = JSTreeObj.dragDropCountLevels(JSTreeObj.dragNode_source,'down');
				var countLevels = countUp/1 + countDown/1 + (JSTreeObj.insertAsSub?1:0);

				//alert(JSTreeObj.dragNode_destination.lang);

				if(countLevels>JSTreeObj.maximumDepth){
					JSTreeObj.dragNode_destination = false;
					showMessage = true; 	// Used later down in this function
				}
			}

			if(JSTreeObj.dragNode_destination){

			JSTreeObj.insertAsSub = false;

				if(JSTreeObj.insertAsSub){
					var uls = JSTreeObj.dragNode_destination.getElementsByTagName('UL');
					//if(uls.length>0){
						ul = uls[0];
						ul.style.display='block';

						var lis = ul.getElementsByTagName('LI');

						if(lis.length>0){	// Sub elements exists - drop dragable node before the first one
							ul.insertBefore(JSTreeObj.dragNode_source,lis[0]);
						}else {	// No sub exists - use the appendChild method - This line should not be executed unless there's something wrong in the HTML, i.e empty <ul>
							ul.appendChild(JSTreeObj.dragNode_source);
						}
					/*}else{
						alert(2);
						var ul = document.createElement('UL');
						ul.style.display='block';
						JSTreeObj.dragNode_destination.appendChild(ul);
						ul.appendChild(JSTreeObj.dragNode_source);
					}*/
					var img = JSTreeObj.dragNode_destination.getElementsByTagName('IMG')[0];
					img.style.visibility='visible';
					img.src = img.src.replace(JSTreeObj.plusImage,JSTreeObj.minusImage);


				}else{

			if(tmpVar=='true') // we're moving a screen
			{

				var is_ok = true;
				if(JSTreeObj.dragNode_destination.id=='node0' && tmpVar=='true')
					{
						is_ok = false;
					}

				if(is_ok)
				{ //testing the rules

					if(JSTreeObj.dragNode_destination.nextSibling){

						if(JSTreeObj.dragNode_destination.getAttribute('isLeaf')=='false')
							{
								var add_first_leaf = JSTreeObj.dragNode_destination.getElementsByTagName('UL');
								add_first_leaf[0].insertBefore(JSTreeObj.dragNode_source,add_first_leaf[0].firstChild);
							}
						else
							{
								var nextSib = JSTreeObj.dragNode_destination.nextSibling;
								//alert(JSTreeObj.dragNode_destination.innerHTML);
								nextSib.parentNode.insertBefore(JSTreeObj.dragNode_source,nextSib);
								//nextSib.parentNode.insertBefore(JSTreeObj.dragNode_source,JSTreeObj.dragNode_destination);
							}

					}else{
							if(JSTreeObj.dragNode_destination.getAttribute('isLeaf')=='false')
								{
									//alert(66);
									var add_first_leaf = JSTreeObj.dragNode_destination.getElementsByTagName('UL');
									add_first_leaf[0].insertBefore(JSTreeObj.dragNode_source,add_first_leaf[0].firstChild);
								}
							else
								{
									//alert(0);
									//alert(JSTreeObj.dragNode_destination.innerHTML);
									var add_first_leaf = JSTreeObj.dragNode_destination.getElementsByTagName('UL');
									//alert(add_first_leaf[0].id);
									//add_first_leaf[0].appendChild(JSTreeObj.dragNode_source);
									JSTreeObj.dragNode_destination.parentNode.appendChild(JSTreeObj.dragNode_source);
									//JSTreeObj.dragNode_destination.parentNode.appendChild(JSTreeObj.dragNode_source);
								}
						}


					} // the rules okay
					else
					{
						alert('Please make sure you are in the course tree area!');
						if(JSTreeObj.dragNode_sourceNextSib){
							JSTreeObj.dragNode_parent.insertBefore(JSTreeObj.dragNode_source,JSTreeObj.dragNode_sourceNextSib);
						}else{
							JSTreeObj.dragNode_parent.appendChild(JSTreeObj.dragNode_source);
						}

					}

				} // we're moving a day
				else if(tmpVar=='false')
					{

						var is_ok = true;
						if(tmpVar1=='false' && tmpVar=='false')
							{
								is_ok = false;
							}

						// it's an appending to the tree
						if(JSTreeObj.dragNode_destination.nextSibling==null && tmpVar1=='false')
							{ // but dropping on a group
								//alert('appending a group to last group');
								var tree_root = document.getElementById('tree_ul_0');
								tree_root.appendChild(JSTreeObj.dragNode_source);
							}
						// still an apending to the tree
						else if (JSTreeObj.dragNode_destination.parentNode.parentNode.nextSibling==null && tmpVar1=='true')
							{ // but dropping on a screen
								//alert('appending a group to a screen from the last group');
								var tree_root = document.getElementById('tree_ul_0');
								tree_root.appendChild(JSTreeObj.dragNode_source);
							}
						// doing a drop between two groups
						else if(JSTreeObj.dragNode_destination.nextSibling && tmpVar1=='false')
							{// dropping on a specific group
								//alert('dropping a group on an inside group');
								var nextSib = JSTreeObj.dragNode_destination.nextSibling;
								nextSib.parentNode.insertBefore(JSTreeObj.dragNode_source,nextSib);
							}
						else if	(JSTreeObj.dragNode_destination.nextSibling && tmpVar1=='true')
							{ // dropping on a screen from that group
								//alert('dropping a group on a screen inside a group');

								// The insert of an Empty day was not working properly between two screens
								//var nextSib = JSTreeObj.dragNode_destination.parentNode.nextSibling;
								var nextSib = JSTreeObj.dragNode_destination.parentNode.parentNode.nextSibling;
								nextSib.parentNode.insertBefore(JSTreeObj.dragNode_source,nextSib);
							}
						// we're on the root now
						else
							{
								//alert('dropping the group to the top of the tree');
								var tree_root = document.getElementById('tree_ul_0');
								tree_root.insertBefore(JSTreeObj.dragNode_source, tree_root.firstChild);
							}

					} // end moving a day

				/* Clear parent object */
				var tmpObj = JSTreeObj.dragNode_parent;
				var lis = tmpObj.getElementsByTagName('LI');
				if(lis.length==0){
					var img = tmpObj.parentNode.getElementsByTagName('IMG')[0];
					//hack LMS img.style.visibility='hidden';	// Hide [+],[-] icon
					//tmpObj.parentNode.removeChild(tmpObj);
					tmpObj.innerHTML = '';
				}

				}

			}else{
				// Putting the item back to it's original location

				if(JSTreeObj.dragNode_sourceNextSib){
					JSTreeObj.dragNode_parent.insertBefore(JSTreeObj.dragNode_source,JSTreeObj.dragNode_sourceNextSib);
				}else{
					JSTreeObj.dragNode_parent.appendChild(JSTreeObj.dragNode_source);
				}

			}

			var drop_indicator = document.getElementById('cucu');
			drop_indicator.style.display='none';

			JSTreeObj.dropTargetIndicator.style.display='none';
			JSTreeObj.dragDropTimer = -1;
			if(showMessage && JSTreeObj.messageMaximumDepthReached)alert(JSTreeObj.messageMaximumDepthReached);


			// hack LMS - using AJAX to save the new order
			var ajaxObjects = new Array();

			saveString = JSTreeObj.getNodeOrders();

			document.getElementById("toolbar-save").style.display = "none";
			document.getElementById("toolbar-apply").style.display = "none";

			//------------------------------------------------
			number = "1";
			for_link = "saveString"+ number +"=";
			var temp_array = saveString.split(",");
			i=0;
			poz = 0;

			while(temp_array[i]){
				if(poz == 20){
					number ++;
					for_link += "saveString"+ number +"=";
					poz = 0;
				}

				for_link += temp_array[i]+"";
				if(poz + 1 != 21){
					for_link += ",";
				}
				i++;
				poz++;
			}
			for_link = for_link.replace(/,saveString/g, "&saveString");
			//------------------------------------------------

			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			var save_type = "s";

			if(tmpVar == 'false'){
				var url = 'index.php?option=com_guru&controller=guruPrograms&tmpl=component&format=raw&task=saveOrderG&' + for_link;
				var save_type = "g";
			}
			else{
				var url = 'index.php?option=com_guru&controller=guruPrograms&tmpl=component&format=raw&task=saveOrderS&' + for_link;
				var save_type = "s";
				// link for ajax
			}

			if(save_type == "g"){
				ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
				//ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
				ajaxObjects[ajaxIndex].onCompletion = function()
						{
							//treeObj._____addElement(day_title, day_ordering, newdayid) ;

							//dom_refresh();
							//alert('order saved');

							/*treeObj.initTree(); */
						} ;	// Specify function that will be executed after file has been found
				
				ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function
			}
			else if(save_type == "s"){
				var step = 1;
				var last_order = 0;
				var for_link_new = "";

				$(".loading-ordering").show();

				while($("#tree_ul_"+step).length != 0){
					var module_id = $("#tree_ul_"+step).parent().attr("leafid");
					module_id = parseInt(module_id);

					last_order_url = last_order;

					if(step == 1){
						last_order_url = "0";
					}

					$("#tree_ul_"+step+" li").each(function(i){
   						var lesson_id = $(this).attr('leafid');

   						if(parseInt(lesson_id) > 0){
   							for_link_new += parseInt(lesson_id)+",";
   						}

   						last_order ++;
					});
					
					url_new = 'index.php?option=com_guru&controller=guruPrograms&tmpl=component&format=raw&task=saveOrderS&last_order='+last_order_url+'&module_id='+module_id+'&ids=' + for_link_new;
					
					$.ajax({
						url: url_new,
						async: false,
						dataType: 'json',
						success: function (json) {}
					});

					step++;
					for_link_new = "";
				}

				$(".loading-ordering").hide();

				return false;
			}

		}
		,
		createDropIndicator : function()
		{
			this.dropTargetIndicator = document.createElement('DIV');
			this.dropTargetIndicator.style.position = 'absolute';
			this.dropTargetIndicator.style.display='none';
			var img = document.createElement('IMG');
			img.src = this.imageFolder + 'dragDrop_ind1.gif';
			img.id = 'dragDropIndicatorImage';
			this.dropTargetIndicator.appendChild(img);

			drop_indicator = document.getElementById('cucu');

			document.body.appendChild(this.dropTargetIndicator);
		}
		,
		dragDropCountLevels : function(obj,direction,stopAtObject){
			var countLevels = 0;
			if(direction=='up'){
				while(obj.parentNode && obj.parentNode!=stopAtObject){
					obj = obj.parentNode;

					if(obj.tagName=='UL')
						{

							//var noUL = false;
							//var tmpVar = obj.getAttribute('noUL');
							//if(!tmpVar)tmpVar = obj.noUL;
							//if(tmpVar=='true')
								countLevels = countLevels/1 +1;
						}
				}
				return countLevels;
			}

			if(direction=='down'){
				var subObjects = obj.getElementsByTagName('LI');
				for(var no=0;no<subObjects.length;no++){

							//var noUL = false;
							//var tmpVar = subObjects[no].getAttribute('noUL');
							//if(!tmpVar)tmpVar = subObjects[no].noUL;
							//if(tmpVar=='true')

					countLevels = Math.max(countLevels,JSTreeObj.dragDropCountLevels(subObjects[no],"up",obj));
				}
				return countLevels;
			}
		}
		,
		cancelEvent : function()
		{
			return false;
		}
		,
		cancelSelectionEvent : function()
		{

			if(JSTreeObj.dragDropTimer<10)return true;
			return false;
		}
		,getNodeOrders : function(initObj,saveString)
		{

			if(!saveString)var saveString = '';
			if(!initObj){
				initObj = document.getElementById(this.idOfTree);

			}
			var lis = initObj.getElementsByTagName('LI');

			if(lis.length>0){
				var li = lis[0];

				while(li){
					if(li.id){

						if(saveString.length>0)saveString = saveString + ',';
						var numericID = li.id.replace(/[^0-9]/gi,'');
						if(numericID.length==0)numericID='A';
						var numericParentID = li.parentNode.parentNode.id.replace(/[^0-9]/gi,'');
						if(numericID!='0'){
							//alert(li.id);
							saveString = saveString + numericID;
							saveString = saveString + '-';
							if(li.parentNode.id!=this.idOfTree)saveString = saveString + numericParentID; else saveString = saveString + '0';

							// hack LMS
							var LeafId = li.getAttribute('LeafId');
							var isLeaf = li.getAttribute('isLeaf');
							saveString = saveString + ':'+ isLeaf + ':' + LeafId + ':';

						}

						var ul = li.getElementsByTagName('UL');

						if(ul.length>0){
							saveString = this.getNodeOrders(ul[0],saveString);
						}

					}

					li = li.nextSibling;
				} // endif lenght>0
			}

			if(initObj.id == this.idOfTree){
				return saveString;

			}
			return saveString;
		}
		,highlightItem : function(inputObj,e)
		{
			if(JSTreeObj.currentlyActiveItem)JSTreeObj.currentlyActiveItem.className = '';
			this.className = 'highlightedNodeItem';
			JSTreeObj.currentlyActiveItem = this;
		}
		,
		removeHighlight : function()
		{
			if(JSTreeObj.currentlyActiveItem)JSTreeObj.currentlyActiveItem.className = '';
			JSTreeObj.currentlyActiveItem = false;
		}
		,
		hasSubNodes : function(obj)
		{
			var subs = obj.getElementsByTagName('LI');
			if(subs.length>0)return true;
			return false;
		}
		,
		deleteItem : function(obj1,obj2)
		{
			var message = 'Click OK to delete item ' + obj2.innerHTML;
			if(this.hasSubNodes(obj2.parentNode)) message = message + ' and it\'s sub nodes';
			if(confirm(message)){
				this.__deleteItem_step2(obj2.parentNode);	// Sending <LI> tag to the __deleteItem_step2 method
			}

		}
		,
		__refreshDisplay : function(obj)
		{
			//alert(obj.id);

			if(this.hasSubNodes(obj))return;

			var img = obj.getElementsByTagName('IMG')[0];
			// hack LMS img.style.visibility = 'hidden';
		}
		,
		__deleteItem_step2 : function(obj)
		{

			var saveString = obj.id.replace(/[^0-9]/gi,'');

			var lis = obj.getElementsByTagName('LI');
			for(var no=0;no<lis.length;no++){
				saveString = saveString + ',' + lis[no].id.replace(/[^0-9]/gi,'');
			}

			// Creating ajax object and send items
			var ajaxIndex = JSTreeObj.ajaxObjects.length;
			JSTreeObj.ajaxObjects[ajaxIndex] = new sack();
			JSTreeObj.ajaxObjects[ajaxIndex].method = "GET";
			JSTreeObj.ajaxObjects[ajaxIndex].setVar("deleteIds", saveString);
			JSTreeObj.__addAdditionalRequestParameters(JSTreeObj.ajaxObjects[ajaxIndex], JSTreeObj.additionalDeleteRequestParameters);
			JSTreeObj.ajaxObjects[ajaxIndex].requestFile = JSTreeObj.filePathDeleteItem;	// Specifying which file to get
			JSTreeObj.ajaxObjects[ajaxIndex].onCompletion = function() { JSTreeObj.__deleteComplete(ajaxIndex,obj); } ;	// Specify function that will be executed after file has been found
			JSTreeObj.ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function


		}
		,
		__deleteComplete : function(ajaxIndex,obj)
		{
			if(this.ajaxObjects[ajaxIndex].response!='OK'){
				alert('ERROR WHEN TRYING TO DELETE NODE: ' + this.ajaxObjects[ajaxIndex].response); 	// Rename failed
			}else{
				var parentRef = obj.parentNode.parentNode;
				obj.parentNode.removeChild(obj);
				//alert(parentRef.id);
				this.__refreshDisplay(parentRef);

			}

		}
		,
		__deleteOfScreen : function(obj)
		{
			//var message = 'Click OK to delete item ';// + obj2.innerHTML;
			//if(confirm(message)){
				//this.__deleteItem_step2(obj2.parentNode);	// Sending <LI> tag to the __deleteItem_step2 method
				//alert('del');
				var parentRef = obj.parentNode.parentNode;
				obj.parentNode.removeChild(obj);
				//alert(parentRef.id);
				this.__refreshDisplay(parentRef);
			//}
		}
		,
		__renameComplete : function(ajaxIndex)
		{
			if(this.ajaxObjects[ajaxIndex].response!='OK'){
				alert('ERROR WHEN TRYING TO RENAME NODE: ' + this.ajaxObjects[ajaxIndex].response); 	// Rename failed
			}
		}
		,
		__saveTextBoxChanges : function(e,inputObj)
		{
			if(!inputObj && this)inputObj = this;
			if(document.all)e = event;
			if(e.keyCode && e.keyCode==27){
				JSTreeObj.__cancelRename(e,inputObj);
				return;
			}
			inputObj.style.display='none';
			inputObj.nextSibling.style.visibility='visible';
			if(inputObj.value.length>0){
				inputObj.nextSibling.innerHTML = inputObj.value;
				// Send changes to the server.
				if (JSTreeObj.renameState != JSTreeObj.RENAME_STATE_BEGIN) {
					return;
				}
				JSTreeObj.renameState = JSTreeObj.RENAME_STATE_REQUEST_SENDED;

				var ajaxIndex = JSTreeObj.ajaxObjects.length;
				JSTreeObj.ajaxObjects[ajaxIndex] = new sack();
				JSTreeObj.ajaxObjects[ajaxIndex].method = "GET";
				JSTreeObj.ajaxObjects[ajaxIndex].setVar("renameId", inputObj.parentNode.id.replace(/[^0-9]/gi,''));
				JSTreeObj.ajaxObjects[ajaxIndex].setVar("newName", inputObj.value);
				JSTreeObj.__addAdditionalRequestParameters(JSTreeObj.ajaxObjects[ajaxIndex], JSTreeObj.additionalRenameRequestParameters);
				JSTreeObj.ajaxObjects[ajaxIndex].requestFile = JSTreeObj.filePathRenameItem;	// Specifying which file to get
				JSTreeObj.ajaxObjects[ajaxIndex].onCompletion = function() { JSTreeObj.__renameComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
				JSTreeObj.ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function



			}
		}
		,
		__cancelRename : function(e,inputObj)
		{
			JSTreeObj.renameState = JSTreeObj.RENAME_STATE_CANCELD;
			if(!inputObj && this)inputObj = this;
			inputObj.value = JSTreeObj.helpObj.innerHTML;
			inputObj.nextSibling.innerHTML = JSTreeObj.helpObj.innerHTML;
			inputObj.style.display = 'none';
			inputObj.nextSibling.style.visibility = 'visible';
		}
		,
		__renameCheckKeyCode : function(e)
		{
			if(document.all)e = event;
			if(e.keyCode==13){	// Enter pressed
				JSTreeObj.__saveTextBoxChanges(false,this);
			}
			if(e.keyCode==27){	// ESC pressed
				JSTreeObj.__cancelRename(false,this);
			}
		}
		,
		__createTextBox : function(obj)
		{
			var textBox = document.createElement('INPUT');
			textBox.className = 'folderTreeTextBox';
			textBox.value = obj.innerHTML;
			obj.parentNode.insertBefore(textBox,obj);
			textBox.id = 'textBox' + obj.parentNode.id.replace(/[^0-9]/gi,'');
			textBox.onblur = this.__saveTextBoxChanges;
			textBox.onkeydown = this.__renameCheckKeyCode;
			this.__renameEnableTextBox(obj);
		}
		,
		__renameEnableTextBox : function(obj)
		{
			JSTreeObj.renameState = JSTreeObj.RENAME_STATE_BEGIN;
			obj.style.visibility = 'hidden';
			obj.previousSibling.value = obj.innerHTML;
			obj.previousSibling.style.display = 'inline';
			obj.previousSibling.select();
		}
		,
		renameItem : function(obj1,obj2)
		{
			currentItemToEdit = obj2.parentNode;	// Reference to the <li> tag.
			if(!obj2.previousSibling || obj2.previousSibling.tagName.toLowerCase()!='input'){
				this.__createTextBox(obj2);
			}else{
				this.__renameEnableTextBox(obj2);
			}
			this.helpObj.innerHTML = obj2.innerHTML;

		}
		,
		initTree : function(how_many, node_tree)
		{
			JSTreeObj = this;

			JSTreeObj.createDropIndicator();
			document.documentElement.onselectstart = JSTreeObj.cancelSelectionEvent;
			document.documentElement.ondragstart = JSTreeObj.cancelEvent;
			document.documentElement.onmousedown = JSTreeObj.removeHighlight;
			var nodeId = 0;
			var dhtmlgoodies_tree = document.getElementById(this.idOfTree);
			var menuItems = dhtmlgoodies_tree.getElementsByTagName('LI');	// Get an array of all menu items

			//alert(menuItems.length);

			for(var no=0;no<menuItems.length;no++){
				// No children var set ?
				var noChildren = false;
				var tmpVar = menuItems[no].getAttribute('noChildren');
				if(!tmpVar)tmpVar = menuItems[no].noChildren;
				if(tmpVar=='true')noChildren=true;
				// No drag var set ?
				var noDrag = false;
				var tmpVar = menuItems[no].getAttribute('noDrag');
				if(!tmpVar)tmpVar = menuItems[no].noDrag;
				if(tmpVar=='true')noDrag=true;

				nodeId++;
				var subItems = menuItems[no].getElementsByTagName('UL');

				var tmpLeaf = menuItems[no].getAttribute('isLeaf');

				if(tmpLeaf=='false')
					img = document.createElement('IMG');

				if(how_many==1)
					{


						if(tmpLeaf=='false')
							{
								img.src = this.imageFolder + this.minusImage;
								img.onclick = JSTreeObj.showHideNode;
							}

				if(subItems.length==0)
					{
						if(tmpLeaf=='false')
							{
								var container = menuItems[no];
								var new_element = document.createElement('ul');
								new_element.id='tree_ul_' + treeUlCounter;

								new_element.style.display = 'block';
								treeUlCounter++;
								//new_element.setAttribute('isLeaf', 'false');
								container.appendChild(new_element);
							}
						//alert(menuItems[no].innerHTML);
					}
				else
					{
						subItems[0].id = 'tree_ul_' + treeUlCounter;
						treeUlCounter++;
					}




					}

				var aTag = menuItems[no].getElementsByTagName('A')[0];
				aTag.id = 'nodeATag' + menuItems[no].id.replace(/[^0-9]/gi,'');
				if(!noDrag)aTag.onmousedown = JSTreeObj.initDrag;
				if(!noChildren)aTag.onmousemove = JSTreeObj.moveDragableNodes;

				if(tmpLeaf=='false' && node_tree == 0)
					menuItems[no].insertBefore(img,aTag);
				//menuItems[no].id = 'dhtmlgoodies_treeNode' + nodeId;
				var folderImg = document.createElement('IMG');
				if(!noDrag)folderImg.onmousedown = JSTreeObj.initDrag;
				folderImg.onmousemove = JSTreeObj.moveDragableNodes;
			}

			initExpandedNodes = this.Get_Cookie('dhtmlgoodies_expandedNodes');

			if(initExpandedNodes){
				var nodes = initExpandedNodes.split(',');
				for(var no=0;no<nodes.length;no++){
					if(nodes[no])this.showHideNode(false,nodes[no]);
				}
			}

			document.documentElement.onmousemove = JSTreeObj.moveDragableNodes;
			document.documentElement.onmouseup = JSTreeObj.dropDragableNodes;
		}
		,
		__addAdditionalRequestParameters : function(ajax, parameters)
		{
			for (var parameter in parameters) {
				ajax.setVar(parameter, parameters[parameter]);
			}
		},
		_____addElement : function (day_title, day_ordering, newdayid)
		{
			var container = document.getElementById('tree_ul_0');
			var new_element = document.createElement('li');
			new_element.innerHTML = '<img src="/administrator/components/com_guru/views/gurudays/tmpl/images/dhtmlgoodies_minus.gif"/><a  class="modal" rel="{handler: \'iframe\', size: {x: 850, y: 650}}" id="nodeATag1000'+ day_ordering +'" href="index.php?option=com_guru&controller=guruDays&tmpl=component&task=edit&node=1000'+ day_ordering +'&cid[]='+ newdayid +'">Group ' + day_ordering + ' - ' + day_title +'</a><a class="modal" href="index.php?option=com_guru&controller=guruDays&task=addtask&no_html=1&cid[]='+ newdayid +'&node='+ day_ordering +'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}"><font color="#0b55c4"> (add screen)</font></a><ul id="tree_ul_'+ day_ordering +'" style="display: block;"></ul>';
			new_element.id='node1000' + day_ordering;
			new_element.setAttribute('isLeaf', 'false');
			container.appendChild(new_element);
			var menuItems = document.getElementById('node0');
			//JSTreeObj.__refreshDisplay(menuItems);
			//JSTreeObj.initTree(2,0);
		}
	}
