<?php

add_action( 'wp_enqueue_scripts', 'child_theme_scripts' );
function child_theme_scripts() {
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'twenty-twenty-one-style' ) );
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/script.js', array( 'jquery' ), false, true );
}

add_action( 'init', 'create_taxonomies' );
function create_taxonomies() {
	register_taxonomy( 'announcement_type', [ 'announcement' ], [
		'label'                 => 'Announcement Type',
		'labels'                => [
			'name'              => 'Announcement Types',
			'singular_name'     => 'Announcement Type',
			'search_items'      => 'Search Announcement Types',
			'all_items'         => 'All Announcement Types',
			'view_item '        => 'View Announcement Type',
			'parent_item'       => 'Parent Announcement Type',
			'parent_item_colon' => 'Parent Announcement Type:',
			'edit_item'         => 'Edit Announcement Type',
			'update_item'       => 'Update Announcement Type',
			'add_new_item'      => 'Add New Announcement Type',
			'new_item_name'     => 'New Announcement Type Name',
			'menu_name'         => 'Announcement Type',
		],
		'description'           => '',
		'public'                => true,
		'hierarchical'          => false,
		'rewrite'               => true,
		'capabilities'          => array(),
		'meta_box_cb'           => 'post_categories_meta_box',
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'rest_base'             => null,
	] );
};

add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type( 'announcement', [
		'label'  => 'Announcement',
		'labels' => [
			'name'               => 'Announcements', 
			'singular_name'      => 'Announcement',
			'add_new'            => 'New Announcement',
			'add_new_item'       => 'Add New Announcement',
			'edit_item'          => 'Edit Announcement',
			'new_item'           => 'New Announcement',
			'view_item'          => 'View Announcement',
			'search_items'       => 'Search Announcement',
			'not_found'          => 'Announcement ',
			'not_found_in_trash' => 'Not found in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Announcements',
		],
		'description'         => '',
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_rest'        => null,
		'rest_base'           => null,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-pressthis',
		'hierarchical'        => false,
		'supports'            => ['title', 'excerpt', 'thumbnail'],
		'taxonomies'          => ['announcement_type'],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
};

add_action( 'add_meta_boxes', 'add_price_meta_box' );
function add_price_meta_box() {
	add_meta_box( 'price_id', 'Price', 'price_meta_box_callback', 'announcement', 'side' );
}
function price_meta_box_callback( $post, $meta ){
	$value = get_post_meta( $post->ID, 'price_meta_key', true );
	echo '<input type="text" id="price_field" name="price_field" value="'. $value .'" />';
}

add_action( 'save_post', 'save_announcement_price' );
function save_announcement_price( $post_id ) {

	if ( ! isset( $_POST['price_field'] ) ) return;
	$price = sanitize_text_field( $_POST['price_field'] );
  $price = !is_numeric( $price ) ? '' : $price;
	update_post_meta( $post_id, 'price_meta_key', $price );
}

add_shortcode( 'announcements_amount' , 'display_announcements_amount' );
function display_announcements_amount() {
  $total = wp_count_posts( 'announcement' )->publish;
  $url = home_url() . '/announcements';
  return '<a href="' . $url . '">Total ads: ' . $total . '</a>';
};

function charlimit( $string, $limit ) {
  return substr( $string, 0, $limit ) . ( strlen( $string ) > $limit ? "..." : '' );
}

function post_view_set() { 
  $post_id = absint( $_POST['pid'] );
  $views_count = get_post_meta( $post_id, '_views_count', true );
  if ( !$views_count ) :
    $views_count = 1;
  else :
    $views_count++;
  endif;
  update_post_meta( $post_id, '_views_count', $views_count );
  echo $views_count;
  exit;
}
add_action('wp_ajax_post_view_set', 'post_view_set');
add_action('wp_ajax_nopriv_post_view_set', 'post_view_set');