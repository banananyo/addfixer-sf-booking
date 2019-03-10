<?php
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/

/**
* Add the stylesheet
*/
function service_finder_base_scripts() {
 
	
}
add_action( 'wp_enqueue_scripts', 'service_finder_base_scripts' );


//Action hook to call shortcodes
add_action( 'init', 'service_finder_base_shortcodes');

function service_finder_base_shortcodes() {
	
	/* Claim Business Payment */
	function service_finder_claimbusiness_payment($atts, $content = null)
	{
			$html = '';
			require SERVICE_FINDER_BOOKING_TEMPLATES_DIR . '/claim-business-payment.php';
			
			return $html;
	}
	add_shortcode( 'service_finder_claimbusiness_payment', 'service_finder_claimbusiness_payment' );

	/* Search Form */
	function service_finder_search_form($atts, $content = null)
	{
			require SERVICE_FINDER_BOOKING_FRONTEND_MODULE_DIR . '/search/templates/search-form.php';
			
			return $html;
	}
	add_shortcode( 'service_finder_search_form', 'service_finder_search_form' );
	
	/* My Account Page */
	function service_finder_MyAccount($atts, $content = null)
	{
			require SERVICE_FINDER_BOOKING_FRONTEND_MODULE_DIR . '/myaccount/MyAccount.php';
			require SERVICE_FINDER_BOOKING_FRONTEND_MODULE_DIR . '/myservices/MyService.php';
			require SERVICE_FINDER_BOOKING_FRONTEND_MODULE_DIR . '/availability/Availability.php';
			require SERVICE_FINDER_BOOKING_FRONTEND_MODULE_DIR . '/business-hours/BusinessHours.php';
			require SERVICE_FINDER_BOOKING_FRONTEND_DIR . '/my-profile.php';
	}
	add_shortcode( 'service_finder_my_account', 'service_finder_MyAccount' );
	
	/* User Registration */
	function service_finder_signupform($atts, $content = null)
	{
			
			$a = shortcode_atts( array(
	
			   'role' => 'both',
	
			), $atts );
			
			require SERVICE_FINDER_BOOKING_FRONTEND_DIR . '/signup.php';
			
			return $html;
			
	}
	add_shortcode( 'service_finder_signup', 'service_finder_signupform' );
	
	/* User Login */
	function service_finder_loginform($atts, $content = null)
	{
			require SERVICE_FINDER_BOOKING_FRONTEND_DIR . '/login.php';
			
			return $html;
	}
	add_shortcode( 'service_finder_login', 'service_finder_loginform' );
	
	/* Success Page */
	function service_finder_SuccessMessage($atts, $content = null)
	{
			
			$html = '';
			require SERVICE_FINDER_BOOKING_TEMPLATES_DIR . '/success.php';
			
			return $html;
	}
	add_shortcode( 'service_finder_success_message', 'service_finder_SuccessMessage' );
	
	/* Thank You Page */
	function service_finder_ThankYou($atts, $content = null)
	{
			
			require SERVICE_FINDER_BOOKING_TEMPLATES_DIR . '/thank-you.php';
			
			return $html;
			
	}
	add_shortcode( 'service_finder_thank_you', 'service_finder_ThankYou' );
	
	/* Fronten Map Search*/
	function service_finder_sedateMapSearch($atts, $content = null)
	{
	global $service_finder_options;
	
	if(is_home() || is_front_page()){
	$height = '';
	$mapclass = 'gmap_home';
	}else{
	if($service_finder_options['search-template'] == 'style-1'){
	$height = 'style="height:600px"';
	$mapclass = '';
	}elseif($service_finder_options['search-template'] == 'style-2'){
	$height = 'style="height:100%"';
	$mapclass = '';
	}else{
	$height = 'style="height:600px"';
	$mapclass = '';
	}
	
	}
	
	$html = '<!-- Google Map -->
	<div id="gmap_wrapper" class="'.$mapclass.'" data-post_id="661" data-cur_lat="0" data-cur_long="0"  '.$height.'  >
		<span id="isgooglemap" data-isgooglemap="1"></span>       
	   
		<div id="gmap-controls-wrapper">
			<div id="gmapzoomplus"><i class="fa fa-plus"></i></div>
			<div id="gmapzoomminus"><i class="fa fa-minus"></i></div>
			<div  id="gmap-full"><i class="fa fa-arrows-alt"></i></div>
			<div  id="gmap-prev"><i class="fa fa-arrow-left"></i></div>
			<div  id="gmap-next"><i class="fa fa-arrow-right"></i></div>
		</div>
		<div id="googleMap" class="'.$mapclass.'" '.$height.'>   
		</div>    
	   <div class="tooltip"> click to enable zoom</div>
	   <div id="gmap-loading">     
			<div class="loader-inner ball-pulse"  id="listing_loader_maps">
				<div class="double-bounce1"></div>
				<div class="double-bounce2"></div>
			</div>
	   </div>
	  
	</div>    
	<!-- END Google Map --> ';		
	
	$defaultcity = (!empty($service_finder_options['default-city'])) ? $service_finder_options['default-city'] : '';
	if(!empty($service_finder_options['default-city'])){
	$default = service_finder_getLatLong(str_replace(" ","+",$defaultcity));
	$defaultlat = (!empty($default['lat'])) ? $default['lat'] : '';
	$defaultlng = (!empty($default['lng'])) ? $default['lng'] : '';
	}else{
	$defaultlat = '28.6430536';
	$defaultlng = '77.2223442';
	}
	
	$defaults = array('general_latitude'=>$defaultlat,'general_longitude'=>$defaultlng,'path'=>'','idx_status'=>'0','page_custom_zoom'=>'12','markers'=>'','generated_pins'=>'0');
	
	$attr = shortcode_atts( $defaults, $atts );
	
	$imagepath = SERVICE_FINDER_BOOKING_IMAGE_URL.'/markers';
	
	$administrativeColor = (!empty($service_finder_options['map-color-administrative'])) ? $service_finder_options['map-color-administrative'] : '#0088ff';
	$landscapeColor = (!empty($service_finder_options['map-color-landscape'])) ? $service_finder_options['map-color-landscape'] : '#ff0000';
	$poiColor = (!empty($service_finder_options['map-color-poi-government'])) ? $service_finder_options['map-color-poi-government'] : '#aaaaaa';
	$roadGeometryColor = (!empty($service_finder_options['map-color-road-geometry'])) ? $service_finder_options['map-color-road-geometry'] : '#f0ece9';
	$roadLabelsColor = (!empty($service_finder_options['map-color-road-labels'])) ? $service_finder_options['map-color-road-labels'] : '#ccdca1';
	$waterAllColor = (!empty($service_finder_options['map-color-water-all'])) ? $service_finder_options['map-color-water-all'] : '#767676';
	$waterGeometryColor = (!empty($service_finder_options['map-color-water-geometry'])) ? $service_finder_options['map-color-water-geometry'] : '#ffffff';
	$waterLabelsColor = (!empty($service_finder_options['map-color-water-labels'])) ? $service_finder_options['map-color-water-labels'] : '#b8cb93';
	
	wp_add_inline_script( 'service_finder-js-gmapfunctions', 'var mapfunctions_vars = {"path":"'.esc_js($imagepath).'","pin_images":"{}","geolocation_radius":"1000","adv_search":"","in_text":"","zoom_cluster":"12","user_cluster":"yes","generated_pins":"0","geo_no_pos":"The browser couldn\'t detect your position!","geo_no_brow":"Geolocation is not supported by this browser.","map_style":"[{\"featureType\":\"water\",\"stylers\":[{\"saturation\":43},{\"lightness\":-11},{\"hue\":\"'.esc_js($administrativeColor).'\"}]},{\"featureType\":\"road\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"hue\":\"'.esc_js($landscapeColor).'\"},{\"saturation\":-100},{\"lightness\":99}]},{\"featureType\":\"road\",\"elementType\":\"geometry.stroke\",\"stylers\":[{\"color\":\"'.esc_js($poiColor).'\"},{\"lightness\":54}]},{\"featureType\":\"landscape.man_made\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"color\":\"'.esc_js($roadGeometryColor).'\"}]},{\"featureType\":\"poi.park\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"color\":\"'.esc_js($roadLabelsColor).'\"}]},{\"featureType\":\"road\",\"elementType\":\"labels.text.fill\",\"stylers\":[{\"color\":\"'.esc_js($waterAllColor).'\"}]},{\"featureType\":\"road\",\"elementType\":\"labels.text.stroke\",\"stylers\":[{\"color\":\"'.esc_js($waterGeometryColor).'\"}]},{\"featureType\":\"poi\",\"stylers\":[{\"visibility\":\"off\"}]},{\"featureType\":\"landscape.natural\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"visibility\":\"on\"},{\"color\":\"'.esc_js($waterLabelsColor).'\"}]},{\"featureType\":\"poi.park\",\"stylers\":[{\"visibility\":\"on\"}]},{\"featureType\":\"poi.sports_complex\",\"stylers\":[{\"visibility\":\"on\"}]},{\"featureType\":\"poi.medical\",\"stylers\":[{\"visibility\":\"on\"}]},{\"featureType\":\"poi.business\",\"stylers\":[{\"visibility\":\"simplified\"}]}]"};', 'before' );
			  
	return $html;	
	}
	add_shortcode( 'service_finder_map_search', 'service_finder_sedateMapSearch' );
	
	/*General Map*/
	function service_finder_sedateMap($atts, $content = null)
	{
	global $service_finder_options;
	
	$defaults = array('general_latitude'=>$atts['general_latitude'],'general_longitude'=>$atts['general_longitude'],'path'=>'','idx_status'=>'0','page_custom_zoom'=>'12','markers'=>'','generated_pins'=>'0');
	
	$attr = shortcode_atts( $defaults, $atts );
	
	$height = $atts['height'];
	
	$html = '<!-- Google Map -->
	<div id="gmap_wrapper"   data-post_id="661" data-cur_lat="0" data-cur_long="0"  style="height:'.$height.'"  >
		<span id="isgooglemap" data-isgooglemap="1"></span>       
	   
		<div id="gmap-controls-wrapper">
			<div id="gmapzoomplus"><i class="fa fa-plus"></i></div>
			<div id="gmapzoomminus"><i class="fa fa-minus"></i></div>
			<div  id="gmap-full"><i class="fa fa-arrows-alt"></i></div>
		</div>
		<div id="googleMap"  style="height:'.$height.'">   
		</div>    
	   <div class="tooltip"> click to enable zoom</div>
	   <div id="gmap-loading">     
			<div class="loader-inner ball-pulse"  id="listing_loader_maps">
				<div class="double-bounce1"></div>
				<div class="double-bounce2"></div>
			</div>
	   </div>
	  
	</div>    
	<!-- END Google Map --> ';		
	
	$imagepath = SERVICE_FINDER_BOOKING_IMAGE_URL.'/markers';
	
	$administrativeColor = (!empty($service_finder_options['map-color-administrative'])) ? $service_finder_options['map-color-administrative'] : '#0088ff';
	$landscapeColor = (!empty($service_finder_options['map-color-landscape'])) ? $service_finder_options['map-color-landscape'] : '#ff0000';
	$poiColor = (!empty($service_finder_options['map-color-poi-government'])) ? $service_finder_options['map-color-poi-government'] : '#aaaaaa';
	$roadGeometryColor = (!empty($service_finder_options['map-color-road-geometry'])) ? $service_finder_options['map-color-road-geometry'] : '#f0ece9';
	$roadLabelsColor = (!empty($service_finder_options['map-color-road-labels'])) ? $service_finder_options['map-color-road-labels'] : '#ccdca1';
	$waterAllColor = (!empty($service_finder_options['map-color-water-all'])) ? $service_finder_options['map-color-water-all'] : '#767676';
	$waterGeometryColor = (!empty($service_finder_options['map-color-water-geometry'])) ? $service_finder_options['map-color-water-geometry'] : '#ffffff';
	$waterLabelsColor = (!empty($service_finder_options['map-color-water-labels'])) ? $service_finder_options['map-color-water-labels'] : '#b8cb93';
	
	wp_add_inline_script( 'service_finder-js-gmapfunctions', 'var mapfunctions_vars = {"path":"'.esc_js($imagepath).'","pin_images":"{}","geolocation_radius":"1000","adv_search":"","in_text":"","zoom_cluster":"12","user_cluster":"yes","generated_pins":"0","geo_no_pos":"The browser couldn\'t detect your position!","geo_no_brow":"Geolocation is not supported by this browser.","map_style":"[{\"featureType\":\"water\",\"stylers\":[{\"saturation\":43},{\"lightness\":-11},{\"hue\":\"'.esc_js($administrativeColor).'\"}]},{\"featureType\":\"road\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"hue\":\"'.esc_js($landscapeColor).'\"},{\"saturation\":-100},{\"lightness\":99}]},{\"featureType\":\"road\",\"elementType\":\"geometry.stroke\",\"stylers\":[{\"color\":\"'.esc_js($poiColor).'\"},{\"lightness\":54}]},{\"featureType\":\"landscape.man_made\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"color\":\"'.esc_js($roadGeometryColor).'\"}]},{\"featureType\":\"poi.park\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"color\":\"'.esc_js($roadLabelsColor).'\"}]},{\"featureType\":\"road\",\"elementType\":\"labels.text.fill\",\"stylers\":[{\"color\":\"'.esc_js($waterAllColor).'\"}]},{\"featureType\":\"road\",\"elementType\":\"labels.text.stroke\",\"stylers\":[{\"color\":\"'.esc_js($waterGeometryColor).'\"}]},{\"featureType\":\"poi\",\"stylers\":[{\"visibility\":\"off\"}]},{\"featureType\":\"landscape.natural\",\"elementType\":\"geometry.fill\",\"stylers\":[{\"visibility\":\"on\"},{\"color\":\"'.esc_js($waterLabelsColor).'\"}]},{\"featureType\":\"poi.park\",\"stylers\":[{\"visibility\":\"on\"}]},{\"featureType\":\"poi.sports_complex\",\"stylers\":[{\"visibility\":\"on\"}]},{\"featureType\":\"poi.medical\",\"stylers\":[{\"visibility\":\"on\"}]},{\"featureType\":\"poi.business\",\"stylers\":[{\"visibility\":\"simplified\"}]}]"};', 'before' );
			  
	return $html;	
	}
	add_shortcode( 'service_finder_map', 'service_finder_sedateMap' );
} 

