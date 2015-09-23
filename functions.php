<?php
/**
 * visainterns functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package visainterns
 */

if ( ! function_exists( 'visainterns_setup' ) ) :

// 1. customize ACF path
add_filter('acf/settings/path', 'visainterns_acf_settings_path');
function visainterns_acf_settings_path( $path ) {

    // update path
    $path = get_stylesheet_directory() . '/inc/acf/';

    // return
    return $path;

}

// 2. customize ACF dir
add_filter('acf/settings/dir', 'visainterns_acf_settings_dir');
function visainterns_acf_settings_dir( $dir ) {

    // update path
    $dir = get_stylesheet_directory_uri() . '/inc/acf/';

    // return
    return $dir;

}

// 3. Hide ACF field group menu item
//add_filter('acf/settings/show_admin', '__return_false');
// 4. Include ACF
include_once( get_stylesheet_directory() . '/inc/acf/acf.php' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function visainterns_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on visainterns, use a find and replace
	 * to change 'visainterns' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'visainterns', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'visainterns' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

  add_image_size ( 'post-card-desktop', 190, 100, true );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'visainterns_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	//Shut Down Admin Bar for development
	add_filter('show_admin_bar', '__return_false');

}
endif; // visainterns_setup
add_action( 'after_setup_theme', 'visainterns_setup' );

function admin_menu_chrome_bug_fix() {
  echo '<style>
      #adminmenu { transform: translateZ(0); }
  </style>';
}
add_action('admin_head', 'admin_menu_chrome_bug_fix');


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function visainterns_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'visainterns_content_width', 640 );
}
add_action( 'after_setup_theme', 'visainterns_content_width', 0 );

/**
 * Post class modifications to add no thumbnail class
 *
 */

$GLOBALS['visainterns_zebra_izer'] = 0;

function visainterns_add_zebra_classes_if_no_thumbnail( $classes ) {
  global $post, $visainterns_zebra_izer;
  if (!has_post_thumbnail( $post->ID )) {
    $triples = $visainterns_zebra_izer % 3;
    switch ($triples) {
      case 2:
        $classes[] = 'post-triple';
        break;
      case 1:
        $classes[] = 'post-double';
        break;
      default:
        $classes[] = 'post-single';
        break;
    }
    $visainterns_zebra_izer++;
    $classes[] = 'no-post-thumbnail';
  }
  return $classes;
}
add_filter( 'post_class', 'visainterns_add_zebra_classes_if_no_thumbnail' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function visainterns_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'visainterns' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'visainterns_widgets_init' );

function visainterns_push_fixed_nav() {
	echo '<style type="text/css">';
	echo '.navbar-fixed-top {top: 32px !important;}';
	echo '</style>';
}

/**
 * Enqueue scripts and styles.
 */
function visainterns_scripts() {

	$scripts_version = 1;
wp_enqueue_style( 'customscrollbar-style',get_stylesheet_directory_uri() . '/js/customScrollbar/jquery.mCustomScrollbar.css' );
  wp_enqueue_style( 'animate.css', get_stylesheet_directory_uri() . '/bower_components/animate.css/animate.css' );
	wp_enqueue_style( 'visainterns-style', get_stylesheet_uri() );

	//wp_enqueue_script( 'livereload', 'http://visa.interns.ingenuitydesign.com:35729/livereload.js', array());

	// visainterns scripts
	// VENDOR
	wp_enqueue_script( 'visainterns-skip-link-focus-fix', get_stylesheet_directory_uri() . '/js/skip-link-focus-fix.js', array(), $scripts_version, true );
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js', array('jquery'), $scripts_version, true );
	wp_enqueue_script( 'html5shiv', get_stylesheet_directory_uri() . '/bower_components/html5shiv/dist/html5shiv.min.js', array(), $scripts_version, false );
	wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri() . '/bower_components/modernizr/modernizr.js', array(), $scripts_version, false );
	wp_enqueue_script( 'respond', get_stylesheet_directory_uri() . '/bower_components/respond/dest/respond.min.js', array(), $scripts_version, false );
	wp_enqueue_script( 'touchswipe', get_stylesheet_directory_uri() . '/bower_components/jquery-touchswipe/jquery.touchSwipe.min.js', array(), $scripts_version, false );
	wp_enqueue_script( 'visainterns-script', get_stylesheet_directory_uri() . '/js/visainterns.min.js', array('bootstrap'), $scripts_version, true );
	wp_enqueue_script( 'custom-scrollbar', get_stylesheet_directory_uri() . '/js/customScrollbar/mCustomScrollbar.concat.min.js', array('jquery'), $scripts_version, true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if (is_admin_bar_showing()) {
		add_action( 'wp_head', 'visainterns_push_fixed_nav' );
	}
}
add_action( 'wp_enqueue_scripts', 'visainterns_scripts' );

