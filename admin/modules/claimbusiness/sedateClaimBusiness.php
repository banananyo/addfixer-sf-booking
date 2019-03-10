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
 * Class SERVICE_FINDER_sedateClaimBusiness
 */
class SERVICE_FINDER_sedateClaimBusiness extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','claimbusiness' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_claimbusiness',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_claimbusiness' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_delete_claimbusiness',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_delete_claimbusiness' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_approveclaim',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approveclaim' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_declineclaim',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_declineclaim' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_approve_claimbusiness_request',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approve_claimbusiness_request' ) );
                    }
						
                );			
		
    }
	
	/*Approve cliam business after wired transfer*/
	public function service_finder_approve_claimbusiness_request(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	
	$userId = (isset($_POST['uid'])) ? esc_html($_POST['uid']) : '';
	
	$payment_method = 'wired';
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->claim_business.' WHERE `provider_id` = %d',$userId));

	$requestdata = get_user_meta($userId,'claimed_request',true);
			
	$user = new WP_User( $userId );
	$user->set_role('Provider');
	
	update_user_meta( $userId, 'provider_activation_time', array( 'role' => $requestdata['role'], 'time' => time()) );
	
	update_user_meta($userId, 'expire_limit', $requestdata['expire_limit']);
	update_user_meta( $userId, 'provider_role', $requestdata['role'] );
	update_user_meta($userId, 'profile_amt',$requestdata['rolePrice']);
	update_user_meta( $userId, 'pay_type', 'single' );
	$roleNum = $requestdata['roleNum'];
	$roleName = $service_finder_options['package'.$roleNum.'-name'];
	update_user_meta( $userId, 'payment_mode', $payment_method );
	update_user_meta( $userId, 'payment_type', 'local' );
	
	$paymode = 'local';
	$userInfo = service_finder_getUserInfo($userId);
	
	$userdata = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->users.' WHERE `ID` = %d',$userId));
	
	$username = $userdata->user_login;
	$useremail = $userdata->user_email;
	
	$data = array(
	'payment_type' => 'local',
	'payment_method' => $payment_method,
	'payment_status' => 'paid',
	);
	
	$where = array(
	'id' => $requestdata['claimedbusinessid']
	);
	
	$wpdb->update($service_finder_Tables->claim_business,wp_unslash($data),$where);
	
	update_user_meta($userId, 'claimed_request_status','completed');
	
	$args = array(
			'username' => (!empty($userdata->user_login)) ? $userdata->user_login : '',
			'email' => (!empty($userdata->user_email)) ? $userdata->user_email : '',
			'package_name' => $roleName,
			'payment_type' => $paymode,
			'payment_mode' => $payment_method
			);
	service_finder_update_job_limit($userId);
	
	service_finder_after_claimedpayment_user($userId,$requestdata['claimedbusinessid']);
	service_finder_after_claimedpayment_admin($args,$requestdata['claimedbusinessid']);
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Claimed business successfully', 'service-finder'),
			);
	echo json_encode($success);
	
	exit(0);
	}
	
	/*Display claim business into datatable*/
	public function service_finder_get_claimbusiness(){
		global $wpdb, $service_finder_Tables;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT * FROM '.$service_finder_Tables->claim_business);
		
		$columns = array( 
			0 =>'provider_id', 
			1=> 'fullname',
			3 => 'date',
			4 => 'email',
			5 => 'id',
			6 => 'status',
		);
		
		// getting total number records without any search
		$sql = 'SELECT * FROM '.$service_finder_Tables->claim_business;
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT * FROM '.$service_finder_Tables->claim_business.' WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( provider_id LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR fullname LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR date LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR email LIKE '".$requestData['search']['value']."%' ";
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
			
			$wiredrequestdata = get_user_meta($result->provider_id,'claimed_request',true);
			$claimed_request_status = get_user_meta($result->provider_id,'claimed_request_status',true);
			
			$nestedData[] = "<input type='checkbox' class='deleteClaimRow' value='".esc_attr($result->id)."' />";
			$nestedData[] = '<a href="'.esc_url($userLink).'" target="_blank">'.service_finder_getProviderFullName($result->provider_id).'</a>';
			$nestedData[] = $result->fullname;
			$nestedData[] = $result->date;
			$nestedData[] = $result->email;
			$nestedData[] = $result->message;
				
			if($result->payment_type == 'woocommerce' && ($result->payment_method == 'bacs' || $result->payment_method == 'cheque')){
			$nestedData[] = $result->txn_id;
			}elseif($result->payment_type == 'woocommerce' && $result->payment_method != 'bacs' && $result->payment_method != 'cheque'){
			$nestedData[] = '-';
			}elseif($result->payment_type == 'local' && $result->payment_method == 'wired'){
			$nestedData[] = ($wiredrequestdata['wired_invoiceid'] != "") ? $wiredrequestdata['wired_invoiceid'] : '';
			}else{
			$nestedData[] = '-';
			}
			
			if($result->payment_status == 'on-hold' && $result->payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$result>txn_id.'&action=edit" target="_blank">'.esc_html__('Approve', 'service-finder').'</a>';
			}elseif($claimed_request_status == 'pending' && $result->payment_type != 'woocommerce'){
				$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->provider_id).'" class="app_claimbusiness_request">'.esc_html__('Approve', 'service-finder').'</a>';
			}else{
				if($result->payment_type == 'woocommerce'){
					$nestedData[] = service_finder_translate_static_status_string($result->payment_status);
				}else{
					//$nestedData[] = esc_html__( 'Paid', 'service-finder' );
					$nestedData[] = service_finder_translate_static_status_string($result->payment_status);
				}
			}
			
			$nestedData[] = ($result->payment_type == 'woocommerce') ? esc_html__('Woocommerce','service-finder') : esc_html__('Local','service-finder');
			$nestedData[] = service_finder_translate_static_status_string($result->payment_method);
			$nestedData[] = '<a href="'.esc_url(service_finder_get_author_url($result->provider_id)).'" class="btn btn-primary btn-xs" target="_blank">'.esc_html__('View Profile', 'service-finder').'</a>';
			
			
			$status = '';
			if($result->status == "pending"){
				$status .= '<a href="javascript:;" class="btn btn-success btn-xs" data-id="'.esc_attr($result->id).'" data-providerid="'.esc_attr($result->provider_id).'" id="approveclaim">'.esc_html__('Approve', 'service-finder').'</a>';
				$status .= '<a href="javascript:;" class="btn btn-danger btn-xs" data-id="'.esc_attr($result->id).'" data-providerid="'.esc_attr($result->provider_id).'" id="declineclaim">'.esc_html__('Decline', 'service-finder').'</a>';
			}elseif($result->status == "approved"){
				$status = esc_html__('Approved', 'service-finder');
			}elseif($result->status == "declined"){
				$status = esc_html__('Declined', 'service-finder');
			}elseif($result->status == "claimed"){
				$status = esc_html__('Claimed', 'service-finder');
			}
			
			$nestedData[] = $status;
			
			if($result->payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$result->txn_id.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
			}else{
				$nestedData[] = '-';
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
	
	/*Delete Claim Business*/
	public function service_finder_delete_claimbusiness(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->claim_business." WHERE id = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	exit(0);
	}
	
	/*Approve Claim Request*/
	public function service_finder_approveclaim(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	$cid = isset($_POST['cid']) ? esc_html($_POST['cid']) : '';
	$provider_id = isset($_POST['pid']) ? esc_html($_POST['pid']) : '';
	
	$claimbusinessstr = (!empty($service_finder_options['string-claim-business'])) ? $service_finder_options['string-claim-business'] : esc_html__('Claim Business', 'service-finder');	
	
	$claimbusinessoption = (!empty($service_finder_options['claim-business-option'])) ? esc_html($service_finder_options['claim-business-option']) : 'free';
	
	$claiminfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->claim_business.' WHERE id = %d',$cid));
	
	if($claimbusinessoption == 'free'){
	if($service_finder_options['approve-claim-free-subject'] != ""){
		$subject = $service_finder_options['approve-claim-free-subject'];
	}else{
		$subject = esc_html__('Your Claimed Business has been Approved', 'service-finder');
	}
	
	if(!empty($service_finder_options['approve-claim-free'])){
		$message = $service_finder_options['approve-claim-free'];
	}else{
		$message = 'Congratulations! Your claimed business has been approved. Please use following credentials for login.

		Username: %USERNAME%
		
		Password: %PASSWORD%';
		
	}
	
	$userinfo = get_userdata($provider_id);
	$username = $userinfo->user_login;
	$password = wp_generate_password( 8, false );
	wp_set_password( $password, $provider_id );
	
	wp_update_user( array( 'ID' => $provider_id, 'user_email' => $claiminfo->email ) );
	
	$cdata = array(
		'email' => $claiminfo->email,
	);
	$cwhere = array(
		'wp_user_id' => $provider_id
	);
						
	$wpdb->update($service_finder_Tables->providers,wp_unslash($cdata),$cwhere);
	
	$tokens = array('%USERNAME%','%PASSWORD%');
	$replacements = array($username,$password);
	$msg_body = str_replace($tokens,$replacements,$message);
	
	$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->claim_business.' SET `status` = "claimed" WHERE `id` = %d',$cid));
	update_user_meta($provider_id,'claimed','yes');
		
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->claim_business.' SET `status` = "declined" WHERE `provider_id` = %d AND `id` != %d',$provider_id,$cid));
	
	if(service_finder_wpmailer($claiminfo->email,$subject,$msg_body)) {
		
		$success = array(
				'status' => 'success',
				'suc_message' => sprintf(esc_html__('%s approved successfully and mail sent with login credentials to user', 'service-finder'),$claimbusinessstr)
				);
		echo json_encode($success);
	}else{
		$error = array(
				'status' => 'error',
				'err_message' => esc_html__('Couldn&#8217;t approved', 'service-finder')
				);
		echo json_encode($error);
	}
	
	}elseif($claimbusinessoption == 'paid'){
	
	if($service_finder_options['approve-claim-paid-subject'] != ""){
		$subject = $service_finder_options['approve-claim-paid-subject'];
	}else{
		$subject = esc_html__('Your Claimed Business has been Approved', 'service-finder');
	}
	
	if(!empty($service_finder_options['approve-claim-paid'])){
		$message = $service_finder_options['approve-claim-paid'];
	}else{
		$message = 'Congratulations! Your claimed business has been approved. Please check your mail to pay for claimed business

		Provider Name: %PROVIDERNAME%
		
		Provider Profile: %PROVIDERPROFILELINK%';
		
	}
	
	$profilepayLink = add_query_arg( array('claimedbusinessid' => service_finder_encrypt($cid, 'Developer#@)!%'),'profileid' => service_finder_encrypt($provider_id, 'Developer#@)!%')), service_finder_get_url_by_shortcode('[service_finder_claimbusiness_payment]') );
	
	if($profilepayLink != ""){
	$message .= '<br/><br/>
				<a href="'.esc_url($profilepayLink).'">'.esc_html__('Pay Now','service-finder').'</a>';
	}
	
	$profilelink = service_finder_get_author_url($provider_id);
	$tokens = array('%PROVIDERNAME%','%PROVIDERPROFILELINK%');
	$replacements = array(service_finder_get_providername_with_link($provider_id),'<a href="'.$profilelink.'" target="_blank">'.$profilelink.'</a>');
	$msg_body = str_replace($tokens,$replacements,$message);
	
	$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->claim_business.' SET `status` = "approved" WHERE `id` = %d',$cid));
	update_user_meta($provider_id,'claimed','yes');
		
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->claim_business.' SET `status` = "declined" WHERE `provider_id` = %d AND `id` != %d',$provider_id,$cid));
	
	if(service_finder_wpmailer($claiminfo->email,$subject,$msg_body)) {
		
		$success = array(
				'status' => 'success',
				'suc_message' => sprintf(esc_html__('%s approved successfully and send mail for pay to user', 'service-finder'),$claimbusinessstr)
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
	
	/*Decline Claim Request*/
	public function service_finder_declineclaim(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	$cid = isset($_POST['cid']) ? esc_html($_POST['cid']) : '';
	$provider_id = isset($_POST['pid']) ? esc_html($_POST['pid']) : '';
	
	$claimbusinessstr = (!empty($service_finder_options['string-claim-business'])) ? $service_finder_options['string-claim-business'] : esc_html__('Claim Business', 'service-finder');	
	
	$claiminfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->claim_business.' WHERE id = %d',$cid));
	
	$getProvider = new SERVICE_FINDER_searchProviders();
	$providerInfo = $getProvider->service_finder_getProviderInfo(esc_attr($provider_id));
	
	if($service_finder_options['decline-claim-subject'] != ""){
		$subject = $service_finder_options['decline-claim-subject'];
	}else{
		$subject = esc_html__('Your Claimed Business has been Declined', 'service-finder');
	}
	
	if(!empty($service_finder_options['decline-claim'])){
		$message = $service_finder_options['decline-claim'];
	}else{
		$message = 'Your following claimed business has been declined.

		Provider Name: %PROVIDERNAME%
		
		Provider Email: %PROVIDEREMAIL%
		
		Provider Profile: %PROVIDERPROFILELINK%';
		
	}
	
	$tokens = array('%PROVIDERNAME%','%PROVIDEREMAIL%','%PROVIDERPROFILELINK%');
	$profilelink = service_finder_get_author_url($provider_id);
	$replacements = array(service_finder_get_providername_with_link($provider_id),'<a href="mailto:'.$providerInfo->email.'">'.$providerInfo->email.'</a>','<a href="'.$profilelink.'" target="_blank">'.$profilelink.'</a>');
	$msg_body = str_replace($tokens,$replacements,$message);
	
	$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->claim_business.' SET `status` = "declined" WHERE `id` = %d',$cid));
	
	if(service_finder_wpmailer($claiminfo->email,$subject,$msg_body)) {
		
		$success = array(
				'status' => 'success',
				'suc_message' => sprintf(esc_html__('%s declined successfully.', 'service-finder'),$claimbusinessstr)
				);
		echo json_encode($success);
	}else{
		$error = array(
				'status' => 'error',
				'err_message' => esc_html__('Couldn&#8217;t declined', 'service-finder')
				);
		echo json_encode($error);
	}
	
	exit(0);		
	}
	
}