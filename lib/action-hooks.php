<?php
/*****************************************************************************
*
*	copyright(c) - aonetheme.com - Service Finder Team
*	More Info: http://aonetheme.com/
*	Coder: Service Finder Team
*	Email: contact@aonetheme.com
*
******************************************************************************/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Booking date selected based on service */
function service_finder_singledate_services($author,$bookingstyle){
   	$services = service_finder_getServices($author,'active');
	if(!empty($services)){
					foreach($services as $service){
					if($service->cost_type == 'hourly'){
						if($service->hours > 0){
						$addhours = '<div class="input-group bootstrap-touchspin sf-service-fixhr-bx" id="hours-outer-bx-'.esc_attr($service->id).'" style="display:none;">
    <div class="input-table-bx">
	<span class="input-cell-bx">
     <i class="fa fa-clock-o"></i>
	 <input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">
    </span>
    <span class="input-cell-bx">
	'.esc_html__('Hour', 'service-finder').'
    </span>
	</div>
</div>';
						}else{
						$addhours = '<input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">';
						}
						if($service->hours > 0){
						$perhr = service_finder_money_format($service->cost);
						$perhr .= '<span class="sf-fix-hours"><i class="fa fa-clock-o"></i> '.$service->hours.esc_html__(' hrs', 'service-finder').'</span>';
						}else{
						$perhr = service_finder_money_format($service->cost).esc_html__('/hour', 'service-finder');
						}
						
						$totalhrsprs = $service->hours;
					
					}elseif($service->cost_type == 'perperson'){
						if($service->persons > 0){
						$addhours = '<div class="input-group bootstrap-touchspin sf-service-fixhr-bx" id="hours-outer-bx-'.esc_attr($service->id).'" style="display:none;">
							<div class="input-table-bx">
							<span class="input-cell-bx">
							<i class="fa fa-user"></i>
							 <input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">
							</span>
							<span class="input-cell-bx">
							'.esc_html__('Person', 'service-finder').'
							</span>
							</div>
						</div>';
						}else{
						$addhours = '<input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">';
						}
						if($service->persons > 0){
						$perhr = service_finder_money_format($service->cost);
						$perhr .= '<span class="sf-fix-hours"><i class="fa fa-user"></i> '.$service->persons.esc_html__(' items', 'service-finder').'</span>';
						}else{
						$perhr = service_finder_money_format($service->cost).esc_html__('/person', 'service-finder');
						}
						
						$totalhrsprs = $service->persons;
					}else{
						$addhours = '';
						$perhr = service_finder_money_format($service->cost);
						$totalhrsprs = 0;
					}
					
						
						$bookingstyleclass = ($bookingstyle == 1) ? 'col-md-4' : 'col-md-3';
						echo '<div class="'.sanitize_html_class($bookingstyleclass).' aon-service-outer equal-col">
							  	<div class="aon-service-bx unselected" id="serbx-'.esc_attr($service->id).'" data-hours="'.esc_attr($totalhrsprs).'" data-discounttype="" data-discountvalue="" data-coupon="" data-costtype="'.esc_attr($service->cost_type).'" data-id="'.esc_attr($service->id).'" data-cost="'.esc_attr($service->cost).'">
									<div class="aon-service-name"><h5>'.esc_html($service->service_name).'</h5></div>
									<div class="aon-service-price">'.$perhr.'</div>
									<div class="aon-service-done"><i class="fa fa-check"></i></div>
								</div>
								'.$addhours.'
								'.service_finder_have_coupon_code_button($service->id,$author).'
							</div>';				
					}
					echo service_finder_coupon_code_section($author);
				}	
}
add_action('service_finder_singledate_services', 'service_finder_singledate_services',10,2);

/* Booking date selected based on service */
function service_finder_multidate_services($author,$bookingstyle){
    $services = service_finder_getServices($author,'active');
    if(!empty($services)){
            foreach($services as $service){
            if($service->cost_type == 'hourly'){
                if($service->hours > 0){
                $addhours = '<div class="input-group bootstrap-touchspin sf-service-fixhr-bx" id="hours-outer-bx-'.esc_attr($service->id).'" style="display:none;">
                            <div class="input-table-bx">
                            <span class="input-cell-bx">
                             <i class="fa fa-clock-o"></i>
                             <input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">
                            </span>
                            <span class="input-cell-bx">
                            '.esc_html__('Hour', 'service-finder').'
                            </span>
                            </div>
                            </div>';
                }else{
                $addhours = '<input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">';
                }
                if($service->hours > 0){
                $perhr = service_finder_money_format($service->cost);
                $perhr .= '<span class="sf-fix-hours"><i class="fa fa-clock-o"></i> '.$service->hours.esc_html__(' hrs', 'service-finder').'</span>';
                }else{
                $perhr = service_finder_money_format($service->cost).esc_html__('/hour', 'service-finder');
                }
                
                $totalhrsprs = $service->hours;
            
            }elseif($service->cost_type == 'days'){
						if($service->days > 0){
						$addhours = '<div class="input-group bootstrap-touchspin sf-service-fixhr-bx" id="days-outer-bx-'.esc_attr($service->id).'" style="display:none;">
    <div class="input-table-bx">
	<span class="input-cell-bx">
	<i class="fa fa-calendar"></i>
     <input id="days-'.esc_attr($service->id).'" class="form-control" type="text" name="days[]" style="display:none;">
    </span>
    <span class="input-cell-bx">
	'.esc_html__('Day', 'service-finder').'
    </span>
	</div>
</div>';
						}else{
						$addhours = '<input id="days-'.esc_attr($service->id).'" class="form-control" type="text" name="days[]" style="display:none;">';
						}
						
						if($service->days > 0){
						$perhr = service_finder_money_format($service->cost);
						$perhr .= '<span class="sf-fix-hours"><i class="fa fa-calendar"></i> '.$service->days.esc_html__(' days', 'service-finder').'</span>';
						}else{
						$perhr = service_finder_money_format($service->cost).esc_html__('/day', 'service-finder');
						}
						
						$totalhrsprs = $service->days;
					}elseif($service->cost_type == 'perperson'){
                if($service->persons > 0){
                $addhours = '<div class="input-group bootstrap-touchspin sf-service-fixhr-bx" id="hours-outer-bx-'.esc_attr($service->id).'" style="display:none;">
                                <div class="input-table-bx">
                                <span class="input-cell-bx">
                                <i class="fa fa-user"></i>
                                 <input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">
                                </span>
                                <span class="input-cell-bx">
                                '.esc_html__('Person', 'service-finder').'
                                </span>
                                </div>
                            </div>';
                }else{
                $addhours = '<input id="hours-'.esc_attr($service->id).'" class="form-control" type="text" name="hours[]" style="display:none;">';
                }
                if($service->persons > 0){
                $perhr = service_finder_money_format($service->cost);
                $perhr .= '<span class="sf-fix-hours"><i class="fa fa-user"></i> '.$service->persons.esc_html__(' items', 'service-finder').'</span>';
                }else{
                $perhr = service_finder_money_format($service->cost).esc_html__('/person', 'service-finder');
                }
                
                $totalhrsprs = $service->persons;
            }else{
                $addhours = '';
                $perhr = service_finder_money_format($service->cost);
                $totalhrsprs = 0;
            }
				$bookingstyleclass = ($bookingstyle == 1) ? 'col-md-4' : 'col-md-3';
				echo '<div class="'.sanitize_html_class($bookingstyleclass).' aon-service-outer equal-col">
							  	<div class="aon-service-bx unselected" data-hours="'.esc_attr($totalhrsprs).'" data-discounttype="" data-discountvalue="" data-discount="" data-coupon="" id="serbx-'.esc_attr($service->id).'" data-costtype="'.esc_attr($service->cost_type).'" data-id="'.esc_attr($service->id).'" data-cost="'.esc_attr($service->cost).'">
									<div class="aon-service-name"><h5>'.esc_html($service->service_name).'</h5></div>
									<div class="aon-service-price">'.$perhr.'</div>
									<div class="aon-service-done"><i class="fa fa-check"></i></div>
								</div>
								'.$addhours.'
								'.service_finder_have_coupon_code_button($service->id,$author).'
							</div>';					
            }
			echo service_finder_coupon_code_section($author);
        }	
}
add_action('service_finder_multidate_services', 'service_finder_multidate_services',10,2);

