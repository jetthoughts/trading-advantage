/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_ReportViewJS = MM_Core.extend({

	includeMemberType: function(){
		if(!jQuery("#mm_member_types").is(":visible")){
			jQuery("#mm_member_types").show();
		}
		else{
			jQuery("#mm_member_types").hide();
		}
	},
	
	validateType: function(tagName, title){

		if(jQuery("#mm_include_"+tagName).is(":checked")){
			var optionTag = "";
			if(jQuery('#mm_'+tagName+'_opt_all').is(":checked")){
				optionTag = "all";
			}
			else{
				optionTag = "selected";
			}
			
			if(optionTag == 'selected'){
				var countTypes =0;
			    jQuery("select[id=mm_"+tagName+"_sel\\[\\]] :selected").each(function()
	    	    {
	    	    	var id = jQuery(this).val();
	    	    	var text = jQuery(this).text();
	    	    	countTypes++;
	    	    });
			    
			    if(countTypes<=0){
			    	alert("Please select at least one "+title+".");
			    	return false;
			    }
			}
		}
		return true;
	},
	
	validateForm: function(){
		
		var ret = this.validateType('access_tags', 'Bundles');
		if(!ret){
			return ret;
		}
		ret = this.validateType('member_types', 'Membership Levels');
		if(!ret){
			return ret;
		}
		
		var fromDate = jQuery("#mm_from_date").val();
		var toDate = jQuery("#mm_to_date").val();
		
		if(fromDate.length<=0){
			alert("Must include a from date.");
			return false;
		}
		
		if(toDate.length<=0){
			alert("Must include a from date.");
			return false;
		}
		return true;
	},
	
	includeAccessTag: function(){
		if(!jQuery("#mm_access_tags").is(":visible")){
			jQuery("#mm_access_tags").show();
		}
		else{
			jQuery("#mm_access_tags").hide();
		}
	},
	
	showMemberTypes: function(){
		if(jQuery("#mm_member_types_sel\\[\\]").attr("disabled")){
			jQuery("#mm_member_types_sel\\[\\]").attr('disabled','');
		}
		else{
			jQuery("#mm_member_types_sel\\[\\]").attr('disabled','disabled');
		}
	},
	
	showAccessTags: function(){
		if(jQuery("#mm_access_tags_sel\\[\\]").attr("disabled")){
			jQuery("#mm_access_tags_sel\\[\\]").attr('disabled','');
		}
		else{
			jQuery("#mm_access_tags_sel\\[\\]").attr('disabled','disabled');
		}
	},
	
});

var mmjs = new MM_ReportViewJS("", "Reports View");