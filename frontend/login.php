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
get_header();
$wpdb = service_finder_plugin_global_vars('wpdb');
$service_finder_Params = service_finder_plugin_global_vars('service_finder_Params');
$service_finder_options = get_option('service_finder_options');


wp_add_inline_script( 'service_finder-js-registration', '/*Declare global variable*/
var formtype = "login"; var selectedpackage;', 'after' );
?>
<!-- Login Template -->

 
<?php 
$social_login = '';
if(function_exists('wsl_shortcode_wordpress_social_login')){
$social_login = wsl_shortcode_wordpress_social_login();
}
		
$html = '';
		$display_title = get_post_meta(get_the_id(), '_display_title', true);
        if(!is_user_logged_in()){
        $html .= '<div class="padding-20  margin-b-30  bg-white sf-rouned-box clearfix">
		<div class="loginform_dx row">';
          $html .= '<form class="loginform_page" method="post">
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group"> <i class="input-group-addon fa fa-user"></i>
                  <input name="login_user_name" type="text" class="form-control" placeholder="'.esc_html__('Username', 'service-finder').'">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group"> <i class="input-group-addon fa fa-lock"></i>
                  <input name="login_password" type="password" class="form-control" placeholder="'.esc_html__('Password', 'service-finder').'">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" name="user-login" value="'.esc_html__('Login', 'service-finder').'" />
              </div>
            </div>
            <div class="col-md-12 text-center"> <small><a href="'.esc_url(home_url('/signup/')).'" >
              '.esc_html__("Don't have an account?", 'service-finder').'
              </a> | <a href="javascript:;" class="fp_bx">
              '.esc_html__('Forgot Password', 'service-finder').'
              </a></small> </div>
          </form>';
		$html .= $social_login;  
        $html .= '</div></div>';
		
        $html .= '<div class="forgotpasswordform_dx hidden">';
        if(class_exists( 'ReduxFrameworkPlugin' )){
		if($service_finder_options['show-page-title']){
		if($display_title){ 
			$html .= '<h4>'.esc_html__('Forgot Password','service-finder').'</h4>';
		}
		}
		}else{
			$html .= '<h4>'.esc_html__('Forgot Password','service-finder').'</h4>';
		}
          $html .= '<form class="forgotpassform_page" method="post">
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group"> <i class="input-group-addon fa fa-user"></i>
                  <input name="fp_user_login" type="text" class="form-control" placeholder="'.esc_html__('Username or E-mail:', 'service-finder').'">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <input type="hidden" name="action" value="resetpass" />
                <input type="submit" class="btn btn-primary btn-block" name="user-login" value="'.esc_html__('Get New Password', 'service-finder').'" />
              </div>
            </div>
            <div class="col-md-12 text-center"> <small><a href="'.esc_url(home_url('/signup/')).'" class="regform">
              '.esc_html__("Don't have an account?", 'service-finder').'
              </a> | <a href="javascript:;" class="lg_bx">
              '.esc_html__('Already Registered?', 'service-finder').'
              </a></small> </div>
          </form>';
        $html .= '</div>';
        }else{ 
          $html .= '<p>'.esc_html__('You are already logged in.','service-finder').'</p>';
		  } 
?>