/* Providers Articles for tab*/
function service_finder_short_provider_aticles($author){
global $current_user;
	
	$args = array(
		'post_type' 	=> 'sf_articles',
		'post_status' 	=> 'publish',
		'posts_per_page' => 5,
		'order' => 'DESC',
		'author' => $author,
	);
	$the_query = new WP_Query( $args );
	
	if ( $the_query->have_posts() ) {
	echo '<ul class="sf-ques-ans-list clearfix">';
	while( $the_query->have_posts() ) : $the_query->the_post();
	global $post;
	?>
    <li>
        <div class="sf-ques-area">
            <div class="sf-ques-ans-author"><img src="<?php echo service_finder_get_avatar_by_userid($post->post_author); ?>" alt=""></div>
            <div class="sf-ques-has"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a></div>
            <div class="sf-ques-has-desc"><?php the_excerpt(); ?></div>
            <div class="sf-ques-ans-meta">
                <span class="sf-ques-meta-col sf-qa-vote"><i class="fa fa-calendar-o"></i> <?php echo get_the_date( 'M j, Y', $post->ID ); ?></span>
                <span class="sf-ques-meta-col sf-qa-hour"><i class="fa fa-clock-o"></i> <?php printf( __( '%s ago', 'service-finder' ), human_time_diff( get_post_time( 'U' ), time() ) ); ?></span>
            </div>
        </div>
    </li>
	<?php
	
	endwhile;
	wp_reset_postdata();
	$authorlink = service_finder_get_author_url($author);
	$url = add_query_arg( array('morearticles' => "true"), $authorlink );
	echo '</ul>';
	echo '<div class="padding-t-20 text-center"><a href="'.esc_url($url).'" target="_blank" class="btn btn-primary">'.esc_html__('More from this provider', 'service-finder').'</a></div>';
	}else{
	echo '<div>'.esc_html__('No data available.', 'service-finder').'</div>';
	}
}
add_action('service_finder_short_provider_aticles', 'service_finder_short_provider_aticles');

/* Full articles list */
function service_finder_provider_aticles($author){
global $current_user;
	echo '<h4>'.esc_html__('Articles', 'service-finder').'</h4>';
	echo '<div class="margin-b-30  bg-white">';
    $args = array(
		'post_type' 	=> 'sf_articles',
		'post_status' 	=> 'publish',
		'posts_per_page' => -1,
		'order' => 'DESC',
		'author' => $author,
	);
	$the_query = new WP_Query( $args );
	
	if ( $the_query->have_posts() ) {
	while( $the_query->have_posts() ) : $the_query->the_post();
	global $post;
	
	$question_for = get_post_meta($post->ID, '_question_for_provider_id', true);
	$category_id = get_post_meta($post->ID, '_article_category_id', true);
	$iconsrc = service_finder_getTermIcon($category_id);
	?>
    <?php if($category_id > 0){ ?>
    <div class="sf-ques-header clearfix">
    	<?php if($iconsrc != ""){ ?>
        <div class="sf-ques-header-pic"><img src="<?php echo esc_url($iconsrc); ?>" alt=""></div>
        <?php } ?>
        <div class="sf-ques-header-body"><div class="sf-ques-header-title"><a href="javascript:;"><?php echo service_finder_getCategoryName($category_id,'sf_article_category') ?></a></div>
        <span class="sf-ques-meta-col sf-qa-answers"><?php echo service_finder_getCategoryDescription($category_id,'sf_question_category') ?></span></div>
    </div>
    <?php } ?>
    <div class="sf-ques-body">
        <div class="sf-ques-body-title"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a></div>
        <div class="sf-ques-ans-author"><img src="<?php echo service_finder_get_avatar_by_userid($post->post_author); ?>" alt=""></div>
        <div class="sf-ques-title-meta">
        	<div class="sf-quesans-title"><?php the_content(); ?></div>
            <div class="sf-quesans-meta">
                <span class="sf-ques-meta-col sf-qa-vote"><i class="fa fa-calendar-o"></i> <?php echo get_the_date( 'M j, Y', $post->ID ); ?></span>
                <span class="sf-ques-meta-col sf-qa-hour"><i class="fa fa-clock-o"></i> <?php printf( __( '%s ago', 'service-finder' ), human_time_diff( get_post_time( 'U' ), time() ) ); ?></span>
            </div>
        </div>
    </div>
	<?php
	
	endwhile;

	wp_reset_postdata();
	}
	echo '</div>';
}
add_action('service_finder_provider_aticles', 'service_finder_provider_aticles');


/* Question and Answer*/
function service_finder_question_answer($author){
global $current_user;
	echo '<h4>'.esc_html__('Question and Answer', 'service-finder').'</h4>';
	echo '<div class="margin-b-30  bg-white">';
    $args = array(
		'post_type' 	=> 'sf_questions',
		'post_status' 	=> 'publish',
		'posts_per_page' => -1,
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key' 		=> '_question_for_provider_id',
				'value' 	=> $author,
				'compare' 	=> '=',
			),
		),
	);
	$the_query = new WP_Query( $args );
	
	if ( $the_query->have_posts() ) {
	while( $the_query->have_posts() ) : $the_query->the_post();
	global $post;
	
	$question_for = get_post_meta($post->ID, '_question_for_provider_id', true);
	$category_id = get_post_meta($post->ID, '_question_category_id', true);
	$iconsrc = service_finder_getTermIcon($category_id);
	?>
    <?php if($category_id > 0){ ?>
    <div class="sf-ques-header clearfix">
    	<?php if($iconsrc != ""){ ?>
        <div class="sf-ques-header-pic"><img src="<?php echo esc_url($iconsrc); ?>" alt=""></div>
        <?php } ?>
        <div class="sf-ques-header-body"><div class="sf-ques-header-title"><a href="javascript:;"><?php echo service_finder_getCategoryName($category_id,'sf_question_category') ?></a></div>
        <span class="sf-ques-meta-col sf-qa-answers"><?php echo service_finder_getCategoryDescription($category_id,'sf_question_category') ?></span></div>
    </div>
    <?php } ?>
    <div class="sf-ques-body" id="question-<?php echo esc_attr($post->ID); ?>">
        <div class="sf-ques-body-title"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a></div>
        <div class="sf-ques-ans-author"><img src="<?php echo service_finder_get_avatar_by_userid($post->post_author); ?>" alt=""></div>
        <div class="sf-ques-title-meta">
        	<div class="sf-quesans-title"><?php the_content(); ?></div>
            <div class="sf-quesans-meta">
                <span class="sf-ques-meta-col sf-qa-answers"><a href="<?php echo get_permalink(); ?>" target="_blank"><i class="fa fa-commenting-o"></i> <?php printf(esc_html__('%d Answers', 'service-finder'),service_finder_get_total_answers($post->ID)); ?></a></span>
                <span class="sf-ques-meta-col sf-qa-vote"><i class="fa fa-calendar-o"></i> <?php echo get_the_date( 'M j, Y', $post->ID ); ?></span>
                <span class="sf-ques-meta-col sf-qa-hour"><i class="fa fa-clock-o"></i> <?php printf( __( '%s ago', 'service-finder' ), human_time_diff( get_post_time( 'U' ), time() ) ); ?></span>
            </div>
            <?php 
			if(is_user_logged_in()){
				if($current_user->ID == $author){
					service_finder_answer_button_html($post->ID);
				} 
			}
			?>
        </div>
        <?php
        if(is_user_logged_in()){
	        if($current_user->ID == $author){
    		    service_finder_answer_html($post->ID);
        	} 
		}
		?>
        <?php
        $args = array( 'post_type' => 'sf_answers', 'posts_per_page' => -1, 'post_status' =>'publish', 'post_parent' => $post->ID ); 
		$answers = get_posts( $args );
		if ( $answers ) {
			foreach ( $answers as $answer ) {
				?>
				<div class="sf-anstext-body">
                    <div class="sf-answer-icon"><img src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL.'/qapic.jpg'; ?>" alt=""></div>
                    <?php
					$mode = get_option('rss_use_excerpt');
					if($mode){
						print_r($answer->post_content);
						echo '....';
						echo '<a href="'.get_permalink($post->ID).'#'.$answer->ID.'" class="readmore-link">('.esc_html__( 'more', 'service-finder' ).')</a>';
					}else{
						print_r($answer->post_content);
					}
					?>
                    <div class="sf-quesans-meta">
						<?php echo service_finder_voting_layout($answer->ID); ?>                                        
                        <span class="sf-ques-meta-col sf-qa-vote"><i class="fa fa-calendar-o"></i> <?php echo get_the_date( 'M j, Y', $answer->ID ); ?></span>
                        <span class="sf-ques-meta-col sf-qa-hour"><i class="fa fa-clock-o"></i> <?php printf( __( '%s ago', 'service-finder' ), human_time_diff( get_post_time( 'U',false, $answer->ID), time() ) ); ?></span>
                    </div>
                </div>
				<?php
			}
		}
		$the_query->reset_postdata();
		?>
    </div>
	<?php
	
	endwhile;

	wp_reset_postdata();
	}
	echo '</div>';
}
add_action('service_finder_question_answer', 'service_finder_question_answer');

