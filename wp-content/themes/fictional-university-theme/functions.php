<?php
	require get_theme_file_path('/inc/search-route.php');
	require get_theme_file_path('/inc/like-route.php');
?>
<?php function pageBanner($args=NULL){

	$imageBanner = $args['photo']? $args['photo']:
		( 
			get_field('page_banner_background_image') && !is_archive() && !is_home() 
      ? get_field('page_banner_background_image')['sizes']['pageBanner'] 
      : get_theme_file_uri('/images/ocean.jpg')
		);
?>
	<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $imageBanner;?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ? $args['title']:get_the_title(); ?></h1>
      <div class="page-banner__intro">
        <p><?php  echo $args['subtitle'] ? $args['subtitle']:get_field('page_banner_subtitle');?></p>
      </div>
    </div>  
  </div>	
<?php }; ?>
<?php

function university_files() {
	wp_enqueue_style('font-google', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	// wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
	// wp_enqueue_style('university_main_styles', get_stylesheet_uri());
	wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyBafzMxmcJbQz8TSn7sHF6QjPX0V4ZrP3w', NULL, '1.0', true);
	// wp_enqueue_script('axios', '//unpkg.com/axios/dist/axios.min.js', NULL, '1.0', true);

	if(strstr($_SERVER['SERVER_NAME'], 'wpibs.test')) {
		wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
	} else {
		wp_enqueue_script('our-vender-js', get_theme_file_uri('/bundled-assets/vendors~scripts.c0230591fee5550328c6.js'), NULL, '1.0', true);
		wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.0f6790c80679290d57a3.js'), NULL, '1.0', true);
		wp_enqueue_style('our-main-styles',  get_theme_file_uri('/bundled-assets/styles.0f6790c80679290d57a3.css'));
	}

	wp_localize_script('main-university-js', 'universityData', [
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest'),
	]);

}

add_action('wp_enqueue_scripts', 'university_files');


function university_features() {
	register_nav_menu('headerMenuLocation', 'Header Menu Location');
	register_nav_menu('footerLocation1', 'Footer Location 1');
	register_nav_menu('footerLocation2', 'Footer Location 2');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('professorLandscape', 400, 260, true);
	add_image_size('professorPortrait', 480, 650, true);
	add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
	// Events Page
	if(!is_admin() && is_post_type_archive('event')){
		// $query->set('posts_per_page', 20);
		$query->set('meta_key', 'event_date');
		$query->set('orderby', 'meta_value');
		$query->set('order', 'ASC');
		$query->set('meta_query', [
			['key'=> 'event_date', 'compare'=>'>=', 'value'=> date('Ymd'), 'type'=>'numeric']
		]);
	}
	
	// Programs Page
	if(!is_admin() && is_post_type_archive('program')){
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1); // show all posts
	}

	// Campus Page
	if(!is_admin() && is_post_type_archive('campus')){
		$query->set('posts_per_page', -1); // show all posts
	}

}

add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api) {
	$api['key'] = 'AIzaSyBafzMxmcJbQz8TSn7sHF6QjPX0V4ZrP3w';
	return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');


function university_custom_rest() {
	register_rest_field('post', 'authorName', [
		'get_callback'=>function() {return get_the_author();}
	]);

	register_rest_field('post', 'perfectlyCropImageURL', [
		'get_callback'=>function() {return get_the_author();}
	]);

	register_rest_field('note', 'userNoteCount', [
		'get_callback'=>function() {return count_user_posts(get_current_user_id(), 'note');}
	]);
}

add_action('rest_api_init', 'university_custom_rest');

// Redirect Subscriber accounts out of admin and onto homepage
function redirectSubsToFrontend() {
	$ourCurrentUser = wp_get_current_user();
	if(count($ourCurrentUser->roles)==1 && $ourCurrentUser->roles[0]=='subscriber') {
		wp_redirect(site_url('/'));
		exit;
	}
}
add_action('admin_init', 'redirectSubsToFrontend');

// hide admin bar
function noSubsAdminBar() {
	$ourCurrentUser = wp_get_current_user();
	if(count($ourCurrentUser->roles)==1 && $ourCurrentUser->roles[0]=='subscriber') {
		show_admin_bar(false);
	}
}
add_action('wp_loaded', 'noSubsAdminBar');

// Customize Login Screen'
function ourHeaderUrl() {
	return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'ourHeaderUrl'); 

//
function ourLoginCSS() {
	wp_enqueue_style('font-google', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('our-main-styles',  get_theme_file_uri('/bundled-assets/styles.0f6790c80679290d57a3.css'));
}
add_action('login_enqueue_scripts', 'ourLoginCSS');


// set login title
function ourLoginTitle() {
	return get_bloginfo('name');
}
add_filter('login_headertitle', 'ourLoginTitle');

// Force note posts to be private
function makeNotePrivate($data, $postarr) {
	if($data['post_type']=='note') {
		if(count_user_posts(get_current_user_id(), 'note')>4 && !$postarr['ID']) {
			die("You have reached your note limit.");
		}

		$data['post_title'] = sanitize_text_field($data['post_title']);
		$data['post_content'] = sanitize_textarea_field($data['post_content']);
	}

	if($data['post_type']=='note' AND $data['post_status']!='trash') {
		$data['post_status'] = 'private';
	}
	return $data;
}
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);


function ignoreCertainFiles() {
	$exclude_filters[] = 'themes/fictional-university-theme/node_modules';
	return $exclude_filters;
}
add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');
?>