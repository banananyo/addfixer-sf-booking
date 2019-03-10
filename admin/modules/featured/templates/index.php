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
$providerreplacestring = (!empty($service_finder_options['provider-replace-string'])) ? $service_finder_options['provider-replace-string'] : esc_html__('Provider', 'service-finder');
?>
<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php esc_html_e( 'Featured Requests', 'service-finder' ); ?>
    </h2>
  </div>
  <div class="sf-column-names">
    <?php esc_html_e( 'Toggle column:', 'service-finder' ); ?>
    <a data-column="1" class="toggle-vis">
    <?php echo esc_html($providerreplacestring).' '.esc_html__( 'Name', 'service-finder' ); ?>
    </a> - <a data-column="2" class="toggle-vis">
    <?php esc_html_e( 'Category', 'service-finder' ); ?>
    </a> - <a data-column="3" class="toggle-vis">
    <?php esc_html_e( 'Number of Days', 'service-finder' ); ?>
    </a> - <a data-column="4" class="toggle-vis">
    <?php esc_html_e( 'View Profile', 'service-finder' ); ?>
    </a> - <a data-column="5" class="toggle-vis"<?php esc_html_e( 'Action', 'service-finder' ); ?>></a> </div>
  <div class="table-responsive">
    <table id="featured-requests-grid" class="table table-bordered table-striped" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
        <th><?php echo esc_html($providerreplacestring).' '.esc_html__( 'Name', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Category', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Number of Days', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Start Date', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'End Date', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Amount', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'View Profile', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Status', 'service-finder' ); ?></th>
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
