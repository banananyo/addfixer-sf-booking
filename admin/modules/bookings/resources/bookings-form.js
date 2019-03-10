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
  var dataTable;
  
  //View Bookings
  jQuery('body').on('click', '.viewBookings', function(){
											  
	var bid = jQuery(this).attr('data-id');
	var upcoming = jQuery(this).attr('data-upcoming');
	if(upcoming == 'yes'){
		var flag = 1;	
	}else{
		var flag = 0;	
	}
	viewBookings(bid,flag,'yes');
	
  });
  
  //Close details
  jQuery('body').on('click', '.closeDetails', function(){
		jQuery('#booking-details').addClass('hidden fade in');
		jQuery('#admin-bookings-grid_wrapper').removeClass('hidden');
  });
  
  function viewBookings(bid,flag = 1,isadmin){
		
		jQuery('#admin-bookings-grid_wrapper').addClass('hidden');
		jQuery('#booking-details').removeClass('hidden');
		
		var data = {
		  "action": "booking_details",
		  "bookingid": bid,
		  "flag": flag,
		  "isadmin": isadmin,
		};
		
		var data = jQuery.param(data);
		
		jQuery.ajax({

					type: 'POST',

					url: ajaxurl,
					
					data: data,
					
					beforeSend: function() {
						jQuery('.loading-area').show();
					},

					success:function (data, textStatus) {
						jQuery('.loading-area').hide();
						
						jQuery('#booking-details').html(data);
						
						jQuery('.display-ratings').rating();
						jQuery('.sf-show-rating').show();
					}

				});		
	}
  
	jQuery('body').on('click', '.paytoprovider', function(){
	
	var paykey = jQuery(this).data('paykey');
	var providerid = jQuery(this).data('providerid');
	var amount = jQuery(this).data('amount');

	var data = {
	   action: 'pay_via_adaptive',
	   providerid: providerid, 
	   paykey: paykey 
	};
	
	bootbox.confirm(param.payto_provider_confirm + " ("+currencysymbol+amount+")?", function(result) {

	if(result){
	
	jQuery('.paytoprovider').attr('disabled', 'true');
	
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
					bootbox.alert(data['suc_message']);
					dataTable.ajax.reload(null, false);
				}else if(data['status'] == 'error'){
					jQuery('.paytoprovider').removeAttr('disabled');
					bootbox.alert(data['err_message']);
				}
		}

	});
	
	}

	});

	});
	
	jQuery('body').on('click', '.paytoproviderviastripe', function(){
	
	var bookingid = jQuery(this).data('bookingid');
	var providerid = jQuery(this).data('providerid');
	var amount = jQuery(this).data('amount');

	var data = {
	   action: 'pay_via_stripe_connect',
	   providerid: providerid, 
	   bookingid: bookingid, 
	   amount: amount
	};
	
	bootbox.confirm(param.payto_provider_confirm + " ("+currencysymbol+amount+")?", function(result) {

	if(result){
	
	jQuery('.paytoproviderviastripe').attr('disabled', 'true');
	
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
					bootbox.alert(data['suc_message']);
					dataTable.ajax.reload(null, false);
				}else if(data['status'] == 'error'){
					jQuery('.paytoproviderviastripe').removeAttr('disabled');
					bootbox.alert(data['err_message']);
				}
		}

	});
	
	}

	});

	});
	
	jQuery('body').on('click', '.paytoproviderviamangopay', function(){
	var bookingid = jQuery(this).data('bookingid');
	var providerid = jQuery(this).data('providerid');
	var amount = jQuery(this).data('amount');
	var orderid = jQuery(this).data('orderid');

	var data = {
	   action: 'pay_via_mangopay',
	   providerid: providerid, 
	   bookingid: bookingid,
	   orderid: orderid,
	   amount: amount
	};
	
	bootbox.confirm(param.payto_provider_confirm + " ("+currencysymbol+amount+")?", function(result) {

	if(result){
	
	jQuery('.paytoproviderviastripe').attr('disabled', 'true');
	
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
					bootbox.alert(data['suc_message']);
					dataTable.ajax.reload(null, false);
				}else if(data['status'] == 'error'){
					jQuery('.paytoproviderviastripe').removeAttr('disabled');
					bootbox.alert(data['err_message']);
				}
		}

	});
	
	}

	});

	});
	
	jQuery('body').on('click', '.statuspaytoprovider', function(){
	
	var bookingid = jQuery(this).data('bookingid');

	var data = {
	   action: 'status_pay_to_provider',
	   bookingid: bookingid, 
	};
	
	bootbox.confirm(param.payto_provider_change_status, function(result) {

	if(result){
	
	jQuery('.statuspaytoprovider').attr('disabled', 'true');
	
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
					jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "#admin-bookings-grid_wrapper" );	
					dataTable.ajax.reload(null, false);
				}else if(data['status'] == 'error'){
					jQuery('.statuspaytoprovider').removeAttr('disabled');
					jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "#admin-bookings-grid_wrapper" );
				}
		}

	});
	
	}

	});

	});
	
  //Approve wired booking
	jQuery('body').on('click', '.adminapprovewiredbooking', function(){
		var bookingid = jQuery(this).data('bookingid');													 
		
		var data = {
					  "action": "wired_booking_admin_approval",
					  "bookingid": bookingid,
					};
				var formdata = jQuery.param(data);
				  
				jQuery.ajax({
	
					type: 'POST',
	
					url: ajaxurl,
	
					data: formdata,
					
					dataType: "json",
					
					beforeSend: function() {
						jQuery('.loading-area').show();
					},
	
					success:function (data, textStatus) {
							jQuery('.loading-area').hide();
							if(data['status'] == 'success'){
								jQuery( "<div class='alert alert-success'>"+data['suc_message']+"</div>" ).insertBefore( "#admin-bookings-grid_wrapper" );	
								dataTable.ajax.reload(null, false);
							}else if(data['status'] == 'error'){
								jQuery( "<div class='alert alert-danger'>"+data['err_message']+"</div>" ).insertBefore( "#admin-bookings-grid_wrapper" );
							}
					}
	
				});
	});
  
	/*Start Admin Booking Table*/
	//Display Bookings in Data Table
	dataTable = jQuery('#admin-bookings-grid').DataTable( {
	"serverSide": true,
	"order": [[ 12, "desc" ]],
	"columnDefs": [ {
		  "targets": 0,
		  "orderable": true,
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
		data: {"action": "get_admin_bookings"},
		error: function(){  // error handling
			jQuery(".admin-bookings-grid-error").html("");
			jQuery("#admin-bookings-grid").append('<tbody class="admin-bookings-grid-error"><tr><th colspan="3">'+param.no_data+'</th></tr></tbody>');
			jQuery("#admin-bookings-grid_processing").css("display","none");
			
		}
	}
	} );
	
	//Bulk Bookings Delete
	jQuery("#bulkAdminBookingsDelete").on('click',function() { // bulk checked
        var status = this.checked;
        jQuery(".deleteAdminBookingRow").each( function() {
            jQuery(this).prop("checked",status);
        });
    });
     
    //Single Booking Delete
	jQuery('#deleteAdminBookingTriger').on("click", function(event){ // triggering delete one by one
        if( jQuery('.deleteAdminBookingRow:checked').length > 0 ){  // at-least one checkbox checked
            
			bootbox.confirm(param.are_you_sure, function(result) {

		  if(result){

           var ids = [];
            jQuery('.deleteAdminBookingRow').each(function(){
                if(jQuery(this).is(':checked')) { 
                    ids.push(jQuery(this).val());
                }
            });
            var ids_string = ids.toString();  // array to string conversion 
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: "delete_admin_bookings", data_ids:ids_string},
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
	/*End Admin Booking Table*/
	
	/*Search By Date*/
	jQuery('#bydate').change(function(){

		dataTable.column(1).search(this.value).draw();
	
	});
	
	/*Search By Provider*/
	jQuery('#byprovider').change(function(){

		dataTable.column(3).search(this.value).draw();
	
	});
	
	/*Toggle Column*/
	jQuery('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = dataTable.column( jQuery(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
	
	
  });