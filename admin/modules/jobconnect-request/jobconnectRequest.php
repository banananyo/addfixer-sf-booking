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
class SERVICE_FINDER_jobconnectRequest extends SERVICE_FINDER_sedateManager{

	
	/*Initial Function*/
	public function service_finder_index()
    {
        
		/*Rander providers template*/
		$this->service_finder_render( 'index','jobconnect-request' );
		
		/*Action for wp ajax call*/
		$this->service_finder_registerWpActions();
		
    }
	
	/*Actions for wp ajax call*/
	protected function service_finder_registerWpActions() {
       $_this = $this;
	   add_action(
                    'wp_ajax_get_jobconnect_requests',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_get_jobconnect_requests' ) );
                    }
						
                );
		add_action(
                    'wp_ajax_approve_jobconnect_request',
					function () use ( $_this ) {
						call_user_func( array( $_this, 'service_finder_approve_jobconnect_request' ) );
                    }
						
                );	
    }
	
	public function service_finder_get_jobconnect_requests(){
		global $wpdb, $service_finder_Tables, $service_finder_options;
		$requestData= $_REQUEST;

		$requests = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'usermeta WHERE `meta_key` = "job_connect_request_status"');
		
		$columns = array( 
			0 =>'umeta_id', 
		);
		
		$totalData = count($requests);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		
		$sql = 'SELECT * FROM '.$wpdb->prefix.'usermeta WHERE `meta_key` = "job_connect_request_status"';

		$query=$wpdb->get_results($sql);
		$totalFiltered = count($query);
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

		$query=$wpdb->get_results($sql);
		
		$data = array();
		
		foreach($query as $result){
			$nestedData=array(); 
			$userid = $result->user_id;
			$requestdata = get_user_meta($userid,'job_connect_request',true);
			$row = $wpdb->get_row('SELECT * FROM '.$service_finder_Tables->job_limits.' WHERE `provider_id` = "'.$userid.'"');
			
			if(service_finder_getUserRole($userid) == 'Provider'){
				$nestedData[] = service_finder_getProviderFullName($userid);
				$nestedData[] = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');
				$current_plan = $row->current_plan;
				$current_plan = (!empty($service_finder_options['plan'.$current_plan.'-name'])) ? $service_finder_options['plan'.$current_plan.'-name'] : '';
				
				$upgrade_plan = $requestdata['current_plan'];
				$upgrade_plan = (!empty($service_finder_options['plan'.$upgrade_plan.'-name'])) ? $service_finder_options['plan'.$upgrade_plan.'-name'] : '';
			}else{
				$nestedData[] = service_finder_getCustomerName($userid);
				$nestedData[] = (!empty($service_finder_options['customer-replace-string'])) ? $service_finder_options['customer-replace-string'] : esc_html__('Customer', 'service-finder');	
				$current_plan = $row->current_plan;
				$current_plan = (!empty($service_finder_options['job-post-plan'.$current_plan.'-name'])) ? $service_finder_options['job-post-plan'.$current_plan.'-name'] : '';
				
				$upgrade_plan = $requestdata['current_plan'];
				$upgrade_plan = (!empty($service_finder_options['job-post-plan'.$upgrade_plan.'-name'])) ? $service_finder_options['job-post-plan'.$upgrade_plan.'-name'] : '';
			}
			$nestedData[] = date('Y-m-d',strtotime($requestdata['date']));
			$nestedData[] = $current_plan;
			$nestedData[] = $upgrade_plan;
			$nestedData[] = service_finder_currencysymbol().$requestdata['planprice'];
			
			if($row->payment_type == 'woocommerce' && ($row->payment_method == 'bacs' || $row->payment_method == 'cheque')){
			$nestedData[] = $row->txn_id;
			}elseif($row->payment_type == 'woocommerce' && $row->payment_method != 'bacs' && $row->payment_method != 'cheque'){
			$nestedData[] = '-';
			}elseif($row->payment_type == 'local' && $row->payment_method == 'wire-transfer'){
			$nestedData[] = ($requestdata['wired_invoiceid'] != "") ? $requestdata['wired_invoiceid'] : '';
			}else{
			$nestedData[] = '-';
			}
			
			$nestedData[] = ($row->payment_type == 'woocommerce') ? esc_html__('Woocommerce','service-finder') : esc_html__('Local','service-finder');
			$nestedData[] = service_finder_translate_static_status_string($row->payment_method);
			if(service_finder_getUserRole($userid) == 'Provider'){
			$nestedData[] = '<a href="'.esc_url(service_finder_get_author_url($userid)).'" class="btn btn-primary btn-xs" target="_blank">'.esc_html__('View Profile', 'service-finder').'</a>';
			}else{
			$nestedData[] = '-';
			}

			if($row->payment_status == 'on-hold' && $row->payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$row->txn_id.'&action=edit" target="_blank">'.esc_html__('Approve', 'service-finder').'</a>';
			}elseif($result->meta_value == 'pending' && $row->payment_type != 'woocommerce'){
				$nestedData[] = '<a href="javascript:;" data-id="'.esc_attr($userid).'" class="app_jobconnect_request">'.esc_html__('Approve', 'service-finder').'</a>';
			}else{
				if($row->payment_type == 'woocommerce'){
					$nestedData[] = service_finder_translate_static_status_string($row->payment_status);
				}else{
					$nestedData[] = esc_html__( 'Paid', 'service-finder' );
				}
			}
			
			if($row->payment_type == 'woocommerce'){
				$nestedData[] = '<a href="'.admin_url().'post.php?post='.$row->txn_id.'&action=edit" target="_blank">'.esc_html__('View Order', 'service-finder').'</a>';
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
	
	public function service_finder_approve_jobconnect_request(){
	global $wpdb, $service_finder_Tables;
	
	$userid = (isset($_POST['uid'])) ? esc_html($_POST['uid']) : '';
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->job_limits.' WHERE `provider_id` = %d',$userid));

	$requestdata = get_user_meta($userid,'job_connect_request',true);
		
	$data = array(
			'paid_limits' => $requestdata['paid_limits'],
			'available_limits' => $requestdata['available_limits'],
			'payment_method' => 'wire-transfer',
			'payment_status' => 'paid',
			'current_plan' => $requestdata['current_plan'],
			);
	$where = array(
			'provider_id' => $userid
	);
	$res = $wpdb->update($service_finder_Tables->job_limits,wp_unslash($data),$where);
	
	if(!empty($row)){
	$paydate = date('Y-m-d h:i:s');
	$txndata = array(
			'provider_id' => $userid,
			'payment_date' => $paydate,
			'txn_id' => $requestdata['wired_invoiceid'],
			'plan' => $requestdata['current_plan'],
			'amount' => $requestdata['planprice'],
			'limit' => $requestdata['limit'],
			'payment_method' => 'wire-transfer',
			'payment_status' => 'paid',
			);
	$where = array(
			'txn_id' => $row->txn_id
	);
	$res = $wpdb->update($service_finder_Tables->transaction,wp_unslash($txndata),$where);
	}
	
	update_user_meta($userid, 'job_connect_request_status','approve');
	//delete_user_meta($userid, 'job_connect_request');
	
	if(service_finder_getUserRole($userid) == 'Provider'){
	send_mail_after_joblimit_connect_purchase( $userid );	
	}else{
	send_mail_after_jobpost_limit_connect_purchase( $userid );
	}
	
	$success = array(
			'status' => 'success',
			'suc_message' => esc_html__('Job connects upgrade successfully', 'service-finder'),
			);
	echo json_encode($success);
	
	exit(0);
	}
	
}