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
class SERVICE_FINDER_sedateQuotations extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','quotations' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_quotations',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_quotations' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_approvemail',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approvemail' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_delete_quote',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_delete_quote' ) );
                    }
						
                );	
		
    }
	
	/*Display quotations into datatable*/
	public function service_finder_get_quotations(){
		global $wpdb, $service_finder_Tables;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT * FROM '.$service_finder_Tables->quotations);
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'provider_id', 
			1=> 'name',
			3 => 'date',
			4 => 'email',
			5 => 'phone',
			6 => 'id',
			7 => 'status',
		);
		
		// getting total number records without any search
		$sql = 'SELECT * FROM '.$service_finder_Tables->quotations;
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT * FROM '.$service_finder_Tables->quotations.' WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( provider_id LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR name LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR date LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR email LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR phone LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR message LIKE '".$requestData['search']['value']."%' ";
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
			$nestedData[] = "<input type='checkbox' class='deleteQuoteRow' value='".esc_attr($result->id)."' />";
			$nestedData[] = '<a href="'.esc_url($userLink).'" target="_blank">'.service_finder_getProviderName($result->provider_id).'</a>';
			$nestedData[] = $result->name;
			$nestedData[] = $result->date;
			$nestedData[] = $result->email;
			$nestedData[] = $result->phone;
			$nestedData[] = $result->message;
			
			if($result->status == "hold"){
				$nestedData[] = '<a href="javascript:;" class="btn btn-success btn-xs" data-id="'.esc_attr($result->id).'" data-providerid="'.esc_attr($result->provider_id).'" id="approvemail">'.esc_html__('Approve', 'service-finder').'</a>';
			}else{
				$nestedData[] = esc_html__('Mail Sent', 'service-finder');
			}
			
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
	
	/*Approve Featured Request*/
	public function service_finder_approvemail(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	$qid = isset($_POST['qid']) ? esc_html($_POST['qid']) : '';
	$provider_id = isset($_POST['pid']) ? esc_html($_POST['pid']) : '';
	
	$customerinfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quotations.' WHERE id = %d',$qid));
	
	if(!empty($customerinfo)){
	
	$getProvider = new SERVICE_FINDER_searchProviders();
	$providerInfo = $getProvider->service_finder_getProviderInfo(esc_attr($provider_id));
	
	if($service_finder_options['quote-to-provider-subject'] != ""){
		$msg_subject = $service_finder_options['quote-to-provider-subject'];
	}else{
		$msg_subject = esc_html__('Request a Quotation', 'service-finder');
	}
	
	if(!empty($service_finder_options['quote-to-provider'])){
		$message = $service_finder_options['quote-to-provider'];
	}else{
		$message = 'Requesting for Quotation

		Customer Name: %CUSTOMERNAME%
		
		Email: %EMAIL%
		
		Phone: %PHONE%
		
		Description: %DESCRIPTION%';
	}
	
	$tokens = array('%PROVIDERNAME%','%PROVIDEREMAIL%','%CUSTOMERNAME%','%EMAIL%','%PHONE%','%DESCRIPTION%');
	$replacements = array(service_finder_get_providername_with_link($provider_id),'<a href="mailto:'.$providerInfo->email.'">'.$providerInfo->email.'</a>',$customerinfo->name,$customerinfo->email,$customerinfo->phone,$customerinfo->message);
	$msg_body = str_replace($tokens,$replacements,$message);
	
	$relatedproviders = service_finder_quote_related_providers($qid);
		
	$provideremails[] = $providerInfo->email;
	
	if(function_exists('service_finder_add_notices')) {
		$noticedata = array(
				'provider_id' => $provider_id,
				'target_id' => $qid, 
				'topic' => esc_html__('Get Quotation', 'service-finder'),
				'notice' => sprintf(esc_html__('New quotation has arrived from %s', 'service-finder'),$customerinfo->name)
				);
		service_finder_add_notices($noticedata);
	
	}
			
	if(!empty($relatedproviders)){
		foreach($relatedproviders as $relatedprovider){
			$provideremails[] = service_finder_getProviderEmail($relatedprovider->related_provider_id);
			
			if(function_exists('service_finder_add_notices')) {
				$noticedata = array(
						'provider_id' => $relatedprovider,
						'target_id' => $quoteid, 
						'topic' => esc_html__('Get Quotation', 'service-finder'),
						'notice' => sprintf(esc_html__('New quotation has arrived from %s', 'service-finder'),$customerinfo->name)
						);
				service_finder_add_notices($noticedata);
			
			}
		}
	}
	
	if(!empty($provideremails)){
		$provideremails = implode(',',$provideremails);
	}else{
		$provideremails = $providerInfo->email;
	}

	if(service_finder_wpmailer($provideremails,$msg_subject,$msg_body)) {
	
		$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->quotations.' SET `status` = "approve" WHERE `id` = %d',$qid));
		
		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('Approved and send mail Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}else{
		$error = array(
				'status' => 'error',
				'err_message' => esc_html__('Couldn&#8217;t approved', 'service-finder')
				);
		echo json_encode($error);
	}
	
	}
	
	exit(0);		
	}
	
	/*Delete Quote*/
	public function service_finder_delete_quote(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->quotations." WHERE id = %d",$id);
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->quoteto_related_providers." WHERE quote_id = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	exit(0);
	}
	
}