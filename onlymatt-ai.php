<?php
/**
 * Plugin Name: ONLYMATT AI Assistant
 * Plugin URI: https://om43.com
 * Description: Interface d'administration pour l'assistant AI ONLYMATT avec intégration Turso
 * Version: 1.0.0
 * Author: ONLYMATT
 * License: GPL v2 or later
 * Text Domain: onlymatt-ai
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('ONLYMATT_AI_VERSION', '1.0.0');
define('ONLYMATT_AI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ONLYMATT_AI_PLUGIN_URL', plugin_dir_url(__FILE__));

// Main plugin class
class OnlyMatt_AI_Plugin {

    public function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

        // AJAX handlers
        add_action('wp_ajax_onlymatt_chat', array($this, 'handle_chat_ajax'));
        add_action('wp_ajax_onlymatt_save_memory', array($this, 'handle_save_memory_ajax'));
        add_action('wp_ajax_onlymatt_get_tasks', array($this, 'handle_get_tasks_ajax'));
        add_action('wp_ajax_onlymatt_create_task', array($this, 'handle_create_task_ajax'));
        add_action('wp_ajax_onlymatt_save_settings', array($this, 'handle_save_settings_ajax'));

        // Shortcodes
        add_shortcode('onlymatt_chat', array($this, 'chat_shortcode'));
        add_shortcode('onlymatt_admin', array($this, 'admin_shortcode'));
        add_shortcode('onlymatt_hey_hi', array($this, 'hey_hi_shortcode'));
        add_shortcode('onlymatt_web_builder', array($this, 'web_builder_shortcode'));

        // REST API
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    public function init() {
        // Load textdomain
        load_plugin_textdomain('onlymatt-ai', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function add_admin_menu() {
        add_menu_page(
            'ONLYMATT AI',
            'ONLYMATT AI',
            'manage_options',
            'onlymatt-ai',
            array($this, 'admin_page'),
            'dashicons-robot',
            30
        );

        add_submenu_page(
            'onlymatt-ai',
            'Chat',
            'Chat',
            'manage_options',
            'onlymatt-ai-chat',
            array($this, 'chat_page')
        );

        add_submenu_page(
            'onlymatt-ai',
            'Tâches',
            'Tâches',
            'manage_options',
            'onlymatt-ai-tasks',
            array($this, 'tasks_page')
        );

        add_submenu_page(
            'onlymatt-ai',
            'Paramètres',
            'Paramètres',
            'manage_options',
            'onlymatt-ai-settings',
            array($this, 'settings_page')
        );
    }

    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'onlymatt-ai') === false) {
            return;
        }

        wp_enqueue_script('onlymatt-admin-js', ONLYMATT_AI_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), ONLYMATT_AI_VERSION, true);
        wp_enqueue_style('onlymatt-admin-css', ONLYMATT_AI_PLUGIN_URL . 'assets/css/admin.css', array(), ONLYMATT_AI_VERSION);

        wp_localize_script('onlymatt-admin-js', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
            'admin_key' => get_option('onlymatt_admin_key', 'test_key')
        ));
    }

    public function enqueue_frontend_scripts() {
        wp_enqueue_script('onlymatt-frontend-js', ONLYMATT_AI_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), ONLYMATT_AI_VERSION, true);
        wp_enqueue_style('onlymatt-frontend-css', ONLYMATT_AI_PLUGIN_URL . 'assets/css/frontend.css', array(), ONLYMATT_AI_VERSION);

        wp_localize_script('onlymatt-frontend-js', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
            'admin_key' => get_option('onlymatt_admin_key', 'test_key')
        ));
    }

    // Admin pages
    public function admin_page() {
        wp_localize_script('onlymatt-admin-js', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com')
        ));
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/admin-dashboard.php';
    }

    public function chat_page() {
        wp_localize_script('onlymatt-admin-js', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
            'admin_key' => get_option('onlymatt_admin_key', 'test_key')
        ));
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/admin-chat.php';
    }

    public function tasks_page() {
        wp_localize_script('onlymatt-admin-js', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
            'admin_key' => get_option('onlymatt_admin_key', 'test_key')
        ));
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/admin-tasks.php';
    }

    public function settings_page() {
        wp_localize_script('onlymatt-admin-js', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
            'admin_key' => get_option('onlymatt_admin_key', 'test_key')
        ));
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/admin-settings.php';
    }

    // Shortcodes
    public function chat_shortcode($atts) {
        // Ensure frontend scripts are loaded
        if (!wp_script_is('onlymatt-frontend-js', 'enqueued')) {
            wp_enqueue_script('onlymatt-frontend-js');
            wp_enqueue_style('onlymatt-frontend-css');
            wp_localize_script('onlymatt-frontend-js', 'onlymatt_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('onlymatt_nonce'),
                'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
                'admin_key' => get_option('onlymatt_admin_key', 'test_key')
            ));
        }

        ob_start();
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/frontend-chat.php';
        return ob_get_clean();
    }

    public function admin_shortcode($atts) {
        if (!current_user_can('manage_options')) {
            return '<p>Accès non autorisé.</p>';
        }

        // Ensure frontend scripts are loaded for admin shortcode
        if (!wp_script_is('onlymatt-frontend-js', 'enqueued')) {
            wp_enqueue_script('onlymatt-frontend-js');
            wp_enqueue_style('onlymatt-frontend-css');
            wp_localize_script('onlymatt-frontend-js', 'onlymatt_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('onlymatt_nonce'),
                'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
                'admin_key' => get_option('onlymatt_admin_key', 'test_key')
            ));
        }

        ob_start();
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/frontend-admin.php';
        return ob_get_clean();
    }

    public function hey_hi_shortcode($atts) {
        if (!wp_script_is('jquery', 'enqueued')) {
            wp_enqueue_script('jquery');
        }

        wp_localize_script('jquery', 'onlymatt_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onlymatt_nonce'),
            'site_info' => $this->get_site_knowledge()
        ));

        ob_start();
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/hey-hi-sphere.php';
        return ob_get_clean();
    }

    public function web_builder_shortcode($atts) {
        // Ensure frontend scripts are loaded
        if (!wp_script_is('onlymatt-frontend-js', 'enqueued')) {
            wp_enqueue_script('onlymatt-frontend-js');
            wp_enqueue_style('onlymatt-frontend-css');
            wp_localize_script('onlymatt-frontend-js', 'onlymatt_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('onlymatt_nonce'),
                'api_base' => get_option('onlymatt_api_base', 'https://your-app.onrender.com'),
                'admin_key' => get_option('onlymatt_admin_key', 'test_key')
            ));
        }

        ob_start();
        include ONLYMATT_AI_PLUGIN_DIR . 'templates/web-builder.php';
        return ob_get_clean();
    }

    public function get_site_knowledge() {
        // Collect comprehensive site information
        $site_info = array(
            'site_title' => get_bloginfo('name'),
            'site_description' => get_bloginfo('description'),
            'site_url' => get_site_url(),
            'current_page' => array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => get_post_type()
            ),
            'main_pages' => array(),
            'menu_structure' => array()
        );

        // Get main pages
        $main_pages = get_pages(array(
            'sort_column' => 'menu_order',
            'sort_order' => 'asc',
            'parent' => 0
        ));

        foreach ($main_pages as $page) {
            $site_info['main_pages'][] = array(
                'title' => $page->post_title,
                'url' => get_permalink($page->ID),
                'excerpt' => wp_trim_words($page->post_excerpt ?: $page->post_content, 20)
            );
        }

        // Get menu structure
        $menus = wp_get_nav_menus();
        foreach ($menus as $menu) {
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            $site_info['menu_structure'][$menu->name] = array();

            foreach ($menu_items as $item) {
                $site_info['menu_structure'][$menu->name][] = array(
                    'title' => $item->title,
                    'url' => $item->url,
                    'description' => $item->description
                );
            }
        }

        return $site_info;
    }

    // AJAX handlers
    public function handle_chat_ajax() {
        check_ajax_referer('onlymatt_nonce', 'nonce');

        $message = sanitize_text_field($_POST['message']);
        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_post($api_base . '/ai/chat', array(
            'body' => json_encode(array(
                'prompt' => $message,
                'model' => 'mistral',
                'temperature' => 0.7
            )),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('Erreur de connexion');
        } else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            wp_send_json_success($data);
        }
    }

    public function handle_save_memory_ajax() {
        check_ajax_referer('onlymatt_nonce', 'nonce');

        $user_id = sanitize_text_field($_POST['user_id']);
        $key = sanitize_text_field($_POST['key']);
        $value = sanitize_textarea_field($_POST['value']);
        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_post($api_base . '/ai/memory/remember', array(
            'body' => json_encode(array(
                'user_id' => $user_id,
                'persona' => 'wp_assistant',
                'key' => $key,
                'value' => $value
            )),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('Erreur de sauvegarde');
        } else {
            wp_send_json_success('Mémoire sauvegardée');
        }
    }

    public function handle_get_tasks_ajax() {
        check_ajax_referer('onlymatt_nonce', 'nonce');

        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_get($api_base . '/admin/tasks', array(
            'headers' => array(
                'X-OM-Key' => get_option('onlymatt_admin_key', 'test_key'),
            ),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('Erreur de récupération');
        } else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            wp_send_json_success($data);
        }
    }

    public function handle_create_task_ajax() {
        check_ajax_referer('onlymatt_nonce', 'nonce');

        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_textarea_field($_POST['description']);
        $priority = sanitize_text_field($_POST['priority']);
        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_post($api_base . '/admin/tasks', array(
            'body' => json_encode(array(
                'title' => $title,
                'description' => $description,
                'priority' => $priority
            )),
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-OM-Key' => get_option('onlymatt_admin_key', 'test_key'),
            ),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('Erreur de création');
        } else {
            wp_send_json_success('Tâche créée');
        }
    }

    public function handle_save_settings_ajax() {
        check_ajax_referer('onlymatt_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $settings = json_decode(stripslashes($_POST['settings']), true);

        if (!$settings) {
            wp_send_json_error('Invalid settings data');
        }

        // Save settings
        update_option('onlymatt_api_base', sanitize_url($settings['api_base'] ?? ''));
        update_option('onlymatt_admin_key', sanitize_text_field($settings['admin_key'] ?? ''));
        update_option('onlymatt_max_memory', intval($settings['max_memory'] ?? 100));
        update_option('onlymatt_enable_logging', $settings['enable_logging'] ? '1' : '0');
        update_option('onlymatt_enable_widget', $settings['enable_widget'] ? '1' : '0');

        wp_send_json_success('Settings saved');
    }

    // REST API routes
    public function register_rest_routes() {
        register_rest_route('onlymatt/v1', '/chat', array(
            'methods' => 'POST',
            'callback' => array($this, 'rest_chat'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route('onlymatt/v1', '/memory', array(
            'methods' => 'POST',
            'callback' => array($this, 'rest_save_memory'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route('onlymatt/v1', '/tasks', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_get_tasks'),
            'permission_callback' => '__return_true'
        ));
    }

    public function rest_chat($request) {
        $message = $request->get_param('message');
        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_post($api_base . '/ai/chat', array(
            'body' => json_encode(array('prompt' => $message)),
            'headers' => array('Content-Type' => 'application/json'),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Erreur de connexion', array('status' => 500));
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    public function rest_save_memory($request) {
        $params = $request->get_params();
        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_post($api_base . '/ai/memory/remember', array(
            'body' => json_encode($params),
            'headers' => array('Content-Type' => 'application/json'),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Erreur de sauvegarde', array('status' => 500));
        }

        return array('success' => true);
    }

    public function rest_get_tasks($request) {
        $api_base = get_option('onlymatt_api_base', 'https://your-app.onrender.com');

        $response = wp_remote_get($api_base . '/admin/tasks', array(
            'headers' => array(
                'X-OM-Key' => get_option('onlymatt_admin_key', 'test_key'),
            ),
            'timeout' => 15
        ));

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Erreur de récupération', array('status' => 500));
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }
}

// Initialize plugin
new OnlyMatt_AI_Plugin();

// Activation hook
register_activation_hook(__FILE__, 'onlymatt_ai_activate');
function onlymatt_ai_activate() {
    // Set default options
    add_option('onlymatt_api_base', 'https://your-app.onrender.com');
    add_option('onlymatt_admin_key', 'test_key');

    // Create necessary directories
    $dirs = array(
        ONLYMATT_AI_PLUGIN_DIR . 'assets/css',
        ONLYMATT_AI_PLUGIN_DIR . 'assets/js',
        ONLYMATT_AI_PLUGIN_DIR . 'templates'
    );

    foreach ($dirs as $dir) {
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'onlymatt_ai_deactivate');
function onlymatt_ai_deactivate() {
    // Cleanup if needed
}