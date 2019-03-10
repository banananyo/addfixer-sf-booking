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
dataTable = jQuery('#quotations-grid').DataTable( {
"serverSide": true,
"order": [[ 6, "desc" ]],
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
	data: {"action": "get_quotations"},
	error: function(){  // error handling
		jQuery(".quotations-grid-error").html("");
		jQuery("#quotations-grid").append('<tbody class="quotations-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
		jQuery("#quotations-grid_processing").css("display","none");
		
	}
}
} );
	
	

//Bulk Quotations Delete
jQuery("#bulkAdminQuoteDelete").on('click',function() { // bulk checked
	var status = this.checked;
	jQuery(".deleteQuoteRow").each( function() {
		jQuery(this).prop("checked",status);
	});
});
 
//Single Quote Delete
jQuery('#deleteAdminQuoteTriger').on("click", function(event){ // triggering delete one by one
	if( jQuery('.deleteQuoteRow:checked').length > 0 ){  // at-least one checkbox checked
		
	  bootbox.confirm(param.are_you_sure, function(result) {

	  if(result){

	   var ids = [];
		jQuery('.deleteQuoteRow').each(function(){
			if(jQuery(this).is(':checked')) { 
				ids.push(jQuery(this).val());
			}
		});
		var ids_string = ids.toString();  // array to string conversion 
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {action: "delete_quote", data_ids:ids_string},
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

/*Approive Feature Request*/
jQuery('body').on('click', '#approvemail', function(){
var qid = jQuery(this).data('id');
var pid = jQuery(this).data('providerid');

bootbox.confirm(param.are_you_sure_approve_mail, function(result) {

  if(result){
	var data = {
	  "action": "approvemail",
	  "qid": qid,
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
						jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "#quotations-grid" );	
						/*Reaload datatable after add new city*/
						dataTable.ajax.reload(null, false);
								
					}else if(data['status'] == 'error'){
						jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "#quotations-grid" );
						
					}
				
				}

			});	  
  }

});												  
});	
	
	
});