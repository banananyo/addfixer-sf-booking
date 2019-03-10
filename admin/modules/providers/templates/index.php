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
<?php
$service_finder_options = get_option('service_finder_options');
$providerreplacestring = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');	
?>
<!--Template for providers in admin panel-->

<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php echo esc_html($providerreplacestring); ?>
    </h2>
  </div>
  <div class="sf-by-provider"> <?php echo esc_html__( 'By Featured', 'service-finder' ); ?> -
    <select class="sf-select-box form-control sf-form-control" name="byfeatured" id="byfeatured">
    <?php
	echo '<option value="">'.esc_html__( 'All ', 'service-finder' ).esc_html($providerreplacestring).'</option>';
	echo '<option value="1">'.esc_html__( 'Featured ', 'service-finder' ).esc_html($providerreplacestring).'</option>';
	?>
    </select>
    <?php echo esc_html__( 'By Approval', 'service-finder' ); ?> -
    <select class="sf-select-box form-control sf-form-control" name="byapproval" id="byapproval">
    <?php
	echo '<option value="">'.esc_html__( 'All ', 'service-finder' ).esc_html($providerreplacestring).'</option>';
	echo '<option value="need-approval">'.esc_html__( 'Need Approval', 'service-finder' ).'</option>';
	?>
    </select>
    <?php if( class_exists( 'WC_Vendors' ) && class_exists( 'WooCommerce' ) ) { ?>
    <div class="aon-bulk-vendor"><a href="javascript:;" class="btn btn-primary mekeitvendors"><?php echo esc_html__( 'All Providers Make Vendors', 'service-finder' ); ?></a></div>
    <?php } ?>
  </div>
  <input type="hidden" name="minvalue" id="minvalue" value="<?php echo (!empty($service_finder_options['feature-min-max-days'][1])) ? esc_attr($service_finder_options['feature-min-max-days'][1]) : '' ?>" >
  <input type="hidden" name="maxvalue" id="maxvalue" value="<?php echo (!empty($service_finder_options['feature-min-max-days'][2])) ? esc_attr($service_finder_options['feature-min-max-days'][2]) : '' ?>" >
  <div class="sf-column-names">
    <?php esc_html_e( 'Toggle column:', 'service-finder' ); ?>
    <a data-column="1" class="toggle-vis">
    <?php esc_html_e( 'Name', 'service-finder' ); ?>
    </a> - <a data-column="2" class="toggle-vis">
    <?php esc_html_e( 'Phone', 'service-finder' ); ?>
    </a> - <a data-column="3" class="toggle-vis">
    <?php esc_html_e( 'Email', 'service-finder' ); ?>
    </a> - <a data-column="4" class="toggle-vis">
    <?php esc_html_e( 'City', 'service-finder' ); ?>
    </a> - 
    <a href="<?php echo SERVICE_FINDER_BOOKING_LIB_URL.'/export.php'; ?> " class="btn btn-primary"><?php esc_html_e( 'Export Bank Details', 'service-finder' ); ?></a>
    </div>
  <div class="table-responsive">
    <table id="providers-grid" class="table table-bordered table-striped"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
        <th><input type="checkbox"  id="bulkProvidersDelete"  />
          <button id="deleteProvidersTriger" class="btn btn-danger btn-xs">
          <?php esc_html_e( 'Delete', 'service-finder' ); ?>
          </button></th>
        <th><?php esc_html_e( 'Name', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Phone', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Email', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Membership Status', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Membership Plan', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Membership Date', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'City', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Category', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Featured', 'service-finder' ); ?></th>
        <?php if(service_finder_check_wallet_system()){ ?>
        <th><?php esc_html_e( 'Wallet', 'service-finder' ); ?></th>
        <?php } ?>
        <th><?php esc_html_e( 'View Profile', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Bank Details', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Type', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Method', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Wired Invoice ID', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Txn ID', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Action', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Order Details', 'service-finder' ); ?></th>
      </tr>
    </thead>
    </table>
  </div>
</div>
