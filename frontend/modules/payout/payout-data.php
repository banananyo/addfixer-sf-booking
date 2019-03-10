<?php
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/

/*Create stripe connect custom account with general info*/
add_action('wp_ajax_create_custom_payout_account', 'service_finder_create_custom_payout_account');
function service_finder_create_custom_payout_account(){
global $wpdb, $service_finder_Errors,$service_finder_options,$current_user;

require_once(SERVICE_FINDER_PAYMENT_GATEWAY_DIR.'/stripe/init.php');

if( isset($service_finder_options['stripe-type']) && $service_finder_options['stripe-type'] == 'test' ){
	$secret_key = (!empty($service_finder_options['stripe-test-secret-key'])) ? $service_finder_options['stripe-test-secret-key'] : '';
	$public_key = (!empty($service_finder_options['stripe-test-public-key'])) ? $service_finder_options['stripe-test-public-key'] : '';
}else{
	$secret_key = (!empty($service_finder_options['stripe-live-secret-key'])) ? $service_finder_options['stripe-live-secret-key'] : '';
	$public_key = (!empty($service_finder_options['stripe-live-public-key'])) ? $service_finder_options['stripe-live-public-key'] : '';
}

if($secret_key != ""){
try {

	$user_id = (isset($_POST['user_id'])) ? esc_attr($_POST['user_id']) : '';
	$first_name = (isset($_POST['first_name'])) ? esc_attr($_POST['first_name']) : '';
	$last_name = (isset($_POST['last_name'])) ? esc_attr($_POST['last_name']) : '';
	$user_email = (isset($_POST['user_email'])) ? esc_attr($_POST['user_email']) : '';
	$dob = (isset($_POST['dob'])) ? esc_attr($_POST['dob']) : '';
	$address = (isset($_POST['address'])) ? esc_attr($_POST['address']) : '';
	$postal_code = (isset($_POST['postal_code'])) ? esc_attr($_POST['postal_code']) : '';
	$city = (isset($_POST['city'])) ? esc_attr($_POST['city']) : '';
	$state = (isset($_POST['state'])) ? esc_attr($_POST['state']) : '';
	
	\Stripe\Stripe::setApiKey($secret_key);
	
	$acct_id = get_user_meta($user_id,'stripe_connect_custom_account_id',true);
	
	if($acct_id == ''){
	$acct = \Stripe\Account::create(array(
	  "type" => "custom",
	  "country" => "US",
	));
	
	$acct_id = $acct->id;
	update_user_meta($user_id,'stripe_connect_custom_account_id',$acct_id);
	}
	
	$account = \Stripe\Account::retrieve($acct_id);
	
	$dob = explode('-',$dob);
	
	$account->tos_acceptance->date = time();
	$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
	$account->legal_entity->dob->day = $dob[2];
	$account->legal_entity->dob->month = $dob[1];
	$account->legal_entity->dob->year = $dob[0];
	//$account->legal_entity->email = $user_email;
	$account->legal_entity->first_name = $first_name;
	$account->legal_entity->last_name = $last_name;
	$account->legal_entity->address->line1 = $address;
	$account->legal_entity->address->city = $city;
	$account->legal_entity->address->postal_code = $postal_code;
	$account->legal_entity->address->state = $state;
	$account->legal_entity->type = "individual";
	$account->save();
	
	$currentpageurl = service_finder_get_url_by_shortcode('[service_finder_my_account]');
	if(service_finder_getUserRole($current_user->ID) == 'administrator'){
	$currentpageurl = add_query_arg( array('manageaccountby' => 'admin','manageproviderid' => $user_id,'tabname' => 'payout-general'), $currentpageurl );
	}else{
	$currentpageurl = add_query_arg( array('tabname' => 'payout-general'), $currentpageurl );
	}

	$success = array(
			'status' => 'success',
			'redirect' => $currentpageurl,
			'suc_message' => esc_html__('Stripe connect custom account created/updated successfully', 'service-finder'),
			);
	echo json_encode($success);

} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	echo json_encode($error);
}
}
exit;
}