/* Question and Answer Limited*/
function service_finder_voting_layout($postid){
global $current_user;
$up_voted_users = get_post_meta($postid,'_upvotedusers',true);
$down_voted_users = get_post_meta($postid,'_downvotedusers',true);
if(empty($up_voted_users)){
	$up_voted_users = array();
}
if(empty($down_voted_users)){
	$down_voted_users = array();
}
if(in_array($current_user->ID,$up_voted_users) || in_array($current_user->ID,$down_voted_users)){
$voteclass = 'alreadyvote';
}else{
$voteclass = 'dovote';
}
?>
<span class="sf-ques-meta-col sf-qa-thum">
    <a href="javascript:;" id="doupvote-<?php echo esc_attr($postid); ?>" class="<?php echo sanitize_html_class($voteclass); ?>" data-postid="<?php echo esc_attr($postid); ?>" data-vote="up"><i class="fa fa-thumbs-o-up"></i></a>
    <?php 
    if(get_post_meta($postid,'_totalupvotes',true) > 1){
        $votes = esc_html__('Votes', 'service-finder' );
    }else{
        $votes = esc_html__('Vote', 'service-finder' );
    }
    ?>
    <span id="upvotes-<?php echo esc_attr($postid); ?>"><?php echo (get_post_meta($postid,'_totalupvotes',true) > 0) ? get_post_meta($postid,'_totalupvotes',true) : 0 ?></span> <?php printf($votes); ?>
</span>
<span class="sf-ques-meta-col sf-qa-thum">
    <a href="javascript:;" id="dodownvote-<?php echo esc_attr($postid); ?>" class="<?php echo sanitize_html_class($voteclass); ?>" data-postid="<?php echo esc_attr($postid); ?>" data-vote="down"><i class="fa fa-thumbs-o-down"></i></a>
    <?php 
    if(get_post_meta($postid,'_totaldownvotes',true) > 1){
        $votes = esc_html__('Votes', 'service-finder' );
    }else{
        $votes = esc_html__('Vote', 'service-finder' );
    }
    ?>
    <span id="downvotes-<?php echo esc_attr($postid); ?>"><?php echo (get_post_meta($postid,'_totaldownvotes',true)) ? get_post_meta($postid,'_totaldownvotes',true) : 0 ?></span> <?php printf($votes); ?>
</span>
<?php
}

/* Question and Answer Limited*/
function service_finder_short_question_answer($author){
global $current_user;
	
	$args = array(
		'post_type' 	=> 'sf_questions',
		'post_status' 	=> 'publish',
		'posts_per_page' => 5,
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key' 		=> '_question_for_provider_id',
				'value' 	=> $author,
				'compare' 	=> '=',
			),
		),
	);
	$the_query = new WP_Query( $args );
	
	if ( $the_query->have_posts() ) {
	echo '<ul class="sf-ques-ans-list clearfix">';
	while( $the_query->have_posts() ) : $the_query->the_post();
	global $post;
	
	$question_for = get_post_meta($post->ID, '_question_for_provider_id', true);
	?>
    <li>
        <div class="sf-ques-area">
            <div class="sf-ques-ans-author"><img src="<?php echo service_finder_get_avatar_by_userid($post->post_author); ?>" alt=""></div>
            <div class="sf-ques-has"><a href="<?php echo get_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?></a></div>
            <div class="sf-ques-has-desc"><?php the_content(); ?></div>
            <div class="sf-ques-ans-meta">
                <span class="sf-ques-meta-col sf-qa-answers" data-toggle="modal" data-target="#answers-modal"><i class="fa fa-commenting-o"></i> <?php printf(esc_html__('%d Answers', 'service-finder'),service_finder_get_total_answers($post->ID)); ?></span>
                <span class="sf-ques-meta-col sf-qa-vote"><i class="fa fa-calendar-o"></i> <?php echo get_the_date( 'M j, Y', $post->ID ); ?></span>
                <span class="sf-ques-meta-col sf-qa-hour"><i class="fa fa-clock-o"></i> <?php printf( __( '%s ago', 'service-finder' ), human_time_diff( get_post_time( 'U' ), time() ) ); ?></span>
            </div>
            <?php 
			if(is_user_logged_in()){
				if($current_user->ID == $author){
					service_finder_answer_button_html($post->ID);
				} 
			}
			?>
        </div>
        <?php 
		if(is_user_logged_in()){
	        if($current_user->ID == $author){
    		    service_finder_answer_html($post->ID);
        	} 
		}
		?>
		<?php
		$args = array( 'post_type' => 'sf_answers', 'posts_per_page' => 1, 'post_status' =>'publish', 'post_parent' => $post->ID, 'order' => 'DESC' ); 
		$answers = get_posts( $args );
		if ( $answers ) {
			foreach ( $answers as $answer ) {
				?>
                <div class="sf-ansering-area">
                    <div class="sf-answer-icon"><img src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL.'/qapic.jpg'; ?>" alt=""></div>
                    <?php
                    print_r($answer->post_content);
					echo '....';
					echo '<a href="'.get_permalink($post->ID).'#'.$answer->ID.'" class="readmore-link">('.esc_html__( 'more', 'service-finder' ).')</a>';
					?>
                </div>
				<?php
			}
		}
		$the_query->reset_postdata();
		?>
    </li>
	<?php
	
	endwhile;
	wp_reset_postdata();
	$authorlink = service_finder_get_author_url($author);
	$url = add_query_arg( array('moreqa' => "true"), $authorlink );
	echo '</ul>';
	echo '<div class="padding-t-20 text-center"><a href="'.esc_url($url).'" target="_blank" class="btn btn-primary">'.esc_html__('More from this provider', 'service-finder').'</a></div>';
	}else{
	echo '<div>'.esc_html__('No data available.', 'service-finder').'</div>';
	}
}
add_action('service_finder_short_question_answer', 'service_finder_short_question_answer');

/* Ask Question */
function service_finder_ask_question($author){
global $current_user;
?>
<div class="sf-qa-wrap sf-ask-question">
    <div class="qa-pic"><img src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL.'/qna.png'; ?>" alt=""></div>
    <h4><?php echo esc_html__('Get answers to your queries now', 'service-finder'); ?></h4>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#sf-add-question"><?php echo esc_html__('Ask Question', 'service-finder'); ?></button>
    <div id="sf-add-question" class="sf-add-question-form collapse">
    	<?php if(is_user_logged_in()){ ?>
        <form method="post" class="add-question">
          <div class="form-group">
            <input type="text" class="form-control" name="question_title" placeholder="<?php esc_html_e('Question Title', 'service-finder'); ?>" required="required" data-error="<?php esc_html_e('Question title should not be empty', 'service-finder'); ?>">
          </div>
            <?php 
            $settings = array('media_buttons' => false);
            wp_editor('', 'question_description', $settings); 
            ?>
            <div class="form-group sf-question-cat-select">
			<?php
            echo service_finder_category_dropdown('sf_question_category');
			?>
            </div>
            <input type="hidden" name="author_id" value="<?php echo base64_encode($author); ?>">
            <input type="submit" class="btn btn-primary" name="add-question" value="<?php esc_html_e('Submit Question', 'service-finder'); ?>" />
        </form>
        <?php }else{ ?>
        <div class="alert alert-info"><?php echo esc_html__('Please login to submit your question.', 'service-finder'); ?></div>	
        <?php } ?>
    </div>
</div>
<?php
}
add_action('service_finder_ask_question', 'service_finder_ask_question');

function service_finder_answer_button_html($question_id){
?>
<button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#sf-add-answer-<?php echo esc_attr($question_id); ?>"><?php echo esc_html__('Give Answer', 'service-finder'); ?></button>
<?php
}

function service_finder_answer_html($question_id){
?>
<div id="sf-add-answer-<?php echo esc_attr($question_id); ?>" class="collapse clearfix">
        <form method="post" class="add-answer">
          <div class="col-md-12">
              <div class="form-group">
                <?php 
				$settings = array('media_buttons' => false, 'textarea_name' => 'answer_description');
				wp_editor('', 'answer_description'.$question_id, $settings); 
				?> 
                
              </div>
            </div>
          <div class="col-md-12">
              <div class="form-group">
                <input type="hidden" name="question_id" value="<?php echo base64_encode($question_id); ?>">
                <input type="submit" class="btn btn-custom" name="add-answer" value="<?php esc_html_e('Submit Answer', 'service-finder'); ?>" />
              </div>
            </div>  
        </form>
</div>
<?php
}

