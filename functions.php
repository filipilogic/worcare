<?php
/**
 * ilogic functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ilogic
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.6' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ilogic_setup() {

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
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'ilogic' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);


	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 150,
			'width'       => 300,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'ilogic_setup' );

/**
 * Enqueue scripts and styles.
 */
function ilogic_scripts() {
	wp_enqueue_style( 'ilogic-style', get_stylesheet_uri(), array(), _S_VERSION );

	wp_enqueue_style( 'frontend-style', get_template_directory_uri() . '/assets/public/css/frontend.css', array(), _S_VERSION );
	wp_enqueue_script( 'ilogic-script', get_template_directory_uri() . '/assets/public/js/frontend.js', array('jquery'), _S_VERSION );

	wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/assets/public/js/vendor/fancybox.js',array('jquery'),_S_VERSION,true);
	wp_enqueue_script( 'flickity', get_template_directory_uri() . '/assets/public/js/vendor/flickity.js',array('jquery'),_S_VERSION,true);

	if ( is_home() ) {
		wp_enqueue_script( 'blog-main-script', get_template_directory_uri() . '/assets/src/js/blog-main.js', array('jquery'), _S_VERSION );
	}

	if ( is_category() ) {
		wp_enqueue_script( 'archive-main-script', get_template_directory_uri() . '/assets/src/js/archive-main.js', array('jquery'), _S_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'ilogic_scripts' );

function ilogic_admin_styles() {
	wp_enqueue_style( 'backend-styles', get_template_directory_uri() . '/assets/public/css/backend.css' );
}
add_action( 'admin_enqueue_scripts', 'ilogic_admin_styles' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/theme-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/includes/theme-functions.php';

// Theme options

require get_template_directory() . '/includes/theme-options.php';

// Fun Facts

require get_template_directory() . '/includes/theme-facts.php';


// Load scripts for block


 require get_template_directory() . '/includes/blocks-js.php';


// Register Blocks

add_action( 'init', 'register_acf_blocks' );
function register_acf_blocks() {
	register_block_type( __DIR__ . '/blocks/hero' );
	register_block_type( __DIR__ . '/blocks/hero-si' );
    register_block_type( __DIR__ . '/blocks/section' );
	register_block_type( __DIR__ . '/blocks/accordion' );
	register_block_type( __DIR__ . '/blocks/gallery' );
	register_block_type( __DIR__ . '/blocks/team' );
	register_block_type( __DIR__ . '/blocks/columns' );
	register_block_type( __DIR__ . '/blocks/tabs' );
	register_block_type( __DIR__ . '/blocks/lb-carousel' );
	register_block_type( __DIR__ . '/blocks/timeline' );
	register_block_type( __DIR__ . '/blocks/inner-hero-1' );
	register_block_type( __DIR__ . '/blocks/inner-hero-2' );
	register_block_type( __DIR__ . '/blocks/fp-section' );
	register_block_type( __DIR__ . '/blocks/mini-gallery' );
	register_block_type( __DIR__ . '/blocks/video-popup-section' );
	register_block_type( __DIR__ . '/blocks/contact-us' );
	register_block_type( __DIR__ . '/blocks/exec-director-section' );
	register_block_type( __DIR__ . '/blocks/countdown' );
	register_block_type( __DIR__ . '/blocks/agenda' );
	register_block_type( __DIR__ . '/blocks/blog-block' );
	register_block_type( __DIR__ . '/blocks/logos' );
	register_block_type( __DIR__ . '/blocks/related-posts' );
	register_block_type( __DIR__ . '/blocks/content-and-sidebar' );
	register_block_type( __DIR__ . '/blocks/user-profile' );
}


function filter_block_categories_when_post_provided( $block_categories, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) {
        array_push(
            $block_categories,
            array(
                'slug'  => 'ilogic-category',
                'title' => __( 'iLogic Blocks', 'ilogic' ),
                'icon'  => null,
            )
        );
    }
    return $block_categories;
}

add_filter( 'block_categories_all', 'filter_block_categories_when_post_provided', 10, 2 );

  /*
  * Action for load more posts
  */

  function blog_load_more() {

	$postCategory = (isset($_GET['postCategory'])) ? $_GET['postCategory'] : 'all';

	if($postCategory === 'all'){

		$countPosts = (isset($_GET['countPosts'])) ? $_GET['countPosts'] : 3;

		$the_query = new WP_Query(array(
			'orderby' => 'date',
			'order'   => 'DESC',
			'posts_per_page' => $countPosts,
			'offset'          => 3
		));

		// $count_posts = wp_count_posts();
		$count_posts =  $the_query->found_posts;
		$postLimit = intval($countPosts) + 3;

	} else {
		$countPosts = (isset($_GET['countPosts'])) ? $_GET['countPosts'] : 4;
		
		$the_query = new WP_Query(array(
			'orderby' => 'date',
			'category_name' => $postCategory,
			'order'   => 'DESC',
			'posts_per_page' => $countPosts,
			'offset'          => 4
		  ));

		// $count_posts = wp_count_posts();
		$count_posts =  $the_query->found_posts;
		$postLimit = intval($countPosts) + 4;
	}

	if($postLimit >= intval($count_posts)){
	  echo '<style>.ilLoadMore{display:none !important;}</style>';
	}
  
	if ($the_query->have_posts()) {
		while ($the_query->have_posts()){
			$the_query->the_post();
		  ?>
		  	<div class="il_blog_post">
				<div class="il_bp_left">
				<div class="il_bp_post_date_category_wrapper">
					<span class="date"><?php echo get_the_date('d M Y'); ?></span>
				</div>
				<a class="il_bp_title" href="<?php echo get_permalink(get_the_ID()) ?>"><h2 class="tg_title_1 tg_dark"><?php the_title(); ?><?php ?></h2></a>
					<div class="il_bp_text">
					<?php if (get_the_excerpt()) {
						echo get_the_excerpt();
					} else {
						echo wp_trim_words(get_the_content(), 5);
					} ?>
				</div>
				<a class="il_bp_link" href="<?php echo get_permalink(get_the_ID()) ?>"><span class="il_bp_link_text">Learn More</span></a>
				</div>
				<div class="il_bp_right">
					<?php the_post_thumbnail(); ?>
				</div>
			</div>
		  <?php
		}
	}
  
	  wp_die();
  }
  
  add_action('wp_ajax_blog_load_more', 'blog_load_more');
  add_action('wp_ajax_nopriv_blog_load_more', 'blog_load_more');

function the_breadcrumb() {

	$page_for_posts_id = get_option( 'page_for_posts' );
	$blog_title = get_the_title($page_for_posts_id);

    $sep = ' > ';

    if (!is_front_page()) {
	
	// Start the breadcrumb with a link to your homepage
        echo '<div class="il_sp_breadcrumbs">';
        echo '<a href="';
        echo get_permalink( get_option( 'page_for_posts' ) );
        echo '">';
        echo $blog_title;
        echo '</a>' . $sep;
	
	
	// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
        if (is_category() || is_single() ){
			echo '<span>Category</span>'. $sep;
            the_category(', ');
        } elseif (is_archive() || is_single()){
            if ( is_day() ) {
                printf( __( '%s', 'text_domain' ), get_the_date() );
            } elseif ( is_month() ) {
                printf( __( '%s', 'text_domain' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'text_domain' ) ) );
            } elseif ( is_year() ) {
                printf( __( '%s', 'text_domain' ), get_the_date( _x( 'Y', 'yearly archives date format', 'text_domain' ) ) );
            } else {
                _e( 'Blog Archives', 'text_domain' );
            }
        }
	
	// If the current page is a single post, show its title with the separator
        if (is_single()) {
            // echo $sep;
            // the_title();
        }
	
	// If the current page is a static page, show its title.
        if (is_page()) {
            echo the_title();
        }
	
	// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
        if (is_home()){
            global $post;
            $page_for_posts_id = get_option('page_for_posts');
            if ( $page_for_posts_id ) { 
                $post = get_post($page_for_posts_id);
                setup_postdata($post);
                the_title();
                rewind_posts();
            }
        }

        echo '</div>';
    }
}

function il_social_share(){
	$sb_url = urlencode(get_permalink());
	
	$sb_title = str_replace( ' ', '%20', get_the_title());

	$twitterURL = 'https://twitter.com/intent/tweet?text='.$sb_title.'&amp;url='.$sb_url.'&amp;via=wpvkp';
	$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$sb_url;
	$bufferURL = 'https://bufferapp.com/add?url='.$sb_url.'&amp;text='.$sb_title;
	$whatsappURL = 'whatsapp://send?text='.$sb_title . ' ' . $sb_url;
	$linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$sb_url.'&amp;title='.$sb_title;

	$post_title = get_the_title();
	$post_permalink = get_permalink();

	$content = '<div class="social-box"><div class="social-btn">';
	$content .= '<a class="col-1 sbtn s-facebook" href="'.$facebookURL.'" target="_blank" rel="nofollow"><span>
	<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M15.2389 12.3003H13.2774C13.2774 15.4355 13.2774 19.2947 13.2774 19.2947H10.3709C10.3709 19.2947 10.3709 15.4729 10.3709 12.3003H8.98926V9.82822H10.3709V8.22926C10.3709 7.08409 10.9148 5.29468 13.3041 5.29468L15.4579 5.30294V7.70259C15.4579 7.70259 14.1491 7.70259 13.8946 7.70259C13.6402 7.70259 13.2784 7.82989 13.2784 8.37599V9.82871H15.4929L15.2389 12.3003Z" fill="var(--color-3)"/>
	<mask id="mask0_272_11736" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="8" y="5" width="8" height="15">
	<path d="M15.2389 12.3003H13.2774C13.2774 15.4355 13.2774 19.2947 13.2774 19.2947H10.3709C10.3709 19.2947 10.3709 15.4729 10.3709 12.3003H8.98926V9.82822H10.3709V8.22926C10.3709 7.08409 10.9148 5.29468 13.3041 5.29468L15.4579 5.30294V7.70259C15.4579 7.70259 14.1491 7.70259 13.8946 7.70259C13.6402 7.70259 13.2784 7.82989 13.2784 8.37599V9.82871H15.4929L15.2389 12.3003Z" fill="white"/>
	</mask>
	<g mask="url(#mask0_272_11736)">
	<rect x="-0.00634766" y="0.294678" width="23.9889" height="24" fill="var(--color-3)"/>
	</g>
	</svg>
	</span></a>';

	$content .= '<a class="col-1 sbtn s-twitter" href="'. $twitterURL .'" target="_blank" rel="nofollow"><span><svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path fill-rule="evenodd" clip-rule="evenodd" d="M18.1923 6.78756L18.1551 6.76276C17.3865 6.03101 16.4816 5.68375 15.4403 5.73336L15.4031 5.65894L15.4279 5.64654C16.37 5.4357 16.9279 5.21246 17.1014 4.96441C17.151 4.76597 17.089 4.65435 16.8783 4.62954C16.4072 4.69155 15.9609 4.79077 15.5766 4.95201C16.0725 4.62954 16.2584 4.4063 16.1469 4.29468C15.6634 4.30708 15.1303 4.55513 14.5849 5.05122C14.7832 4.70396 14.87 4.50552 14.8204 4.48071C14.5477 4.65435 14.3246 4.85279 14.1262 5.06363C13.7047 5.53492 13.37 5.969 13.1097 6.36588L13.0973 6.39068C12.4403 7.46969 11.9692 8.5487 11.6965 9.65252L11.5973 9.73933L11.5725 9.75173C11.1758 9.25564 10.6923 8.83396 10.1221 8.49909C9.45268 8.05261 8.6593 7.64333 7.74195 7.23405C6.75022 6.71315 5.73369 6.29146 4.71716 5.9566C4.70477 7.11002 5.27501 8.0154 6.37832 8.68513V8.69753C5.99402 8.69753 5.60972 8.75954 5.23782 8.87116C5.3122 9.93777 6.0684 10.6695 7.49401 11.0664L7.48162 11.0912C6.92377 11.054 6.46509 11.2524 6.10559 11.6617C6.57666 12.5795 7.40724 13.0136 8.60971 12.9888C8.37418 13.1128 8.18823 13.2368 8.06426 13.3856C7.84112 13.6213 7.76674 13.8941 7.84112 14.2042C8.10145 14.6755 8.56013 14.8863 9.24194 14.8491L9.27913 14.8987L9.26673 14.9235C8.08905 16.139 6.66344 16.6847 5.00229 16.5731L4.97749 16.5855C3.96097 16.5731 2.87006 16.0894 1.69238 15.122C2.87006 16.8211 4.44444 18.0489 6.39071 18.8303C8.60971 19.562 10.8411 19.624 13.0601 18.9915H13.0973C15.2543 18.3714 17.0766 17.0816 18.589 15.1468C19.2832 14.1422 19.7171 13.1748 19.8907 12.2446C21.0188 12.2818 21.8245 11.9594 22.3328 11.2648L22.3204 11.24C21.9361 11.3764 21.2047 11.3392 20.1262 11.116V10.992C21.3163 10.8555 22.0229 10.4711 22.246 9.83855C21.4154 10.161 20.5973 10.1734 19.7915 9.86336C19.6427 8.74714 19.1097 7.71774 18.1923 6.78756Z" fill="var(--color-3)"/>
	<mask id="mask0_272_11740" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="4" width="22" height="16">
	<path fill-rule="evenodd" clip-rule="evenodd" d="M18.1923 6.78756L18.1551 6.76276C17.3865 6.03101 16.4816 5.68375 15.4403 5.73336L15.4031 5.65894L15.4279 5.64654C16.37 5.4357 16.9279 5.21246 17.1014 4.96441C17.151 4.76597 17.089 4.65435 16.8783 4.62954C16.4072 4.69155 15.9609 4.79077 15.5766 4.95201C16.0725 4.62954 16.2584 4.4063 16.1469 4.29468C15.6634 4.30708 15.1303 4.55513 14.5849 5.05122C14.7832 4.70396 14.87 4.50552 14.8204 4.48071C14.5477 4.65435 14.3246 4.85279 14.1262 5.06363C13.7047 5.53492 13.37 5.969 13.1097 6.36588L13.0973 6.39068C12.4403 7.46969 11.9692 8.5487 11.6965 9.65252L11.5973 9.73933L11.5725 9.75173C11.1758 9.25564 10.6923 8.83396 10.1221 8.49909C9.45268 8.05261 8.6593 7.64333 7.74195 7.23405C6.75022 6.71315 5.73369 6.29146 4.71716 5.9566C4.70477 7.11002 5.27501 8.0154 6.37832 8.68513V8.69753C5.99402 8.69753 5.60972 8.75954 5.23782 8.87116C5.3122 9.93777 6.0684 10.6695 7.49401 11.0664L7.48162 11.0912C6.92377 11.054 6.46509 11.2524 6.10559 11.6617C6.57666 12.5795 7.40724 13.0136 8.60971 12.9888C8.37418 13.1128 8.18823 13.2368 8.06426 13.3856C7.84112 13.6213 7.76674 13.8941 7.84112 14.2042C8.10145 14.6755 8.56013 14.8863 9.24194 14.8491L9.27913 14.8987L9.26673 14.9235C8.08905 16.139 6.66344 16.6847 5.00229 16.5731L4.97749 16.5855C3.96097 16.5731 2.87006 16.0894 1.69238 15.122C2.87006 16.8211 4.44444 18.0489 6.39071 18.8303C8.60971 19.562 10.8411 19.624 13.0601 18.9915H13.0973C15.2543 18.3714 17.0766 17.0816 18.589 15.1468C19.2832 14.1422 19.7171 13.1748 19.8907 12.2446C21.0188 12.2818 21.8245 11.9594 22.3328 11.2648L22.3204 11.24C21.9361 11.3764 21.2047 11.3392 20.1262 11.116V10.992C21.3163 10.8555 22.0229 10.4711 22.246 9.83855C21.4154 10.161 20.5973 10.1734 19.7915 9.86336C19.6427 8.74714 19.1097 7.71774 18.1923 6.78756Z" fill="white"/>
	</mask>
	<g mask="url(#mask0_272_11740)">
	<rect x="-0.306641" y="0.294678" width="23.9889" height="24" fill="var(--color-3)"/>
	</g>
	</svg>
	</span></a>';
	$content .= '<a class="col-2 sbtn s-googleplus" href="mailto:?subject='.$post_title.'&body=Check out this post: '.esc_url($post_permalink).'" target="_blank" rel="nofollow"><span><svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path fill-rule="evenodd" clip-rule="evenodd" d="M19.3833 3.29468H3.3907C1.73952 3.29468 0.402085 4.64203 0.402085 6.29468L0.39209 18.2938C0.39209 19.947 1.73918 21.2947 3.3907 21.2947H19.3833C21.0348 21.2947 22.3819 19.947 22.3819 18.2947V6.29468C22.3819 4.64239 21.0348 3.29468 19.3833 3.29468ZM2.40124 6.29551C2.40124 5.74351 2.84677 5.29468 3.39079 5.29468H19.3834C19.9308 5.29468 20.3829 5.74696 20.3829 6.29468V18.2947C20.3829 18.8424 19.9308 19.2947 19.3834 19.2947H3.39079C2.84332 19.2947 2.39125 18.8424 2.39125 18.2947L2.40124 6.29551ZM4.87399 9.29473C4.4012 9.00972 4.24884 8.39547 4.53366 7.92256C4.81863 7.44937 5.43332 7.29691 5.90639 7.58208L11.3578 10.8682L17.0471 7.43868C17.5202 7.15347 18.1357 7.30722 18.4207 7.78047C18.7049 8.25237 18.5547 8.8665 18.0839 9.15241L11.397 13.2127L11.3908 13.2231L4.87399 9.29473Z" fill="#979797"/>
	<mask id="mask0_272_11744" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="3" width="23" height="19">
	<path fill-rule="evenodd" clip-rule="evenodd" d="M19.3833 3.29468H3.3907C1.73952 3.29468 0.402085 4.64203 0.402085 6.29468L0.39209 18.2938C0.39209 19.947 1.73918 21.2947 3.3907 21.2947H19.3833C21.0348 21.2947 22.3819 19.947 22.3819 18.2947V6.29468C22.3819 4.64239 21.0348 3.29468 19.3833 3.29468ZM2.40124 6.29551C2.40124 5.74351 2.84677 5.29468 3.39079 5.29468H19.3834C19.9308 5.29468 20.3829 5.74696 20.3829 6.29468V18.2947C20.3829 18.8424 19.9308 19.2947 19.3834 19.2947H3.39079C2.84332 19.2947 2.39125 18.8424 2.39125 18.2947L2.40124 6.29551ZM4.87399 9.29473C4.4012 9.00972 4.24884 8.39547 4.53366 7.92256C4.81863 7.44937 5.43332 7.29691 5.90639 7.58208L11.3578 10.8682L17.0471 7.43868C17.5202 7.15347 18.1357 7.30722 18.4207 7.78047C18.7049 8.25237 18.5547 8.8665 18.0839 9.15241L11.397 13.2127L11.3908 13.2231L4.87399 9.29473Z" fill="white"/>
	</mask>
	<g mask="url(#mask0_272_11744)">
	<rect x="-0.607422" y="0.294678" width="23.9889" height="24" fill="var(--color-3)"/>
	</g>
	</svg>
	</span></a>';
	$content .= '<a class="col-2 sbtn s-linkedin" href="#" target="_blank" rel="nofollow" onclick="copyPostURL(\''.esc_url($post_permalink).'\'); return false;"><span><svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path fill-rule="evenodd" clip-rule="evenodd" d="M17.0834 16.3747C16.3237 16.3747 15.644 16.6747 15.1243 17.1447L7.99759 12.9947C8.04757 12.7647 8.08755 12.5347 8.08755 12.2947C8.08755 12.0547 8.04757 11.8247 7.99759 11.5947L15.0443 7.48468C15.5841 7.98468 16.2937 8.29468 17.0834 8.29468C18.7426 8.29468 20.082 6.95468 20.082 5.29468C20.082 3.63468 18.7426 2.29468 17.0834 2.29468C15.4241 2.29468 14.0848 3.63468 14.0848 5.29468C14.0848 5.53468 14.1247 5.76468 14.1747 5.99468L7.12799 10.1047C6.58824 9.60468 5.87857 9.29468 5.08894 9.29468C3.42971 9.29468 2.09033 10.6347 2.09033 12.2947C2.09033 13.9547 3.42971 15.2947 5.08894 15.2947C5.87857 15.2947 6.58824 14.9847 7.12799 14.4847L14.2447 18.6447C14.1947 18.8547 14.1647 19.0747 14.1647 19.2947C14.1647 20.9047 15.4741 22.2147 17.0834 22.2147C18.6926 22.2147 20.002 20.9047 20.002 19.2947C20.002 17.6847 18.6926 16.3747 17.0834 16.3747Z" fill="var(--color-3)"/>
	<mask id="mask0_272_11758" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="2" y="2" width="19" height="21">
	<path fill-rule="evenodd" clip-rule="evenodd" d="M17.0834 16.3747C16.3237 16.3747 15.644 16.6747 15.1243 17.1447L7.99759 12.9947C8.04757 12.7647 8.08755 12.5347 8.08755 12.2947C8.08755 12.0547 8.04757 11.8247 7.99759 11.5947L15.0443 7.48468C15.5841 7.98468 16.2937 8.29468 17.0834 8.29468C18.7426 8.29468 20.082 6.95468 20.082 5.29468C20.082 3.63468 18.7426 2.29468 17.0834 2.29468C15.4241 2.29468 14.0848 3.63468 14.0848 5.29468C14.0848 5.53468 14.1247 5.76468 14.1747 5.99468L7.12799 10.1047C6.58824 9.60468 5.87857 9.29468 5.08894 9.29468C3.42971 9.29468 2.09033 10.6347 2.09033 12.2947C2.09033 13.9547 3.42971 15.2947 5.08894 15.2947C5.87857 15.2947 6.58824 14.9847 7.12799 14.4847L14.2447 18.6447C14.1947 18.8547 14.1647 19.0747 14.1647 19.2947C14.1647 20.9047 15.4741 22.2147 17.0834 22.2147C18.6926 22.2147 20.002 20.9047 20.002 19.2947C20.002 17.6847 18.6926 16.3747 17.0834 16.3747Z" fill="white"/>
	</mask>
	<g mask="url(#mask0_272_11758)">
	<rect x="-0.908203" y="0.294678" width="23.9889" height="24" fill="var(--color-3)"/>
	</g>
	</svg>
	</span></a>';
	$content .= '</div></div>';
	$content .= '<script>
	function copyPostURL(url) {
		const el = document.createElement("textarea");
		el.value = url;
		document.body.appendChild(el);
		el.select();
		document.execCommand("copy");
		document.body.removeChild(el);

		showToastMessage("Post Link Copied");
	}
	function showToastMessage(message) {
		const toast = document.createElement("div");
		toast.textContent = message;
		toast.style.position = "fixed";
		toast.style.bottom = "20px";
		toast.style.left = "50%";
		toast.style.transform = "translateX(-50%)";
		toast.style.background = "#333";
		toast.style.color = "#fff";
		toast.style.padding = "10px";
		toast.style.borderRadius = "5px";
		toast.style.zIndex = "9999";
		
		document.body.appendChild(toast);
	
		// Remove toast after 3 seconds (adjust as needed)
		setTimeout(() => {
			document.body.removeChild(toast);
		}, 3000);
	}
	</script>';
	
	return $content;
}

add_action( 'il_social_share','il_social_share' );

function custom_post_navigation_shortcode() {
    ob_start(); // Start output buffering
    ?>
    <div class="post_nav_container">
        <?php
        // Output the post navigation
        the_post_navigation(array(
            'prev_text' => '<b><</b> Previous',
            'next_text' => 'Next <b>></b>',
            'in_same_term' => true,
        ));
        ?>
    </div>
    <?php

    return ob_get_clean(); // End output buffering and return the buffered content
}

// Register the shortcode
add_shortcode('post_navigation_shortcode', 'custom_post_navigation_shortcode');

function create_form_on_user_register( $user_id ) {
	$user = get_userdata( $user_id );

	if (in_array('contributor', $user->roles)) {
		if (class_exists('FrmForm')) {
			$employee_template_form_id = 166;
			$template = false;
			$copy_keys = true;
			$company_name = get_user_meta( $user_id, 'company_name', true );
			$employee_survey_name = $company_name . ' Employee Form';
	
			$employee_form_id = FrmForm::duplicate($employee_template_form_id, $template, $copy_keys);

			if ($employee_form_id) {
				$update_result = FrmForm::update($employee_form_id, array('name' => $employee_survey_name));
			}
			
			// Additional data you want to store
			update_user_meta($user_id, 'employee_survey_id', $employee_form_id);
	
			// Create a new page for Employer survey
			$employee_survey_page = wp_insert_post(array(
				'post_title'    => $company_name . ' Employee Survey',
				'post_content'  => '[formidable id=' . $employee_form_id . ']',
				'post_status'   => 'publish',
				'post_type'     => 'page',
			));
		}
	}
}

add_action( 'user_register', 'create_form_on_user_register' );

function redirect_to_login_if_not_logged_in() {
    // Check if the user is not logged in and the current page is with ID 46
    if (!is_user_logged_in()) {
		if (is_page(10) || is_page(46)) {
			// Redirect to the login page
			wp_redirect(wp_login_url());
			exit();
		}
    }
}

add_action('template_redirect', 'redirect_to_login_if_not_logged_in');

// Redirect users on login to user dashboard
function custom_user_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles) && ! in_array('administrator', $user->roles)) {
        // Redirect users to a specific page
        return home_url('/user-profile');
    } else {
        return $redirect_to;
    }
}
add_filter('login_redirect', 'custom_user_login_redirect', 10, 3);

