/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_SmartTagLibraryViewJS = MM_Core.extend({
  
	/** SMARTTAG LIBRARY FUNCTIONS */
	showSmartTagLibrary: function(contentAreaId)
	{
		this.contentAreaId = contentAreaId;
		
		var values = {
	        mm_module: "smarttag.library"
	    };

		smartTagLibDialog.showDialog("mm-smarttag-library-dialog", this.module, 800, "", "SmartTag Library", values,"","smartTagLibDialog");
	},
	
	toggleSmartTagGroup: function(id)
	{
		jQuery("#mm-smarttag-group"+id+"-children").toggle();
		jQuery("#mm-smarttag-group"+id+"-open-img").toggle();
		jQuery("#mm-smarttag-group"+id+"-closed-img").toggle();
	},
	
	smartTagClickHandler: function(file)
	{
		jQuery("#mm-smarttag-documentation").load(file);
	},
	
	
	/** ID LOOKUP FUNCTIONS */
	showIdLookup: function(contentAreaId)
	{
		this.contentAreaId = contentAreaId;
		
		var values = {
	        mm_module: "smarttag.idlookup"
	    };

		smartTagLibDialog.showDialog("mm-id-lookup-dialog", this.module, 550, 350, "ID Lookup", values,"","smartTagLibDialog");
	},
	
	lookupIds: function()
	{
		var values = {
            mm_action: "getLookupGrid",
            objectType: jQuery("#mm-object-type-selection").val()
        };

        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
        ajax.send(values, false, "stl_js", "lookupIdsCallback"); 
	},
	
	lookupIdsCallback: function(data) 
	{
		jQuery("#mm-lookup-results-container").html(data);
	},
	
	insertContent: function(content)
	{
		if(this.contentAreaId == "wordpress") {
			this.insertText("content", content, false);
		}
		else {
			var html = jQuery("#"+this.contentAreaId).val();
			jQuery("#"+this.contentAreaId).val(html+content);
		}
		
		smartTagLibDialog.close();
	},
	
	insertTemplate: function(templateType, templateName)
	{
		if(templateType != "")
		{
			var insertOk = confirm("Are you sure you want to insert the " + templateName + " template? ");
			
		    if(insertOk)
		    {
				var values = {
						mm_action: "getPageTemplate",
						mm_template_type: templateType
			    };
		
		        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		        ajax.send(values, false, "stl_js", "insertTemplateCallback");
		    }
		}
	},
	
	insertTemplateCallback: function(data)
	{
		this.contentAreaId == "wordpress";
		this.insertText("content", data, false);
	},
	
	normalizeElement: function(textarea)
	{
		textarea = textarea || 'content';
		if (textarea.constructor == String)	{
			textarea = document.getElementById(textarea);
		}
		return textarea;
	},
	
	insertText: function(textarea, text, forceTextMode)
	{
		textarea = this.normalizeElement(textarea);
		
		/** Visual mode **/
		if(typeof tinyMCE != 'undefined' && (ed = tinyMCE.activeEditor) 
				&& !ed.isHidden() && !forceTextMode) 
		{
			ed.focus();
			
			// IE fix
			if (tinymce.isIE) {			
				ed.selection.moveToBookmark(ed.windowManager.bookmark);			
			}		
					
	        ed.selection.setContent(text);
	        
			return;
		}
		
		
		/** HTML mode **/
		// IE
		if((textarea) && (document.selection && textarea.selection)) 
		{		
			var scrollTop = textarea.scrollTop;
			textarea.focus();
			
			var sel = textarea.selection;
			sel.text = text;	
			
			if (sel.text.length > 0)
			{
				sel.collapse(false);
			}
			
			sel.select();
			textarea.focus();
			textarea.scrollTop = scrollTop;
		}
		
		//MOZILLA/NETSCAPE support
		else if((textarea) && (textarea.selectionStart || textarea.selectionStart == '0'))
		{
			var startPos  = textarea.selectionStart, 
				endPos 	  = textarea.selectionEnd, 
				cursorPos = endPos, 
				scrollTop = textarea.scrollTop;

			if (startPos != endPos) 
			{			
				textarea.value = textarea.value.substring(0, startPos)
				              + text
				              + textarea.value.substring(endPos, textarea.value.length);
				              
				cursorPos += text.length - textarea.value.substring(startPos, endPos).length;						
			}
			else {
				textarea.value = textarea.value.substring(0, startPos)
				              + text
				              + textarea.value.substring(endPos, textarea.value.length);
				              
				cursorPos += text.length;
			}
			
			textarea.focus();
			textarea.selectionStart = cursorPos;
			textarea.selectionEnd 	= cursorPos;
			textarea.scrollTop 		= scrollTop;
		}
		
		// Unknown browser
		else {
			jQuery(textarea).val( jQuery(textarea).val() + text );
		}
	}
});
var smartTagLibDialog = new MM_DialogJS();
var stl_js = new MM_SmartTagLibraryViewJS("MM_SmartTagLibraryView", "");