
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
        
        <title>KPI Dashboard</title>
        
                
        	        	

			<link href="/KPIDash/web/assets/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
        	<link href="/KPIDash/web/assets/vendor/normalize-css/normalize.css" rel="stylesheet" />
        	<link href="/KPIDash/web/css/main.css" rel="stylesheet" />
        	<link href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css" rel="stylesheet" />
        	<link href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css" rel="stylesheet" />        	
        	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">    	        	
        	        	
                
     
    </head>
    <body>
        		 		
			<!-- Page Content -->
			<div class="content">				
				
<div class="row">
    <div class="col-sm-12">
        <h1 class="page-header">KPI Project Details</h1>
        <ol class="breadcrumb">        
            <li class="active"> Project Details for KPI - <strong>ST Defect Density</strong></li>
        </ol>
    </div>
</div>
<div class="text-primary"><strong>Target for this KPI ( &lt;=10 )</strong>
</br>(<strong>Note : </strong>The details on final result obtained for each Project for this KPI can be viewed when mouse hover on KPI result.)
</div></br>
<div class="col-sm-10">
	<table id="dataTable" class="table table-bordered table-hover table-sm">
		<thead class="thead-inverse">
			<tr>					
				<th class="bg_grey col-md-1">S.No.</th>
				<th class="bg_grey">Projects</th>			
				<th class="bg_grey">ST Defect Density</th>
				<th class="bg_grey">RAG</th>
				<th class="bg_grey">Cause</th>
				<th class="bg_grey">Action</th>
			</tr>					
		</thead>
		<tbody>									
					
				
							<tr>
					<td class="col-md-1">1</td>						
					<td>Store Proc Fusion</td>					
																																										<td style="cursor:help" data-toggle="tooltip" data-html="true" title="Numerator-Number of  Functional defects found in SIT/UAT where 1st Possible detection is ST - (5)
Denominator-Number of  Functional defects - (27)" class="bg-danger">18.52%</td>
													
																
					<td align="center">
													<img src="/KPIDash/web/images/red_RAG.png">
											</td>		
					
																	<td contenteditable="true" id="CAUSE_1" class="col-md-5"></td>						
						<td contenteditable="true" id="ACTION_1" class="col-md-5"></td>	
										
					<input type="hidden" name="KPIHidden" id="KPIHidden" value="2">
					<input type="hidden" name="ProjectIDHidden" id="ProjectIDHidden_1" value="26">
					
								
				</tr>
					
				
							<tr>
					<td class="col-md-1">2</td>						
					<td>HP QC Upgrade</td>					
																	<td style="cursor:help" data-toggle="tooltip" data-html="true" title="Numerator-Number of  Functional defects found in SIT/UAT where 1st Possible detection is ST - (0)
Denominator-Number of  Functional defects - (0)" class="bg_grey">0</td>
										
					<td align="center">
													<img src="/KPIDash/web/images/na_RAG.png">
											</td>		
					
											
						<td class="bg_grey">N/A</td>						
						<td class="bg_grey">N/A</td>			
										
					<input type="hidden" name="KPIHidden" id="KPIHidden" value="2">
					<input type="hidden" name="ProjectIDHidden" id="ProjectIDHidden_2" value="25">
					
								
				</tr>
					
				
							<tr>
					<td class="col-md-1">3</td>						
					<td>VH160173_HSS_Virt</td>					
																	<td style="cursor:help" data-toggle="tooltip" data-html="true" title="Numerator-Number of  Functional defects found in SIT/UAT where 1st Possible detection is ST - (0)
Denominator-Number of  Functional defects - (0)" class="bg_grey">N/A</td>
										
					<td align="center">
													<img src="/KPIDash/web/images/na_RAG.png">
											</td>		
					
											
						<td class="bg_grey">N/A</td>						
						<td class="bg_grey">N/A</td>			
										
					<input type="hidden" name="KPIHidden" id="KPIHidden" value="2">
					<input type="hidden" name="ProjectIDHidden" id="ProjectIDHidden_3" value="5">
					
								
				</tr>
					
			
		<div id="dialog" class="modal_dialog" title="Action Status"></div> 							
		</tbody>
	</table>