// Prevent users from accessing wp-admin
function restrict_user_access() {
    // Check if the current user is logged in
    if (is_user_logged_in()) {
        // Get the current user's capabilities
        $user = wp_get_current_user();
        
        // Check if the user has the Contributor role
        if ( ! in_array('administrator', $user->roles )) {
            // Check if it's an AJAX request
            if (!defined('DOING_AJAX') || !DOING_AJAX) {
                // Redirect contributors away from the admin area
                wp_redirect(home_url('/'));
                exit;
            }
        }
    }
}
add_action('admin_init', 'restrict_user_access');

// Remove admin bar for non-admins
function hide_admin_bar_for_users() {
    // Check if the current user is logged in
    if (is_user_logged_in()) {
        // Get the current user's capabilities
        $user = wp_get_current_user();
        
        // Check if the user has the admin role
        if ( ! in_array('administrator', $user->roles )) {
            // Disable the admin bar for users
            add_filter('show_admin_bar', '__return_false');
        }
    }
}
add_action('init', 'hide_admin_bar_for_users');

function badge_shortcode_output() {
    // Get the current user ID
    $user_id = get_current_user_id();

    // Get the badge value total for the user
    $badge_value_total = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1961, 'user_id' => 'current'));

    // Output the appropriate badge image based on the badge value
    if ($badge_value_total > 0 && $badge_value_total < 25) {
        return '<p class="emp-ty-badge-text">SUPPORTIVE</p><img src="/wp-content/uploads/2024/02/ty-supportive-badge-1.png" class="emp-ty-badge-img">';
    } elseif ($badge_value_total >= 25 && $badge_value_total < 500) {
        return '<p class="emp-ty-badge-text">EMPOWERING</p><img src="/wp-content/uploads/2024/02/ty-empowering-badge-1.png" class="emp-ty-badge-img">';
    } elseif ($badge_value_total >= 500) {
        return '<p class="emp-ty-badge-text">AMBASSADOR</p><img src="/wp-content/uploads/2024/02/ty-ambassador-badge-1.png" class="emp-ty-badge-img">';
    } else {
        // Add a default image or message if needed
        return 'No badge available';
    }
}