/*Add question to db*/	
add_action( 'wp_ajax_add_question', 'service_finder_add_question' );
add_action( 'wp_ajax_nopriv_add_question', 'service_finder_add_question' );
function service_finder_add_question() {
global $current_user;
$author_id = (!empty($_POST['author_id'])) ? base64_decode($_POST['author_id']) : '';
$title = (!empty($_POST['question_title'])) ? esc_attr($_POST['question_title']) : '';
$description = (!empty($_POST['question_description'])) ? esc_attr($_POST['question_description']) : '';
$categoryid = (!empty($_POST['categoryid'])) ? esc_attr($_POST['categoryid']) : '';
		
$question_data = array(
	'post_title' 	=> $title,
	'post_content'  => $description,
	'post_status' 	=> 'publish',
	'post_author'   => $current_user->ID,
	'post_type' 	=> 'sf_questions'
);

$post_id = wp_insert_post($question_data);

update_post_meta($post_id, '_question_for_provider_id', $author_id);
update_post_meta($post_id, '_question_category_id', $categoryid);

if($post_id > 0) {
	$success = array(
			'status' => 'success',
			'author_id' => $author_id,
			'suc_message' => esc_html__('Question submit successfully.', 'service-finder'),
			);
	echo json_encode($success);
}else{
	$error = array(
			'status' => 'error',
			'err_message' => esc_html__('Question could not be submit.', 'service-finder'),
			);
	echo json_encode($error);

}
exit(0);
}

/*Add answer to db*/	
add_action( 'wp_ajax_add_answer', 'service_finder_add_answer' );
add_action( 'wp_ajax_nopriv_add_answer', 'service_finder_add_answer' );
function service_finder_add_answer() {
global $current_user;
$question_id = (!empty($_POST['question_id'])) ? base64_decode($_POST['question_id']) : '';
$description = (!empty($_POST['answer_description'])) ? $_POST['answer_description'] : '';
		
$answer_data = array(
	'post_title' 	=> '',
	'post_content'  => $description,
	'post_status' 	=> 'publish',
	'post_author'   => $current_user->ID,
	'post_type' 	=> 'sf_answers',
	'post_parent'	=> $question_id,
);

$post_id = wp_insert_post($answer_data);
$redirecturl = service_finder_get_author_url($current_user->ID);
$redirecturl = add_query_arg( array('moreqa' => 'true#question-'.$question_id), $redirecturl );

if($post_id > 0) {
	$success = array(
			'status' => 'success',
			'author_id' => $author_id,
			'redirecturl' => $redirecturl,
			'suc_message' => esc_html__('Answer submit successfully.', 'service-finder'),
			);
	echo json_encode($success);
}else{
	$error = array(
			'status' => 'error',
			'err_message' => esc_html__('Answer could not be submit.', 'service-finder'),
			);
	echo json_encode($error);

}
exit(0);
}

/*Update Voting*/	
add_action( 'wp_ajax_updatevoting', 'service_finder_updatevoting' );
add_action( 'wp_ajax_nopriv_updatevoting', 'service_finder_updatevoting' );
function service_finder_updatevoting() {
global $current_user;
$postid = (!empty($_POST['postid'])) ? intval($_POST['postid']) : 0;
$vote = (!empty($_POST['vote'])) ? esc_attr($_POST['vote']) : '';
$up = 0;
$down = 0;
if($vote == 'up'){
	$up = get_post_meta($postid,'_totalupvotes',true);
	$voted_users = get_post_meta($postid,'_upvotedusers',true);
	if($up != ''){
		$up = intval($up) + 1;
	}else{
		$up = 1;
	}
	if(!empty($voted_users)){
		$voted_users[] = $current_user->ID;
	}else{
		$voted_users = array($current_user->ID);
	}
	update_post_meta($postid,'_totalupvotes',$up);
	update_post_meta($postid,'_upvotedusers',$voted_users);
}else{
	$down = get_post_meta($postid,'_totaldownvotes',true);
	$voted_users = get_post_meta($postid,'_downvotedusers',true);
	if($down != ''){
		$down = intval($down) + 1;
	}else{
		$down = 1;
	}
	if(!empty($voted_users)){
		$voted_users[] = $current_user->ID;
	}else{
		$voted_users = array($current_user->ID);
	}
	update_post_meta($postid,'_totaldownvotes',$down);
	update_post_meta($postid,'_downvotedusers',$voted_users);
}

$success = array(
	'status' => 'success',
	'upvotes' => get_post_meta($postid,'_totalupvotes',true),
	'downvotes' => get_post_meta($postid,'_totaldownvotes',true),
	'_upvotedusers' => get_post_meta($postid,'_upvotedusers',true),
	'_downvotedusers' => get_post_meta($postid,'_downvotedusers',true),
	'suc_message' => esc_html__('Vote has been update.', 'service-finder'),
	);
echo json_encode($success);
		
exit(0);
}

/*Get total answers*/
function service_finder_get_total_answers($question_id){
global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare('SELECT count(ID) as total FROM '.$wpdb->prefix.'posts WHERE `post_parent` = %d AND `post_type` = "sf_answers" AND `post_status` = "publish"',$question_id));
	
	return $row->total;
}

/*Coupon code section*/
function service_finder_have_coupon_code_button($serviceid,$userid){
global $wpdb,$service_finder_Tables;
$html = '';

if(service_finder_check_offer_system() && service_finder_offers_method($userid) == 'services'){
$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->services.' where id = %d',$serviceid));
if(!empty($row)){
	if($row->offer == 'yes'){
	$html .= '<div class="addcouponcode-wrap"><button class="btn btn-primary btn-sm addcouponcode" data-toggle="collapse" data-sid="'.$serviceid.'">'.esc_html__('Have a coupon code?', 'service-finder').'</button></div>';
		
	}
}
}
	
return $html;	
}

/*Coupon code section*/
function service_finder_coupon_code_section($userid = 0){
global $wpdb,$service_finder_Tables;
$html = '';

if(service_finder_check_offer_system() && service_finder_offers_method($userid) == 'services'){
$html = '<div id="addcouponcode" class="collapse sf-couponcode-popup">
		  <i class="fa fa-close sf-couponcode-close"></i>
		  <label>'.esc_html__('Enter coupon code', 'service-finder').'</label>
		  <input type="text" name="couponcode" id="" class="form-control sf-form-control"><a href="javascropt:;" class="verifycoupon btn btn-primary" data-sid="" data-userid="'.$userid.'">'.esc_html__('Verify', 'service-finder').'</a>
		 </div>';
$html .= '<div class="sf-couponcode-popup-overlay" style="display:none;"></div>';		 
}		 
			  	
return $html;	
}

/*Coupon code section for multidate*/
function service_finder_multidate_coupon_code($user_id){
global $wpdb,$service_finder_Tables;
$html = '';

if(service_finder_check_offer_system() && service_finder_offers_method($user_id) == 'booking'){
$result = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->offers.' where wp_user_id = %d',$user_id));
if(!empty($result)){
	$html .= '<div class="viewcoupon-bx">
			  <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#addbookingcoupon"><i class="fa fa-arrow-circle-down"></i> '.esc_html__('Have a coupon code?', 'service-finder').'</button>
			  <div id="addbookingcoupon" class="collapse">
			  <input type="text" name="couponcode" id="couponcode" class="form-control sf-form-control"><a href="javascropt:;" class="verifybookingcoupon btn btn-custom" data-userid="'.$user_id.'">'.esc_html__('Verify', 'service-finder').'</a>
			  </div> 
			  </div>';
		
}
}
	
return $html;	
}

