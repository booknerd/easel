<?php

function easel_themeinfo($whichinfo = null) {
	global $easel_themeinfo;
	if (empty($easel_themeinfo) || $whichinfo == 'reset') {	
		$easel_themeinfo = array();
		$easel_options = easel_load_options();
		$easel_coreinfo = wp_upload_dir();
		$easel_addinfo = array(
			'upload_path' => get_option('upload_path'),
			'version' => '2.0.6',
			'themepath' => get_template_directory(),
			'themeurl' => get_template_directory_uri(), 
			'stylepath' => get_stylesheet_directory(), 
			'styleurl' => get_stylesheet_directory_uri(),
			'uploadpath' => $easel_coreinfo['basedir'],
			'uploadurl' => $easel_coreinfo['baseurl'],
			'home' => untrailingslashit(home_url()),  
			'siteurl' => untrailingslashit(site_url()) 
		);
		$easel_themeinfo = array_merge($easel_coreinfo, $easel_addinfo);
		$easel_themeinfo = array_merge($easel_themeinfo, $easel_options);
		if (!isset($easel_themeinfo['layout']) || empty($easel_themeinfo['layout'])) $easel_themeinfo['layout'] = '3c';
	}
	if ($whichinfo && $whichinfo !== 'reset')
		if (isset($easel_themeinfo[$whichinfo])) 
			return $easel_themeinfo[$whichinfo];
		else
			return false;
	return $easel_themeinfo;
}

// xili-language plugin check
if (class_exists('xili_language')) {
	define('THEME_TEXTDOMAIN', 'easel');
	define('THEME_LANGS_FOLDER', get_template_directory() . '/lang');
} else {
	load_theme_textdomain( 'easel', get_template_directory() . '/lang' );
}

// the_post_thumbnail('thumbnail/medium/full');
add_theme_support( 'post-thumbnails' );

// Required by the wordpress review theme, it sucks donkey balls but is required.
add_theme_support( 'automatic-feed-links' );

add_theme_support( 'custom-header' );

// This theme allows users to set a custom background
add_custom_background();

/* this sets default video width */
if (!isset($content_width)) {
	if (easel_sidebars_disabled()) {
		$content_width = 740;
	} else {
		$content_width = 540;
	}
}

register_nav_menus(array(
			'Primary' => __( 'Primary', 'easel' )
			));

/* child-functions.php / child-widgets.php - in the child theme */
if (is_child_theme()) {
	get_template_part('child', 'functions');
	get_template_part('child', 'widgets');
}

// load up the addons that it finds, loads before functions just in case we want to rewrite a function
if (is_dir(easel_themeinfo('themepath') . '/addons')) {
	if (easel_themeinfo('enable_addon_page_options')) 
		@require_once(easel_themeinfo('themepath') . '/addons/page-options.php');
	if (easel_themeinfo('enable_addon_comics')) 
		@require_once(easel_themeinfo('themepath') . '/addons/comics.php');
	if (easel_themeinfo('enable_addon_membersonly'))
		@require_once(easel_themeinfo('themepath') . '/addons/membersonly.php');
	if (easel_themeinfo('enable_addon_playingnow'))
		@require_once(easel_themeinfo('themepath') . '/addons/playingnow.php');
	if (easel_themeinfo('enable_addon_showcase'))
		@require_once(easel_themeinfo('themepath') . '/addons/showcase.php');
	if (easel_themeinfo('enable_addon_commpress'))
		@require_once(easel_themeinfo('themepath') . '/addons/commpress.php');
	if (easel_themeinfo('enable_wprewrite_posttype_control'))
		@require_once(easel_themeinfo('themepath') . '/addons/wp-rewrite.php');
}

// These autoload
foreach (glob(easel_themeinfo('themepath') . "/functions/*.php") as $funcfile) {
	@require_once($funcfile);
}

// Load all the widgets.
foreach (glob(easel_themeinfo('themepath')  . '/widgets/*.php') as $widgefile) {
	@require_once($widgefile);
}

// Load all the widgets from the child theme *if* a child theme exists
if (is_child_theme()) {
	if (is_dir(easel_themeinfo('stylepath') . '/widgets')) {
		$results = glob(easel_themeinfo('stylepath') . '/widgets/*.php');
		if (!empty($results)) {
			foreach ($results as $widgefile) {
				@require_once($widgefile);
			}
		}
	}
}

// Dashboard Menu Easel Options
if (is_admin()) {
	@require_once(easel_themeinfo('themepath') . '/options.php');
}

