jQuery(document).ready(function($) {	
	
	
	/* --------------------------------Themex Object----------------------------------- */	
	
	//Enable save buttons on options change
	var saveButton=$('.themex_panel .save_options');
	
	$('.themex_panel input,.themex_panel textarea').each(function() {
	   $(this).data('oldVal', $(this).val());
	   $(this).bind("propertychange keyup input paste", function(event){
		  //if value has changed
		  if ($(this).data('oldVal') != $(this).val()) {
		   $(this).data('oldVal', $(this).val());
		   saveButton.removeClass('disabled');
		 }
	   });
	 });
	 
	 $('.themex_panel select,.themex_panel input').live('change', function() {
		saveButton.removeClass('disabled');
	 });
		
	//Save and reset options
	$('.themex_panel .save_options:not(.disabled), .themex_panel .reset_options').live('click', function() {	
		//get options values
		var values = $('#themex_options').serialize();
		var button=$(this);
		var type='save';
		if(button.is('.reset_options')) {
			type='reset';
			$('.themex_panel .reset_options').addClass('disabled');
		}
		
		var data = {
			type: type,
			action: 'themex_action',
			data: values
		};
		
		//disable button
		saveButton.addClass('disabled');
			
		//send data to server
		$.post(ajaxurl, data, function(response) {
			$('.themex_panel .reset_options').removeClass('disabled');
			$('.themex_popup').text(response);
			$('.themex_popup').fadeTo(400,0.8);
			window.setTimeout(function() {
				$('.themex_popup').fadeOut(400);
			}, 2000);
		});		
	});
	
	/* --------------------------------Themex Interface----------------------------------- */
	
	//Tabs
	$('.themex_menu li:first-child').addClass('active');
	$('.themex_menu li').click(function() {
		$('.themex_pages .themex_page').hide();
		$('.themex_pages #'+$(this).attr('id')+'_page').show();
		$('.themex_menu li').removeClass('active');
		$(this).addClass('active');
	});
	
	//Options relations
	$('.themex_panel select').change(function() {
		var item=$('.'+$(this).attr('id'));
		if(item.length) {
			item.slideToggle(300, function() {
				var selected=$('.'+$(this).attr('id')+'.themex_child_'+$(this).find('option:selected').index());				
				if(selected.length) {
					selected.slideToggle(300);
				}
			});
		}
	});
	
	$.each($('.themex_panel select'), function (i, val) {
		var item=$('.'+$(this).attr('id')+'.themex_child_'+$(this).find('option:selected').index());
		if(item.length) {
			item.show();
		}
	});
	
	//Colorpicker
	$.each($('.themex_color'), function(i, val) {
		var item=$(this);
		item.children('div').css('background-color',item.next('input').val());
		item.ColorPicker({
			color: item.next('input').val(),
			onShow: function (picker) {
				$(picker).fadeIn(200);
				return false;
			},
			onHide: function (picker) {
				$(picker).fadeOut(200);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				item.children('div').css('background-color','#' + hex);
				item.next('input').val('#' + hex);	
				saveButton.removeClass('disabled');
			}
		});
    });
	
	//Slider
	$.each($('.themex_slider'), function(i, val) {
		var item=$(this);
		var unit=item.parent().find('div.unit').text();
		var currentValue=item.parent().find('input').val();		
		
		//set default value
		if(currentValue=='') {
			currentValue=parseInt(item.parent().find('div.min_value').text());
		}
		
		//set current value
		item.parent().find('div.slider_value').text(currentValue+' '+unit);
		
		//event handlers
		item.slider({
			value: currentValue,
			min: parseInt(item.parent().find('div.min_value').text()),
			max: parseInt(item.parent().find('div.max_value').text()),
			slide: function( event, ui ) {
				item.parent().find('div.slider_value').text( ui.value+' '+unit );
				item.parent().find('input').val(ui.value);
				
				//enable save button
				saveButton.removeClass('disabled');
			}
		});
	});
	
	//Datepicker
	$.each($('input.datepicker'), function() {
		$(this).datepicker({ dateFormat: 'dd/mm/yy' });
	});	
	
	//Option description
	$('.themex_tip').live(
	{
        mouseenter:
           function() {
				$(this).parent().append('<div class="themex_tip_cloud hidden"><div>'+$(this).text()+'</div></div>');
				$(this).parent().find('div.themex_tip_cloud').fadeTo(200,0.8);
			},
        mouseleave:
           function() {
				$(this).parent().find('div.themex_tip_cloud').fadeOut(200, function() {
					$(this).parent().find('div.themex_tip_cloud').remove();
				});
			}
    });
	
	//Select image option
	$('.themex_option.select_image img').click( function() {
		var item=$(this);
		
		//set active class
		item.parent().find('img').removeClass('active');
		item.addClass('active');
		saveButton.removeClass('disabled');
		
		//set option value
		item.parent().find('input').val(item.attr('alt'));
	});
	
	$.each($('.themex_option.select_image img'), function (i, val) {
		var item=$(this);
		if(item.parent().find('input').val()==item.attr('alt')) {
			item.addClass('active');
		}
	});
	
	//Uploader
	var header_clicked = false,
		fileInput = '';

	jQuery('.themex_panel .upload_button,.repeatable-upload,.themex_meta_table .upload_button,.themex_avatar .upload_button').live('click', function(e) {		
		fileInput = jQuery(this).prev('input');		
		tb_show('', 'media-upload.php?post=-629834&amp;themex_uploader=1&amp;TB_iframe=true');
		header_clicked = true;
		e.preventDefault();
	});	

	//store original function
	window.original_send_to_editor = window.send_to_editor;
	window.original_tb_remove = window.tb_remove;

	//override removing function (resets our boolean)
	window.tb_remove = function() {
		header_clicked = false;
		window.original_tb_remove();
	}
	
	//send data to editor
	window.send_to_editor = function(html) {
		if (header_clicked) {
			imgurl = jQuery(html).attr('href');
			fileInput.val(imgurl);
			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}
		
		fileInput.change();
		saveButton.removeClass('disabled');
	};
	
	//avatar image
	jQuery('.themex_avatar input').change(function() {
		$(this).prev('img').attr('src', $(this).val());
	});
	
	//Repeatable fields
	jQuery('.repeatable-add').click(function() {
		var fieldLocation = jQuery('tr.'+$(this).parent().parent().parent().next('tr').attr('class').split(' ')[1]+':last');
		var field=fieldLocation.clone(true);
		field.find('input').each(function() {
			var input=$(this);
			input.removeAttr('checked');
			input.val('').attr('name', input.attr('name').replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				})
			);
		});
		field.insertAfter(fieldLocation, field);
		return false;
	});

	jQuery('.repeatable-remove').click(function(){
		jQuery(this).parent().parent().remove();
		return false;
	});
	
	//Submit select
	$('select.submit-select').change(function() {
		var form=$($(this).attr('href'));
		
		if(!form.length || !form.is('form')) {
			form=$(this).parent();
			while(!form.is('form')) {
				form=form.parent();
			}
		}
			
		form.submit();		
		return false;
	});
	
	/* --------------------------------Themex Widgetiser----------------------------------- */
	
	$('.add_sidebar,.remove_sidebar,.add_category,.remove_category,.add_page,.remove_page').live( 'click', function() {
		var values = $('#themex_options').serialize();
		var button=$(this);
		var type='';
		
		//add sidebar
		if(button.is('.add_sidebar')) {
			if($('#themex_widgetiser_area_name').val()=='') {
				$('#themex_widgetiser_area_name').trigger('focus');
			} else {
				var data = {
					type: 'add_area',
					module: 'ThemexWidgetiser',
					area_name: $('#themex_widgetiser_area_name').val(),
					action: 'themex_action',
				};
				//send data to server
				$.post(ajaxurl, data, function(response) {
					button.parent('div').find('.themex_button.add_sidebar').parent().after(response);
					button.parent().next('.themex_section').slideToggle(300);
				});
			}
			
		//remove sidebar
		} else if(button.is('.remove_sidebar')) {
			var data = {
				type: 'remove_area',
				module: 'ThemexWidgetiser',
				action: 'themex_action',
				area_id: button.parent('div').parent('div').attr('id')
			};
			//send data to server
			$.post(ajaxurl, data, function(response) {
				button.parent('div').parent('div').slideToggle(300, function() {
					button.parent('div').parent('div').remove();
				});
			});
			
		//add page or category
		} else if(button.is('.add_page') || button.is('.add_category')) {
			var child_type='';
			if(button.is('.add_page')) {					
				child_type='pages';
			} else {
				child_type='categories';
			}
			var data = {
				type: 'add_area_child',
				module: 'ThemexWidgetiser',
				action: 'themex_action',
				child_type: child_type,
				area_id: button.parent('div').parent('div').attr('id')
			};
			//send data to server
			$.post(ajaxurl, data, function(response) {
				button.after(response);
				button.next('div.themex_option').slideToggle(300);
			});
		
		//remove page or category
		} else if(button.is('.remove_page') || button.is('.remove_category')) {
			var child_type='';
			if(button.is('.remove_page')) {					
				child_type='pages';
			} else {
				child_type='categories';
			}
			var data = {
				type: 'remove_area_child',
				module: 'ThemexWidgetiser',
				action: 'themex_action',
				child_type: child_type,
				child_id: button.parent('div').find('select').attr('id'),
				area_id: button.parent('div').parent('div').parent('div').attr('id')
			};
			//send data to server
			$.post(ajaxurl, data, function(response) {
				button.parent('div.themex_option').slideToggle(300);
			});
		}			
	});
	
	/* ----------------------------------Themex Form------------------------------------- */
	
	//hide remove button if only one field created
	if($('.themex_form .remove_field').length==1) {
		$('.themex_form .remove_field').hide();
	}
	
	$('.themex_form .add_field, .themex_form .remove_field').live( 'click', function() {
		var values = $('#themex_options').serialize();
		var button=$(this);
		var type='';
		
		//add field
		if(button.is('.add_field')) {
			var data = {
				type: 'add_field',
				slug: button.data('slug'),
				module: 'ThemexForm',
				action: 'themex_action'
			};
			//send data to server
			$.post(ajaxurl, data, function(response) {
				button.parent('div').after(response);
				button.parent('div').next('.themex_section').slideToggle(300, function() {
					if($('.themex_form .themex_section').length>1) {
						$('.themex_form .remove_field').show();
					}
				});
			});
			
		//remove field
		} else if(button.is('.remove_field')) {
			var data = {
				type: 'remove_field',
				slug: button.data('slug'),
				module: 'ThemexForm',
				action: 'themex_action',
				field_id: button.data('id')
			};
			//send data to server
			$.post(ajaxurl, data, function(response) {
				button.parent('div').slideToggle(300, function() {
					button.parent('div').remove();
					if($('.themex_form .themex_section').length==1) {
						$('.themex_form .remove_field').hide();
					}
				});				
			});
			
		}
	});
	
	//options visibility
	$('.themex_form select').live('change', function() {
		var item=$(this);
		var hiddenOption=item.parent('div').parent('div').find('div.hidden');
		if(item.find('option:selected').index()==5) {	
			hiddenOption.slideToggle(300);
		} else if(hiddenOption.is(':visible')) {
			hiddenOption.slideToggle(300);
		}
	});
	
	$.each($('.themex_form select'), function (i, val) {
		var item=$(this);
		var hiddenOption=item.parent('div').parent('div').find('div.hidden');
		if(item.find('option:selected').index()==5) {
			hiddenOption.show();
		}
	});	
	
	/* ----------------------------------Themex User------------------------------------- */
	
	if(jQuery('#profile-page').length) {
		jQuery('#description').parents('tr').remove();
	}
	
	$('.themex_user input.themex_parent').change(function() {	
		var children=$('.themex_user input.themex_child').parent();
		children.slideToggle(300);
	});
	
	$('.themex_user input.themex_parent').each(function() {
		var children=$('.themex_user input.themex_child').parent();
		if($(this).is(':checked')) {
			children.show();
		}
	});
});