/*View Invoice*/
function service_finder_view_invoice($invoiceid){
global $wpdb,$service_finder_Tables;

$servicehtml = '';
$sql = $wpdb->prepare("SELECT invoice.id, invoice.reference_no, invoice.duedate, invoice.booking_id, invoice.discount_type, invoice.tax_type, invoice.discount, invoice.tax, invoice.services, invoice.description, invoice.total, invoice.grand_total, invoice.status, customers.name, customers.phone as cusphone, customers.phone2 as cusphone2, customers.email as cusemail, customers.address as cusaddress, customers.apt as cusapt, customers.city as cuscity, customers.state as cusstate, customers.zipcode as cuszipcdoe, customers.description, providers.full_name, providers.phone, providers.email, providers.mobile, providers.fax, providers.address, providers.apt, providers.city, providers.state, providers.zipcode, providers.country FROM ".$service_finder_Tables->invoice." as invoice INNER JOIN ".$service_finder_Tables->customers." as customers on invoice.customer_email = customers.email  LEFT JOIN ".$service_finder_Tables->providers." as providers on invoice.provider_id = providers.wp_user_id WHERE invoice.id = %d",$invoiceid);

$row = $wpdb->get_row($sql);

$discount_type = $row->discount_type;
$tax_type = $row->tax_type;
if($row->discount > 0){
if($discount_type == 'fix'){
	$displaydiscount = $row->discount;
}elseif($discount_type == 'percentage'){
	$displaydiscount = $row->total * ($row->discount/100);
}
}else{
	$displaydiscount = '0.00';
}

if($row->tax > 0){
if($tax_type == 'fix'){
	$displaytax = $row->tax;
}elseif($tax_type == 'percentage'){
	$displaytax = $row->total * ($row->tax/100);
}
}else{
	$displaytax = '0.00';
}

$services = unserialize($row->services);
if(!empty($services)){
	foreach($services as $key => $value){
	
	if($value[0] == 'new'){
		$servicename = 'Extra Service';
	}else{
		$servicedata = service_finder_getServiceData($value[0]);
		$servicename = stripcslashes($servicedata->service_name);
	}
	
	
	if($value[1] == 'fix'){
		$hrs = 'N/A';
	}else{
		$hrs = $value[2];
	}
	
	$servicehtml .= '
<tr>
<td>'.(esc_html($key)+1).'</td>
<td>'.esc_html($servicename).'</td>
<td>'.esc_html($value[1]).'</td>
<td>'.esc_html($hrs).'</td>
<td>'.esc_html($value[3]).'</td>
<td>'.esc_html($value[4]).'</td>
</tr>
';
	
	}
}

$now = time();
$date = $row->duedate;

if($row->status == 'pending' && strtotime($date) < $now){
	$status = esc_html__('Overdue', 'service-finder');
}else{
	$status = service_finder_translate_static_status_string($row->status);
}

$cuszipcode = (!empty($row->cuszipcode)) ? $row->cuszipcode : '';

$html = '
<div class="invoice-view">
  <div class="row">
    <div class="col-md-12"><span class="invoice-status">'.esc_html($status).'</span></div>
    <div class="col-md-6 col-sm-6">
      <h4>'.esc_html__('Invoice Manager', 'service-finder').'</h4>
      <table class="table">
        <tbody>
          <tr>
            <td>'.esc_html__('Name', 'service-finder').': '.esc_html($row->full_name).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Email', 'service-finder').': '.esc_html($row->email).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Phone', 'service-finder').': '.esc_html($row->phone).' '.esc_html($row->mobile).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Fax', 'service-finder').': '.esc_html($row->fax).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Address', 'service-finder').': '.esc_html($row->apt).' '.esc_html($row->address).'</td>
          </tr>
          <tr>
            <td>'.esc_html($row->city).', '.esc_html($row->state).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Postal Code', 'service-finder').': '.esc_html($row->zipcode).'</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-6 col-sm-6">
      <h4>'.esc_html__('Billed to', 'service-finder').': </h4>
      <table class="table">
        <tbody>
          <tr>
            <td>'.esc_html__('Attn', 'service-finder').': '.esc_html($row->name).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Email', 'service-finder').': '.esc_html($row->cusemail).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Phone', 'service-finder').': '.esc_html($row->cusphone).' '.esc_html($row->cusphone2).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Address', 'service-finder').': '.esc_html($row->cusapt).' '.esc_html($row->cusaddress).'</td>
          </tr>
          <tr>
            <td>'.esc_html($row->cuscity).', '.esc_html($row->cusstate).'</td>
          </tr>
          <tr>
            <td>'.esc_html__('Postal Code', 'service-finder').': '.esc_html($cuszipcode).'</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-6 col-sm-6">
      <h4>Invoice No <strong class="text-primary">'.esc_html($row->id).'</strong></h4>
    </div>
    <div class="col-md-6 col-sm-6">
      <table class="table">
        <tbody>
          <tr>
            <td><strong>Reference No: '.esc_html($row->reference_no).'</strong></td>
          </tr>
          <tr>
            <td><strong>'.esc_html__('Due Date', 'service-finder').': '.date('d-m-Y',strtotime($row->duedate)).'</strong></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <table class="table table-bordered table-hover invoice-margin-in">
    <thead>
      <tr>
        <th>'.esc_html__('No.', 'service-finder').'</th>
        <th>'.esc_html__('Service', 'service-finder').'</th>
        <th>'.esc_html__('Type', 'service-finder').'</th>
        <th>'.esc_html__('Hours', 'service-finder').'</th>
        <th>'.esc_html__('Description', 'service-finder').'</th>
        <th>'.esc_html__('Price', 'service-finder').'</th>
      </tr>
    </thead>
    <tbody>
    
    '.$servicehtml.'
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" class="text-right font-weight-600">'.esc_html__('Total', 'service-finder').' ('.service_finder_currencycode().')</td>
      <td colspan="2" class="text-right font-weight-600">'.service_finder_money_format($row->total).'</td>
    </tr>
    <tr>
      <td colspan="4" class="text-right font-weight-600">'.esc_html__('Discount', 'service-finder').'</td>
      <td colspan="2" class="text-right font-weight-600">'.esc_html($displaydiscount).'</td>
    </tr>
    <tr>
      <td colspan="4" class="text-right font-weight-600">'.esc_html__('Tax', 'service-finder').'</td>
      <td colspan="2" class="text-right font-weight-600">'.esc_html($displaytax).'</td>
    </tr>
    <tr class="info">
      <td colspan="4" class="text-right font-weight-600">'.esc_html__('Grand Total', 'service-finder').' ('.service_finder_currencycode().')</td>
      <td colspan="2" class="text-right font-weight-600">'.service_finder_money_format($row->grand_total).'</td>
    </tr>
    </tbody>
    
  </table>
</div>
';

return $html;
}

/*Quote extended section*/
function service_finder_quote_extend($userid){
global $wpdb,$service_finder_Tables,$service_finder_Params,$service_finder_options;
$url = str_replace('/','\/',$service_finder_Params['homeUrl']);
$adminajaxurl = str_replace('/','\/',admin_url('admin-ajax.php'));

$html = '';
$providers = service_finder_getRelatedProviders($userid,get_user_meta($userid,'primary_category',true),10);
if(!empty($providers)){

$bookingpagestyle = (isset($service_finder_options['booking-page-style'])) ? esc_attr($service_finder_options['booking-page-style']) : '';

if($bookingpagestyle == 'style-2'){
$html .= '<a href="javascript:;" class="btn-linking togglerelatedproviders toggle-quoterelated-providers">'.esc_html__('Send request to related providers also', 'service-finder').'</a>';
$html .= '<div class="show-quoterelated-providers" style="display:none">';
$html .= service_finder_quote_extend_providers_list($userid);
$html .= '<input type="button" value="'.esc_html__('Continue', 'service-finder').'" name="quoteto-relproviders" class="btn btn-primary hiderelatedproviders">';
$html .= '</div>';

}elseif($bookingpagestyle == 'style-1'){
$html .= '<a href="javascript:;" class="btn-linking" data-toggle="modal" data-target="#quoteto-related-providers">'.esc_html__('Send request to related providers also', 'service-finder').'</a>';

$html .= '<div id="quoteto-related-providers" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">
          '.esc_html__('Send quote request to related providers', 'service-finder').'
        </h4>
      </div>
        <div class="modal-body clearfix">';
		
		$html .= service_finder_quote_extend_providers_list($userid);

        $html .= '</div>
        <div class="modal-footer">
          <input type="button" data-dismiss="modal" value="'.esc_html__('Continue', 'service-finder').'" name="quoteto-relproviders" class="btn btn-primary">
        </div>
    </div>
  </div>
</div>';
}
}
$html .= '<div class="col-md-12 toggle-quoterelated-providers">
              <div class="sf-quote-wrap">
			  <div class="rwmb-field rwmb-plupload_image-wrapper">
                <div class="rwmb-input">
                  <ul class="rwmb-images rwmb-uploaded" data-field_id="quoteuploader" data-delete_nonce="" data-reorder_nonce="" data-force_delete="0" data-max_file_uploads="25"></ul>
                  <div id="quoteuploader-dragdrop" class="RWMB-drag-drop drag-drop hide-if-no-js new-files" data-upload_nonce="" data-js_options="{&quot;runtimes&quot;:&quot;html5,silverlight,flash,html4&quot;,&quot;file_data_name&quot;:&quot;async-upload&quot;,&quot;browse_button&quot;:&quot;quoteuploader-browse-button&quot;,&quot;drop_element&quot;:&quot;quoteuploader-dragdrop&quot;,&quot;multiple_queues&quot;:true,&quot;max_file_size&quot;:&quot;8388608b&quot;,&quot;url&quot;:&quot;'.esc_url($adminajaxurl).'&quot;,&quot;flash_swf_url&quot;:&quot;'.esc_url($url).'wp-includes\/js\/plupload\/plupload.flash.swf&quot;,&quot;silverlight_xap_url&quot;:&quot;'.esc_url($url).'wp-includes\/js\/plupload\/plupload.silverlight.xap&quot;,&quot;multipart&quot;:true,&quot;urlstream_upload&quot;:true,&quot;filters&quot;:[{&quot;title&quot;:&quot;Allowed  Files&quot;,&quot;extensions&quot;:&quot;doc,docx,jpg,jpeg,png,gif,pdf,xls,xlsx,rtf,txt,ppt,pptx&quot;}],&quot;multipart_params&quot;:{&quot;field_id&quot;:&quot;quoteuploader&quot;,&quot;action&quot;:&quot;file_upload&quot;}}">
                    <div class = "drag-drop-inside text-center">
					  <h4 class="sf-title">'.esc_html__('Drop files here', 'service-finder').'</h4>	
                      <p class="drag-drop-info">
                        '.esc_html__('(Valid Formats: doc, docx, pdf, xls, xlsx, rtf, txt, ppt, pptx, jpg, jpeg, png)', 'service-finder').'
                      </p>
                      <p>'.esc_html__('or', 'service-finder').'</p>
                      
                      <p class="drag-drop-buttons">
                        <input id="quoteuploader-browse-button" type="button" value="'.esc_html__('Select Files', 'service-finder').'" class="button btn btn-default" />
                      </p>
                      
                    </div>
                  </div>
                </div>
              </div>
			  </div>
            </div>';

return $html;
}

