<?php
namespace TeamManager;

use WP_REST_Response;
use WP_REST_Server;
use WP_Query;

if (!defined('ABSPATH')) {
    exit;
}

class Rest_Controller
{
    public static function register_routes()
    {
        register_rest_route(
            'v1',
            '/team-manager/members',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [__CLASS__, 'get_members'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'unit'  => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                    'limit' => [
                        'sanitize_callback' => 'absint',
                    ],
                ],
            ]
        );
    }

    public static function get_members($request)
    {
        $unit = $request->get_param('unit');
        $limit = $request->get_param('limit');

        $limit = absint($limit);
        if ($limit <= 0) {
            $limit = 100;
        }

        $args = [
            'post_type'      => 'team_member',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if (!empty($unit)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'work_unit',
                    'field'    => 'slug',
                    'terms'    => sanitize_title($unit),
                ],
            ];
        }

        $query = new WP_Query($args);
        $items = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $post_id = get_the_ID();
                $description = get_post_meta($post_id, Metaboxes::META_DESCRIPTION, true);
                $email = get_post_meta($post_id, Metaboxes::META_EMAIL, true);
                $linkedin = get_post_meta($post_id, Metaboxes::META_LINKEDIN, true);
                $terms = get_the_terms($post_id, 'work_unit');

                $unit_slug = '';

                if (!empty($terms) && !is_wp_error($terms)) {
                    $first_term = array_shift($terms);
                    if ($first_term) {
                        $unit_slug = $first_term->slug;
                    }
                }

                $items[] = [
                    'id'           => $post_id,
                    'title'        => get_the_title(),
                    'description'  => $description,
                    'email'        => $email,
                    'linkedin_url' => $linkedin,
                    'work_unit'    => $unit_slug,
                ];
            }

            wp_reset_postdata();
        }

        return new WP_REST_Response($items, 200);
    }
}
