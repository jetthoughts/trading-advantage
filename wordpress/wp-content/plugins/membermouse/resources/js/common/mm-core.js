/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_Core = Class.extend({
  
  init: function(moduleName, entityName) 
  {	
	  if(moduleName == undefined) {
		  this._alert("MM_Core.js: module name is required (i.e. MM_MembershipLevelsView, MM_BundlesView, etc.)");
	  }
	  
	  if(entityName == undefined) {
		  this._alert("MM_Core.js: entity name is required (i.e. Membership Level, Bundle, etc.)");
	  }
	  
	  this.module = moduleName;
	  this.entityName = entityName;
	  this.method = "performAction";
	  this.action = "module-handle";
	  this.updateHandler = "dataUpdateHandler";
	  this.mm_page = "mm_configure_site";
	  this.mm_module = "member_types";
  },
  
  _alert: function(str){
	 alert(str);
  },
  
  shouldRedirectExternal: function(urlObj, func){
	  	var url = "";
	    if(urlObj.url!=undefined){
	  		url = urlObj.url;
	  	}
	    else{
	    	url = urlObj;
	    }
	    
	    if(func=="changeMembership"){
		  	if(url.toLowerCase().indexOf("paypal")>=0 || url.toLowerCase().indexOf("clickbank")>=0){
				return confirm("You will be redirected to "+url);
			}
	    }
		return true;
  },
  
  createFormSubmit: function(params, submitButtonId){
	if(params!=null){
		var html  = "<form id='mm-paymentmethod' action='"+params.url+"' method='post'>";
		for(var eachvar in params){
			html+= "<input type='hidden' name='"+eachvar+"' value='"+params[eachvar]+"' />";
		}
		html+="</form>";
		//this._alert(html);
		jQuery("body").append(html);
		if(submitButtonId != undefined){
			if(jQuery("#"+submitButtonId).length){
				jQuery("#"+submitButtonId).submit();
			}
			else{
				this._alert("No button defined "+submitButtonId);
			}
		}
		else{
			if(jQuery("#mm-paymentmethod").length){
				jQuery("#mm-paymentmethod").submit();
			}
			else{
				this._alert("No button defined mm-paymentmethod");
			}
		}
		
	}
  },
  
  downloadFile: function(url){
	  document.location.href=url;
  },

  isValidURL: function(url)
  { 
	  return true;
  },
  
  ucfirst: function(str)
  {
      return str.charAt(0).toUpperCase() + str.slice(1);
  },
  
  getVar: function(value, defaultValue){
	if(value==undefined){
		return defaultValue;
	}
	return value;
  },
  
  setDataGridProps: function(sortBy, sortDir, crntPage, resultSize)
  {
	  this.sortBy = sortBy;
	  this.sortDir = sortDir;
	  this.crntPage = crntPage;
	  this.resultSize = resultSize;
  },
  
  getQuerystringParam: function(name)
  {
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    
    if(results == null) {
      return "";
  	} else {
      return decodeURIComponent(results[1].replace(/\+/g, " "));
  	}
  },
  
  sort: function(columnName)
  {
	  var newSortDir = "asc";
	  
	  if(columnName == this.sortBy)
	  {
		  if(this.sortDir=="asc") {
			  newSortDir = "desc";
		  }
	  }
	  
	  this.sortBy = columnName;
	  this.sortDir = newSortDir;
	  this.refreshView();
  },
  
  dgPreviousPage: function(dgCrntPage)
  {
	  if(parseInt(dgCrntPage) != 0) {
		  this.crntPage = parseInt(dgCrntPage) - 1;
		  this.refreshView();
	  }
  },
  
  dgNextPage: function(dgCrntPage, dgTotalPages)
  {
	  if(dgCrntPage != (parseInt(dgTotalPages) - 1)) {
		  this.crntPage = parseInt(dgCrntPage) + 1;
		  this.refreshView();
	  }
  },
  
  dgSetResultSize: function(pageControl)
  {
	  if(jQuery(pageControl).val() != undefined)
	  {
		  this.crntPage = 0;
		  this.resultSize = jQuery(pageControl).val();
		  this.refreshView();
	  }
  },
  
  refreshView: function()
  {
    var values = {
        sortBy: this.sortBy,
        sortDir: this.sortDir,
        crntPage: this.crntPage,
        resultSize: this.resultSize,
        mm_action: "refreshView"
    };
    
    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
    ajax.send(values, false, 'mmjs','refreshViewCallback'); 
  },
  
  refreshViewCallback: function(data)
  {
	  if(data.message != undefined && data.message.length > 0) {
		  jQuery("#mm-view-container").html(data.message);
	  }
	  else {
		  this._alert("No data received");
	  }
  },
  
  save: function(callback, params, callbackFunc) 
  {
	  this.processForm();
	  
	  if(this.validateForm() == true) {
	      var form_obj = new MM_Form('mm-form-container');
	      var values = form_obj.getFields();
	      
	      values.mm_action = "save";
	      
    	  if(params != undefined && params != "") {
    		  if(typeof params == "object") {
    			  for(var key in params) {
    				  eval("values."+key+"='"+params[key]+"'");
    			  }
    		  }
    	   }
	      
    	  var callbackObject = "mmjs";
	      if(callback != undefined && callback!="")
	      {
	    	  callbackObject = callback;
	      }

	      var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	      ajax.send(values, false, callbackObject, this.updateHandler); 
	  }
  },
  
  create: function(dialogId, width, height)
  {
	  mmdialog_js.showDialog(dialogId, this.module, width, height, "Create "+this.entityName);
  },
  
  edit: function(dialogId, id, width, height)
  {
	  mmdialog_js.showDialog(dialogId, this.module, width, height, "Edit "+this.entityName, id);
  },
  
  remove: function(id)
  { 
    var doRemove = confirm("Are you sure you want to delete this " + this.entityName.toLowerCase() + "?");
    
    if(doRemove)
    {
        var values = {
            id: id,
            mm_action: "remove"
        };
        
        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
        ajax.send(values, false, 'mmjs', this.updateHandler); 
    }
  },
 
  dataUpdateHandler: function(data)
  {
	  if(data.type == "error")
	  {
		  if(data.message.length > 0)
		  {  
			  this._alert(data.message);
			  return false;
		  }
	  }
	  else {
		  if(data.message != undefined && data.message.length > 0)
		  {
			  this._alert(data.message);
		  }

		  this.refreshView();
		  this.closeDialog();
	  }
  },

  closeDialog: function(dialogReference)
  {
      if(dialogReference != undefined && dialogReference != "")
      {
    	  dialogReference.close();
      }
      else
      {
    	  mmdialog_js.close();
      }
  },
  
  /** FORM-SPECIFIC FUNCTION **/
  processForm: function()
  {
	  // define in subclass
  },
  
  validateForm: function()
  {
	  // define in subclass
	  return true;
  },
  
  validatePhone: function(phone)
  {
	var regexs = new Array();
	regexs.push(/^(\+\d)*\s*(\(\d{3}\)\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$/); 
	regexs.push(/^\d{10}$/);
	regexs.push(/^(\d{3})*(\-|\s)*\d{3}(\-|\s)*\d{4}$/); 
	regexs.push(/^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12}){1,2}$/); //international
	
	for(i=0; i<regexs.length; i++)
	{
		if (phone.match(regexs[i])) {
			return true;
		} 
	}
	return false;  
  },

  validateCreditDate: function(year,month)
  {
	  var d = new Date();
	  var curr_date = d.getDate();
	  var curr_month = d.getMonth()+1; /// required to add 1 since their month index starts on 0
	  var curr_year = d.getFullYear();
	  
	  curr_year = curr_year.toString().substring(2);
	  
	  if(parseInt(curr_year)>parseInt(year))
	  {
		return false;  
	  }
	  else if(parseInt(curr_year)== parseInt(year))
	  {
		  var m = month.replace(/^[ 0]/g,'');
		  
//		this._alert(parseInt(curr_month)+" > "+parseInt(m)+" ("+month.replace(/^[ 0]/g,'')+")");
		  if(parseInt(curr_month)>parseInt(m))
		  {
			  return false;
		  }
	  }
	  return true;
  },
  
  validateEmail: function(email) 
  {
	  var apos = email.indexOf("@");
	  var dotpos = email.lastIndexOf(".");
	   
	  if(apos < 1 || dotpos - apos < 2)
	  {
			return false;
	  }
	  
	  return true;
  },
  
  validateUrl: function(s) {
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		return regexp.test(s);
	},
  
  /** BADGE UPLOAD FUNCTIONS **/
  startUpload: function()
  {
      return true;
  },

  stopUpload: function(success, msg, filePath)
  {
      if (success == '1')
      {
         jQuery("#mm-uploaded-file-details").show();
         jQuery("#mm-file-upload-container").hide();
         if(jQuery("#mm-uploaded-file")[0]){
        	 var tag = jQuery("#mm-uploaded-file")[0].tagName;
        	 if(tag.toLowerCase() == "div"){
               jQuery("#mm-uploaded-file").attr("href", msg);
               var fileArr = msg.split('/');
               if(filePath != undefined){
            	   jQuery("#mm-uploaded-file-hidden").text(filePath);
               }
               jQuery("#mm-uploaded-file").text(fileArr.pop());
               
        	 }
         }
         else{
             jQuery("#mm-uploaded-file").attr("src", msg); 
         }
      }
      else 
      {
         jQuery("#mm-uploaded-file-details").hide();
         jQuery("#mm-file-upload-container").show();
         
         this._alert(msg);     
      }
      
      return true;   
  },
  
  clearUploadedFile: function()
  {
	  jQuery("#fileToUpload").attr("value", "");
	  jQuery("#mm-uploaded-file-details").hide();
      jQuery("#mm-file-upload-container").show();
      jQuery("#mm-uploaded-file").attr("src", "");
  }
});