/*Quote extended section*/
function service_finder_quote_extend_providers_list($userid){

$providers = service_finder_getRelatedProviders($userid,get_user_meta($userid,'primary_category',true),10);

$html = '';
$html .= '<div class="checkbox sf-radio-checkbox sf-selectall-providers">
								<input id="allrelatedproviders" type="checkbox" name="selectallrelatedproviders" value="yes">
								<label for="allrelatedproviders">'.esc_html__('Select All', 'service-finder').'</label>
							  </div>';
		
		$html .= '<ul class="sf-quote-related-providers row">';
		foreach($providers as $provider){
		
		$link = service_finder_get_author_url($provider->wp_user_id);
		$settings = service_finder_getProviderSettings($provider->wp_user_id);

		if(!empty($provider->avatar_id) && $provider->avatar_id > 0){
			$src  = wp_get_attachment_image_src( $provider->avatar_id, 'service_finder-provider-thumb' );
			$src  = $src[0];
		}else{
			$src  = service_finder_get_default_avatar();
		}
		
		if($src != ''){
			$imgtag = '<img src="'.esc_url($src).'" width="358" height="259" alt="">';
		}else{
			$imgtag = '';
		}
		
		if(service_finder_is_featured($provider->wp_user_id)){
		$featured = '<strong class="sf-featured-label"><span>'.esc_html__( 'Featured', 'service-finder' ).'</span></strong>';
		}else{
		$featured = '';
		}
		
		$html .= '<li class="sf-selectpro-col">

                <div class="sf-rel-provider-outer item">
				<div class="sf-provider-bx item">
                    <div class="sf-element-bx">
                    
                        <div class="sf-thum-bx sf-listing-thum img-effect2" style="background-image:url('.esc_url($src).');"> <a href="'.esc_url($link).'" class="sf-listing-link"></a>
                            <strong class="sf-category-tag"><a href="'.esc_url(service_finder_getCategoryLink(get_user_meta($provider->wp_user_id,'primary_category',true))).'">'.service_finder_getCategoryName(get_user_meta($provider->wp_user_id,'primary_category',true)).'</a></strong>
							'.$featured.'
                        </div>
                        
                        <div class="padding-20 bg-white">
                            <strong class="sf-company-name"><a href="'.esc_url($link).'">'.service_finder_getExcerpts($provider->full_name,0,35).'</a></strong>
							'.service_finder_displayRating(service_finder_getAverageRating($provider->wp_user_id)).'
                            
                        </div>
                        
                    </div>
                </div>
				<div class="checkbox sf-radio-checkbox">
								<input id="rp-'.esc_attr($provider->wp_user_id).'" type="checkbox" class="quote-relatedprovider" name="relatedproviders[]" value="'.esc_attr($provider->wp_user_id).'">
								<label for="rp-'.esc_attr($provider->wp_user_id).'">'.service_finder_get_providername_with_link($provider->wp_user_id).'</label>
							  </div>
				</div>			  
            </li>';
			
		}
		$html .= '</ul>';
		
		return $html;
}

/*Quote related providers*/
function service_finder_quote_related_providers($quoteid){
global $wpdb,$service_finder_Tables;

$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quoteto_related_providers.' WHERE id = %d',$quoteid));

return $results;
}

/*Get number of replies*/
function service_finder_get_number_of_replies($quoteid){
global $wpdb,$service_finder_Tables;

$row1 = $wpdb->get_row($wpdb->prepare('SELECT count(id) as cnt FROM '.$service_finder_Tables->quotations.' WHERE status = "approved" AND id = %d AND (reply != "" OR quote_price > 0)',$quoteid));

$tem1 = $row1->cnt;

$row2 = $wpdb->get_row($wpdb->prepare('SELECT count(id) as cnt FROM '.$service_finder_Tables->quoteto_related_providers.' WHERE quote_id = %d AND (related_reply != "" OR related_quote_price > 0)',$quoteid));

$tem2 = $row2->cnt;

$totalreplies = intval($tem1) + intval($tem2);

return $totalreplies;

}

/*Get quote price*/
function service_finder_get_quote_price($quoteid,$userid){
global $wpdb,$service_finder_Tables;
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quotations.' WHERE `id` = %d AND `provider_id` = %d',$quoteid,$userid));
	$reply = '';
	if(!empty($row)){
		$quote_price = $row->quote_price;
		return $quote_price;
	}else{
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quoteto_related_providers.' WHERE `quote_id` = %d AND `related_provider_id` = %d',$quoteid,$userid));
	$reply = '';
	if(!empty($row)){
		$quote_price = $row->related_quote_price;
		return $quote_price;
	}
	}
}

/*Get quote price*/
function service_finder_get_quote_reply($quoteid,$userid){
global $wpdb,$service_finder_Tables;
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quotations.' WHERE `id` = %d AND `provider_id` = %d',$quoteid,$userid));
	$reply = '';
	if(!empty($row)){
		$reply = $row->reply;
		return $reply;
	}else{
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quoteto_related_providers.' WHERE `quote_id` = %d AND `related_provider_id` = %d',$quoteid,$userid));
	$reply = '';
	if(!empty($row)){
		$reply = $row->related_reply;
		return $reply;
	}
	}
}

/*Update quote hire nformation*/
function service_finder_update_quote_hired($quoteid,$userid){
global $wpdb,$service_finder_Tables;
	
	$data = array(
		'hired'		=> 'yes',
		'assignto'	=> $userid,
	);
	
	$where = array(
		'id'		=> $quoteid,
	);
	
	$wpdb->update($service_finder_Tables->quotations,wp_unslash($data),$where);
}

/*Get quote author*/
function service_finder_get_quote_author($quoteid){
global $wpdb,$service_finder_Tables;
	
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$service_finder_Tables->quotations.' WHERE `id` = %d',$quoteid));
	if(!empty($row)){
		$customerid = $row->customer_id;
		return $customerid;
	}
}

if (!function_exists('service_finder_allow_uploads_to_provider')) {
add_action('init', 'service_finder_allow_uploads_to_provider');

function service_finder_allow_uploads_to_provider() {
echo 'test';die;
	if (is_admin() && !current_user_can('administrator')) {
		wp_redirect(home_url('/'));
	}
	//Professional users
	$provider = get_role('Provider');
	$provider->add_cap('upload_files');

	$provider->add_cap('publish_posts');
	$provider->add_cap('edit_posts');
	$provider->add_cap('edit_published_posts');
	$provider->add_cap('edit_others_posts');
	$provider->add_cap('delete_posts');
	$provider->add_cap('delete_others_posts');
	$provider->add_cap('delete_published_posts');

	$provider->add_cap('publish_pages');
	$provider->add_cap('edit_pages');
	$provider->add_cap('edit_published_pages');
	$provider->add_cap('edit_others_pages');


}
}