/*Create stripe connect custom account with general info*/
add_action('wp_ajax_create_mp_account', 'service_finder_create_mp_account');
function service_finder_create_mp_account(){
global $wpdb, $service_finder_Errors,$service_finder_options;

$user_id = (isset($_POST['user_id'])) ? esc_attr($_POST['user_id']) : '';
$dob = (isset($_POST['dob'])) ? esc_attr($_POST['dob']) : '';
$mp_nationality = (isset($_POST['mp_nationality'])) ? esc_attr($_POST['mp_nationality']) : '';
$mp_country = (isset($_POST['mp_country'])) ? esc_attr($_POST['mp_country']) : '';

update_user_meta($user_id,'user_birthday',$dob);
update_user_meta($user_id,'user_nationality',$mp_nationality);
update_user_meta($user_id,'billing_country',$mp_country);

service_finder_meke_user_vendor($user_id);

$success = array(
		'status' => 'success',
		'suc_message' => esc_html__('First step completed, please continue to next step.', 'service-finder'),
		);
echo json_encode($success);

exit;
}

/*stripe connect custom identity verification*/
add_action('wp_ajax_stripe_identity_verification', 'service_finder_stripe_identity_verification');
function service_finder_stripe_identity_verification(){
global $wpdb, $service_finder_Errors,$service_finder_options,$current_user;

require_once(SERVICE_FINDER_PAYMENT_GATEWAY_DIR.'/stripe/init.php');

if( isset($service_finder_options['stripe-type']) && $service_finder_options['stripe-type'] == 'test' ){
	$secret_key = (!empty($service_finder_options['stripe-test-secret-key'])) ? $service_finder_options['stripe-test-secret-key'] : '';
	$public_key = (!empty($service_finder_options['stripe-test-public-key'])) ? $service_finder_options['stripe-test-public-key'] : '';
}else{
	$secret_key = (!empty($service_finder_options['stripe-live-secret-key'])) ? $service_finder_options['stripe-live-secret-key'] : '';
	$public_key = (!empty($service_finder_options['stripe-live-public-key'])) ? $service_finder_options['stripe-live-public-key'] : '';
}

if($secret_key != ""){
try {

	$user_id = (isset($_POST['user_id'])) ? esc_attr($_POST['user_id']) : '';
	$personal_id_number = (isset($_POST['personal_id_number'])) ? esc_attr($_POST['personal_id_number']) : '';
	$alternative_text = (isset($_POST['alternative_text'])) ? esc_attr($_POST['alternative_text']) : '';
	$stripeidentityattachmentid = (isset($_POST['stripeidentityattachmentid'])) ? esc_attr($_POST['stripeidentityattachmentid']) : '';
	$filepath = get_attached_file( $stripeidentityattachmentid );
	
	\Stripe\Stripe::setApiKey($secret_key);
	
	$acct_id = get_user_meta($user_id,'stripe_connect_custom_account_id',true);
	
	if($acct_id == ''){
	$acct = \Stripe\Account::create(array(
	  "type" => "custom",
	  "country" => "US",
	));
	
	$acct_id = $acct->id;
	update_user_meta($user_id,'stripe_connect_custom_account_id',$acct_id);
	}
	
	$account = \Stripe\Account::retrieve($acct_id);
	
	$account->tos_acceptance->date = time();
	$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
	
	if($filepath != ""){
	$fp = fopen($filepath, 'r');
	$file_obj = \Stripe\FileUpload::create(
	  array(
		"purpose" => "identity_document",
		"file" => $fp
	  ),
	  array(
		"stripe_account" => $acct_id
	  )
	);
	
	$file = $file_obj->id;
	
	$account->legal_entity->verification->document = $file;
	}
	$account->legal_entity->personal_id_number = $personal_id_number;
	$account->save();
	
	$currentpageurl = service_finder_get_url_by_shortcode('[service_finder_my_account]');
	if(service_finder_getUserRole($current_user->ID) == 'administrator'){
	$currentpageurl = add_query_arg( array('manageaccountby' => 'admin','manageproviderid' => $user_id,'tabname' => 'identity-verification'), $currentpageurl );
	}else{
	$currentpageurl = add_query_arg( array('tabname' => 'identity-verification'), $currentpageurl );
	}
	
	$success = array(
			'status' => 'success',
			'redirect' => $currentpageurl,
			'suc_message' => esc_html__('Stripe identity submitted successfully', 'service-finder'),
			);
	echo json_encode($success);

} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	echo json_encode($error);
}
}
exit;
}