/*Notification Bar*/
if ( !function_exists( 'service_finder_notification_bar' ) ){
function service_finder_notification_bar( ) {
global $wpdb, $service_finder_Tables, $current_user;

if(is_user_logged_in()){
$html  = '<ul class="login-bx  list-inline">';
$html .= '<li>';
if(service_finder_getUserRole($current_user->ID) == 'Provider'){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d AND `read` = "no" ORDER BY id DESC',$current_user->ID));
$count = count($res);
$html .= '<a href="javascript:;" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" data-usertype="Provider" data-userid="'.$current_user->ID.'" class="dropdown-toggle sf-notifications" aria-expanded="false"><i class="fa fa-bell"></i> <span>'.esc_attr($count).'</span></a>';
if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d ORDER BY id DESC LIMIT 5',$current_user->ID));
}
}elseif(service_finder_getUserRole($current_user->ID) == 'Customer'){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `customer_id` = %d AND `read` = "no" ORDER BY id DESC',$current_user->ID));
$count = count($res);
$html .= '<a href="javascript:;" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" data-usertype="Customer" data-userid="'.$current_user->ID.'" class="dropdown-toggle sf-notifications" aria-expanded="false"><i class="fa fa-bell"></i> <span>'.esc_attr($count).'</span></a>';
if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `customer_id` = %d ORDER BY id DESC LIMIT 5',$current_user->ID));
}
}
if(!empty($res)){
	$html .= '<ul class="dropdown-menu arrow-up sf-notification-list">';
	foreach($res as $row){
	if($row->read == 'yes'){
		$class = 'sf-read';
	}else{
		$class = 'sf-unread';
	}
	
	$url = service_finder_get_notification_link($row->topic,$row->target_id);
	
	$html .= '<li class="'.$class.'"><a href="'.esc_url($url).'">'.$row->topic.': '.$row->notice.'</a></li>';
	}
	$html .= '</ul>';
}
$html .= '</li>';
$html .= '</ul>';
}else{
$html = '';
}

