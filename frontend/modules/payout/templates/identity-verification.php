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

$url = str_replace('/','\/',$service_finder_Params['homeUrl']);
$adminajaxurl = str_replace('/','\/',admin_url('admin-ajax.php'));

echo service_finder_get_payout_status($accountinfo);
echo service_finder_get_fields_needed($accountinfo);
?>

<form class="stripe-identity-verification" method="post">
  <div class="panel panel-default about-me-here">
    <div class="panel-heading sf-panel-heading">
      <h3 class="panel-tittle m-a0"><span class="fa fa-user"></span> <?php esc_html_e('Identity Verification', 'service-finder'); ?> </h3>
    </div>
    <div class="panel-body sf-panel-body padding-30">
      <div class="row">
        <div class="col-md-12">
          <div class="rwmb-field rwmb-plupload_image-wrapper">
            <div class="rwmb-input">
              <ul class="rwmb-images rwmb-uploaded" data-field_id="stripeidentityuploader" data-delete_nonce="" data-reorder_nonce="" data-force_delete="0" data-max_file_uploads="1">
              </ul>
              <div id="stripeidentity-dragdrop" class="RWMB-drag-drop drag-drop hide-if-no-js new-files" data-upload_nonce="1f7575f6fa" data-js_options="{&quot;runtimes&quot;:&quot;html5,silverlight,flash,html4&quot;,&quot;file_data_name&quot;:&quot;async-upload&quot;,&quot;browse_button&quot;:&quot;stripeidentity-browse-button&quot;,&quot;drop_element&quot;:&quot;stripeidentity-dragdrop&quot;,&quot;multiple_queues&quot;:true,&quot;max_file_size&quot;:&quot;8388608b&quot;,&quot;url&quot;:&quot;<?php echo esc_url($adminajaxurl); ?>&quot;,&quot;flash_swf_url&quot;:&quot;<?php echo esc_url($url); ?>wp-includes\/js\/plupload\/plupload.flash.swf&quot;,&quot;silverlight_xap_url&quot;:&quot;<?php echo esc_url($url); ?>wp-includes\/js\/plupload\/plupload.silverlight.xap&quot;,&quot;multipart&quot;:true,&quot;urlstream_upload&quot;:true,&quot;filters&quot;:[{&quot;title&quot;:&quot;Allowed  Files&quot;,&quot;extensions&quot;:&quot;jpg,jpeg,gif,png&quot;}],&quot;multipart_params&quot;:{&quot;field_id&quot;:&quot;stripeidentity&quot;,&quot;action&quot;:&quot;stripeidentity_upload&quot;}}">
                <div class = "drag-drop-inside text-center">
                  <p class="drag-drop-info"><?php esc_html_e('Drop files here', 'service-finder'); ?></p>
                  <p><?php esc_html_e('or', 'service-finder'); ?></p>
                  <p class="drag-drop-buttons">
                    <input id="stripeidentity-browse-button" type="button" value="<?php esc_html_e('Select Files', 'service-finder'); ?>" class="button btn btn-default" />
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Personal ID Number', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-user"></i>
              <input type="text" class="form-control sf-form-control" name="personal_id_number" value="">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label>
            <?php esc_html_e('Alternative Text/Title', 'service-finder'); ?>
            </label>
            <div class="input-group"> <i class="input-group-addon fixed-w fa fa-user"></i>
              <input type="text" class="form-control sf-form-control" name="alternative_text" value="">
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