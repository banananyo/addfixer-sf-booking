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

$last4 = (!empty($accountinfo->external_accounts->data[0]->last4)) ? $accountinfo->external_accounts->data[0]->last4 : '';
$routing_number = (!empty($accountinfo->external_accounts->data[0]->routing_number)) ? $accountinfo->external_accounts->data[0]->routing_number : '';
$currency = (!empty($accountinfo->external_accounts->data[0]->currency)) ? $accountinfo->external_accounts->data[0]->currency : '';
$country = (!empty($accountinfo->external_accounts->data[0]->country)) ? $accountinfo->external_accounts->data[0]->country : '';

echo service_finder_get_payout_status($accountinfo);
echo service_finder_get_fields_needed($accountinfo);
?>

<form class="stripe-external-account" method="post">
  <div class="panel panel-default about-me-here">
    <div class="panel-heading sf-panel-heading">
      <h3 class="panel-tittle m-a0"><span class="fa fa-user"></span> <?php esc_html_e('External Account', 'service-finder'); ?> </h3>
    </div>
    <div class="panel-body sf-panel-body padding-30">
      <div class="row">
        <div class="col-lg-6">
        <label>
        <?php esc_html_e('Currency', 'service-finder'); ?>
        </label>
        <div class="form-group">
        <?php 
        $currencylist = service_finder_get_currency_list();
        ?>
          <select class="sf-select-box form-control sf-form-control" name="currency" data-live-search="true" title="<?php esc_html_e('Currency', 'service-finder'); ?>">
            <option value=""><?php esc_html_e('Currency', 'service-finder'); ?></option>
            <?php
            if(!empty($currencylist)){
                foreach($currencylist as $key => $value){
                    if($currency == strtolower($key)){
                    $select = 'selected="selected"';
                    }else{
                    $select = '';
                    }
                    echo '<option '.$select.' value="'.esc_attr(strtolower($key)).'">'.esc_html($value).'</option>';	
                }
            }
            ?>
          </select>  
        </div>
        </div>
        <div class="col-lg-6">
        <label>
        <?php esc_html_e('Bank Country', 'service-finder'); ?>
        </label>
        <div class="form-group">
        <?php 
        $countrylist = service_finder_get_country_list();
        ?>
          <select class="sf-select-box form-control sf-form-control" name="bank_country" data-live-search="true" title="<?php esc_html_e('Country', 'service-finder'); ?>">
            <option value=""><?php esc_html_e('Country', 'service-finder'); ?></option>
            <?php
            if(!empty($countrylist)){
                foreach($countrylist as $key => $value){
                    if($country == $key){
                    $select = 'selected="selected"';
                    }else{
                    $select = '';
                    }
                    echo '<option '.$select.' value="'.esc_attr($key).'">'.esc_html($value).'</option>';	
                }
            }
            ?>
          </select>  
        </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Routing Number', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control" name="routing_number" value="<?php echo esc_attr($routing_number) ?>">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Account Number', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-envelope"></i>
              <input type="text" class="form-control sf-form-control" name="account_number" value="<?php echo esc_attr($last4) ?>">
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