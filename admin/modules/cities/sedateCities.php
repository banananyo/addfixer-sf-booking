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
 * Class SERVICE_FINDER_sedateFeatured
 */
class SERVICE_FINDER_sedateCities extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','cities' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_add_city',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_add_city' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_get_cities',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_cities' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_delete_city',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_delete_city' ) );
                    }
						
                );
    }
	
	/*Add city to db*/
	public function service_finder_add_city(){
		global $wpdb, $service_finder_Tables;
		
		$cityname = isset($_POST['cityname']) ? strtolower(esc_html($_POST['cityname'])) : '';
		$countryname = isset($_POST['countryname']) ? strtolower(esc_html($_POST['countryname'])) : '';
		
		$chkcity = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->cities.' WHERE `cityname` = "%s" AND `countryname` = "%s"',$cityname,$countryname));
		
		if(empty($chkcity)){
		$data = array(
				'cityname' => $cityname,
				'countryname' => $countryname, 
				);
		$wpdb->insert($service_finder_Tables->cities,$data);
		
		$cityid = $wpdb->insert_id;
				
		if ($cityid > 0) {
		
			$success = array(
					'status' => 'success',
					'suc_message' => esc_html__('City added successfully', 'service-finder'),
					);
			echo json_encode($success);
		}else{
			$error = array(
					'status' => 'error',
					'err_message' => esc_html__('Couldn&#8217;t add city.', 'service-finder'),
					);
			echo json_encode($error);
		
		}
		}else{
			$error = array(
					'status' => 'error',
					'err_message' => esc_html__('City already exist.', 'service-finder'),
					);
			echo json_encode($error);
		}
		exit(0);
	}
	
	/*Display cities to datatable*/
	public function service_finder_get_cities(){
		global $wpdb, $service_finder_Tables;
		$requestData= $_REQUEST;

		$cities = $wpdb->get_results('SELECT * FROM '.$service_finder_Tables->cities);
		
		$columns = array( 
			0 => 'cityname', 
			1=> 'cityname',
			2=> 'countryname',
		);
		
		$totalData = count($cities);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT * FROM '.$service_finder_Tables->cities.' WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {
			$sql.=" AND ( cityname LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR countryname LIKE '".$requestData['search']['value']."%' )";
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query);
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length'];

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
		
			$nestedData[] = "<input type='checkbox' class='deleteCityRow' value='".esc_attr($result->id)."' />";
			$nestedData[] = ucfirst($result->cityname);
			$nestedData[] = ucfirst($result->countryname);
			
			$data[] = $nestedData;
		}
		
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),
					"recordsTotal"    => intval( $totalData ),
					"recordsFiltered" => intval( $totalFiltered ),
					"data"            => $data
					);
		
		echo json_encode($json_data);  // send data as json format
		exit(0);
	}
	
	/*Delete Cities*/
	public function service_finder_delete_city(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->cities." WHERE id = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	exit(0);		
	}
}