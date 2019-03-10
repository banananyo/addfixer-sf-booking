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
 * Class SERVICE_FINDER_sedateBookingManager
 */
class SERVICE_FINDER_sedateBookingManager extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		$this->service_finder_render( 'index','bookings', $this->service_finder_getAllProvidersList() );
		
		$this->service_finder_registerWpActions();
		
    }
	
	/*Display bookings into datatable*/
	public function service_finder_get_admin_bookings(){
		global $wpdb, $service_finder_Tables, $service_finder_options;
		$requestData= $_REQUEST;
		$paymentstatus = '';

		$members = $wpdb->get_results('SELECT bookings.id, bookings.multi_date, bookings.jobid, bookings.provider_id, bookings.payment_type,bookings.order_id, bookings.charge_admin_fee_from, bookings.paid_to_provider, bookings.total, bookings.adminfee, bookings.stripe_token, bookings.payment_to, bookings.type, bookings.paypal_paykey, bookings.wired_invoiceid, bookings.date, bookings.start_time, bookings.end_time, bookings.status, bookings.txnid, providers.full_name as proname, providers.wp_user_id, providers.phone as prophone, providers.mobile as promobile, providers.email as proemail, customers.name as cusname, customers.phone as cusphone, customers.email as cusemail, customers.address as cusaddress, customers.city as cuscity FROM '.$service_finder_Tables->bookings.' as bookings INNER JOIN '.$service_finder_Tables->customers.' as customers INNER JOIN '.$service_finder_Tables->providers.' as providers on bookings.booking_customer_id = customers.id AND bookings.provider_id = providers.wp_user_id');
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'date', 
			1=> 'start_time',
			2 => 'end_time',
			3 =>'proname', 
			4=> 'prophone',
			5 => 'proemail',
			6 =>'cusname', 
			7=> 'cusphone',
			8 => 'cusemail',
			9 =>'cusaddress', 
			10=> 'cuscity',
			11=> 'status',
			12=> 'id'
		);
		
		// getting total number records without any search
		$sql = "SELECT bookings.id, bookings.jobid, bookings.multi_date, bookings.total, bookings.provider_id, bookings.payment_type, bookings.order_id, bookings.charge_admin_fee_from, bookings.paid_to_provider, bookings.adminfee, bookings.payment_to, bookings.stripe_token, bookings.type, bookings.paypal_paykey, bookings.wired_invoiceid, bookings.date, bookings.start_time, bookings.end_time, bookings.status, bookings.txnid,  providers.full_name as proname, providers.wp_user_id, providers.phone as prophone, providers.mobile as promobile, providers.email as proemail, customers.name as cusname, customers.phone as cusphone, customers.email as cusemail, customers.address as cusaddress, customers.city as cuscity";
		$sql.=" FROM ".$service_finder_Tables->bookings." as bookings INNER JOIN ".$service_finder_Tables->customers." as customers INNER JOIN ".$service_finder_Tables->providers." as providers on bookings.booking_customer_id = customers.id AND bookings.provider_id = providers.wp_user_id";
		$query=$wpdb->get_results($sql);
		$totalData = count($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = "SELECT bookings.id, bookings.jobid, bookings.multi_date, bookings.total, bookings.provider_id, bookings.payment_type, bookings.order_id, bookings.charge_admin_fee_from, bookings.paid_to_provider, bookings.adminfee, bookings.payment_to, bookings.stripe_token, bookings.type, bookings.paypal_paykey, bookings.wired_invoiceid, bookings.date, bookings.start_time, bookings.end_time, bookings.status, bookings.txnid, providers.full_name as proname, providers.wp_user_id, providers.phone as prophone, providers.mobile as promobile, providers.email as proemail, customers.name as cusname, customers.phone as cusphone, customers.email as cusemail, customers.address as cusaddress, customers.city as cuscity";
		$sql.=" FROM ".$service_finder_Tables->bookings." as bookings INNER JOIN ".$service_finder_Tables->customers." as customers INNER JOIN ".$service_finder_Tables->providers." as providers on bookings.booking_customer_id = customers.id AND bookings.provider_id = providers.wp_user_id";
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql.=" AND ( customers.name LIKE '".$requestData['search']['value']."%' ";    
			$sql.=" OR bookings.id LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR bookings.date LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR bookings.start_time LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR bookings.end_time LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR providers.full_name LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR providers.phone LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR providers.email LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customers.name LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customers.phone LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customers.email LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customers.address LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR customers.city LIKE '".$requestData['search']['value']."%' ";
			$sql.=" OR bookings.status LIKE '".$requestData['search']['value']."%' )";
		}
		
		if( !empty($requestData['columns'][1]['search']['value']) && empty($requestData['columns'][3]['search']['value']) ) {
			
		
			switch ($requestData['columns'][1]['search']['value']) {
				case 'today':
					$start_date = date('Y-m-d');
					$end_date = date('Y-m-d');
					break;
				case 'yesterday':
					$start_date = date('Y-m-d',strtotime('-1 days'));
					$end_date = date('Y-m-d',strtotime('-1 days'));
					break;
				case 'tomorrow':
					$start_date = date('Y-m-d',strtotime('1 days'));
					$end_date = date('Y-m-d',strtotime('1 days'));
					break;
				case 'last_7':
					$start_date = date('Y-m-d',strtotime('-7 days'));
					$end_date = date('Y-m-d');
					break;
				case 'last_30':
					$start_date = date('Y-m-d',strtotime('-30 days'));
					$end_date = date('Y-m-d');
					break;
				case 'next_7':
					$start_date = date('Y-m-d');
					$end_date = date('Y-m-d',strtotime('7 days'));
					break;
				case 'this_month':
					$start_date = date('Y-m-01',strtotime('this month'));
					$end_date = date('Y-m-t',strtotime('this month'));
					break;
				case 'next_month':
					$start_date = date('Y-m-01',strtotime('next month'));
					$end_date = date('Y-m-t',strtotime('next month'));
					break;
			}
		
		$sql.=" WHERE bookings.date BETWEEN '{$start_date}' AND '{$end_date}'";
		
		}elseif( !empty($requestData['columns'][3]['search']['value']) && empty($requestData['columns'][1]['search']['value']) ){
			$sql.=" WHERE bookings.provider_id = ".$requestData['columns'][3]['search']['value'];
		}elseif( !empty($requestData['columns'][3]['search']['value']) && !empty($requestData['columns'][1]['search']['value']) ){
			
		
			switch ($requestData['columns'][1]['search']['value']) {
				case 'today':
					$start_date = date('Y-m-d');
					$end_date = date('Y-m-d');
					break;
				case 'yesterday':
					$start_date = date('Y-m-d',strtotime('-1 days'));
					$end_date = date('Y-m-d',strtotime('-1 days'));
					break;
				case 'tomorrow':
					$start_date = date('Y-m-d',strtotime('1 days'));
					$end_date = date('Y-m-d',strtotime('1 days'));
					break;
				case 'last_7':
					$start_date = date('Y-m-d',strtotime('-7 days'));
					$end_date = date('Y-m-d');
					break;
				case 'last_30':
					$start_date = date('Y-m-d',strtotime('-30 days'));
					$end_date = date('Y-m-d');
					break;
				case 'next_7':
					$start_date = date('Y-m-d');
					$end_date = date('Y-m-d',strtotime('7 days'));
					break;
				case 'this_month':
					$start_date = date('Y-m-01',strtotime('this month'));
					$end_date = date('Y-m-t',strtotime('this month'));
					break;
				case 'next_month':
					$start_date = date('Y-m-01',strtotime('next month'));
					$end_date = date('Y-m-t',strtotime('next month'));
					break;
			}
		
			$sql.=" WHERE bookings.date BETWEEN '{$start_date}' AND '{$end_date}' AND bookings.provider_id = ".$requestData['columns'][3]['search']['value'];
		
		}
		
		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
			
			if((strtotime($result->date) >= strtotime(date("Y-m-d")) && $result->multi_date != "yes") || ($result->status == "Pending" && $result->multi_date == "yes")){
				$status2 = esc_html__('Upcoming','service-finder');
				
			}else{
				$status2 = esc_html__('Past','service-finder');
				
			}
			
			if($result->status == 'Cancel'){
				$status = service_finder_translate_static_status_string($result->status);
			}elseif($result->status == 'Pending'){
				$status = esc_html__('Incomplete','service-finder');
			}else{
				$status = service_finder_translate_static_status_string($result->status);
			}
			
			if($result->jobid > 0){
				$type = esc_html__('Job','service-finder');
			}else{
				$type = esc_html__('Booking','service-finder');
			}
			
			if($result->type == 'wired' && $result->payment_to == 'admin'){
			if($result->status == 'Need-Approval'){
			$actions = '<button type="button" data-bookingid="'.esc_attr($result->id).'" class="btn btn-primary btn-xs adminapprovewiredbooking" title="'.esc_html__('Approve Booking', 'service-finder').'">'.esc_html__('Approve Booking', 'service-finder').'</button>';
			}else{
			$actions = esc_html__('Approved', 'service-finder');
			}

			}elseif(($result->type == 'bacs' || $result->type == 'cheque') && $result->payment_to == 'admin' && $result->payment_type == 'woocommerce'){
			if($result->status == 'Need-Approval'){
			$actions = '<a class="btn btn-primary btn-xs" href="'.admin_url().'post.php?post='.$result->order_id.'&action=edit" target="_blank">'.esc_html__('Approve Booking', 'service-finder').'</a>';
			}elseif($result->status == 'Pending'){
			$actions = esc_html__('Approved', 'service-finder');
			}elseif($result->status == 'Cancel'){
			$actions = esc_html__('Cancelled', 'service-finder');
			}elseif($result->status == 'Completed'){
			$actions = esc_html__('Completed', 'service-finder');
			}

			}else{
			$actions = '';
			}
			
			if($result->type == 'free'){
				$paymentstatus = esc_html__('Free', 'service-finder');
			}else{
				if($result->status == 'Pending'){
				$paymentstatus = esc_html__('Paid', 'service-finder');
				}elseif($result->status == 'Need-Approval'){
				$paymentstatus = esc_html__('Pending', 'service-finder');
				}else{
				$paymentstatus = esc_html__('Paid', 'service-finder');
				}
			}
			
			$nestedData[] = "