/* Providers Availability Search Filter */
function service_finder_availability_search_filter($totalresult,$viewtype = ''){
global $service_finder_options;
?>
<form action="?" method="get">
<div class="row">
     <div class="col-md-6">
        <?php if($totalresult > 0){ ?>
          <h2 class="result-title">
          <?php 
          $allowedhtml = array(
                'strong' => array()
            );
          printf( wp_kses( '<strong>%s</strong> ', $allowedhtml ), $totalresult ); echo esc_html__('Results Found','service-finder');
          ?>
          </h2>
          <?php } ?>
     </div>
     <div class="col-md-6">
         <div class="sf-anybooking-select">
            <select class="sf-select-box form-control sf-form-control"  title="<?php esc_html_e('Search Result Based on','service-finder')?>" name="srhbybooking" id="srhbybooking">
              <option value=""><?php esc_html_e('Any','service-finder')?></option>
              <option value="on"><?php esc_html_e('Booking on','service-finder')?></option>
              <option value="off"><?php esc_html_e('Booking off','service-finder')?></option>
            </select>
        </div>
     </div>
</div>
<div class="row">

<div class="col-md-12">
<div class="sort-filter-bx clearfix <?php echo ($service_finder_options['booking-page-style'] == 'style-2') ? 'sf-srhfilter-v2' : ''; ?>">
    <h5 class="sf-tilte"><?php esc_html_e('Filter The Search','service-finder')?></h5>
    
    <div class="f-f-left" id="avlsrhfilter">
        <ul class="sf-search-any-option">
            <li class="sf-filterby-date">
                <div class="input-group input-append date srhdatepicker"> 
                    <span class="input-group-addon add-on" style="">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <input type="text" class="form-control sf-form-control" name="srhdate" id="srhdate" placeholder="<?php esc_html_e('Filter by Date', 'service-finder'); ?>" />
                </div>
            </li>
            <li>
                <select class="sf-select-box form-control sf-form-control"  title="<?php esc_html_e('Available in','service-finder')?>" name="srhperiod" id="srhperiod">
                              <option value="morning"><?php esc_html_e('Morning','service-finder')?></option>
                              <option value="afternoon"><?php esc_html_e('Afternoon','service-finder')?></option>
                              <option value="evening"><?php esc_html_e('Evening','service-finder')?></option>
                              <option value="any"><?php esc_html_e('Any Time','service-finder')?></option>
                            </select>
                        </li>
                        <li>
                            <select class="sf-select-box form-control sf-form-control"  title="<?php esc_html_e('Available for','service-finder')?>" name="srhtime" id="srhtime">
                  <?php
                  $intervals = array(15 => esc_html__('15 Mins', 'service-finder'),30 => esc_html__('30 Mins', 'service-finder'),45 => esc_html__('45 Mins', 'service-finder'),60 => esc_html__('1 Hr', 'service-finder'),75 => esc_html__('1 Hr 15 Mins', 'service-finder'),90 => esc_html__('1 Hr 30 Mins', 'service-finder'),105 => esc_html__('1 Hr 45 Mins', 'service-finder'),120 => esc_html__('2 Hrs', 'service-finder'),135 => esc_html__('2 Hr 15 Mins', 'service-finder'),150 => esc_html__('2 Hr 30 Mins', 'service-finder'),165 => esc_html__('2 Hr 45 Mins', 'service-finder'),180 => esc_html__('3 Hr', 'service-finder'),195 => esc_html__('3 Hr 15 Mins', 'service-finder'),210 => esc_html__('3 Hr 30 Mins', 'service-finder'),225 => esc_html__('3 Hr 45 Mins', 'service-finder'),240 => esc_html__('4 Hrs', 'service-finder'));
                  
                  if(!empty($intervals)){
                    foreach($intervals as $key => $value){
                        echo '<option value="'.esc_attr($key).'">'.esc_html($value).'</option>';	
                    }
                  }
                  ?>
                </select>
            </li>
            <li>
                <input type="button" name="avialabilityfilter" id="avialabilityfilter" value="<?php esc_html_e('Apply','service-finder'); ?>" class="btn btn-primary">
            </li>                                            
            
            
        </ul>
    </div>
    <div class="f-f-right">
        <ul class="sf-search-sortby">
            <li class="sf-select-sort-by">
                <select class="sf-select-box form-control sf-form-control"  title="<?php esc_html_e('SORT BY','service-finder')?>" name="setorderby" id="setorderby">
                  <option value="rating"><?php esc_html_e('Rating','service-finder')?></option>
                  <option value="title"><?php esc_html_e('Title','service-finder')?></option>
                  <option value="distance"><?php esc_html_e('Distance','service-finder')?></option>
                </select>
            </li>
            <li>
                <select class="sf-select-box form-control sf-form-control"  title="<?php esc_html_e('DESC','service-finder')?>" name="setorder" id="setorder">
                  <option value="asc"><?php esc_html_e('ASC','service-finder')?></option>
                  <option value="desc"><?php esc_html_e('DESC','service-finder')?></option>
                </select>
            </li>
            <li>
                <select class="sf-select-box form-control sf-form-control"  title="9" name="numberofpages" id="numberofpages">
                  <option value="9">9</option>
                  <option value="12">12</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                  <option value="25">25</option>
                  <option value="30">30</option>
                </select>
            </li>
        </ul>  
        <ul class="sf-search-grid-option" id="viewTypes">
            <?php 
			if($service_finder_options['booking-page-style'] == 'style-1'){ 
			if($viewtype != ""){
			?>
			<li data-view="grid-4" class="<?php echo ($viewtype == "grid-4" || $viewtype == "") ? 'active' : '' ?>">
              <button type="button" class="btn btn-border btn-icon"><i class="col-4"><img class="pro-cat-img-in" alt="" src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL ?>/icons/grid4c.png"></i></button>
            </li>
            <li data-view="grid-3" class="<?php echo ($viewtype == "grid-3" || $viewtype == "") ? 'active' : '' ?>">
              <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th" ></i></button>
            </li>
            <li data-view="listview" class="<?php echo ($viewtype == "listview" || $viewtype == "") ? 'active' : '' ?>">
              <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-list" ></i></button>
            </li>
			<?php
			}else{
			?>
			<li data-view="grid-4" <?php echo service_finder_is_default_view("grid-4") ?>>
              <button type="button" class="btn btn-border btn-icon"><i class="col-4"><img class="pro-cat-img-in" alt="" src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL ?>/icons/grid4c.png"></i></button>
            </li>
            <li data-view="grid-3" <?php echo service_finder_is_default_view("grid-3") ?>>
              <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th" ></i></button>
            </li>
            <li data-view="listview" <?php echo service_finder_is_default_view("listview") ?>>
              <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-list" ></i></button>
            </li>
			<?php
			}
			?>
            
            
            <?php 
			}elseif($service_finder_options['booking-page-style'] == 'style-2'){ 
			if($viewtype != ""){
			?>
			<li data-view="grid-2" class="<?php echo ($viewtype == "grid-2" || $viewtype == "") ? 'active' : '' ?>">
                 <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-large"></i></button>
            </li>
            <li data-view="listview" class="<?php echo ($viewtype == "listview") ? 'active' : '' ?>">
                <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-list"></i></button>
            </li>
			<?php
			}else{
			?>
			<li data-view="grid-2"  <?php echo service_finder_is_default_view_style2("grid-2") ?>>
              <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-large"></i></button>
            </li>
            <li data-view="listview"  <?php echo service_finder_is_default_view_style2("listview") ?>>
              <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-list"></i></button>
            </li>				
			<?php
			}
			
			?>
            <?php } ?>
          </ul>                                   
    </div>
</div>
</div>

</div>
</form>
<?php
}
add_action('service_finder_availability_search_filter', 'service_finder_availability_search_filter',10,2);