/*Update stripe connect external account info*/
add_action('wp_ajax_update_stripe_external_account', 'service_finder_update_stripe_external_account');
function service_finder_update_stripe_external_account(){
global $wpdb, $service_finder_Errors,$service_finder_options,$current_user;

require_once(SERVICE_FINDER_PAYMENT_GATEWAY_DIR.'/stripe/init.php');

if( isset($service_finder_options['stripe-type']) && $service_finder_options['stripe-type'] == 'test' ){
	$secret_key = (!empty($service_finder_options['stripe-test-secret-key'])) ? $service_finder_options['stripe-test-secret-key'] : '';
	$public_key = (!empty($service_finder_options['stripe-test-public-key'])) ? $service_finder_options['stripe-test-public-key'] : '';
}else{
	$secret_key = (!empty($service_finder_options['stripe-live-secret-key'])) ? $service_finder_options['stripe-live-secret-key'] : '';
	$public_key = (!empty($service_finder_options['stripe-live-public-key'])) ? $service_finder_options['stripe-live-public-key'] : '';
}

if($secret_key != ""){
try {

	$user_id = (isset($_POST['user_id'])) ? esc_attr($_POST['user_id']) : '';
	$currency = (isset($_POST['currency'])) ? esc_attr($_POST['currency']) : '';
	$bank_country = (isset($_POST['bank_country'])) ? esc_attr($_POST['bank_country']) : '';
	$routing_number = (isset($_POST['routing_number'])) ? esc_attr($_POST['routing_number']) : '';
	$account_number = (isset($_POST['account_number'])) ? esc_attr($_POST['account_number']) : '';
	
	\Stripe\Stripe::setApiKey($secret_key);
	
	$acct_id = get_user_meta($user_id,'stripe_connect_custom_account_id',true);
	
	if($acct_id == ''){
	$acct = \Stripe\Account::create(array(
	  "type" => "custom",
	  "country" => "US",
	));
	
	$acct_id = $acct->id;
	update_user_meta($user_id,'stripe_connect_custom_account_id',$acct_id);
	}
	
	$account = \Stripe\Account::retrieve($acct_id);
	
	$account->tos_acceptance->date = time();
	$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
	$account->save();
	
	$bank_account_id = get_user_meta($user_id,'stripe_connect_bank_account_id',true);
	
	if($bank_account_id == ''){
	$bank_account = $account->external_accounts->create(array("external_account" => array(
	"object" => 'bank_account',
	"country" => $bank_country,
	"currency" => $currency,
	"routing_number" => $routing_number,
	"account_number" => $account_number,
	)));
	
	$bank_account_id = $bank_account->id;

	update_user_meta($user_id,'stripe_connect_bank_account_id',$bank_account_id);
	}

	$currentpageurl = service_finder_get_url_by_shortcode('[service_finder_my_account]');
	if(service_finder_getUserRole($current_user->ID) == 'administrator'){
	$currentpageurl = add_query_arg( array('manageaccountby' => 'admin','manageproviderid' => $user_id,'tabname' => 'external-account'), $currentpageurl );
	}else{
	$currentpageurl = add_query_arg( array('tabname' => 'external-account'), $currentpageurl );
	}

	$success = array(
			'status' => 'success',
			'redirect' => $currentpageurl,
			'suc_message' => esc_html__('Stripe external account created successfully', 'service-finder'),
			);
	echo json_encode($success);

} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	echo json_encode($error);
}
}
exit;
}

