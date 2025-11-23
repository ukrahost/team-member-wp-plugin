<?php
namespace TeamManager;

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode
{
    public static function register()
    {
        add_shortcode('team_members_grid', [__CLASS__, 'render']);
    }

    public static function render($atts)
    {
        $atts = shortcode_atts(
            [
                'unit'  => '',
                'count' => 9,
            ],
            $atts,
            'team_members_grid'
        );

        $count = absint($atts['count']);
        if ($count <= 0) {
            $count = 9;
        }

        $args = [
            'post_type'      => 'team_member',
            'post_status'    => 'publish',
            'posts_per_page' => $count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if ($atts['unit'] !== '') {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'work_unit',
                    'field'    => 'slug',
                    'terms'    => sanitize_title($atts['unit']),
                ],
            ];
        }

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            return '';
        }

        ob_start();

        echo '<div class="team-manager-grid">';

        while ($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();
            $description = get_post_meta($post_id, Metaboxes::META_DESCRIPTION, true);
            $email = get_post_meta($post_id, Metaboxes::META_EMAIL, true);
            $linkedin = get_post_meta($post_id, Metaboxes::META_LINKEDIN, true);
            $terms = get_the_terms($post_id, 'work_unit');

            $unit_names = [];
            if (!empty($terms) && !is_wp_error($terms)) {
                $unit_names = wp_list_pluck($terms, 'name');
            }

            $unit_label = '';
            if (!empty($unit_names)) {
                $unit_label = implode(', ', $unit_names);
            }

            echo '<div class="team-manager-card">';
            echo '<h3 class="team-manager-name">' . esc_html(get_the_title()) . '</h3>';

            if ($unit_label !== '') {
                echo '<div class="team-manager-unit">' . esc_html($unit_label) . '</div>';
            }

            if ($description !== '') {
                echo '<div class="team-manager-description">' . esc_html($description) . '</div>';
            }

            if ($email !== '') {
                echo '<div class="team-manager-email">' . esc_html($email) . '</div>';
            }

            if ($linkedin !== '') {
                echo '<a class="team-manager-linkedin" href="' . esc_url($linkedin) . '" target="_blank" rel="noopener noreferrer">';
                echo '<span class="team-manager-linkedin-label">LinkedIn</span>';
                echo '</a>';
            }

            echo '</div>';
        }

        echo '</div>';

        wp_reset_postdata();

        return ob_get_clean();
    }
}
