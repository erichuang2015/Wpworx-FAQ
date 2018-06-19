<?php

/**
 * Plugin Name:       Wpworx FAQ
 * Description:       A plugin to create collapsible panels and shortcodes to call them, pretty usefull for FAQ pages
 * Version:           1.0.0
 * Author:            Wpworx
 * Author URI:        http://wpworx.co/
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


define('ADV_WFAQ_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ADV_WFAQ_PLUGIN_URL', plugin_dir_url(__FILE__));
register_activation_hook(__FILE__, 'ADV_WFAQ_database_table');
register_uninstall_hook(__FILE__, 'ADV_WFAQ_remove_table');


add_action('admin_enqueue_scripts', 'ADV_WFAQ_Add_Admin_Scripts');
add_filter('admin_body_class', 'ADV_WFAQ_lower_body_classes');

// Register Custom Post Type
function ADV_WFAQ_create_post() {

    $labels = array(
        'name' => _x('FAQ Items', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('FAQ Item', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('FAQ', 'text_domain'),
        'name_admin_bar' => __('FAQ', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All FAQs', 'text_domain'),
        'add_new_item' => __('Add New FAQ', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Item', 'text_domain'),
        'edit_item' => __('Edit Item', 'text_domain'),
        'update_item' => __('Update Item', 'text_domain'),
        'view_item' => __('View Item', 'text_domain'),
        'search_items' => __('Search Item', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
    );
    $args = array(
        'label' => __('FAQ Item', 'text_domain'),
        'description' => __('Questions and answers', 'text_domain'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'author', 'revisions', 'custom-fields',),
        'taxonomies' => array('wpwfaq'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => plugins_url('images/f2.png', __FILE__),
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('worxfaq', $args);
}

add_action('init', 'ADV_WFAQ_create_post', 0);

add_action('init', 'ADV_WFAQ_Create_Taxonomy', 0);

function ADV_WFAQ_Create_Taxonomy() {
// Labels part for the GUI
    $labels = array(
        'name' => _x('Wpworx FAQ Category', 'taxonomy general name'),
        'singular_name' => _x('Wpworx FAQ Category', 'taxonomy singular name'),
        'search_items' => __('Search Wpworx FAQ Categories'),
        'popular_items' => __('Popular Wpworx FAQ Categories'),
        'all_items' => __('All Wpworx FAQ Categories'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Wpworx FAQ Category'),
        'update_item' => __('Update Wpworx FAQ Category'),
        'add_new_item' => __('Add New Wpworx FAQ Category'),
        'new_item_name' => __('New FAQ Wpworx Category Name'),
        'separate_items_with_commas' => __('Separate faq categories with commas'),
        'add_or_remove_items' => __('Add or remove faq categories'),
        'choose_from_most_used' => __('Choose from the most used faq categories'),
        'menu_name' => __('Wpworx FAQ Categories'),
    );

// Now register the non-hierarchical taxonomy like tag

    register_taxonomy('wpwfaq', 'worxfaq', array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array('slug' => 'wpwfaq'),
    ));
}

include_once('inc/settings.php');
add_filter('custom_menu_order', 'ADV_WFAQ_Add_Sub_menu');

function ADV_WFAQ_Add_Sub_menu($menu_order) {
    # Get submenu key location based on slug
    global $submenu;
    if (!empty($submenu['edit.php?post_type=worxfaq'])) {
        $settings = $submenu['edit.php?post_type=worxfaq'];

        foreach ($settings as $key => $details) {
            if ($details[0] == 'Dashboard') {
                $index = $key;
            }
        }
        $submenu['edit.php?post_type=worxfaq'][4] = $submenu['edit.php?post_type=worxfaq'][$index];
        unset($submenu['edit.php?post_type=worxfaq'][$index]);
        ksort($submenu['edit.php?post_type=worxfaq']);
    }
    # Return the new submenu order
    return $submenu;
}

/* Add localization support */

