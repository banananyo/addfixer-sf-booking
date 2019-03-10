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
<!--Template for bookings in admin panel-->
<?php
$service_finder_options = get_option('service_finder_options');
$providerreplacestring = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');	
$customerreplacestring = (!empty($service_finder_options['customer-replace-string'])) ? $service_finder_options['customer-replace-string'] : esc_html__('Customers', 'service-finder');	
$admin_fee_label = (!empty($service_finder_options['admin-fee-label'])) ? esc_html($service_finder_options['admin-fee-label']) : esc_html__('Admin Fee', 'service-finder');
?>
<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php esc_html_e( 'Bookings', 'service-finder' ); ?>
    </h2>
  </div>
  <div class="sf-by-provider"> <?php echo esc_html__( 'By', 'service-finder' ).' '.esc_html($providerreplacestring); ?> -
    <select name="byprovider" id="byprovider" class="sf-select-box form-control sf-form-control">
      <?php
if(!empty($args)){
	echo '<option value="">'.esc_html__( 'All ', 'service-finder' ).esc_html($providerreplacestring).'</option>';
	foreach($args as $arg){
	echo '<option value="'.esc_attr($arg->wp_user_id).'">'.$arg->full_name.'</option>';
	}
}else{
	echo '<option value="">'.esc_html__( 'No Providers Found', 'service-finder' ).'</option>';
}
?>
    </select>
    <?php esc_html_e( 'By Date', 'service-finder' ); ?> -
    <select name="bydate" id="bydate" class="sf-select-box form-control sf-form-control">
      <option value="">
      <?php esc_html_e( 'All Days', 'service-finder' ); ?>
      </option>
      <option value="today">
      <?php esc_html_e( 'Today', 'service-finder' ); ?>
      </option>
      <option value="yesterday">
      <?php esc_html_e( 'Yesterday', 'service-finder' ); ?>
      </option>
      <option value="tomorrow">
      <?php esc_html_e( 'Tomorrow', 'service-finder' ); ?>
      </option>
      <option value="last_7">
      <?php esc_html_e( 'Last 7 Days', 'service-finder' ); ?>
      </option>
      <option value="last_30">
      <?php esc_html_e( 'Last 30 Days', 'service-finder' ); ?>
      </option>
      <option value="next_7">
      <?php esc_html_e( 'Next 7 Days', 'service-finder' ); ?>
      </option>
      <option value="this_month">
      <?php esc_html_e( 'This Month', 'service-finder' ); ?>
      </option>
      <option value="next_month">
      <?php esc_html_e( 'Next Month', 'service-finder' ); ?>
      </option>
    </select>
  </div>
  <div class="sf-column-names">
    <?php esc_html_e( 'Toggle column:', 'service-finder' ); ?>
    <a data-column="1" class="toggle-vis">
    <?php esc_html_e( 'Date', 'service-finder' ); ?>
    </a> - <a data-column="2" class="toggle-vis">
    <?php esc_html_e( 'Start Time', 'service-finder' ); ?>
    </a> - <a data-column="3" class="toggle-vis">
    <?php esc_html_e( 'End Time', 'service-finder' ); ?>
    </a> - <a data-column="4" class="toggle-vis">
    <?php echo esc_html($providerreplacestring).' '.esc_html__( 'Name', 'service-finder' ); ?>
    </a> - <a data-column="5" class="toggle-vis">
    <?php echo esc_html($providerreplacestring).' '.esc_html__( 'Phone', 'service-finder' ); ?>
    </a> - <a data-column="6" class="toggle-vis">
    <?php echo esc_html($providerreplacestring).' '.esc_html__( 'Email', 'service-finder' ); ?>
    </a> - <a data-column="7" class="toggle-vis">
    <?php echo esc_html($customerreplacestring).' '.esc_html__( 'Name', 'service-finder' ); ?>
    </a> - <a data-column="8" class="toggle-vis">
    <?php echo esc_html($customerreplacestring).' '.esc_html__( 'Phone', 'service-finder' ); ?>
    </a> - <a data-column="9" class="toggle-vis">
    <?php echo esc_html($customerreplacestring).' '.esc_html__( 'Email', 'service-finder' ); ?>
    </a> - <a data-column="10" class="toggle-vis">
    <?php echo esc_html($customerreplacestring).' '.esc_html__( 'Address', 'service-finder' ); ?>
    </a> - <a data-column="11" class="toggle-vis">
    <?php echo esc_html($customerreplacestring).' '.esc_html__( 'City', 'service-finder' ); ?>
    </a> - <a data-column="12" class="toggle-vis">
    <?php esc_html_e( 'Status', 'service-finder' ); ?>
    </a> - </div>
  <div class="table-responsive">
    <table id="admin-bookings-grid" class="table table-bordered table-striped"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
        <th><input type="checkbox"  id="bulkAdminBookingsDelete"  />
          <button id="deleteAdminBookingTriger" class="btn btn-danger btn-xs">
          <?php esc_html_e( 'Delete', 'service-finder' ); ?>
          </button></th>
        <th><?php esc_html_e( 'Booking Reference ID #', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Date', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Start Time', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'End Time', 'service-finder' ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Name', 'service-finder'), $providerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Phone', 'service-finder'), $providerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Email', 'service-finder'), $providerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Name', 'service-finder'), $customerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Phone', 'service-finder'), $customerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Email', 'service-finder'), $customerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s Address', 'service-finder'), $customerreplacestring ); ?></th>
        <th><?php echo sprintf( esc_html__('%s City', 'service-finder'), $customerreplacestring ); ?></th>
        <th><?php esc_html_e( 'Upcoming or Past', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Booking Status', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Type', 'service-finder' ); ?></th>
        <th><?php echo sprintf( esc_html__('Pay to %s via Bank Transffer', 'service-finder'), $providerreplacestring ); ?></th>
        <th><?php esc_html_e( 'Transaction ID', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Type', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Method', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Invoice ID (Wire Transfer)', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Status', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Booking Amount', 'service-finder' ); ?></th>
        <th><?php echo esc_html($admin_fee_label); ?></th>
        <th><?php echo esc_html($providerreplacestring).' '.esc_html__( 'Fee', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Generated Invoice', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Action for wire transfer', 'service-finder' ); ?></th>
        <th><?php echo sprintf( esc_html__('Pay to %s via Paypal/Stripe/Mangopay', 'service-finder'), $providerreplacestring ); ?></th>
        <th><?php esc_html_e( 'Order Details', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'View Booking', 'service-finder' ); ?></th>
      </tr>
    </thead>
    </table>
    <div id="booking-details" class="hidden"> </div>
  </div>
</div>
<!-- Loading area start -->
<div class="loading-area default-hidden">
  <div class="loading-box"></div>
  <div class="loading-pic"></div>
</div>
<!-- Loading area end -->