// Register the shortcode
add_shortcode('badge_shortcode', 'badge_shortcode_output');

function add_login_logout_link_to_menu( $items, $args ) {
    // Check if this is the primary menu
    if ( $args->theme_location == 'menu-1' ) {
        // Check if the user is logged in
        if ( is_user_logged_in() ) {
            // If logged in, add a logout link
            $logout_link = '<li class="menu-item login-logout-link"><a href="' . wp_logout_url( home_url() ) . '"><img src="/wp-content/uploads/2024/02/login-icon.svg"> התנתקות</a></li>';
            $items = $logout_link . $items;
        } else {
            // If not logged in, add a login link
            $login_link = '<li class="menu-item login-logout-link"><a href="' . wp_login_url( get_permalink() ) . '"><img src="/wp-content/uploads/2024/02/login-icon.svg"> כניסה</a></li>';
            $items = $login_link . $items;
        }
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'add_login_logout_link_to_menu', 10, 2 );

function add_allow_upload_extension_exception( $types, $file, $filename, $mimes ) {
    // Do basic extension validation and MIME mapping
    $wp_filetype = wp_check_filetype( $filename, $mimes );
    $ext         = $wp_filetype['ext'];
    $type        = $wp_filetype['type'];
    if( in_array( $ext, array( 'zip', 'rar' ) ) ) {
        $types['ext'] = $ext;
        $types['type'] = $type;
    }
    return $types;
}
add_filter( 'wp_check_filetype_and_ext', 'add_allow_upload_extension_exception', 99, 4 );

function combine_and_format_values_field_1916() {
    // Add the default values
    $default_values = array('<b>הורה</b>','<b>בן.ת זוג</b>','<b>הורי בן.ת זוג</b>','<b>אח.ות</b>','<b>ילד.ה</b>');
    
    // Get the dynamic value from the form field
    $dynamic_value = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1916, 'user_id' => 'current'));
    
    // Split the dynamic value into an array
    $dynamic_values_array = explode(',', $dynamic_value);

    // Trim each value in the dynamic values array to remove leading and trailing spaces
    $dynamic_values_array = array_map('trim', $dynamic_values_array);
	
    // Check if the specific value exists in the dynamic values array
    if (!in_array('אנו מעוניינים להישאר עם הגדרת נציבות שירות המדינה', $dynamic_values_array)) {
        // Combine the default and dynamic values
        $combined_values = array_merge($default_values, $dynamic_values_array);
    } else {
        // Use only the dynamic values without merging
        $combined_values = $default_values;
    }
    
    // Format the values into an HTML unordered list
    $html_output = '<table><tr>';
    $count = 0;
    foreach ($combined_values as $value) {
        if ($count > 0 && $count % 3 == 0) {
            $html_output .= '</tr><tr>';
        }
        $html_output .= '<td>&#x2022; ' . $value . '</td>';
        $count++;
    }
    $html_output .= '</tr></table>';

    return $html_output;
}

