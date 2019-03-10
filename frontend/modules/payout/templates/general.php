<?php
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/
?>
<?php
wp_enqueue_script('service_finder-js-payout');
wp_add_inline_script( 'service_finder-js-payout', '/*Declare global variable*/
var user_id = "'.$globalproviderid.'";', 'after' );

$accountinfo = service_finder_get_stripe_account_info($globalproviderid);

$first_name = (!empty($accountinfo->legal_entity->first_name)) ? $accountinfo->legal_entity->first_name : '';
$last_name = (!empty($accountinfo->legal_entity->last_name)) ? $accountinfo->legal_entity->last_name : '';
$dob[] = (!empty($accountinfo->legal_entity->dob->year)) ? $accountinfo->legal_entity->dob->year : '';
$dob[] = (!empty($accountinfo->legal_entity->dob)) ? $accountinfo->legal_entity->dob->month : '';
$dob[] = (!empty($accountinfo->legal_entity->dob)) ? $accountinfo->legal_entity->dob->day : '';
$address = (!empty($accountinfo->legal_entity->address->line1)) ? $accountinfo->legal_entity->address->line1 : '';
$city = (!empty($accountinfo->legal_entity->address->city)) ? $accountinfo->legal_entity->address->city : '';
$postal_code = (!empty($accountinfo->legal_entity->address->postal_code)) ? $accountinfo->legal_entity->address->postal_code : '';
$state = (!empty($accountinfo->legal_entity->address->state)) ? $accountinfo->legal_entity->address->state : '';

if(!empty($dob[0]) && !empty($dob[1]) && !empty($dob[2])){
$dob = implode('-',$dob);
}else{
$dob = '';
}


echo service_finder_get_payout_status($accountinfo);
echo service_finder_get_fields_needed($accountinfo);
?>

<form class="payout-general" method="post">
  <div class="panel panel-default about-me-here">
    <div class="panel-heading sf-panel-heading">
      <h3 class="panel-tittle m-a0"><span class="fa fa-user"></span> <?php esc_html_e('General', 'service-finder'); ?> </h3>
    </div>
    <div class="panel-body sf-panel-body padding-30">
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('First Name', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-user"></i>
              <input type="text" class="form-control sf-form-control" name="first_name" value="<?php echo esc_attr($first_name) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Last Name', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-user"></i>
              <input type="text" class="form-control sf-form-control" name="last_name" value="<?php echo esc_attr($last_name) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Date of Birth', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control payout_customer_dob" name="dob" value="<?php echo (!empty($dob)) ? esc_attr($dob) : ''; ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Address', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control" name="address" value="<?php echo esc_attr($address) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Postal Code', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control" name="postal_code" value="<?php echo esc_attr($postal_code) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('City', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control" name="city" value="<?php echo esc_attr($city) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('State', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control" name="state" value="<?php echo esc_attr($state) ?>">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="sf-submit-payout">
  	<input type="submit" class="btn btn-primary margin-r-10" value="<?php esc_html_e('Submit information', 'service-finder'); ?>" />
  </div>
  </form>