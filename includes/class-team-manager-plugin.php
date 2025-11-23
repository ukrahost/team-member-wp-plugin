<?php
namespace TeamManager;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin
{
    private static $instance;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies()
    {
        require_once TEAM_MANAGER_PLUGIN_DIR . 'includes/class-team-manager-cpt.php';
        require_once TEAM_MANAGER_PLUGIN_DIR . 'includes/class-team-manager-taxonomy.php';
        require_once TEAM_MANAGER_PLUGIN_DIR . 'includes/class-team-manager-metaboxes.php';
        require_once TEAM_MANAGER_PLUGIN_DIR . 'includes/class-team-manager-shortcode.php';
        require_once TEAM_MANAGER_PLUGIN_DIR . 'includes/class-team-manager-rest-controller.php';
    }

    private function init_hooks()
    {
        add_action('init', [$this, 'register_cpt_and_taxonomy']);
        add_action('add_meta_boxes', [$this, 'register_meta_boxes']);
        add_action('save_post_team_member', [$this, 'save_meta_boxes'], 10, 2);
        add_action('delete_post', [$this, 'delete_meta_on_post_delete']);
        add_action('init', [$this, 'register_shortcode']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function register_cpt_and_taxonomy()
    {
        CPT::register();
        Taxonomy::register();
    }

    public function register_meta_boxes()
    {
        Metaboxes::register();
    }

    public function save_meta_boxes($post_id, $post)
    {
        Metaboxes::save($post_id, $post);
    }

    public function delete_meta_on_post_delete($post_id)
    {
        Metaboxes::delete($post_id);
    }

    public function register_shortcode()
    {
        Shortcode::register();
    }

    public function register_rest_routes()
    {
        Rest_Controller::register_routes();
    }

    public function enqueue_assets()
    {
        if (is_admin()) {
            return;
        }

        wp_enqueue_style(
            'team-manager-frontend',
            TEAM_MANAGER_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            TEAM_MANAGER_VERSION
        );
    }
}
