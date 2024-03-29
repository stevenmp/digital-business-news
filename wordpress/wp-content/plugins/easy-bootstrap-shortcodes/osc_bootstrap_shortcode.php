<?php

/*
  Plugin Name: Easy Bootstrap Shortcode
  Plugin URI: http://www.oscitasthemes.com
  Description: Add bootstrap 3.0 styles to your theme by wordpress editor shortcode buttons.
  Version: 3.2.0
  Author: oscitas
  Author URI: http://www.oscitasthemes.com
  License: Under the GPL v2 or later
 */
function osc_ebs_plugin_exists( $prevent ) {
    return 'ebs';
}
$checkplugin=apply_filters('osc_ebs_pro_plugin_exists',false);
if(isset($checkplugin) && $checkplugin=='ebsp'):

    function ebs_init_sessions()
    {
        if (!session_id()) {
            session_start();
        }
    }

    add_action('init', 'ebs_init_sessions', 1);

    add_action('admin_notices', 'ebs_showAdminMessages');

    function ebs_showMessage($message, $errormsg = false)
    {
        if ($errormsg) {
            echo '<div id="message" class="error ebs_notification">';
        }
        else {
            echo '<div id="message" class="update-nag ebs_notification">';
        }
        echo '<p><strong>' . $message . '</strong></p></div>';
    }

    function ebs_showAdminMessages()
    {
        ebs_showMessage("As you already installed Easy Bootstrap Shortcode Pro plugin, please deactivate Easy Bootstrap Shortcode free version", false);
    }
