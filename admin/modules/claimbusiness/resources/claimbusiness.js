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

jQuery('body').on('click', '.app_claimbusiness_request', function(){
				var uid = jQuery(this).data('id');
			
				bootbox.confirm(param.approve_request, function(result) {
				  if(result){
					  var data = {
								  "action": "approve_claimbusiness_request",
								  "uid": uid
								};
								
								var formdata = jQuery.param(data);
								
								jQuery.ajax({
					
											type: 'POST',
					
											url: ajaxurl,
											
											dataType: "json",
											
											beforeSend: function() {
												jQuery(".alert-success,.alert-danger").remove();
											},
											
											data: formdata,
					
											success:function (data, textStatus) {
												if(data['status'] == 'success'){
																			
													/*Reaload datatable after add new service*/
													dataTable.ajax.reload(null, false);
															
												}
												
												
											
											}
					
										});
				  }
				}); 
			
	 });

/*Start Claim Table*/
dataTable = jQuery('#claim-grid').DataTable( {
"serverSide": true,
"order": [[ 5, "desc" ]],
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
	data: {"action": "get_claimbusiness"},
	error: function(){  // error handling
		jQuery(".claim-grid-error").html("");
		jQuery("#claim-grid").append('<tbody class="claim-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
		jQuery("#claim-grid_processing").css("display","none");
		
	}
}
} );
	
	

//Bulk Claim Delete
jQuery("#bulkAdminClaimDelete").on('click',function() { // bulk checked
	var status = this.checked;
	jQuery(".deleteClaimRow").each( function() {
		jQuery(this).prop("checked",status);
	});
});
 
//Single Claim Delete
jQuery('#deleteAdminClaimTriger').on("click", function(event){ // triggering delete one by one
	if( jQuery('.deleteClaimRow:checked').length > 0 ){  // at-least one checkbox checked
		
	  bootbox.confirm(param.are_you_sure, function(result) {

	  if(result){

	   var ids = [];
		jQuery('.deleteClaimRow').each(function(){
			if(jQuery(this).is(':checked')) { 
				ids.push(jQuery(this).val());
			}
		});
		var ids_string = ids.toString();  // array to string conversion 
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {action: "delete_claimbusiness", data_ids:ids_string},
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


/*Approive Claim Request*/
jQuery('body').on('click', '#approveclaim', function(){
var cid = jQuery(this).data('id');
var pid = jQuery(this).data('providerid');

bootbox.confirm(param.are_you_sure, function(result) {

  if(result){
	var data = {
	  "action": "approveclaim",
	  "cid": cid,
	  "pid": pid,
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
						jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "#claim-grid" );	
						/*Reaload datatable after add new city*/
						dataTable.ajax.reload(null, false);
								
					}else if(data['status'] == 'error'){
						jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "#claim-grid" );
						
					}
				
				}

			});	  
  }

});												  
});	

/*Decline Claim Request*/
jQuery('body').on('click', '#declineclaim', function(){
var cid = jQuery(this).data('id');
var pid = jQuery(this).data('providerid');

bootbox.confirm(param.are_you_sure, function(result) {

  if(result){
	var data = {
	  "action": "declineclaim",
	  "cid": cid,
	  "pid": pid,
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
						jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "#claim-grid" );	
						/*Reaload datatable after add new city*/
						dataTable.ajax.reload(null, false);
								
					}else if(data['status'] == 'error'){
						jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "#claim-grid" );
						
					}
				
				}

			});	  
  }

});												  
});	

	
});