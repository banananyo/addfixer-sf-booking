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
$service_finder_options = get_option('service_finder_options');
$claimbusiness = (!empty($service_finder_options['string-claim-business'])) ? $service_finder_options['string-claim-business'] : esc_html__('Claim Business', 'service-finder');
$providerreplacestring = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');
$customerreplacestring = (!empty($service_finder_options['customer-replace-string'])) ? $service_finder_options['customer-replace-string'] : esc_html__('Customers', 'service-finder');	
?>
<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php echo esc_html($claimbusiness); ?>
    </h2>
  </div>
  
  <div class="table-responsive">
    <table id="claim-grid" class="table table-bordered table-striped" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
      	<th><input type="checkbox"  id="bulkAdminClaimDelete"  />
          <button id="deleteAdminClaimTriger" class="btn btn-danger btn-xs">
          <?php esc_html_e( 'Delete', 'service-finder' ); ?>
        </button></th>
        <th><?php echo esc_html($providerreplacestring).' '.esc_html__( 'Name', 'service-finder' ); ?></th>
        <th><?php echo esc_html($customerreplacestring).' '.esc_html__( 'Name', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Date', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Email', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Message', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Wired Invoice ID', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Status', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Type', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Payment Method', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'View Profile', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Action', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Order Details', 'service-finder' ); ?></th>
      </tr>
    </thead>
    </table>
  </div>
</div>