return $html;
}
add_shortcode( 'service_finder_notification_bar', 'service_finder_notification_bar' );
}

/*Notification Bar*/
if ( !function_exists( 'service_finder_myaccount_notification_bar' ) ){
function service_finder_myaccount_notification_bar( ) {
global $wpdb, $service_finder_Tables, $current_user;

if(is_user_logged_in()){
$html = '<li class="header-widget dropdown">';
if(service_finder_getUserRole($current_user->ID) == 'Provider'){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d AND `read` = "no" ORDER BY id DESC',$current_user->ID));
$count = count($res);

$html .= '<div data-delay="1000" data-hover="dropdown" data-toggle="dropdown" data-usertype="Provider" data-userid="'.$current_user->ID.'" class="aon-admin-notification dropdown-toggle sf-notifications" aria-expanded="false"><i class="fa fa-bell"></i> <span>'.esc_attr($count).'</span></div>';
								
if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d ORDER BY id DESC LIMIT 5',$current_user->ID));
}
}elseif(service_finder_getUserRole($current_user->ID) == 'Customer'){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `customer_id` = %d AND `read` = "no" ORDER BY id DESC',$current_user->ID));
$count = count($res);

$html .= '<div data-delay="1000" data-hover="dropdown" data-toggle="dropdown" data-usertype="Customer" data-userid="'.$current_user->ID.'" class="aon-admin-notification dropdown-toggle sf-notifications" aria-expanded="false"><i class="fa fa-bell"></i> <span>'.esc_attr($count).'</span></div>';

if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `customer_id` = %d ORDER BY id DESC LIMIT 5',$current_user->ID));
}
}

if(!empty($res)){
	$html .= '<ul class="dropdown-menu arrow-up sf-notification-list">';
	foreach($res as $row){
	if($row->read == 'yes'){
		$class = 'sf-read';
	}else{
		$class = 'sf-unread';
	}
	
	$url = service_finder_get_notification_link($row->topic,$row->target_id);
	
	$html .= '<li class="'.$class.'"><a href="'.esc_url($url).'">'.$row->topic.': '.$row->notice.'</a></li>';
	}
	$html .= '</ul>';
}
$html .= '</li>';
}else{
$html = '';
}