add_shortcode('combined_values_field_1916', 'combine_and_format_values_field_1916');

function combine_and_format_values_field_1919() {
    // Add the default values
	$default_values = array('<b>מגבלה קשה עקב מחלה</b>','<b>מחלות כרוניות</b>','<b>השתלת איברים</b>','<b>אשפוז ממושך</b>','<b>דיאליזה</b>','<b>מחלות קשות</b>');
    
    // Get the dynamic value from the form field
    $dynamic_value = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1919, 'user_id' => 'current'));
    
    // Split the dynamic value into an array
    $dynamic_values_array = explode(',', $dynamic_value);

    // Trim each value in the dynamic values array to remove leading and trailing spaces
    $dynamic_values_array = array_map('trim', $dynamic_values_array);
	
    // Check if the specific value exists in the dynamic values array
    if (!in_array('אנו מעוניינים להישאר עם הגדרת נציבות שירות המדינה', $dynamic_values_array)) {
        // Combine the default and dynamic values
        $combined_values = array_merge($default_values, $dynamic_values_array);
    } else {
        // Use only the dynamic values without merging
        $combined_values = $default_values;
    }
    
    // Format the values into an HTML unordered list
    $html_output = '<table><tr>';
    $count = 0;
    foreach ($combined_values as $value) {
        if ($count > 0 && $count % 3 == 0) {
            $html_output .= '</tr><tr>';
        }
        $html_output .= '<td>&#x2022; ' . $value . '</td>';
        $count++;
    }
    $html_output .= '</tr></table>';

    return $html_output;
}