/**
 * Get required other functions
 */
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Custom functions for generating Social feeds.
 */
require get_template_directory() . '/inc/feeds.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

add_filter('wp_mail_from','custom_wp_mail_from');
function custom_wp_mail_from($email) {
	return get_option('mail_from');
}
add_filter('wp_mail_from_name','custom_wp_mail_from_name');
function custom_wp_mail_from_name($name) {
	return get_option('mail_from_name');
}

function visainterns_contact_ajax(){
	//wp_verify_nonce( 'my-special-string', 'security' );
	//var_dump($_REQUEST); die();
	if(!isset($_REQUEST['email'])){
		echo json_encode("error");
		die();
	}
	$from 	  = 'Visa Recruiting <visarecruiting@tapin2tech.com>';
	$email 	  = htmlspecialchars(stripslashes(trim($_REQUEST['email'])));
	$location = htmlspecialchars(stripslashes(trim($_REQUEST['location'])));
	//$headers  = ['From: '.$from,'Reply-To: '. $from, 'MIME-Version: 1.0','Content-type: text/plain; charset=iso-8859-1','X-Mailer: PHP/'.phpversion()];
	$url  	  = get_site_url() . '?loc=' . $location;
	$message  = visainterns_messagebody($url,$_REQUEST['round']);
	$wasSent  = wp_mail( $email, "You're invited to the the Visa Recruiting Session", $message);//,$headers
	if(!$wasSent){
		echo json_encode($GLOBALS['phpmailer']->ErrorInfo);
		die();
	}
	echo json_encode(['success'=>'true']);
	echo json_encode($url);
	die();

}

add_filter( 'wp_mail_content_type', 'visainterns_email_content_type' );
function visainterns_email_content_type( $content_type ) {
	return 'text/html';
}

function visainterns_messagebody($url,$rnd){
	$topimg = ($rnd == 2) ? 'eml2_top.jpg' : 'eml_top.png' ;
	$url    = ($rnd == 2) ? 'https://visa.recsolucampus.com/candidatepreferenceform.php?formId=YQ%3D%3D' : $url;
	return "<style>body{margin:0;}</style>
			<table cellspacing='0' cellpadding='0'  bordercollapse='collapse'   borderspacing:'0' style='margin:0; padding:0;'>
			  <tbody>
			    <tr><td><img src='http://360.ingenuitydesign.com/tapin2tech/{$topimg}' style='display: block;' /></td></tr>
			    <tr><td><a href='{$url}' target='_blank'><img src='http://360.ingenuitydesign.com/tapin2tech/eml_middle.png' style='display: block;'/></a></td></tr>
			    <tr><td><a href='https://twitter.com/hashtag/lifeatvisa' target='_blank'><img src='http://360.ingenuitydesign.com/tapin2tech/eml_bottom.png' style='display: block;'/></a></td></tr>
			  </tbody>
			</table>";
}
add_action( 'wp_ajax_contact_ajax', 'visainterns_contact_ajax' );
add_action( 'wp_ajax_nopriv_contact_ajax', 'visainterns_contact_ajax' );

class ACF_HELPER
{
	static function getRepeater($field, array $subfields, $post = null,$usekeys = true)
	{
	  	if(!self::checkGetField($field,$post)) return false;

	  	if(!have_rows($field,$post)) return false;
	  	$data = [];
	    $i=0;
	    while ( have_rows($field,$post) ) : the_row();
	        $data[$i] =  [];
	        foreach($subfields as $k=> $fld){
	            $key = ($usekeys) ? $fld : $k;
	            $subfld = get_sub_field($fld,$post);
	            $subfld = ($key == 'image') ? $subfld['url'] : $subfld;
	            $data[$i][$key] = $subfld;
	        }
	    $i++;
	    endwhile;
	  	return $data;
	}
	static function getImgURI($img){
		return $img['sizes']['medium'];
	}

	static function checkGetField($field,$post = null){
	  return (!$val = get_field($field,$post,false)) ? false : $val;
	}
}
