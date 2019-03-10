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

	jQuery('.add-new-labels')
	.bootstrapValidator({
	message: param.not_valid,
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
				category: {
					validators: {
						notEmpty: {
							message: param.req
						}
					}
				},
				labelname: {
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
	.on('change', 'select[name="category"]', function() {
			var categoryid = jQuery(this).val();
			
			var data = {
			  "action": "load_labels",
			  "categoryid": categoryid,
			};
			
			var formdata = jQuery.param(data);
			
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
					if(data['status'] == 'success'){
						 jQuery("input[name='labelname']").val('');
						 jQuery("#labels-list").html(data['html']);
					}
				
				}
	
			});
	})
	.on('success.form.bv', function(form) {
	// Prevent form submission
	form.preventDefault();	
	
	var $form = jQuery(form.target);
	// Get the BootstrapValidator instance
	var bv = $form.data('bootstrapValidator');
	
	var catid = jQuery("select[name='category']").val();
	
	var data = {
	  "action": "add_labels",
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
						jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "form.add-new-labels .modal-body" );	
						 jQuery("#labels-list").html(data['html']);
						 jQuery('.add-new-labels').bootstrapValidator('resetForm', true);
						 jQuery("select[name='category']").val(catid);
								
					}else if(data['status'] == 'error'){
						jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "form.add-new-labels .modal-body" );
						jQuery('.add-new-labels').bootstrapValidator('resetForm', true);
						
					}
				
				}
	
			});
	});
	
	//Delete Group
	jQuery('body').on('click', '.delete-label', function(){
												  
		var lid = jQuery(this).data('id');
		
		var $this = jQuery(this);
		
		bootbox.confirm(param.are_you_sure, function(result) {
		  if(result){
			  var data = {
			  "action": "delete_label",
			  "labelid": lid,
			};
			
			var data = jQuery.param(data);
			
			jQuery.ajax({

						type: 'POST',

						url: ajaxurl,
						
						data: data,
						
						dataType: "json",
						
						beforeSend: function() {
							jQuery('.loading-area').show();
							jQuery('.alert').remove();
						},

						success:function (data, textStatus) {
							jQuery('.loading-area').hide();

							if(data['status'] == 'success'){

								$this.closest('li').remove();
								
								jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "form.add-new-labels .modal-body" );

							}
						}

					});
			  }
		}); 
		
    });

	jQuery('#addratinglabels').on('hide.bs.modal', function (event) {
	  jQuery("#addratinglabels .alert").remove();
	  jQuery("input[name='labelname']").val('');
	  jQuery("select[name='category']").val('');
	  jQuery("#labels-list").html('');
	  /*Reaload datatable after add new city*/
	  dataTable.ajax.reload(null, false);
	}); 

	dataTable = jQuery('#ratinglabels-grid').DataTable( {
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
	data: {"action": "get_labels"},
	error: function(){  // error handling
		jQuery(".ratinglabels-grid-error").html("");
		jQuery("#ratinglabels-grid").append('<tbody class="ratinglabels-requests-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
		jQuery("#ratinglabels-grid_processing").css("display","none");
		
	}
	}
	} );
	
	jQuery("#bulkAdminRatingLabelsDelete").on('click',function() { // bulk checked
		var status = this.checked;
		jQuery(".deleteRatingLabelsRow").each( function() {
			jQuery(this).prop("checked",status);
		});
	});
	 
	jQuery('#deleteRatingLabelsTriger').on("click", function(event){ // triggering delete one by one
		if( jQuery('.deleteRatingLabelsRow:checked').length > 0 ){  // at-least one checkbox checked
			
		  bootbox.confirm(param.are_you_sure, function(result) {
	
		  if(result){
	
		   var ids = [];
			jQuery('.deleteRatingLabelsRow').each(function(){
				if(jQuery(this).is(':checked')) { 
					ids.push(jQuery(this).val());
				}
			});
			var ids_string = ids.toString();  // array to string conversion 
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: {action: "delete_labels", data_ids:ids_string},
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