add_shortcode('combined_values_field_1919', 'combine_and_format_values_field_1919');

function combine_and_format_values_field_1936() {
    // Get the dynamic value from the form field
    $dynamic_value = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1936, 'user_id' => 'current'));
    
    // Split the dynamic value into an array
    $dynamic_values_array = explode(',', $dynamic_value);

    // Trim each value in the dynamic values array to remove leading and trailing spaces
    $dynamic_values_array = array_map('trim', $dynamic_values_array);
	
    // Check if the specific value exists in the dynamic values array
    if (in_array('איננו מעוניינים להרחיב', $dynamic_values_array)) {
        return false;
    }
    
    // Format the values into an HTML unordered list
    $html_output = '<table><tr>';
    $count = 0;
    foreach ($dynamic_values_array as $value) {
        if ($count > 0 && $count % 2 == 0) {
            $html_output .= '</tr><tr>';
        }
        $html_output .= '<td>&#x2022; ' . $value . '</td>';
        $count++;
    }
    $html_output .= '</tr></table>';

    return $html_output;
}

add_shortcode('combined_values_field_1936', 'combine_and_format_values_field_1936');

function combine_and_format_values_field_1939() {    
    // Get the dynamic value from the form field
    $dynamic_value = FrmProEntriesController::get_field_value_shortcode(array('field_id' => 1939, 'user_id' => 'current'));
    
    // Split the dynamic value into an array
    $dynamic_values_array = explode(',', $dynamic_value);
    
    // Format the values into an HTML unordered list
    $html_output = '<table><tr>';
    $count = 0;
    foreach ($dynamic_values_array as $value) {
        if ($count > 0 && $count % 1 == 0) {
            $html_output .= '</tr><tr>';
        }
        $html_output .= '<td>&#x2022; ' . $value . '</td>';
        $count++;
    }
    $html_output .= '</tr></table>';

    return $html_output;
}

