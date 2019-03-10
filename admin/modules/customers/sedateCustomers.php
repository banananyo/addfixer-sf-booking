<?php
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class SERVICE_FINDER_sedateCustomers
 */
class SERVICE_FINDER_sedateCustomers extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander customers template*/
		$this->service_finder_render( 'index','customers' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_customers',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_customers' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_delete_customers',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_delete_customers' ) );
                    }
						
                );		
				
    }
	
	/*Display customers into datatable*/
	public function service_finder_get_customers(){
		global $wpdb, $service_finder_Tables;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT customerdata.id,customerdata.wp_user_id,customerdata.phone, customerdata.phone2, customerdata.address, customerdata.apt, customerdata.city, customerdata.state, customerdata.zipcode, userdata.user_email, userdata.display_name FROM '.$service_finder_Tables->customers_data.' as customerdata INNER JOIN `'.$wpdb->prefix.'users` as userdata on customerdata.wp_user_id = userdata.ID');
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'display_name', 
			1=> 'phone',
			2 => 'phone2',
			3 => 'user_email',
			4 => 'address',
			5 => 'apt',
			6 => 'city',
			7 => 'state',
			8 => 'zipcode',
			9 => 'id'
		);
		
		// getting total number records without any search
		$sql = 'SELECT customerdata.id,customerdata.wp_user_id,customerdata.phone, customerdata.phone2, customerdata.address, customerdata.apt, customerdata.city, customerdata.state, customerdata.zipcode, userdata.user_email, userdata.display_name FROM '.$service_finder_Tables->customers_data.' as customerdata INNER JOIN `'.$wpdb->prefix.'users` as userdata on customerdata.wp_user_id = userdata.ID';
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT customerdata.id,customerdata.wp_user_id,customerdata.phone, customerdata.phone2, customerdata.address, customerdata.apt, customerdata.city, customerdata.state, customerdata.zipcode, userdata.user_email, userdata.display_name FROM '.$service_finder_Tables->customers_data.' as customerdata INNER JOIN `'.$wpdb->prefix.'users` as userdata on customerdata.wp_user_id = userdata.ID WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( userdata.display_name LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR customerdata.phone LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customerdata.phone2 LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR userdata.user_email LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customerdata.address LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customerdata.apt LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customerdata.city LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customerdata.state LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customerdata.zipcode LIKE '".$requestData['search']['value']."%' )";
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
			$resultid = (!empty($result->wp_user_id)) ? $result->wp_user_id : '';
			$nestedData[] = "<input type='checkbox' class='deleteCustomersRow' value='".esc_attr($resultid)."'  />";
			$nestedData[] = service_finder_getCustomerName($result->wp_user_id);
			$nestedData[] = $result->phone;
			$nestedData[] = $result->phone2;
			$nestedData[] = $result->user_email;
			$nestedData[] = $result->address;
			$nestedData[] = $result->apt;
			$nestedData[] = $result->city;
			$nestedData[] = $result->state;
			$nestedData[] = $result->zipcode;
			
			$data[] = $nestedData;
		}
		
		
		
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
					"recordsTotal"    => intval( $totalData ),  // total number of records
					"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
					"data"            => $data   // total data array
					);
		
		echo json_encode($json_data);  // send data as json format
		exit(0);
	}
	
	/*Delete customers*/
	protected function service_finder_delete_customers(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					wp_delete_user( $id );
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->customers_data." WHERE id = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	exit(0);		
	}
	
}