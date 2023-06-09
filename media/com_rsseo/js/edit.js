function rsseo_show_panel() {
	document.getElementById('rsseo-frontend-edit').className = 'rsseo-frontend-edit-hide';
	document.getElementById('rsseo-frontend-window').className = 'rsseo-frontend-window rsseo-frontend-window-open';
}

function rsseo_hide_panel() {
	document.getElementById('rsseo-frontend-edit').className = 'rsseo-frontend-edit';
	document.getElementById('rsseo-frontend-window').className = 'rsseo-frontend-window';
}

function rsseo_save_page(root) {
	var elements = document.getElementById('rsseo-frontend-edit-form').elements;
	var params	 = [];
	
	for (var i = 0; i < elements.length; i++) {
		if (elements[i].type == 'button') {
			continue;
		}
		
		params.push(elements[i].name + '=' + encodeURIComponent(elements[i].value));
	}
	
	if (params.length) {
		document.getElementById('rsseo-frontend-edit-loader').style.display = '';
		params.push('task=save');
		
		var xhttp;
		
		if (window.XMLHttpRequest) {
			xhttp = new XMLHttpRequest();
		} else {
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xhttp.open('POST', root + 'index.php?option=com_rsseo', true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.setRequestHeader("Content-length", params.length);
		xhttp.setRequestHeader("Connection", "close");
		
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				document.getElementById('rsseo-frontend-edit-loader').style.display = 'none';
				document.getElementById('rsseo-frontend-edit-message').style.display = '';
				document.getElementById('rsseo-frontend-edit-message').innerHTML = xhttp.responseText;
				
				window.setTimeout(function() {
					document.getElementById('rsseo-frontend-edit-message').innerHTML = '';
					document.getElementById('rsseo-frontend-edit-message').style.display = 'none';
					rsseo_hide_panel();
					document.location.reload();
				},2000);
				
			}
		}
		
		xhttp.send(params.join('&'));
	}
}