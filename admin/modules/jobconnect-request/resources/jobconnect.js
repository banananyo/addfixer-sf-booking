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
  
  jQuery('body').on('click', '.app_jobconnect_request', function(){
			var uid = jQuery(this).data('id');
			
				bootbox.confirm(param.approve_request, function(result) {
				  if(result){
					  var data = {
								  "action": "approve_jobconnect_request",
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
  
	//Start Display Providers in Data Table
	dataTable = jQuery('#jobconnect-requests-grid').DataTable( {
	"serverSide": true,
	"order": [[ 0, "desc" ]],
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
		data: {"action": "get_jobconnect_requests"},
		error: function(){  // error handling
			jQuery(".jobconnect-requests-grid-error").html("");
			jQuery("#jobconnect-requests-grid").append('<tbody class="jobconnect-requests-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
			jQuery("#jobconnect-requests-grid_processing").css("display","none");
			
		}
	}
	} );
	
  });