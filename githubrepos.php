<?php
defined('ABSPATH') or die('Inaccessible');
/*
Plugin Name: GitHub Repos
Plugin URI: http://cordine.site/
Description:Improved Nav bar placement
Version:0.0
Author: Christopher Cordine
Author URI: https://cordine.site
License: MIT
*/
global $wpdb;

// PLUGIN VERSION
defined('GH_REPOS_VERSION') or define('GH_REPOS_VERSION', '0.0.1');
// PLUGIN ADMIN SLUG
defined('GH_REPOS_SLUG') or define('GH_REPOS_SLUG', 'gh_repos');
// PLUGIN'S TEXT DOMAIN
defined('GH_REPOS_TD') or define('GH_REPOS_TD', 'gh_repos');
// PLUGIN IMAGE DIRECTORY
defined('GH_REPOS_IMG_DIR') or define('GH_REPOS_IMG_DIR', plugin_dir_url(__FILE__) . 'images');
// PLUGIN DIRECTORY URL
defined('GH_REPOS_URL') or define('GH_REPOS_URL', plugin_dir_url(__FILE__));
// PLUGIN JS DIRECTORY
defined('GH_REPOS_JS_DIR') or define('GH_REPOS_JS_DIR', plugin_dir_url(__FILE__) . 'js');
// PLUGIN CSS DIRECTORY
defined('GH_REPOS_CSS_DIR') or define('GH_REPOS_CSS_DIR', plugin_dir_url(__FILE__) . 'css');

if (!class_exists('GH_REPOS_CLASS')) {
    class GH_REPOS_CLASS
    {
        public $plugin;
        private $options;

        function __construct()
        {
            $this->plugin = plugin_basename(__FILE__);
            // 
            // Add Item(s) to menu bar
            add_action('admin_bar_menu', array($this, 'gh_repos_add_toolbar_items'), 100);
            // 
            // Include scripts necessary to run, Register front end assets
            add_action('wp_enqueue_scripts', array($this, 'gh_repos_include_scripts'));
            // 
            // Add shortcodes
            add_shortcode('display_gh_repos', array($this, 'gh_repos_display_repos'));
            // 
            // Add Tables to Database
            register_activation_hook(__FILE__, array($this, 'gh_repos_database_setup'));
        }
        // Add to toolbar
        public function gh_repos_add_toolbar_items($admin_bar)
        {
            $admin_bar->add_menu(array(
                'id' => 'gh_repos_adminbar',
                'title' => 'GitHub Repos',
                'meta' => array(
                    'title' => 'GH Repos Plugin'
                )
            ));
        }
        // Register Front End Assets
        public function gh_repos_include_scripts()
        {
        }
        // Database Setup
        public function gh_repos_database_setup()
        {
        }
        // Shortcodes
        public function gh_repos_display_repos()
        {
        }
    }
    $gh_repos_obj = new GH_REPOS_CLASS();
}
