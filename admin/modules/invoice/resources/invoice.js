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
  
  jQuery('#invoice-booking-modal').on('show.bs.modal', function (event) {
																 
	   var button = jQuery(event.relatedTarget);
	   var bookingid = button.data('bookingid');																 
	   var data = {
		  "action": "booking_details",
		  "bookingid": bookingid,
		  "calendar": true
		};
		
		var formdata = jQuery.param(data);
		
		jQuery.ajax({

					type: 'POST',

					url: ajaxurl,
					
					beforeSend: function() {
						jQuery('.loading-area').show();
					},
					
					data: formdata,

					success:function (data, textStatus) {
						jQuery('.loading-area').hide();
						jQuery('#invoice-booking-modal .modal-body').html(data);
					}

				});
  });	  
  
  jQuery('body').on('click', '.invoicepaytoprovider', function(){
	
	var invoiceid = jQuery(this).data('invoiceid');
	var providerid = jQuery(this).data('providerid');
	var amount = jQuery(this).data('amount');

	var data = {
	   action: 'invoice_pay_via_adaptive',
	   invoiceid: invoiceid, 
	   providerid: providerid, 
	   amount: amount 
	};
	
	bootbox.confirm(param.payto_provider_confirm + " ("+currencysymbol+amount+")?", function(result) {

	if(result){
	
	jQuery('.invoicepaytoprovider').attr('disabled', 'true');
	
	jQuery.ajax({
	
		type: 'POST',

		url: ajaxurl,

		data: data,
		
		dataType: "json",
		
		beforeSend: function() {
			jQuery('.loading-area').show();
		},

		success:function (data, textStatus) {
				
				if(data['status'] == 'success'){
					if(data['sandbox']){
					window.location = 'https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay?expType=light&payKey=' + data['PayKey'];	
					}else{
					window.location = 'https://www.paypal.com/webapps/adaptivepayment/flow/pay?expType=light&payKey=' + data['PayKey'];
					}
					jQuery('.loading-area').hide();
					dataTable.ajax.reload(null, false);
				}else if(data['status'] == 'error'){
					jQuery('.loading-area').hide();
					jQuery('.invoicepaytoprovider').removeAttr('disabled');
					bootbox.alert(data['err_message']);
				}
		}

	});
	
	}

	});

	});
  
  jQuery('body').on('click', '.statusinvoicepaytoprovider', function(){
	
	var invoiceid = jQuery(this).data('invoiceid');

	var data = {
	   action: 'status_invoice_pay_to_provider',
	   invoiceid: invoiceid, 
	};
	
	bootbox.confirm(param.payto_provider_change_status, function(result) {

	if(result){
	
	jQuery('.statusinvoicepaytoprovider').attr('disabled', 'true');
	
	jQuery.ajax({
	
		type: 'POST',

		url: ajaxurl,

		data: data,
		
		dataType: "json",
		
		beforeSend: function() {
			jQuery('.loading-area').show();
		},

		success:function (data, textStatus) {
				jQuery('.loading-area').hide();
				if(data['status'] == 'success'){
					jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "#invoice-requests-grid_wrapper" );	
					dataTable.ajax.reload(null, false);
				}else if(data['status'] == 'error'){
					jQuery('.statusinvoicepaytoprovider').removeAttr('disabled');
					jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "#invoice-requests-grid_wrapper" );
				}
		}

	});
	
	}

	});

	});
  
  jQuery('body').on('click', '.approve_wiredinvoice', function(){
			var invoiceid = jQuery(this).data('id');
			
				bootbox.confirm(param.approve_request, function(result) {
				  if(result){
					  var data = {
								  "action": "approve_wired_invoice",
								  "invoiceid": invoiceid
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
  
	/*Start Featured Providers Table*/
	dataTable = jQuery('#invoice-requests-grid').DataTable( {
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
		data: {"action": "get_admin_invoice","bookingid": bookingid},
		error: function(){  // error handling
			jQuery(".invoice-requests-grid-error").html("");
			jQuery("#invoice-requests-grid").append('<tbody class="invoice-requests-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
			jQuery("#invoice-requests-grid_processing").css("display","none");
			
		}
	}
	} );
	
	/*Search By Provider*/
	jQuery('#byproviderinvoice').change(function(){

		dataTable.column(3).search(this.value).draw();
	
	});
	
	//Bulk Providers Delete
	jQuery("#bulkAdminInvoiceDelete").on('click',function() { // bulk checked
        var status = this.checked;
        jQuery(".deleteInvoiceRow").each( function() {
            jQuery(this).prop("checked",status);
        });
    });
     
    //Single Providers Delete
	jQuery('#deleteAdminInvoiceTriger').on("click", function(event){ // triggering delete one by one
        if( jQuery('.deleteInvoiceRow:checked').length > 0 ){  // at-least one checkbox checked
            
			bootbox.confirm(param.are_you_sure, function(result) {

		  if(result){

           var ids = [];
            jQuery('.deleteInvoiceRow').each(function(){
                if(jQuery(this).is(':checked')) { 
                    ids.push(jQuery(this).val());
                }
            });
            var ids_string = ids.toString();  // array to string conversion 
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: "delete_admin_invoice", data_ids:ids_string},
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
	/*End Providers Table*/
	
  });