</div>	

	
			</div>	
        
        	        	 <script src="/KPIDash/web/js/Common/Datatable_Common.js"></script>    	 	
        	
        	<script src="/KPIDash/web/assets/vendor/jquery/dist/jquery.min.js"></script>
        	<script src="/KPIDash/web/assets/vendor/bootstrap/dist/js/bootstrap.js"></script>
        	<script src="/KPIDash/web/assets/vendor/jquery-ui/jquery-ui.min.js"></script>      	        	
        	<script src="/KPIDash/web/assets/vendor/DateJS/build/date-en-AU.js"></script>        	
        	    	
    		    		    		<script src="/KPIDash/web/assets/vendor/js/jsapi.js"></script>
    		
    		<script src="/KPIDash/web/assets/vendor/jquery/dist/jquery.dataTables.min.js"></script>
    		<script src="/KPIDash/web/assets/vendor/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
    		<script src="/KPIDash/web/assets/vendor/jquery/dist/jquery.validate.min.js"></script>
    		
    		        	
        	        	
        	        	
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
			<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
			<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
			<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
			<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>				
			<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
 			<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
 			 	
 			 					        	  
	    
	<script src="/KPIDash/web/js/Dashboard/Dashboard_common.js"></script>		
	<script>
						OnTDBlurEditValues();	
		
	</script>	
        
        	<script>
		$('[data-toggle="tooltip"]').tooltip({
			html: true,			
    		max-width:none
		});
	</script>
    
