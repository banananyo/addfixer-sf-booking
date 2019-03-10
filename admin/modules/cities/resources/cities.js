/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/
// When the browser is ready...
  jQuery(function() {
  'use strict';
  
  var dataTable = '';

jQuery('.add-new-city')
.bootstrapValidator({
	message: param.not_valid,
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
				cityname: {
					validators: {
						notEmpty: {
							message: param.req
						}
					}
				},
				countryname: {
					validators: {
						notEmpty: {
							message: param.req
						}
					}
	            },
            }
})
.on('error.field.bv', function(e, data) {
	data.bv.disableSubmitButtons(false); // disable submit buttons on errors
})
.on('status.field.bv', function(e, data) {
	data.bv.disableSubmitButtons(false); // disable submit buttons on valid
})
.on('success.form.bv', function(form) {
	// Prevent form submission
	form.preventDefault();	

	var $form = jQuery(form.target);
	// Get the BootstrapValidator instance
	var bv = $form.data('bootstrapValidator');
	
	var data = {
	  "action": "add_city",
	};
	
	var formdata = jQuery($form).serialize() + "&" + jQuery.param(data);
	
	jQuery.ajax({

				type: 'POST',

				url: ajaxurl,
				
				dataType: "json",
				
				beforeSend: function() {
					jQuery(".alert-success,.alert-danger").remove();
					jQuery('.loading-area').show();
				},
				
				data: formdata,

				success:function (data, textStatus) {
					jQuery('.loading-area').hide();
					$form.find('input[type="submit"]').prop('disabled', false);
					if(data['status'] == 'success'){
						jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "form.add-new-city .modal-body" );	
						/*Reaload datatable after add new city*/
						dataTable.ajax.reload(null, false);
								
					}else if(data['status'] == 'error'){
						jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "form.add-new-city .modal-body" );
						
					}
				
				}

			});
});

jQuery('.upload-cities-csv')
.bootstrapValidator({
	message: param.not_valid,
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
				citycsv: {
					validators: {
						notEmpty: {
							message: param.req
						}
					}
				},
            }
})
.on('error.field.bv', function(e, data) {
	data.bv.disableSubmitButtons(false); // disable submit buttons on errors
})
.on('status.field.bv', function(e, data) {
	data.bv.disableSubmitButtons(false); // disable submit buttons on valid
})
.on('success.form.bv', function(form) {
	// Prevent form submission
	form.preventDefault();	

	var $form = jQuery(form.target);
	// Get the BootstrapValidator instance
	var bv = $form.data('bootstrapValidator');
	
	jQuery.ajax({

				type: 'POST',
				
                processData: false,
				
				enctype: 'multipart/form-data',

				url: ajaxurl,
				
				dataType: "json",
				
				beforeSend: function() {
					jQuery(".alert-success,.alert-danger").remove();
					jQuery('.loading-area').show();
				},
				
				data: new FormData(this),
				
				contentType: false,

				cache: false,
	
				processData:false, 

				success:function (data, textStatus) {
					jQuery('.loading-area').hide();
					$form.find('input[type="submit"]').prop('disabled', false);
					if(data['status'] == 'success'){
						jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "form.upload-cities-csv .modal-body" );	
						/*Reaload datatable after add new city*/
						dataTable.ajax.reload(null, false);
								
					}else if(data['status'] == 'error'){
						jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "form.upload-cities-csv .modal-body" );
						
					}
				
				}

			});
});

 
jQuery('#addcity').on('hide.bs.modal', function (event) {
	  jQuery("#addcity .alert").remove();
	  jQuery("input[name='cityname']").val('');
	  jQuery("input[name='countryname']").val('');
}); 
  
/*Start Cities Providers Table*/
dataTable = jQuery('#cities-grid').DataTable( {
"serverSide": true,
"columnDefs": [ {
	  "targets": 0,
	  "orderable": false,
	  "searchable": false
	   
	},
	],
"processing": true,
"language": {
				"processing": "<div></div><div></div><div></div><div></div><div></div>",
				"emptyTable":     param.empty_table,
				"search":         param.dt_search+":",
				"lengthMenu":     param.dt_show + " _MENU_ " + param.dt_entries,
				"info":           param.dt_showing + " _START_ " + param.dt_to + " _END_ " + param.dt_of + " _TOTAL_ " + param.dt_entries,
				"paginate": {
					first:      param.dt_first,
					previous:   param.dt_previous,
					next:       param.dt_next,
					last:       param.dt_last,
				},
			},
"ajax":{
	url :ajaxurl, // json datasource
	type: "post",  // method  , by default get
	data: {"action": "get_cities"},
	error: function(){  // error handling
		jQuery(".cities-grid-error").html("");
		jQuery("#cities-grid").append('<tbody class="featured-requests-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
		jQuery("#cities-grid_processing").css("display","none");
		
	}
}
} );
	
	//Bulk Cities Delete
	jQuery("#bulkAdminCityDelete").on('click',function() { // bulk checked
        var status = this.checked;
        jQuery(".deleteCityRow").each( function() {
            jQuery(this).prop("checked",status);
        });
    });
     
    //Single City Delete
	jQuery('#deleteAdminCityTriger').on("click", function(event){ // triggering delete one by one
        if( jQuery('.deleteCityRow:checked').length > 0 ){  // at-least one checkbox checked
            
		  bootbox.confirm(param.are_you_sure, function(result) {

		  if(result){

           var ids = [];
            jQuery('.deleteCityRow').each(function(){
                if(jQuery(this).is(':checked')) { 
                    ids.push(jQuery(this).val());
                }
            });
            var ids_string = ids.toString();  // array to string conversion 
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: "delete_city", data_ids:ids_string},
                success: function(result) {
                    dataTable.draw(); // redrawing datatable
                },
                async:false
            });

		}

		});
			
        }else{

				bootbox.alert(param.select_checkbox);

		}
    });
	/*End City Table*/
	
});