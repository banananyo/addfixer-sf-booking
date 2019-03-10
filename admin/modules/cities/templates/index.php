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
?>
<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php esc_html_e( 'Cities', 'service-finder' ); ?>
    </h2>
  </div>
  <div class="sf-add-city"> 
  <button class="btn btn-primary" data-toggle="modal" data-target="#addcity" type="button"><i class="fa fa-plus"></i>
    <?php echo esc_html__( 'Add City', 'service-finder' ); ?> 
  </button>
  </div>
  <div class="sf-upload-csv"> 
  <button class="btn btn-primary" data-toggle="modal" data-target="#uploadcitycsv" type="button"><i class="fa fa-plus"></i>
    <?php echo esc_html__( 'Upload CSV', 'service-finder' ); ?> 
  </button>
  </div>
  <div class="table-responsive">
    <table id="cities-grid" class="table table-bordered table-striped" cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
      <tr>
        <th><input type="checkbox"  id="bulkAdminCityDelete"  />
          <button id="deleteAdminCityTriger" class="btn btn-danger btn-xs">
          <?php esc_html_e( 'Delete', 'service-finder' ); ?>
        </button></th>
        <th><?php esc_html_e( 'City Name', 'service-finder' ); ?></th>
        <th><?php esc_html_e( 'Country', 'service-finder' ); ?></th>
      </tr>
    </thead>
    </table>
  </div>
  <!-- Add City Modal Popup Box-->
  <div id="addcity" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" class="add-new-city">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
              <?php esc_html_e('Add New City', 'service-finder'); ?>
            </h4>
          </div>
          <div class="modal-body clearfix row input_fields_wrap">
            <div class="col-md-12">
              <div class="form-group">
                <input name="cityname" id="cityname" type="text" class="form-control" placeholder="<?php esc_html_e('City Name', 'service-finder'); ?>">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <select class="sf-select-box form-control sf-form-control" name="countryname" id="countryname" placeholder="<?php esc_html_e('Select Country', 'service-finder'); ?>">
                	<option value=""> <?php esc_html_e('Select Country', 'service-finder'); ?> </option>
					  <?php
					  $allcountry = (!empty($service_finder_options['all-countries'])) ? $service_finder_options['all-countries'] : '';
					  $countries = service_finder_get_countries();
					  if($allcountry){
						  if(!empty($countries)){
							foreach($countries as $key => $country){
								echo '<option value="'.esc_attr($country).'" data-code="'.esc_attr($key).'">'. $country.'</option>';
							}
						  }
					  }else{
					 	 $countryarr = (!empty($service_finder_options['allowed-country'])) ? $service_finder_options['allowed-country'] : '';
						 if($countryarr){
						 	foreach($countryarr as $key){
								echo '<option value="'.esc_attr($countries[$key]).'" data-code="'.esc_attr($key).'">'. $countries[$key].'</option>';
							}
						 }
					  }
					  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php esc_html_e('Cancel', 'service-finder'); ?>
            </button>
            <input type="submit" class="btn btn-primary" name="add-service" value="<?php esc_html_e('Save', 'service-finder'); ?>" />
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Upload City CSV Modal Popup Box-->
  <div id="uploadcitycsv" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" class="upload-cities-csv" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
              <?php esc_html_e('Upload CSV', 'service-finder'); ?>
            </h4>
          </div>
          <div class="modal-body clearfix row input_fields_wrap">
            <div class="col-md-12">
              <div class="form-group">
                <input name="citycsv" id="citycsv" type="file" placeholder="<?php esc_html_e('Upload CSV', 'service-finder'); ?>">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <a href="<?php echo SERVICE_FINDER_BOOKING_LIB_URL.'/downloads.php?file='.SERVICE_FINDER_BOOKING_INC_URL.'/sample.csv' ?>"><?php esc_html_e('Download sample csv file', 'service-finder'); ?></a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php esc_html_e('Cancel', 'service-finder'); ?>
            </button>
            <input type="hidden" name="action" value="upload_cities" />
            <input type="submit" class="btn btn-primary" name="add-csv" value="<?php esc_html_e('Upload', 'service-finder'); ?>" />
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Loading area start -->
<div class="loading-area default-hidden">
  <div class="loading-box"></div>
  <div class="loading-pic"></div>
</div>
<!-- Loading area end -->