return $html;
}
add_shortcode( 'service_finder_myaccount_notification_bar', 'service_finder_myaccount_notification_bar' );
} 

/*Notification Bar for Top Bar*/
if ( !function_exists( 'service_finder_notification_notopbar' ) ){
function service_finder_notification_notopbar( ) {
global $wpdb, $service_finder_Tables, $current_user;

if(is_user_logged_in()){
$html  = '<div class="extra-cell">';
if(service_finder_getUserRole($current_user->ID) == 'Provider'){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d AND `read` = "no" ORDER BY id DESC',$current_user->ID));
$count = count($res);
$html .= '<a href="javascript:;" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" data-usertype="Provider" data-userid="'.$current_user->ID.'" class="dropdown-toggle btn btn-border btn-sm" aria-expanded="false"><i class="fa fa-bell"></i> <span>'.esc_attr($count).'</span></a>';
if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `provider_id` = %d ORDER BY id DESC LIMIT 5',$current_user->ID));
}
}elseif(service_finder_getUserRole($current_user->ID) == 'Customer'){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `customer_id` = %d AND `read` = "no" ORDER BY id DESC',$current_user->ID));
$count = count($res);
$html .= '<a href="javascript:;" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" data-usertype="Customer" data-userid="'.$current_user->ID.'" class="dropdown-toggle btn btn-border btn-sm" aria-expanded="false"><i class="fa fa-bell"></i> <span>'.esc_attr($count).'</span></a>';
if($count == 0){
$res = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->notifications.' WHERE `customer_id` = %d ORDER BY id DESC LIMIT 5',$current_user->ID));
}
}
if(!empty($res)){
	$html .= '<ul class="dropdown-menu arrow-up sf-notifications">';
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
$html .= '</div>';
}else{
$html = '';
}

return $html;
}
add_shortcode( 'service_finder_notification_notopbar', 'service_finder_notification_notopbar' );
} 