add_shortcode('combined_values_field_1939', 'combine_and_format_values_field_1939');



// Add JavaScript to dynamically add show/hide functionality to password fields in the footer
function add_password_toggle_script() {
    ?>
    <script type="text/javascript">
        // Add show/hide functionality to password fields
        function addPasswordToggle() {
            var passwordFields = document.querySelectorAll('input[type="password"]');
            
            passwordFields.forEach(function(field) {
                var toggleButton = document.createElement('span');
                toggleButton.className = 'toggle-password';
                toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>'; // Font Awesome icon for 'eye'
                toggleButton.style.cursor = 'pointer';
                toggleButton.style.position = 'absolute';
                toggleButton.style.top = '75%';
                toggleButton.style.left = '10px';
                toggleButton.style.transform = 'translateY(-50%)';
                
                toggleButton.onclick = function() {
                    if (field.type === 'password') {
                        field.type = 'text';
                        toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"/></svg>'; // Font Awesome icon for 'eye-slash'
                    } else {
                        field.type = 'password';
                        toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>'; // Font Awesome icon for 'eye'
                    }
                };
                
                field.parentNode.style.position = 'relative';
                field.parentNode.appendChild(toggleButton);
            });
        }

        // Call the function to add toggle functionality when the document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            addPasswordToggle();
        });
    </script>


