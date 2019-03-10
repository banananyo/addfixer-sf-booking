<?php 
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!--Template for dispaly featured requests-->
<?php

$wpdb = service_finder_plugin_global_vars('wpdb');
$current_user = service_finder_plugin_global_vars('current_user');
$service_finder_Tables = service_finder_plugin_global_vars('service_finder_Tables');
$service_finder_options = get_option('service_finder_options');
$providerreplacestring = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');

$limit = 15;

$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d AND `read` = "no" ORDER BY id DESC',2));
$count = count($res);
if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d ORDER BY id DESC LIMIT %d',2,$limit));
}
$html = '';
if(!empty($res)){
	$html .= '<ul class="dropdown-menu arrow-up sf-notification-list">';
	foreach($res as $row){
	if($row->read == 'yes'){
		$class = 'sf-read';
	}else{
		$class = 'sf-unread';
	}
	$html .= '<li class="'.$class.'"><a href="javascript:;">'.$row->topic.': '.$row->notice.'</a></li>';
	}
	$html .= '</ul>';
}

echo $html;
?>


