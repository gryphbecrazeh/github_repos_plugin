<?php
/*



*/
defined('ABSPATH') or die('Inaccessible');
/*
Plugin Name: GitHub Repos
Plugin URI: http://cordine.site/
Description:Display Github Repos
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
        // 
        // Add to toolbar
        // 
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
        // 
        // Register Front End Assets
        // 
        private function register_frontend_assets()
        {
            $frontend_js_obj = array(

                'default_error_message' => __('This field is required', GH_REPOS_TD),

                'ajax_url' => admin_url('admin-ajax.php'),

                'ajax_nonce' => wp_create_nonce('frontend-ajax-nonce'),

                'preview_img' => GH_REPOS_IMG_DIR . '/no-preview.png'

            );
            wp_localize_script('frontend.js', 'frontend_js_obj', $frontend_js_obj);
        }
        // 
        // Include Scripts
        // 
        public function gh_repos_include_scripts()
        {
            wp_enqueue_script('frontend.js', plugins_url('/js/frontend.js', __FILE__));
            $this->register_frontend_assets();
        }
        // 
        // Database Setup
        // 
        public function gh_repos_database_setup()
        {
        }
        // 
        // Shortcodes
        // 
        public function gh_repos_display_repos($attributes = [], $content = null, $tag = '')
        {
            require_once(__DIR__ . '/includes/github.php');
            // 
            // Convert all attribute keys to lowercase
            // 
            $attributes = array_change_key_case((array) $atts, CASE_LOWER);

            $atts = shortcode_atts(array(
                'user' => 'default',
                'sort' => true,
                'sort_direction' => true,
                'link_repo' => true
            ), $attributes);
            // 
            // Override default attributes with user attributes
            // 
            $user = $atts['user'];
            $link_repo = $atts['link_repo'];
            // 
            // Begin output buffering
            // 
            ob_start();
            // 
            // Open list element
            // 
            $gh = new GH_REPOS_GITHUB($user);

?>
            <ul class="gh_repos_list">

                <?php
                $repos = $gh->getRepos();
                // 
                // If there are no results, output no items found, end the loop
                // 
                if (!$repos) {
                ?>
                    <li class='gh_repos_item'>No items found...</li>
                <?php
                    // 
                    // Display the output and clear the buffer
                    // 
                    ob_flush();
                    ob_clean();
                    return 0;
                }
                // 
                // Reder out all repositories
                // 
                foreach ($repos as $repo) {
                ?>
                    <!-- <pre><?php echo var_export($repo, true) ?></pre> -->
                    <li class="gh_repos_item">
                        <a href="<?php echo $repo->html_url ?>"><?php echo $repo->name ?></a>
                        <!-- <span class="updatedAt"><?php echo $repo->updated_at ?></span> -->
                    </li>
                <?php
                }

                ?>

            </ul>

<?php
            // 
            // Display the output and clear the buffer
            // 
            ob_flush();
            ob_clean();
        }
    }
    $gh_repos_obj = new GH_REPOS_CLASS();
}
