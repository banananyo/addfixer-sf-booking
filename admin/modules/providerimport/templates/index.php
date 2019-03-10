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


<div class="sf-wpbody-inr">
  <div class="sedate-title">
    <h2>
      <?php esc_html_e( 'Provider Import', 'service-finder' ); ?>
    </h2>
  </div>
  <div class="table-responsive">
   <div class="wrap">	
   		<div id="success"></div>
		<div class="provider-import-wrap">
		<form id="upload_csv" method="post" enctype="multipart/form-data">
		<input type="hidden" name="records" id="no_records" value="0">
		<input type="hidden" name="csv_num_rows" id="csv_num_rows" value="0">
			<table class="form-table">
				<tbody>
				<tr class="form-field form-required">
					<th scope="row"><label><?php esc_html_e( 'Update existing users?', 'service-finder' ); ?></label></th>
					<td>
                    	<div class="sf-select-wrap">	
						<select class="sf-select-box form-control sf-form-control" name="update_existing_users" id="update_existing_users">
							<option value="yes"><?php esc_html_e( 'Yes', 'service-finder' ); ?></option>
							<option value="no"><?php esc_html_e( 'No', 'service-finder' ); ?></option>
						</select>
                        </div>
					</td>
				</tr>

				<tr class="form-field form-required">
					<th scope="row"><label><?php esc_html_e( 'CSV file', 'service-finder');?> <span class="description">(<?php esc_html_e( 'required', 'service-finder');?>)</span></label></th>
					<td>
						<div id="upload_file">
							<input type="file" name="uploadfiles" id="uploadfiles" size="35" class="uploadfiles" />
						</div>
					
					</td>
					</th>
				</tr>
                
                <tr class="form-field form-required">
					<td><input class="button-primary" type="submit" name="uploadfile" id="uploadfile_btn" value="<?php esc_html_e( 'Start Importing', 'service-finder' ); ?>"/></td>
					<td>
                    	<?php $downloadurl = SERVICE_FINDER_BOOKING_LIB_URL.'/downloads.php?file='.SERVICE_FINDER_BOOKING_INC_URL.'/sample-provider-import.csv'; ?>
                        <a href="<?php echo esc_url($downloadurl); ?>" class="button-primary btn-download"><i class="fa fa-download"></i> <?php esc_html_e( 'Download Sample CSV FILE', 'service-finder' ); ?></a>
					</td>
					</th>
				</tr>

			</tbody>
			</table>
			
			
			
			</form>
            <div class="provider-form-overlay" style="display:none"></div>
            <div class="provider-loading-image" style="display:none"><img src="<?php echo esc_url(SERVICE_FINDER_BOOKING_IMAGE_URL.'/load.gif'); ?>"></div>
		</div>

	</div>
  </div>
</div>