<div id="sfwdt6215df" class="sf-toolbar" style="display: none"></div><script>/*<![CDATA[*/        Sfjs = (function() {        "use strict";        var classListIsSupported = 'classList' in document.documentElement;        if (classListIsSupported) {            var hasClass = function (el, cssClass) { return el.classList.contains(cssClass); };            var removeClass = function(el, cssClass) { el.classList.remove(cssClass); };            var addClass = function(el, cssClass) { el.classList.add(cssClass); };            var toggleClass = function(el, cssClass) { el.classList.toggle(cssClass); };        } else {            var hasClass = function (el, cssClass) { return el.className.match(new RegExp('\\b' + cssClass + '\\b')); };            var removeClass = function(el, cssClass) { el.className = el.className.replace(new RegExp('\\b' + cssClass + '\\b'), ' '); };            var addClass = function(el, cssClass) { if (!hasClass(el, cssClass)) { el.className += " " + cssClass; } };            var toggleClass = function(el, cssClass) { hasClass(el, cssClass) ? removeClass(el, cssClass) : addClass(el, cssClass); };        }        var noop = function() {},            collectionToArray = function (collection) {                var length = collection.length || 0,                    results = new Array(length);                while (length--) {                    results[length] = collection[length];                }                return results;            },            profilerStorageKey = 'sf2/profiler/',            request = function(url, onSuccess, onError, payload, options) {                var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');                options = options || {};                options.maxTries = options.maxTries || 0;                xhr.open(options.method || 'GET', url, true);                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');                xhr.onreadystatechange = function(state) {                    if (4 !== xhr.readyState) {                        return null;                    }                    if (xhr.status == 404 && options.maxTries > 1) {                        setTimeout(function(){                            options.maxTries--;                            request(url, onSuccess, onError, payload, options);                        }, 500);                        return null;                    }                    if (200 === xhr.status) {                        (onSuccess || noop)(xhr);                    } else {                        (onError || noop)(xhr);                    }                };                xhr.send(payload || '');            },            getPreference = function(name) {                if (!window.localStorage) {                    return null;                }                return localStorage.getItem(profilerStorageKey + name);            },            setPreference = function(name, value) {                if (!window.localStorage) {                    return null;                }                localStorage.setItem(profilerStorageKey + name, value);            },            requestStack = [],            renderAjaxRequests = function() {                var requestCounter = document.querySelectorAll('.sf-toolbar-ajax-requests');                if (!requestCounter.length) {                    return;                }                var ajaxToolbarPanel = document.querySelector('.sf-toolbar-block-ajax');                var tbodies = document.querySelectorAll('.sf-toolbar-ajax-request-list');                var state = 'ok';                if (tbodies.length) {                    var tbody = tbodies[0];                    var rows = document.createDocumentFragment();                    if (requestStack.length) {                        for (var i = 0; i < requestStack.length; i++) {                            var request = requestStack[i];                            var row = document.createElement('tr');                            rows.appendChild(row);                            var methodCell = document.createElement('td');                            if (request.error) {                                methodCell.className = 'sf-ajax-request-error';                            }                            methodCell.textContent = request.method;                            row.appendChild(methodCell);                            var pathCell = document.createElement('td');                            pathCell.className = 'sf-ajax-request-url';                            if ('GET' === request.method) {                                var pathLink = document.createElement('a');                                pathLink.setAttribute('href', request.url);                                pathLink.textContent = request.url;                                pathCell.appendChild(pathLink);                            } else {                                pathCell.textContent = request.url;                            }                            pathCell.setAttribute('title', request.url);                            row.appendChild(pathCell);                            var durationCell = document.createElement('td');                            durationCell.className = 'sf-ajax-request-duration';                            if (request.duration) {                                durationCell.textContent = request.duration + "ms";                            } else {                                durationCell.textContent = '-';                            }                            row.appendChild(durationCell);                            row.appendChild(document.createTextNode(' '));                            var profilerCell = document.createElement('td');                            if (request.profilerUrl) {                                var profilerLink = document.createElement('a');                                profilerLink.setAttribute('href', request.profilerUrl);                                profilerLink.textContent = request.profile;                                profilerCell.appendChild(profilerLink);                            } else {                                profilerCell.textContent = 'n/a';                            }                            row.appendChild(profilerCell);                            var requestState = 'ok';                            if (request.error) {                                requestState = 'error';                                if (state != "loading" && i > requestStack.length - 4) {                                    state = 'error';                                }                            } else if (request.loading) {                                requestState = 'loading';                                state = 'loading';                            }                            row.className = 'sf-ajax-request sf-ajax-request-' + requestState;                        }                        var infoSpan = document.querySelectorAll(".sf-toolbar-ajax-info")[0];                        var children = collectionToArray(tbody.children);                        for (var i = 0; i < children.length; i++) {                            tbody.removeChild(children[i]);                        }                        tbody.appendChild(rows);                        if (infoSpan) {                            var text = requestStack.length + ' AJAX request' + (requestStack.length > 1 ? 's' : '');                            infoSpan.textContent = text;                        }                        ajaxToolbarPanel.style.display = 'block';                    } else {                        ajaxToolbarPanel.style.display = 'none';                    }                }                requestCounter[0].textContent = requestStack.length;                var className = 'sf-toolbar-ajax-requests sf-toolbar-value';                requestCounter[0].className = className;                if (state == 'ok') {                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                    Sfjs.removeClass(ajaxToolbarPanel, 'sf-toolbar-status-red');                } else if (state == 'error') {                    Sfjs.addClass(ajaxToolbarPanel, 'sf-toolbar-status-red');                } else {                    Sfjs.addClass(ajaxToolbarPanel, 'sf-ajax-request-loading');                }            };        var addEventListener;        var el = document.createElement('div');        if (!'addEventListener' in el) {            addEventListener = function (element, eventName, callback) {                element.attachEvent('on' + eventName, callback);            };        } else {            addEventListener = function (element, eventName, callback) {                element.addEventListener(eventName, callback, false);            };        }                    if (window.XMLHttpRequest && XMLHttpRequest.prototype.addEventListener) {                var proxied = XMLHttpRequest.prototype.open;                XMLHttpRequest.prototype.open = function(method, url, async, user, pass) {                    var self = this;                    /* prevent logging AJAX calls to static and inline files, like templates */                    var path = url;                    if (url.substr(0, 1) === '/') {                        if (0 === url.indexOf('\x2FKPIDash\x2Fweb')) {                            path = url.substr(12);                        }                    }                    else if (0 === url.indexOf('http\x3A\x2F\x2Flocalhost\x2FKPIDash\x2Fweb')) {                        path = url.substr(28);                    }                    if (path.substr(0, 1) === '/' && !path.match(new RegExp("^\/(app(_[\\w]+)?\\.php\/)?_wdt"))) {                        var stackElement = {                            loading: true,                            error: false,                            url: url,                            method: method,                            start: new Date()                        };                        requestStack.push(stackElement);                        this.addEventListener('readystatechange', function() {                            if (self.readyState == 4) {                                stackElement.duration = new Date() - stackElement.start;                                stackElement.loading = false;                                stackElement.error = self.status < 200 || self.status >= 400;                                stackElement.profile = self.getResponseHeader("X-Debug-Token");                                stackElement.profilerUrl = self.getResponseHeader("X-Debug-Token-Link");                                Sfjs.renderAjaxRequests();                            }                        }, false);                        Sfjs.renderAjaxRequests();                    }                    proxied.apply(this, Array.prototype.slice.call(arguments));                };            }                return {            hasClass: hasClass,            removeClass: removeClass,            addClass: addClass,            toggleClass: toggleClass,            getPreference: getPreference,            setPreference: setPreference,            addEventListener: addEventListener,            request: request,            renderAjaxRequests: renderAjaxRequests,            load: function(selector, url, onSuccess, onError, options) {                var el = document.getElementById(selector);                if (el && el.getAttribute('data-sfurl') !== url) {                    request(                        url,                        function(xhr) {                            el.innerHTML = xhr.responseText;                            el.setAttribute('data-sfurl', url);                            removeClass(el, 'loading');                            (onSuccess || noop)(xhr, el);                        },                        function(xhr) { (onError || noop)(xhr, el); },                        '',                        options                    );                }                return this;            },            toggle: function(selector, elOn, elOff) {                var tmp = elOn.style.display,                    el = document.getElementById(selector);                elOn.style.display = elOff.style.display;                elOff.style.display = tmp;                if (el) {                    el.style.display = 'none' === tmp ? 'none' : 'block';                }                return this;            },            createTabs: function() {                var tabGroups = document.querySelectorAll('.sf-tabs');                /* create the tab navigation for each group of tabs */                for (var i = 0; i < tabGroups.length; i++) {                    var tabs = tabGroups[i].querySelectorAll('.tab');                    var tabNavigation = document.createElement('ul');                    tabNavigation.className = 'tab-navigation';                    for (var j = 0; j < tabs.length; j++) {                        var tabId = 'tab-' + i + '-' + j;                        var tabTitle = tabs[j].querySelector('.tab-title').innerHTML;                        var tabNavigationItem = document.createElement('li');                        tabNavigationItem.setAttribute('data-tab-id', tabId);                        if (j == 0) { Sfjs.addClass(tabNavigationItem, 'active'); }                        if (Sfjs.hasClass(tabs[j], 'disabled')) { Sfjs.addClass(tabNavigationItem, 'disabled'); }                        tabNavigationItem.innerHTML = tabTitle;                        tabNavigation.appendChild(tabNavigationItem);                        var tabContent = tabs[j].querySelector('.tab-content');                        tabContent.parentElement.setAttribute('id', tabId);                    }                    tabGroups[i].insertBefore(tabNavigation, tabGroups[i].firstChild);                }                /* display the active tab and add the 'click' event listeners */                for (i = 0; i < tabGroups.length; i++) {                    tabNavigation = tabGroups[i].querySelectorAll('.tab-navigation li');                    for (j = 0; j < tabNavigation.length; j++) {                        tabId = tabNavigation[j].getAttribute('data-tab-id');                        document.getElementById(tabId).querySelector('.tab-title').className = 'hidden';                        if (Sfjs.hasClass(tabNavigation[j], 'active')) {                            document.getElementById(tabId).className = 'block';                        } else {                            document.getElementById(tabId).className = 'hidden';                        }                        tabNavigation[j].addEventListener('click', function(e) {                            var activeTab = e.target || e.srcElement;                            /* needed because when the tab contains HTML contents, user can click */                            /* on any of those elements instead of their parent '<li>' element */                            while (activeTab.tagName.toLowerCase() !== 'li') {                                activeTab = activeTab.parentNode;                            }                            /* get the full list of tabs through the parent of the active tab element */                            var tabNavigation = activeTab.parentNode.children;                            for (var k = 0; k < tabNavigation.length; k++) {                                var tabId = tabNavigation[k].getAttribute('data-tab-id');                                document.getElementById(tabId).className = 'hidden';                                Sfjs.removeClass(tabNavigation[k], 'active');                            }                            Sfjs.addClass(activeTab, 'active');                            var activeTabId = activeTab.getAttribute('data-tab-id');                            document.getElementById(activeTabId).className = 'block';                        });                    }                }            },            createToggles: function() {                var toggles = document.querySelectorAll('.sf-toggle');                for (var i = 0; i < toggles.length; i++) {                    var elementSelector = toggles[i].getAttribute('data-toggle-selector');                    var element = document.querySelector(elementSelector);                    Sfjs.addClass(element, 'sf-toggle-content');                    if (toggles[i].hasAttribute('data-toggle-initial') && toggles[i].getAttribute('data-toggle-initial') == 'display') {                        Sfjs.addClass(element, 'sf-toggle-visible');                    } else {                        Sfjs.addClass(element, 'sf-toggle-hidden');                    }                    Sfjs.addEventListener(toggles[i], 'click', function(e) {                        e.preventDefault();                        var toggle = e.target || e.srcElement;                        /* needed because when the toggle contains HTML contents, user can click */                        /* on any of those elements instead of their parent '.sf-toggle' element */                        while (!Sfjs.hasClass(toggle, 'sf-toggle')) {                            toggle = toggle.parentNode;                        }                        var element = document.querySelector(toggle.getAttribute('data-toggle-selector'));                        Sfjs.toggleClass(element, 'sf-toggle-hidden');                        Sfjs.toggleClass(element, 'sf-toggle-visible');                        /* the toggle doesn't change its contents when clicking on it */                        if (!toggle.hasAttribute('data-toggle-alt-content')) {                            return;                        }                        if (!toggle.hasAttribute('data-toggle-original-content')) {                            toggle.setAttribute('data-toggle-original-content', toggle.innerHTML);                        }                        var currentContent = toggle.innerHTML;                        var originalContent = toggle.getAttribute('data-toggle-original-content');                        var altContent = toggle.getAttribute('data-toggle-alt-content');                        toggle.innerHTML = currentContent !== altContent ? altContent : originalContent;                    });                }            }        };    })();    Sfjs.addEventListener(window, 'load', function() {        Sfjs.createTabs();        Sfjs.createToggles();    });/*]]>*/</script><script>/*<![CDATA[*/    (function () {                Sfjs.load(            'sfwdt6215df',            '/KPIDash/web/app_dev.php/_wdt/6215df',            function(xhr, el) {                el.style.display = -1 !== xhr.responseText.indexOf('sf-toolbarreset') ? 'block' : 'none';                if (el.style.display == 'none') {                    return;                }                if (Sfjs.getPreference('toolbar/displayState') == 'none') {                    document.getElementById('sfToolbarMainContent-6215df').style.display = 'none';                    document.getElementById('sfToolbarClearer-6215df').style.display = 'none';                    document.getElementById('sfMiniToolbar-6215df').style.display = 'block';                } else {                    document.getElementById('sfToolbarMainContent-6215df').style.display = 'block';                    document.getElementById('sfToolbarClearer-6215df').style.display = 'block';                    document.getElementById('sfMiniToolbar-6215df').style.display = 'none';                }                Sfjs.renderAjaxRequests();                /* Handle toolbar-info position */                var toolbarBlocks = document.querySelectorAll('.sf-toolbar-block');                for (var i = 0; i < toolbarBlocks.length; i += 1) {                    toolbarBlocks[i].onmouseover = function () {                        var toolbarInfo = this.querySelectorAll('.sf-toolbar-info')[0];                        var pageWidth = document.body.clientWidth;                        var elementWidth = toolbarInfo.offsetWidth;                        var leftValue = (elementWidth + this.offsetLeft) - pageWidth;                        var rightValue = (elementWidth + (pageWidth - this.offsetLeft)) - pageWidth;                        /* Reset right and left value, useful on window resize */                        toolbarInfo.style.right = '';                        toolbarInfo.style.left = '';                        if (elementWidth > pageWidth) {                            toolbarInfo.style.left = 0;                        }                        else if (leftValue > 0 && rightValue > 0) {                            toolbarInfo.style.right = (rightValue * -1) + 'px';                        } else if (leftValue < 0) {                            toolbarInfo.style.left = 0;                        } else {                            toolbarInfo.style.right = '0px';                        }                    };                }            },            function(xhr) {                if (xhr.status !== 0) {                    confirm('An error occurred while loading the web debug toolbar (' + xhr.status + ': ' + xhr.statusText + ').\n\nDo you want to open the profiler?') && (window.location = '/KPIDash/web/app_dev.php/_profiler/6215df');                }            },            {'maxTries': 5}        );    })();/*]]>*/</script>
</body>
</html>
