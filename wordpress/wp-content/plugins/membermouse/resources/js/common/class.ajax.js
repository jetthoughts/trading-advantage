jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ((jQuery(window).height() - this.outerHeight()) / 2) + jQuery(window).scrollTop() + "px");
    this.css("left", ((jQuery(window).width() - this.outerWidth()) / 2) + jQuery(window).scrollLeft() + "px");
    return this;
}

var MM_Ajax = Class.extend({
  
  init: function(custom_url, module, action, method){
    //this.url = (!custom_url)?'admin-ajax.php':custom_url;
    this.url = wpadmin_url+'admin-ajax.php';
	this.module = module;
    this.action = action;
    this.useLoader = true;
    this.method = method;
    this.postvars = "";
    this.hideButton = true;
    this.dataType = 'json';
    this.response = "";
    if(isAdministrationSection != undefined){
    	if(!isAdministrationSection){
    		this.lockarea = "main";
    	}
    	else{
    		this.lockarea = "wpbody-content";
    	}
    }
    else{
    	this.lockarea = "wpbody-content";
    }
  },
  
  dump: function(type)
  {
    if(type=='post')
        alert("class.ajax.js:\n\n"+this.postvars);
    else if(type=='response')
        alert("class.ajax.js:\n\n"+this.response);
  },
  
  send: function(data, lockdiv, returnobj, returnfunc, datatype) 
  {
	  	
	  
        this.postvars = "";
        this.response = "";
        for(var eachvar in data)
        {
            this.postvars += eachvar+": "+data[eachvar]+"\n";
        }
        if(!lockdiv)
            lockdiv = 'body';
         
        //testing purposes only
        //this.dump('post');
        //erase 
        
        data.method = this.method;
        data.action = this.action;
        data.module = this.module;
        
        var responseType = this.dataType;
        if(datatype != undefined)
        {
        	responseType = datatype;
        }
        
        var self = this;
        this.startLoader();
        	
        var r = doAjax( 
        {		
            data:			data,
            lock:			jQuery(''+lockdiv)[0],
            url: 			this.url,
            dataType:		responseType,
            onSuccess: 		function(data)
                            {
        						self.stopLoader(data);
                                for(var eachvar in data)
                                {
                                    this.response += eachvar+": "+data[eachvar]+"\n";
                                }
                                eval(returnobj+"."+returnfunc+"(data)");
                            },
            onError: 		function(e){ 
                            	self.stopLoader();
                                alert("Error: "+e);
                            }		
        } );
        
        doAddAjax(r, this.module+"Request");
  },

  createLoaderDiv: function()
  {	
	jQuery("<div id=\"mm-progressbar-container\" style='position:absolute;left: 38%; top:30%; z-index: 10; filter: alpha(opacity=100);opacity:1;' ><div id=\"mm-progressbar\" style=\"width:350px; height:22px; border: 1px solid #ccc;\"></div></div>").hide().appendTo("body").fadeIn();
  },
  
  lock: function(){
	  if(jQuery("#"+this.lockarea).length){
		  jQuery("#"+this.lockarea).attr("style","filter: alpha(opacity=30);opacity:0.3;");
		  jQuery("#"+this.lockarea).attr("disabled","disabled"); 
	  } 
  },
  
  unlock: function(){
	  if(jQuery("#"+this.lockarea).length){
		  jQuery("#"+this.lockarea).attr("style","filter: alpha(opacity=100);opacity:1;");
		  jQuery("#"+this.lockarea).removeAttr("disabled");  
	  } 
  },
  
  startLoader: function(lockarea){
	  if(this.hideButton){
		  if(smartTagLibDialog!=undefined){
			  try{
				  smartTagLibDialog.preventDblClick();  
			  }catch(e){
				  
			  }
		  }
		  else if(mmdialog_js!=undefined){
			  try{
				  mmdialog_js.preventDblClick();  
			  }catch(e){
				  
			  }
		  }
	  }
	  
	  // if it exists on page, it means we are using it independantly
	  if(!this.useLoader){
		 return false;
	  }
	  if(jQuery("#mm-progressbar-container").length){
		  return false;
	  }
	  
	  var center = " ";
	  jQuery("<style type='text/css'> .ui-progressbar-value { "+center+" background-image: url('"+globalurl+"/resources/images/pbar-animated.gif');} </style>").appendTo("head");
	  this.createLoaderDiv();
	  if(lockarea!= undefined){
		  this.lockarea = lockarea;
	  }
	  this.lock();
	  jQuery("#mm-progressbar-container").center();
	  jQuery(function() {
			jQuery("#mm-progressbar").progressbar({
				value: 100
			});
		});
  },
  
  stopLoader: function(response){
	  if(this.hideButton){
		  if(smartTagLibDialog!=undefined){
			  try{
				  if(response!=undefined){
					if(response.type !=undefined){
						if(response.type=='error'){
							smartTagLibDialog.releaseButton();
						}
					}
					else{
						smartTagLibDialog.releaseButton();	
					}
				  }
				  else{
					  smartTagLibDialog.releaseButton();  
				  }
			  }catch(e){
	
			  }
		  }
		  else if(mmdialog_js!=undefined){
			  try{
				  if(response!=undefined){
					if(response.type !=undefined){
	//					alert(response.type);
						if(response.type=='error'){
							  mmdialog_js.releaseButton();
						}
					}
					else{
							mmdialog_js.releaseButton();	
					}
				  }
				  else{
					  mmdialog_js.releaseButton();  
				  }
			  }catch(e){
	
			  }
		  }
	  }
	  this.unlock();
	  // remove it so it won't interfere with anything else and skew results with start loader.
	  jQuery("#mm-progressbar-container").remove();
  },
});