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
 * Class SERVICE_FINDER_sedateQuotations
 */
class SERVICE_FINDER_PAYOUT_HISTORY extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','payout-history' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_payout_history',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_payout_history' ) );
                    }
						
                );
    }
	
	/*Display payout history into datatable*/
	public function service_finder_get_payout_history(){
		global $wpdb, $service_finder_Tables;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT * FROM '.$service_finder_Tables->payout_history);
		
		$columns = array( 
			0 =>'booking_id', 
			1 => 'created',
			2 => 'arrival_date',
			3 => 'amount',
			4 => 'stripe_connect_type',
			5 => 'connected_account_id',
			6 => 'status',
		);
		
		// getting total number records without any search
		$sql = 'SELECT * FROM '.$service_finder_Tables->payout_history;
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT * FROM '.$service_finder_Tables->payout_history.' WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( booking_id LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR amount LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR stripe_connect_type LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR connected_account_id LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR status LIKE '".$requestData['search']['value']."%' )";
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
			$userLink = service_finder_get_author_url($result->provider_id);
			$nestedData[] = '#'.$result->booking_id;
			$nestedData[] = '<a href="'.esc_url($userLink).'" target="_blank">'.service_finder_getProviderName($result->provider_id).'</a>';
			$nestedData[] = $result->created;
			$nestedData[] = $result->arrival_date;
			$nestedData[] = service_finder_money_format($result->amount);
			$nestedData[] = $result->stripe_connect_type;
			$nestedData[] = $result->connected_account_id;
			$nestedData[] = $result->status;
			
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
	
}