/*Get stripe connect info*/
function service_finder_get_stripe_account_info($user_id){
$secret_key = service_finder_stripe_connection();

$secret_key = $secret_key['secret_key'];

if($secret_key != ""){
try {

	\Stripe\Stripe::setApiKey($secret_key);
	
	$acct_id = get_user_meta($user_id,'stripe_connect_custom_account_id',true);
	
	if($acct_id != ''){
	$account = \Stripe\Account::retrieve($acct_id);
	return $account;
	}else{
	return '';
	}
	
} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	return json_encode($error);
}
}

}

/*Payout via custom stripe connect account*/
function service_finder_do_payout($user_id,$amount){
$secret_key = service_finder_stripe_connection();

$secret_key = $secret_key['secret_key'];

if($secret_key != ""){
try {

	\Stripe\Stripe::setApiKey($secret_key);
	
	$acct_id = get_user_meta($user_id,'stripe_connect_custom_account_id',true);
	
	if($acct_id != ''){
	
	$accountinfo = service_finder_get_stripe_account_info($user_id);

	$currency = (!empty($accountinfo->external_accounts->data[0]->currency)) ? $accountinfo->external_accounts->data[0]->currency : '';
	
	$payout = \Stripe\Payout::create(array(
	  "amount" => $amount,
	  "currency" => $currency,
	  ),
	  array(
		"stripe_account" => $acct_id
	  )
	);
	
	return $payout;
	}
	
} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	return json_encode($error);
}
}
}

/*Get fields need in stipe connect custom account*/
function service_finder_get_fields_needed($accountinfo){

$string = '';
if(!empty($accountinfo)){
$fields_needed = $accountinfo->verification->fields_needed;
if(!empty($fields_needed)){
	$reqfields = '<ul>';
	$flag = 0;
	$genaralflag = 0;
	foreach($fields_needed as $fieldval){
		switch($fieldval){
			case 'bank_account':
				$genaralflag = 0;
				$fieldmsg = esc_html__('Fill external account information', 'service-finder');
				break;
			case 'legal_entity.ssn_last_4':
				$genaralflag = 0;
				$fieldmsg = esc_html__('Fill Personal ID Number', 'service-finder');
				break;
			case 'legal_entity.dob.day':
				$flag = $flag + 1;
				$genaralflag = 1;
				$fieldmsg = esc_html__('Please fill general information from general tab', 'service-finder');
				break;
			case 'legal_entity.dob.month':
				$flag = $flag + 1;
				$genaralflag = 1;
				$fieldmsg = esc_html__('Please fill general information from general tab', 'service-finder');
				break;
			case 'legal_entity.dob.year':
				$flag = $flag + 1;
				$genaralflag = 1;
				$fieldmsg = esc_html__('Please fill general information from general tab', 'service-finder');
				break;
			case 'legal_entity.first_name':
				$flag = $flag + 1;
				$genaralflag = 1;
				$fieldmsg = esc_html__('Please fill general information from general tab', 'service-finder');
				break;
			case 'legal_entity.last_name':
				$flag = $flag + 1;
				$genaralflag = 1;
				$fieldmsg = esc_html__('Please fill general information from general tab', 'service-finder');
				break;
			case 'legal_entity.type':
				$flag = $flag + 1;
				$genaralflag = 1;
				$fieldmsg = esc_html__('Please fill general information from general tab', 'service-finder');
				break;			
			default:	
				$fieldmsg = $fieldval;
				$genaralflag = 0;
				break;
		}
		if($flag == 1 || $genaralflag == 0){
		$reqfields .= '<li>'.$fieldmsg.'</li>';
		}
	}
	$reqfields .= '</ul>';
	
	$reqfields = str_replace('<li></li>','',$reqfields);
	
	$string .= '<div class="alert alert-info">';
	$string .= esc_html__('You have to complete following steps.', 'service-finder');
	$string .= '<br/>';
	$string .= $reqfields;
	$string .= '</div>';
}
}
	return $string;
}