<input type='checkbox' class='deleteAdminBookingRow' value='".esc_attr($result->id)."'  />
";
			$promobile = (!empty($result->promobile)) ? $result->promobile : '';
			$prophone = (!empty($result->prophone)) ? $result->prophone : '';
			
			if($prophone != "" && $promobile != ""){
			$contactnumber = $prophone.','.$promobile;
			}elseif($prophone != ""){
			$contactnumber = $prophone;
			}elseif($promobile != ""){
			$contactnumber = $promobile;
			}else{
			$contactnumber = '';
			}
			
			if($result->type == "mangopay"){
				$bookingamount = $result->total - $result->adminfee;
			}else{
			if($result->charge_admin_fee_from == 'provider'){
				$bookingamount = $result->total - $result->adminfee;
			}elseif($result->charge_admin_fee_from == 'customer'){
				$bookingamount = $result->total;
			}else{
				$bookingamount = $result->total;
			}
			}
			
			$time_format = (!empty($service_finder_options['time-format'])) ? $service_finder_options['time-format'] : '';
			
			if($time_format){
				$starttime = $result->start_time;
				$endtime = $result->end_time;
			}else{
				$starttime = date('h:i a',strtotime($result->start_time));
				$endtime = date('h:i a',strtotime($result->end_time));
			}
			
			$payment_methods = (!empty($service_finder_options['payment-methods'])) ? $service_finder_options['payment-methods'] : '';
			$paynow = '';
			if($result->paid_to_provider == 'pending' && $result->paypal_paykey != "" && $payment_methods['paypal-adaptive']){
				$paynow = '<button type="button" data-paykey="'.esc_attr($result->paypal_paykey).'" data-providerid="'.esc_attr($result->wp_user_id).'" class="btn btn-primary paytoprovider" data-amount="'.esc_attr($bookingamount).'" title="'.esc_html__('Pay Now', 'service-finder').'">'.esc_html__('Pay Now', 'service-finder').'</button>';
			}elseif($result->paid_to_provider == 'pending' && $result->stripe_token != "" && $payment_methods['stripe']){
				$paynow = '<button type="button" data-bookingid="'.esc_attr($result->id).'" data-providerid="'.esc_attr($result->wp_user_id).'" data-amount="'.esc_attr($bookingamount).'" class="btn btn-primary paytoproviderviastripe" title="'.esc_html__('Pay Now', 'service-finder').'">'.esc_html__('Pay Now', 'service-finder').'</button>';
			}elseif($result->paid_to_provider == 'pending' && $result->type == "mangopay" && class_exists( 'WC_Vendors' ) && class_exists( 'WooCommerce' )){
				
				$paynow = '<button type="button" data-orderid="'.esc_attr($result->order_id).'" data-bookingid="'.esc_attr($result->id).'" data-providerid="'.esc_attr($result->wp_user_id).'" data-amount="'.esc_attr($bookingamount).'" class="btn btn-primary paytoproviderviamangopay" title="'.esc_html__('Pay Now', 'service-finder').'">'.esc_html__('Pay Now', 'service-finder').'</button>';
			
			}elseif($result->paid_to_provider != 'pending' && $result->stripe_token != "" && $payment_methods['stripe']){
				$paynow = $result->paid_to_provider;
			}elseif($result->paid_to_provider == 'paid'){
				$paynow = esc_html__('Paid', 'service-finder');
			}elseif($result->paid_to_provider == 'in-process'){
				$paynow = esc_html__('In-process', 'service-finder');
			}else{
				$paynow = $result->paid_to_provider;
			}
			
			$paytoproviderstatus = '';
			if($result->paid_to_provider == 'pending'){
				$paytoproviderstatus = '<button type="button" data-bookingid="'.esc_attr($result->id).'" class="btn btn-primary statuspaytoprovider" title="'.esc_html__('Change Payment Status to Paid', 'service-finder').'">'.esc_html__('Change Status', 'service-finder').'</button>';
			}elseif($result->paid_to_provider == 'paid'){
				$paytoproviderstatus = esc_html__('Paid', 'service-finder');
			}else{
				$paytoproviderstatus = '-';
			}

			$invoicequery = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->invoice.' WHERE `booking_id` = "%d"',$result->id));			
			$totalinvoice = count($invoicequery);
			
			$payment_type = $result->payment_type;
			$payment_method = $result->type;
			$order_id = $result->order_id;
			
			if($payment_type == 'woocommerce' && ($payment_method == 'bacs' || $payment_method == 'cheque') && $result->payment_to == 'admin'){
			$invoiceid = $order_id;
			}elseif($payment_type == 'woocommerce' && $payment_method != 'bacs' && $payment_method != 'cheque'){
			$invoiceid = '-';
			}elseif(($payment_type == 'local' || $payment_type == "") && $payment_method == 'wired' && $result->payment_to == 'admin'){
			$invoiceid = esc_html($result->wired_invoiceid);
			}else{
			$invoiceid = '-';
			}
			
			$nestedData[] = $result->id;
			$nestedData[] = ($result->multi_date != 'yes') ? $result->date : '-';
			$nestedData[] = ($result->multi_date != 'yes') ? $starttime : '-';;
			$nestedData[] = ($result->multi_date != 'yes') ? $endtime : '-';
			$nestedData[] = $result->proname;
			$nestedData[] = $contactnumber;
			$nestedData[] = service_finder_getProviderEmail($result->provider_id);
			$nestedData[] = $result->cusname;
			$nestedData[] = $result->cusphone;
			$nestedData[] = $result->cusemail;
			$nestedData[] = $result->cusaddress;
			$nestedData[] = $result->cuscity;
			$nestedData[] = $status2;
			$nestedData[] = $status;
			$nestedData[] = $type;
			$nestedData[] = $paytoproviderstatus;
			$nestedData[] = $result->txnid;
			
			$nestedData[] = ($payment_type == 'woocommerce') ? esc_html__('Woocommerce','service-finder') : esc_html__('Local','service-finder');
			$nestedData[] = service_finder_translate_static_status_string($payment_method);
			$nestedData[] = $invoiceid;
			$nestedData[] = $paymentstatus;
			$nestedData[] = service_finder_money_format($result->total);
			$nestedData[] = service_finder_money_format($result->adminfee);
			$nestedData[] = service_finder_money_format($bookingamount);
			if($totalinvoice > 0){
			$redirect_uri = add_query_arg( array('page' => 'invoices','bookingid' => $result->id), admin_url('admin.php') );
			$nestedData[] = '<a href="'.esc_url($redirect_uri).'">'.$totalinvoice.'</a>';
			}else{
			$nestedData[] = $totalinvoice;
			}
			$nestedData[] = $actions;

			$nestedData[] = $paynow;
			
			if($payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$order_id.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
			}else{
				$nestedData[] = '-';
			}
			
			if(strtotime($result->date) >= strtotime(date("Y-m-d"))){
				$upcoming = 'yes';
				
			}else{
				$upcoming = 'no';
				
			}
			
			$nestedData[] = '<button type="button" class="btn btn-custom btn-xs viewBookings" data-upcoming="'.esc_attr($upcoming).'" data-id="'.esc_attr($result->id).'" title="'.esc_html__('View Booking', 'service-finder').'"><i class="fa fa-eye"></i></button>';
			
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
	
	/*Delete Bookings*/
	public function service_finder_deleteAdminBookings(){
	global $wpdb, $service_finder_Tables;
			$data_ids = $_REQUEST['data_ids'];
			$data_id_array = explode(",", $data_ids); 
			if(!empty($data_id_array)) {
				foreach($data_id_array as $id) {
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->bookings." WHERE id = %d",$id);
					$query=$wpdb->query($sql);
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->booked_services." WHERE booking_id = %d",$id);
					$query=$wpdb->query($sql);
					$sql = $wpdb->prepare("DELETE FROM ".$service_finder_Tables->notifications." WHERE `topic` = 'Booking' AND `target_id` = %d",$id);
					$query=$wpdb->query($sql);
				}
			}
	exit(0);		
	}
	
	/*Approve wired booking*/
	public function service_finder_wired_booking_admin_approval(){
	global $wpdb, $service_finder_Tables;
	
	$bookingid = (!empty($_POST['bookingid'])) ? esc_html($_POST['bookingid']) : '';
	
		$data = array(
				'status' => 'Pending',
				);
		
		$where = array(
				'id' => $bookingid,
				);

		$booking_id = $wpdb->update($service_finder_Tables->bookings,wp_unslash($data),$where);		

		if(is_wp_error($booking_id)){
			$error = array(
					'status' => 'error',
					'err_message' => $service_id->get_error_message()
					);
			echo json_encode($error);
		}else{
			
			$bookingdata = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->bookings.' WHERE `id` = %d',$bookingid),ARRAY_A);
			if(function_exists('service_finder_add_notices')) {
			$res = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->customers.' WHERE `id` = %d',$bookingdata['booking_customer_id']),ARRAY_A);
			$users = $wpdb->prefix . 'users';
			$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$users.' WHERE `user_email` = "%s"',$res['email']));
			
			
			$noticedata = array(
						'customer_id' => $row->ID,
						'target_id' => $bookingid, 
						'topic' => esc_html__('Approve Booking', 'service-finder'),
						'notice' => esc_html__('Booking have been approved after wired bank transffer', 'service-finder')
						);
				service_finder_add_notices($noticedata);
			
			}
			
			$senMail = new SERVICE_FINDER_sedateBookingManager();
			$senMail->service_finder_SendApproveBookingMailToProvider($bookingdata,$bookingdata['adminfee']);
			$senMail->service_finder_SendApproveBookingMailToCustomer($bookingdata,$bookingdata['adminfee']);
			$senMail->service_finder_SendApproveBookingMailToAdmin($bookingdata,$bookingdata['adminfee']);
			
			$success = array(
					'status' => 'success',
					'suc_message' => esc_html__('Booking approved successfully.', 'service-finder'),
					);
			echo json_encode($success);
		}
	exit(0);	
	}
	
	/*Send Booking Approval mail to provider*/
	public function service_finder_SendApproveBookingMailToProvider($bookingdata,$adminfee = '0.0'){
		global $service_finder_options, $service_finder_Tables, $wpdb;
		
		$providerInfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->providers.' WHERE `wp_user_id` = %d',$bookingdata['provider_id']));
		$customerInfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->customers.' WHERE `id` = %d',$bookingdata['booking_customer_id']));
		
		$bookingpayment_mode = (!empty($maildata['type'])) ? $maildata['type'] : '';
		
		$payent_mode = ($bookingpayment_mode != '') ? $bookingpayment_mode : 'free';
		
		$pay_booking_amount_to = (!empty($service_finder_options['pay_booking_amount_to'])) ? $service_finder_options['pay_booking_amount_to'] : '';
		
		if(!empty($service_finder_options['booking-approval-to-provider'])){
			$message = $service_finder_options['booking-approval-to-provider'];
		}else{
			$message = '
<h4>Booking Approved</h4>
Date: %DATE%
				
				Time: %STARTTIME% - %ENDTIME%
				
				Member Name: %MEMBERNAME%
<h4>Provider Details</h4>
Provider Name: %PROVIDERNAME%

				Provider Email: %PROVIDEREMAIL%
				
				Phone: %PROVIDERPHONE%
<h4>Customer Details</h4>
Customer Name: %CUSTOMERNAME%

Customer Email: %CUSTOMEREMAIL%

Phone: %CUSTOMERPHONE%

Alternate Phone: %CUSTOMERPHONE2%

Address: %ADDRESS%

Apt/Suite: %APT%

City: %CITY%

State: %STATE%

Postal Code: %ZIPCODE%

Country: %COUNTRY%

Services: %SERVICES%

<h4>Payment Details</h4>
Pay Via: %PAYMENTMETHOD%
				
Amount: %AMOUNT%
				
Admin Fee: %ADMINFEE%';
}
			
			$tokens = array('%DATE%','%STARTTIME%','%ENDTIME%','%MEMBERNAME%','%PROVIDERNAME%','%PROVIDEREMAIL%','%PROVIDERPHONE%','%CUSTOMERNAME%','%CUSTOMEREMAIL%','%CUSTOMERPHONE%','%CUSTOMERPHONE2%','%ADDRESS%','%APT%','%CITY%','%STATE%','%ZIPCODE%','%COUNTRY%','%SERVICES%','%PAYMENTMETHOD%','%AMOUNT%','%ADMINFEE%');
			
			if($bookingdata['member_id'] > 0){
			$membername = service_finder_getMemberName($bookingdata['member_id']);
			}else{
			$membername = '-';
			}
			
			$services = service_finder_get_booking_services($bookingdata['id']);
			
			$charge_admin_fee = (!empty($service_finder_options['charge-admin-fee'])) ? $service_finder_options['charge-admin-fee'] : '';
			$charge_admin_fee_from = (!empty($service_finder_options['charge-admin-fee-from'])) ? $service_finder_options['charge-admin-fee-from'] : '';
			
			if($charge_admin_fee_from == 'provider' && $pay_booking_amount_to == 'admin' && $charge_admin_fee){
			$bookingamount = $bookingdata['total'] - $adminfee;
			}elseif($charge_admin_fee_from == 'customer' && $charge_admin_fee && $pay_booking_amount_to == 'admin'){
			$bookingamount = $bookingdata['total'];
			}else{
			$bookingamount = $bookingdata['total'];
			$adminfee = '0.0';
			}
			
			$replacements = array(date('Y-m-d',strtotime($bookingdata['date'])),$bookingdata['start_time'],$bookingdata['end_time'],$membername,service_finder_get_providername_with_link($providerInfo->wp_user_id),$providerInfo->email,service_finder_get_contact_info($providerInfo->phone,$providerInfo->mobile),$customerInfo->name,$customerInfo->email,$customerInfo->phone,$customerInfo->phone2,$customerInfo->address,$customerInfo->apt,$customerInfo->city,$customerInfo->state,$customerInfo->zipcode,$customerInfo->country,$services,ucfirst($payent_mode),service_finder_money_format($bookingamount),service_finder_money_format($adminfee));
			$msg_body = str_replace($tokens,$replacements,$message);
			
			if($service_finder_options['booking-approval-to-provider-subject'] != ""){
				$msg_subject = $service_finder_options['booking-approval-to-provider-subject'];
			}else{
				$msg_subject = esc_html__('Booking Approval Notification', 'service-finder');
			}
			
			if(service_finder_wpmailer($providerInfo->email,$msg_subject,$msg_body)) {

				$success = array(
						'status' => 'success',
						'suc_message' => esc_html__('Message has been sent', 'service-finder'),
						);
				$service_finder_Success = json_encode($success);
				return $service_finder_Success;
				
				
			} else {
					
				$error = array(
						'status' => 'error',
						'err_message' => esc_html__('Message could not be sent.', 'service-finder'),
						);
				$service_finder_Errors = json_encode($error);
				return $service_finder_Errors;
			}
		
	}
	
	/*Send Booking Approval mail to customer*/
	public function service_finder_SendApproveBookingMailToCustomer($bookingdata,$adminfee = '0.0'){
		global $service_finder_options, $service_finder_Tables, $wpdb;
		$providerInfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->providers.' WHERE `wp_user_id` = %d',$bookingdata['provider_id']));
		$customerInfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->customers.' WHERE `id` = %d',$bookingdata['booking_customer_id']));
		
		$bookingpayment_mode = (!empty($maildata['type'])) ? $maildata['type'] : '';
		
		$payent_mode = ($bookingpayment_mode != '') ? $bookingpayment_mode : 'free';
		
		$pay_booking_amount_to = (!empty($service_finder_options['pay_booking_amount_to'])) ? $service_finder_options['pay_booking_amount_to'] : '';
		
		if(!empty($service_finder_options['booking-approval-to-customer'])){
			$message = $service_finder_options['booking-approval-to-customer'];
		}else{
			$message = '
<h4>Booking Details</h4>
Date: %DATE%
				
				Time: %STARTTIME% - %ENDTIME%
				
				Member Name: %MEMBERNAME%
<h4>Provider Details</h4>
Provider Name: %PROVIDERNAME%

				Provider Email: %PROVIDEREMAIL%
				
				Phone: %PROVIDERPHONE%
<h4>Customer Details</h4>
Customer Name: %CUSTOMERNAME%

Customer Email: %CUSTOMEREMAIL%

Phone: %CUSTOMERPHONE%

Alternate Phone: %CUSTOMERPHONE2%

Address: %ADDRESS%

Apt/Suite: %APT%

City: %CITY%

State: %STATE%

Postal Code: %ZIPCODE%

Country: %COUNTRY%

Services: %SERVICES%

<h4>Payment Details</h4>
Pay Via: %PAYMENTMETHOD%
				
				Amount: %AMOUNT%
				
				Admin Fee: %ADMINFEE%';
		}
		
			$tokens = array('%DATE%','%STARTTIME%','%ENDTIME%','%MEMBERNAME%','%PROVIDERNAME%','%PROVIDEREMAIL%','%PROVIDERPHONE%','%CUSTOMERNAME%','%CUSTOMEREMAIL%','%CUSTOMERPHONE%','%CUSTOMERPHONE2%','%ADDRESS%','%APT%','%CITY%','%STATE%','%ZIPCODE%','%COUNTRY%','%SERVICES%','%PAYMENTMETHOD%','%AMOUNT%','%ADMINFEE%');
			
			if($bookingdata['member_id'] > 0){
			$membername = service_finder_getMemberName($bookingdata['member_id']);
			}else{
			$membername = '-';
			}
			$services = service_finder_get_booking_services($bookingdata['id']);
			
			$charge_admin_fee_from = (!empty($service_finder_options['charge-admin-fee-from'])) ? $service_finder_options['charge-admin-fee-from'] : '';
			$charge_admin_fee = (!empty($service_finder_options['charge-admin-fee'])) ? $service_finder_options['charge-admin-fee'] : '';
			
			if($charge_admin_fee_from == 'provider' && $charge_admin_fee && $pay_booking_amount_to == 'admin'){
			$adminfee = '0.0';
			}
			
			$replacements = array(date('Y-m-d',strtotime($bookingdata['date'])),$bookingdata['start_time'],$bookingdata['end_time'],$membername,service_finder_get_providername_with_link($providerInfo->wp_user_id),$providerInfo->email,service_finder_get_contact_info($providerInfo->phone,$providerInfo->mobile),$customerInfo->name,$customerInfo->email,$customerInfo->phone,$customerInfo->phone2,$customerInfo->address,$customerInfo->apt,$customerInfo->city,$customerInfo->state,$customerInfo->zipcode,$customerInfo->country,$services,ucfirst($payent_mode),service_finder_money_format($bookingdata['total']),service_finder_money_format($adminfee));
			$msg_body = str_replace($tokens,$replacements,$message);

			if($service_finder_options['booking-approval-to-customer-subject'] != ""){
				$msg_subject = $service_finder_options['booking-approval-to-customer-subject'];
			}else{
				$msg_subject = esc_html__('Booking Approval Notification', 'service-finder');
			}
			
			if(service_finder_wpmailer($customerInfo->email,$msg_subject,$msg_body)) {

				$success = array(
						'status' => 'success',
						'suc_message' => esc_html__('Message has been sent', 'service-finder'),
						);
				$service_finder_Success = json_encode($success);
				return $service_finder_Success;
				
				
			} else {
					
				$error = array(
						'status' => 'error',
						'err_message' => esc_html__('Message could not be sent.', 'service-finder'),
						);
				$service_finder_Errors = json_encode($error);
				return $service_finder_Errors;
			}
		
	}
	
	/*Send Booking Approval mail to admin*/
	public function service_finder_SendApproveBookingMailToAdmin($bookingdata,$adminfee = '0.0'){
		global $service_finder_options, $wpdb, $service_finder_Tables;
		$providerInfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->providers.' WHERE `wp_user_id` = %d',$bookingdata['provider_id']));
		$customerInfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->customers.' WHERE `id` = %d',$bookingdata['booking_customer_id']));
		
		$bookingpayment_mode = (!empty($bookingdata['type'])) ? $bookingdata['type'] : '';
		
		$payent_mode = ($bookingpayment_mode != '') ? $bookingpayment_mode : 'free';
		
		$pay_booking_amount_to = (!empty($service_finder_options['pay_booking_amount_to'])) ? $service_finder_options['pay_booking_amount_to'] : '';
		
		if(!empty($service_finder_options['booking-approval-to-admin'])){
			$message = $service_finder_options['booking-approval-to-admin'];
		}else{
			$message = '
<h4>Booking Details</h4>
Date: %DATE%
				
				Time: %STARTTIME% - %ENDTIME%
				
				Member Name: %MEMBERNAME%
<h4>Provider Details</h4>
Provider Name: %PROVIDERNAME%

				Provider Email: %PROVIDEREMAIL%
				
				Phone: %PROVIDERPHONE%
<h4>Customer Details</h4>
Customer Name: %CUSTOMERNAME%

Customer Email: %CUSTOMEREMAIL%

Phone: %CUSTOMERPHONE%

Alternate Phone: %CUSTOMERPHONE2%

Address: %ADDRESS%

Apt/Suite: %APT%

City: %CITY%

State: %STATE%

Postal Code: %ZIPCODE%

Country: %COUNTRY%

Services: %SERVICES%


<h4>Payment Details</h4>
Pay Via: %PAYMENTMETHOD%
				
				Amount: %AMOUNT%
				
				Admin Fee: %ADMINFEE%';
		}
			
			$tokens = array('%DATE%','%STARTTIME%','%ENDTIME%','%MEMBERNAME%','%PROVIDERNAME%','%PROVIDEREMAIL%','%PROVIDERPHONE%','%CUSTOMERNAME%','%CUSTOMEREMAIL%','%CUSTOMERPHONE%','%CUSTOMERPHONE2%','%ADDRESS%','%APT%','%CITY%','%STATE%','%ZIPCODE%','%COUNTRY%','%SERVICES%','%PAYMENTMETHOD%','%AMOUNT%','%ADMINFEE%');
			
			if($bookingdata['member_id'] > 0){
			$membername = service_finder_getMemberName($bookingdata['member_id']);
			}else{
			$membername = '-';
			}
			$services = service_finder_get_booking_services($bookingdata['id']);
			
			$charge_admin_fee = (!empty($service_finder_options['charge-admin-fee'])) ? $service_finder_options['charge-admin-fee'] : '';
			$charge_admin_fee_from = (!empty($service_finder_options['charge-admin-fee-from'])) ? $service_finder_options['charge-admin-fee-from'] : '';
			
			if($charge_admin_fee_from == 'provider' && $charge_admin_fee && $pay_booking_amount_to == 'admin'){
			$bookingamount = $bookingdata['total'] - $adminfee;
			}elseif($charge_admin_fee_from == 'customer' && $charge_admin_fee && $pay_booking_amount_to == 'admin'){
			$bookingamount = $bookingdata['total'];
			}else{
			$bookingamount = $bookingdata['total'];
			$adminfee = '0.0';
			}
			
			$replacements = array(date('Y-m-d',strtotime($bookingdata['date'])),$bookingdata['start_time'],$bookingdata['end_time'],$membername,service_finder_get_providername_with_link($providerInfo->wp_user_id),$providerInfo->email,service_finder_get_contact_info($providerInfo->phone,$providerInfo->mobile),$customerInfo->name,$customerInfo->email,$customerInfo->phone,$customerInfo->phone2,$customerInfo->address,$customerInfo->apt,$customerInfo->city,$customerInfo->state,$customerInfo->zipcode,$customerInfo->country,$services,ucfirst($payent_mode),service_finder_money_format($bookingamount),service_finder_money_format($adminfee));
			$msg_body = str_replace($tokens,$replacements,$message);
			
			if($service_finder_options['booking-approval-to-admin-subject'] != ""){
				$msg_subject = $service_finder_options['booking-approval-to-admin-subject'];
			}else{
				$msg_subject = esc_html__('Booking Approval Notification', 'service-finder');
			}
			
			if(service_finder_wpmailer(get_option('admin_email'),$msg_subject,$msg_body)) {

				$success = array(
						'status' => 'success',
						'suc_message' => esc_html__('Message has been sent', 'service-finder'),
						);
				$service_finder_Success = json_encode($success);
				return $service_finder_Success;
				
				
			} else {
					
				$error = array(
						'status' => 'error',
						'err_message' => esc_html__('Message could not be sent.', 'service-finder'),
						);
				$service_finder_Errors = json_encode($error);
				return $service_finder_Errors;
			}
		
	}
	
	/*Pay to Provider via adaptive paypal*/
	public function service_finder_pay_via_adaptive(){
		global $wpdb,$service_finder_options, $service_finder_Tables;

		$providerid = (!empty($_POST['providerid'])) ? esc_html($_POST['providerid']) : '';
		$pay_key = (!empty($_POST['paykey'])) ? esc_html($_POST['paykey']) : '';
		
		$paypal_email_id = get_user_meta($providerid,'paypal_email_id',true);
		
	   $paypalCreds['USER'] = (isset($service_finder_options['paypal-username'])) ? $service_finder_options['paypal-username'] : '';
	   $paypalCreds['PWD'] = (isset($service_finder_options['paypal-password'])) ? $service_finder_options['paypal-password'] : '';
	   $paypalCreds['SIGNATURE'] = (isset($service_finder_options['paypal-signatue'])) ? $service_finder_options['paypal-signatue'] : '';
	   $sandbox = (isset($service_finder_options['paypal-type']) && $service_finder_options['paypal-type'] == 'sandbox') ? true : false;
	   
	   $return_page = add_query_arg( array('page' => 'bookings'), admin_url('admin.php') );
	   $ipn_page = SERVICE_FINDER_BOOKING_LIB_URL.'/paypal_ipn.php?paytoprovider=done';
	   $appid = (isset($service_finder_options['paypal-app-id'])) ? $service_finder_options['paypal-app-id'] : '';
				
		$PayPalConfig = array( 		
			'Sandbox' => $sandbox, 
			'DeveloperAccountEmail' => '', 
			'ApplicationID' => $appid, 
			'DeviceID' => '', 
			'IPAddress' => $_SERVER['REMOTE_ADDR'], 
			'APIUsername' => $paypalCreds['USER'], 
			'APIPassword' => $paypalCreds['PWD'], 
			'APISignature' => $paypalCreds['SIGNATURE'], 
			'APISubject' => ''
		);
	
		$PayPal = new angelleye\PayPal\Adaptive($PayPalConfig);
			
		try {
			$executePaymentRequest = array(
				'PayKey' => $pay_key, 
			);
			
			$PayPalRequestData = array(
				'ExecutePaymentFields' => $executePaymentRequest, 
			);
			
			$response = $PayPal->ExecutePayment($PayPalRequestData);
			//echo '<pre>';print_r($response);echo '</pre>';
			
			if($response['Ack'] == 'Success' && $response['PaymentExecStatus'] == 'COMPLETED'){
			$data = array(
					'paid_to_provider' => 'paid',
					);
			
			$where = array(
					'paypal_paykey' => $pay_key,
					);
			
			$booking_id = $wpdb->update($service_finder_Tables->bookings,wp_unslash($data),$where);
			
			if(function_exists('service_finder_add_notices')) {
				$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->bookings.' WHERE `paypal_paykey` = %s',$pay_key));	
				$noticedata = array(
						'provider_id' => $row->provider_id,
						'target_id' => $row->id, 
						'topic' => esc_html__('Booking Payment', 'service-finder'),
						'notice' => esc_html__('Site administrator paid you for your service via paypal adaptive', 'service-finder')
						);
				service_finder_add_notices($noticedata);
			
			}
			
			$success = array(
					'status' => 'success',
					'suc_message' => esc_html__('Payment transferred successfully.', 'service-finder'),
					);
			echo json_encode($success);
			}else{
			$error = array(
					'status' => 'error',
					'err_message' => $response['Errors'][0]['Message']
					);
			echo json_encode($error);
			}
		} catch (Exception $e) {

			$error = array(
					'status' => 'error',
					'err_message' => $e->getMessage()
					);
			echo json_encode($error);
			
		}
		exit(0);
	}
	
	/*Pay to Provider via stripe connect*/
	public function service_finder_pay_via_stripe_connect(){
		global $wpdb,$service_finder_options, $service_finder_Tables;

		$providerid = (!empty($_POST['providerid'])) ? esc_html($_POST['providerid']) : '';
		$bookingid = (!empty($_POST['bookingid'])) ? esc_html($_POST['bookingid']) : '';
		$amount = (!empty($_POST['amount'])) ? esc_html($_POST['amount']) : '';
		
		$stripetype = (!empty($service_finder_options['stripe-type'])) ? esc_html($service_finder_options['stripe-type']) : '';
		if($stripetype == 'live'){
			$secret_key = (!empty($service_finder_options['stripe-live-secret-key'])) ? esc_html($service_finder_options['stripe-live-secret-key']) : '';
		}else{
			$secret_key = (!empty($service_finder_options['stripe-test-secret-key'])) ? esc_html($service_finder_options['stripe-test-secret-key']) : '';
		}
		
		$totalcost = $amount * 100;
		require_once(SERVICE_FINDER_PAYMENT_GATEWAY_DIR.'/stripe/init.php');
		
	    try {
            
			$stripeconnecttype = (!empty($service_finder_options['stripe-connect-type'])) ? esc_html($service_finder_options['stripe-connect-type']) : '';
			
			if($stripeconnecttype == 'custom'){
			
			$stripe_connect_id = get_user_meta($providerid,'stripe_connect_custom_account_id',true);
			
			$payout = service_finder_do_payout($providerid,$totalcost);
			$payout = json_decode($payout);
			
				
			}else{
			
			$stripe_connect_id = get_user_meta($providerid,'stripe_connect_id',true);
			
			\Stripe\Stripe::setApiKey($secret_key);
            $transfer_args = array(
                'amount' => $totalcost,
                'currency' => strtolower(service_finder_currencycode()),
                'destination' => $stripe_connect_id
            );
            $payout = \Stripe\Transfer::create($transfer_args);
			$payout = json_decode($payout);
			}
			
			if($payout->status == 'pending' || $payout->status == 'in_transit' || $payout->status == 'paid'){
			
			if($payout->status == 'paid'){
				$bookingtablestatus = 'paid';
			}else{
				$bookingtablestatus = 'in-process';
			}
			
			$data = array(
					'paid_to_provider' => $bookingtablestatus,
					);
			
			$where = array(
					'id' => $bookingid,
					);
			
			$booking_id = $wpdb->update($service_finder_Tables->bookings,wp_unslash($data),$where);
			
			$data = array(
					'created' => date('Y-m-d h:i:s',$payout->created),
					'arrival_date' => date('Y-m-d h:i:s',$payout->arrival_date),
					'provider_id' => $providerid,
					'booking_id' => $bookingid,
					'connected_account_id' => $stripe_connect_id,
					'amount' => $amount,
					'stripe_connect_type' => $stripeconnecttype,
					'status' => $payout->status,
					'payout_id' => $payout->id,
					);
					
			$wpdb->insert($service_finder_Tables->payout_history,wp_unslash($data));
			
			if(function_exists('service_finder_add_notices')) {
				$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->bookings.' WHERE `id` = %d',$bookingid));	
				$noticedata = array(
						'provider_id' => $row->provider_id,
						'target_id' => $row->id, 
						'topic' => esc_html__('Booking Payment', 'service-finder'),
						'notice' => sprintf(esc_html__('Site administrator release payout. It will take some time to reflect in your account. Booking Ref id is #%d', 'service-finder'),$bookingid)
						);
				service_finder_add_notices($noticedata);
			
			}
			
			$success = array(
					'status' => 'success',
					'suc_message' => esc_html__('Payout initiate successfully.', 'service-finder'),
					);
			echo json_encode($success);
			}else{
			$error = array(
					'status' => 'error',
					'err_message' => $payout->err_message
					);
			echo json_encode($error);
			}
			
        } catch (\Stripe\Error\InvalidRequest $e) {
           $error = array(
					'status' => 'error',
					'err_message' => $e->getMessage()
					);
			echo json_encode($error);
        } catch (\Stripe\Error\Authentication $e) {
           $error = array(
					'status' => 'error',
					'err_message' => $e->getMessage()
					);
			echo json_encode($error);
        } catch (\Stripe\Error\ApiConnection $e) {
            $error = array(
					'status' => 'error',
					'err_message' => $e->getMessage()
					);
			echo json_encode($error);
        } catch (\Stripe\Error\Base $e) {
            $error = array(
					'status' => 'error',
					'err_message' => $e->getMessage()
					);
			echo json_encode($error);
        } catch (Exception $e) {
            $error = array(
					'status' => 'error',
					'err_message' => $e->getMessage()
					);
			echo json_encode($error);
        }
			
		exit(0);
	}
	
	/*Pay to Provider via mangopay*/
	public function service_finder_pay_via_mangopay(){
		global $wpdb,$service_finder_options, $service_finder_Tables;

		$providerid = (!empty($_POST['providerid'])) ? esc_html($_POST['providerid']) : '';
		$bookingid = (!empty($_POST['bookingid'])) ? esc_html($_POST['bookingid']) : '';
		$amount = (!empty($_POST['amount'])) ? esc_html($_POST['amount']) : '';
		$order_id = (!empty($_POST['orderid'])) ? esc_html($_POST['orderid']) : '';
		$order_author_id = get_post_field( 'post_author', $order_id );
		
		$fees = 0;
		$currency	= get_woocommerce_currency();
		
		$mp_account_id = service_finder_get_mp_account_id($providerid);
		
		if(!$mp_account_id){
			$error = array(
					'status' => 'error',
					'err_message' => esc_html__('Vendor does not have a MANGOPAY bank account.', 'service-finder'),
					);
			echo json_encode($error);
			
			exit(0);
		}
		
		$result = service_finder_payout(  $order_author_id, $mp_account_id, $order_id, $currency, $amount, $fees );
		
		
		if(	isset( $result->Status ) && ( 'SUCCEEDED' == $result->Status || 'CREATED' == $result->Status )) {
			
			update_post_meta($order_id,'mp_payout_result',$result);
			update_post_meta($order_id,'mp_payout_status',$result->Status);
			update_post_meta($order_id,'mp_payout_id',$result->Id);
			update_post_meta($order_id,'mp_payout_due','yes');
			update_post_meta($order_id,'mp_booking_id',$bookingid);
			
			if('SUCCEEDED' == $result->Status){
				$payoutstatus = 'paid';
				$noticemsg = sprintf(esc_html__('Succeed: Payout has been paid to your bank account. Booking Ref id is #%d', 'service-finder'),$bookingid);
			}elseif('CREATED' == $result->Status){
				$payoutstatus = 'created';
				$noticemsg = sprintf(esc_html__('Created: The payout released by admin. Booking Ref id is #%d', 'service-finder'),$bookingid);
				
			}
			
			$data = array(
					'paid_to_provider' => $payoutstatus,
					);
			
			$where = array(
					'id' => $bookingid,
					);
			
			$booking_id = $wpdb->update($service_finder_Tables->bookings,wp_unslash($data),$where);
			
			$data = array(
					'status' => $payoutstatus,
					);
			
			$where = array(
					'order_id' => $order_id,
					);
			
			$commission_id = $wpdb->update($wpdb->prefix.'pv_commission',wp_unslash($data),$where);
			
			$data = array(
					'created' => date('Y-m-d h:i:s'),
					'payout_id' => $result->Id,
					'order_id' => $order_id,
					'mp_account_id' => $mp_account_id,
					'provider_id' => $providerid,
					'booking_id' => $bookingid,
					'amount' => $amount,
					'status' => $result->Status,
					);
					
			$wpdb->insert($service_finder_Tables->mp_payout_history,wp_unslash($data));
			
			if(function_exists('service_finder_add_notices')) {
				$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->bookings.' WHERE `id` = %d',$bookingid));	
				$noticedata = array(
						'provider_id' => $row->provider_id,
						'target_id' => $row->id, 
						'topic' => esc_html__('Booking Payment', 'service-finder'),
						'notice' => $noticemsg
						);
				service_finder_add_notices($noticedata);
			
			}
			
			$success = array(
					'status' => 'success',
					'suc_message' => esc_html__('Payout created for vendor successfully.', 'service-finder'),
					);
			echo json_encode($success);
		} else {
			
			$errmsg = esc_html__('Vendor MANGOPAY payout transaction failed.', 'service-finder');
			if($result->ResultMessage){
			$errmsg .= ': '.$result->ResultMessage;
			}
			
			$error = array(
					'status' => 'error',
					'err_message' => $errmsg
					);
			echo json_encode($error);
			
		}
		
		exit(0);
	}
	
	/*Change provider payment status from pending to paid*/
	public function service_finder_status_pay_to_provider(){
		global $wpdb, $service_finder_options, $service_finder_Tables;
		$receiver          = array();
		
		$bookingid = (!empty($_POST['bookingid'])) ? esc_html($_POST['bookingid']) : '';
		
		$data = array(
				'paid_to_provider' => 'paid',
				);
		
		$where = array(
				'id' => $bookingid,
				);
		
		$booking_id = $wpdb->update($service_finder_Tables->bookings,wp_unslash($data),$where);
				
		if(is_wp_error($booking_id)){
			$error = array(
					'status' => 'error',
					'err_message' => $booking_id->get_error_message()
					);
			echo json_encode($error);
		}else{
			
			if(function_exists('service_finder_add_notices')) {
				$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->bookings.' WHERE `id` = %d',$bookingid));	
				$noticedata = array(
						'provider_id' => $row->provider_id,
						'target_id' => $row->id, 
						'topic' => esc_html__('Booking Payment', 'service-finder'),
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
	
	/*Actions for wp ajax call*/
	public function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_admin_bookings',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_admin_bookings' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_delete_admin_bookings',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_deleteAdminBookings' ) );
                    }
						
                );
				
		add_action(
                    'wp_ajax_wired_booking_admin_approval',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_wired_booking_admin_approval' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_pay_via_adaptive',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_pay_via_adaptive' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_pay_via_stripe_connect',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_pay_via_stripe_connect' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_pay_via_mangopay',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_pay_via_mangopay' ) );
                    }
						
                );				
		add_action(
                    'wp_ajax_status_pay_to_provider',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_status_pay_to_provider' ) );
                    }
						
                );								
				
    }
	
}