else:
    add_filter( 'osc_ebs_plugin_exists', 'osc_ebs_plugin_exists' );
    define('EBS_PLUGIN_URL',plugins_url('/',__FILE__));
    define('EBS_JS_CDN','http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js');
    define('EBS_RESPOND_CDN','http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js');


    add_action('admin_enqueue_scripts', 'osc_add_admin_ebs_scripts');
    add_action('admin_menu', 'osc_ebs_add_admin_menu');
    add_action('wp_enqueue_scripts', 'osc_add_dynamic_css',100);
    add_filter('mce_external_plugins', 'osc_editor_enable_mce');
    if(!apply_filters('plugin_oscitas_theme_check',false)){
        add_action('wp_enqueue_scripts', 'osc_add_frontend_ebs_scripts',-100);
    }



    register_activation_hook(__FILE__, 'osc_ebs_activate_plugin');
    register_deactivation_hook(__FILE__, 'osc_ebs_deactivate_plugin');


    function osc_ebs_activate_plugin() {
        $isSet=apply_filters('ebs_custom_option',false);
        if (!$isSet) {

            // EBS_BOOTSTRAP_JS_LOCATION   '1' - for plugin file, '2' - don't user EBS files but use from other plugin or theme, '3' - to user CDN path
            update_option( 'EBS_BOOTSTRAP_JS_LOCATION', 1 );
            update_option( 'EBS_BOOTSTRAP_JS_CDN_PATH', EBS_JS_CDN );
            update_option( 'EBS_BOOTSTRAP_RESPOND_CDN_PATH', EBS_RESPOND_CDN );
            // EBS_BOOTSTRAP_RESPOND_LOCATION   '1' - for plugin file, '2' - don't user EBS files but use from other plugin or theme, '3' - to user CDN path
            update_option('EBS_BOOTSTRAP_RESPOND_LOCATION',2);

            // EBS_BOOTSTRAP_CSS_LOCATION   '1' - for plugin file, '2' - don't user EBS files but use from other plugin or theme
            update_option( 'EBS_BOOTSTRAP_CSS_LOCATION', 1 );
            update_option( 'EBS_EDITOR_OPT','icon');
            update_option( 'EBS_EDITOR_OPT','icon');
            if(get_option('EBS_CUSTOM_CSS')==''){
                update_option( 'EBS_CUSTOM_CSS','');
            }
        }

    }
    function osc_ebs_settings_link( $links ) {
        $isSet=apply_filters('ebs_custom_option',false);
        if (!$isSet) {
            $settings_link = '<a href="admin.php?page=ebs/ebs-settings.php">Settings</a>';
            array_push( $links, $settings_link );
        }
        return $links;
    }

    add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), 'osc_ebs_settings_link' );

    function osc_ebs_deactivate_plugin() {
        $isSet=apply_filters('ebs_custom_option',false);
        if (!$isSet) {
            delete_option( 'EBS_BOOTSTRAP_JS_LOCATION' );
            delete_option( 'EBS_BOOTSTRAP_JS_CDN_PATH' );
            delete_option( 'EBS_BOOTSTRAP_CSS_LOCATION');
            delete_option( 'EBS_BOOTSTRAP_RESPOND_LOCATION' );
            delete_option( 'EBS_BOOTSTRAP_RESPOND_CDN_PATH' );
            delete_option('EBS_EDITOR_OPT');
        }
    }

    function osc_ebs_add_admin_menu() {
        $isSet=apply_filters('ebs_custom_option',false);
        if (!$isSet) {
            add_menu_page('EBS Settings', ' EBS Settings', 'manage_options', 'ebs/ebs-settings.php', 'osc_ebs_setting_page', plugins_url('/images/icon.png', __FILE__));
        }
    }

    function osc_ebs_setting_page() {
        if (isset($_POST['ebs_submit'])) {
            update_option( 'EBS_BOOTSTRAP_JS_LOCATION', isset($_POST['b_js'])?$_POST['b_js']:1 );
            update_option( 'EBS_BOOTSTRAP_JS_CDN_PATH',isset($_POST['cdn_path'])? $_POST['cdn_path']:EBS_JS_CDN );
            update_option( 'EBS_BOOTSTRAP_CSS_LOCATION', isset($_POST['b_css'])?$_POST['b_css']:1 );
            update_option( 'EBS_BOOTSTRAP_RESPOND_LOCATION', isset($_POST['respond_js'])?$_POST['respond_js']:2 );
            update_option( 'EBS_BOOTSTRAP_RESPOND_CDN_PATH', isset($_POST['respond_cdn_path'])?$_POST['respond_cdn_path']:EBS_RESPOND_CDN );
            update_option( 'EBS_EDITOR_OPT', isset($_POST['ebsp_editor_opt'])?$_POST['ebsp_editor_opt']:'icon' );
            update_option( 'EBS_CUSTOM_CSS', isset($_POST['ebs_custom_css'])?$_POST['ebs_custom_css']:'' );

            $_SESSION['ebs_dynamic_css'] = $_POST['ebs_custom_css'];
            $js =isset($_POST['b_js'])?$_POST['b_js']:1;
            $cdn = isset($_POST['cdn_path'])? $_POST['cdn_path']:EBS_JS_CDN;
            $css = isset($_POST['b_css'])?$_POST['b_css']:1;
            $respond = isset($_POST['respond_js'])?$_POST['respond_js']:2;
            $respondcdn = isset($_POST['respond_cdn_path'])?$_POST['respond_cdn_path']:EBS_RESPOND_CDN;
            $ebsp_editor_opt=isset($_POST['ebsp_editor_opt'])?$_POST['ebsp_editor_opt']:'icon' ;
            $ebs_custom_css=isset($_POST['ebs_custom_css'])?$_POST['ebs_custom_css']:'' ;

        } else {
            $js = get_option( 'EBS_BOOTSTRAP_JS_LOCATION', 1 );
            $cdn = get_option( 'EBS_BOOTSTRAP_JS_CDN_PATH', EBS_JS_CDN );
            $css = get_option( 'EBS_BOOTSTRAP_CSS_LOCATION', 1 );
            $respond = get_option( 'EBS_BOOTSTRAP_RESPOND_LOCATION', 2 );
            $respondcdn = get_option( 'EBS_BOOTSTRAP_RESPOND_CDN_PATH', EBS_RESPOND_CDN );
            $ebsp_editor_opt=get_option('EBS_EDITOR_OPT','icon');
            $ebs_custom_css=get_option('EBS_CUSTOM_CSS','');
        }
        include 'ebs_settings.php';
    }

    add_action('admin_head', 'osc_ebs_ajax_ul');
    function osc_ebs_ajax_ul(){
        $ebsp_editor_opt=get_option('EBS_EDITOR_OPT','icon');

        ?>
        <script type="text/javascript">
            var ebs_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var ebs_url='<?php echo EBS_PLUGIN_URL;?>';
            var ebs_editor_opt='<?php echo $ebsp_editor_opt; ?>'

        </script>
    <?php
    }
