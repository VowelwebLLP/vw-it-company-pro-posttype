<?php 
/*
 Plugin Name: VW IT Company Pro Posttype
 lugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW IT Company Pro Theme.
 Author: VW Themes
 Version: 1.0
 Author URI: https://www.vwthemes.com/
*/

define( 'VW_IT_COMPANY_PRO_POSTTYPE_VERSION', '1.0' );
add_action( 'init', 'createcategory');
add_action( 'init', 'vw_it_company_pro_posttype_create_post_type' );

function vw_it_company_pro_posttype_create_post_type() {
  register_post_type( 'project',
    array(
      'labels' => array(
        'name' => __( 'Project','vw-it-company-pro-posttype' ),
        'singular_name' => __( 'Project','vw-it-company-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-portfolio',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );

  register_post_type( 'team',
    array(
      'labels' => array(
        'name' => __( 'Our Team','vw-it-company-pro-posttype' ),
        'singular_name' => __( 'Our Team','vw-it-company-pro-posttype' )
      ),
        'capability_type' => 'post',
        'menu_icon'  => 'dashicons-groups',
        'public' => true,
        'supports' => array( 
          'title',
          'editor',
          'thumbnail'
      )
    )
  );

  register_post_type( 'testimonials',
    array(
  		'labels' => array(
  			'name' => __( 'Testimonials','vw-it-company-pro-posttype' ),
  			'singular_name' => __( 'Testimonials','vw-it-company-pro-posttype' )
  		),
  		'capability_type' => 'post',
  		'menu_icon'  => 'dashicons-businessman',
  		'public' => true,
  		'supports' => array(
  			'title',
  			'editor',
  			'thumbnail'
  		)
		)
	);
}

/*--------------- Project section ----------------*/
function createcategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Project Category', 'vw-it-company-pro-posttype' ),
    'singular_name'     => __( 'Project Category', 'vw-it-company-pro-posttype' ),
    'search_items'      => __( 'Search Ccats', 'vw-it-company-pro-posttype' ),
    'all_items'         => __( 'All Project Category', 'vw-it-company-pro-posttype' ),
    'parent_item'       => __( 'Parent Project Category', 'vw-it-company-pro-posttype' ),
    'parent_item_colon' => __( 'Parent Project Category:', 'vw-it-company-pro-posttype' ),
    'edit_item'         => __( 'Edit Project Category', 'vw-it-company-pro-posttype' ),
    'update_item'       => __( 'Update Project Category', 'vw-it-company-pro-posttype' ),
    'add_new_item'      => __( 'Add New Project Category', 'vw-it-company-pro-posttype' ),
    'new_item_name'     => __( 'New Project Category Name', 'vw-it-company-pro-posttype' ),
    'menu_name'         => __( 'Project Category', 'vw-it-company-pro-posttype' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'createcategory' ),
  );
  register_taxonomy( 'createcategory', array( 'project' ), $args );
}
/* Adds a meta box to the portfolio editing screen */
function vw_it_company_pro_posttype_bn_work_meta_box() {
  add_meta_box( 'vw-it-company-pro-posttype-portfolio-meta', __( 'Enter Details', 'vw-it-company-pro-posttype' ), 'vw_it_company_pro_posttype_bn_work_meta_callback', 'project', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_it_company_pro_posttype_bn_work_meta_box');
}
/* Adds a meta box for custom post */
function vw_it_company_pro_posttype_bn_work_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'vw_project_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  //date details
  if(!empty($bn_stored_meta['vw_work_project_url'][0]))
    $bn_vw_work_project_url = $bn_stored_meta['vw_work_project_url'][0];
  else
    $bn_vw_work_project_url = '';
  ?>
  <div id="portfolios_custom_stuff">
    <table id="list">
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php esc_html_e( 'Project Url', 'vw-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="vw_work_project_url" id="vw_work_project_url" value="<?php echo esc_attr( $bn_vw_work_project_url ); ?>" />
          </td>
        </tr>

      </tbody>
    </table>
  </div>
  <?php
}

/* Saves the custom meta input */
function vw_it_company_pro_posttype_bn_meta_work_save( $post_id ) {
  if (!isset($_POST['vw_project_meta_nonce']) || !wp_verify_nonce($_POST['vw_project_meta_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

   // Save desig.
  if( isset( $_POST[ 'vw_work_project_url' ] ) ) {
    update_post_meta( $post_id, 'vw_work_project_url', esc_url($_POST[ 'vw_work_project_url']) );
  }
}

add_action( 'save_post', 'vw_it_company_pro_posttype_bn_meta_work_save' );

/*------------------------- Team Section -----------------------------*/
/* Adds a meta box for Designation */
function vw_it_company_pro_posttype_bn_team_meta() {
    add_meta_box( 'vw_it_company_pro_posttype_bn_meta', __( 'Enter Details','vw-it-company-pro-posttype' ), 'vw_it_company_pro_posttype_ex_bn_meta_callback', 'team', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_it_company_pro_posttype_bn_team_meta');
}
/* Adds a meta box for custom post */
function vw_it_company_pro_posttype_ex_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_it_company_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );

    //Email details
    if(!empty($bn_stored_meta['meta-desig'][0]))
      $bn_meta_desig = $bn_stored_meta['meta-desig'][0];
    else
      $bn_meta_desig = '';

    //Phone details
    if(!empty($bn_stored_meta['meta-call'][0]))
      $bn_meta_call = $bn_stored_meta['meta-call'][0];
    else
      $bn_meta_call = '';

    //facebook details
    if(!empty($bn_stored_meta['meta-facebookurl'][0]))
      $bn_meta_facebookurl = $bn_stored_meta['meta-facebookurl'][0];
    else
      $bn_meta_facebookurl = '';

    //linkdenurl details
    if(!empty($bn_stored_meta['meta-linkdenurl'][0]))
      $bn_meta_linkdenurl = $bn_stored_meta['meta-linkdenurl'][0];
    else
      $bn_meta_linkdenurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-twitterurl'][0]))
      $bn_meta_twitterurl = $bn_stored_meta['meta-twitterurl'][0];
    else
      $bn_meta_twitterurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-googleplusurl'][0]))
      $bn_meta_googleplusurl = $bn_stored_meta['meta-googleplusurl'][0];
    else
      $bn_meta_googleplusurl = '';

    //twitterurl details
    if(!empty($bn_stored_meta['meta-designation'][0]))
      $bn_meta_designation = $bn_stored_meta['meta-designation'][0];
    else
      $bn_meta_designation = '';

    ?>
    <div id="agent_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                    <td class="left">
                        <?php esc_html_e( 'Email', 'vw-it-company-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-desig" id="meta-desig" value="<?php echo esc_attr($bn_meta_desig); ?>" />
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Phone Number', 'vw-it-company-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-call" id="meta-call" value="<?php echo esc_attr($bn_meta_call); ?>" />
                    </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php esc_html_e( 'Facebook Url', 'vw-it-company-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_url($bn_meta_facebookurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php esc_html_e( 'Linkedin URL', 'vw-it-company-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_url($bn_meta_linkdenurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php esc_html_e( 'Twitter Url', 'vw-it-company-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_meta_twitterurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php esc_html_e( 'GooglePlus URL', 'vw-it-company-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_url($bn_meta_googleplusurl); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Designation', 'vw-it-company-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($bn_meta_designation); ?>" />
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom Designation meta input */
function vw_it_company_pro_posttype_ex_bn_metadesig_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', esc_html($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', esc_html($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url($_POST[ 'meta-googleplusurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', esc_html($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'vw_it_company_pro_posttype_ex_bn_metadesig_save' );

add_action( 'save_post', 'bn_meta_save' );
/* Saves the custom meta input */
function bn_meta_save( $post_id ) {
  if( isset( $_POST[ 'vw_it_company_pro_posttype_team_featured' ] )) {
      update_post_meta( $post_id, 'vw_it_company_pro_posttype_team_featured', esc_attr(1));
  }else{
    update_post_meta( $post_id, 'vw_it_company_pro_posttype_team_featured', esc_attr(0));
  }
}

/*----------------------Testimonial section ----------------------*/
/* Adds a meta box to the Testimonial editing screen */
function vw_it_company_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'vw-it-company-pro-posttype-testimonial-meta', __( 'Enter Details', 'vw-it-company-pro-posttype' ), 'vw_it_company_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_it_company_pro_posttype_bn_testimonial_meta_box');
}
/* Adds a meta box for custom post */
function vw_it_company_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'vw_it_company_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  if(!empty($bn_stored_meta['vw_it_company_pro_posttype_testimonial_desigstory'][0]))
      $bn_vw_it_company_pro_posttype_testimonial_desigstory = $bn_stored_meta['vw_it_company_pro_posttype_testimonial_desigstory'][0];
    else
      $bn_vw_it_company_pro_posttype_testimonial_desigstory = '';
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php _e( 'Designation', 'vw-it-company-pro-posttype' )?>
					</td>
					<td class="left" >
						<input type="text" name="vw_it_company_pro_posttype_testimonial_desigstory" id="vw_it_company_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $bn_vw_it_company_pro_posttype_testimonial_desigstory ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function vw_it_company_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['vw_it_company_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['vw_it_company_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'vw_it_company_pro_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'vw_it_company_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'vw_it_company_pro_posttype_testimonial_desigstory']) );
	}
}

add_action( 'save_post', 'vw_it_company_pro_posttype_bn_metadesig_save' );

/*------------- Team Shortcode --------------*/
function vw_it_company_pro_posttype_team_func( $atts ) {
    $team = ''; 
    $team = '<div class="row" id="team">';
      $new = new WP_Query( array( 'post_type' => 'team') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $designation = get_post_meta($post_id,'meta-designation',true);
          $call = get_post_meta($post_id,'meta-call',true);
          $email = get_post_meta($post_id,'meta-desig',true);
          $facebookurl = get_post_meta($post_id,'meta-facebookurl',true);
          $linkedin = get_post_meta($post_id,'meta-linkdenurl',true);
          $twitter = get_post_meta($post_id,'meta-twitterurl',true);
          $googleplus = get_post_meta($post_id,'meta-googleplusurl',true);

          $team .= '
          <div class="col-md-3">
            <div class="teambox">';
              if (has_post_thumbnail()){
               $team .= '<img src="'.esc_url($url).'">
                <div class="teambox-content">
                  <h4 class="teamtitle"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
                  if($designation != ''){
                    $team .= '<p>'.esc_html($designation).'</p>';
                  }
                  $team .= '
                  <ul class="teamicon">';
                    if($facebookurl != '' || $linkedin != '' || $twitter != '' || $googleplus != ''){
                   $team .= '<li>';    
                       if($facebookurl != '')
                       {
                        $team .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                       } 
                    $team .= '</li><li>';   
                       if($twitter != '')
                       {
                        $team .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';                          
                       }
                    $team .= '</li><li>';     
                       if($linkedin != '')
                       {
                       $team .= ' <a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                       }
                    $team .= '</li><li>';   
                       if($googleplus != '')
                       {
                        $team .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                       }
                    $team .= '</li>';    
                    }
                  $team .= '</ul>
                </div>';
              }
            $team .= '</div>
            </div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
      else :
        $team = '<div id="team" class="team_wrap col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-it-company-pro-posttype').'</h2></div>';
      endif;
    $team .= '</div>';
    return $team;
}
add_shortcode( 'vw-it-company-pro-team', 'vw_it_company_pro_posttype_team_func' );

/*------------------- Testimonial Shortcode -------------------------*/
function vw_it_company_pro_posttype_testimonials_func( $atts ) {
    $testimonial = ''; 
    $testimonial = '<div id="testimonials"><div class="row testimonial_shortcodes">';
      $new = new WP_Query( array( 'post_type' => 'testimonials') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $excerpt = vw_it_company_pro_string_limit_words(get_the_excerpt(),20);
          $designation = get_post_meta($post_id,'vw_it_company_pro_posttype_testimonial_desigstory',true);

          $testimonial .= '<div class="col-lg-6 col-md-6 col-sm-6 mb-4">
          <div class="testimonial_box text-center">
            <div class="qoute_text pb-3">'.$excerpt.'</div>';

                if (has_post_thumbnail()){
                    $testimonial.= '<img src="'.esc_url($url).'">';
                    }
               $testimonial .= '
                <h4 class="testimonial_name testimonial_short"><a href="'.get_the_permalink().'">'.get_the_title().'</a> <cite class="testimonial_short">'.esc_html($designation).'</cite></h4>
              </div></div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
      else :
        $testimonial = '<div id="testimonial" class="testimonial_wrap col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-it-company-pro-posttype').'</h2></div>';
      endif;
    $testimonial .= '</div></div>';
    return $testimonial;
}
add_shortcode( 'vw-it-company-pro-testimonials', 'vw_it_company_pro_posttype_testimonials_func' );

/*---------------- Project Shortcode ---------------------*/
function vw_it_company_pro_posttype_project_func( $atts ) {
    $project = ''; 
    $project = '<div id="our_project" class="row project_tab_content">';
      $new = new WP_Query( array( 'post_type' => 'project') );
      if ( $new->have_posts() ) :
        $k=1;
        while ($new->have_posts()) : $new->the_post();
          $post_id = get_the_ID();
          $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
          $excerpt = vw_it_company_pro_string_limit_words(get_the_excerpt(),12);
          $project .= '<div class="col-lg-4 col-md-6 col-sm-6 mb-4">
              <div class="box">';
                if (has_post_thumbnail()){
                  $project.= '<img src="'.esc_url($url).'">';
                  }
                $project.= '<div class="box-content">
                    <h4 class="title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>
                    <p class="post">'.$excerpt.'</p>
                    <ul class="icon">
                        <li><a href="'.get_the_permalink().'"><i class="fa fa-link"></i></a></li>
                    </ul>
                </div>
              </div>
            </div>';
          $k++;         
        endwhile; 
        wp_reset_postdata();
        $project.= '</div>';
      else :
        $project = '<div id="our_project" class="col-md-3 mt-3 mb-4"><h2 class="center">'.__('Not Found','vw-it-company-pro-posttype').'</h2></div>';
      endif;
      $project.= '</div>';
    return $project;
}
add_shortcode( 'vw-it-company-pro-project', 'vw_it_company_pro_posttype_project_func' );