if (!is_admin()) {
	wp_enqueue_script('jquery');
	if (!easel_themeinfo('disable_jquery_menu_code')) {
		wp_enqueue_script('ddsmoothmenu_js', easel_themeinfo('themeurl') . '/js/ddsmoothmenu.js'); 
		wp_enqueue_script('menubar_js', easel_themeinfo('themeurl') . '/js/menubar.js');
	}
	if (!easel_themeinfo('disable_scroll_to_top')) {
		wp_enqueue_script('easel_scroll', easel_themeinfo('themeurl') . '/js/scroll.js', null, null, true);
	}
	if (is_active_widget('easel_google_translate_widget', false, 'easel_google_translate_widget', true)) {
		wp_enqueue_script('google-translate', 'http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', null, null, true);
		wp_enqueue_script('google-translate-settings', get_template_directory_uri() . '/js/googletranslate.js');
	}
	if (easel_themeinfo('enable_avatar_trick') && !$is_IE) {
		wp_enqueue_script('themetricks_historic1', easel_themeinfo('themeurl') . '/js/cvi_text_lib.js', null, null, true);
		wp_enqueue_script('themetricks_historic2', easel_themeinfo('themeurl') . '/js/instant.js', null, null, true);
	}
	if (easel_themeinfo('facebook_like_blog_post'))
		wp_enqueue_script('easel-facebook', 'http://connect.facebook.net/en_US/all.js#xfbml=1'); // force to the header instead of footer
	easel_display_scheme();
}

if (!function_exists('easel_register_sidebars')) {
	function easel_register_sidebars() {
		foreach (array(
					__('Left Sidebar', 'easel'),
					__('Right Sidebar', 'easel'),
					__('Above Header', 'easel'),
					__('Header', 'easel'),
					__('Menubar', 'easel'),
					__('Over Blog', 'easel'),
					__('Under Blog', 'easel'),
					__('Footer', 'easel')
					) as $sidebartitle) {
			register_sidebar(array(
						'name'=> $sidebartitle,
						'id' => 'sidebar-'.sanitize_title($sidebartitle),
						'before_widget' => "<div id=\"".'%1$s'."\" class=\"widget ".'%2$s'."\">\r\n<div class=\"widget-head\"></div>\r\n<div class=\"widget-content\">\r\n",
						'after_widget'  => "</div>\r\n<div class=\"widget-foot\"></div>\r\n</div>\r\n",
						'before_title'  => "<h2 class=\"widgettitle\">",
						'after_title'   => "</h2>\r\n"
						));
		}
	}
}
add_action('widgets_init', 'easel_register_sidebars');

function easel_get_sidebar($location = '') {
	if (empty($location)) { get_sidebar(); return; }
	if (is_active_sidebar('sidebar-'.$location)) { ?>
		<div id="sidebar-<?php echo $location; ?>" class="sidebar">
			<?php dynamic_sidebar('sidebar-'.$location); ?>
		</div>
	<?php }
}

function easel_is_signup() {
	global $wp_query;
	if (strpos( $_SERVER['SCRIPT_NAME'], 'wp-signup.php' ) || strpos( $_SERVER['SCRIPT_NAME'], 'wp-activate.php' )) return true;
	return false;
}

function easel_load_options() {

	$easel_options = get_option('easel-options');
	if (empty($easel_options)) {
		
		foreach (array(
			// This section is added
			'disable_jquery_menu_code' => false,
			'disable_scroll_to_top' => false,
			'enable_avatar_trick' => true,
			'disable_default_design' => false,
			'disable_comment_note' => false,
			'enable_numbered_pagination' => true,
			'disable_comment_javascript' => false,
			'enable_post_thumbnail_rss' => true,
			'disable_page_titles' => false,
			'disable_post_titles' => false,			
			'enable_post_calendar' => true,
			'enable_post_author_gravatar' => false,
			'disable_categories_in_posts' => false,
			'disable_tags_in_posts' => false,
			'disable_author_info_in_posts' => false,
			'disable_date_info_in_posts' => false,
			'home_post_count' => '5',
			'disable_footer_text' => false,
			'disable_default_menubar' => false,
			'enable_search_in_menubar' => false,
			'enable_rss_in_menubar' => true,
			'avatar_directory' => 'none',
			'enable_debug_footer_code' => false,
			'disable_blog_on_homepage' => false,
			'enable_comments_on_homepage' => false,
			'enable_addon_comics' => false,
			'enable_addon_membersonly' => false,
			'non_members_message' => __('There is members only content here.','easel'),
			'enable_addon_showcase' => false,
			'enable_addon_playingnow' => false,
			'enable_addon_showcase_slider' => false,
			'enable_addon_commpress' => false,
			'enable_addon_page_options' => false,
			'custom_image_header_width' => '980',
			'custom_image_header_height' => '100',
			'copyright_name' => '',
			'copyright_url' => '',
			'facebook_like_blog_post' => false,
			'facebook_meta' => false,
			'display_archive_as_links' => false,
			'archive_display_order' => 'DESC',
			'layout' => '3c',
			'scheme' => 'default',
			'enable_wprewrite_posttype_control' => false,
			'force_active_connection_close' => false,
			'enable_addon_easel_slider' => true,
			'display_comic_on_home' => true,
			'display_comic_post_on_home' => true
		) as $field => $value) {
			$easel_options[$field] = $value;
		}
		update_option('easel-options', $easel_options);
	}
	return $easel_options;
}

if (!function_exists('easel_add_post_ratings')) {
	function easel_add_post_ratings() {
		global $post;
		if (function_exists('the_ratings') && $post->post_type !== 'post') { the_ratings(); } 
	}
}
add_action('easel-post-info','easel_add_post_ratings');


