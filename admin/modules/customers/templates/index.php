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
<!--Template for customers in admin panel-->
<?php
$service_finder_options = get_option('service_finder_options');
$customerreplacestring = (!empty($service_finder_options['customer-replace-string'])) ? $service_finder_options['customer-replace-string'] : esc_html__('Customers', 'service-finder');	
?>
<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php echo esc_html( $customerreplacestring ); ?>
    </h2>
  </div>
  <div class="sf-column-names">
    <?php esc_html_e( 'Toggle column:', 'service-finder' ); ?>
    <a data-column="1" class="toggle-vis">
    <?php esc_html_e( 'Name', 'service-finder' ); ?>
    </a> - <a data-column="2" class="toggle-vis">
    <?php esc_html_e( 'Phone', 'service-finder' ); ?>
    </a> - <a data-column="3" class="toggle-vis">
    <?php esc_html_e( 'Alternate Phone', 'service-finder' ); ?>
    </a> - <a data-column="4" class="toggle-vis">
    <?php esc_html_e( 'Email', 'service-finder' ); ?>
    </a> - <a data-column="5" class="toggle-vis">
    <?php esc_html_e( 'Address', 'service-finder' ); ?>
    </a> - <a data-column="6" class="toggle-vis">
    <?php esc_html_e( 'Apt', 'service-finder' ); ?>
    </a> - <a data-column="7" class="toggle-vis">
    <?php esc_html_e( 'City', 'service-finder' ); ?>
    </a> - <a data-column="8" class="toggle-vis">
    <?php esc_html_e( 'State', 'service-finder' ); ?>
    </a> - <a data-column="9" class="toggle-vis">
    <?php esc_html_e( 'Postal Code', 'service-finder' ); ?>
    </a> - </div>
  <div class="table-responsive">
    <table id="customers-grid" class="table table-bordered table-striped"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
        <th><input type="checkbox"  id="bulkCustomersDelete"  />
          <button id="deleteCustomersTriger" class="btn btn-danger btn-xs">
          <?php esc_html_e( 'Delete', 'service-finder' ); ?>
          </button></th>
        <th><?php esc_html_e( 'Name', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Phone', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Altternate Phone', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Email', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Address', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Apt', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'City', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'State', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Postal Code', 'service-finder' ); ?></th>
      </tr>
    </thead>
    </table>
  </div>
</div>