/*Get payout status of stipe connect custom account*/
function service_finder_get_payout_status($accountinfo){

$string = '';
if(!empty($accountinfo)){
$payouts_enabled = $accountinfo->payouts_enabled;
if($payouts_enabled == 1){
	$string .= '<div class="alert alert-info">';
	$string .= esc_html__('Payouts to this account are enabled.', 'service-finder');
	$string .= '</div>';
}else{
	$string .= '<div class="alert alert-warning">';
	$string .= esc_html__('Payouts to this account are disabled.', 'service-finder');
	$string .= '</div>';
}
}
	return $string;
}

/*Payout via custom stripe connect account*/
function service_finder_get_balance(){
$secret_key = service_finder_stripe_connection();

$secret_key = $secret_key['secret_key'];

if($secret_key != ""){
try {

	\Stripe\Stripe::setApiKey($secret_key);
	
	$bal = \Stripe\Balance::retrieve();
	return $bal;
	
} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	return json_encode($error);
}
}
}

/*Retrieve Payout via custom stripe connect account*/
function service_finder_retrieve_payout($payoutid){
$secret_key = service_finder_stripe_connection();

$secret_key = $secret_key['secret_key'];

if($secret_key != ""){
try {

	\Stripe\Stripe::setApiKey($secret_key);
	
	$payout = \Stripe\Payout::all(array("limit" => 3));
	return $payout;
	
} catch (Exception $e) {
	$body = $e->getJsonBody();
	$err  = $body['error'];

	$error = array(
			'status' => 'error',
			'err_message' => sprintf( esc_html__('%s', 'service-finder'), $err['message'] )
			);
	return json_encode($error);
}
}
}

/*Create stripe connect custom account with general info*/
add_action('wp_ajax_get_payout_history', 'service_finder_get_payout_history');
function service_finder_get_payout_history(){
	global $wpdb, $service_finder_Tables;
	$requestData= $_REQUEST;
	
	$user_id = (isset($_POST['user_id'])) ? esc_attr($_POST['user_id']) : '';
	
	$providers = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->payout_history.' WHERE `provider_id` = %d',$user_id));
	
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
		$nestedData[] = '#'.$result->booking_id;
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

/*Create stripe connect custom account with general info*/
add_action('wp_ajax_get_mp_payout_history', 'service_finder_get_mp_payout_history');
function service_finder_get_mp_payout_history(){
	global $wpdb, $service_finder_Tables;
	$requestData= $_REQUEST;
	
	$user_id = (isset($_POST['user_id'])) ? esc_attr($_POST['user_id']) : '';
	
	$providers = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->mp_payout_history.' WHERE `provider_id` = %d',$user_id));
	
	$columns = array( 
		0 =>'booking_id', 
		1 => 'created',
		2 => 'payout_id',
		3 => 'amount',
		6 => 'status',
	);
	
	// getting total number records without any search
	$sql = 'SELECT * FROM '.$service_finder_Tables->mp_payout_history.' WHERE `provider_id` = '.$user_id;
	$query=$wpdb->get_results($sql);
	$totalData = count($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
	
	$sql = 'SELECT * FROM '.$service_finder_Tables->mp_payout_history.' WHERE `provider_id` = '.$user_id;

	if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql.=" AND ( booking_id LIKE '".$requestData['search']['value']."%' ";    
		$sql.=" OR amount LIKE '".$requestData['search']['value']."%' ";
		$sql.=" OR payout_id LIKE '".$requestData['search']['value']."%' ";
		$sql.=" OR created LIKE '".$requestData['search']['value']."%' ";
		$sql.=" OR status LIKE '".$requestData['search']['value']."%' )";
	}
	
	$query=$wpdb->get_results($sql);
	$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

	$query=$wpdb->get_results($sql);
	
	$data = array();
	
	foreach($query as $result){
		$nestedData=array(); 
		$nestedData[] = '#'.$result->booking_id;
		$nestedData[] = $result->created;
		$nestedData[] = $result->payout_id;
		$nestedData[] = service_finder_money_format($result->amount);
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