/* Providers Availability Search Filter */
function service_finder_category_filter($setorderby = '',$setorder = '',$numberofpages = '',$viewtype = '',$defaultview = ''){
global $service_finder_options;
?>
<div class="sf-category-filter-bar">
<form class="sort-filter-bx pull-right" action="?" method="get">
    <?php if($service_finder_options['search-style'] == 'ajax-search'){ ?>
    <ul class="sf-search-sortby">
        <li class="sf-select-sort-by">
            <select class="form-control sf-select-box"  title="<?php esc_html_e('SORT BY','service-finder')?>" name="setorderby" id="setorderby">
              <option value="rating"><?php esc_html_e('Rating','service-finder')?></option>
              <option value="title"><?php esc_html_e('Title','service-finder')?></option>
            </select>
        </li>
        <li>
            <select class="form-control sf-select-box"  title="DESC" name="setorder" id="setorder">
              <option value="asc"><?php esc_html_e('ASC','service-finder')?></option>
              <option value="desc"><?php esc_html_e('DESC','service-finder')?></option>
            </select>
        </li>
        <li>
            <select class="form-control sf-select-box"  title="9" name="numberofpages" id="numberofpages">
              <option value="9">9</option>
              <option value="12">12</option>
              <option value="15">15</option>
              <option value="20">20</option>
              <option value="25">25</option>
              <option value="30">30</option>
            </select>
        </li>
    </ul>  
    <ul class="sf-search-grid-option" id="viewTypes">
        <li data-view="grid-4" <?php echo service_finder_is_default_view_category("grid-4") ?>>
          <button type="button" class="btn btn-border btn-icon"><i class="col-4"><img class="pro-cat-img-in" alt="" src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL ?>/icons/grid4c.png"></i></button>
        </li>
        <li data-view="grid-3" <?php echo service_finder_is_default_view_category("grid-3") ?>>
          <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th"></i></button>
        </li>
        <li data-view="listview" <?php echo service_finder_is_default_view_category("listview") ?>>
          <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-list"></i></button>
        </li>
    </ul>
	<?php }else{ ?>
    <ul class="sf-search-sortby">
        <li class="sf-select-sort-by">
            <select class="form-control"  title="<?php esc_html_e('SORT BY','service-finder')?>" name="setorderby" id="setorderby">
              <option <?php echo ($setorderby == "rating") ? 'selected="selected"' : '' ?> value="rating"><?php esc_html_e('Rating','service-finder')?></option>
              <option <?php echo ($setorderby == "title") ? 'selected="selected"' : '' ?> value="title"><?php esc_html_e('Title','service-finder')?></option>
            </select>
        </li>
        <li>
            <select class="form-control"  title="<?php esc_html_e('DESC','service-finder')?>" name="setorder" id="setorder">
              <option <?php echo ($setorder == "asc") ? 'selected="selected"' : '' ?> value="asc"><?php esc_html_e('ASC','service-finder')?></option>
              <option <?php echo ($setorder == "desc") ? 'selected="selected"' : '' ?> value="desc"><?php esc_html_e('DESC','service-finder')?></option>
            </select>
        </li>
        <li>
            <select class="form-control"  title="9" name="numberofpages" id="numberofpages">
              <option <?php echo ($numberofpages == "9") ? 'selected="selected"' : '' ?> value="9">9</option>
              <option <?php echo ($numberofpages == "12") ? 'selected="selected"' : '' ?> value="12">12</option>
              <option <?php echo ($numberofpages == "15") ? 'selected="selected"' : '' ?> value="15">15</option>
              <option <?php echo ($numberofpages == "20") ? 'selected="selected"' : '' ?> value="20">20</option>
              <option <?php echo ($numberofpages == "25") ? 'selected="selected"' : '' ?> value="25">25</option>
              <option <?php echo ($numberofpages == "30") ? 'selected="selected"' : '' ?> value="30">30</option>
            </select>
        </li>
    </ul>  
    <ul class="sf-search-grid-option" id="viewTypes">
        <li data-view="grid-4" class="<?php echo ($viewtype  == "grid-4" || $defaultview == "grid-4") ? 'active' : '' ?>">
          <button type="button" class="btn btn-border btn-icon"><i class="col-4"><img class="pro-cat-img-in" alt="" src="<?php echo SERVICE_FINDER_BOOKING_IMAGE_URL ?>/icons/grid4c.png"></i></button>
        </li>
        <li data-view="grid-3" class="<?php echo ($viewtype  == "grid-3" || $defaultview  == "grid-3") ? 'active' : '' ?>">
          <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th" ></i></button>
        </li>
        <li data-view="listview" class="<?php echo ($viewtype  == "listview" || $defaultview  == "listview") ? 'active' : '' ?>">
          <button type="button" class="btn btn-border btn-icon"><i class="fa fa-th-list" ></i></button>
        </li>
    </ul>
    <?php } ?>
</form>
</div>
<?php
}
add_action('service_finder_category_filter', 'service_finder_category_filter',10,2);

/* Hook for login after signup */
function service_finder_login_after_signup(){
$result = service_finder_sedateUserLogin($_SESSION['signup_username'],$_SESSION['signup_password']);
}
add_action('service_finder_login_after_signup', 'service_finder_login_after_signup',10,5);

/* Display services on provider public profile page */
function service_finder_display_services($author){
$services = service_finder_getServices($author);
$i=0;
$serviceflag = 0;
if(!empty($services)){
$serviceflag = 1;
service_finder_services_loop($services,$author);
}

$groups = service_finder_getServiceGroups($author);
if(!empty($groups)){
	foreach($groups as $group){
		
		$services = service_finder_getServices($author,'',$group->id);	
		$i=0;
		if(!empty($services)){
			$serviceflag = 1;
			echo '<div class="sf-service-group-title">'.$group->group_name.'</div>';
			service_finder_services_loop($services,$author);
		}
	}
}

if($serviceflag == 0){
	echo '<div class="sf-no-service">';
	esc_html_e('No Service Available', 'service-finder');
	echo '</div>';
}
}
add_action('service_finder_display_services', 'service_finder_display_services',10,1);

/* Loop for services */
function service_finder_services_loop($services = array(),$author = 0){
global $service_finder_options;
foreach($services as $service){
			
if($service->cost > 0){

if($service->cost_type == 'hourly'){

if($service->hours > 0){
$cost = '<span>'.service_finder_money_format($service->cost,'strong').'</span>';
$cost .= '<span><i class="fa fa-clock-o"></i> '.$service->hours.' '.esc_html__(' Hrs', 'service-finder').'</span>';
}else{
$cost = '<span>'.service_finder_money_format($service->cost,'strong').' / '.esc_html__('Hr', 'service-finder').'</span>';
}

}elseif($service->cost_type == 'days'){

if($service->days > 0){
$cost = '<span>'.service_finder_money_format($service->cost,'strong').'</span>';
$cost .= '<span><i class="fa fa-calendar"></i> '.$service->days.' '.esc_html__(' Days', 'service-finder').'</span>';
}else{
$cost = '<span>'.service_finder_money_format($service->cost,'strong').' / '.esc_html__('Day', 'service-finder').'</span>';
}

}elseif($service->cost_type == 'perperson'){

if($service->persons > 0){
$cost = '<span>'.service_finder_money_format($service->cost,'strong').'</span>';
$cost .= '<span><i class="fa fa-user"></i> '.$service->persons.' '.esc_html__(' Persons', 'service-finder').'</span>';
}else{
$cost = '<span>'.service_finder_money_format($service->cost,'strong').' / '.esc_html__('Person', 'service-finder').'</span>';
}

}else{
$cost = '<span>'.service_finder_money_format($service->cost,'strong').'</span>';
}
}else{
$requestquote = (!empty($service_finder_options['requestquote-replace-string'])) ? esc_attr($service_finder_options['requestquote-replace-string']) : esc_html__( 'Request a Quote', 'service-finder' );
if($service_finder_options['booking-page-style'] == 'style-1'){
$cost = '<a class="sf-requestquote-icon" href="#form-quot-bx" data-tool="tooltip" data-placement="top" title="'.$requestquote.'"><img src="'.SERVICE_FINDER_BOOKING_IMAGE_URL.'/requestaquote.png"></a>';
}elseif($service_finder_options['booking-page-style'] == 'style-2'){
$cost = '<a class="sf-requestquote-icon" href="javascript:void(0);" data-providerid="'.esc_attr($author).'" data-toggle="modal" data-target="#quotes-Modal" data-tool="tooltip" data-placement="top" title="'.$requestquote.'"><img src="'.SERVICE_FINDER_BOOKING_IMAGE_URL.'/requestaquote.png"></a>';
}
}
?>
<div class="panel sf-panel">
<div class="acod-head">
 <h6 class="acod-title text-uppercase">
	<a data-toggle="collapse" href="#services-row-<?php echo $service->id; ?>" data-parent="#sf-services-listing" >
		<span class="exper-author"> <?php echo stripcslashes($service->service_name); ?></span>
	</a>
	<?php if($service->offer == 'yes' && service_finder_offers_method($author) == 'services'){ ?>
	<strong  class="sf-service-offer-label sf-booking-payment-info" data-toggle="popover" data-container="body" data-placement="top" type="button" data-html="true" id="offers-<?php echo $service->id; ?>" data-trigger="hover"><i class="fa fa-gift"></i> <?php echo esc_html__('offer', 'service-finder'); ?></strong>
	<div id="popover-content-offers-<?php echo $service->id; ?>" class="hide">
		<ul class="list-unstyled margin-0 booking-payment-data">
			<li><strong><?php echo esc_html($service->offer_title); ?></strong></li>
			<li><?php printf($service->discount_description); ?></li>
		</ul>
	</div>
	<?php } ?>
 </h6>
 <div class="sf-servicemeta">
	<?php printf($cost); ?>
 </div>
</div>
<div id="services-row-<?php echo $service->id; ?>" class="acod-body collapse">
<div class="acod-content p-tb15"><?php echo nl2br(stripcslashes($service->description)); ?></div>
</div>
</div>
<?php		
}
}

/*Quote extended section*/
function service_finder_add_media_button(){
$html = '<button type="button" id="insert-add-media" class="button insert-media add_media sf-add-media"><span class="wp-media-buttons-icon"></span> '.esc_html__('Add Media', 'service-finder').'</button>';

return $html;
}