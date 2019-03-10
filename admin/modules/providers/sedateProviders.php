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
 * Class SERVICE_FINDER_sedateProviders
 */
class SERVICE_FINDER_sedateProviders extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','providers' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Identity check functionality*/
	public function service_finder_identitycheck()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'identity-check','providers' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }

	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_providers',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_providers' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_delete_providers',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_delete_providers' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_free_featured',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_free_featured' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_make_unfeatured',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_make_unfeatured' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_block_user',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_block_user' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_get_bank_account_info',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_bank_account_info' ) );
                    }
						
                );			
		add_action(
                    'wp_ajax_unblock_user',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_unblock_user' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_approved_user',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approved_user' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_reject_user',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_reject_user' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_get_providers_identity',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_providers_identity' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_approve_provider_identity',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approve_provider_identity' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_unapprove_provider_identity',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_unapprove_provider_identity' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_make_it_vendors',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_make_it_vendors' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_addtowallet',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_addtowallet' ) );
                    }
						
                );																	
											
				
    }
	
	/*Add to wallet*/
	public function service_finder_addtowallet(){
	global $service_finder_Tables, $wpdb;
	
	$user_id = (!empty($_POST['user_id'])) ? $_POST['user_id'] : '';
	$amount = (!empty($_POST['amount'])) ? esc_attr($_POST['amount']) : 0;
	
	service_finder_add_wallet_amount($user_id,$amount);
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Add balance to wallet successfully', 'service-finder'),
			);
	echo json_encode($success);
		
	exit(0);
	
	}
	
	/*Make all providers to vendors also*/
	public function service_finder_make_it_vendors(){
	global $service_finder_Tables, $wpdb;
		
	$providers = $wpdb->get_results('SELECT * FROM '.$service_finder_Tables->providers.' Where id > 0');
	
	if(!empty($providers)){
		foreach($providers as $provider){
			$user_id = $provider->wp_user_id;
			
			service_finder_meke_user_vendor($user_id);
	
		}
		
		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('All providers make vendor Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}else{
		$error = array(
				'status' => 'error',
				'err_message' => esc_html__('No providers found', 'service-finder'),
				);
		echo json_encode($error);
	}
	
	exit(0);
	
	}
	
	/*Display providers into datatable*/
	public function service_finder_get_providers(){
		global $wpdb,$service_finder_Tables,$service_finder_options;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT provider.id, provider.wp_user_id, provider.status as membershipstatus, provider.admin_moderation, provider.account_blocked, provider.full_name, provider.mobile, provider.phone, provider.category_id, provider.email, provider.city, featured.amount, featured.days, featured.status,featured.feature_status FROM '.$service_finder_Tables->feature.' as featured RIGHT JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id');
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'full_name', 
			1 =>'full_name', 
			2=> 'phone',
			3 => 'email',
			4 => 'city',
			5 => 'category_id',
			6 => 'id'
		);
		
		// getting total number records without any search
		$sql = 'SELECT provider.id, provider.full_name, provider.admin_moderation, provider.status as membershipstatus, provider.account_blocked, provider.mobile, provider.phone, provider.email, provider.category_id, provider.city, provider.wp_user_id, featured.amount, featured.days, featured.status,featured.feature_status FROM '.$service_finder_Tables->feature.' as featured RIGHT JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id';
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT provider.id, provider.full_name, provider.phone, provider.status as membershipstatus, provider.admin_moderation, provider.account_blocked, provider.mobile, provider.email, provider.city, provider.category_id, provider.wp_user_id, featured.amount, featured.days, featured.status,featured.feature_status FROM '.$service_finder_Tables->feature.' as featured RIGHT JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( provider.full_name LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR provider.phone LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR provider.email LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR provider.city LIKE '".$requestData['search']['value']."%' )";
		}
		
		if( !empty($requestData['columns'][3]['search']['value']) ){
			$sql.=" AND `provider`.`featured` = ".$requestData['columns'][3]['search']['value'];
		}
		
		if( !empty($requestData['columns'][4]['search']['value']) ){
			if( $requestData['columns'][4]['search']['value'] == 'need-approval'){
			$sql.=" AND ((`provider`.`admin_moderation` = 'approved' AND `provider`.`account_blocked` = 'yes') OR `provider`.`admin_moderation` = 'pending' OR `provider`.`admin_moderation` = 'rejected')";
			}
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
			
			$plandata = get_user_meta($result->wp_user_id,'provider_activation_time',true);
			$role = get_user_meta($result->wp_user_id,'provider_role',true);
			if($role != ""){
			$roleNum = intval(substr($role, 8));
			}else{
			$roleNum = '';
			}
			$packagename = (!empty($service_finder_options['package'.$roleNum.'-name'])) ? $service_finder_options['package'.$roleNum.'-name'] : esc_html__('No Package','service-finder');
			
			$nestedData[] = "
<input type='checkbox' class='deleteProvidersRow' value='".esc_attr($result->wp_user_id)."'  />
";
			$mobile = (!empty($result->mobile)) ? $result->mobile : '';
			$phone = (!empty($result->phone)) ? $result->phone : '';
			
			$contactnumber = service_finder_get_contact_info($phone,$mobile);
			
			if($result->membershipstatus == 'draft'){
			$membershipstatus = esc_html__('Cancelled', 'service-finder');
			}else{
			$membershipstatus = esc_html__('Active', 'service-finder');
			}
			
			$nestedData[] = $result->full_name;
			$nestedData[] = $contactnumber;
			$nestedData[] = $result->email;
			$nestedData[] = $membershipstatus;
			$nestedData[] = esc_html($packagename);
			$nestedData[] = (!empty($plandata['time'])) ? esc_html(date('Y-m-d',$plandata['time'])) : '';
			$nestedData[] = $result->city;
			$nestedData[] = service_finder_getCategoryName(get_user_meta($result->wp_user_id,'primary_category',true));
			if($result->status == 'Paid' && $result->feature_status == 'active'){
			$status = esc_html__('Featured (Paid)', 'service-finder');
			}elseif($result->status == 'Free' && $result->feature_status == 'active'){
			$status = '
<input type="checkbox" checked="checked" name="makefeatured" value="'.esc_attr($result->wp_user_id).'" id="makefeatured-'.esc_attr($result->wp_user_id).'">
'.esc_html__('Featured (By Admin)', 'service-finder');
			}else{
			$status = '<input type="checkbox" name="makefeatured" value="'.esc_attr($result->wp_user_id).'" id="makefeatured-'.esc_attr($result->wp_user_id).'">';
			}
			$nestedData[] = $status;
			if(service_finder_check_wallet_system()){
			$walletamount = service_finder_get_wallet_amount($result->wp_user_id);
			$wallethtml = service_finder_money_format($walletamount);
			$wallethtml .= ' <a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="addtowallet btn btn-primary btn-xs">'.esc_html__('Add Balance to Wallet', 'service-finder').'</a>';
			$nestedData[] = $wallethtml;
			}
			$nestedData[] = '<a href="'.esc_url(service_finder_get_author_url($result->wp_user_id)).'" class="btn btn-primary btn-xs" target="_blank">'.esc_html__('View Profile', 'service-finder').'</a>';
			$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="viewbankinfo btn btn-primary btn-xs">'.esc_html__('View Bank Info', 'service-finder').'</a>';
			
			$payment_type = get_user_meta($result->wp_user_id,'payment_type',true);
			$payment_method = get_user_meta($result->wp_user_id,'payment_mode',true);
			$order_id = get_user_meta($result->wp_user_id,'order_id',true);
			
			$nestedData[] = ($payment_type == 'woocommerce') ? esc_html__('Woocommerce','service-finder') : esc_html__('Local','service-finder');
			$nestedData[] = service_finder_translate_static_status_string($payment_method);
			
			if($payment_type == 'woocommerce' && ($payment_method == 'bacs' || $payment_method == 'cheque')){
			$nestedData[] = get_user_meta($result->wp_user_id,'order_id',true);
			}elseif($payment_type == 'woocommerce' && $payment_method != 'bacs' && $payment_method != 'cheque'){
			$nestedData[] = '-';
			}elseif(($payment_type == 'local' || $payment_type == "") && $payment_method == 'wired'){
			$nestedData[] = get_user_meta($result->wp_user_id,'wired_invoiceid',true);
			}else{
			$nestedData[] = '-';
			}
			
			$currentPayType = get_user_meta($result->wp_user_id,'pay_type',true);
			if($currentPayType == 'single'){
			
			if($payment_type == 'woocommerce'){
			$nestedData[] = get_user_meta($result->wp_user_id,'order_id',true);
			}else{
			$nestedData[] = get_user_meta($result->wp_user_id,'txn_id',true);
			}
			
			}elseif($currentPayType == 'recurring'){
			
				$subscription_id = get_user_meta($result->wp_user_id,'subscription_id',true);
				$profileid = get_user_meta($result->wp_user_id,'recurring_profile_id',true);
	
				if($subscription_id != ""){
					$nestedData[] = $subscription_id;
				}elseif(!empty($profileid)){
					$nestedData[] = $profileid;
				}else{
					$nestedData[] = '-';
				}
			
			}else{
				$nestedData[] = '-';
			}
			
			$manageprofilelink = add_query_arg( array('manageaccountby' => 'admin','manageproviderid' => esc_attr($result->wp_user_id)), service_finder_get_url_by_shortcode('[service_finder_my_account') );
			
			if($result->admin_moderation == 'approved'){
				if($result->account_blocked == 'yes'){
				$actionsbtns = '<a href="'.esc_url($manageprofilelink).'" target="_blank" class="btn btn-primary btn-xs">'.esc_html__('Manage Profile', 'service-finder').'</a> <a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="unblockaccount btn btn-primary btn-xs">'.esc_html__('UnBlock', 'service-finder').'</a>';
				
				if($payment_method == 'wired'){
				$actionsbtns .= '<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="unblockaccount btn btn-primary btn-xs">'.esc_html__('Approve After Wired Transfer', 'service-finder').'</a>';				
				}
				
				$nestedData[] = $actionsbtns;
				
				}else{
				$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="blockaccount btn btn-primary btn-xs">'.esc_html__('Block', 'service-finder').'</a> <a href="'.esc_url($manageprofilelink).'" target="_blank" class="btn btn-primary btn-xs">'.esc_html__('Manage Profile', 'service-finder').'</a>';
				}
			}elseif($result->admin_moderation == 'pending'){
				$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="approveprovider btn btn-success btn-xs">'.esc_html__('Approve', 'service-finder').'</a>&nbsp;&nbsp;&nbsp;<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="rejectprovider btn btn-danger btn-xs">'.esc_html__('Reject', 'service-finder').'</a> <a href="'.esc_url($manageprofilelink).'" target="_blank" class="btn btn-primary btn-xs">'.esc_html__('Manage Profile', 'service-finder').'</a>';
			}elseif($result->admin_moderation == 'rejected'){
				$nestedData[] = '<span class="reject">Rejected</span><a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="approveprovider">'.esc_html__('Re-Approve', 'service-finder').'</a> <a href="'.esc_url($manageprofilelink).'" target="_blank" class="btn btn-primary btn-xs">'.esc_html__('Manage Profile', 'service-finder').'</a>';
			}
			
			if($payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$order_id.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
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
	
	/*Display providers identity into datatable*/
	public function service_finder_get_providers_identity(){
		global $wpdb,$service_finder_Tables,$service_finder_options;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT provider.id, provider.identity, provider.wp_user_id, provider.admin_moderation, provider.account_blocked, provider.full_name, provider.phone, provider.category_id, provider.email, provider.city, featured.amount, featured.days, featured.status FROM '.$service_finder_Tables->feature.' as featured RIGHT JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id');
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'full_name', 
			1=> 'phone',
			2 => 'email',
		);
		
		// getting total number records without any search
		$sql = 'SELECT provider.id, provider.full_name, provider.identity, provider.admin_moderation, provider.account_blocked, provider.phone, provider.email, provider.category_id, provider.city, provider.wp_user_id, featured.amount, featured.days, featured.status FROM '.$service_finder_Tables->feature.' as featured RIGHT JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id';
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT provider.id, provider.full_name, provider.identity, provider.phone, provider.admin_moderation, provider.account_blocked, provider.email, provider.city, provider.category_id, provider.wp_user_id, featured.amount, featured.days, featured.status FROM '.$service_finder_Tables->feature.' as featured RIGHT JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( provider.full_name LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR provider.phone LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR provider.email LIKE '".$requestData['search']['value']."%' )";
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
			
			$plandata = get_user_meta($result->wp_user_id,'provider_activation_time',true);
			$role = (!empty($plandata['role'])) ? esc_html($plandata['role']) : '';
			$roleNum = intval(substr($role, 8));
			$packagename = (!empty($service_finder_options['package'.$roleNum.'-name'])) ? $service_finder_options['package'.$roleNum.'-name'] : '';
			
			$nestedData[] = $result->full_name;
			$nestedData[] = $result->phone;
			$nestedData[] = $result->email;
			
			$attachmentIDs = service_finder_get_identity($result->wp_user_id);
			
			$identityfile = '';
			if(!empty($attachmentIDs)){
				foreach($attachmentIDs as $attachmentID){
				$identityfile .= '<a href="'.SERVICE_FINDER_BOOKING_LIB_URL.'/downloads.php?file='.wp_get_attachment_url( $attachmentID->attachmentid ).'"><i class="fa fa-download"></i> '.esc_html__('View/Download').'</a><br/>';
				}
			}else{
				$identityfile = esc_html__('No identity available', 'service-finder');
			}
			
			$nestedData[] = $identityfile;
			
			if($result->identity == 'approved'){
			$nestedData[] = ucfirst(esc_attr($result->identity));
			$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="unapproveidentity">'.esc_html__('Un-Approve', 'service-finder').'</a>';
			}else{
			$nestedData[] = esc_html__('Un-Approved','service-finder');
			$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->wp_user_id).'" class="approveidentity">'.esc_html__('Approve', 'service-finder').'</a>';
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
	
	/*Delete Providers*/
	public function service_finder_delete_providers(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					wp_delete_user( $id );
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->providers." WHERE wp_user_id = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	exit(0);		
	}
	
	/*Make Featured by Admin*/
	public function service_finder_free_featured(){
	global $wpdb, $service_finder_Tables;
	
	$proid = (isset($_POST['proid'])) ? esc_html($_POST['proid']) : '';
	$days = (isset($_POST['days'])) ? esc_html($_POST['days']) : '';
	
	$wpdb->query($wpdb->prepare('DELETE FROM '.$service_finder_Tables->feature.' WHERE `provider_id` = %d',$proid));
	
	$date = date('Y-m-d H:i:s');
	$data = array(
			'provider_id' => $proid,
			'days' => $days,
			'status' => 'Free',
			'feature_status' => 'active',
			'date' => $date,
			);

	$wpdb->insert($service_finder_Tables->feature,wp_unslash($data));
	
	$feature_id = $wpdb->insert_id;
	
	$data = array(
			'featured' => 1,
			);
	
	$where = array(
			'wp_user_id' => $proid,
			);
	$wpdb->update($service_finder_Tables->providers,wp_unslash($data),$where);

	if ( ! $feature_id ) {
		$errmsg = 'Provider Couldn&#8217;t make featured... Please try again';
		$error = array(
				'status' => 'error',
				'err_message' => sprintf( esc_html__('%s', 'service-finder'), $errmsg )
				);
		echo json_encode($error);
	}else{
		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('Provider has been Featured Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}
	
	exit(0);		
	}
	
	/*Block User by Admin*/
	public function service_finder_block_user(){
	global $wpdb, $service_finder_Tables;
	
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->providers.' SET `account_blocked` = "yes" WHERE `wp_user_id` = %d',$_POST['uid']));
	
	$email = service_finder_getProviderEmail($_POST['uid']);
	$message = esc_html__('Dear Provider,', 'service-finder');
	$message .= esc_html__('Your account has been blocked by following reason:', 'service-finder');
	$message .= '%COMMENT%';
	
	$tokens = array('%COMMENT%');
	$replacements = array($_POST['comment']);
	$msg_body = str_replace($tokens,$replacements,$message);
	$msg_subject = esc_html__('Account Blocked', 'service-finder');
	if(service_finder_wpmailer($email,$msg_subject,$msg_body)) {

		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('User has been Blocked Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}else{
		$success = array(
				'status' => 'error',
				'err_message' => esc_html__('Message could not be sent.', 'service-finder'),
				);
		echo json_encode($success);
	}
	
	exit(0);		
	}
	
	/*Get Bank account info*/
	public function service_finder_get_bank_account_info(){
	
	$userId = (!empty($_POST['uid'])) ? esc_html($_POST['uid']) : '';
	
	$bank_account_holder_name = get_user_meta($userId,'bank_account_holder_name',true);
	$bank_account_number = get_user_meta($userId,'bank_account_number',true);
	$swift_code = get_user_meta($userId,'swift_code',true);
	$bank_name = get_user_meta($userId,'bank_name',true);
	$bank_branch_city = get_user_meta($userId,'bank_branch_city',true);
	$bank_branch_country = get_user_meta($userId,'bank_branch_country',true);
	
	if($bank_account_holder_name == "" && $bank_account_holder_name == "" && $bank_account_holder_name == "" && $bank_account_holder_name == "" && $bank_account_holder_name == "" && $bank_account_holder_name == ""){
		$flag = 0;
	}else{
		$flag = 1;
	}
	
	$success = array(
				'status' => 'success',
				'flag' => $flag,
				'bank_account_holder_name' => esc_html($bank_account_holder_name),
				'bank_account_number' => esc_html($bank_account_number),
				'swift_code' => esc_html($swift_code),
				'bank_name' => esc_html($bank_name),
				'bank_branch_city' => esc_html($bank_branch_city),
				'bank_branch_country' => esc_html($bank_branch_country),
				);
	echo json_encode($success);

	exit(0);		
	}
	
	/*Un-Block User by Admin*/
	public function service_finder_unblock_user(){
	global $wpdb, $service_finder_Tables;
	
	$data = array(
			'account_blocked' => 'no',
			'status' => 'active',
			);
	
	$where = array(
			'wp_user_id' => $_POST['uid'],
			);
	$wpdb->update($service_finder_Tables->providers,wp_unslash($data),$where);
	
	
	$email = service_finder_getProviderEmail($_POST['uid']);
	$message = esc_html__('Dear Provider,', 'service-finder');
	$message .= esc_html__('Your account has been UnBlocked successfully.', 'service-finder');
	
	$msg_body = $message;
	$msg_subject =  esc_html__('Account UnBlocked', 'service-finder');
	
	service_finder_wpmailer($email,$msg_subject,$msg_body);

	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('User has been UnBlocked Successfully', 'service-finder'),
			);
	echo json_encode($success);

	exit(0);		
	}
	
	/*Approved User by Admin*/
	public function service_finder_approved_user(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	$providerreplacestring = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');	
	
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->providers.' SET `admin_moderation` = "approved" WHERE `wp_user_id` = %d',$_POST['uid']));
	
	$email = service_finder_getProviderEmail($_POST['uid']);
	
	if(!empty($service_finder_options['send-to-provider-account-approval'])){
		$message = $service_finder_options['send-to-provider-account-approval'];
	}else{
		$message = 'Dear '.esc_html($providerreplacestring).',
		Congratulations! Your account has been approved.';
	}
	
	$tokens = array('%PROVIDERNAME%');
	$replacements = array(service_finder_getProviderName($_POST['uid']));
	$msg_body = str_replace($tokens,$replacements,$message);
	
	if(!empty($service_finder_options['provider-account-approval-subject'])){
		$msg_subject = $service_finder_options['provider-account-approval-subject'];
	}else{
		$msg_subject = 'User account approved';
	}
	
	service_finder_wpmailer($email,$msg_subject,$msg_body);
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('User has been Approved Successfully', 'service-finder'),
			);
	echo json_encode($success);
	
	exit(0);		
	}
	
	/*Approved provider identity*/
	public function service_finder_approve_provider_identity(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	
	$providerid = (isset($_POST['providerid'])) ? esc_attr($_POST['providerid']) : '';
	
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->providers.' SET `identity` = "approved" WHERE `wp_user_id` = %d',$providerid));
	
	$email = service_finder_getProviderEmail($providerid);
	
	$messagetmp = (!empty($service_finder_options['identity-approve-mail'])) ? $service_finder_options['identity-approve-mail'] : '';
	if($messagetmp != ""){
	$message = $messagetmp;
	}else{
	$message = 'Dear '.esc_html($providerreplacestring).',
	Congratulations! Your identity has been approved.';
	}
	
	
	$msg_body = $message;
	
	if($service_finder_options['identity-approve-mail-subject'] != ""){
		$msg_subject = $service_finder_options['identity-approve-mail-subject'];
	}else{
		$msg_subject = esc_html__('Identity check approved', 'service-finder');
	}
	
	service_finder_wpmailer($email,$msg_subject,$msg_body);
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Provider Identity Approved Successfully', 'service-finder'),
			);
	echo json_encode($success);
	
	exit(0);		
	}
	
	/*UnApproved provider identity*/
	public function service_finder_unapprove_provider_identity(){
	global $wpdb, $service_finder_Tables, $service_finder_options;
	
	$providerid = (isset($_POST['providerid'])) ? esc_attr($_POST['providerid']) : '';
	
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->providers.' SET `identity` = "unapproved" WHERE `wp_user_id` = %d',$providerid));
	$wpdb->query($wpdb->prepare('DELETE FROM `'.$service_finder_Tables->attachments.'` WHERE `type` = "identity" AND `wp_user_id` = %d',$providerid));
	
	$email = service_finder_getProviderEmail($providerid);
	$messagetmp = (!empty($service_finder_options['identity-unapprove-mail'])) ? $service_finder_options['identity-unapprove-mail'] : '';
	if($messagetmp != ""){
	$message = $messagetmp;
	}else{
	$message = 'Dear '.esc_html($providerreplacestring).',
	Your identity has been unapproved.';
	}
	
	$msg_body = $message;
	$msg_subject = 'Identity check unapproved';
	
	if($service_finder_options['identity-unapprove-mail-subject'] != ""){
		$msg_subject = $service_finder_options['identity-unapprove-mail-subject'];
	}else{
		$msg_subject = esc_html__('Identity check unapproved', 'service-finder');
	}
	
	service_finder_wpmailer($email,$msg_subject,$msg_body);
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Provider Identity Un-Approved Successfully', 'service-finder'),
			);
	echo json_encode($success);
	
	exit(0);		
	}
	
	/*Approved User by Admin*/
	public function service_finder_reject_user(){
	global $wpdb, $service_finder_Tables;
	
	$wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->providers.' SET `admin_moderation` = "rejected" WHERE `wp_user_id` = %d',$_POST['uid']));
	
	$email = service_finder_getProviderEmail($_POST['uid']);
	$message = 'Dear Provider,
	Your account has been rejected by following reason: %COMMENT%';
	
	$tokens = array('%COMMENT%');
	$replacements = array($_POST['comment']);
	$msg_body = str_replace($tokens,$replacements,$message);
	$msg_subject = 'User account rejected';
	
	service_finder_wpmailer($email,$msg_subject,$msg_body);

	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('User has been Rejected Successfully', 'service-finder'),
			);
	echo json_encode($success);	
	
	exit(0);		
	}
	
	/*Make Un Featured by Admin*/
	public function service_finder_make_unfeatured(){
	global $wpdb, $service_finder_Tables;
	
	$proid = (isset($_POST['proid'])) ? esc_html($_POST['proid']) : '';
	
	$res = $wpdb->query($wpdb->prepare('DELETE FROM '.$service_finder_Tables->feature.' WHERE `provider_id` = %d',$proid));
	
	$data = array(
			'featured' => 0,
			);
	
	$where = array(
			'wp_user_id' => $proid,
			);
	$wpdb->update($service_finder_Tables->providers,wp_unslash($data),$where);

	if ( ! $res ) {
		$errmsg = 'Provider Couldn&#8217;t make unfeatured... Please try again';
		$error = array(
				'status' => 'error',
				'err_message' => sprintf( esc_html__('%s', 'service-finder'), $errmsg )
				);
		echo json_encode($error);
	}else{
		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('Provider has been UnFeatured Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}
	
	exit(0);		
	}
	
}