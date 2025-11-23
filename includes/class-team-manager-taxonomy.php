<?php
namespace TeamManager;

if (!defined('ABSPATH')) {
    exit;
}

class Taxonomy
{
    public static function register()
    {
        $labels = [
            'name'              => __('Work Units', 'team-manager'),
            'singular_name'     => __('Work Unit', 'team-manager'),
            'search_items'      => __('Search Work Units', 'team-manager'),
            'all_items'         => __('All Work Units', 'team-manager'),
            'parent_item'       => __('Parent Work Unit', 'team-manager'),
            'parent_item_colon' => __('Parent Work Unit:', 'team-manager'),
            'edit_item'         => __('Edit Work Unit', 'team-manager'),
            'update_item'       => __('Update Work Unit', 'team-manager'),
            'add_new_item'      => __('Add New Work Unit', 'team-manager'),
            'new_item_name'     => __('New Work Unit Name', 'team-manager'),
            'menu_name'         => __('Work Units', 'team-manager'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'work-unit'],
        ];

        register_taxonomy('work_unit', ['team_member'], $args);
    }
}
