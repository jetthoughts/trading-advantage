/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
function SearchPostFilter()
{
jQuery("#post-search-input").attr("disabled", "disabled");
    return true;
    
}
function s(v)
{
	if (v.constructor == String)
	{		
		return jQuery('#' + v);
	}
	else
	{
		return jQuery(v);
	}
}

/** 
 * Check vartiable on empty
 * 
 * @param {Mixed} v
 * @return {Boolean}
 */
function empty(v)
{
	if (v == undefined) return true;
	
	if (!v) return true;
	
	if ((v.constructor === String) && (v == '')) return true;
	
	if ((v.constructor === Array) && (v.length == 0)) return true;
	
	return false;
}

function RouteToPage(url, lock_id)
{
    document.location.href=url;

    var r = doAjax( {
        lock:		jQuery('body')[0],
        dataType:	'json',
        url: 		url,
        onSuccess: 	function(data)
        {
        }
    } );

    // Add request to list
    doAddAjax(r, 'Router');
}

/**
 * Send data by AJAX
 * @param {Object} options
 */
function doAjax(options)
{
	// Try get data from cache if needed
	if ( doGetCacheAJAX(options) ) {
		return false;
	}

	// Stack of request
	if (!options.ignoreStack) {
		if (!window.ajax_request_version) {
			window.ajax_request_version = 0;
		}
		options.version = ++window.ajax_request_version;
	}
	
	// Lock element if needed
	if (options.lock) {
		doAjaxLock( options.lock );	
	}	

	var xhr = jQuery.ajax({
		data: 		options.data,
		dataType: 	options.dataType || 'json',
		type: 		options.type || 'POST',
   		url: 		options.url,
   		success: 	function(data, status)
   					{   						
   						// Check request version
   						if (options.ignoreStack || (window.ajax_request_version == options.version)) 
   						{			
	   						// Process redirect
	   						if (data.redirect)
	   						{	
	   							// and do redirect.
	   							alert( sprintf( __('Session expired. You will be redirected to "%s" page.'), data.redirect) );
	   							document.location = data.redirect;
	   							return;
	   						}
	   						
	   						// Process data
	   						if (options.onSuccess && !data.errors) {
				   				options.onSuccess(data, status);
	   						}
				   			
				   			// Try set data to cache if needed
				   			doSetCacheAJAX(options, data);			   				
   						}
			   			
			   			// Unlock in anyway
			   			if (options.lock) {
							doAjaxUnlock( options.lock );
			   			}
			   		},
   		error: 		function(XMLHttpRequest, status, errorThrown)
   					{
   						// Check version
   						if (options.ignoreStack || (window.ajax_request_version == options.version)) 
   						{
	   						// Process errors
	   						if (options.onError) {
				   				options.onError(XMLHttpRequest, status);
	   						}
				   		
				   			// Process AJAX errors
							onAjaxError(XMLHttpRequest, status);
   						}
						
						// Unlock
			   			if (options.lock) {
							doAjaxUnlock( options.lock );
			   			}
			   		},
   		cache: 		false
	});

	// Add options
	var request = {	
		xhr: 		xhr,
		options: 	options	
	};

	return request;
}


function __(str)
{
	if ( (window.l10n) && str && l10n[str])
	{
		str = l10n[str]; 
	}
	
	return str;
}


/**
 * Get response from cache
 * 
 * @param {Object} options
 */
function doGetCacheAJAX(options)
{
	if (glCache && options.cacheId)
	{
		var cache = glCache.getData( options.cacheId );

		if (cache)
		{
			if (cache.errors && options.onError)
			{
			   	options.onError(cache.errors);
			}
			else if (options.onSuccess)
			{
				options.onSuccess(cache);
			}
			
			return true;
		}
	}
	
	return false;
}


/**
 * Set response to cache
 * @param {Object} options
 * @param {Object} data
 * @return {Boolean}
 */
