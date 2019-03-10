<?php
ob_start();
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/


require SERVICE_FINDER_BOOKING_FRONTEND_MODULE_DIR . '/book-now/BookNow.php';

$service_finder_options = get_option('service_finder_options');

$receiver          = array();

$providerid = isset($_POST['provider']) ? $_POST['provider'] : '';
$totalcost = isset($_POST['totalcost']) ? $_POST['totalcost'] : '';
$totaldiscount = (!empty($_POST['totaldiscount'])) ? esc_html($_POST['totaldiscount']) : 0;
		
if(floatval($totalcost) >= floatval($totaldiscount)){
$totalcost = floatval($totalcost) - floatval($totaldiscount);
}else{
$totalcost = floatval($totalcost);
}

$settings = service_finder_getProviderSettings($providerid);

$admin_fee_type = (!empty($service_finder_options['admin-fee-type'])) ? $service_finder_options['admin-fee-type'] : 0;
$admin_fee_percentage = (!empty($service_finder_options['admin-fee-percentage'])) ? $service_finder_options['admin-fee-percentage'] : 0;
$admin_fee_fixed = (!empty($service_finder_options['admin-fee-fixed'])) ? $service_finder_options['admin-fee-fixed'] : 0;

$charge_admin_fee = (!empty($service_finder_options['charge-admin-fee'])) ? $service_finder_options['charge-admin-fee'] : '';
$charge_admin_fee_from = (!empty($service_finder_options['charge-admin-fee-from'])) ? $service_finder_options['charge-admin-fee-from'] : '';

$pay_booking_amount_to = (!empty($service_finder_options['pay_booking_amount_to'])) ? esc_html($service_finder_options['pay_booking_amount_to']) : '';

if($charge_admin_fee && $pay_booking_amount_to == 'admin' && (($admin_fee_type == 'fixed' && $admin_fee_fixed > 0) || ($admin_fee_type == 'percentage' && $admin_fee_percentage > 0)) && $charge_admin_fee_from == 'customer'){

	if($admin_fee_type == 'fixed'){
		$adminfee = $admin_fee_fixed;
	}elseif($admin_fee_type == 'percentage'){
		$adminfee = $totalcost * ($admin_fee_percentage/100);	
	}
	
	$totalcost = $totalcost + $adminfee;
}elseif($charge_admin_fee && $pay_booking_amount_to == 'admin' && (($admin_fee_type == 'fixed' && $admin_fee_fixed > 0) || ($admin_fee_type == 'percentage' && $admin_fee_percentage > 0)) && $charge_admin_fee_from == 'provider'){
	if($admin_fee_type == 'fixed'){
		$adminfee = $admin_fee_fixed;
	}elseif($admin_fee_type == 'percentage'){
		$adminfee = $totalcost * ($admin_fee_percentage/100);	
	}
	
}else{
	$adminfee = 0;
}

$primaryamount = $adminfee;
$secondaryamount = $totalcost - $adminfee;


$secondaryemail = get_user_meta($providerid,'paypal_email_id',true);

$paypalCreds['USER'] = (isset($service_finder_options['paypal-username'])) ? $service_finder_options['paypal-username'] : '';
$paypalCreds['PWD'] = (isset($service_finder_options['paypal-password'])) ? $service_finder_options['paypal-password'] : '';
$paypalCreds['SIGNATURE'] = (isset($service_finder_options['paypal-signatue'])) ? $service_finder_options['paypal-signatue'] : '';
$sandbox = (isset($service_finder_options['paypal-type']) && $service_finder_options['paypal-type'] == 'sandbox') ? true : false;

$paymentfeesby = (isset($service_finder_options['payment-fees-by'])) ? $service_finder_options['payment-fees-by'] : '';

$return_page = add_query_arg( array('page' => 'bookings'), admin_url('admin.php') );
$ipn_page = SERVICE_FINDER_BOOKING_LIB_URL.'/paypal_ipn.php?booking=complete';

$appid = (isset($service_finder_options['paypal-app-id'])) ? $service_finder_options['paypal-app-id'] : '';

$userLink = service_finder_get_author_url($providerid);
$redirectOption = $service_finder_options['redirect-option'];
$redirectURL = (!empty($service_finder_options['thankyou-page-url'])) ? $service_finder_options['thankyou-page-url'] : '';
if($redirectOption == 'thankyou-page'){
	if($redirectURL != ""){
	$return_page = add_query_arg( array('bookingcompleted' => 'success'), $redirectURL );
	}else{
	$return_page = add_query_arg( array('bookingcompleted' => 'success'), service_finder_get_url_by_shortcode('[service_finder_thank_you]') );
	}
}else{

$return_page = add_query_arg( array('bookingcompleted' => 'success'), $userLink );
}

$primaryemail = (isset($service_finder_options['paypal-email-id'])) ? $service_finder_options['paypal-email-id'] : '';

$receiver[] = array(
			  'Amount'           => $totalcost,
			  'Email'            => $primaryemail,
			  'Primary'          => TRUE
	   );
$receiver[] = array(
			  'Amount'           => $secondaryamount,
			  'Email'            => $secondaryemail,
			  'Primary'          => FALSE
	   );	   

$cancel_url = add_query_arg( array('booking_made' => 'cancel'), $userLink );	

try {
$paypal_result = execute_payment( $sandbox, $paypalCreds['USER'], $paypalCreds['PWD'], $paypalCreds['SIGNATURE'], service_finder_currencycode(), $paymentfeesby, $receiver, $return_page, $ipn_page, $appid, $cancel_url);
//echo '<pre>';print_r($paypal_result);echo '</pre>';
if($paypal_result['Ack'] == 'Success'){
$saveBooking = new SERVICE_FINDER_BookNow();
$saveBooking->service_finder_SaveBooking($_POST,'','',$adminfee,$paypal_result['PayKey']);

wp_redirect($paypal_result['RedirectURL']);
exit(0);
}else{
$redirect_uri = add_query_arg( array('booking' => 'failed','errmsg' => $paypal_result['Errors'][0]['Message']), $userLink );
wp_redirect($redirect_uri);
exit(0);
}
} catch (Exception $e) {
echo '<div class="alert alert-danger">'.$e->getMessage().'</div>';
}
