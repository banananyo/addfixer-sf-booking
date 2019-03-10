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
  
	/*Start Customers Table*/
	//Display Customers in Data Table
	var dataTable = jQuery('#customers-grid').DataTable( {
	"serverSide": true,
	"order": [[ 9, "desc" ]],
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
		data: {"action": "get_customers"},
		error: function(){  // error handling
			jQuery(".customers-grid-error").html("");
			jQuery("#customers-grid").append('<tbody class="customers-grid-error"><tr><th colspan="3">'+param.not_valid+'</th></tr></tbody>');
			jQuery("#customers-grid_processing").css("display","none");
			
		}
	}
	} );
	
	//Bulk Customers Delete
	jQuery("#bulkCustomersDelete").on('click',function() { // bulk checked
        var status = this.checked;
        jQuery(".deleteCustomersRow").each( function() {
            jQuery(this).prop("checked",status);
        });
    });
     
    //Single Customers Delete
	jQuery('#deleteCustomersTriger').on("click", function(event){ // triggering delete one by one
        if( jQuery('.deleteCustomersRow:checked').length > 0 ){  // at-least one checkbox checked
		
		bootbox.confirm(param.are_you_sure, function(result) {

		  if(result){

               var ids = [];
            jQuery('.deleteCustomersRow').each(function(){
                if(jQuery(this).is(':checked')) { 
                    ids.push(jQuery(this).val());
                }
            });
            var ids_string = ids.toString();  // array to string conversion 
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: "delete_customers", data_ids:ids_string},
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
	/*End Customers Table*/
	
	/*Toggle Column*/
	jQuery('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = dataTable.column( jQuery(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
	
	
  });