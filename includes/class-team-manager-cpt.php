<?php
namespace TeamManager;

if (!defined('ABSPATH')) {
    exit;
}

class CPT
{
    public static function register()
    {
        $labels = [
            'name'               => __('Team Members', 'team-manager'),
            'singular_name'      => __('Team Member', 'team-manager'),
            'add_new'            => __('Add New', 'team-manager'),
            'add_new_item'       => __('Add New Team Member', 'team-manager'),
            'edit_item'          => __('Edit Team Member', 'team-manager'),
            'new_item'           => __('New Team Member', 'team-manager'),
            'view_item'          => __('View Team Member', 'team-manager'),
            'search_items'       => __('Search Team Members', 'team-manager'),
            'not_found'          => __('No team members found', 'team-manager'),
            'not_found_in_trash' => __('No team members found in Trash', 'team-manager'),
            'menu_name'          => __('Team Members', 'team-manager'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'supports'           => ['title'],
            'has_archive'        => false,
            'rewrite'            => ['slug' => 'team-member'],
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-groups',
        ];

        register_post_type('team_member', $args);
    }
}