<script> 

// Create a new span element
var passwordStrengthSpan = document.createElement("span");

// Set the inner HTML with the provided HTML code
passwordStrengthSpan.innerHTML = `
<span id="frm-pass-eight-char-875" class="frm-pass-req">
    <svg viewBox="0 0 20 20" class="frmsvg frm_cancel1_icon failed_svg">
        <title>cancel1</title>
        <path d="M10 0a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16zm3.6-13L10 8.6 6.4 5 5 6.4 8.6 10 5 13.6 6.4 15l3.6-3.6 3.6 3.6 1.4-1.4-3.6-3.6L15 6.4z"></path>
    </svg>
    <svg viewBox="0 0 20 20" class="frmsvg frm_check1_icon passed_svg">
        <title>check1</title>
        <path d="M10 .3a9.7 9.7 0 1 0 0 19.4A9.7 9.7 0 0 0 10 .3zm0 1.9a7.8 7.8 0 1 1 0 15.6 7.8 7.8 0 0 1 0-15.6zm5.5 5l-.9-.8a.5.5 0 0 0-.7 0l-5.5 5.5-2.3-2.4a.5.5 0 0 0-.7 0l-.9.9c-.2.2-.2.5 0 .6l3.6 3.6c.2.2.4.2.6 0L15.5 8c.2-.1.2-.4 0-.6z"></path>
    </svg>
    מקסימום שמונה תווים
</span>`;

