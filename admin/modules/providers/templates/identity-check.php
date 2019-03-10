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
?>
<!--Template for providers in admin panel-->

<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php esc_html_e( 'Providers Identity Check', 'service-finder' ); ?>
    </h2>
  </div>

  <div class="table-responsive">
    <table id="providers-identity-check-grid" class="table table-bordered table-striped"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
        <th><?php esc_html_e( 'Name', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Phone', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Email', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'View Identity', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Status', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Action', 'service-finder' ); ?></th>
      </tr>
    </thead>
    </table>
  </div>
</div>
