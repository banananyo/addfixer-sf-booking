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
/*Start Featured Providers Table*/
dataTable = jQuery('#payout-history-grid').DataTable( {
"serverSide": true,
"order": [[ 2, "desc" ]],
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
	data: {"action": "get_payout_history"},
	error: function(){  // error handling
		jQuery(".payout-history-grid-error").html("");
		jQuery("#payout-history-grid").append('<tbody class="payout-history-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
		jQuery("#payout-history-grid_processing").css("display","none");
		
	}
}
} );
	
	
});