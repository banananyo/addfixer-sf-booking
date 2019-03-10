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
  
  jQuery('body').on('click', '.approve_wallet_payment', function(){
		var request_id = jQuery(this).data('id');
		bootbox.confirm(param.approve_request, function(result) {
		  if(result){
			  var data = {
						  "action": "approve_wallet_amount_request",
						  "request_id": request_id
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
	dataTable = jQuery('#wallet-requests-grid').DataTable( {
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
		data: {"action": "get_wallet_requests"},
		error: function(){  // error handling
			jQuery(".wallet-requests-grid-error").html("");
			jQuery("#wallet-requests-grid").append('<tbody class="wallet-requests-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
			jQuery("#wallet-requests-grid_processing").css("display","none");
			
		}
	}
	} );
	
  });