// add_submenu_page('optine
    function osc_add_admin_ebs_scripts() {
        global $pagenow;
        $screen = get_current_screen();
        if ($screen->id == 'toplevel_page_ebs/ebs-settings') {
            wp_enqueue_style('ebs-setting', plugins_url('/styles/ebs-setting.min.css', __FILE__));
        }
        wp_enqueue_script('ebs-main', plugins_url('/js/ebs_main.js', __FILE__));

    }

    function osc_editor_enable_mce(){
        wp_enqueue_script('jquery');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_style('EBS_jquery-ui-slider-css', plugins_url('/styles/slider.css', __FILE__));
        if (!apply_filters('ebs_bootstrap_icon_css_url',false)) {
            wp_enqueue_style('bootstrap-icon', plugins_url('/styles/bootstrap-icon.min.css', __FILE__));
        } else{
            wp_enqueue_style('bootstrap-icon', apply_filters('ebs_bootstrap_icon_css_url',false));
        }
        if (!apply_filters('ebs_custom_bootstrap_admin_css',false)) {
            wp_enqueue_style('ebs_bootstrap_admin', plugins_url('/styles/bootstrap_admin.min.css', __FILE__));
        }

    }

    function osc_add_dynamic_css(){
        wp_enqueue_style('ebs_dynamic_css', plugins_url('/styles/ebs_dynamic_css.php', __FILE__));

    }
    function osc_add_frontend_ebs_scripts() {
        wp_enqueue_script('jquery');
        $isSet=apply_filters('ebs_custom_option',false);
        if (!$isSet) {
            $js = get_option( 'EBS_BOOTSTRAP_JS_LOCATION', 1 );
            $respond = get_option( 'EBS_BOOTSTRAP_RESPOND_LOCATION', 2 );
            $cdn = get_option( 'EBS_BOOTSTRAP_JS_CDN_PATH', EBS_JS_CDN );
            $respondcdn = get_option( 'EBS_BOOTSTRAP_RESPOND_CDN_PATH', EBS_RESPOND_CDN );
            $css = get_option( 'EBS_BOOTSTRAP_CSS_LOCATION', 1 );

//			http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js


            if ($js == 1) {
                if (!apply_filters('ebs_bootstrap_js_url',false)) {
                    wp_enqueue_script('bootstrap', plugins_url('/js/bootstrap.min.js', __FILE__));
                } else{
                    wp_enqueue_script('bootstrap', apply_filters('ebs_bootstrap_js_url',false));
                }
            } elseif ($js == 3) {
                if (!apply_filters('ebs_bootstrap_js_cdn',false)) {
                    wp_enqueue_script('bootstrap', $cdn);
                } else{
                    wp_enqueue_script('bootstrap', apply_filters('ebs_bootstrap_js_cdn',false));
                }
            }
            if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])){
                if ($respond == 1) {
                    if (!apply_filters('ebs_bootstrap_respond_url',false)) {
                        wp_enqueue_script('bootstrap_respond', plugins_url('/js/respond.min.js', __FILE__));
                    } else{
                        wp_enqueue_script('bootstrap_respond', apply_filters('ebs_bootstrap_respond_url',false));
                    }
                } elseif ($respond == 3) {
                    if (!apply_filters('ebs_bootstrap_respond_cdn',false)) {
                        wp_enqueue_script('bootstrap_respond', $respondcdn);
                    } else{
                        wp_enqueue_script('bootstrap_respond', apply_filters('ebs_bootstrap_respond_cdn',false));
                    }
                }
            }
            if ($css == 1) {
                if (!apply_filters('ebs_bootstrap_css_url',false)) {
                    wp_enqueue_style('bootstrap', plugins_url('/styles/bootstrap.min.css', __FILE__));
                } else {
                    wp_enqueue_style('bootstrap', apply_filters('ebs_bootstrap_css_url',false));
                }
            }
            elseif($css==3){
                if (!apply_filters('ebs_no_bootstrap_theme_css_url',false)) {
                    wp_enqueue_style('bootstrap', plugins_url('/styles/bootstrap-oscitas.css', __FILE__));
                } else {
                    wp_enqueue_style('bootstrap', apply_filters('ebs_no_bootstrap_theme_css_url',false));
                }

            }
            else {
                if (!apply_filters('ebs_bootstrap_icon_css_url',false)) {
                    //wp_enqueue_style('bootstrap-icon', plugins_url('/styles/bootstrap-icon.min.css', __FILE__));
                } else{
                    wp_enqueue_style('bootstrap-icon', apply_filters('ebs_bootstrap_icon_css_url',false));
                }
            }
        }
    }

// Shortcodes
    include('shortcode/functions.php');


endif;