function ADV_WFAQ_localization_setup() {
    load_plugin_textdomain('wpworx-faq', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

add_action('after_setup_theme', 'ADV_WFAQ_localization_setup');

function ADV_WFAQ_Add_Frontend_Scripts() {
    global $wp_scripts;
    wp_enqueue_script('jquery');
    // will only work for http, https can be added if needed as well
    wp_enqueue_style('font-icon-style', 'https://use.fontawesome.com/releases/v5.0.9/css/all.css', false, null);
}

add_action('wp_enqueue_scripts', 'ADV_WFAQ_Add_Frontend_Scripts');

// admin css
function ADV_WFAQ_Add_Admin_Scripts() {
    wp_enqueue_style('admin_css', plugins_url('css/admin.css', __FILE__), false, '1.0.0');
}

function ADV_WFAQ_lower_body_classes($classes) {
    $version = get_bloginfo('version');
    $v_class = '';
    if ($version <= '4.0') {
        $v_class = "lower-version";
    }
    return $v_class;
}

function ADV_WFAQ_Flag_Set() {
    global $flag, $faq_title_flag;
    $flag = 0;
    $faq_title_flag = 0;
}

add_action('init', 'ADV_WFAQ_Flag_Set');

add_filter('wp_head', 'ADV_WFAQ_Create_Css');

// Add Plugin CSS
function ADV_WFAQ_Create_Css() {
    global $wpdb;
    $table_name = $wpdb->prefix . "worx_faq";
    $script_table = $wpdb->prefix . "worx_files";
    $options = $wpdb->get_row("SELECT * FROM $table_name");
    $xtreem_scripts = $wpdb->get_row("SELECT * FROM $script_table");
    $css_string = '';
    $shortcodes = array('[QUESTION_BG_COLOR]', '[QUESTION_COLOR]', '[QUESTION_HOVER_COLOR]', '[QUESTION_ACTIVE_BG_COLOR]', '[QUESTION_ACTIVE_COLOR]', '[ICON_COLOR]', '[ICON_BG_COLOR]');
    $css_settings = array($options->question_bg_color, $options->question_color, $options->question_hover_color, $options->question_active_bg_color, $options->question_active_color, $options->icon_color, $options->icon_bg_color);
    $css_string = $xtreem_scripts->accordian_css;
    $css_string = str_replace($shortcodes, $css_settings, $css_string);

    echo $style = "<style>" . $css_string . "</style>";
    echo $add_custom_css = "<style>" . $options->custom_css . "</style>";
    echo '<meta http-equiv="content-type" content="text/html;" />';
}

// Add Plugin JS here
function ADV_WFAQ_Create_Js() {
    global $wpdb;

    $table_name = $wpdb->prefix . "worx_faq";
    $script_table = $wpdb->prefix . "worx_files";
    $options = $wpdb->get_row("SELECT * FROM $table_name");
    $xtreem_scripts = $wpdb->get_row("SELECT * FROM $script_table");
    $js_string = '';
    $expand_script = '';
    $expand_html = '';
    $answer_script = '';
    $back_button = '';
    $back_script = '';
    if ($options->accordian == "accordian") {
        $js_string = $xtreem_scripts->accordian_js;
    } else {
        $js_string = $xtreem_scripts->toggle_js;
    }
    if ($options->expand_collapse_all == 'Yes') {

        $expand_script = '<script type="text/javascript">
						     jQuery(document).ready(function(){
						        var $this = jQuery(".accordian-faqlist .accordian-heading");
						        jQuery(".expend-accordian-menu").click(function(){
						            jQuery($this).toggleClass("active");
						            $this.next().toggleClass("show").slideToggle(); 
						            jQuery(this).toggleClass("active");
						         if(jQuery(".expend-accordian-menu").hasClass("active")){
						        	jQuery(".expend-accordian-menu span").html("Collapse All");
						        }else{
						        	jQuery(".expend-accordian-menu span").html("Expand All");
						        	}
						        });

						     });
						</script>';
    }
    if ($options->display_all_answers == 'Yes') {
        $answer_script = '<script type="text/javascript">
						     jQuery(document).ready(function(){
						        var $this = jQuery(".accordian-faqlist .accordian-heading");
						            jQuery($this).addClass("active");
						            $this.next().addClass("show").slideToggle(); 
						            jQuery(".expend-accordian-menu span").html("Collapse All");
						            jQuery(".expend-accordian-menu").addClass("active");
						     });
						</script>';
    }

    if ($options->back_to_top == "Yes") {

        $back_script = '<script type="text/javascript">
		  					    jQuery(window).scroll(function() {
									    if (jQuery(this).scrollTop()) {
									        jQuery(".toTop").fadeIn();
									    } else {
									        jQuery(".toTop").fadeOut();
									    }
									});
									jQuery(".toTop").click(function () {
									   jQuery("html, body").animate({scrollTop:  jQuery(".xtreem_faq_main").position().top}, 1000);
									});
		  					</script>';
    }
    $window_script = '<script type="text/javascript">
		  					jQuery(document).ready(function(){
		  					jQuery(".xtreem_faq_main").each(function(){
		  						var $this_width = jQuery(this).width() - 55;
								jQuery(".headline-panel").css("width", width_question_title);	
		  					})	
		  			
							});
							jQuery(window).resize(function(){
								var width_question_title = jQuery(".accordian-heading").width() - 55;
								jQuery(".headline-panel").css("width", width_question_title);
								});
							</script>';
    echo $js_string;
    echo $expand_script;
    echo $answer_script;
    //echo $window_script;
    echo $back_script;
}

add_filter('wp_footer', 'ADV_WFAQ_Create_Js');

// Create plugin shortcode
function ADV_WFAQ_Shortcode($atts) {
    global $wpdb, $flag, $faq_title_flag;
    $table_name = $wpdb->prefix . "worx_faq";
    $options = $wpdb->get_row("SELECT * FROM $table_name");
    $category_heading_html = '';
    $faq_title_html = '';
    $expand_html = '';
    $back_button = "";
    if ($options->faq_title != '' && $faq_title_flag == 0) {
        $heading = $options->faq_heading;
        if ($heading != '') {
            $faq_title_html = '<span class="faq_heading"><' . $heading . '>' . $options->faq_title . '</' . $heading . '></span>';
        } else {
            $faq_title_html = '<span class="faq_heading">' . $options->faq_title . '</span>';
        }

        $faq_title_flag++;
    }
    if ($options->expand_collapse_all == 'Yes' && $flag == 0) {
        $expand_html = '<div class="expend-accordian-menu"><span>Expand All</span> <i class="fas fa-chevron-down"></i></div>';
        $flag++;
    }
    if ($options->category_heading == 'show') {

        $category_heading_html = '';
    }
    if ($options->back_to_top == "Yes") {
        $back_button = "<br><a class='toTop' href='javascript:void(0);'>Back to top</a>";
    }
    extract(shortcode_atts(array(
        'cat_id' => null,
        'category' => null,
        'order' => 'DESC',
        'orderby' => 'date',
        'posts' => -1,
        'type' => 'worxfaq'
                    ), $atts));
    $args = array(
        'cat' => $cat_id,
        'wpwfaq' => $category,
        'order' => $order,
        'orderby' => $orderby,
        'posts_per_page' => $posts,
        'post_type' => $type
    );
    $category_title_html = '';
    $content = "";

    if (!empty($args['wpwfaq']) && $options->category_title == "show") {
        $category_slug = $args['wpwfaq'];
        $cat = get_term_by('slug', $category_slug, 'wpwfaq');
        $category_name = $cat->name;
        $category_heading = $options->category_heading;
        if ($category_heading != '') {
            $category_title_html = '<span class="category_heading"><' . $category_heading . '>' . $category_name . '</' . $category_heading . '></span>';
        } else {
            $category_title_html = '<span class="category_heading">' . $category_name . '</span>';
        }
    }


    // The Query
    $the_query = new WP_Query($args);

    // The Loop
    if ($the_query->have_posts()) {
        $content .= '<div class="xtreem_faq_main">';
        $content.= $expand_html;
        $content .= $faq_title_html;
        $content .= $category_title_html;

        //beginning of the FAQ element
        $content .= '<div class="accordian-faqlist default-functionality">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $content .= '<div class="accordian-heading"><span class="icon-heading"><i class="fas fa-plus"></i><i class="fas fa-minus"></i></span>';
            $content .= '<span class="headline-panel">' . get_the_title() . '</span></div>';
            $content .= '<div class="accordian-answer">' . get_the_content() . $back_button . '</div>';
        }
        //closing the FAQ element
        $content .= '</div>';
    } else {
        $content .= "No FAQ items found";
    }

    $content .= '</div>';

    /* Restore original Post Data */
    wp_reset_postdata();

    return $content;
}

add_shortcode('worxfaq', 'ADV_WFAQ_Shortcode');

// create plugin db tables on plugin activation
function ADV_WFAQ_database_table() {
    global $table_prefix, $wpdb;
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    $wp_xtreem_table = $table_prefix . "worx_faq";
    $wp_xtreem_file = $table_prefix . "worx_files";
    #Check to see if the table exists already, if not, then create it
    if ($wpdb->get_var("show tables like '$wp_xtreem_file'") != $wp_xtreem_file) {
        $sql = "CREATE TABLE " . $wp_xtreem_file . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`accordian_css` text NOT NULL,
		`accordian_js` text NOT NULL,
		`toggle_js` text NOT NULL,
		`toggle_css` text NOT NULL,
		 PRIMARY KEY id (id)
		);";
        dbDelta($sql);
        $wpdb->insert(
                $table_prefix . 'worx_files', array(
            'accordian_css' => '.accordian-faqlist{ display: block; width: 100%; position: relative;margin-bottom: 30px;}
								.incenterdata{ max-width: 1170px; margin-left: auto; margin-right: auto; display: block; }
								.incenterdata:before, .incenterdata:after{ content: ""; display: block; clear: both; }
								.accordian-faqlist:before,.accordian-faqlist:after{ content: ""; display: block; clear: both; }
								.accordian-faqlist > .accordian-heading{ display: block; width: 100%; clear: both; background:[QUESTION_BG_COLOR];position: relative; padding: 0; font-size: 17px; color: [QUESTION_COLOR];}
								.accordian-faqlist > .accordian-heading:hover{ color: [QUESTION_HOVER_COLOR]; }
								.accordian-faqlist > .accordian-heading.active{ background:[QUESTION_ACTIVE_BG_COLOR]; color:[QUESTION_ACTIVE_COLOR]; }
								.accordian-faqlist > .accordian-answer{ display: none; font-size: 14px; color: #555; clear: both; width: 100%; padding: 15px 15px 15px 55px; border-top: 0; position: relative; z-index: 2; background:#fff; line-height: 1.5;}
								.accordian-faqlist > .accordian-answer p{ margin:0; }
								.accordian-faqlist > .accordian-answer p + p{ margin-top: 12px; }
								.accordian-answer + .accordian-heading{ margin-top: 20px; }
								.icon-heading{display: inline-block;vertical-align: middle;transition: all 0.3s ease-in-out 0s;-webkit-transition: all 0.3s ease-in-out 0s;
									cursor: pointer;text-align: center;width: 40px; height: 40px;color:[ICON_COLOR]; background: [ICON_BG_COLOR];}
								.icon-heading i{ line-height: 40px; }
								.headline-panel{ display: inline-block; vertical-align: middle; 
									padding-left: 0; cursor: pointer;}
								.accordian-faqlist  .accordian-heading .icon-heading i.fa-minus{ display: none; }
								.accordian-faqlist  .accordian-heading.active .icon-heading i.fa-minus{ display: block; }
								.accordian-faqlist  .accordian-heading.active .icon-heading i.fa-plus{ display: none; }
								.expend-accordian-menu{ display: inline-block; border-radius: 50px; padding: 10px 20px; font-size: 14px; text-align: center; cursor: pointer; background: #eee; margin-bottom: 20px; }
								.expend-accordian-menu:hover{ background: #bbb; }
								.expend-accordian-menu i{ transition: all 0.3s ease-in-out 0s; -webkit-transition: all 0.3s ease-in-out 0s; }
								.expend-accordian-menu.active{ background: #bbb; }
								.expend-accordian-menu.active i{ transform: rotate(-180deg); -webkit-transform: rotate(-180deg); }.accordian-faqlist .accordian-heading .icon-heading i.fa-plus{ animation: flipicon 0.3s linear forwards; -webkit-animation: flipicon 0.3s linear forwards; }
									.accordian-faqlist .accordian-heading .icon-heading i.fa-minus{ animation: flipicon 0.3s linear forwards; -webkit-animation: flipicon 0.3s linear forwards; }
									@keyframes flipicon{
									0%{display: block;}
									100%{transform: rotate(180deg); -webkit-transform: rotate(180deg);}
									}
									@-webkit-keyframes flipicon{
									0%{display: block;}
									100%{transform: rotate(180deg); -webkit-transform: rotate(180deg);}
									}.category_heading *{ font:inherit; margin: 0; padding:0; }
									.category_heading{ font-size: 22px; margin-bottom: 12px; display: block; text-transform: capitalize; }.xtreem_faq_main, .xtreem_faq_main *{ box-sizing: border-box; -webkit-box-sizing: border-box;}

									.accordian-faqlist > .accordian-heading{padding-left:55px; min-height:40px; padding-top:5px; padding-bottom:5px; padding-right:15px;}
									.icon-heading{ position:absolute;left:0; top:0; bottom:0; }
									',
            'accordian_js' => '<script type="text/javascript">
										    jQuery(document).ready(function(){
										        jQuery(".accordian-faqlist .accordian-heading").click(function(e) {
										            e.preventDefault();
										            var $this = jQuery(this);
										            if ($this.next().hasClass("show") || $this.hasClass("active")) {
										                $this.next().removeClass("show");
										                $this.next().slideUp(350);
										                 $this.removeClass("active");
										            } else {
										                $this.parent().parent().find(".accordian-faqlist .accordian-answer").removeClass("show"); 
										                $this.parent().parent().find(".accordian-faqlist .accordian-heading").removeClass("active");
										                $this.parent().parent().find(".accordian-faqlist .accordian-answer").slideUp(350);
										                $this.next().toggleClass("show"); 
										                $this.toggleClass("active");
										                $this.next().slideToggle(350);
										            }
										        });
										    });
										</script>',
            'toggle_js' => '<script type="text/javascript">
									    jQuery(document).ready(function(){
									        jQuery(".accordian-faqlist .accordian-heading").click(function(e) {
									            e.preventDefault();
									            var $this = jQuery(this);
									            $this.toggleClass("active");
									            $this.next().toggleClass("show").slideToggle(350); 
									        });
									    });
									</script>',
            'toggle_css' => 'TOGGLE_CSS'
                ), array(
            '%s',
            '%s',
            '%s',
            '%s'
                )
        );
    }
    if ($wpdb->get_var("show tables like '$wp_xtreem_table'") != $wp_xtreem_table) {

        $sql = "CREATE TABLE " . $wp_xtreem_table . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`category_title` enum('show','hide') DEFAULT 'show',
		`accordian` enum('accordian','toggle') DEFAULT 'accordian',
		`back_to_top` enum('Yes','No') DEFAULT 'No',
		`expand_collapse_all` enum('Yes','No') DEFAULT 'No',
		`display_author` enum('Yes','No') DEFAULT 'No',
		`display_date` enum('Yes','No') DEFAULT 'No',
		`display_all_answers` enum('Yes','No') DEFAULT 'No',
		`icon_color` varchar(255) NOT NULL,
		`faq_title` varchar(255) NULL,
		`faq_heading` varchar(255) NULL,
		`category_heading` varchar(255) NULL,
		`custom_css` text NULL,
		`icon_bg_color` varchar(255) NOT NULL,
		`question_color` varchar(255) NOT NULL,
		`question_bg_color` varchar(255) NOT NULL,
		`question_hover_color` varchar(255) NOT NULL,
		`question_active_bg_color` varchar(255) NOT NULL,
		`question_active_color` varchar(255) NOT NULL,
		`add_date` DATETIME NOT NULL,
		PRIMARY KEY id (id)
		);";
        dbDelta($sql);
        $wpdb->insert(
                $table_prefix . 'worx_faq', array(
            'faq_title' => 'All FAQs',
            'faq_heading' => 'h2',
            'category_heading' => 'h2',
            'icon_color' => '#fff',
            'icon_bg_color' => '#5f8f14',
            'question_color' => '#222',
            'question_bg_color' => '#fff',
            'question_hover_color' => '#aaaaaa',
            'question_active_bg_color' => '#eee',
            'question_active_color' => '#222',
            'add_date' => date("Y-m-d H:i:s")
                ), array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
                )
        );
    }
}

// remove plugin db tables on removing the plugin
function ADV_WFAQ_remove_table() {
    global $table_prefix, $wpdb;
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    $wp_xtreem_table = $table_prefix . "worx_faq";
    $wp_xtreem_file = $table_prefix . "worx_files";
    #Check to see if the table exists already, if yes, then remove it
    if ($wpdb->get_var("show tables like '$wp_xtreem_table'") == $wp_xtreem_table) {
        $sql = "DROP TABLE IF EXISTS $wp_xtreem_table";
        $wpdb->query($sql);
    }
    if ($wpdb->get_var("show tables like '$wp_xtreem_file'") == $wp_xtreem_file) {
        $sql2 = "DROP TABLE IF EXISTS $wp_xtreem_file";
        $wpdb->query($sql2);
    }
}

function ADV_WFAQ_Add_Category_Header($columns) {
    $columns['header_name'] = 'Shortcodes';
    $columns['header_name3'] = 'View Setting';
    unset($columns['description']);
    unset($columns['slug']);
    return $columns;
}

add_filter("manage_edit-wpwfaq_columns", 'ADV_WFAQ_Add_Category_Header', 10);

/**
 * To show the column value
 * @global type $wpdb
 * @param type $value
 * @param type $column_name
 * @param type $term_id
 * @return string
 */
function ADV_WFAQ_Add_Header_Content($value, $column_name, $term_id) {
    global $wpdb;
    if ($column_name == "header_name") {
        $term_name = get_term_by('id', $term_id, 'wpwfaq');
        $cat_name = $term_name->slug;
        $shortcode = "[worxfaq category='" . $cat_name . "']";
        return $shortcode;
    } else if ($column_name == "header_name3") {
        $view_url = admin_url("edit.php?post_type=worxfaq&page=worxFaq");
        $grp_settings = "<a href='" . esc_url($view_url) . "'>View</a>";
        return $grp_settings;
    }
}

add_action("manage_wpwfaq_custom_column", 'ADV_WFAQ_Add_Header_Content', 10, 3);