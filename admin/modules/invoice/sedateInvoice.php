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
class SERVICE_FINDER_sedateInvoice extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','invoice',$this->service_finder_getAllProvidersList() );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_admin_invoice',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_admin_invoice' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_delete_admin_invoice',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_delete_admin_invoice' ) );
                    }
						
                );		
		add_action(
                    'wp_ajax_approve_wired_invoice',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approve_wired_invoice' ) );
                    }
						
                );		
		add_action(
                    'wp_ajax_invoice_pay_via_adaptive',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_invoice_pay_via_adaptive' ) );
                    }
						
                );		
		add_action(
                    'wp_ajax_status_invoice_pay_to_provider',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_status_invoice_pay_to_provider' ) );
                    }
						
                );
				
    }
	
	/*Change provider payment status from pending to paid*/
	public function service_finder_status_invoice_pay_to_provider(){
		global $wpdb, $service_finder_options, $service_finder_Tables;
		$receiver          = array();
		
		$invoiceid = (!empty($_POST['invoiceid'])) ? esc_html($_POST['invoiceid']) : '';
		
		$data = array(
				'paid_to_provider' => 'paid',
				);
		
		$where = array(
				'id' => $invoiceid,
				);
		
		$invoice_id = $wpdb->update($service_finder_Tables->invoice,wp_unslash($data),$where);
				
		if(is_wp_error($invoice_id)){
			$error = array(
					'status' => 'error',
					'err_message' => $invoice_id->get_error_message()
					);
			echo json_encode($error);
		}else{
			
			if(function_exists('service_finder_add_notices')) {
				$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->invoice.' WHERE `id` = %d',$invoiceid));	
				$noticedata = array(
						'provider_id' => $row->provider_id,
						'target_id' => $row->id, 
						'topic' => esc_html__('Booking Invoice Payment', 'service-finder'),
						'notice' => esc_html__('Site administrator paid you for your service via bank transfer', 'service-finder')
						);
				service_finder_add_notices($noticedata);
			
			}
			
			$success = array(
					'status' => 'success',
					'suc_message' => esc_html__('Payment status changed successfully.', 'service-finder'),
					);
			echo json_encode($success);
		}
		 
		exit(0);
	}
	
	/*Pay to Provider via adaptive paypal*/
	public function service_finder_invoice_pay_via_adaptive(){
		global $service_finder_options;
		$receiver          = array();
		
		$invoiceid = (!empty($_POST['invoiceid'])) ? esc_html($_POST['invoiceid']) : '';
		$providerid = (!empty($_POST['providerid'])) ? esc_html($_POST['providerid']) : '';
		$amount = (!empty($_POST['amount'])) ? esc_html($_POST['amount']) : '';
		
		$paypal_email_id = get_user_meta($providerid,'paypal_email_id',true);
		
		$receiver[] = array(
			  'Amount'           => $amount, 					  // Required.  Amount to be paid to the receiver.
			  'Email'            => $paypal_email_id,			  // Receiver's email address. 127 char max.
			  'InvoiceID'        => '', 						  // The invoice number for the payment.  127 char max.
			  'AccountID' => '',       
			  'Phone'            => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''),   // Receiver's phone number.   Numbers only.
			  'Primary'          => ''							  // Whether this receiver is the primary receiver.  Values are boolean:  TRUE, FALSE
	   );
	   
	   $paypalCreds['USER'] = (isset($service_finder_options['paypal-username'])) ? $service_finder_options['paypal-username'] : '';
	   $paypalCreds['PWD'] = (isset($service_finder_options['paypal-password'])) ? $service_finder_options['paypal-password'] : '';
	   $paypalCreds['SIGNATURE'] = (isset($service_finder_options['paypal-signatue'])) ? $service_finder_options['paypal-signatue'] : '';
	   $sandbox = (isset($service_finder_options['paypal-type']) && $service_finder_options['paypal-type'] == 'sandbox') ? true : false;
	   
	   $return_page = add_query_arg( array('page' => 'invoices'), admin_url('admin.php') );
	   $ipn_page = SERVICE_FINDER_BOOKING_LIB_URL.'/paypal_ipn.php?invoiceid='.$invoiceid;
	   $appid = (isset($service_finder_options['paypal-app-id'])) ? $service_finder_options['paypal-app-id'] : '';
				
	   $paypal_result = execute_payment( $sandbox, $paypalCreds['USER'], $paypalCreds['PWD'], $paypalCreds['SIGNATURE'], service_finder_currencycode(), 'SENDER', $receiver, $return_page, $ipn_page, $appid);
				
		if( $paypal_result['Ack'] == 'Failure' )
		{
			$error = array(
					'status' => 'error',
					'err_message' => $paypal_result['Errors'][0]['Message']
					);
			echo json_encode($error);
		}else{
			$success = array(
					'status' => 'success',
					'PayKey' => $paypal_result['PayKey'],
					'sandbox' => $sandbox,
					);
			echo json_encode($success);
		}   
		 
		exit(0);
	}
	
	/*Display invoice into datatable*/
	public function service_finder_get_admin_invoice(){
		global $wpdb, $service_finder_Tables, $service_finder_options;
		$requestData= $_REQUEST;
		$currUser = wp_get_current_user(); 
		$columns = array( 
			0 =>'id', 
			1 =>'id', 
		);
		$bookingid = (isset($_POST['bookingid'])) ? esc_html($_POST['bookingid']) : '';
		//$user_id = (!empty($arg['user_id'])) ? $arg['user_id'] : '';
		
		// getting total number records without any search
		if($bookingid != ""){
		$sql = $wpdb->prepare("SELECT * FROM ".$service_finder_Tables->invoice." WHERE `booking_id` = %d",$bookingid);
		}else{
		$sql = "SELECT * FROM ".$service_finder_Tables->invoice;		
		}
		
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		if($bookingid != ""){
		$sql = "SELECT * FROM ".$service_finder_Tables->invoice." WHERE 1 = 1 AND `booking_id` = ".$bookingid;		
		}else{
		$sql = "SELECT * FROM ".$service_finder_Tables->invoice." WHERE 1 = 1";
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( `invoice_number` LIKE '".$requestData['search']['value']."%' )";    
		}
		
		if( !empty($requestData['columns'][3]['search']['value']) ){
			$sql.=" AND provider_id = ".$requestData['columns'][3]['search']['value'];
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
		$query=$wpdb->get_results($sql);
		$data = array();
		
		$payment_methods = (!empty($service_finder_options['payment-methods'])) ? $service_finder_options['payment-methods'] : '';
		
		foreach($query as $result){
			$nestedData=array(); 
		
			$nestedData[] = '
<div class="checkbox sf-radio-checkbox">
  <input type="checkbox" id="invoice-'.esc_attr($result->id).'" class="deleteInvoiceRow" value="'.esc_attr($result->id).'">
  <label for="invoice-'.esc_attr($result->id).'"></label>
</div>';

			if($result->charge_admin_fee_from == 'provider'){
				$invoiceamount = $result->grand_total - $result->adminfee;
			}elseif($result->charge_admin_fee_from == 'customer'){
				$invoiceamount = $result->grand_total;
			}else{
				$invoiceamount = $result->grand_total;
			}
			
			$q = $wpdb->get_row($wpdb->prepare('SELECT name FROM '.$service_finder_Tables->customers.' WHERE `email` = "%s" GROUP BY email',$result->customer_email));
			$nestedData[] = $result->reference_no;
			$nestedData[] = service_finder_getProviderFullName($result->provider_id);
			$nestedData[] = $q->name;
			$nestedData[] = $result->duedate;
			$nestedData[] = service_finder_money_format($result->grand_total);
			$nestedData[] = service_finder_money_format($result->adminfee);
			$nestedData[] = service_finder_money_format($invoiceamount);
			
			$now = time();
			$date = $result->duedate;
			
			if($result->status == 'pending' && strtotime($date) < $now){
				$status = esc_html__('Overdue', 'service-finder');
			}else{
				$status = service_finder_translate_static_status_string($result->status);
			}
			
			$payment_type = $result->payment_mode;
			$payment_method = $result->payment_type;
			$order_id = $result->txnid;
			
			$nestedData[] = $status;
			$nestedData[] = ($payment_type == 'woocommerce') ? esc_html__('Woocommerce','service-finder') : esc_html__('Local','service-finder');
			$nestedData[] = service_finder_translate_static_status_string($payment_method);
			$nestedData[] = $result->txnid;
			
			if($payment_type == 'woocommerce' && ($payment_method == 'bacs' || $payment_method == 'cheque')){
			$nestedData[] = $result->txnid;
			}elseif($payment_type == 'woocommerce' && $payment_method != 'bacs' && $payment_method != 'cheque'){
			$nestedData[] = '-';
			}elseif(($payment_type == 'local' || $payment_type == "") && $payment_method == 'wire-transfer'){
			$nestedData[] = $result->txnid;
			}else{
			$nestedData[] = '-';
			}
			
			if($result->booking_id > 0){
			$nestedData[] = '<a href="javascript:;" data-toggle="modal" data-target="#invoice-booking-modal" data-bookingid="'.esc_attr($result->booking_id).'" class="viewbookingdeatils">#'.$result->booking_id.'</a>';
			}else{
			$nestedData[] = '-';
			}
			
			if($result->status != 'paid'){
			$reminder = '
<button type="button" class="btn btn-primary btn-xs sendReminder" data-id="'.esc_attr($result->id).'" title="'.esc_html__('Send Reminder', 'service-finder').'"><i class="fa fa-envelope"></i></button>
';
			
			$editbtn = '
<button type="button" data-id="'.esc_attr($result->id).'" class="btn btn-warning btn-xs editInvoice margin-r-5" title="'.esc_html__('Edit Invoice', 'service-finder').'"><i class="fa fa-pencil"></i></button>
';
			}else{
			$reminder = '';
			$editbtn = '';
			}
			
			if($result->status == 'on-hold' && $payment_type != 'woocommerce'){
				$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($result->id).'" class="approve_wiredinvoice">'.esc_html__('Approve', 'service-finder').'</a>';
			}elseif($result->status == 'on-hold' && $payment_type == 'woocommerce' && $result->txnid != ""){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$result->txnid.'&action=edit" target="_blank">'.esc_html__('Approve', 'service-finder').'</a>';
			}else{
				$nestedData[] = service_finder_translate_static_status_string($result->status);
			}
			
			$paytoproviderstatus = '';
			if($result->paid_to_provider == 'pending'){
				$paytoproviderstatus = '<button type="button" data-invoiceid="'.esc_attr($result->id).'" class="btn btn-primary statusinvoicepaytoprovider" title="'.esc_html__('Change Payment Status to Paid', 'service-finder').'">'.esc_html__('Change Status', 'service-finder').'</button>';
			}elseif($result->paid_to_provider == 'paid'){
				$paytoproviderstatus = esc_html__('Paid', 'service-finder');
			}else{
				$paytoproviderstatus = '-';
			}
			
			$nestedData[] = $paytoproviderstatus;
			
			$paynow = '';
			if($payment_methods['paypal-adaptive']){
				if($result->paid_to_provider == 'pending' && $invoiceamount > 0){
					$paynow = '<button type="button" data-invoiceid="'.esc_attr($result->id).'" data-providerid="'.esc_attr($result->provider_id).'" data-amount="'.esc_attr($invoiceamount).'" class="btn btn-primary invoicepaytoprovider" title="'.esc_html__('Pay Now', 'service-finder').'">'.esc_html__('Pay Now', 'service-finder').'</button>';
				}elseif($result->paid_to_provider == 'paid'){
					$paynow = esc_html__('Paid', 'service-finder');
				}else{
					$paynow = '-';
				}
			}
			
			if($payment_methods['paypal-adaptive']){
			$nestedData[] = $paynow;
			}
			
			if($payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$order_id.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
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
		
		echo json_encode($json_data);  // send data as json format
		exit(0);
	}
	
	/*Delete Invoice*/
	public function service_finder_delete_admin_invoice(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->invoice." WHERE id = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	}
	
	public function service_finder_approve_wired_invoice(){
	global $wpdb, $service_finder_Tables;
	
	$invoiceid = (isset($_POST['invoiceid'])) ? esc_html($_POST['invoiceid']) : '';
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->invoice.' WHERE `id` = %d',$invoiceid));

	if(!empty($row)){
	$provider_id = $row->provider_id;
	$customer_email = $row->customer_email;
	
	$data = array(
	'status' => 'paid',
	);
	
	$where = array(
	'id' => $invoiceid
	);
	
	$wpdb->update($service_finder_Tables->invoice,wp_unslash($data),$where);
	
	if(function_exists('service_finder_add_notices')) {

		$noticedata = array(
				'provider_id' => $provider_id,
				'target_id' => $invoiceid, 
				'topic' => esc_html__('Invoice Paid', 'service-finder'),
				'notice' => sprintf( esc_html__('Invoice paid by %s', 'service-finder'), $customer_email ),
				);
		service_finder_add_notices($noticedata);
	
	}
	
	service_finder_SendInvoicePaidMailToProvider($invoiceid);
	service_finder_SendInvoicePaidMailToCustomer($invoiceid);
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Invoice paid via wire transfer successful', 'service-finder'),
			);
	echo json_encode($success);
	
	}
	
	exit(0);
	}
	
}