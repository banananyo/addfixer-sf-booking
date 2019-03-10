<?php
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/

/*Load cities ajax call when country change*/
add_action('wp_ajax_load_cities', 'service_finder_load_cities');

add_action('wp_ajax_nopriv_load_cities', 'service_finder_load_cities');



function service_finder_load_cities(){

global $wpdb;

echo service_finder_getCities($_POST['country']);

exit;

}