function easel_debug_page_foot_code() { ?>
	<p><?php echo get_num_queries() ?> queries. <?php if (function_exists('memory_get_usage')) { $unit=array('b','kb','mb','gb','tb','pb'); echo @round(memory_get_usage(true)/pow(1024,($i=floor(log(memory_get_usage(true),1024)))),2).' '.$unit[$i]; ?> Memory usage. <?php } timer_stop(1) ?> seconds.</p>
<?php }
if (easel_themeinfo('enable_debug_footer_code')) {
	add_action('easel-page-foot', 'easel_debug_page_foot_code');
}

/**
 * Retrieve adjacent post link.
 *
 * Can either be next or previous post link.
 * chapters is for the comic post type
 */
function easel_get_adjacent_post_type($previous = true, $taxonomy = 'post', $in_same_chapter = false) {
	global $post, $wpdb;
	
	if ( empty( $post ) ) return null;

	$current_post_date = $post->post_date;

	$join = '';

	if ( $in_same_chapter ) {
		$join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

		if ( $in_same_chapter ) {
			$chapt_array = wp_get_object_terms($post->ID, 'chapters', array('fields' => 'ids'));
			if (!empty($chapt_array))
				$join .= " AND tt.taxonomy = 'chapters' AND tt.term_id IN (" . implode(',', $chapt_array) . ")";
		}
	}

	$adjacent = $previous ? 'previous' : 'next';
	$op = $previous ? '<' : '>';
	$order = $previous ? 'DESC' : 'ASC';

	$join  = apply_filters( "get_{$adjacent}_{$taxonomy}_join", $join, $in_same_chapter, $excluded_chapters );
	$where = apply_filters( "get_{$adjacent}_{$taxonomy}_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type), $in_same_chapter, $excluded_chapters );
	$sort  = apply_filters( "get_{$adjacent}_{$taxonomy}_sort", "ORDER BY p.post_date $order LIMIT 1" );

	$query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
	$query_key = "adjacent_{$taxonomy}_" . md5($query);
	$result = wp_cache_get($query_key, 'counts');
	if ( false !== $result )
		return $result;

	$result = $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $sort");
	if ( null === $result )
		$result = '';

	wp_cache_set($query_key, $result, 'counts');
	return $result;
}


function easel_auto_excerpt_more( $more ) {
	return __(' [&hellip;]','easel') . ' <a class="more-link" href="'. get_permalink() . '">' . __('&darr; Read the rest of this entry...','easel') . '</a>';
}
add_filter( 'excerpt_more', 'easel_auto_excerpt_more' );

function easel_display_scheme() {
	if (!easel_themeinfo('disable_default_design') && !is_child_theme()) {
		$scheme = easel_themeinfo('scheme');
		if (!empty($scheme)) {
			switch ($scheme) {
				case 'desert':
					wp_enqueue_style('easel-desert-scheme', get_template_directory_uri().'/schemes/desert.css');
					break;
				case 'ocean':
					wp_enqueue_style('ease-blue-scheme', get_template_directory_uri().'/schemes/ocean.css');
					break;
				case 'greymatter':
					wp_enqueue_style('ease-greymatter-scheme', get_template_directory_uri().'/schemes/greymatter.css');
					break;
				case 'default':
				default:
					break;
			}
		}
	}
}

if (easel_themeinfo('force_active_connection_close')) 
	add_action('shutdown_action_hook','easel_close_up_shop');

function easel_close_up_shop() {
	@mysql_close();
}

if (!function_exists('easel_is_layout')) {
	function easel_is_layout($choices) {
		$choices = explode(",", $choices);
		if (in_array(easel_themeinfo('layout'), $choices)) return true;
		return false;
	}
}

function easel_sidebars_disabled() {
	global $post;
	if (is_page() && !empty($post)) {
		$sidebars_disabled = get_post_meta($post->ID, 'disable-sidebars', true);
		if ($sidebars_disabled) return true;
	}
	return false;
}

add_action('pre_get_posts','easel_add_post_types_to_queries');

function easel_add_post_types_to_queries($query) {
	$args = array(
			'public' => true,
			'_builtin' => false
			);
	$output = 'names';
	$operator = 'and';
	$post_types = get_post_types( $args , $output , $operator );
	$post_types = array_merge(array('post'), $post_types);
	if ($query->is_author) {
		$query->set('post_type', $post_types);
	}
	return $query;
}


add_action('easel-menubar-menunav', 'easel_social_icons');

function easel_social_icons() {
	echo '<a href="http://www.twitter.com/Frumph" title="Follow Frumph on Twitter" class="menunav-social menunav-twitter">Twitter</a>'."\r\n";
	echo '<a href="http://www.facebook.com/philip.hofer" title="Friend Frumph on Facebook" class="menunav-social menunav-facebook">Facebook</a>'."\r\n";
	echo '<a href="'.get_bloginfo('rss2_url').'" title="RSS Feed" class="menunav-social menunav-rss2">RSS</a>'."\r\n";
}


?>