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

class SERVICE_FINDER_WALLET_REQUEST extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','wallet-request' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_wallet_requests',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_wallet_requests' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_approve_wallet_amount_request',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approve_wallet_amount_request' ) );
                    }
						
                );	
    }
	
	public function service_finder_get_wallet_requests(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	$requestData= $_REQUEST;
	
	$columns = array( 
		0 =>'payment_date', 
		1 =>'txn_id', 
		2 =>'payment_method', 
		3 =>'payment_status', 
		4 =>'amount', 
		5 =>'action', 
		6 =>'debit_for', 
	);
	
	// getting total number records without any search
	$sql = "SELECT * FROM ".$service_finder_Tables->wallet_transaction." WHERE 1 = 1";
	$query=$wpdb->get_results($sql);
	$totalData = count($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
	
	$sql = "SELECT * FROM ".$service_finder_Tables->wallet_transaction." WHERE 1 = 1";
	if( !empty($requestData['search']['value']) ) {   
		$sql.=" AND (( `txn_id` LIKE '".$requestData['search']['value']."%' )";    
		$sql.=" OR ( `payment_method` LIKE '".$requestData['search']['value']."%' )";    
		$sql.=" OR ( `payment_status` LIKE '".$requestData['search']['value']."%' )";    
		$sql.=" OR ( `amount` LIKE '".$requestData['search']['value']."%' )";    
		$sql.=" OR ( `action` LIKE '".$requestData['search']['value']."%' )";    
		$sql.=" OR ( `debit_for` LIKE '".$requestData['search']['value']."%' )";    
		$sql.=" OR ( `payment_date` LIKE '".$requestData['search']['value']."%' ))";    
	}
	
	$query=$wpdb->get_results($sql);
	$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=$wpdb->get_results($sql);
	$data = array();
	
	foreach($query as $result){
		$nestedData=array(); 
	
		$nestedData[] = '<a href="'.esc_url(service_finder_get_author_url($result->user_id)).'" target="_blank">'.service_finder_getProviderFullName($result->user_id).'</a>';
		$nestedData[] = date('d-m-Y',strtotime($result->payment_date));
		$nestedData[] = $result->txn_id;
		$nestedData[] = $result->payment_method;
		$nestedData[] = service_finder_translate_static_status_string($result->payment_status);
		$nestedData[] = service_finder_money_format($result->amount);
		
		if($result->payment_status == 'on-hold' && $result->payment_mode == 'woocommerce'){
			$nestedData[] = '<a href="'.admin_url().'post.php?post='.$result->txn_id.'&action=edit" target="_blank">'.esc_html__('Approve', 'service-finder').'</a>';
		}elseif($result->payment_status == 'pending' && $row->payment_mode != 'woocommerce'){
			$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->id).'" class="approve_wallet_payment">'.esc_html__('Approve', 'service-finder').'</a>';
		}else{
			if($row->payment_mode == 'woocommerce'){
				$nestedData[] = service_finder_translate_static_status_string($result->payment_status);
			}else{
				$nestedData[] = esc_html__( 'Completed', 'service-finder' );
			}
		}
		
		if($result->payment_mode == 'woocommerce'){
			$nestedData[] = '<a href="'.admin_url().'post.php?post='.$result->txn_id.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
		}else{
			$nestedData[] = '-';
		}
		
		$data[] = $nestedData;
	}
	
	
	
	$json_data = array(
				"draw"            => intval( $requestData['draw'] ),
				"recordsTotal"    => intval( $totalData ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
				);
	
	echo json_encode($json_data);
	exit(0);
	
	}
	
	public function service_finder_approve_wallet_amount_request(){
	global $wpdb, $service_finder_Tables;
	
	$request_id = (isset($_POST['request_id'])) ? esc_html($_POST['request_id']) : '';
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->wallet_transaction.' WHERE `id` = %d',$request_id));
	
	if(!empty($row)){
		$amount = $row->amount;
		$provider_id = $row->user_id;
		
		service_finder_add_wallet_amount($provider_id,$amount);
		
		$data = array(
				'payment_status' => 'completed',
				);
		$where = array(
				'id' => $request_id
		);		
		$wpdb->update($service_finder_Tables->wallet_transaction,wp_unslash($data),$where);
	}
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Wallet amount added successfully', 'service-finder'),
			);
	echo json_encode($success);

	exit(0);
	}
	
}