// Append the span element to the desired location in the DOM
document.querySelector(".ud-password-change-form .frm-password-strength").appendChild(passwordStrengthSpan);

</script>

<script> 
// Select all password input fields within the .ud-password-change-form
var passwordInputs = document.querySelectorAll('.ud-password-change-form input[type="password"]');

// Add event listeners to each password input field
passwordInputs.forEach(function(passwordInput) {
    passwordInput.addEventListener('input', function() {
        // Get the current password value and its length
        var password = this.value;
        var passwordLength = password.length;

        // Limit the password length to 8 characters
        if (passwordLength > 8) {
            this.value = password.slice(0, 8);
            passwordLength = 8;
        }

        // Select the element with the ID directly
        var specialCharElem = document.getElementById('frm-pass-eight-char-875');

        // Toggle class based on password length
        if (passwordLength === 8) {
            specialCharElem.classList.remove('frm-pass-req');
            specialCharElem.classList.add('frm-pass-verified');
        } else {
            specialCharElem.classList.remove('frm-pass-verified');
            specialCharElem.classList.add('frm-pass-req');
        }
    });
});

</script>

    <?php
}

// Hook into the wp_footer action
add_action( 'wp_footer', 'add_password_toggle_script' );

function my_text_strings( $translated_text, $text, $domain ) {
	switch ( $translated_text ) {
		case 'סיסמא' :
			$translated_text = __( 'סיסמה', 'frmreg' );
			break;
			
			
			case 'שכחת את הסיסמא? לחץ כאן' :
			$translated_text = __( 'שכחת את הסיסמה? לחץ כאן', 'frmreg' );
			break;	
		



	}
	return $translated_text;
}
add_filter( 'gettext', 'my_text_strings', 20, 3 );