function doSetCacheAJAX(options, data)
{
	if (glCache && options.cacheId && !options.noCache)
	{
		return glCache.setData( options.cacheId, data, options.cacheExpire );
	}
}


/**
 * Cancel AJAX request
 * 
 * @param {Object} request
 */
function doCancelAjax(id)
{
	if (!mm || !mm.ajaxRequests) return;
	
	var requests = mm.ajaxRequests[ id ];

	jQuery(requests).each(function() 
	{
		if (this.options)
		{
			if (this.options.lock)
				doAjaxUnlock( this.options.lock );
		}
	
		if (this.xhr)
		{
			this.xhr.abort();
		}
	});

	delete mm.ajaxRequests[ id ];

	mm.ajaxRequests[ id ] = null;
}


function doAddAjax(r, id)
{
	if (!window.mm) 
		window.mm = {};
		
	if (!mm.ajaxRequests) 
		mm.ajaxRequests = [];
	
	if (!mm.ajaxRequests[ id ])
		mm.ajaxRequests[ id ] = [];
		
	var requests = mm.ajaxRequests[ id ];	
		
	requests.push( r );
	
	return requests; 
}


/**
 * Process AJAX errros
 * 
 * @param {XMLHttpRequest} xhr
 * @param {Number} status
 */
function onAjaxError(xhr, status)
{
	var errinfo = { errcode: status };
	
    if (xhr.status != 200) 
    {        
        errinfo.message = xhr.statusText;
    }
    else 
    {
        errinfo.message = __('Incorrect response data :'+xhr.responseText);
    }
    
    // Alert
    if (status == 'parsererror')
    {
            alert( __('[AJAX ERROR] ') + errinfo.message);
    }
}

function addMessages(messages, classes, parent)
{		
	var content = '';
    jQuery( messages ).each(function(){
            content = content + '<li>' + this +'</li>';
	});	

	if (content != '')
	{	
        var str = '<div class="' + classes + '"><ul>' + content + '</ul></div>';
        parent.prepend(  str );
	}	
}

/**
 * Process messages. Add message block to page.
 * 
 * @param {Object} data
 * @param {String} parentElement
 * @param {String} scrollContainer
 * @return {Boolean}
 */
function processMessages(data, parentElement, scrollContainer)
{			
	parentElement = s(parentElement || 'message-container');
	
	if (!empty(data.pageMessages))
	{
		addMessages(data.pageMessages, 'message-container', parentElement);
	}	

	if (!empty(data.pageErrors))
	{
		addMessages(data.pageErrors, 'message-container', parentElement);
	}
		
	if (scrollContainer)
	{
		s(scrollContainer)[0].scrollTop = 0; 
	}

	return true;
}

/**
 * Remove blocks with message
 * 
 * @param {DOM Element} parentElement
 */
function clearMessages(parentElement)
{
	parentElement =  s(parentElement || 'mm-messages-container');
	jQuery('.message-container', parentElement).remove();
}

/**
 * Lock element
 * 
 * @param {DOMElement} element
 */
function doAjaxLock( element )
{
	if (!element) return;
	
	// Show element
	jQuery(element).show();

	// Disable element
	jQuery(element).css('disabled', 'disabled');
	
	// Create lock area and ajax loader
	if (!element.lockArea)
		element.lockArea = jQuery('<div class="lock-area"></div>');
		
	if (!element.lockAreaTitle)
		element.lockAreaTitle = jQuery('<div class="lock-area-title"></div>');	

	try
	{
		jQuery(element.lockArea).bgiframe();
	}
	catch(e)
	{
	}
	
	// Place lock area
	arrangeElementAbove( element.lockArea, element, true, null, 98 );

	// Place ajax loader 
	var posDelta = {
		top	: element.offsetHeight/2 - 10, // half height of ajax loader image
		left: element.offsetWidth/2 - 110  // half width of ajax loader image
	};

	arrangeElementAbove( element.lockAreaTitle, element.lockArea[0], false, posDelta, 99 );
		
	// Show lock area
	viewAjaxLock(element, true);
		
	if (!element.lockCount) { 
		element.lockCount = 0; 
	}

	element.lockCount++;
}

