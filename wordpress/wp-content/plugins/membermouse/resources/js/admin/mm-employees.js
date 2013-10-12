/*!
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_EmployeesViewJS = MM_Core.extend({
  
	setDefault: function(id)
	{
		var doSet = confirm("Are you sure you want to set this employee as the default?");
	    
	    if(doSet)
	    {
	        var values = {
	            id:id,
	            mm_action: "setDefault"
	        };

	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, 'mmjs',this.updateHandler); 
	    }
	},
	
	validateForm: function()
	{
		// display name 
		if(jQuery('#mm-display-name').val() == "") {
			alert("Please enter a display name");
			return false;
		}
		
		// password
		if(jQuery('#id').val() == "0")
		{
			if(jQuery('#mm-password').val() == "") 
			{
				alert("Please enter a password");
				return false;
			}
		}
		
		// email
		if(jQuery('#mm-email').val() == "") {
			alert("Please enter a valid email address");
			return false;
		}
	   
		if(!this.validateEmail(jQuery('#mm-email').val())) 
		{
			alert("Please enter a valid email address");
			return false;
		}
	   
		return true;
	},
	
	removeAccount: function(id)
	{
		var doRemove = confirm("Are you sure you want to delete this " + this.entityName.toLowerCase() + "?");
	    
	    if(doRemove)
	    {
	    	
	    	var doRemoveLinked = confirm("Would you like to delete the linked WordPress user also?\n\nWARNING: If you don't remove the linked WordPress user, they will still be able to log in.\n\nClick OK to remove them or CANCEL to continue without removing them.");
	    	
	        var values = {
	            id: id,
	            mm_action: "remove",
	            remove_linked: doRemoveLinked
	        };
	        
	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, 'mmjs', this.updateHandler); 
	    }    
	}
});

var mmjs = new MM_EmployeesViewJS("MM_EmployeesView", "Employee");