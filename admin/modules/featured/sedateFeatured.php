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
class SERVICE_FINDER_sedateFeatured extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','featured' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_featured',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_featured' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_featured_approve',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_featured_approve' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_featured_decline',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_featured_decline' ) );
                    }
						
                );	
		add_action(
                    'wp_ajax_featured_edit_price',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_featured_edit_price' ) );
                    }
						
                );			
									
				
    }
	
	/*Display featured providers into datatable*/
	public function service_finder_get_featured(){
		global $wpdb, $service_finder_Tables;
		$requestData= $_REQUEST;

		$providers = $wpdb->get_results('SELECT featured.id, featured.paymenttype, featured.payment_mode, featured.txnid, featured.date, featured.feature_status, provider.full_name, provider.category_id, featured.provider_id, featured.amount, featured.days, featured.status, featured.paypal_transaction_id FROM '.$service_finder_Tables->feature.' as featured INNER JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id');
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'full_name', 
			1=> 'category_id',
			2 => 'days',
			3 => 'id',
		);
		
		// getting total number records without any search
		$sql = 'SELECT featured.id, featured.date, featured.paymenttype, featured.feature_status, featured.payment_mode, featured.txnid, provider.full_name, provider.category_id, featured.provider_id, featured.amount, featured.days, featured.status, featured.paypal_transaction_id FROM '.$service_finder_Tables->feature.' as featured INNER JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id';
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT featured.id, featured.date, featured.paymenttype, featured.feature_status, featured.payment_mode, featured.txnid, provider.full_name, provider.category_id, featured.provider_id, featured.amount, featured.days, featured.status, featured.paypal_transaction_id FROM '.$service_finder_Tables->feature.' as featured INNER JOIN '.$service_finder_Tables->providers.' as provider on featured.provider_id = provider.wp_user_id WHERE 1=1';

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( full_name LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR days LIKE '".$requestData['search']['value']."%' )";
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
		
			$nestedData[] = $result->full_name;
			$nestedData[] = service_finder_getCategoryName(get_user_meta($result->provider_id,'primary_category',true));
			$nestedData[] = $result->days;
			if($result->feature_status == 'active'){
			$nestedData[] = date('Y-m-d',strtotime($result->date));
			$nestedData[] = date('Y-m-d',strtotime($result->date .'+'.$result->days.' day'));
			}else{
			$nestedData[] = '-';
			$nestedData[] = '-';
			}
			
			if($result->status == "Payment Pending"){
			$editprice = ' <a href="javascript:;" class="btn btn-success btn-xs editfeaturedprice" data-id="'.esc_attr($result->id).'" data-amount="'.esc_attr($result->amount).'">'.esc_html__('Edit Price', 'service-finder').'</a>';
			}else{
			$editprice = '';
			}
			if($result->amount > 0){
			$nestedData[] = service_finder_money_format($result->amount).' '.$editprice;
			}else{
			$nestedData[] = '-';
			}
			$nestedData[] = '<a href="'.esc_url(service_finder_get_author_url($result->provider_id)).'" target="_blank">'.esc_html__('View Profile', 'service-finder').'</a>';
			
			if($result->status == "Declined"){
			$nestedData[] = 'Declined';
			}else{
			$nestedData[] = ucfirst($result->feature_status);
			}
			
			$payment_type = $result->payment_mode;
			$payment_method = $result->paymenttype;
			$order_id = $requestdata['wired_invoiceid'];
			
			$nestedData[] = ($payment_type == 'woocommerce') ? esc_html__('Woocommerce','service-finder') : esc_html__('Local','service-finder');
			$nestedData[] = service_finder_translate_static_status_string($payment_method);
			
			if($payment_type == 'woocommerce' && ($payment_method == 'bacs' || $payment_method == 'cheque')){
			$nestedData[] = $result->txnid;
			}elseif($payment_type == 'woocommerce' && $payment_method != 'bacs' && $payment_method != 'cheque'){
			$nestedData[] = '-';
			}elseif(($payment_type == 'local' || $payment_type == "") && $payment_method == 'wire-transfer'){
			$nestedData[] = $result->txnid;
			}else{
			$nestedData[] = '-';
			}
			
			if($payment_type == 'woocommerce'){
			$nestedData[] = $result->txnid;
			}else{
			$nestedData[] = $result->paypal_transaction_id;
			}
			
			if($result->amount > 0 || $result->status == "Declined" || $result->status == "Free"){
				$lastcoloum = $result->status;
				if($result->status == "on-hold" && $result->paymenttype == "woocommerce"){
				$lastcoloum .= '<a href="'.admin_url().'post.php?post='.$result->txnid.'&action=edit" target="_blank">'.esc_html__('Approve', 'service-finder').'</a>';
				}elseif($result->paymenttype == "wire-transfer" && $result->status == "on-hold"){
				$lastcoloum .= '<a href="javascript:;" class="btn btn-success btn-xs" data-id="'.esc_attr($result->id).'" id="approve-wired">'.esc_html__('Approve After Wire Transfer', 'service-finder').'</a>';
				}
			}else{
				$lastcoloum = '<a href="javascript:;" class="btn btn-success btn-xs" data-id="'.esc_attr($result->id).'" id="approve-bx">'.esc_html__('Approve', 'service-finder').'</a> <a id="decline-bx" class="btn btn-danger btn-xs" data-id="'.esc_attr($result->id).'" href="javascript:;">'.esc_html__('Decline', 'service-finder').'</a>';
			}
			
			$nestedData[] = $lastcoloum;
			
			if($payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$result->txnid.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
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
	
	/*Approve Featured Request*/
	public function service_finder_featured_approve(){
	global $wpdb, $service_finder_Tables, $service_finder_options;

	$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->feature.' SET `status` = "Payment Pending", `amount` = %f WHERE `id` = %d',$_POST['featured_amount'],$_POST['fid']));

	$getfeature = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->feature.' WHERE `id` = %d',$_POST['fid']));
	
	$email = service_finder_getProviderEmail($getfeature->provider_id);
	
	if(!empty($service_finder_options['send-to-provider-featured-request-approval'])){
		$message = $service_finder_options['send-to-provider-featured-request-approval'];
	}else{
		$message = 'Dear '.esc_html($providerreplacestring).',
		Your account has been approved for feature. Please make payment to activate';
	}
	
	$msg_body = $message;
	if(!empty($service_finder_options['provider-featured-request-approval-subject'])){
		$msg_subject = $service_finder_options['provider-featured-request-approval-subject'];
	}else{
		$msg_subject = 'Approved Feature Request';
	}
	
	if(function_exists('service_finder_add_notices')) {
		
		$noticedata = array(
				'provider_id' => $getfeature->provider_id,
				'target_id' => $fid, 
				'topic' => esc_html__('Feature Request Approved', 'service-finder'),
				'notice' => esc_html__('Your feature request have been approved.', 'service-finder')
				);
		service_finder_add_notices($noticedata);
	
	}
	
	if(service_finder_wpmailer($email,$msg_subject,$msg_body)) {
		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('Approved Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}else{
		$adminemail = get_option( 'admin_email' );
		$allowedhtml = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
		);
		$error = array(
				'status' => 'error',
				'err_message' => sprintf( wp_kses(esc_html__('Couldn&#8217;t approved... please contact the <a href="mailto:%s">Administrator</a> !', 'service-finder'),$allowedhtml), $adminemail )
				);
		echo json_encode($error);
	}
	
	exit(0);		
	}
	
	/*Update Featured Request*/
	public function service_finder_featured_edit_price(){
	global $wpdb, $service_finder_Tables;
	
	$fid = (isset($_POST['fid'])) ? esc_html($_POST['fid']) : '';
	$featured_amount = (isset($_POST['featured_amount'])) ? esc_html($_POST['featured_amount']) : '';

	$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->feature.' SET `status` = "Payment Pending", `amount` = %f WHERE `id` = %d',$_POST['featured_amount'],$_POST['fid']));
	
	$data = array(
			'amount' => esc_attr($featured_amount)
			);
	$where = array(
			'id' => esc_attr($fid)
			);		

	$wpdb->update($service_finder_Tables->feature,wp_unslash($data),$where);

	$getfeature = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->feature.' WHERE `id` = %d',$fid));
	
	if(function_exists('service_finder_add_notices')) {
		
		$noticedata = array(
				'provider_id' => $getfeature->provider_id,
				'target_id' => $fid, 
				'topic' => esc_html__('Featured Amount Edited', 'service-finder'),
				'notice' => esc_html__('Featured Amount has been updated', 'service-finder')
				);
		service_finder_add_notices($noticedata);
	
	}
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Featured Amount has been updated', 'service-finder'),
			);
	echo json_encode($success);
	
	exit(0);		
	}
	
	/*Decline Featured Request*/
	public function service_finder_featured_decline(){
	global $wpdb, $service_finder_Tables;

	$res = $wpdb->query($wpdb->prepare('UPDATE '.$service_finder_Tables->feature.' SET `status` = "Declined", `comments` = "%s" WHERE `id` = %d',$_POST['comment'],$_POST['fid']));
	
	$getfeature = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->feature.' WHERE `id` = %d',$_POST['fid']));
	
	$email = service_finder_getProviderEmail($getfeature->provider_id);
	$message = esc_html__('Dear Provider,', 'service-finder');
	$message .= esc_html__('Your account has been declined for feature.', 'service-finder');
	
	$message .= '<br>'.$_POST['comment'];
	
	$msg_body = $message;
	$msg_subject = esc_html__('Declined Feature Request', 'service-finder');
	if(service_finder_wpmailer($email,$msg_subject,$msg_body)) {
		$success = array(
				'status' => 'success',
				'suc_message' => esc_html__('Declined Successfully', 'service-finder'),
				);
		echo json_encode($success);
	}else{
		$adminemail = get_option( 'admin_email' );
		$allowedhtml = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
		);
		$error = array(
				'status' => 'error',
				'err_message' => sprintf( wp_kses(esc_html__('Couldn&#8217;t declined... please contact the <a href="mailto:%s">Administrator</a> !', 'service-finder'),$allowedhtml), $adminemail )
				);		
		echo json_encode($error);
	}

	exit(0);		
	}
	
}