/**
 * Unlock element
 * 
 * @param {DOMElement} element
 */
function doAjaxUnlock( element )
{
	if (!element || !element.lockCount) 
		return;
	
	element.lockCount--;
	
	if (element.lockCount == 0)
	{
		if (element.lockAreaTitle)
			element.lockAreaTitle.remove();
			
		if (element.lockArea)
			element.lockArea.remove();
		
		jQuery(element).css('disabled', '');	
	}
}

/**
 * Show and hide ajax lockers
 * 
 * @param {DOMElement} element
 * @param {Boolean} isVisible
 */
function viewAjaxLock(element, isVisible)
{
	if ( element && element.lockArea && element.lockAreaTitle )
	{
		if (isVisible)
		{
			element.lockArea.show();
			element.lockAreaTitle.show();
			repositionAjaxLock( element );
		}
		else
		{
			element.lockArea.hide();
			element.lockAreaTitle.hide();
		}
	}
}

/**
 * Reposition ajax lockers
 * 
 * @param {DOMElement} element
 */
function repositionAjaxLock(element, isCopySize) 
{
	if (!element.lockArea || !element.lockAreaTitle) return;

	// Replace lock area
	repositionElementAbove(element.lockArea, element, null);
	if (isCopySize)
	{
		jQuery(element.lockArea).height( element.offsetHeight ).width( element.offsetWidth );
	}
	
	// Replace ajax loader 
	var posDelta = {
		top	: element.offsetHeight/2 - 10, // half height of ajax loader image
		left: element.offsetWidth/2 - 110  // half width of ajax loader image
	};	

	repositionElementAbove( element.lockAreaTitle, element.lockArea[0], posDelta );
}

/**
 * Placed element above target element and set a same size
 * 
 * @param {DOMElement} element
 * @param {DOMElement} target
 * @param {Boolean} isCopySize
 * @param {Object} posDelta
 * @param {Number} zIndex
 */
function arrangeElementAbove(element, target, isCopySize, posDelta, zIndex)
{	
	if (!repositionElementAbove(element, target, posDelta)) return;	

	if (isCopySize)
	{
		element.height( target.offsetHeight ).width( target.offsetWidth );
	}	

	if ( zIndex )
	{
		element.css('z-index', zIndex );
	}

	jQuery(document.body).append( element );
	
	return element;
}

/**
 * Reposition absolute element above target element
 *
 * @param {DOMElement} element
 * @param {DOMElement} target
 * @param {Object} posDelta
 */
function repositionElementAbove(element, target, posDelta)
{
	if (!element || !target)
		return false;
		
	var t = jQuery(target).offset().top;
	var l = jQuery(target).offset().left;

	if (posDelta && posDelta.top)
		t = t + posDelta.top;

	if (posDelta && posDelta.left)
		l = l + posDelta.left;

	element.css('position', 'absolute').css('top', t).css('left', l);

	return true;
}

/**
 * Adds universal mechanism for table checkboxes.
 * Can be used on any table. Column with checkboxes must have class "checkbox".
 *
 * @param jQueryObject table
 *
 * @author of version 1.0 [17.06.2010] Dmitry Gryanko
 * @version 1.0 [17.06.2010]
 */
function applyTableCheckboxes(table)
{
	var mainBox = jQuery('TH.checkbox INPUT', table);
	mainBox.unbind('click');
	mainBox.bind('click', function() {
		if (this.checked) {
			jQuery('TD.checkbox INPUT', table).attr('checked', 'checked');
		}
		else {
			jQuery('TD.checkbox INPUT', table).removeAttr('checked');
		}
	});
}